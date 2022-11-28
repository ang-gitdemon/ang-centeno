<?php
    $header_logo = get_field('header_logo', 'option');
    $header_cta = get_field('header_cta', 'option');
?>

<header class="site-header">
    <div class="site-header__container">
        <div class="site-header__logo">
            <a href="<?= home_url(); ?>">
                <?= $header_logo ? wp_get_attachment_image( $header_logo, 'full', false ) : get_bloginfo('name'); ?>
            </a>
        </div>
        <button class="mobile-menu-button" aria-expanded="false" aria-controls="menu"><span></span></button>
        <nav class="site-header__nav">
            <?php wp_nav_menu([
                'menu' => 'Main Menu',
                'menu_class' => 'nav-items',
                'container' => false
            ]); ?>
        </nav>
        <div class="site-header__cta">
            <?php if($header_cta): ?>
                <a class="button button--primary" target="<?= $header_cta['target']; ?>" href="<?= $header_cta['url']; ?>"><?= $header_cta['title']; ?></a>
            <?php endif; ?>
        </div>
    </div>
</header>