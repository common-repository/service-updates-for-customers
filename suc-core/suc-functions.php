<?php


/**
 * suc_register_post_types()
 *
 * The meat of this whole operation, this is where we register our post types
 *
 * @global array $suc_page_titles
 */

function suc_register_post_types() {
	global $suc_page_titles;
        $labels = array(
            'name' => _x( 'Service', 'post type name', 'suc' ),
            'singular_name' => _x( 'Service', 'post type singular name', 'Service' ),
            'add_new' => _x( 'Add New', 'admin menu: add new service', 'suc' ),
            'add_new_item' => __('Add New Service', 'suc' ),
            'edit_item' => __('Edit Service', 'suc' ),
            'new_item' => __('New Service', 'suc' ),
            'view_item' => __('View Service', 'suc' ),
            'search_items' => __('Search Service', 'suc' ),
            'not_found' =>  __('No Service found', 'suc' ),
            'not_found_in_trash' => __( 'No Service found in Trash', 'suc' ),
            'parent_item_colon' => '',
            'menu_name' => __( 'Services', 'suc' )
          );
	// Service
			$post_supports = array(
			 'title'
			,'editor'
			,'author'
			,'thumbnail'
			,'excerpt'
			,'trackbacks'
			,'custom-fields'
			,'comments'
			,'revisions'
		  );
	register_post_type( 'suc-service', array(
		'capability_type' => 'post',
		'hierarchical' => false,
		'exclude_from_search' => false,
		'public' => true,
		'show_ui' => true,
		'show_in_nav_menus' => true,
                'menu_icon' =>   false,
		'labels' => $labels,
		'supports' => $post_supports,
		'query_var' => true,
		'register_meta_box_cb' => false,
		'rewrite' => array(
			'slug' => false,
			'with_front' => false
		)
	) );

	

	// Service tags
	$labels = array( 'name' => _x( 'Service Tags', 'taxonomy general name', 'suc' ),
		'singular_name' => _x( 'Service Tag', 'taxonomy singular name', 'suc' ),
		'search_items' => __( 'Service Search Tags', 'suc' ),
		'all_items' => __( 'All Service Tags' , 'suc'),
		'edit_item' => __( 'Edit Tag', 'suc' ),
		'update_item' => __( 'Update Tag', 'suc' ),
		'add_new_item' => __( 'Add new Service Tag', 'suc' ),
		'new_item_name' => __( 'New Service Tag Name', 'suc' ) );

	register_taxonomy( 'product_tag', 'suc-service', array(
		'hierarchical' => false,
		'labels' => $labels,
		'rewrite' => array(
			'slug' => '/' . sanitize_title_with_dashes( _x( 'tagged', 'slug, part of url', 'suc' ) ),
			'with_front' => false )
	) );

	// Service categories, is heirarchical and can use permalinks
	$labels = array(
		'name' => _x( 'Service Categories', 'taxonomy general name', 'suc' ),
		'singular_name' => _x( 'Service Category', 'taxonomy singular name', 'suc' ),
		'search_items' => __( 'Search Product Categories', 'suc' ),
		'all_items' => __( 'All Service Categories', 'suc' ),
		'parent_item' => __( 'Parent Service Category', 'suc' ),
		'parent_item_colon' => __( 'Parent Service Category:', 'suc' ),
		'edit_item' => __( 'Edit Service Category', 'suc' ),
		'update_item' => __( 'Update Service Category', 'suc' ),
		'add_new_item' => __( 'Add New Service Category', 'suc' ),
		'new_item_name' => __( 'New Service Category Name', 'suc' ),
		'menu_name' => _x( 'Categories', 'taxonomy general name', 'suc' )
	);

	register_taxonomy( 'suc_service_category', 'suc-service', array(
		'hierarchical' => true,
		'rewrite' => array(
			'slug' =>false,
			'with_front' => false,
			'hierarchical' => (bool) get_option( 'product_category_hierarchical_url', 0 ),
		),
		'labels' => $labels,
	) );
	$labels = array(
		'name' => _x( 'Variations', 'taxonomy general name', 'suc' ),
		'singular_name' => _x( 'Variation', 'taxonomy singular name', 'suc' ),
		'search_items' => __( 'Search Variations', 'suc' ),
		'all_items' => __( 'All Variations', 'suc' ),
		'parent_item' => __( 'Parent Variation', 'suc' ),
		'parent_item_colon' => __( 'Parent Variations:', 'suc' ),
		'edit_item' => __( 'Edit Variation', 'suc' ),
		'update_item' => __( 'Update Variation', 'suc' ),
		'add_new_item' => __( 'Add New Variation/Set', 'suc' ),
		'new_item_name' => __( 'New Variation Name', 'suc' ),
	);

	// Service Variations, is internally heirarchical, externally, two separate types of items, one containing the other
	register_taxonomy( 'suc-variation', 'suc-product', array(
		'hierarchical' => false,
		'query_var' => 'variations',
		'rewrite' => false,
		'public' => true,
		'labels' => $labels
	) );
	$role = get_role( 'administrator' );
	$role->add_cap( 'read_suc-product' );
	$role->add_cap( 'read_suc-product-file' );
}
add_action( 'init', 'suc_register_post_types', 8 );





?>