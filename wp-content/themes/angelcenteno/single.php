<?php
	get_header();

	if (have_posts()) :
		while (have_posts()) : the_post(); ?>
			<article class="single-post__body">
                <?= the_content(); ?>
            </article>
            <?= get_sidebar(); ?>
		<?php endwhile;
	endif;


	get_footer();
