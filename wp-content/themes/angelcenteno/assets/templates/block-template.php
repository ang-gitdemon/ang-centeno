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

<div id="<?= $id ?>" class="<?= $classes ?> fpo">

	<p class="fpo-title"><?= $blockName ?></p>

	<?php if ($is_preview) : ?>
		<span class="block-badge"><?= $block['title'] ?></span>
	<?php endif; ?>

	<?php /*
		// Remove These PHP wraps and FPO section and class
	<div class="<?= $block_class; ?>__container container--<?= $container_size ?? 'default'; ?>">

	</div>
	*/ ?>

</div>