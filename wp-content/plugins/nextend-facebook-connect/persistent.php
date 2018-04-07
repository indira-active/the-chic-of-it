<?php

add_action('plugins_loaded', 'NextendSocialLoginPersistentAnonymous::plugins_loaded');

class NextendSocialLoginPersistentAnonymous {

    /**
     * @var string name of the cookie. Can be changed with nsl_session_name filter and NSL_SESSION_NAME constant.
     *
     * @see https://pantheon.io/docs/caching-advanced-topics/
     */
    private static $sessionName = 'SESSnsl';

    private static $verifiedSession = false;

    public static function plugins_loaded() {
        if (defined('NSL_SESSION_NAME')) {
            self::$sessionName = NSL_SESSION_NAME;
        }
        self::$sessionName = apply_filters('nsl_session_name', self::$sessionName);
    }

    public static function hasSession() {
        if (self::$verifiedSession !== false) {
            return true;
        }
        if (isset($_COOKIE[self::$sessionName])) {
            if (get_site_transient('n_' . $_COOKIE[self::$sessionName]) !== false) {
                self::$verifiedSession = $_COOKIE[self::$sessionName];

                return true;
            }
        }

        return false;
    }

    private static function getSessionID($mustCreate = false) {
        if (self::hasSession()) {
            return self::$verifiedSession;
        }
        if ($mustCreate) {
            self::$verifiedSession = uniqid('nsl', true);

            self::setcookie(self::$sessionName, self::$verifiedSession, time() + DAY_IN_SECONDS, apply_filters('nsl_session_use_secure_cookie', false));
            set_site_transient('n_' . self::$verifiedSession, 1, 3600);

            return self::$verifiedSession;
        }

        return false;
    }

    public static function set($key, $value, $expiration = 3600) {

        set_site_transient(self::getSessionID(true) . $key, (string)$value, $expiration);
    }

    public static function get($key) {

        $session = self::getSessionID();
        if ($session) {
            return get_site_transient($session . $key);
        }

        return false;
    }

    public static function delete($key) {

        $session = self::getSessionID();
        if ($session) {
            delete_site_transient(self::getSessionID() . $key);
        }
    }

    public static function destroy() {
        $sessionID = self::getSessionID();
        if ($sessionID) {
            self::setcookie(self::$sessionName, $sessionID, time() - YEAR_IN_SECONDS, apply_filters('nsl_session_use_secure_cookie', false));

            add_action('shutdown', 'NextendSocialLoginPersistentAnonymous::destroy_site_transient');
        }
    }

    public static function destroy_site_transient() {
        $sessionID = self::getSessionID();
        if ($sessionID) {
            delete_site_transient('n_' . $sessionID);
        }
    }

    private static function setcookie($name, $value, $expire, $secure = false) {

        setcookie($name, $value, $expire, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, $secure);
    }

}

class NextendSocialLoginPersistentUser {

    private static function getSessionID() {
        return get_current_user_id();
    }

    public static function set($key, $value, $expiration = 3600) {

        set_site_transient(self::getSessionID() . $key, (string)$value, $expiration);
    }

    public static function get($key) {

        return get_site_transient(self::getSessionID() . $key);
    }

    public static function delete($key) {

        delete_site_transient(self::getSessionID() . $key);
    }

}