<?php
$auto_login = empty($form['auto_login']) ? '' : 'checked';
$recaptcha_enabled = $form['recaptcha_enabled'];
$auto_user_activation = empty($form['auto_user_activation']) ? '' : 'checked';
$en_role_choices = empty($form['en_role_choices']) ? '' : 'checked';
?>
<div class="erf-form-conf-wrapper">
    <form action="" method="post" id="erf_configuration_form">
        <fieldset class="erf-config-wrap">

            <!-- General Settings -->
            <div style="<?php echo $type == 'general' ? '' : 'display:none' ?>">
                <div class="group-title">
                    <?php _e('General Settings', 'erforms'); ?>
                </div>

                <div class="group-wrap">

                    <?php if (!empty($options['recaptcha_configured'])) : ?>
                        <div class="erf-row">
                            <div class="erf-control-label">
                                <label><?php _e('Enable Recaptcha', 'erforms'); ?></label>
                            </div>
                            <div class="erf-control">
                                <input class="erf_toggle"  type="checkbox" name="recaptcha_enabled" value="1" <?php echo empty($recaptcha_enabled) ? '' : 'checked'; ?>>
                                <label></label>
                                <p class="description"><?php _e('This will show Google Recaptcha at the bottom of form for guest users.', 'erforms'); ?></p>
                            </div>  
                        </div>
                    <?php else : ?>
                        <div class="erf-row">
                            <div class="erf-control-label">
                                <label><?php _e('Enable Recaptcha', 'erforms'); ?></label>
                            </div>
                            <div class="erf-control">
                                <input class="erf_toggle"  type="checkbox" disabled="">
                                <label></label>
                                <p class="description"><?php printf(__('Recapctha can not be enabled. Please enable and configure keys from <a target="_blank" href="%s">here</a>.', 'erforms'),'?page=erforms-settings&tab=general#erf_recaptcha'); ?></p>
                            </div>  
                        </div>
                    <?php endif; ?>

                    <?php if ($form['type'] == 'reg'): ?>
                        <div class="erf-row">
                            <div class="erf-control-label">
                                <label><?php _e('Enable Login Form', 'erforms'); ?></label>
                            </div>
                            <div class="erf-control">
                                <input class="erf_toggle"  type="checkbox" name="enable_login_form" value="1" <?php echo $form['enable_login_form'] ? 'checked' : '' ?>>
                                <label></label>
                                <p class='description'><?php _e('This will show login option after registration form (Will not be visible to logged in users.)', 'erforms') ?></p>
                            </div>  
                        </div>
                    <?php endif; ?>

                    <div class="erf-row">
                        <div class="erf-control-label">
                            <label><?php _e('Unique ID', 'erforms'); ?></label>
                        </div>
                        <div class="erf-control erf-has-child">
                            <input class="erf_toggle"  type="checkbox" value="1" id="enable_unique_id" name="enable_unique_id" data-has-child="1" <?php echo $form['enable_unique_id'] == '1' ? 'checked' : ''; ?> />
                            <label></label>
                            <p class="description"><?php _e('Enable Unique token ID generation for each submission.', 'erforms') ?></p>
                        </div>  
                    </div>



                    <div class="erf-child-rows" style="display:none">

                        <div class="erf-row">
                            <div class="erf-control-label">
                                <label><?php _e('Generation Method', 'erforms'); ?></label>
                            </div>
                            <div class="erf-control erf-has-child">
                                <input type="radio" checked value="auto" name="unique_id_gen_method" data-child-index="-1" <?php echo $form['unique_id_gen_method'] == 'auto' ? 'checked' : '' ?>/> Auto
                                <input type="radio" value="configure" name="unique_id_gen_method"  data-child-index="1" <?php echo $form['unique_id_gen_method'] == 'configure' ? 'checked' : '' ?> /> Configure
                            </div>  
                        </div>

                        <div class="erf-child-rows erf-dummy-child">

                        </div>

                        <div class="erf-child-rows">
                            <div class="erf-row">
                                <div class="erf-control-label">
                                    <label><?php _e('Current Index', 'erforms'); ?></label>
                                </div>
                                <div class="erf-control">
                                    <input type="number" value="<?php echo $form['unique_id_index']; ?>" min="<?php echo $form['unique_id_index']; ?>" name="unique_id_index" />
                                </div>  
                            </div>

                            <div class="erf-row">
                                <div class="erf-control-label">
                                    <label><?php _e('Prefix', 'erforms'); ?></label>
                                </div>
                                <div class="erf-control">
                                    <input type="text" value="<?php echo $form['unique_id_prefix']; ?>"  min="1" name="unique_id_prefix" />
                                </div>  
                            </div>

                            <div class="erf-row">
                                <div class="erf-control-label">
                                    <label><?php _e('Number Padding', 'erforms'); ?></label>
                                </div>
                                <div class="erf-control">
                                    <input type="number" value="<?php echo $form['unique_id_padding']; ?>" min="0" name="unique_id_padding" />
                                </div>  
                            </div>
                        </div>

                    </div>
                    <?php if (erforms_show_opt_in()): ?>
                                <div class="erf-row">
                                    <div class="erf-control-label">
                                        <label><?php _e('Enable opt-in checkbox', 'erforms'); ?></label>
                                    </div>
                                    <div class="erf-control erf-has-child">.
                                        <input type="checkbox"  class="erf_toggle" name="opt_in" <?php echo!empty($form['opt_in']) ? 'checked' : ''; ?>/>
                                        <label></label>
                                        <p class="description"><?php _e('Allow users to opt-in for subscription.', 'erforms'); ?></p>        
                                    </div> 
                                </div>

                                <div class="erf-child-rows" style="display:none">
                                    <div class="erf-row">
                                        <div class="erf-control-label">
                                            <label><?php _e('Checkbox Text', 'erforms'); ?></label>
                                        </div>
                                        <div class="erf-control">
                                            <input type="text" name="opt_text" value="<?php echo $form['opt_text']; ?>" />
                                            <p class="description"><?php _e('Text will appear with checkbox.', 'erforms'); ?></p>
                                        </div>  
                                    </div>

                                    <div class="erf-row">
                                        <div class="erf-control-label">
                                            <label><?php _e('Default State', 'erforms'); ?></label>
                                        </div>
                                        <div class="erf-control">
                                            <input type="radio" name="opt_default_state" value="1" <?php echo!empty($form['opt_default_state']) ? 'checked' : ''; ?>/><?php _e('Checked', 'erforms'); ?>
                                            <input type="radio" name="opt_default_state" value="0" <?php echo empty($form['opt_default_state']) ? 'checked' : ''; ?>/><?php _e('Unchecked', 'erforms'); ?>
                                            <p class="description"><?php _e('Default state of the checkbox.', 'erforms'); ?></p>
                                        </div>  
                                    </div>
                                </div>   
                    <?php endif; ?>
                    
                    <?php do_action('erf_form_config_user_general'); ?>

                </div>

            </div>

            <div style="<?php echo $type == 'user_account' ? '' : 'display:none' ?>">
                <?php if ($form['type'] == "reg") : ?>
                    <!-- User Account Settings -->
                    <div class="group-title">
                        <?php _e('User Account Settings', 'erforms'); ?>
                    </div>

                    <div class="group-wrap">
                        <div class="erf-row">
                            <div class="erf-control-label">
                                <label><?php _e('Auto User Activation', 'erforms'); ?></label>
                            </div>
                            <div class="erf-control erf-has-child">
                                <input class="erf_toggle" type="checkbox" data-has-child="1" name="auto_user_activation" value="1" <?php echo $auto_user_activation; ?>>
                                <label></label>
                                <p class="description"><?php printf(__("User's account will be activated after submission. Notifications can be configured from <a target='_blank' href='%s'>here</a>.", 'erforms'), '?page=erforms-dashboard&form_id=' . $form_id . '&tab=notifications&type=user_activation'); ?></p>
                            </div>  
                        </div>

                        <div class="erf-child-rows" style="display:none">
                            <div class="erf-row">
                                <div class="erf-control-label">
                                    <label><?php _e('Log in user after Registration', 'erforms'); ?></label>
                                </div>

                                <div class="erf-control">
                                    <input class="erf_toggle" type="checkbox" name="auto_login" value="1" <?php echo $auto_login; ?>>
                                    <label></label>
                                    <p class="description"><?php _e('User will be logged in automatically in WordPress system. (It will work only if Auto User Activation is enabled)', 'erforms'); ?></p>
                                </div>  
                            </div>
                        </div>



                        <div class="erf-row" id="verification_link">
                            <div class="erf-control-label">
                                <label><?php _e('Send Verification Link', 'erforms'); ?></label>
                            </div>
                            <div class="erf-control erf-has-child">
                                <input class="erf_toggle" type="checkbox" data-has-child="1" name="en_email_verification" value="1" <?php echo empty($form['auto_user_activation']) ? '' : 'disabled'; ?> <?php echo!empty($form['en_email_verification']) ? 'checked' : '' ?>>
                                <label></label>
                                <p class="description"><?php printf(__('After successful form submission, user will receive an email with account verification link. Clicking the link will activate the account. Make sure <b>Auto User Activation</b> is disabled. Otherwise it won\'t work. To change the notification content, Please click <a target="_blank" href="%s">here</a>', 'erforms'), '?page=erforms-dashboard&form_id=' . $form_id . '&tab=notifications&type=user_verification'); ?></p>
                            </div>  
                        </div>

                        <div class="erf-child-rows" style="display:none">
                            <div class="erf-row">
                                <div class="erf-control-label">
                                    <label><?php _e('Account Activation Message', 'erforms'); ?></label>
                                </div>

                                <div class="erf-control">
                                    <?php
                                    $editor_id = 'user_acc_verification_msg';
                                    $settings = array('editor_class' => 'erf-editor');
                                    wp_editor($form['user_acc_verification_msg'], $editor_id, $settings);
                                    ?>
                                    <p class="desription"><?php _e('Message will appear on successful account activation. Here you can add any plugin shortcode to show login box or any other elements.', 'erforms') ?></p>
                                </div>
                            </div>

                            <div class="erf-row">
                                <div class="erf-control-label">
                                    <label><?php _e('Account Verification Page', 'erforms'); ?><sup>*</sup></label>
                                </div>

                                <div class="erf-control">
                                    <?php wp_dropdown_pages(array('selected' => $form['after_user_ver_page'], 'show_option_none' => 'Select Page', 'option_none_value' => 0, 'name' => 'after_user_ver_page')); ?>
                                    <p class="desription"><?php printf(__("This Page's link will be sent to User for account re-verification. Make sure to add <code>%s</code> shortcode on the selected page.", 'erforms'), '[erforms_account_verification]') ?></p>
                                </div>
                            </div>

                            <div class="erf-row">
                                <div class="erf-control-label">
                                    <label><?php _e('Auto login after verification', 'erforms'); ?></label>
                                </div>

                                <div class="erf-control">
                                    <input class="erf_toggle" type="checkbox" name="auto_login_after_ver" value="1"  <?php echo!empty($form['auto_login_after_ver']) ? 'checked' : '' ?>>
                                    <label></label>
                                    <p class="desription"><?php _e('Logs in User after successful verfication. In case <b>After Login Redirection</b> or <b>Role Based Login Redirection</b> (Under Global Settings) is enabled, User will be directed to corresponding page.', 'erforms'); ?></p>
                                </div>
                            </div>

                        </div>

                        <div class="erf-row">
                            <div class="erf-control-label">
                                <label><?php _e('Assign User Role', 'erforms'); ?></label>
                            </div>
                            <div class="erf-control">
                                <?php $default_role = isset($form['default_role']) ? $form['default_role'] : get_option('default_role'); ?>
                                <select name="default_role">
                                    <option value=""><?php _e('Inherit from Form', 'erforms'); ?></option>
                                    <?php wp_dropdown_roles($default_role); ?>
                                </select>
                                <p class='description'><?php _e('User Role that will be assigned to the user after successful registration. In case "Inherit from Form" options selected, Form has to provide role information. Role option can be allowed for Radio Group field by enabling "User Role" option.', 'erforms'); ?></p>
                            </div>  
                        </div>
                        <?php do_action('erf_form_config_user_account'); ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Form Restriction Setting -->
            <div style="<?php echo $type == 'restrictions' ? '' : 'display:none' ?>">
                <div class="group-title">
                    <?php _e('Submission/Form Restriction Settings', 'erforms'); ?>
                </div>

                <div class="group-wrap">
                    <div class="erf-row">
                        <div class="erf-control-label">
                            <label><?php _e('Allowed User Roles', 'erforms'); ?></label>
                        </div>
                        <div class="erf-control">
                            <?php echo erforms_get_roles_checkbox('access_roles', $form['access_roles']); ?>
                            <p class='description'><?php _e('Only users with above roles will be allowed to view form. By default it will allow all the users.', 'erforms') ?></p>
                        </div>  
                    </div>

                    <div class="erf-row">
                        <div class="erf-control-label">
                            <label><?php _e('Access Denied Note', 'erforms'); ?></label>
                        </div>
                        <div class="erf-control">
                            <textarea name="access_denied_msg"><?php echo $form['access_denied_msg'] ?></textarea>
                            <p class="description"><?php _e('Users will see this message when they are not allowed to access the form.', 'erforms') ?></p>
                        </div>  
                    </div>

                    <?php if ($form['type'] == 'reg') : ?>
                        <div class="erf-row">
                            <div class="erf-control-label">
                                <label><?php _e('Allow submission from Logged in Users', 'erforms'); ?></label>
                            </div>
                            <div class="erf-control">
                                <input class="erf_toggle"  type="checkbox" name="allow_re_register" value="1" <?php echo $form['allow_re_register'] ? 'checked' : '' ?>>
                                <label></label>
                                <p class='description'><?php _e('If checked form will be visible to logged in users. Helpful in case you want to re-register the users.', 'erforms') ?></p>
                            </div>  
                        </div>
                    <?php else : ?>
                        <div class="erf-row">
                            <div class="erf-control-label">
                                <label><?php _e('Allow only logged in users ', 'erforms'); ?></label>
                            </div>
                            <div class="erf-control">
                                <input class="erf_toggle"  type="checkbox" name="allow_only_registered" value="1" <?php echo $form['allow_only_registered'] ? 'checked' : '' ?>>
                                <label></label>
                                <p class='description'><?php _e('If checked only logged in users will be able to submit the form.', 'erforms') ?></p>
                            </div>  
                        </div>
                    <?php endif; ?>

                    <div class="erf-row">
                        <div class="erf-control-label">
                            <label><?php _e('Password based Form Restriction', 'erforms'); ?></label>
                        </div>
                        <div class="erf-control erf-has-child">
                            <input class="erf_toggle"  type="checkbox" name="en_pwd_restriction" value="1" <?php echo $form['en_pwd_restriction'] ? 'checked' : ''; ?> />
                            <label></label>
                            <p class='description'><?php _e('System will ask users to enter a password before accessing form.', 'erforms'); ?>
                        </div>  
                    </div>

                    <div class="erf-child-rows" style="display:none">

                        <div class="erf-row">
                            <div class="erf-control-label">
                                <label><?php _e('Description', 'erforms'); ?></label>
                            </div>

                            <div class="erf-control">
                                <input type="text" name="pwd_res_description" value="<?php echo $form['pwd_res_description']; ?>">
                                <p class="description"><?php _e('Description about the restriction. It will be displayed above the form.', 'erforms'); ?></p>
                            </div>  
                        </div>

                        <div class="erf-row">
                            <div class="erf-control-label">
                                <label><?php _e('Question', 'erforms'); ?></label>
                            </div>

                            <div class="erf-control">
                                <input type="text" name="pwd_res_question" value="<?php echo $form['pwd_res_question']; ?>">
                                <p class="description"><?php _e('This question will be asked to user.', 'erforms'); ?></p>
                            </div>  
                        </div>

                        <div class="erf-row">
                            <div class="erf-control-label">
                                <label><?php _e('Password/Answer'); ?></label>
                            </div>

                            <div class="erf-control">
                                <input type="text" name="pwd_res_answer" value="<?php echo $form['pwd_res_answer']; ?>">
                                <p class="description"><?php _e('Password/Answer that must be given by user to access the form.', 'erforms'); ?></p>
                            </div>  
                        </div>

                        <div class="erf-row">
                            <div class="erf-control-label">
                                <label><?php _e('Error Message'); ?></label>
                            </div>

                            <div class="erf-control">
                                <input type="text" name="pwd_res_err" value="<?php echo $form['pwd_res_err']; ?>">
                                <p class="description"><?php _e('It will be displayed when user enters wrong password/answer.', 'erforms'); ?></p>
                            </div>  
                        </div>

                        <div class="erf-row">
                            <div class="erf-control-label">
                                <label><?php _e('Enable For Logged in Users'); ?></label>
                            </div>

                            <div class="erf-control">
                                <input  class="erf_toggle" type="checkbox" name="pwd_res_en_logged_in" value="1" <?php echo empty($form['pwd_res_en_logged_in']) ? '' : 'checked'; ?>>
                                <label></label>
                                <p class="description"><?php _e('If enabled, Logged in users will have to answer the security question before accessing the form.', 'erforms'); ?></p>
                            </div>  
                        </div>

                    </div>

                    <div class="erf-row">
                        <div class="erf-control-label">
                            <label><?php _e('Limit Submissions', 'erforms'); ?></label>
                        </div>
                        <div class="erf-control erf-has-child">
                            <input class="erf_toggle"  type="checkbox" name="enable_limit" data-has-child="1" value="1" <?php echo $form['enable_limit'] ? 'checked' : ''; ?> />
                            <label></label>
                            <p class='description'><?php _e('Removes the form after required number of submissions or a specific date. ', 'erforms'); ?>
                        </div>  
                    </div>

                    <div class="erf-child-rows">
                        <div class="erf-row">
                            <div class="erf-control-label">
                                <label><?php _e('By Date/ By Number', 'erforms'); ?></label>
                            </div>
                            <div class="erf-control erf-has-child">
                                <input checked type="radio" name="limit_type" data-has-child="1"  value="date" /> <?php _e('Date', 'erforms'); ?>
                                <input type="radio" name="limit_type" data-has-child="1" data-child-index="1" value="number" <?php echo $form['limit_type'] == 'number' ? 'checked' : ''; ?>/> <?php _e('Number', 'erforms'); ?>
                            </div>  
                        </div>

                        <div class="erf-child-rows">
                            <div class="erf-row">
                                <div class="erf-control-label">
                                    <label><?php _e('Date', 'erforms'); ?></label>
                                </div>
                                <div class="erf-control">
                                    <input type="text" id="erf_configure_limit_by_date" name="limit_by_date" data-has-child="1" value="<?php echo $form['limit_by_date']; ?>" />
                                    <p class="description"><?php _e('Last date on which this form will appear for users.', 'erforms') ?></p>
                                </div>  
                            </div>

                        </div>

                        <div class="erf-child-rows">
                            <div class="erf-row">
                                <div class="erf-control-label">
                                    <label><?php _e('Number of Submissions', 'erforms'); ?></label>
                                </div>
                                <div class="erf-control">
                                    <input type="number" name="limit_by_number" data-has-child="1" value="<?php echo $form['limit_by_number']; ?>" />
                                    <p class="description"><?php _e('Form will not be visible after this number is reached.', 'erforms') ?></p>
                                </div>  
                            </div>
                        </div> 

                        <div class="erf-row">
                            <div class="erf-control-label">
                                <label><?php _e('Display message', 'erforms'); ?></label>
                            </div>
                            <div class="erf-control">
                                <textarea name="limit_message"><?php echo $form['limit_message']; ?></textarea>
                                <p class="description"><?php _e('This message will be shown when accessing the form after limit expired.', 'erforms') ?></p>
                            </div>  
                        </div>

                    </div>

                    <?php do_action('erf_form_config_restrictions'); ?>
                </div>
            </div>
            <!-- Restriction settings ends here -->

            <div style="<?php echo $type == 'plans' ? '' : 'display:none' ?>">
                <!-- Plan Settings -->
                <?php if ($form['type'] == 'reg'): ?>
                    <div class="group-title">
                        <?php _e('Plan Settings', 'erforms'); ?>
                    </div>

                    <div class="group-wrap">
                        <?php if (empty($options['payment_methods'])) : ?>
                            <p class="description"><?php echo 'It appears Offline payment is not enabled from plugin Settings page. Please click <a target="_blank" href="' . admin_url('admin.php?page=erforms-settings#erf-pm-wrapper') . '">here</a> to configur it.'; ?></p>
                        <?php endif; ?>
                        <div class="erf-row">
                            <div class="erf-control-label">
                                <label><?php _e('Enable Plan', 'erforms'); ?></label>
                            </div>
                            <div class="erf-control erf-has-child">
                                <input class="erf_toggle"  type="checkbox" value="1" name="plan_enabled" data-has-child="1" <?php echo $form['plan_enabled'] == "1" ? 'checked' : ''; ?>/> <?php _e('Enable Plan', 'erforms'); ?>
                                <label></label>
                            </div>  
                        </div>


                        <div class="erf-child-rows">
                            <div class="erf-row">
                                <div class="erf-control-label">
                                    <label><?php _e('Plan Type', 'erforms'); ?></label>
                                </div>
                                <div class="erf-control erf-has-child">
                                    <input type="radio" checked value="fixed" name="plan_type" data-has-child="1" checked/> <?php _e('Fixed', 'erforms'); ?>
                                    <input type="radio" value="user" name="plan_type"  data-has-child="1" data-child-index="1" <?php echo $form['plan_type'] == "user" ? 'checked' : ''; ?>/> <?php _e('User Defined', 'erforms'); ?>
                                </div>  
                            </div>
                            <div class="erf-child-rows">

                                <div class="erf-row">
                                    <div class="erf-control-label">
                                        <label><?php _e('Fixed Plans', 'erforms'); ?></label>
                                    </div>
                                    <div class="erf-control">
                                        <?php
                                        $dropdown = array('name' => 'fixed_plan_ids[]', 'selected' => $form['fixed_plan_ids']);
                                        if (is_array($form['fixed_plan_ids']))
                                            $dropdown['multiple'] = '';
                                        $plans_drodown = erforms()->plan->get_plans_dropdown($dropdown);
                                        echo $plans_drodown;
                                        ?>
                                        <p class="description">
                                            <?php
                                            $fixed_plans = erforms()->plan->get_plans_by_type('fixed');
                                            if (count($fixed_plans) == 0) {
                                                echo "It seems you haven't created any <b>Fixed</b> type of plan. You can create plans from <a target='_blank' href='" . admin_url('admin.php?page=erforms-plans') . "'>here</a>";
                                            }
                                            ?>
                                        </p>
                                    </div>  
                                </div>
                            </div>  

                            <div class="erf-child-rows">
                                <div class="erf-row">
                                    <div class="erf-control-label">
                                        <label><?php _e('User Defined Plans', 'erforms'); ?></label>
                                    </div>
                                    <div class="erf-control">
                                        <?php
                                        $dropdown = array('name' => 'user_plan_id', 'selected' => $form['user_plan_id']);
                                        $plans_drodown = erforms()->plan->get_plans_dropdown($dropdown, 'user');
                                        echo $plans_drodown;
                                        ?>
                                        <p class="description">
                                            <?php
                                            $user_plans = erforms()->plan->get_plans_by_type('user');
                                            if (count($user_plans) == 0) {
                                                echo "It seems you haven't created any <b>User Defined</b> type of plan. You can create plans from <a target='_blank' href='" . admin_url('admin.php?page=erforms-plans') . "'>here</a>";
                                            }
                                            ?>
                                        </p>
                                    </div>  
                                </div>
                            </div> 

                            <div class="erf-row">
                                <div class="erf-control-label">
                                    <label><?php _e('Required', 'erforms'); ?></label>
                                </div>
                                <div class="erf-control">
                                    <input class="erf_toggle"  type="checkbox" value="1" name="plan_required" <?php echo $form['plan_required'] == "1" ? 'checked' : ''; ?>/>
                                    <label></label>
                                    <p>Marks this payment mandatory for Registration.</p>
                                </div>  
                            </div>

                        </div>

                        <?php do_action('erf_form_config_plans'); ?>    
                    </div>
                <?php endif; ?>
            </div>

            <!-- Edit Submission Settings -->
            <div style="<?php echo $type == 'edit_sub' ? '' : 'display:none' ?>">
                <div class="group-wrap">

                    <div class="erf-row">
                        <div class="erf-control-label">
                            <label><?php _e('Enable Edit Submission', 'erforms'); ?></label>
                        </div>
                        <div class="erf-control erf-has-child">
                            <input class="erf_toggle" type="checkbox" data-has-child="1" name="en_edit_sub" value="1" <?php echo!empty($form['en_edit_sub']) ? 'checked' : ''; ?>>
                            <label></label>
                            <p class="description"><?php _e("This will allow users' to edit/delete their submissions from front end My Account area <code>[erforms_my_account]</code>", 'erforms'); ?></p>
                        </div>  
                    </div>

                    <div class="erf-child-rows" style="display:none">
                        <div class="erf-row">
                            <div class="erf-control-label">
                                <label><?php _e('Allowed Fields for Edit', 'erforms'); ?></label>
                            </div>
                            <div class="erf-control">
                                <?php
                                $fields = erforms()->form->get_fields_dropdown($form['id'], erforms_non_editable_fields());
                                $field_names = array_keys($fields);
                                foreach ($fields as $field_name => $field_label):
                                    ?>
                                    <label class="erf-form-field">
                                        <input  name="edit_fields[]" type="checkbox" value="<?php echo $field_name ?>" <?php echo in_array($field_name, $form['edit_fields']) ? 'checked' : ''; ?>>

                                        <span><?php echo $field_label; ?></span>
                                    </label>
                                <?php endforeach; ?>
                                <br>
                                <p class="description"><?php printf(__("Only above chosen fields will be allowed to edit. Notifications can be configured <a target='_blank' href='%s'>here</a>", 'erforms'), '?page=erforms-dashboard&form_id=' . $form_id . '&tab=notifications&type=edit_submission'); ?></p>
                            </div>  
                        </div>

                        <div class="erf-row">
                            <div class="erf-control-label">
                                <label><?php _e('Allow Deletion from Front end', 'erforms'); ?></label>
                            </div>
                            <div class="erf-control erf-has-child">
                                <div class="erf-control">
                                    <input class="erf_toggle" type="checkbox" data-has-child="1" name="allow_sub_deletion" value="1" <?php echo!empty($form['allow_sub_deletion']) ? 'checked' : ''; ?>>
                                    <label></label>
                                    <p class="description"><?php printf(__("Allows users to delete their submission(s).  Notifications can be configured <a target='_blank' href='%s'>here</a>", 'erforms'), '?page=erforms-dashboard&form_id=' . $form_id . '&tab=notifications&type=delete_submission'); ?></p>
                                </div>    

                            </div>  
                        </div>
                    </div>

                    <?php do_action('erf_form_config_edit_sub'); ?>
                </div>
            </div>
            <!-- Edit Submission ends here -->


            <!-- Form Layout -->
            <div style="<?php echo $type == 'display' ? '' : 'display:none' ?>">
                <div class="group-title">
                    <?php _e('Display Settings', 'erforms'); ?>
                </div>
                <div class="group-wrap">
                    <div class="erf-row">
                        <div class="erf-control-label">
                            <label><?php _e('Layout', 'erforms'); ?></label>
                        </div>
                        <div class="erf-control">
                            <select name="layout">
                                <option <?php echo $form['layout'] == "one-column" ? 'selected' : ''; ?> value="one-column"><?php _e('One Column', 'erforms'); ?></option>
                                <option <?php echo $form['layout'] == "two-column" ? 'selected' : ''; ?> value="two-column"><?php _e('Two Column', 'erforms'); ?></option>
                            </select>    
                        </div>  
                    </div>
                    
                    <div class="erf-row">
                        <div class="erf-control-label">
                            <label><?php _e('Field Style', 'erforms'); ?></label>
                        </div>
                        <div class="erf-control">
                            <select name="field_style">
                                <option <?php echo $form['field_style'] == "flat" ? 'selected' : ''; ?> value="flat"><?php _e('Flat', 'erforms'); ?></option>
                                <option <?php echo $form['field_style'] == "rounded" ? 'selected' : ''; ?> value="rounded"><?php _e('Rounded', 'erforms'); ?></option>
                                <option <?php echo $form['field_style'] == "rounded-corner" ? 'selected' : ''; ?> value="rounded-corner"><?php _e('Rounded Corner', 'erforms'); ?></option>
                                <option <?php echo $form['field_style'] == "border-bottom" ? 'selected' : ''; ?> value="border-bottom"><?php _e('Border Bottom', 'erforms'); ?></option>
                            </select>    
                        </div>  
                    </div>

                    <div class="erf-row">
                        <div class="erf-control-label">
                            <label>Label Position</label>
                        </div>
                        <div class="erf-control">
                            <select name="label_position">
                                <option <?php echo $form['label_position'] == "top" ? 'selected' : ''; ?> value="top"><?php _e('Top', 'erforms'); ?></option>
                                <option <?php echo $form['label_position'] == "inline" ? 'selected' : ''; ?> value="inline"><?php _e('Inline', 'erforms'); ?></option>
                                <option <?php echo $form['label_position'] == "no-label" ? 'selected' : ''; ?> value="no-label"><?php _e('No Label', 'erforms'); ?></option>
                            </select>    
                        </div>  
                    </div>

                    <div class="erf-row">
                        <div class="erf-control-label">
                            <label><?php _e('Content Above The Form', 'erforms'); ?></label>
                        </div>
                        <div class="erf-control">
                            <?php
                            $editor_id = 'before_form';
                            $settings = array('editor_class' => 'erf-editor');
                            wp_editor($form['before_form'], $editor_id, $settings);
                            ?>
                            <p class="desription"><?php _e('This will be displayed above the form.', 'erforms') ?></p>
                        </div>  
                    </div>
                    <?php do_action('erf_form_config_display_settings', $form); ?>
                </div>
            </div>


            <!-- User -->
            <div style="<?php echo $type == 'post_sub' ? '' : 'display:none' ?>">
                <div class="group-title">
                    <?php _e('Post Submission', 'erforms'); ?>
                </div>
                <div class="group-wrap">

                    <div class="erf-row">
                        <div class="erf-control-label">
                            <label><?php _e('After Submission, Redirect User to', 'erforms'); ?></label>
                        </div>
                        <div class="erf-control">
                            <input type="url" name="redirect_to" value="<?php echo $form['redirect_to']; ?>">
                            <?php if ($form['type'] == 'reg') : ?>
                                <p class='description'><?php _e('URL where the user will be redirected after submission. Leave this blank and configure redirection from <b>Global Settings->Enable Role Based Redirection</b> (In case you want to implement role based redirection after auto login).', 'erforms'); ?></p>
                            <?php else: ?>
                                <p class='description'><?php _e('URL where the user will be redirected after submission.', 'erforms'); ?></p>
                            <?php endif; ?>    
                        </div>  
                    </div>

                    <div class="erf-row">
                        <div class="erf-control-label">
                            <label><?php _e('Success Message', 'erforms'); ?></label>
                        </div>
                        <div class="erf-control">
                            <?php
                            $editor_id = 'success_msg';
                            $settings = array('editor_class' => 'erf-editor');
                            wp_editor($form['success_msg'], $editor_id, $settings);
                            ?>
                            <p class="description"><?php _e('This will be displayed after successful submission.', 'erforms') ?></p>
                        </div>  
                    </div>

                    <div class="erf-row">
                        <div class="erf-control-label">
                            <label><?php _e('Post to External URL', 'erforms'); ?></label>
                        </div>
                        <div class="erf-control erf-has-child">
                            <input class="erf_toggle"  type="checkbox" value="1" name="enable_external_url" data-has-child="1" <?php echo $form['enable_external_url'] == '1' ? 'checked' : ''; ?> />
                            <label></label>
                            <p class="description"><?php _e('Posts submission data to external API. Useful for synchronizing submission data on other applications.', 'erforms') ?></p>
                        </div>  
                    </div>

                    <div class="erf-child-rows">
                        <div class="erf-row">
                            <div class="erf-control-label">
                                <label><?php _e('URL', 'erforms'); ?></label>
                            </div>
                            <div class="erf-control">
                                <input type="text" name="external_url" value="<?php echo $form['external_url']; ?>" />
                                <p class='description'><?php _e('API URL which handles submission data.', 'erforms'); ?>
                            </div>  
                        </div>
                    </div>

                    <?php do_action('erf_form_config_post_sub'); ?>
                </div>
            </div>


            <?php do_action('erf_form_configuration', $form,$type); ?>
        </fieldset>

        <p class="submit">
            <input type="hidden" name="erf_save_configuration" />
            <input type="submit" class="button button-primary" value="<?php _e('Save', 'erforms'); ?>" name="save" />
            <input type="submit" value="<?php _e('Save & Close', 'erforms'); ?>" class="button button-primary" name="savec"/>
        </p>
    </form>  
</div>    

<script>
    jQuery(document).ready(function () {
        $ = jQuery;
        var auto_act = $('[name=auto_user_activation]');
        var email_verification = $('[name=en_email_verification]');
        auto_act.change(function () {
            if ($(this).is(':checked')) {
                email_verification.attr('disabled', 'disabled');
                return;
            }
            email_verification.removeAttr('disabled');
        });
    });
</script>
