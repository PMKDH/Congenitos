<?php 
// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
} ?>

<form class="input-group form-group" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    
    <span class="input-group-btn">
        <button type="submit"><i class="icon-search"></i></button>
    </span>
    
    <input name="s" class="form-control" type="search" placeholder="<?php esc_attr_e( 'Search', 'fury' ); ?>" value="<?php echo get_search_query() ?>" title="<?php echo esc_attr_x( 'Search for:', 'Search for', 'fury' ) ?>" />
    
</form>
