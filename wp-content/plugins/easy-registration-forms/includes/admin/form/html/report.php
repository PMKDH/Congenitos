<?php
if ($form['type'] != 'reg')
    return;
wp_enqueue_style('erf-timepicker', ERFORMS_PLUGIN_URL . 'assets/admin/css/jquery.timepicker.min.css');
wp_enqueue_script('erf-timepicker', ERFORMS_PLUGIN_URL . 'assets/admin/js/jquery.timepicker.min.js');
$index = isset($_REQUEST['index']) ? absint($_REQUEST['index']) : 0;
$report = null;
if (isset($form['reports'][$index]) && isset($_REQUEST['index'])) {
    $nonce = isset($_REQUEST['nonce']) ? $_REQUEST['nonce'] : '';
    if (!wp_verify_nonce($nonce, 'erf-report-edit-nonce')) {
        die('Invalid security token, Please go tp Reports page and try again.');
    }
    $report = $form['reports'][$index];
}
?>
<div class="erf-form-report-wrapper">
    <form method="POST" action="<?php echo admin_url('?page=erforms-dashboard&form_id=' . $form_id . '&tab=reports'); ?>">
        <fieldset>
            <h1><?php _e('Add/Edit Report','erforms'); ?></h1>
            <div class="erf-row">
                <div class="erf-control-label">
                    <label><?php _e('Name', 'erforms'); ?></label>
                </div>
                <div class="erf-control">
                    <input type="text" name="name" required value="<?php echo isset($report['name']) ? $report['name'] : ''; ?>"/>
                    <p class="description"><?php _e('Report Name.', 'erforms'); ?></p>
                </div>  
            </div>

            <div class="erf-row">
                <div class="erf-control-label">
                    <label><?php _e('Description', 'erforms'); ?></label>
                </div>
                <div class="erf-control">
                    <textarea name="description"><?php echo isset($report['description']) ? $report['description'] : ''; ?></textarea>
                    <p class="description"><?php _e('Report Description.', 'erforms'); ?></p>
                </div>  
            </div>

            <!-- Sortable Field -->
            <div class="erforms_sortable_fields-wrap">
                <ul id="erforms_sortable_fields">
                    <?php
                    $fields = erforms_get_report_fields($form_id);
                    $form_fields = erforms()->form->get_fields_dropdown($form_id);
                    $form_field_names = array_keys($form_fields);
                    $df_sub_fields = erforms_get_default_submission_fields();
                    ?>
                    <?php if (!empty($report) && !empty($report['fields'])): ?>
                        <?php foreach ($report['fields'] as $name => $field): ?>
                            <?php
                            if (isset($form_fields[$name])) {
                                unset($form_fields[$name]);
                            }

                            if (!in_array($name, $form_field_names) && !in_array($name, $df_sub_fields))
                                continue; // Making sure to exclude deleted fields
                            ?>
                            <li class="ui-state-default" id="<?php echo $name; ?>">
                                <div class="group-wrap">
                                    <div class="erf-report-field-label"><div class="field-arrow fa fa-arrows-v"></div> <?php echo $field['label']; ?></div>

                                    <div class="erf-report-field-options" style="display:none">
                                        <div class="erf-row">
                                            <div class="erf-control-label">
                                                <label><?php _e('Alias', 'erforms'); ?></label>
                                            </div>
                                            <div class="erf-control">
                                                <input type="text" name="<?php echo $name; ?>_alias" value="<?php echo $field['alias']; ?>"/>
                                            </div>  
                                        </div>

                                        <div class="erf-row">
                                            <div class="erf-control-label">
                                                <label><?php _e('Include in report', 'erforms'); ?></label>
                                            </div>
                                            <div class="erf-control">
                                                <input  type="checkbox" name="<?php echo $name; ?>_included" value="1" <?php echo empty($field['included']) ? '' : 'checked'; ?>></textarea>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                            </li>  

                        <?php endforeach; ?>
                    <?php else : ?> 
                        <?php foreach ($fields as $name => $label) : ?>
                            <li class="ui-state-default" id="<?php echo $name; ?>">
                                <div class="group-wrap">
                                    <div class="erf-report-field-label"><div class="field-arrow fa fa-arrows-v"></div> <?php echo $label; ?></div>

                                    <div class="erf-report-field-options" style="display:none">
                                        <div class="erf-row">
                                            <div class="erf-control-label">
                                                <label><?php _e('Alias', 'erforms'); ?></label>
                                            </div>
                                            <div class="erf-control">
                                                <input type="text" name="<?php echo $name; ?>_alias" value="<?php echo $label; ?>"/>
                                            </div>  
                                        </div>


                                        <div class="erf-row">
                                            <div class="erf-control-label">
                                                <label><?php _e('Include in report', 'erforms'); ?></label>
                                            </div>
                                            <div class="erf-control">
                                                <input  type="checkbox" name="<?php echo $name; ?>_included" value="1" checked></textarea>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                            </li>

                        <?php endforeach; ?>      
                    <?php endif; ?>

                    <?php if (!empty($report)): ?>             
                        <?php foreach ($form_fields as $fn => $fl): //Printing new fields from dropdown ?>  
                            <li class="ui-state-default" id="<?php echo $fn; ?>">
                                <div class="group-wrap">
                                    <div class="erf-report-field-label"><div class="field-arrow fa fa-arrows-v"></div> <?php echo $fl; ?></div>

                                    <div class="erf-report-field-options" style="display:none">
                                        <div class="erf-row">
                                            <div class="erf-control-label">
                                                <label><?php _e('Alias', 'erforms'); ?></label>
                                            </div>
                                            <div class="erf-control">
                                                <input type="text" name="<?php echo $fn; ?>_alias" value="<?php echo $fl; ?>"/>
                                            </div>  
                                        </div>

                                        <div class="erf-row">
                                            <div class="erf-control-label">
                                                <label><?php _e('Include in report', 'erforms'); ?></label>
                                            </div>
                                            <div class="erf-control">
                                                <input  type="checkbox" name="<?php echo $fn; ?>_included" value="1"></textarea>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                            </li> 
                        <?php endforeach; ?>  
                    <?php endif; ?>               

                </ul>
            </div>
            <!-- Sortable Fields area ends here -->

            <div class="erf-row">
                <div class="erf-control-label">
                    <label><?php _e('Receipents', 'erforms'); ?></label>
                </div>
                <div class="erf-control">
                    <input type="text" name="receipents" value="<?php echo isset($report['receipents']) ? $report['receipents'] : ''; ?>"/>
                    <p class="description"><?php echo __('Email where you want to receive the report. Multiple emails can be given using comma(,) sepration. In case this value is empty, system will send the notification to site admin ', 'erforms') . '(' . get_option('admin_email') . ')'; ?></p>
                </div>  
            </div>

            <div class="erf-row">
                <div class="erf-control-label">
                    <label><?php _e('Email Subject', 'erforms'); ?></label>
                </div>
                <div class="erf-control">
                    <input type="text" required name="email_subject" value="<?php echo isset($report['email_subject']) ? $report['email_subject'] : $form['title'] . ' Report'; ?>"/>
                    <p class="description"><?php echo __('Subject of the email.', 'erforms'); ?></p>
                </div>  
            </div>

            <div class="erf-row">
                <div class="erf-control-label">
                    <label><?php _e('Email Message', 'erforms'); ?></label>
                </div>
                <div class="erf-control">
                    <?php
                    $email_message = isset($report['email_message']) ? $report['email_message'] : 'Please find the attached report.';
                    echo wp_editor($email_message, 'email_message');
                    ?>
                </div>  
            </div>

            <div class="erf-row">
                <div class="erf-control-label">
                    <label><?php _e('Status', 'erforms'); ?></label>
                </div>
                <div class="erf-control">
                    <select name="active">
                        <option <?php echo isset($report['active']) && $report['active'] == '1' ? 'selected' : ''; ?> value="1"><?php _e('Active', 'erforms'); ?></option>
                        <option <?php echo isset($report['active']) && $report['active'] == '0' ? 'selected' : ''; ?> value=""><?php _e('Deactive', 'erforms'); ?></option>
                    </select>
                    <p class="description"><?php _e('Activate/Deactivate the report. Report will not be sent for Deactivated status.', 'erforms'); ?></p>
                </div>  
            </div>

            <div class="erf-row">
                <div class="erf-control-label">
                    <label><?php _e('Time Range (Days)', 'erforms'); ?></label>
                </div>
                <div class="erf-control">
                    <select name="range">
                        <option <?php echo isset($report['range']) && $report['range'] == '1' ? 'selected' : ''; ?> value="1">1</option>
                        <option <?php echo isset($report['range']) && $report['range'] == '2' ? 'selected' : ''; ?> value="2">2</option>
                        <option <?php echo isset($report['range']) && $report['range'] == '7' ? 'selected' : ''; ?> value="7">7</option>
                    </select>
                    <p class="description"><?php _e('Only those submissions will be included in report which are submitted during this time range.', 'erforms'); ?></p>
                </div>  
            </div>

            <div class="erf-row">
                <div class="erf-control-label">
                    <label><?php _e('Starting Time', 'erforms'); ?></label>
                </div>
                <div class="erf-control">
                    <input value="<?php echo isset($report['time']) ? $report['time'] : ''; ?>" id="erforms_time" required="" name="time" type="text" class="time ui-timepicker-input" autocomplete="off">
                    <p class="description"><?php _e('The first time that you want to send the report.', 'erforms'); ?></p>
                </div>  
            </div>


            <div class="erf-row">
                <div class="erf-control-label">
                    <label><?php _e('Recurrence', 'erforms'); ?></label>
                </div>
                <div class="erf-control">
                    <select name="recurrence">
                        <option <?php echo (isset($report['recurrence']) && $report['recurrence'] == 'twicedaily') ? 'selected' : ''; ?> value="twicedaily"><?php _e('Twice Daily', 'erforms'); ?></option>
                        <option <?php echo (isset($report['recurrence']) && $report['recurrence'] == 'daily') ? 'selected' : ''; ?> value="daily"><?php _e('Daily', 'erforms'); ?></option>
                    </select>
                    <p class="description"><?php _e('How often the report should send.', 'erforms'); ?></p>
                </div>  
            </div>
            <?php
            $field_names = array();
            if (!empty($report) && !empty($report['fields'])) {
                $field_names = array_keys($report['fields']);
            }
            // erforms_debug($field_names);
            foreach ($field_names as $name_index => $fn) {
                if (!in_array($fn, $form_field_names) && !in_array($fn, $df_sub_fields)) {
                    unset($field_names[$name_index]);
                }
            }
            // erforms_debug($field_names); die;
            ?>
            <input type="hidden" name="field_names" id="erf_field_names" value="<?php echo empty($field_names) ? '' : implode(',', $field_names); ?>" />
            <input type="hidden" name="created" value="<?php echo isset($report['created']) ? $report['created'] : '' ?>" />
            <input type="hidden" name="index" value="<?php echo empty($report) ? -1 : $index; ?>" />
            <input type="hidden" name="erf_save_report" value="1" />
            <p class="submit">
                <input type="submit" class="button button-primary" value="<?php _e('Save', 'erforms'); ?>" name="save" /> 
            </p>
        </fieldset>
    </form>


<?php wp_enqueue_script('jquery-ui-sortable'); ?>
    <script>
        jQuery(document).ready(function () {
            $ = jQuery;

            $('#erforms_sortable_fields').sortable({
                stop: function (e, ui) {
                    var fieldName = $(this).sortable('toArray', {attribute: 'id'});
                    console.log(fieldName);
                    $('#erf_field_names').val(fieldName);
                }
            });
            $("#erforms_sortable_fields").disableSelection();

            // Timepicker
            $('#erforms_time').timepicker();

            $('.erf-report-field-label').click(function () {
                var optionsContainer = $(this).next('.erf-report-field-options');
                optionsContainer.slideToggle();
            });
        });
    </script>
</div>