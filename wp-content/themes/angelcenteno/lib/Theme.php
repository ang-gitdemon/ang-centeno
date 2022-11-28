<?php

require_once __DIR__ . '/Blocks.php';
require_once __DIR__ . '/Utils.php';
require_once __DIR__ . '/helpers/RESTLogic.php';

class Theme
{
	protected $blocks;
	protected $shortcodes;

	public function __construct()
	{
		$this->blocks = new Blocks();
		$this->shortcodes = [
			'year' => date('Y')
		];
		$this->register_actions();
		$this->register_shortcodes();
		$this->register_filters();
		$this->theme_support();
		$this->add_option_pages();
	}

	public function register_actions()
	{
		add_action('admin_notices', [$this, 'register_admin_notices']);
		add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
		add_action('enqueue_block_editor_assets', [$this, 'enqueue_assets']);
		add_action('wp_head', [$this, 'modify_head']);
		add_action('init', [$this, 'init_hooks']);
		add_action('admin_init', [$this, 'admin_init_hooks']);
		add_action('admin_menu', [$this, 'admin_menu_hooks']);
		add_action('login_enqueue_scripts', [$this, 'login_page_hooks']);
		add_action('rest_api_init', [$this, 'rest_init_hooks']);
		add_action('acf/init', [$this, 'my_acf_init']);
	}

	public function register_filters()
	{
		add_filter('acf/format_value/type=image', ['Utils', 'format_acf_images'], 100, 3);
		add_filter('acf/format_value/type=gallery', ['Utils', 'umj_format_acf_gallery_images'], 100, 3);
		add_filter('cron_schedules', ['Utils', 'add_30_day_cron_schedule'], 10, 1);
		add_filter('acf/fields/wysiwyg/toolbars', ['Utils', 'modify_acf_wysiwyg_toolbars'], 10, 1);
		add_filter('tiny_mce_before_init', ['Utils', 'modify_tiny_mce_format_options'], 10, 1);
		add_filter('post_thumbnail_html', [$this, 'remove_width_attribute'], 10);
		add_filter('image_send_to_editor', [$this, 'remove_width_attribute'], 10);
		add_filter('wp_prepare_attachment_for_js', [$this, 'common_svg_media_thumbnails'], 10, 3);
		add_filter('gform_submit_button', [$this, 'form_submit_button'], 10, 2 );
		add_filter('comments_open', function () {
			return false;
		});
		add_filter('pings_open', function () {
			return false;
		});
		add_filter('comments_array', function ($comments) {
			return [];
		});
		add_filter('excerpt_length', function ($length) {
			return 20;
		}, 999);
		add_filter('excerpt_more', function ($more) {
			return '...';
		});
		add_filter('login_headerurl', function () {
			return home_url();
		});
		add_filter('upload_mimes', function ($mimeTypes) {
			$mimeTypes['svg'] = 'image/svg+xml';
			return $mimeTypes;
		});
		add_filter('the_content', function ($content) {
			return preg_replace('/<p>\s*(<iframe.*>*.<\/iframe>)\s*<\/p>/iU', '<div class="iframe">$1</div>', $content);
		});
		add_filter('wp_mail_content_type', function () {
			return 'text/html';
		});
		// Allowed menu classes
		add_filter('nav_menu_css_class', function ($classes, $item) {
			return is_array($classes) ?
				array_intersect(
					$classes,
					array(
						'current-menu-item',
						'current-menu-parent',
						'menu-item-has-children'
					)
				) : $classes;
		}, 10, 2);

		add_filter('nav_menu_item_id', function () {
			return '';
		}, 100, 1);

		// accessible menus
		add_filter('wp_nav_menu', function ($menu_html, $args) {
			$bad = array('menu', 'navigation', 'nav');
			$menu_label = $args->menu;
			$menu_label = strtolower($menu_label);
			$menu_label = str_replace($bad, '', $menu_label);
			$menu_label = trim($menu_label);
			$menu_html = '<nav aria-label="' . $menu_label . '">' . $menu_html . '</nav>';
			return $menu_html;
		}, 10, 2);

		// limit number of post revisions
		add_filter( 'wp_revisions_to_keep', function($num, $post){
			$revisions = 5;
			return $revisions;
		}, 10, 2 );
	}

