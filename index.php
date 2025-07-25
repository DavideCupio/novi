<?php

/**
 * Theme: Novi
 *
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * Also it's used to display search results, category page, tag page and date page.
 *
 * @package novi
 * @version 1.0.0
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
            echo '<div class="is-layout-constrained has-global-padding">';
            the_content();
            echo '</div>';
        endwhile;
        ?>

    </div>
</main>

<?php get_footer(); ?>