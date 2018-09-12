<?php
/**
 * Villagio functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Villagio
 */
if (!function_exists('villagio_setup')) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function villagio_setup()
    {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on villagio, use a find and replace
         * to change 'villagio' to the name of your theme in all the template files.
         */
        load_theme_textdomain('villagio', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');
        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');
        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');
        set_post_thumbnail_size(1434, 734, true);
        add_image_size('villagio-thumb-large', 2000);
        add_image_size('villagio-thumb-medium', 0, 618);
        add_image_size('villagio-thumb-small', 717, 540, true);
		add_image_size('villagio-thumb-small-x2', 1434, 1080, true);

		// This theme uses wp_nav_menu() in one location.
        register_nav_menus(array(
            'menu-1' => esc_html__('Primary', 'villagio'),
            'menu-2' => esc_html__('Header Right', 'villagio'),
            'menu-3' => esc_html__('Footer', 'villagio'),
        ));

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));
        // Add theme support for selective refresh for widgets.
        add_theme_support('customize-selective-refresh-widgets');
        /*
     * This theme styles the visual editor to resemble the theme style,
     * specifically font, colors, icons, and column width.
     */
        add_editor_style(array('css/editor-style.css', villagio_fonts_url()));
    }
endif;
add_action('after_setup_theme', 'villagio_setup');

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 */
if (!isset($content_width)) {
    $content_width = apply_filters('villagio_content_width', 769);
}
function villagio_adjust_content_width() {
	global $content_width;

	if ( is_page_template( 'template-full-width-page.php' ) ) {
		$content_width = 886;
	}
	if ( is_page_template( 'template-wide-screen-page.php' ) ) {
		$content_width = 1354;
	}
}
add_action( 'template_redirect', 'villagio_adjust_content_width' );

/**
 * Get theme vertion.
 *
 * @access public
 * @return string
 */
