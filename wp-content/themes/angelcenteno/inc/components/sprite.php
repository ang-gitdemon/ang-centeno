<?php
    // Sprite SVG
    $sprite_path = '/assets/css/build/sprite.svg';
    if( !file_exists(get_template_directory() . $sprite_path) ) return;
?>

<div class="hidden" hidden>
    <?= file_get_contents( get_template_directory() . $sprite_path ); ?>
</div>