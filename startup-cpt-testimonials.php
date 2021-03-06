<?php
/*
Plugin Name: StartUp CPT Testimonials
Description: Le plugin pour activer le Custom Post Testimonials
Author: Yann Caplain
Version: 1.0.0
Text Domain: startup-cpt-testimonials
Domain Path: /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

//Include this to check if a plugin is activated with is_plugin_active
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

//Include this to check dependencies
include_once( 'inc/dependencies.php' );

//GitHub Plugin Updater
function startup_cpt_testimonials_updater() {
	include_once 'lib/updater.php';
	//define( 'WP_GITHUB_FORCE_UPDATE', true );
	if ( is_admin() ) {
		$config = array(
			'slug' => plugin_basename( __FILE__ ),
			'proper_folder_name' => 'startup-cpt-testimonials',
			'api_url' => 'https://api.github.com/repos/yozzi/startup-cpt-testimonials',
			'raw_url' => 'https://raw.github.com/yozzi/startup-cpt-testimonials/master',
			'github_url' => 'https://github.com/yozzi/startup-cpt-testimonials',
			'zip_url' => 'https://github.com/yozzi/startup-cpt-testimonials/archive/master.zip',
			'sslverify' => true,
			'requires' => '3.0',
			'tested' => '3.3',
			'readme' => 'README.md',
			'access_token' => '',
		);
		new WP_GitHub_Updater( $config );
	}
}

//add_action( 'init', 'startup_cpt_testimonials_updater' );

//CPT
function startup_cpt_testimonials() {
	$labels = array(
		'name'                => _x( 'Testimonials', 'Post Type General Name', 'startup-cpt-testimonials' ),
		'singular_name'       => _x( 'Testimonial', 'Post Type Singular Name', 'startup-cpt-testimonials' ),
		'menu_name'           => __( 'Testimonials', 'startup-cpt-testimonials' ),
		'name_admin_bar'      => __( 'Testimonials', 'startup-cpt-testimonials' ),
		'parent_item_colon'   => __( 'Parent Item:', 'startup-cpt-testimonials' ),
		'all_items'           => __( 'All Items', 'startup-cpt-testimonials' ),
		'add_new_item'        => __( 'Add New Item', 'startup-cpt-testimonials' ),
		'add_new'             => __( 'Add New', 'startup-cpt-testimonials' ),
		'new_item'            => __( 'New Item', 'startup-cpt-testimonials' ),
		'edit_item'           => __( 'Edit Item', 'startup-cpt-testimonials' ),
		'update_item'         => __( 'Update Item', 'startup-cpt-testimonials' ),
		'view_item'           => __( 'View Item', 'startup-cpt-testimonials' ),
		'search_items'        => __( 'Search Item', 'startup-cpt-testimonials' ),
		'not_found'           => __( 'Not found', 'startup-cpt-testimonials' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'startup-cpt-testimonials' )
	);
	$args = array(
		'label'               => __( 'testimonials', 'startup-cpt-testimonials' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'revisions' ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-format-chat',
		'show_in_admin_bar'   => false,
		'show_in_nav_menus'   => false,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
        'capability_type'     => array('testimonial','testimonials'),
        'map_meta_cap'        => true
	);
	register_post_type( 'testimonials', $args );
}

add_action( 'init', 'startup_cpt_testimonials', 0 );

//Flusher les permalink à l'activation du plugin pour qu'ils fonctionnent sans mise à jour manuelle
function startup_cpt_testimonials_rewrite_flush() {
    startup_cpt_testimonials();
    flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'startup_cpt_testimonials_rewrite_flush' );

// Capabilities
function startup_cpt_testimonials_caps() {	
	$role_admin = get_role( 'administrator' );
	$role_admin->add_cap( 'edit_testimonial' );
	$role_admin->add_cap( 'read_testimonial' );
	$role_admin->add_cap( 'delete_testimonial' );
	$role_admin->add_cap( 'edit_others_testimonials' );
	$role_admin->add_cap( 'publish_testimonials' );
	$role_admin->add_cap( 'edit_testimonials' );
	$role_admin->add_cap( 'read_private_testimonials' );
	$role_admin->add_cap( 'delete_testimonials' );
	$role_admin->add_cap( 'delete_private_testimonials' );
	$role_admin->add_cap( 'delete_published_testimonials' );
	$role_admin->add_cap( 'delete_others_testimonials' );
	$role_admin->add_cap( 'edit_private_testimonials' );
	$role_admin->add_cap( 'edit_published_testimonials' );
}

register_activation_hook( __FILE__, 'startup_cpt_testimonials_caps' );

// Shortcode
function startup_cpt_testimonials_shortcode( $atts ) {

	// Attributes
    $atts = shortcode_atts(array(
            'bg' => '#fff'
        ), $atts);
    
	// Code
    ob_start();
    if ( function_exists( 'startup_reloaded_setup' ) || function_exists( 'startup_revolution_setup' ) ) {
        require get_template_directory() . '/template-parts/content-testimonials.php';
    } else {
        echo 'You should install <a href="https://github.com/yozzi/startup-reloaded" target="_blank">StartUp Reloaded</a> or <a href="https://github.com/yozzi/startup-revolution" target="_blank">StartUp Revolution</a> theme to make things happen...';
    }
    return ob_get_clean();    
}

add_shortcode( 'testimonials', 'startup_cpt_testimonials_shortcode' );

// Shortcode UI
function startup_cpt_testimonials_shortcode_ui() {

    shortcode_ui_register_for_shortcode(
        'testimonials',
        array(
            'label' => esc_html__( 'Testimonials', 'startup-cpt-testimonials' ),
            'listItemImage' => 'dashicons-format-chat',
            'attrs' => array(
                array(
                    'label' => esc_html__( 'Background', 'startup-cpt-testimonials' ),
                    'attr'  => 'bg',
                    'type'  => 'color',
                ),
            ),
        )
    );
};
if ( function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
    add_action( 'init', 'startup_cpt_testimonials_shortcode_ui');
}

// Add code to footer
function startup_cpt_testimonials_footer() { ?>
    <script type="text/javascript">
        jQuery('#testimonials-carousel').carousel({
            interval: 0
        }).on('slide.bs.carousel', function (e){
            var nextH = jQuery(e.relatedTarget).height();
            jQuery(this).find('.active.item').parent().animate({ height: nextH }, 500);
        });
        
        jQuery('#testimonials-carousel').on('slid.bs.carousel', function () {
            jQuery(window).trigger('resize').trigger('scroll');
        })
    </script>
<?php }

add_action( 'wp_footer', 'startup_cpt_testimonials_footer', 15 );

// Enqueue scripts and styles.
function startup_cpt_testimonials_scripts() {
    wp_enqueue_style( 'startup-cpt-testimonials-style', plugins_url( '/css/startup-cpt-testimonials.css', __FILE__ ), array( ), false, 'all' );
}

add_action( 'wp_enqueue_scripts', 'startup_cpt_testimonials_scripts', 15 );
?>