<?php
if (!defined('ABSPATH')) { exit; }
?>
<section class="acf-homepage-section acf-homepage-section--services">
    <h2><?php echo esc_html(get_sub_field('heading')); ?></h2>
    <?php if (have_rows('items')) : ?>
        <div class="acf-homepage-services-items">
            <?php while (have_rows('items')) : the_row(); ?>
                <article class="acf-homepage-service-item">
                    <h3><?php echo esc_html(get_sub_field('title')); ?></h3>
                    <p><?php echo esc_html(get_sub_field('description')); ?></p>
                </article>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</section>
