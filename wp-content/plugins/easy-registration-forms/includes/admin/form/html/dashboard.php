<?php
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : '';
$form_id = isset($_GET['form_id']) ? absint($_GET['form_id']) : 0;
?>
<div class="erf-wrapper wrap erf-wrapper-bg">
    <div class="erf-page-title">
        <?php
            switch($active_tab){
                case 'build': $title = __('Build', 'erforms') ; break;
                case 'configure': $title = __('Configure', 'erforms') ; break;
                case 'report': $title = __('Report', 'erforms') ; break;
                case 'reports': $title = __('Reports', 'erforms') ; break;
                case 'notifications': $title = __('Notifications', 'erforms') ; break;
                default : $title = __('Form Dashboard', 'erforms') ;
             }
             $title= apply_filters('erf_dashboard_tab_title', $title, $active_tab);
            ?> 
        <h1 class="wp-heading-inline">
            <?php echo $title ?>
        </h1>
        <?php include('dashboard-header-menu.php'); ?>
    </div>
    <div class="erforms-new-form">
        <?php if (empty($active_tab)): ?>
            <div class="erf-settings-wrap">
                <a class="erf-setting" href="?page=erforms-dashboard&form_id=<?php echo $form_id; ?>&tab=build"><div class="erf-setting-inner"><img src="<?php echo ERFORMS_PLUGIN_URL?>/assets/admin/images/build.png"><span class='erf-setting-title'><?php _e('Build', 'erforms'); ?></span><div class="erf-setting-desc"><?php _e('Create, edit and manage fields from this section.','erforms'); ?></div></div></a>
                <a class="erf-setting" href="?page=erforms-dashboard&form_id=<?php echo $form_id; ?>&tab=configure"><div class="erf-setting-inner"><img src="<?php echo ERFORMS_PLUGIN_URL?>/assets/admin/images/configure.png"><span class='erf-setting-title'><?php _e('Configure', 'erforms'); ?></span><div class="erf-setting-desc"><?php _e('User Account Settings,Submission Restrictions,General Settings,Display Settings,Notifications,Post Submission actions','erforms'); ?></div></div></a>
                <a class="erf-setting" href="?page=erforms-dashboard&form_id=<?php echo $form_id; ?>&tab=notifications"><div class="erf-setting-inner"><img src="<?php echo ERFORMS_PLUGIN_URL?>/assets/admin/images/notifications.png"><span class='erf-setting-title'><?php _e('Notifications', 'erforms'); ?></span><div class="erf-setting-desc"><?php _e('Enable email notifications. Modify email contents with mail merge feature.','erforms'); ?></div></div></a>
                <a class="erf-setting" href="?page=erforms-dashboard&form_id=<?php echo $form_id; ?>&tab=attachments"><div class="erf-setting-inner"><img src="<?php echo ERFORMS_PLUGIN_URL?>/assets/admin/images/attachments.png"><span class='erf-setting-title'><?php _e('Uploads', 'erforms'); ?></span><div class="erf-setting-desc"><?php _e('Enable email notifications. Modify email contents with mail merge feature.','erforms'); ?></div></div></a>
                <?php if ($form['type'] == 'reg'): ?>
                    <a class="erf-setting" href="?page=erforms-dashboard&form_id=<?php echo $form_id; ?>&tab=reports"><div class="erf-setting-inner"><img src="<?php echo ERFORMS_PLUGIN_URL?>/assets/admin/images/submissions.png"><span class='erf-setting-title'><?php _e('Reports', 'erforms'); ?></span><div class="erf-setting-desc"><?php _e("Reports allows you to relay information (Submissions) to interested parties in a periodic manner. You can create multiple reports to share different submitted information with people.For each report, System will generate a CSV file containing the submitted information (which is selected by you) and will send an email to all it's recipients. In short <b>Report</b> allows you to share selected information from the submission to multiple people.",'erforms'); ?></div></div></a>
                <?php endif; ?>   
                <a class="erf-setting" href="?page=erforms-submissions&erform_id=<?php echo $form_id; ?>"><div class="erf-setting-inner"><img src="<?php echo ERFORMS_PLUGIN_URL?>/assets/admin/images/submissions.png"><span class='erf-setting-title'><?php _e('Submissions', 'erforms'); ?></span><div class="erf-setting-desc"><?php _e('Shows list of all the submissions. Allows to filter/export submission records.','erforms'); ?></div></div></a>
                <?php do_action('erf_builder_tab_links', $form, $active_tab); ?> 
            </div>

        <?php else: ?>
            <?php
                switch($active_tab){
                    case 'build': include('build.php'); break;
                    case 'configure': include('configure.php'); break;
                    case 'report': include('report.php'); break;
                    case 'reports': include('reports.php'); break;
                    case 'notifications': include('notifications.php'); break;
                    case 'attachments': include('attachments.php'); break;
                    case 'submission_attachments': include('submission_attachments.php'); break;
                }
                do_action('erf_dashboard_tabs', $form, $active_tab);
            ?>    
        <?php endif; ?>
    </div>    
</div>
