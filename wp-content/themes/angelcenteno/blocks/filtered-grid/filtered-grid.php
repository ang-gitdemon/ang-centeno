<?php
/**
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during backend preview render.
 * @param int $post_id The post ID the block is rendering content against.\
 * @param array $context The context provided to the block by the post or it's parent block.
 */

$id = $block['name'] . '-' . $block['id'];
$blockName = str_replace('acf/', '', $block['name']);
$classes = 'bloc bloc--' . $blockName;
extract( get_fields(), EXTR_PREFIX_SAME, str_replace('-', '', $blockName) );

if (!empty($block['className'])) $classes .= ' ' . $block['className'];
if (!empty($block_background)) $classes .= ' bloc--bg-' . $block_background;
if (!empty($margin_top)) $classes .= ' bloc--mt-' . $margin_top;
if (!empty($margin_bottom)) $classes .= ' bloc--mb-' . $margin_bottom;
if (!empty($padding_top)) $classes .= ' bloc--pt-' . $padding_top;
if (!empty($padding_bottom)) $classes .= ' bloc--pb-' . $padding_bottom;
if (!empty($hide_mobile)) $classes .= ' bloc--hm';
if (!empty($hide_desktop)) $classes .= ' bloc--hd';
if (!empty($block_id)) $id = str_replace(' ', '-', strtolower($block_id));

$block_class = 'bloc--' . $blockName;

$args = [];
switch ($post_types) {
	case 'work':
		$args = [
			'order' => 'ASC',
			'orderby' => 'post_title',
			'post_type' => $post_types,
			'numberposts' => -1
		];
		break;
	
	default:
		# code...
		break;
}
$grid_content = get_posts($args);
?>

<div id="<?= $id ?>" class="<?= $classes ?>">

	<?php if ($is_preview) : ?>
		<span class="block-badge"><?= $block['title'] ?></span>
	<?php endif; ?>

	<div class="<?= $block_class; ?>__container container container--<?= $container_size ?? 'default'; ?>">
		<div class="<?= $block_class; ?>__side">
			Side
		</div>
		<div class="<?= $block_class; ?>__main">
			<?php if($grid_content) : ?>
				<?php foreach($grid_content as $idx => $item) : ?>
					<div class="<?= $block_class; ?>__each">
						<div class="<?= $block_class; ?>__each-image">
							<?= get_the_post_thumbnail( $item->ID, 'full'); ?>
						</div>
						<div class="<?= $block_class; ?>__each-content pimg" data-speed="50">
							<h3 class="heading heading--sm"><a href="<?= get_the_permalink($item->ID); ?>" rel="noindex nofollow"><?= get_the_title($item->ID); ?></a></h3>
							<?= wp_trim_words( get_field('description', $item->ID), 14, '...'  ); ?>
						</div>
					</div>
				<?php endforeach; ?>
			<?php else: ?>
				<p>No results found.</p>
			<?php endif; ?>
		</div>
	</div>

</div>