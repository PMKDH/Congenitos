<?php
/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$user_info = get_userdata( get_current_user_id() );

echo '<div class="col-md-12">';
    wc_print_notices();
echo '</div>';

/**
 * My Account navigation.
 * @since 2.6.0
 */
echo '<div class="col-lg-4">';
    echo '<aside class="user-info-wrapper">';
        echo '<div class="user-cover">';

        echo '</div>';
        echo '<div class="user-info">';
            echo '<div class="user-avatar">';
                echo '<a href="'. get_edit_user_link() .'" class="edit-avatar"></a>';
                echo get_avatar( get_current_user_id(), 115 );
            echo '</div>';
            echo '<div class="user-data">';
                echo '<h4>'. ucfirst( $user_info->user_login ) .'</h4>';
                echo '<span>'. esc_html__( 'Joined', 'fury' ) .' '. date( 'F d, Y', strtotime( $user_info->user_registered ) ) .'</span>';
            echo '</div>';
        echo '</div>';
    echo '</aside>';
    do_action( 'woocommerce_account_navigation' );
echo '</div>'; ?>

<div class="col-lg-8">
	<?php
		/**
		 * My Account content.
		 * @since 2.6.0
		 */
		do_action( 'woocommerce_account_content' );
	?>
</div>
