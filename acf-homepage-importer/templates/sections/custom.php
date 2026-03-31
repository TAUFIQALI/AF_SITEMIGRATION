<?php
if (!defined('ABSPATH')) { exit; }
?>
<section class="acf-homepage-section acf-homepage-section--custom">
    <h2><?php echo esc_html(get_sub_field('heading')); ?></h2>
    <div><?php echo wp_kses_post(get_sub_field('raw_html')); ?></div>
</section>
