<?php 
        $notifications= erforms_system_notifications($form['type']);
        $type= !empty($_REQUEST['type']) ? sanitize_text_field($_REQUEST['type']) : '';
        if(!in_array($type, array_keys($notifications))){
            $type= '';
        }
?>

<?php if(empty($type)){ ?>
<div class="erf-notifications">
    <table class="wp-list-table widefat fixed striped">
        <thead>
                <tr>
                    <th><?php _e('Status','erforms'); ?></th>
                    <th><?php _e('Email','erforms'); ?></th>
                    <th><?php _e('Subject','erforms'); ?></th>
                    <th><?php _e('Recipient(s)','erforms'); ?></th>
                    <th><?php _e('Action','erforms'); ?></th>
                </tr>
        </thead>
        <tbody>
            <?php foreach($notifications as $type=>$label):
                    $notification= erforms_system_notification_by_type($type,$form);
            ?>
                    <tr>
                        <td class="notification-status <?php echo empty($notification['enabled']) ? 'disabled' : 'enabled' ?>">
                            <?php echo empty($notification['enabled']) ? '<span title="Notification Inactive" class="dashicons dashicons-no"></span>' : '<span title="Notification Active" class="dashicons dashicons-yes"></span>' ?>
                            &nbsp;</td> 
                        <td><?php echo $label. '<div class="erf-notifiaction-desc"><span class="dashicons dashicons-editor-help"></span><span class="erf-notification-help">' . $notification['help'] . '</span></div>' ; ?></td>
                        <td><?php echo $notification['subject']; ?></td>
                        <td><?php echo $notification['recipients']; ?></td>
                        <td><a class="button alignright" href="?page=erforms-dashboard&form_id=<?php echo $form_id; ?>&tab=notifications&type=<?php echo $type; ?>"><?php _e('Manage','erforms'); ?></a></td>
                    </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php } else { 
    include('notification-type.php');
} ?>

