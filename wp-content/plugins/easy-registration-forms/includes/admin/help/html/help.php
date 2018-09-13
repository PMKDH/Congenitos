<?php 
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'new_form';
?>
<div class="erf-wrapper wrap">    
    <div class="wrap erf-help">

        <h1><?php _e('Shortcodes','erforms') ?></h1>
        <br>
        <table class="wp-list-table widefat fixed striped">
            <thead>

            <tr>
                <td class="check-column"></td>
                <th class="manage-column"><?php _e('Shortcode','erforms') ?></th>
                <th class="manage-column"><?php _e('Displays','erforms') ?></th>
                <th class="manage-column column-title"><?php _e('Description','erforms') ?></th>
            </tr>

            <tr>
                </tr></thead>
                <tbody><tr><td>1</td>
                <td><code>[erforms id="X"]</code></td>
                <td><?php _e('Display Forms','erforms') ?></td>
                <td><?php _e("It renders form in any page/post, 'X' denotes the ID of a form.",'erforms') ?></td>
            </tr>

            <tr>
                <td>2</td>
                <td><code>[erforms_my_account]</code></td>
                <td><?php _e('Display User Account section','erforms') ?></td>
                <td><?php _e('Shows listing of all the submissions done by logged in user. Allows to display, edit and print a submission).','erforms') ?> </td>
            </tr>

            <tr>
                <td>3</td>
                <td><code>[erforms_login]</code></td>
                <td><?php _e('Login Form','erforms') ?></td>
                <td><?php _e('Shows login form with Lost Password option.','erforms') ?></td>
            </tr>

            </tbody>
        </table>
    </div>
    
</div>      
   
