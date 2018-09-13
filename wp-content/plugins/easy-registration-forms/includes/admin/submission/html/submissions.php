<div class="erf-wrapper wrap">
    <div id="erforms-submission" class="erforms-admin-wrap">
        <h1 class="page-title">
            <?php _e('Submissions Overview', 'erforms'); ?>

        </h1>
        <div class="erf-feature-request">
            <?php _e('Feature not available ? Request new features <a target="_blank" href="http://www.easyregistrationforms.com/support/">here</a>.','erforms'); ?>
         </div>
        <?php
        $submisson_table = new ERForms_Submission_Table;
        $submisson_table->prepare_items();
        ?>

        <div class="erforms-admin-content">
            <form id="erforms-submission-table" method="get" action="<?php echo admin_url('admin.php?page=erforms-submission'); ?>">
                <input type="hidden" name="page" value="erforms-submissions" />
                <?php $submisson_table->views(); ?>
                <?php $submisson_table->display(); ?>
            </form>
        </div>
    </div>
</div>

<!-- Labels -->
<?php 
    $labels= erforms()->label->get_labels();
    if(!empty($labels)):
?>
<div class="erf-legends wrap">
    <div class="erf-legends-heading">
        <?php _e('Legends','erforms'); ?></div>
    <div class="erf-legends-wrap flex-s-e">
        <?php foreach($labels as $label): ?>
                <div class="erf-legend"><span style="background-color: #<?php echo $label['color']; ?>">&nbsp;</span><?php echo $label['name']; ?></div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?php $sanitized_tags= erforms()->label->get_tags(true); ?>
<script>
    jQuery(document).ready(function(){
        $= jQuery;
        var sanitized_tags= <?php echo json_encode($sanitized_tags); ?>;
        if(!$.isEmptyObject(sanitized_tags)){
            $.each(sanitized_tags, function(name,color) {
               $('.erf-label-' + name).attr('style','background-color:' + color);
            });
        }
    });
</script>    