	public function common_svg_media_thumbnails($response, $attachment, $meta)
	{
		if ($response['type'] === 'image' && $response['subtype'] === 'svg+xml' && class_exists('SimpleXMLElement')) {
			try {
				$path = get_attached_file($attachment->ID);
				if (@file_exists($path)) {
					$svg = new SimpleXMLElement(@file_get_contents($path));
					$src = $response['url'];
					$width = (int) $svg['width'];
					$height = (int) $svg['height'];

					//media gallery
					$response['image'] = compact('src', 'width', 'height');
					$response['thumb'] = compact('src', 'width', 'height');

					//media single
					$response['sizes']['full'] = array(
						'height'        => $height,
						'width'         => $width,
						'url'           => $src,
						'orientation'   => $height > $width ? 'portrait' : 'landscape',
					);
				}
			} catch (Exception $e) {
			}
		}

		return $response;
	}

	public function remove_width_attribute($html)
	{
		$html = preg_replace('/(width|height)="\d*"\s/', "", $html);
		return $html;
	}

	public function register_shortcodes()
	{
		foreach ($this->shortcodes as $slug => $returnValue) {
			add_shortcode($slug, function ($atts) use ($returnValue) {
				return $returnValue;
			});
		}
	}

	public function enqueue_assets()
	{
		$css_file_path = glob( get_template_directory() . '/assets/css/build/main.min.*.css' );
		$css_uri = get_template_directory_uri() . '/assets/css/build/' . basename($css_file_path[0]);
    	wp_enqueue_style( 'main', $css_uri );
		
		$modules_file_path = glob( get_template_directory() . '/assets/js/build/main.min.*.js' );
		$modules_uri = get_template_directory_uri() . '/assets/js/build/' . basename($modules_file_path[0]);
		wp_enqueue_script( 'modules', $modules_uri , [], null , true );

		$blocks_file_path = glob( get_template_directory() . '/assets/js/build/blocks.min.*.js' );
		$blocks_uri = get_template_directory_uri() . '/assets/js/build/' . basename($blocks_file_path[0]);
		wp_enqueue_script( 'blocks', $blocks_uri , ['modules'] , null , true );

		// is_admin() ? wp_localize_script('umjblock', 'UMJCustomBlocks', $this->umjblock->customBlocks) : wp_localize_script('main', 'UMJConstants', [ 'ajaxUrl' => admin_url('admin-ajax.php'), 'themeDir' => get_template_directory_uri() ]);
	}

	public function register_admin_notices()
	{
		Utils::acf_sync_notice();
	}

	public function theme_support()
	{
		add_theme_support('editor-styles');
		add_theme_support('title-tag');
		add_theme_support('menus');
		add_theme_support('post-thumbnails', ['post', 'destination', 'resort', 'testimonial', 'press']);
	}

	public function my_acf_init()
	{
		$google_maps_api = get_field('google_maps_api', 'option');
		acf_update_setting('google_api_key', $google_maps_api);
	}

	public function add_option_pages()
	{
		if (function_exists('acf_add_options_page')) {
			acf_add_options_page([
				'page_title' 	=> 'Theme General Settings',
				'menu_title'	=> 'Theme Settings',
				'menu_slug' 	=> 'theme-general-settings',
				'capability'	=> 'edit_posts',
				'redirect'		=> false
			]);

			acf_add_options_page([
				'page_title' 	=> 'Modals',
				'menu_title'	=> 'Modals',
				'menu_slug' 	=> 'theme-modals',
				'capability'	=> 'edit_posts',
				'icon_url'		=> 'dashicons-editor-expand',
				'redirect'		=> false
			]);
		}
	}

	public function form_submit_button( $button, $form ) {
		$submit_button = "<button class='button button--arrow gform_button' id='gform_submit_button_{$form['id']}'>" . $button . " <div class='icon'> ";
		$submit_button .= file_get_contents( get_template_directory() . '/assets/svg/arrow.svg');
		$submit_button .= "</div></button>";
		return $submit_button;
	}

	public function modify_head()
	{
		Utils::add_google_analytics();
	}

	public function init_hooks()
	{
		Utils::register_custom_post_types();
		Utils::clean_head();
		Utils::register_menus();
	}

	public function admin_init_hooks()
	{
		Utils::disable_comments_logic();
		Utils::add_tinymce_editor_styles();
	}

	public function admin_menu_hooks()
	{
		Utils::cleanup_admin_menu();
	}

	public function login_page_hooks()
	{
		// Utils::set_login_page_styles();
	}

	public function rest_init_hooks()
	{
		$R = new RESTLogic();
		$R->register_fields();
		$R->register_routes();
	}
}
