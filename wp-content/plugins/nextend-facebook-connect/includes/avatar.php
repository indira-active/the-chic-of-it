<?php

class NextendSocialLoginAvatar {

    public static function init() {
        add_action('nsl_update_avatar', 'NextendSocialLoginAvatar::updateAvatar', 10, 3);
    }

    /**
     * @param NextendSocialProvider $provider
     * @param                       $user_id
     * @param                       $avatarUrl
     */
    public static function updateAvatar($provider, $user_id, $avatarUrl) {
        global $blog_id, $wpdb;
        if (defined('WPUA_VERSION') && !empty($avatarUrl)) {
            // WP User Avatar https://wordpress.org/plugins/wp-user-avatar/

            $original_attachment_id = get_user_meta($user_id, $wpdb->get_blog_prefix($blog_id) . 'user_avatar', true);
            if ($original_attachment_id && !get_attached_file($original_attachment_id)) {
                $original_attachment_id = false;
            }
            $overwriteAttachment = false;
            if ($original_attachment_id && get_post_meta($original_attachment_id, $provider->getId() . '_avatar', true)) {
                $overwriteAttachment = true;
            }

            if (!$original_attachment_id) {
                /**
                 * If the user unlink and link the social provider back the original avatar will be used.
                 */
                $args  = array(
                    'post_type'   => 'attachment',
                    'post_status' => 'inherit',
                    'meta_query'  => array(
                        array(
                            'key'   => $provider->getId() . '_avatar',
                            'value' => $provider->getAuthUserData('id')
                        )
                    )
                );
                $query = new WP_Query($args);
                if ($query->post_count > 0) {
                    $original_attachment_id = $query->posts[0]->ID;
                    $overwriteAttachment    = true;
                    update_user_meta($user_id, $wpdb->get_blog_prefix($blog_id) . 'user_avatar', $original_attachment_id);
                }
            }

            if (!$original_attachment_id || $overwriteAttachment === true) {
                $avatarTempPath = download_url($avatarUrl);
                if (!is_wp_error($avatarTempPath)) {
                    $mime        = wp_get_image_mime($avatarTempPath);
                    $mime_to_ext = apply_filters('getimagesize_mimes_to_exts', array(
                        'image/jpeg' => 'jpg',
                        'image/png'  => 'png',
                        'image/gif'  => 'gif',
                        'image/bmp'  => 'bmp',
                        'image/tiff' => 'tif',
                    ));

                    if (isset($mime_to_ext[$mime])) {

                        $wp_upload_dir = wp_upload_dir();
                        $filename      = 'user-' . $user_id . '.' . $mime_to_ext[$mime];

                        $filename = wp_unique_filename($wp_upload_dir['path'], $filename);

                        $newAvatarPath = trailingslashit($wp_upload_dir['path']) . $filename;
                        $newFile       = @copy($avatarTempPath, $newAvatarPath);
                        @unlink($avatarTempPath);

                        if (false !== $newFile) {
                            $url = $wp_upload_dir['url'] . '/' . basename($filename);

                            if ($overwriteAttachment) {
                                $originalAvatarImage = get_attached_file($original_attachment_id);

                                // we got the same image, so we do not want to store it
                                if (md5_file($originalAvatarImage) === md5_file($newAvatarPath)) {
                                    @unlink($newAvatarPath);
                                } else {
                                    // Store the new avatar and remove the old one
                                    @unlink($originalAvatarImage);
                                    update_attached_file($original_attachment_id, $newAvatarPath);

                                    // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                                    require_once(ABSPATH . 'wp-admin/includes/image.php');

                                    wp_update_attachment_metadata($original_attachment_id, wp_generate_attachment_metadata($original_attachment_id, $newAvatarPath));

                                    update_user_meta($user_id, $wpdb->get_blog_prefix($blog_id) . 'user_avatar', $original_attachment_id);
                                }
                            } else {
                                $attachment = array(
                                    'guid'           => $url,
                                    'post_mime_type' => $mime,
                                    'post_title'     => '',
                                    'post_content'   => ""
                                );

                                $new_attachment_id = wp_insert_attachment($attachment, $newAvatarPath);
                                if (!is_wp_error($new_attachment_id)) {

                                    // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                                    require_once(ABSPATH . 'wp-admin/includes/image.php');

                                    wp_update_attachment_metadata($new_attachment_id, wp_generate_attachment_metadata($new_attachment_id, $newAvatarPath));

                                    update_post_meta($new_attachment_id, $provider->getId() . '_avatar', $provider->getAuthUserData('id'));
                                    update_post_meta($new_attachment_id, '_wp_attachment_wp_user_avatar', $user_id);

                                    update_user_meta($user_id, $wpdb->get_blog_prefix($blog_id) . 'user_avatar', $new_attachment_id);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}


NextendSocialLoginAvatar::init();