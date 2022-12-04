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
extract(get_fields(), EXTR_PREFIX_SAME, $blockName);

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
?>

<div id="<?= $id ?>" class="<?= $classes ?>">

	<?php if ($is_preview) : ?>
		<span class="block-badge"><?= $block['title'] ?></span>
	<?php endif; ?>

	<div class="<?= $block_class; ?>__container container container--<?= $container_size ?? 'default'; ?>">
		<div class="bloc--banner__content">
			<?php if($banner_tags) : ?>
			<div class="bloc--banner__content-tags">
				<ul>
					<?php foreach($banner_tags as $bt) : ?>
						<li><?= $bt['tag_text']; ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>
			<?php if($banner_title): ?>
				<h1 class="heading heading--xl"><?= $banner_title; ?></h1>
			<?php endif; ?>
			<?php if($banner_content): ?>
				<?= $banner_content; ?>
			<?php endif; ?>
		</div>
		<?php if($banner_image) : ?>
		<div class="bloc--banner__image">
			<?= wp_get_attachment_image($banner_image, 'large', false, [
				'class' => 'pimg',
				'data-direction' => '+'
			]); ?>
			<div class="circle circle--bg pimg" data-direction="-"></div>
			<div class="circle circle--right pimg" data-direction="+"></div>
		</div>
		<?php endif; ?>
	</div>

</div>