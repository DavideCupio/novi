<?php

/**
 * Template: Blog Page
 * Description: Blog posts archive page.
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
    </div><!-- above -->



    <section class="page-content is-layout-constrained has-global-padding">

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
                            aria-label="Find articles in category <?php echo esc_attr($category->name); ?>">
                            <?php echo esc_html($category->name); ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </nav><!-- category nav -->
        <?php endif; ?>
        <div class="alignwide">
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
            </div> <!-- chiude grid-bento-post -->

            <nav class="pagination alignwide" role="navigation"
                aria-label="<?php esc_attr_e('Browse blog posts', 'novi'); ?>">
                <ul class="pagination-list">
                    <li class="pagination-item">
                        <?php
                        $prev_link = get_previous_posts_link(__('Previous', 'novi'));
                        echo $prev_link
                            ? str_replace('<a', '<a class="site-button style-outline" aria-disabled="false"', $prev_link)
                            : '<a class="site-button style-outline" href="#" aria-disabled="true" tabindex="-1">' . __('Previous', 'novi') . '</a>';
                        ?>
                    </li>
                    <?php
                    $pagination_links = paginate_links(array(
                        'total' => $wp_query->max_num_pages,
                        'current' => max(1, get_query_var('paged')),
                        'type' => 'array',
                        'prev_next' => false,
                    ));

                    if (!empty($pagination_links)) :
                        foreach ($pagination_links as $link) :
                            echo '<li class="pagination-item">' . str_replace('<a', '<a class="site-button style-outline"', $link) . '</li>';
                        endforeach;
                    endif;
                    ?>
                    <li class="pagination-item">
                        <?php
                        $next_link = get_next_posts_link(__('Next', 'novi'));
                        echo $next_link
                            ? str_replace('<a', '<a class="site-button style-outline" aria-disabled="false"', $next_link)
                            : '<a class="site-button style-outline" href="#" aria-disabled="true" tabindex="-1">' . __('Next', 'novi') . '</a>';
                        ?>
                    </li>
                </ul>
            </nav>

        </div>
    </section><!-- blog posts -->
</main>

<?php get_footer(); ?>