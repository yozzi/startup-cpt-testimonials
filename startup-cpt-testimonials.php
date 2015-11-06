<?php
/*
Plugin Name: StartUp CPT Testimonials
Description: Le plugin pour activer le Custom Post Testimonials
Author: Yann Caplain
Version: 1.0.0
Text Domain: startup-cpt-testimonials
*/

//GitHub Plugin Updater
function startup_reloaded_testimonials_updater() {
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

add_action( 'init', 'startup_reloaded_testimonials_updater' );

//CPT
function startup_reloaded_testimonials() {
	$labels = array(
		'name'                => _x( 'Testimonials', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'Testimonial', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'Testimonials', 'text_domain' ),
		'name_admin_bar'      => __( 'Testimonials', 'text_domain' ),
		'parent_item_colon'   => __( 'Parent Item:', 'text_domain' ),
		'all_items'           => __( 'All Items', 'text_domain' ),
		'add_new_item'        => __( 'Add New Item', 'text_domain' ),
		'add_new'             => __( 'Add New', 'text_domain' ),
		'new_item'            => __( 'New Item', 'text_domain' ),
		'edit_item'           => __( 'Edit Item', 'text_domain' ),
		'update_item'         => __( 'Update Item', 'text_domain' ),
		'view_item'           => __( 'View Item', 'text_domain' ),
		'search_items'        => __( 'Search Item', 'text_domain' ),
		'not_found'           => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' )
	);
	$args = array(
		'label'               => __( 'testimonials', 'text_domain' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'revisions' ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-format-quote',
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

add_action( 'init', 'startup_reloaded_testimonials', 0 );

//Flusher les permalink à l'activation du plugin pour qu'ils fonctionnent sans mise à jour manuelle
function startup_reloaded_testimonials_rewrite_flush() {
    startup_reloaded_testimonials();
    flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'startup_reloaded_testimonials_rewrite_flush' );

// Capabilities
function startup_reloaded_testimonials_caps() {	
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

register_activation_hook( __FILE__, 'startup_reloaded_testimonials_caps' );

// Shortcode
function startup_reloaded_testimonials_shortcode( $atts ) {

	// Attributes
    $atts = shortcode_atts(array(
            'bg' => '#f0f0f0'
        ), $atts);
    
	// Code
        ob_start();
        require get_template_directory() . '/template-parts/content-testimonials.php';
        return ob_get_clean();    
}

add_shortcode( 'testimonials', 'startup_reloaded_testimonials_shortcode' );

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

add_action( 'wp_footer', 'startup_cpt_testimonials_footer', 100 );
?>