<?php
/*
Plugin Name: StartUp Testimonials Custom Post
Description: Le plugin pour activer le Custom Post Testimonials
Author: Yann Caplain
Version: 0.1.0
*/

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
		'menu_icon'           => 'dashicons-megaphone',
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
?>