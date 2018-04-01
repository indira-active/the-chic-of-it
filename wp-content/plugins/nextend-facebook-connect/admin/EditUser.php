<?php
/** @var $user WP_User */
?>

<?php foreach (NextendSocialLogin::$enabledProviders AS $provider): ?>
    <?php if (!$provider->isUserConnected($user->ID)) continue; ?>
    <h2><?php echo $provider->getLabel(); ?></h2>

    <table class="form-table">
        <tbody>
            <?php foreach ($provider->getSyncFields() AS $fieldName => $fieldData): ?>
                <tr>
                    <th><label><?php echo $fieldData['label'] ?></label></th>
                    <td>
                        <?php echo esc_html(get_user_meta($user->ID, $fieldName, true)); ?>
                    </td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
<?php endforeach; ?>