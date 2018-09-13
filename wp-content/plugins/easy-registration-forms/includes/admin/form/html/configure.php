<?php $menus= erforms_form_configuration_menus($form['type']);
      $type= !empty($_REQUEST['type']) ? sanitize_text_field($_REQUEST['type']) : '';
      if(!in_array($type, array_keys($menus))){
        $type= '';
      }  
?>

<?php if(empty($type)): ?>
    <div class="erf-settings-wrap">
        <?php foreach($menus as $slug=>$menu): ?>
            <a class="erf-setting" href="?page=erforms-dashboard&form_id=<?php echo $form['id']; ?>&tab=configure&type=<?php echo $slug; ?>">
                <img src="<?php echo ERFORMS_PLUGIN_URL?>/assets/admin/images/configuration/<?php echo $slug; ?>-icon.png">
                <span class="erf-setting-title"><?php echo $menu['label'];  ?></span>
                <div class="erf-setting-desc"><?php echo implode(',',$menu['desc']);  ?></div>
                        
                
                
            </a>
        <?php endforeach; ?>
        

    </div> 
<?php else: include('configuration-type.php'); ?>
<?php endif; ?>