function villagio_get_theme_version()
{
    $theme_info = wp_get_theme(get_template());

    return $theme_info->get('Version');
}

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function villagio_widgets_init()
{
    register_sidebar(array(
        'name' => esc_html__('Sidebar', 'villagio'),
        'id' => 'sidebar-1',
        'description' => esc_html__('Add widgets here.', 'villagio'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Footer Left', 'villagio'),
        'id' => 'sidebar-2',
        'description' => esc_html__('Appears in the footer section of the site.', 'villagio'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Footer Center', 'villagio'),
        'id' => 'sidebar-3',
        'description' => esc_html__('Appears in the footer section of the site.', 'villagio'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Footer Right', 'villagio'),
        'id' => 'sidebar-4',
        'description' => esc_html__('Appears in the footer section of the site.', 'villagio'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Front Page Top', 'villagio'),
        'id' => 'sidebar-5',
        'description' => esc_html__('Appears on the Front Page.', 'villagio'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
    register_sidebar( array(
    'name'          => esc_html__( 'Shop', 'villagio' ),
    'id'            => 'shop',
    'description'   => esc_html__( 'Add widgets here.', 'villagio' ),
    'before_widget' => '<section id="%1$s" class="widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h2 class="widget-title">',
    'after_title'   => '</h2>',
) );

}

add_action('widgets_init', 'villagio_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function villagio_scripts()
{

    // Add custom fonts, used in the main stylesheet.
    wp_enqueue_style('villagio-fonts', villagio_fonts_url(), array(), null);
    wp_enqueue_style('villagio-style', get_stylesheet_uri(), array(), villagio_get_theme_version());
    if (is_plugin_active('motopress-hotel-booking/motopress-hotel-booking.php')) {
        wp_enqueue_style('villagio-motopress-hotel-booking', get_template_directory_uri() . '/css/motopress-hotel-booking.css', array(
            'villagio-style'
        ), villagio_get_theme_version(), 'all');
    }


    wp_enqueue_script('villagio-navigation', get_template_directory_uri() . '/js/navigation.js', array(), villagio_get_theme_version(), true);

    wp_enqueue_script('villagio-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), villagio_get_theme_version(), true);
    wp_enqueue_script('villagio-script', get_template_directory_uri() . '/js/functions.js', array('jquery'), villagio_get_theme_version(), true);
    if (is_plugin_active('motopress-hotel-booking/motopress-hotel-booking.php')) {
        wp_enqueue_script('villagio-flexslider', get_template_directory_uri() . '/js/jquery.flexslider-min.js', array('jquery'), villagio_get_theme_version(), true);

    }

    wp_localize_script('villagio-script', 'screenReaderText', array(
        'expand' => esc_html__('expand child menu', 'villagio'),
        'collapse' => esc_html__('collapse child menu', 'villagio')
    ));

    $slider_animation = esc_attr(get_theme_mod('villagio_slider_animation', 'slide'));
    $slider_slideshow = esc_attr(get_theme_mod('villagio_slideshow', '0'));
    $slider_autoplay_timeout = esc_attr(get_theme_mod('villagio_slideshow_speed', '7000'));
    wp_localize_script('villagio-script', 'mphbSlider', array(
        'animation' => $slider_animation,
        'slideshow' => $slider_slideshow,
        'autoplayTimeout' => $slider_autoplay_timeout
    ));
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'villagio_scripts');

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom template tags for mphb this theme.
 */
if (is_plugin_active('motopress-hotel-booking/motopress-hotel-booking.php')) {
    require get_template_directory() . '/inc/template-tags-mphb.php';
}

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
/**
 * Load TGM plugin activation.
 */
if (current_user_can('install_plugins')) {
    require get_template_directory() . '/inc/tgm-init.php';
}
/**
 * Load WooCommerce compatibility file.
 */
if(class_exists( 'WooCommerce' )){
    require get_template_directory() . '/inc/woocommerce.php';
}

if (!function_exists('villagio_the_custom_logo')) :
    /**
     * Displays the optional custom logo.
     *
     * Does nothing if the custom logo is not available.
     *
     * @since Villagio 1.0.0
     */
    function villagio_the_custom_logo()
    {
        if (function_exists('the_custom_logo')) {
            the_custom_logo();
        }
    }
endif;


if (!function_exists('villagio_fonts_url')) :
    /**
     * Register Google fonts for Villagio.
     *
     * Create your own villagio_fonts_url() function to override in a child theme.
     *
     * @since Villagio 1.0.0
     *
     * @return string Google fonts URL for the theme.
     */
    function villagio_fonts_url()
    {
        $fonts_url = '';
        $font_families = array();

        /**
         * Translators: If there are characters in your language that are not
         * supported by Lato, translate this to 'off'. Do not translate
         * into your own language.
         */
        if ('off' !== esc_html_x('on', 'Lato font: on or off', 'villagio')) {
            $font_families[] = 'Lato:100,100i,300,300i,400,400i,700,700i,900';
        }
        if ($font_families) {
            $query_args = array(
                'family' => urlencode(implode('|', $font_families)),
                'subset' => urlencode('latin,latin-ext'),
            );
            $fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');
        }

        return esc_url_raw($fonts_url);

        $fonts_url = '';
    }
endif;
/**
 * Modifies tag cloud widget arguments to have all tags in the widget same font size.
 *
 * @since Villagio 1.0.0
 *
 * @param array $args Arguments for tag cloud widget.
 *
 * @return array A new modified arguments.
 */
function villagio_widget_tag_cloud_args($args)
{
    $args['largest'] = 0.875;
    $args['smallest'] = 0.875;
    $args['unit'] = 'rem';

    return $args;
}

add_filter('widget_tag_cloud_args', 'villagio_widget_tag_cloud_args');

/*
 * Filters the title of the default page template displayed in the drop-down.
 */
function villagio_default_page_template_title() {
	return esc_html__( 'Page with sidebar', 'villagio' );
}

add_filter( 'default_page_template_title', 'villagio_default_page_template_title' );
