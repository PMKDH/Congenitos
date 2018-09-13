<?php
/**
 * Generates card view for Form Manager page
 *
 * @package    ERForms
 * @author     ERForms
 * @since      1.0.0
*/
class ERForms_Form_Cards extends ERForms_List_Cards {

	/**
	 * Number of forms to show per page.
	 *
	 * @since 1.0.0
	 */
	public $per_page;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Bring globals into scope for parent.
		global $status, $page;

		// Utilize the parent constructor to build the main class properties.
		parent::__construct(
			array(
				'singular' => 'form',
				'plural'   => 'forms',
				'ajax'     => false,
			)
		);

		// Default number of forms to show per page
		$this->per_page = apply_filters( 'erforms_overview_per_page', 10 );
	}

	/**
	 * Render the checkbox column.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $form
	 *
	 * @return string
	 */
	public function column_cb( $form ) {
		return '<input type="checkbox" name="form_id[]" value="' . absint( $form->ID ) . '" />';
	}

	/**
	 * Message to be displayed when there are no forms.
	 *
	 * @since 1.0.0
	 */
	public function no_items() {
		printf( __( 'Whoops, you haven\'t created a form yet.', 'erforms' ), admin_url( 'admin.php?page=erforms-dashboard' ) );
	}

	/**
	 * Fetch and setup the final data
	 *
	 * @since 1.0.0
	 */
	public function prepare_items() {
                $_SERVER['REQUEST_URI'] = remove_query_arg( '_wp_http_referer', $_SERVER['REQUEST_URI'] );
		
                $this->process_bulk_actions();

		// Define which columns can be sorted - form name, date
		$sortable = array(
			'form_name' => array( 'title', false ),
			'created'   => array( 'date', false ),
		);
                $search = isset($_GET['filter_key']) ? sanitize_text_field(urldecode($_GET['filter_key'])) : '';
		// Get forms
		
		$page     = $this->get_pagenum();
		$order    = isset( $_GET['order'] ) ? $_GET['order'] : 'DESC';
		$orderby  = isset( $_GET['orderby'] ) ? $_GET['orderby'] : 'ID';
		$per_page = $this->get_items_per_page( 'erforms_forms_per_page', $this->per_page );
                $post_query= array(
                                                        'orderby'        => $orderby,
                                                        'order'          => $order,
                                                        'nopaging'       => false,
                                                        'posts_per_page' => $per_page,
                                                        'paged'          => $page,
                                                        'no_found_rows'  => false,
                                                        's'=>$search,
                              );
                
		$data     = erforms()->form->get('',$post_query);
                
                // Fetch total forms
                unset($post_query['posts_per_page']);
                $post_query['nopaging']= true;
                $total_data = erforms()->form->get('',$post_query);
                $total    = count($total_data);
                
		// Giddy up
		$this->items = $data;

		// Finalize pagination
		$this->set_pagination_args(
			array(
				'total_items' => $total,
				'per_page'    => $per_page,
				'total_pages' => ceil( $total / $per_page ),
			)
		);
	}
        
        public function card_actions($form){
                $options= erforms()->options->get_options();
		$out = '<div class="erf-card-actions erf-pad10">';
                $out .= '<div class="erf-action"><a href="'.admin_url('/admin.php?page=erforms-dashboard&form_id='.$form->ID).'"><img  src="'.ERFORMS_PLUGIN_URL.'/assets/admin/images/settings.png"><span>'.__('Dashboard','erforms').'</span></a></div>';
                $out .= '<div class="erf-action"><a target="_blank" href="'.get_permalink($options['preview_page']).'?erform_id='.$form->ID.'"><img src="'.ERFORMS_PLUGIN_URL.'/assets/admin/images/preview.png"><span>'.__('Preview','erforms').'</span></a></div>';
                $out .= '<div class="erf-action"><a href="'.admin_url('/admin.php?page=erforms-dashboard&form_id='.$form->ID.'&tab=build').'"><img src="'.ERFORMS_PLUGIN_URL.'/assets/admin/images/edit.png"><span>'.__('Edit','erforms').'</span></a></div>';
                $duplicate_url= wp_nonce_url(
				add_query_arg(
					array(
						'action'  => 'duplicate',
						'form_id' => $form->ID,
					),
					admin_url( 'admin.php?page=erforms-overview' )
				),
				'erforms_duplicate_form_nonce'
			);
                $out .= '<div class="erf-action"><a href="'.$duplicate_url.'"><img src="'.ERFORMS_PLUGIN_URL.'/assets/admin/images/duplicate.png"><span>'.__('Duplicate','erforms').'</span></a></div>';
                $delete_url= wp_nonce_url(
				add_query_arg(
					array(
						'action'  => 'delete',
						'form_id' => $form->ID,
					),
					admin_url( 'admin.php?page=erforms-overview' )
				),
				'erforms_delete_form_nonce'
			);
                $out .= '<div class="erf-action"><a href="javascript:void(0)" onclick="erf_overview_delete_form(\''.$delete_url.'\')"><img src="'.ERFORMS_PLUGIN_URL.'/assets/admin/images/delete.png"><span>'.__('Delete','erforms').'</span></a></div>';
                
                $out .= '</div>';
		return $out;
	
        }
        
        
        public function card_body($post){
		$out = '<div class="erf-card-content">';
                $out .= '<div class="post-title erf-card-title erf-pad10"><a href="'.admin_url('/admin.php?page=erforms-dashboard&form_id='.$post->ID).'" title="' . $post->post_title .'">'.$post->post_title.'</a></div>';
                $shortcode = '[erforms id="'.$post->ID.'"]';
                $out .= "<div class='erf-short-code-wrap'><input type='text' class='erf-shortcode' title='Click to copy Shortcode' value='"."$shortcode"."' readonly><span class='focus-bg'></span><span style='display: none;' class='copy-message'>Copied to Clipboard</span></div>";
                $form= erforms()->form->get_form($post->ID);
                $form_type= $form['type']=='reg' ? __('Registration','erforms') : __('Contact/Other','erforms');
                $out .='<div class="erf-form-details erf-pad10">';
                $out .= '<div class="erf-form-detail"><span class="erf-detail-title">'.__('Form Type','erforms').' : </span>'.$form_type.'</div>';
                $total= erforms()->submission->get_submissions_by_form($form['id']);
                $out .= '<div class="erf-form-detail"><a href="'.admin_url("/admin.php?page=erforms-submissions&erform_id=".$form['id']).'"><span class="erf-detail-title">'.__('Submissions','erforms').' : </span>'.count($total).' <span class="dashicons dashicons-arrow-right-alt"></span></a></div>';
                $out .='</div>';
                $out .= '</div>';
		return $out;
	
        }
        
        public function process_bulk_actions() {

		$ids = isset( $_GET['form_id'] ) ? $_GET['form_id'] : array();
  
		if ( ! is_array( $ids ) ) {
			$ids = array( $ids );
		}

		$ids    = array_map( 'absint', $ids );
		$action = ! empty( $_REQUEST['action'] ) ? $_REQUEST['action'] : false;

		if ( empty( $ids ) || empty( $action ) ) {
			return;
		}

		// Delete one or multiple forms - both delete links and bulk actions
		if ( 'delete' === $this->current_action() ) {

			if (
				wp_verify_nonce( $_GET['_wpnonce'], 'bulk-forms' ) ||
				wp_verify_nonce( $_GET['_wpnonce'], 'erforms_delete_form_nonce' )
			) {
				foreach ( $ids as $id ) {
					erforms()->form->delete( $id );
				}
				?>
				<div class="notice updated">
					<p>
						<?php
						if ( count( $ids ) === 1 ) {
							_e( 'Form was successfully deleted.', 'erforms' );
						} else {
							_e( 'Forms were successfully deleted.', 'erforms' );
						}
						?>
					</p>
				</div>
				<?php
			} else {
				?>
				<div class="notice updated">
					<p>
						<?php _e( 'Security check failed. Please try again.', 'erforms' ); ?>
					</p>
				</div>
				<?php
			}
		}

		// Duplicate form - currently just delete links (no bulk action at the moment)
		if ( 'duplicate' === $this->current_action() ) {
                        
			if ( wp_verify_nonce( $_GET['_wpnonce'], 'erforms_duplicate_form_nonce' ) ) {
				foreach ( $ids as $id ) {
					erforms()->form->duplicate( $id );
				}
				?>
				<div class="notice updated">
					<p>
						<?php
						if ( count( $ids ) === 1 ) {
							_e( 'Form was successfully duplicated.', 'erforms' );
						} else {
							_e( 'Forms were successfully duplicated.', 'erforms' );
						}
						?>
					</p>
				</div>
				<?php
			} else {
				?>
				<div class="notice updated">
					<p>
						<?php _e( 'Security check failed. Please try again.', 'erforms' ); ?>
					</p>
				</div>
				<?php
			}
		}
	}
}

