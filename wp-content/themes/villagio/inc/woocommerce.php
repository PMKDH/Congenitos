<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package _s
 */

/**
 * WooCommerce setup function.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)-in-3.0.0
 *
 * @return void
 */
function villagio_woocommerce_setup()
{
    //since woocommerce 3.3
    add_theme_support('woocommerce',
        apply_filters('villagio_woocommerce_image_size',
            array(
                'single_image_width' => '712',
                'thumbnail_image_width' => '342',
            )
        )
    );
    //theme aspect ratio
    update_option('woocommerce_thumbnail_cropping',
        apply_filters('villagio_woocommerce_thumbnail_cropping', '1:1.3')
    );
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}

add_action('after_setup_theme', 'villagio_woocommerce_setup');
/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function villagio_woocommerce_scripts()
{
    wp_enqueue_style('villagio_woocommerce-style', get_template_directory_uri() . '/woocommerce.css');
}

add_action('wp_enqueue_scripts', 'villagio_woocommerce_scripts');

if (!function_exists('villagio_before_content')) {
    /**
     * Before Content
     * Wraps all WooCommerce content in wrappers which match the theme markup
     *
     * @return  void
     */
    function villagio_before_content()
    {

        ?>
        <div class="wrapper main-wrapper clear">
        <div id="primary" class="content-area ">
        <main id="main" class="site-main" role="main">
        <?php
    }
}

if (!function_exists('villagio_after_content')) {
    /**
     * After Content
     * Closes the wrapping divs
     *
     * @return  void
     */
    function villagio_after_content()
    {
        ?>
        </main><!-- #main -->
        </div><!-- #primary -->
        <?php
        do_action('villagio_sidebar');
        ?>
        </div><!-- .wrapper -->
        <?php
    }
}

if (!function_exists('villagio_get_sidebar')) {
    /**
     * Display villagio sidebar
     *
     * @uses get_sidebar()
     */
    function villagio_get_sidebar()
    {
        if (is_active_sidebar('shop')) { ?>
            <aside id="secondary" class="widget-area" role="complementary">
                <?php dynamic_sidebar('shop'); ?>
            </aside><!-- #secondary -->
        <?php }
    }
}


remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
add_action('woocommerce_before_main_content', 'villagio_before_content', 10);
add_action('woocommerce_after_main_content', 'villagio_after_content', 10);
add_action('villagio_sidebar', 'villagio_get_sidebar', 10);


if (!function_exists('villagio_before_shop_loop_filter')) {
    /**
     * Display villagio sidebar
     *
     * @uses get_sidebar()
     */
    function villagio_before_shop_loop_filter()
    {
        ?>
        <div class="shop-filter-wrapper">
        <?php
    }
}
if (!function_exists('villagio_after_shop_loop_filter')) {
    /**
     * Display villagio sidebar
     *
     * @uses get_sidebar()
     */
    function villagio_after_shop_loop_filter()
    {
        ?>
        </div><!-- .shop-filter-wrapper -->
        <?php
    }
}

add_action('woocommerce_before_shop_loop', 'villagio_before_shop_loop_filter', 10);
add_action('woocommerce_before_shop_loop', 'villagio_after_shop_loop_filter', 50);

add_action('woocommerce_after_shop_loop', 'villagio_before_shop_loop_filter', 0);
add_action('woocommerce_after_shop_loop', 'woocommerce_result_count', 20);
add_action('woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 30);
add_action('woocommerce_after_shop_loop', 'villagio_after_shop_loop_filter', 40);


if (!function_exists('villagio_woocommerce_pagination_args')) {
    /**
     * Define the woocommerce_pagination_args callback
     *
     */
    function villagio_woocommerce_pagination_args($array)
    {
        $array['end_size'] = '1';
        $array['mid_size'] = '0';
        return $array;
    }
}

add_filter('woocommerce_pagination_args', 'villagio_woocommerce_pagination_args', 10, 1);

if (!function_exists('villagio_loop_shop_columns')) {
    /**
     * Change number or products per row to 3
     *
     */
    function villagio_loop_shop_columns($int)
    {
        return 3;
    }
}


add_filter('loop_shop_columns', 'villagio_loop_shop_columns', 999);
if (!function_exists('villagio_woocommerce_breadcrumb_defaults')) {
    /**
     * Define the woocommerce_breadcrumb_defaults callback
     *
     */
    function villagio_woocommerce_breadcrumb_defaults($array)
    {
        $array['delimiter'] = '<span class="delimiter">|</span>';
        return $array;
    }

}
add_filter('woocommerce_breadcrumb_defaults', 'villagio_woocommerce_breadcrumb_defaults', 10, 1);
if (!function_exists('villagio_woocommerce_output_related_products_args')) {
    /**
     * Change number of related products on product page
     * Set your own value for 'posts_per_page'
     *
     */
    function villagio_woocommerce_output_related_products_args($args)
    {
        $args['posts_per_page'] = 3;
        $args['columns'] = 3;
        return $args;
    }
}
add_filter('woocommerce_output_related_products_args', 'villagio_woocommerce_output_related_products_args');
/**
 * Change single product rating position
 *
 */

remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 15);

