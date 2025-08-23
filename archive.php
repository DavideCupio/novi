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

                    $classe_raw = $classi[$i % count($classi)];
                    $classe     = sanitize_html_class($classe_raw);
                    $article_classes = implode(' ', array_map('sanitize_html_class', ['blog-post', $classe, 'animation', 'fade']));
                    $style = sprintf(
                        "background-image: url('%s'); background-size: cover; background-position: center;",
                        esc_url($image_url)
                    );
                ?>

                    <article class="<?php echo esc_attr($article_classes); ?>" style="<?php echo esc_attr($style); ?>">

                        <a href="<?php echo esc_url(get_permalink()); ?>" class="post-link">
                        </a>
                        <div class="post-content">
                            <h3 class="post-title"><?php echo esc_html(get_the_title()); ?></h3>
                            <p class="post-excerpt"><?php echo wp_kses_post(novi_get_custom_excerpt()); ?></p>
                        </div>
                    </article>

                <?php
                    $i++;
                endwhile;
                ?>
            </div> <!-- closes grid-bento-post -->

            <?php
            global $wp_query;

            $current = max(1, (int) get_query_var('paged'));
            $total   = max(1, (int) $wp_query->max_num_pages);

            // Link numerati come array (senza prev/next: li gestiamo a mano)
            $links = paginate_links([
                'total'     => $total,
                'current'   => $current,
                'type'      => 'array',
                'end_size'  => 1,
                'mid_size'  => 2,
                'prev_next' => false,
            ]);

            // Costruiamo gli href di prev/next
            $prev_href = $current > 1       ? get_pagenum_link($current - 1) : '';
            $next_href = $current < $total  ? get_pagenum_link($current + 1) : '';

            if ($total > 1) : ?>
                <nav class="pagination alignwide" role="navigation"
                    aria-label="<?php echo esc_attr__('Browse blog posts', 'novi'); ?>">
                    <ul class="pagination-list">

                        <!-- Prev -->
                        <li class="pagination-item">
                            <?php if ($prev_href) : ?>
                                <a class="site-button style-outline" href="<?php echo esc_url($prev_href); ?>" rel="prev">
                                    <?php echo esc_html__('Previous', 'novi'); ?>
                                </a>
                            <?php else : ?>
                                <span class="site-button style-outline is-disabled" aria-disabled="true">
                                    <?php echo esc_html__('Previous', 'novi'); ?>
                                </span>
                            <?php endif; ?>
                        </li>

                        <!-- Numeri -->
                        <?php if (! empty($links)) :
                            foreach ($links as $l) :
                                if (strpos($l, 'current') !== false) {
                                    // Pagina corrente: di solito è <span class="page-numbers current">N</span>
                                    $num = (int) filter_var(wp_strip_all_tags($l), FILTER_SANITIZE_NUMBER_INT);
                                    echo '<li class="pagination-item"><span class="site-button style-outline current" aria-current="page">' . esc_html($num) . '</span></li>';
                                } elseif (preg_match('/href=[\"\\\']([^\"\\\']+)[\"\\\'][^>]*>(.*?)<\\/a>/', $l, $m)) {
                                    $href = $m[1];
                                    $text = wp_strip_all_tags($m[2]);
                                    echo '<li class="pagination-item"><a class="site-button style-outline" href="' . esc_url($href) . '">' . esc_html($text) . '</a></li>';
                                }
                            endforeach;
                        endif; ?>

                        <!-- Next -->
                        <li class="pagination-item">
                            <?php if ($next_href) : ?>
                                <a class="site-button style-outline" href="<?php echo esc_url($next_href); ?>" rel="next">
                                    <?php echo esc_html__('Next', 'novi'); ?>
                                </a>
                            <?php else : ?>
                                <span class="site-button style-outline is-disabled" aria-disabled="true">
                                    <?php echo esc_html__('Next', 'novi'); ?>
                                </span>
                            <?php endif; ?>
                        </li>

                    </ul>
                </nav>
            <?php endif; ?>

        </div>
    </section>
</main>


<?php get_footer(); ?>