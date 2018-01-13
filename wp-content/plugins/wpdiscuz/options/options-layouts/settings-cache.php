<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div>
    <h2 style="padding:5px 10px 10px 10px; margin:0px;"><?php _e('Gravatar Cache', 'wpdiscuz'); ?></h2>
    <table class="wp-list-table widefat plugins" style="margin-top:10px; border:none;">
        <tbody>
            <tr valign="top">
                <th scope="row">
                    <label for="isGravatarCacheEnabled"><?php _e('Enable Grvatar caching', 'wpdiscuz'); ?></label>
                    <?php if (!$this->optionsSerialized->isFileFunctionsExists) { ?>
                        <p class="desc"><?php _e('It seems on of important functions ("file_get_contents", "file_put_contents") of php is not exists.<br/> Please enable these functions in your server settings to use gravatar caching feature.', 'wpdiscuz'); ?></p>
                    <?php } ?>
                </th>
                <td>   
                    <input type="checkbox" <?php checked($this->optionsSerialized->isGravatarCacheEnabled == 1) ?> value="1" name="isGravatarCacheEnabled" id="isGravatarCacheEnabled" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="gravatarCacheMethod"><?php _e('Caching method', 'wpdiscuz'); ?></label>
                </th>
                <td>   
                    <fieldset>                        
                        <label for="gravatarCacheMethodRuntime">
                            <input type="radio" <?php checked($this->optionsSerialized->gravatarCacheMethod == 'runtime') ?> value="runtime" name="gravatarCacheMethod" id="gravatarCacheMethodRuntime" />
                            <span><?php _e('Runtime', 'wpdiscuz'); ?></span>
                        </label><br/>
                        <label for="gravatarCacheMethodCronjob">
                            <input type="radio" <?php checked($this->optionsSerialized->gravatarCacheMethod == 'cronjob') ?> value="cronjob" name="gravatarCacheMethod" id="gravatarCacheMethodCronjob" />
                            <span><?php _e('Cron job', 'wpdiscuz'); ?></span>
                        </label>
                    </fieldset>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="gravatarCacheTimeout"><?php _e('Cache avatars for "X" days', 'wpdiscuz'); ?></label>
                </th>
                <td>
                    <?php $gravatarCacheTimeout = isset($this->optionsSerialized->gravatarCacheTimeout) && ($days = absint($this->optionsSerialized->gravatarCacheTimeout)) ? $days : 10; ?>
                    <input type="number" id="gravatarCacheTimeout" name="gravatarCacheTimeout" value="<?php echo $gravatarCacheTimeout; ?>"/>
                </td>
            </tr>
            <tr>
                <th>
                    <?php _e('Purge expired caches', 'wpdiscuz'); ?>
                </th>
                <td>
                    <?php $cacheUrl = admin_url('admin-post.php/?action=purgeExpiredGravatarsCaches'); ?>
                    <a id="wpdiscuz-purge-expired-gravatars-cache" href="<?php echo wp_nonce_url($cacheUrl, 'purgeExpiredGravatarsCaches'); ?>" class="button button-secondary" style="margin-left: 5px;"><?php _e('Purge expired caches', 'wpdiscuz'); ?></a>
                </td>
            </tr>
            <tr>
                <th>
                    <?php _e('Purge all caches', 'wpdiscuz'); ?>
                </th>
                <td>
                    <?php $cacheUrl = admin_url('admin-post.php/?action=purgeGravatarsCaches'); ?>
                    <a id="wpdiscuz-purge-gravatars-cache" href="<?php echo wp_nonce_url($cacheUrl, 'purgeGravatarsCaches'); ?>" class="button button-secondary" style="margin-left: 5px;"><?php _e('Purge all caches', 'wpdiscuz'); ?></a>
                </td>
            </tr>
        </tbody>
    </table>
</div>