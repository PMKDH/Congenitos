<div id="erforms-plan" class="wrap erforms-admin-wrap">

    <h1 class="wp-heading-inline">
        <?php _e('Plans', 'erforms'); ?>
    </h1>
    <a href="<?php echo admin_url('admin.php?page=erforms-plan'); ?>" class="page-title-action"><?php _e('Add New', 'erforms'); ?></a>

    <?php
    $plan_table = new ERForms_Plan_Table;
    $plan_table->prepare_items();
    ?>

    <div class="erforms-admin-content">

        <form id="erforms-plan-table" method="get" action="<?php echo admin_url('admin.php?page=erforms-plan'); ?>">

            <input type="hidden" name="post_type" value="erforms" />

            <input type="hidden" name="page" value="erforms-plan" />

            <?php $plan_table->views(); ?>
            <?php $plan_table->display(); ?>

        </form>

    </div>

</div>
