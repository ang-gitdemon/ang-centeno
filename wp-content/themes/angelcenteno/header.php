
<?php
	global $post;
    $header_type = get_field('header_type') ? get_field('header_type') : 'default';
	$head_scripts = get_field('head_scripts', 'option') ?? '';
	$body_scripts = get_field('body_scripts', 'option') ?? '';
	$under_construction = get_field('under_construction', 'option') ?? '';
	$additional_classes = '';
	if($under_construction) $additional_classes .= ' hidden';
	if($header_type) $additional_classes .= ' ' . $header_type;
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<?php wp_head(); ?>
    <?= $head_scripts; ?>
</head>

<body <?= body_class($additional_classes); ?> data-barba="wrapper">

	<?= get_template_part('/inc/components/sprite'); ?>

    <?php do_action('after_body_open_tag'); ?>
	
    <?= $body_scripts; ?>

    <a href="#site" class="skip_to_main_link"><?= get_field('skip_content_label', 'option') ?></a>

	<?= get_template_part('/inc/components/gdpr'); ?>

    <?= get_template_part('/inc/header/header', $header_type); ?>

    <main class="site-main" data-barba="container" data-barba-namespace="<?= $post->post_name; ?>">
