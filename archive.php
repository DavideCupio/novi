<?php

/**
 * Template: Archive Page
 * Description: Base template for an archive page.
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



    <section class="blog-posts page-content is-layout-constrained has-global-padding">
        <?php
        $categories = get_categories();

        if (! empty($categories)) :
        ?>
        <nav class="categories-nav alignfull is-layout-constrained has-global-padding" aria-label="Blog categories">
            <span class="note alignwide">* <?php _e('Grab and drag the categories', 'novi') ?></span>
            <div class="category-scroll-wrapper alignwide">
                <ul class="category-list" tabindex="0">
                    <?php foreach ($categories as $category) : ?>
                    <li>
                        <a class="site-button style-outline"
                            href="<?php echo esc_url(get_category_link($category->term_id)); ?>"
                            aria-label="View posts in category <?php echo esc_attr($category->name); ?>">
                            <?php echo esc_html($category->name); ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </nav>
        <?php endif; ?>
        <div class="alignwide ">
            <div class="grid-bento-post">
                <!-- the grid starts here -->
                <?php
                $classi = ['bento-el-1', 'bento-el-2', 'bento-el-2', 'bento-el-1'];
                $i = 0;

                while (have_posts()) : the_post();
                    $featured_image = get_the_post_thumbnail_url(null, 'full');
                    $image_url = $featured_image ? $featured_image : get_template_directory_uri() . '/assets/img/fallback-article.jpg';

                    $classe = $classi[$i % count($classi)];
                ?>

                <article class="blog-post <?php echo $classe ?> animation fade" style="background-image: url('<?php echo esc_url($image_url); ?>'); background-size: cover;
                        background-position: center;">

                    <a href="<?php the_permalink(); ?>" class="post-link">

                        <div class="post-content">
                            <h3 class="post-title"><?php the_title(); ?></h3>
                            <p class="post-excerpt"><?php echo novi_get_custom_excerpt(); ?></p>
                        </div>

                    </a>

                </article>

                <?php
                    $i++;
                endwhile;
                ?>
            </div> <!-- closes grid-bento-post -->
        </div>
    </section>
</main>


<?php get_footer(); ?>