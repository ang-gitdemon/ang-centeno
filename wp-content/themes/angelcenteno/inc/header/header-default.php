<?php
    $header_logo = get_field('header_logo', 'option');
    $header_cta = get_field('header_cta', 'option');
?>

<header class="site-header">
    <div class="site-header__container container container--large">
        <div class="site-header__logo">
            <a  href="<?= home_url(); ?>">
                <?php //= $header_logo ? wp_get_attachment_image( $header_logo, 'full', false ) : get_bloginfo('name'); ?>
                ang<span>el</span>.
            </a>
        </div>
        <button class="mobile-menu-button" aria-expanded="false" aria-controls="menu"><span></span></button>
        <nav class="site-header__nav">
            <?php wp_nav_menu([
                'menu_class' => 'nav-items',
                'container' => false,
                'theme_location' => 'header'
            ]); ?>
            <div class="site-header__social">
                <ul>
                    <li>
                        <a href="https://github.com/ang-gitdemon" target="_blank" rel="nofollow">
                            <?= Utils::renderIcon('github'); ?> ang-gitdemon
                        </a>
                    </li>
                </ul>
            </div>
            <div class="site-header__cta">
                <?php if($header_cta): ?>
                    <a class="button button--primary" target="<?= $header_cta['target']; ?>" href="<?= $header_cta['url']; ?>"><?= $header_cta['title']; ?></a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</header>