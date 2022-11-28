<?php

class CustomPostTypes
{
	protected $customPostTypes;
	protected $customTaxonomies;

	public function __construct()
	{
		$this->customPostTypes = [
			[
				'slug' => 'testimonial',
				'single' => 'Testimonial',
				'plural' => 'Testimonials',
				'hierarchical' => false,
				'supports' => ['title',  'thumbnail'],
				'taxonomy' => [],
				'menu_icon' => 'dashicons-editor-quote'
			]
		];

		$this->customTaxonomies = [
			// [
			// 	'slug' => 'activities',
			// 	'single' => 'Activity',
			// 	'plural' => 'Activities',
			// 	'hierarchical' => true,
			// 	'post_type' => 'resort',
			// ],
		];
	}

	public function register()
	{
		foreach ($this->customPostTypes as $p) {
			$args = [
				'labels' => [
					'name' => _x($p['plural'], 'post type general name'),
					'singular_name' => _x($p['single'], 'post type singular name'),
					'add_new' => _x('Add New', $p['single']),
					'add_new_item' => __('Add New ' . $p['single']),
					'edit_item' => __('Edit ' . $p['single']),
					'new_item' => __('New ' . $p['single']),
					'view_item' => __('View ' . $p['single']),
					'search_items' => __('Search ' . $p['plural']),
					'not_found' =>  __('No ' . $p['plural'] . ' found'),
					'not_found_in_trash' => __('No ' . $p['plural'] . ' found in Trash'),
					'parent_item_colon' => ''
				],
				'public' => true,
				'publicly_queryable' => true,
				'show_ui' => true,
				'show_in_rest' => true,
				'query_var' => true,
				'rewrite' => true,
				'capability_type' => 'post',
				'hierarchical' => $p['hierarchical'],
				'menu_position' => 5,
				'supports' => $p['supports'],
				'menu_icon' => $p['menu_icon'],
				'taxonomies' => $p['taxonomy']
			];

			register_post_type($p['slug'], $args);
		}

		foreach ($this->customTaxonomies as $t) {
			$args = [
				'labels'            => [
					'name'              => _x($t['plural'], 'taxonomy general name', 'textdomain'),
					'singular_name'     => _x($t['single'], 'taxonomy singular name', 'textdomain'),
					'search_items'      => __('Search ' . $t['plural'], 'textdomain'),
					'all_items'         => __('All ' . $t['plural'], 'textdomain'),
					'parent_item'       => __('Parent ' . $t['single'], 'textdomain'),
					'parent_item_colon' => __('Parent ' . $t['single'], 'textdomain'),
					'edit_item'         => __('Edit ' . $t['single'], 'textdomain'),
					'update_item'       => __('Update ' . $t['single'], 'textdomain'),
					'add_new_item'      => __('Add New ' . $t['single'], 'textdomain'),
					'new_item_name'     => __('New ' . $t['single'] . ' Name', 'textdomain'),
					'menu_name'         => __($t['single'], 'textdomain')
				],
				'hierarchical'      => $t['hierarchical'],
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'query_var'         => true,
				'has_archive' 		=> true,
				'rewrite'           => ['slug' => $t['slug'], 'with_front' => false]
			];

			register_taxonomy($t['slug'], [$t['post_type']], $args);
		}
	}
}
