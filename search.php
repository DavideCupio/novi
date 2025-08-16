<?php

/**
 * Template: commenti del blog
 * Description: Template base per i commenti del blog.
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
    </div><!-- above -->

    <section class="page-content container has-global-padding is-layout-constrained">


        <?php if (have_posts()) : ?>
            <div class="search-results alignwide site-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <?php
                    $featured_image = get_the_post_thumbnail_url(null, 'full');
                    $image_url = $featured_image ? $featured_image : get_template_directory_uri() . '/assets/img/fallback-article.jpg';
                    $style = sprintf(
                        "background-image: url('%s'); background-size: cover; background-position: center;",
                        esc_url($image_url)
                    );
                    ?>
                    <article class="blog-post animation fade" style="<?php echo esc_attr($style); ?>">
                        <a href="<?php echo esc_url(get_permalink()); ?>" class="post-link">
                            <div class="post-content">
                                <h3 class="post-title"><?php echo esc_html(get_the_title()); ?></h3>
                            </div>
                        </a>
                    </article>

                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <p><?php _e('No results found for your search.', 'novi'); ?></p>
        <?php endif; ?>


    </section><!-- container -->
</main>
<?php get_footer(); ?>