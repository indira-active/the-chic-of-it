<?php

if (!defined('ABSPATH')) {
    exit();
}

class WpdiscuzCache implements WpDiscuzConstants {

    public $gravatars;
    public $wpUploadDir;
    private $avBaseDir;
    private $currentTime;
    private $timeout;
    private $optionsSerialized;
    private $helper;
    private $dbManager;

    public function __construct($optionsSerialized, $helper, $dbManager) {
        $this->optionsSerialized = $optionsSerialized;
        $this->helper = $helper;
        $this->dbManager = $dbManager;
        $this->gravatars = array();
        $this->wpUploadDir = wp_upload_dir();
        $this->avBaseDir = $this->wpUploadDir['basedir'] . self::GRAVATARS_CACHE_DIR;
        $this->currentTime = current_time('timestamp');
        $this->timeout = $this->optionsSerialized->gravatarCacheTimeout * DAY_IN_SECONDS;
        wp_mkdir_p($this->avBaseDir);
        add_filter('pre_get_avatar', array(&$this, 'preGetGravatar'), 10, 3);
        if ($this->optionsSerialized->gravatarCacheMethod == 'runtime') {
            add_filter('get_avatar_url', array(&$this, 'gravatarsRunTime'), 10, 3);
        } else {
            add_filter('get_avatar_url', array(&$this, 'gravatarsCronJob'), 10, 3);
        }
        add_action('admin_post_purgeExpiredGravatarsCaches', array(&$this, 'purgeExpiredCaches'));
        add_action('admin_post_purgeGravatarsCaches', array(&$this, 'purgeCaches'));
    }

    public function preGetGravatar($avatar, $idOrEmail, $args) {
        if ($idOrEmail && $args &&
                isset($args['wpdiscuz_gravatar_field']) &&
                $args['wpdiscuz_gravatar_field'] != ''
        ) {
            $cacheFileUrl = $this->wpUploadDir['baseurl'] . self::GRAVATARS_CACHE_DIR;
            $md5FileName = md5($args['wpdiscuz_gravatar_field']);
            $fileNameHash = $md5FileName . '.gif';
            $fileDirHash = $this->avBaseDir . $fileNameHash;
            if (file_exists($fileDirHash)) {
                $fileUrlHash = $cacheFileUrl . $fileNameHash;
                $url = $fileUrlHash;
                $url2x = $fileUrlHash;
                $class = array('avatar', 'avatar-' . (int) $args['size'], 'photo');
                if ($args['force_default']) {
                    $class[] = 'avatar-default';
                }

                if ($args['class']) {
                    if (is_array($args['class'])) {
                        $class = array_merge($class, $args['class']);
                    } else {
                        $class[] = $args['class'];
                    }
                }

                $avatar = sprintf(
                        "<img alt='%s' src='%s' srcset='%s' class='%s' height='%d' width='%d' %s/>", esc_attr($args['alt']), esc_url($url), esc_attr("$url2x 2x"), esc_attr(join(' ', $class)), (int) $args['height'], (int) $args['width'], $args['extra_attr']
                );
            }
        }
        return $avatar;
    }

    public function gravatarsRunTime($url, $idOrEmail, $args) {
        if ($url && $idOrEmail && $args &&
                isset($args['wpdiscuz_gravatar_field']) &&
                isset($args['size']) &&
                isset($args['wpdiscuz_gravatar_size']) &&
                $args['wpdiscuz_gravatar_field'] != '' &&
                $args['size'] == $args['wpdiscuz_gravatar_size'] &&
                $fileData = @file_get_contents($url)
        ) {
            $md5FileName = md5($args['wpdiscuz_gravatar_field']);
            $fileNameHash = $md5FileName . '.gif';
            $cacheFile = $this->avBaseDir . $fileNameHash;
            if (@file_put_contents($cacheFile, $fileData)) {
                $url = $this->avBaseDir . $fileNameHash;
                $this->gravatars[$md5FileName] = array(
                    'user_id' => intval($args['wpdiscuz_gravatar_user_id']),
                    'user_email' => trim($args['wpdiscuz_gravatar_user_email']),
                    'url' => trim($url),
                    'hash' => trim($md5FileName),
                    'cached' => 1
                );
            }
        }
        return $url;
    }

    public function gravatarsCronJob($url, $idOrEmail, $args) {
        if ($url && $idOrEmail && $args &&
                isset($args['wpdiscuz_gravatar_field']) &&
                isset($args['size']) &&
                isset($args['wpdiscuz_gravatar_size']) &&
                $args['wpdiscuz_gravatar_field'] != '' &&
                $args['size'] == $args['wpdiscuz_gravatar_size']
        ) {
            $md5FileName = md5($args['wpdiscuz_gravatar_field']);
            $this->gravatars[$md5FileName] = array(
                'user_id' => intval($args['wpdiscuz_gravatar_user_id']),
                'user_email' => trim($args['wpdiscuz_gravatar_user_email']),
                'url' => trim($url),
                'hash' => trim($md5FileName),
                'cached' => 0
            );
        }
        return $url;
    }

    public function cacheGravatars() {
        $gravatars = $this->dbManager->getGravatars();
        if ($gravatars) {
            $cachedIds = array();
            foreach ($gravatars as $gravatar) {
                $id = $gravatar['id'];
                $url = $gravatar['url'];
                $hash = $gravatar['hash'];
                if ($fileData = @file_get_contents($url)) {
                    $cacheFile = $this->avBaseDir . $hash . '.gif';
                    if (@file_put_contents($cacheFile, $fileData)) {
                        $cachedIds[] = $id;
                    }
                }
            }
            $this->dbManager->updateGravatarsStatus($cachedIds);
        }
    }

    public function purgeExpiredCaches() {
        if (current_user_can('manage_options') && isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'purgeExpiredGravatarsCaches')) {
            $timeFrame = $this->optionsSerialized->gravatarCacheTimeout * DAY_IN_SECONDS;
            $expiredGravatars = $this->dbManager->getExpiredGravatars($timeFrame);
            if ($expiredGravatars) {
                $files = function_exists('scandir') ? scandir($this->avBaseDir) : false;
                if ($files) {
                    foreach ($files as $file) {
                        if (in_array($file, $expiredGravatars)) {
                            @unlink($this->avBaseDir . $file);
                        }
                    }
                }
                $this->dbManager->deleteExpiredGravatars($timeFrame);
            }
        }
        wp_redirect(admin_url('edit-comments.php?page=' . WpdiscuzCore::PAGE_SETTINGS));
    }

    public function purgeCaches() {
        if (current_user_can('manage_options') && isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'purgeGravatarsCaches')) {
            $files = function_exists('scandir') ? scandir($this->avBaseDir) : false;
            if ($files) {
                foreach ($files as $file) {
                    if ($file != '.' && $file != '..' && $file != '.htaccess') {
                        @unlink($this->avBaseDir . $file);
                    }
                }
            }
            $this->dbManager->deleteGravatars();
        }
        wp_redirect(admin_url('edit-comments.php?page=' . WpdiscuzCore::PAGE_SETTINGS));
    }

}
