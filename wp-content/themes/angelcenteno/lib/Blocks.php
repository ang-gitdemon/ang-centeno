<?php

require_once get_template_directory() . '/lib/helpers/BlockFileGenerator.php';

class Blocks
{
	public $customBlocks;
	protected $customBlockCategories;

	public function __construct()
	{
		$this->customBlockCategories = [
			[
				'slug' => 'theme-blocks',
				'title' => __('Theme Blocks', 'theme-blocks')
			]
		];

		$this->customBlocks = [
			[
				'name'            => 'banner',
				'title'           => __('Banner'),
				'description'     => __('A custom banner block.'),
				'render_template' => get_template_directory() . '/blocks/banner/banner.php',
				'category'        => 'theme-blocks',
				'icon'            => 'cover-image',
				'keywords'        => ['banner', 'content', 'text'],
				'supports' => [
					'align' => false
				],
				'example'           => [
					'attributes' => [
						'mode' => 'preview',
						'data' => [
							'is_preview'    => true
						]
					]
				]
			],
			[
				'name'            => 'filtered-grid',
				'title'           => __('Filtered Grid'),
				'description'     => __('A custom filtered grid block.'),
				'render_template' => get_template_directory() . '/blocks/filtered-grid/filtered-grid.php',
				'category'        => 'theme-blocks',
				'icon'            => 'grid-view',
				'keywords'        => ['grid', 'filter', 'content'],
				'supports' => [
					'align' => false
				],
				'example'           => [
					'attributes' => [
						'mode' => 'preview',
						'data' => [
							'is_preview'    => true
						]
					]
				]
			],
		];

		$this->init();
	}

	/**
	 * Initialize our block logic (check that ACF has the functionality needed and register hooks)
	 *
	 * @return void
	 */
	public function init()
	{
		if (!function_exists('acf_register_block_type')) return;
		// NEW
		add_action('init', [$this, 'register_acf_blocks']);
		add_action('acf/field_group/admin_head', [$this, 'generate_block_files']); 
		add_filter('allowed_block_types_all', [$this, 'allowed_block_types'], 10, 2);
		add_filter('block_categories_all',  [$this, 'register_custom_block_categories'], 10, 2);
	}

	public function register_acf_blocks() {
		foreach ($this->customBlocks as $block) {
			register_block_type( get_template_directory() . '/blocks/' . $block['name'] );
		}
	}
	
	/**
	 * Restrict the default available block types
	 * ref. https://developer.wordpress.org/reference/hooks/allowed_block_types_all/
	 *
	 * @param Array $allowedBlocks
	 * @return void
	 */
	public function allowed_block_types($allowedBlocks, $editor_context)
	{
		$allowedBlocks = [
			// 'core/paragraph',
			// 'core/image',
			// 'core/heading',
			// 'core/gallery',
			// 'core/list',
		];

		foreach ($this->customBlocks  as $block) {
			$allowedBlocks[] = 'acf/' . $block['name'];
		}

		return $allowedBlocks;
	}

	/**
	 * Generate all the necessary files for our custom blocks
	 * @return void
	 */
	public function generate_block_files()
	{
		foreach ($this->customBlocks as $block) {
			$generator = new BlockFileGenerator($block);
			$generator->generate();
		}
	}

	/**
	 * Add custom categories to the blocks list
	 * ref. https://developer.wordpress.org/reference/hooks/block_categories_all/
	 * 
	 * @param array $block_categories
	 * @param WP_Block_Editor_Context $block_editor_context
	 * @return array
	 */
	public function register_custom_block_categories($block_categories, $block_editor_context)
	{
		return array_merge($block_categories, $this->customBlockCategories);
	}
}
