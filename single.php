<?php

/**
 * Template: single page
 * Description: Template for displaying single posts.
 *
 * @package novi
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly. 
}

get_header();
?>


<main id="main-content" role="main" <?php post_class('site-main single-post'); ?>>

    <div class="is-layout-constrained has-global-padding">
        <?php get_template_part('template-parts/above'); ?>
    </div>
    <div class="page-content container">
        <div class="is-layout-constrained has-global-padding">
            <nav class="post-navigation alignwide" aria-label="<?php esc_attr_e('Post Navigation', 'novi'); ?>">
                <?php
                $prev_post = get_previous_post();
                $next_post = get_next_post();
                ?>
                <div class="nav-links">
                    <a class="nav-previous site-button<?php echo $prev_post ? '' : ' disabled'; ?>"
                        href="<?php echo $prev_post ? get_permalink($prev_post) : '#'; ?>"
                        aria-disabled="<?php echo $prev_post ? 'false' : 'true'; ?>"
                        tabindex="<?php echo $prev_post ? '0' : '-1'; ?>">
                        <?php _e('Precedente', 'novi'); ?>
                    </a>

                    <div class="content">
                        <p><?php _e('Naviga tra gli articoli', 'novi'); ?></p>
                    </div>

                    <a class="nav-next site-button<?php echo $next_post ? '' : ' disabled'; ?>"
                        href="<?php echo $next_post ? get_permalink($next_post) : '#'; ?>"
                        aria-disabled="<?php echo $next_post ? 'false' : 'true'; ?>"
                        tabindex="<?php echo $next_post ? '0' : '-1'; ?>">
                        <?php _e('Prossimo', 'novi'); ?>
                    </a>
                </div>
            </nav>
        </div>
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <article <?php post_class('page-content container entry-content'); ?> id="post-<?php the_ID(); ?>"
                    aria-labelledby="post-title-<?php the_ID(); ?>">

                    <?php
                    echo '<div class="has-global-padding is-layout-constrained">';
                    the_content();
                    echo '</div>';
                    ?>
                    <?php
                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . __('Pagine:', 'novi'),
                        'after'  => '</div>',
                    ));
                    ?>

                </article>
                <?php
                // If comments are open or there's at least one comment, load the comment template.
                if (comments_open() || get_comments_number()) :
                    echo '<div class="comments-area alignfull is-layout-constrained has-global-padding">';
                    comments_template();
                    echo '</div>';
                endif;
                ?>

        <?php endwhile;
        endif; ?>
    </div>
</main>





<?php get_footer(); ?>