<?php

namespace MPHB\Admin;

abstract class AdminPage {

	/**
	 * Custom actions that output just after the page heading
	 *
	 * @var array [%URL% => %Label%]
	 */
	protected $titleActions = array();

	public function __construct(){
		add_action( 'admin_footer', array( $this, 'addTitleActionsScript' ) );
	}

	public function addTitleAction( $url, $label ){
		$this->titleActions[$url] = $label;
	}

	public function addTitleActionsScript(){
		if ( $this->isCurrentPage() && !empty( $this->titleActions ) ) {
			$actions = array();

			foreach ( $this->titleActions as $url => $label ) {
				$actions[] = '<a href="' . esc_url( $url ) . '" class="page-title-action">' . $label . '</a>';
			}

			?>
			<script type="text/javascript">
				jQuery( function() {
					var actions = ['<?php echo join( "', '", $actions ); ?>'];
					var $heading = jQuery( '#wpbody-content > .wrap > .wp-heading-inline' );

					actions.forEach( function( action ) {
						$heading.after( action );
					});
				} );
			</script>
			<?php
		}
	}

}
