<?php 
    $menus= erforms_global_setting_menus();
    $tab= !empty($_REQUEST['tab']) ? sanitize_text_field($_REQUEST['tab']) : '';
    if(!in_array($tab, array_keys($menus))){
        $tab= '';
    }
?>
<?php if(empty($tab)): ?>
    <div class="erf-wrapper erforms-settings wrap erf-wrapper-bg">
        <div class="erf-page-title">
            <h1 class="wp-heading-inline">
                <?php _e('Global Settings', 'erforms'); ?>
            </h1>
            <div class="erf-page-menu">
                <ul class="erf-nav clearfix">

                </ul>        
            </div>
        </div>
        <div class="erf-settings-wrap">
            <?php foreach($menus as $slug=>$menu): ?>
                    <a class="erf-setting" href="?page=erforms-settings&tab=<?php echo $slug; ?>" class="erf-settings-<?php echo $slug; ?>">
                        <img src="<?php echo ERFORMS_PLUGIN_URL?>/assets/admin/images/global/<?php echo $slug; ?>-icon.png">
                        <span class="erf-setting-title"><?php echo $menu['label'];  ?></span>
                        <div class="erf-setting-desc"><?php echo implode(',', $menu['desc']);  ?></div>
                    </a>  
            <?php endforeach; ?>
        </div>
    </div> 
<?php else: include('setting-tab.php'); ?>
<?php endif; ?>

