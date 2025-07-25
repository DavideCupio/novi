<?php

/**
 * Template: Pagina 404
 * Description: Template base per una pagina 404.
 *
 * @package novi
 */


if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly. 
}

get_header(); ?>

<main id="main-content" role="main" <?php post_class('site-main'); ?>>
    <div class="is-layout-constrained has-global-padding">
        <?php get_template_part('template-parts/above'); ?>
    </div>
    <div class="page-content container">

        <h1><?php _e('404 - Page Not Found', 'novi'); ?></h1>
        <p><?php _e('Sorry, but the page you were looking for could not be found.', 'novi'); ?></p>

    </div>
</main>


<?php get_footer(); ?>