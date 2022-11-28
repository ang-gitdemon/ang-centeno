<?php
    $header_logo = get_field('header_logo', 'option');
    $header_cta = get_field('header_cta', 'option');
?>

<header class="site-header site-header--splash">
    <div class="site-header__container">
        <div class="site-header__logo">
            <?=  $header_logo ? wp_get_attachment_image( $header_logo, 'full', false ) : get_bloginfo('name'); ?>
        </div>
        <div class="site-header__cta">
            <?php if($header_cta): ?>
                <a class="button button--primary" target="<?= $header_cta['target']; ?>" href="<?= $header_cta['url']; ?>"><?= $header_cta['title']; ?></a>
            <?php endif; ?>
        </div>
    </div>
</header>