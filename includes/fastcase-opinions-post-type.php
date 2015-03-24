<?php
// Register Custom Post Type
function fcopinions_post_type() {

	$labels = array(
		'name'                => _x( 'Opinions', 'Post Type General Name', 'fastcase-opinions' ),
		'singular_name'       => _x( 'Opinion', 'Post Type Singular Name', 'fastcase-opinions' ),
		'menu_name'           => __( 'Opinion', 'fastcase-opinions' ),
		'parent_item_colon'   => __( 'Parent Opinion:', 'fastcase-opinions' ),
		'all_items'           => __( 'All opinions', 'fastcase-opinions' ),
		'view_item'           => __( 'View Opinion', 'fastcase-opinions' ),
		'add_new_item'        => __( 'Add New Opinion', 'fastcase-opinions' ),
		'add_new'             => __( 'New Opinion', 'fastcase-opinions' ),
		'edit_item'           => __( 'Edit Opinion', 'fastcase-opinions' ),
		'update_item'         => __( 'Update Opinion', 'fastcase-opinions' ),
		'search_items'        => __( 'Search opinions', 'fastcase-opinions' ),
		'not_found'           => __( 'No opinions found', 'fastcase-opinions' ),
		'not_found_in_trash'  => __( 'No opinions found in Trash', 'fastcase-opinions' ),
	);
	$args = array(
		'label'               => __( 'opinions', 'fastcase-opinions' ),
		'description'         => __( 'Court opinions retreived with through Fastcase API', 'fastcase-opinions' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', 'page-attributes', ),
		'taxonomies'          => array( 'category', 'post_tag' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-admin-post',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	register_post_type( 'opinions', $args );

}

// Hook into the 'init' action
add_action( 'init', 'fcopinions_post_type', 0 );
?>