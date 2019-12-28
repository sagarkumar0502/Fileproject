<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com/about/anuj
 * @since             1.0.0
 * @package           Woocommerce_Slide_Show
 *
 * @wordpress-plugin
 * Plugin Name:       Woocommerce Slide Show
 * Plugin URI:        http://example.com/about/anuj
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Anuj Panwar
 * Author URI:        http://example.com/about/anuj
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-slide-show
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WOOCOMMERCE_SLIDE_SHOW_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woocommerce-slide-show-activator.php
 */
function activate_woocommerce_slide_show() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-slide-show-activator.php';
	Woocommerce_Slide_Show_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woocommerce-slide-show-deactivator.php
 */
function deactivate_woocommerce_slide_show() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-slide-show-deactivator.php';
	Woocommerce_Slide_Show_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woocommerce_slide_show' );
register_deactivation_hook( __FILE__, 'deactivate_woocommerce_slide_show' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-slide-show.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woocommerce_slide_show() {

	$plugin = new Woocommerce_Slide_Show();
	$plugin->run();

}
run_woocommerce_slide_show();




function woo_slide_show_display_ex(){




	$productswithalldata = wc_get_products( array(
		'status' => 'published',
		'limit'	 =>  5,

	) );


	$products =  array();
	foreach ( $productswithalldata as $product ){

	if ( count($product->get_gallery_image_ids()) !== 0  ){

	$products[] = array(
		'name'     			=>  $product->get_name(),
		'price'    			=>  $product->get_price(),
		'sale_price'    =>  $product->get_sale_price(),
	//	'ratings'       =>  $product->get_average_rating(),
		'ratings'       =>  4,
		'gallery'				=> $product->get_gallery_image_ids()

	);

}


}

	return $products;


}

function woo_slide_show_render(){




	//return $products;


}


function woo_slide_show_display( $attributes ){


	return "<pre>" . var_export( $attributes, true ) . "</pre>";


		$queryargs = array(
			'status' => 'published',
			'limit'	 =>  $attributes['limit'],
			'orderby' => 'date',
			'order' => 'DESC',

		);
		//return "<pre>" . var_export( $attributes, true ) . "</pre>";

		if ( $attributes['tags'] !== false ){
			$atags = trim($attributes['tags']);
			$atags = explode( ',', $atags);
			$queryargs['tag']  = $atags;
		}

		if ( $attributes['categories'] !== false ){
			$acategories = trim($attributes['categories']);
			$acategories = explode( ',', $acategories);
			$queryargs['category']  = $acategories;
		}

		//return "<pre>" . var_export( $attributes, true ) . "</pre>";
		//return "<pre>" . var_export( $queryargs, true ) . "</pre>";

		//return "<pre>" . var_export( $atags, true ) . "</pre>";

		$productswithalldata = wc_get_products( $queryargs );

		$products =  array();
		foreach ( $productswithalldata as $product ){

		if ( count($product->get_gallery_image_ids()) !== 0  ){

		$products[] = array(
			'name'     			=>  $product->get_name(),
			'price'    			=>  $product->get_price(),
			'sale_price'    =>  $product->get_sale_price(),
		//	'ratings'       =>  $product->get_average_rating(),
	   	'ratings'       =>  4,
			'gallery'				=> $product->get_gallery_image_ids()

		);

	}


	}

$slideTitle = $attributes['title'] === '' ? '' :  "<h4 class='woo-slide-main-title'>" . $attributes['title'] . "</h4>";



$slidehtml = '';

$product_html = '';
$sale_html = '';

foreach ( $products as $key => $value ) {
	// Set sale price if not empty
	$sale_html = ! empty( $value['sale_price'] ) ? '<span class="woo-saleprice">&nbsp;/&nbsp;$' . $value['sale_price']  . '</span>' : '';
	$product_html .= '<div class="woo-slide-product-div">';
	$product_html .= '<div class="cycle-slideshow woo-slide-up"  data-cycle-fx="scrollVert"
	data-cycle-shuffle-right=0
	    data-cycle-shuffle-top="-75"
	    data-cycle-speed=1000
	    data-cycle-timeout=2000 >';
	foreach ( $value['gallery'] as $images ){
		$product_html .= wp_get_attachment_image( $images, 'full');

	}
	$product_html .= '</div><!--.woo-slide-up-->';
	$product_html .= '<div class="woo-slide-low">';
	$product_html .= '<div class="woo-slide-detail">';
	$product_html .= '<h3 class="woo-slide-title">' . $value['name'] . '</h3>';
	$product_html .= '<p class="woo-slide-price"><span class="woo-slideprirce">$' . $value['price'] . '</span>' . $sale_html . '</p>';
	$product_html .= '</div><!--.woo-slide-detail-->';
	$product_html .= '<div class="woo-slide-ratings">';
	$product_html .= '<div class="jstars" data-size="30px" data-color="#00b9eb" data-value="' . $value['ratings'] . '"></div>';
	$product_html .= '<a href="#" class="woo-slide-cta">Buy Now</a>';
	$product_html .= '</div><!--.woo-slide-ratings-->';
	$product_html .= '</div><!--.woo-slide-low-->';
	$product_html .= '</div><!--.woo-slide-product-div-->';
}

$slidehtml = <<<EOF
<div class="woo-slide-mainwrapper">
$slideTitle
<div class='cycle-slideshow woo-slide-show-container' data-cycle-timeout='0' data-cycle-slides='> div.woo-slide-product-div'
data-cycle-next="> .woo-slide-show-navigation .woo-slide-show-next" data-cycle-prev="> .woo-slide-show-navigation .woo-slide-show-prev"
>
$product_html
<div class='woo-slide-show-navigation'>
<button class="woo-slide-show-prev">&lt;</button>
<button class="woo-slide-show-next">&gt;</button>
</div>
</div><!--.slider-->
</div><!--.woo-slide-mainwrapper-->
EOF;

return $slidehtml;


}

function test_echo_pre( $var ){

	echo "<pre>" . var_export( $var, true ) . "</pre>" ;


}

add_shortcode('woo-slide-show', 'woo_slide_show_shortcode_cb' );
function woo_slide_show_shortcode_cb( $atts = [], $content = null, $tag = '' ){

	// normalize attribute keys, lowercase
	$atts = array_change_key_case((array)$atts, CASE_LOWER);

	// override default attributes with user attributes
  $parsed_atts = shortcode_atts([
                                   'title' => 'Products Slider',
																	 'limit' => -1,
																	 'include' => 'false'
                               ], $atts, 'woo-slide-show');



return woo_slide_show_display( $atts );

}
