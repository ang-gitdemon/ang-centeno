<?php
    get_header();
    global $post;
    $industries = get_the_terms( $post->ID , 'industry' );
    $services = get_the_terms( $post->ID , 'service' );
    $codes = get_the_terms( $post->ID , 'code' );
    $tools = get_the_terms( $post->ID , 'tools' );
    $description = get_field('description', $post->ID);
    $website = get_field('website', $post->ID);
?>

<div class="work">
    <header class="work__cover">
        
        <div class="work__cover-content">
            <h1 class="heading heading--lg"><?= get_the_title(); ?></h1>
            <?php if($industries) : ?>
                <dl>
                    <dt><?= count($industries) > 1 ? 'Industries:' : 'Industry:'; ?></dt>
                    <dd>
                        <?php foreach($industries as $ind) : ?>
                            <a href="<?= home_url(); ?>/industry/<?= $ind->slug; ?>"><?= $ind->name; ?></a>
                        <?php endforeach; ?>
                    </dd>
                    <?php if($website): ?>
                    <dt>Website:</dt>
                    <dd>
                        <a href="<?= $website['url']; ?>" target="<?= $website['target']; ?>"><?= $website['title']; ?></a>
                    </dd>
                    <?php endif; ?>
                </dl>
            <?php endif; ?>
            <?php if($description): ?>
                <?= $description; ?>
            <?php endif; ?>
        </div>

        <?php if(has_post_thumbnail($post->ID)) : ?>
        <div class="work__cover-image">
            <?= get_the_post_thumbnail($post->ID, 'full'); ?>
        </div>
        <?php endif; ?>
    </header>
    <main class="work__container container">
        <div class="work__meta">
            <?php if($codes): ?>
            <div class="work__meta-box">      
                <div class="work__meta-inner">
                    <h2 class="heading heading--tag heading--xs">Coding Languages</h2>
                    <ul>
                    <?php foreach($codes as $c) : ?>
                        <li><a href="<?= home_url(); ?>/code/<?= $c->slug; ?>"><?= $c->name; ?></a></li>
                    <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
            <?php if($services): ?>
            <div class="work__meta-box">
                <div class="work__meta-inner">
                    <h2 class="heading heading--tag heading--xs">Services</h2>
                    <ul>
                    <?php foreach($services as $serv) : ?>
                        <li><a href="<?= home_url(); ?>/service/<?= $serv->slug; ?>"><?= $serv->name; ?></a></li>
                    <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
            <?php if($tools) : ?>
            <div class="work__meta-box">
                <div class="work__meta-inner">
                    <h2 class="heading heading--tag heading--xs">Tools</h2>
                    <ul>
                    <?php foreach($tools as $tool) : ?>
                        <li><a href="<?= home_url(); ?>/tools/<?= $tool->slug; ?>"><?= $tool->name; ?></a></li>
                    <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>


<?php get_footer();