if (!function_exists('villagio_woocommerce_product_thumbnails_columns')) {
    /**
     * Define the woocommerce_product_thumbnails_columns callback
     *
     */
    function villagio_woocommerce_product_thumbnails_columns($int)
    {
        return 3;
    }

}
add_filter('woocommerce_product_thumbnails_columns', 'villagio_woocommerce_product_thumbnails_columns', 10, 1);

/**
 * Modifies tag cloud widget arguments to have all tags in the widget same font size.
 */
add_filter('woocommerce_product_tag_cloud_widget_args', 'villagio_widget_tag_cloud_args');


if (!function_exists('villagio_woocommerce_cross_sells_total')) {
    /**
     * Display Only 3 Cross Sells instead of default 4
     *
     */
    function villagio_woocommerce_cross_sells_total($args)
    {
        return 3;
    }
}
add_filter('woocommerce_cross_sells_total', 'villagio_woocommerce_cross_sells_total');
add_filter('woocommerce_cross_sells_columns', 'villagio_woocommerce_cross_sells_total');
/*
/* Remove Cross Sells From Default Position
*/
remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
/*
* Add them back UNDER the Cart Table
*/

add_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display', 20);


if (!function_exists('villagio_is_woocommerce_activated')) {
    /**
     * Query WooCommerce activation
     */
    function villagio_is_woocommerce_activated()
    {
        return class_exists('WooCommerce') ? true : false;
    }
}

if (!function_exists('villagio_header_cart')) {
    /**
     * Display Header Cart
     *
     * @since  1.0.0
     * @uses  villagio_is_woocommerce_activated() check if WooCommerce is activated
     * @return void
     */
    function villagio_header_cart()
    {
        if (villagio_is_woocommerce_activated()) {
            if (is_cart()) {
                $class = 'current-menu-item';
            } else {
                $class = '';
            }
            ?>
            <ul id="site-header-cart" class="site-header-cart menu">
                <li class="<?php echo esc_attr($class); ?>">
                    <?php villagio_cart_link(); ?>
                </li>
                <li class="cart-widget">
                    <?php the_widget('WC_Widget_Cart', 'title='); ?>
                </li>
            </ul>
            <?php
        }
    }
}

if (!function_exists('villagio_cart_link')) {
    /**
     * Cart Link
     * Displayed a link to the cart including the number of items present and the cart total
     *
     * @return void
     * @since  1.0.0
     */
    function villagio_cart_link()
    {
        $class = 'cart-contents';
        if (WC()->cart->get_cart_contents_count() === 0) {
            $class .= ' empty-cart';
        }
        ?>
        <a class="<?php echo $class; ?>" href="<?php echo esc_url(wc_get_cart_url()); ?>"
           title="<?php esc_attr_e('View your shopping cart', 'villagio'); ?>">
            <span class="amount"><?php echo wp_kses_data(WC()->cart->get_cart_subtotal()); ?></span>
            <span class="count"><?php echo wp_kses_data(sprintf(_n('%d item', '%d items', WC()->cart->get_cart_contents_count(), 'villagio'), WC()->cart->get_cart_contents_count())); ?></span>
            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
        </a>
        <?php
    }
}

add_action('villagio_header', 'villagio_header_cart', 60);


if (!function_exists('villagio_cart_link_fragment')) {
    /**
     * Cart Fragments
     * Ensure cart contents update when products are added to the cart via AJAX
     *
     * @param  array $fragments Fragments to refresh via AJAX.
     * @return array            Fragments to refresh via AJAX
     */
    function villagio_cart_link_fragment($fragments)
    {
        global $woocommerce;
        ob_start();
        villagio_cart_link();
        $fragments['a.cart-contents'] = ob_get_clean();
        return $fragments;
    }
}
/**
 * Cart fragment
 *
 * @see villagio_cart_link_fragment()
 */
if (defined('WC_VERSION') && version_compare(WC_VERSION, '2.3', '>=')) {
    add_filter('woocommerce_add_to_cart_fragments', 'villagio_cart_link_fragment');
} else {
    add_filter('add_to_cart_fragments', 'villagio_cart_link_fragment');
}

if (!function_exists('villagio_woocommerce_get_price_html')) {
    /**
     *
     * Code used to change the price order in WooCommerce
     *
     * */
    function villagio_woocommerce_get_price_html($price, $product)
    {
        return preg_replace('@(<del>.*?</del>).*?(<ins>.*?</ins>)@misx', '$2 $1', $price);
    }
}
add_filter('woocommerce_get_price_html', 'villagio_woocommerce_get_price_html', 100, 2);

if (!function_exists('villagio_woocommerce_review_gravatar_size')) {
    /*
     * Define the woocommerce_review_gravatar_size callback
     */
    function villagio_woocommerce_review_gravatar_size($size)
    {
        return 80;
    }
}
add_filter('woocommerce_review_gravatar_size', 'villagio_woocommerce_review_gravatar_size', 10, 1);

if (!function_exists('villagio_loop_shop_per_page')) {
    /*
     * Define the number of products show per page.
     */
    function villagio_loop_shop_per_page($cols)
    {
        return 12;
    }
}
add_filter('loop_shop_per_page', 'villagio_loop_shop_per_page', 20);
