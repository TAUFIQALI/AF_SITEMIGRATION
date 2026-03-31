<?php
if (!defined('ABSPATH')) { exit; }
?>
<section class="acf-homepage-section acf-homepage-section--about">
    <h2><?php echo esc_html(get_sub_field('heading')); ?></h2>
    <div><?php echo esc_html(get_sub_field('description')); ?></div>
</section>
