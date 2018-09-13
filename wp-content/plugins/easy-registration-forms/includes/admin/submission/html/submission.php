<?php 
    $options= erforms()->options->get_options();
    $form= erforms()->form->get_form($submission['form_id']);
?>
<div class="erf-wrapper wrap erf-wrapper-bg">
    <div class="erf-page-title">
        <h1><?php _e('Submission Details', 'erforms'); ?></h1>
    </div>
    <div class="tablenav top">

        <div class="alignleft actions">
            <a id="erf_submission_add_note" class="button button-primary" href="javascript:void(0)"><?php _e('Add Note', 'erforms'); ?></a>
        </div>
        <div class="alignleft actions">
            <a id="erf_submission_print" class="button" href="#"><?php _e('Print', 'erforms'); ?></a>
        </div>
        
        <div class="alignleft actions">
            <a target="_blank" class="button" href="<?php echo get_permalink($options['preview_page']).'?sub_id='.$submission['id'].'&erform_id='.$submission['form_id'];?>"><?php _e('Edit', 'erforms'); ?></a>
        </div>
        
        <div class="alignleft actions">
            <a class="button button-danger" href="<?php print wp_nonce_url(admin_url('admin.php?page=erforms-submissions&submission_id=' . $sub_id), 'erf_submission_delete', 'delete_nonce'); ?>"><?php _e('Delete', 'erforms'); ?></a>
        </div>

    </div>
    <?php if(!empty($submission['user'])): ?>
        <div class="erf-submission-from erf-feature-request">
            <strong><?php _e('Submission From: ','erforms'); ?></strong>
            <a target="_blank" href="<?php echo get_edit_user_link($submission['user']['ID']); ?>"><?php echo $submission['user']['user_email']; ?></a>
        </div>
    <?php endif; ?>
    <div class="erf-submission-tags clearfix">
        <?php 
                // Show if any tag exists in the system.  
                $tags= erforms()->label->get_tags();
                $sanitized_tags= erforms()->label->get_tags(true);
                $selected_tags= erforms()->label->tags_by_submission($submission['id']);
                if(!empty($tags)):
        ?>
            <input type="text" value="<?php echo implode(',',$selected_tags); ?>" class="erf-tag-select" />
        <?php endif; ?>
    </div>
    <?php erforms_admin_submission_table($submission); ?>
    <?php if(!empty($submission['plan']))
        {
            include('payment-part.php');
        }
    ?>
    <div class="erf-payment-info">
        
    </div>
    

    <?php if (!empty($notes) && is_array($notes)) : ?>
        <div class="erf-notes">
            <div class="erf-notes-title"><?php _e('Note(s)', 'erforms'); ?></div>
            <div class="erf-notes-wrap">
    <?php foreach ($notes as $note) : ?>
                    <div class="erf-note-row">
                        <p>
                    <?php echo $note['text']; ?>
                            <span> <?php _e('By','erforms'); ?>
                            <?php echo $note['by']; ?> on
                            <?php echo $note['time']; ?></span>
                        </p>
                    </div>  
                            <?php endforeach; ?>
            </div>
        </div>    
            <?php endif; ?>
    
    <div id="erf_submission_add_note_dialog" title="<?php _e('Add Note','erforms'); ?>" style="display: none;">
        <form method="POST">
            <table class="fixed">
                <tbody>
                    <tr>
                        <th><?php _e('Note', 'erforms'); ?></th>
                        <td>    
                            <textarea name="note_text" id="erf_submission_note_text"></textarea>
                        </td>
                    </tr>
                    <?php if($form['type']=='reg'): ?>
                        <tr>
                            <th><?php _e('Notify User', 'erforms'); ?></th>
                            <td>
                                    <input type="checkbox" value='1' name="notify_user"/>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
             <input type="hidden" name="erf_save_note" />
        </form>
    </div>
    
    
    <?php if(is_array($submissions) && !empty($submissions)): ?>
        <hr class="erf-divider">
        <div class="erf-history-submissions">
            <h1><?php _e('Submissions History','erforms'); ?></h1>
            <?php 
                    foreach($submissions as $temp_sub)
                    {
                        erforms_admin_submission_table($temp_sub);
                    }
            ?>
        </div>
    <?php endif; ?>
</div>
<script>
jQuery(document).ready(function(){
   $= jQuery;
   var sanitized_tags= <?php echo json_encode($sanitized_tags); ?>;
   if(!$.isEmptyObject(sanitized_tags)){
       $.each(sanitized_tags, function(name,color) {
          $('.erf-label-' + name).attr('style','background-color:' + color);
       });
   }
   /*
    * Submission tag related
    */
   if($('.erf-tag-select').length>0){
        $('.erf-tag-select').amsifySuggestags({
        type : 'bootstrap',
        suggestions: <?php echo json_encode(array_keys($tags)); ?>,
        backgrounds: <?php echo json_encode(array_values($tags)); ?>,
        defaultLabel: '<?php _e('Assign Label','erforms'); ?>',
        whiteList: true,
        afterAdd: function(value) {
            var data = {
                            'action': 'erf_assign_label',
                            'name': value,
                            'sub_id': <?php echo $submission['id']; ?>
                       };
            $.post(ajaxurl, data, function(response) {
                    var response_data= response.data;
                    if(response.success){
                        location.reload();
                    }
            }).fail(function (xhr,textStatus,e) {
                 alert('<?php _e('Unable to connect to server.','erforms'); ?>');
            });
        },
        afterRemove: function(value){
            var data = {
                            'action': 'erf_remove_sub_label',
                            'name': value,
                            'sub_id': <?php echo $submission['id']; ?>
                       };
            $.post(ajaxurl, data, function(response) {
                    var response_data= response.data;
                    if(response.success){
                        location.reload();
                    }
            }).fail(function (xhr,textStatus,e) {
                 alert('<?php _e('Unable to connect to server.','erforms'); ?>');
            });
        }    
        });
   }
   

});
</script>
