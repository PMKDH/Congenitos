<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ThemeVision
 * @subpackage Fury
 * @since 1.0
 */

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
} 

// Return early if sidebar disabled.
if( 'none' == fury_mod_sidebar() ) {
    return;
} ?>

<!-- Sidebar -->
<div class="col-xl-3 col-lg-4">
    <aside class="sidebar sidebar-offcanvas">
        <?php dynamic_sidebar( 'fury-sidebar' ); ?>
    </aside>
</div><!-- Sidebar End -->
