<footer class="site-footer site-header--default">
    <div class="site-footer__container container container--large">
        <div class="site-footer__col">
            <div class="site-footer__col-inner">
                <p class="heading heading--lg"><a href="<?= home_url(); ?>"><strong>Ang</strong>C.</a></p>
                <p>&copy; <?= date('Y'); ?>. All Rights Reserved. <a href="#">Privacy Policy</a></p>
            </div>
        </div>
        <div class="site-footer__col">
            <div class="site-footer__col-inner">

            </div>
        </div>
        <div class="site-footer__col">
            <div class="site-footer__col-inner">
                <h2 class="heading heading--sm">Links</h2>
                <?php wp_nav_menu([
                    'menu_class' => 'nav-items',
                    'container' => false,
                    'theme_location' => 'footer'
                ]); ?>
            </div>
        </div>
    </div>
</footer>