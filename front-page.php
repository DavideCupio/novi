<?php

/**
 * Template: Front page
 * Description: Template base per una singola pagina statica.
 *
 * @package novi
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly. 
}

get_header();
?>

<main id="main-content" role="main" <?php post_class('site-main'); ?>>
    <div class="is-layout-constrained has-global-padding">
        <?php get_template_part('template-parts/above'); ?>
    </div>
    <div class="page-content container">

        <?php
        while (have_posts()) :
            the_post();
            echo '<div class="is-layout-constrained has-global-padding entry-content">';
            the_content();
            echo '</div>';
        endwhile;
        ?>

    </div>
</main>

<?php get_footer(); ?>