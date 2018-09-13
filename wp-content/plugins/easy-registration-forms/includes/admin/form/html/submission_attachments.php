<?php
$submission_id= !empty($_GET['submission_id']) ? $_GET['submission_id'] : false;
if(empty($submission_id))
    return;

$submission= erforms()->submission->get_submission($submission_id);
?>
<?php if(empty($submission['attachments'])) : ?>
        <div><?php echo __('No Attachments available','erforms'); ?></div>
<?php else:?>
        <?php foreach($submission['attachments'] as $attachment): ?>
                <?php if(wp_attachment_is_image($attachment['f_val'])): $image_attributes = wp_get_attachment_image_src($attachment['f_val']); ?>
                        <a target="_blank" href="<?php wp_get_attachment_url($attachment['f_val']); ?>">
                            <img src="<?php echo $image_attributes[0]; ?>" width="<?php echo $image_attributes[1]; ?>" height="<?php echo $image_attributes[2]; ?>" />
                        </a>
                <?php else: $url = wp_get_attachment_url($attachment['f_val']); ?>
                            <?php if(!empty($url)):?>
                                    <a target="_blank" href="<?php echo $url; ?>"><?php echo $url; ?></a>
                            <?php else: ?>
                                    <?php _e('Unable to fetch file.File might have deleted from from WordPress media section.','erforms'); ?>
                            <?php endif;?>
                            
                <?php endif; ?>
        <?php endforeach;?>
<?php endif; ?>

    