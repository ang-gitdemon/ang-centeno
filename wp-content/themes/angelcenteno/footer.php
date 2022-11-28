<?php
	$footer_type = get_field('footer_type') ? get_field('footer_type') : 'default';
	$footer_scripts = get_field('footer_scripts', 'option') ?? '';
?>
	</main>

	<?= get_template_part('/inc/footer/footer', $footer_type); ?>

    <?php wp_footer(); ?>

    <?= $footer_scripts; ?>

</body>


</html>
