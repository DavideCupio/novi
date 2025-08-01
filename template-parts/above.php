<?php
function novi_get_page_header_background($id = null)
{
    $image = get_the_post_thumbnail_url($id, 'full');
    return $image ? $image : get_template_directory_uri() . '/assets/img/fallback.jpg';
}
?>
<?php if (is_front_page()) : ?>
    <?php

    // Se esiste l'immagine dell'header impostata nel Customizer, usala
    if (get_header_image()) {
        $background_image = get_header_image();
    } else {
        // Altrimenti usa la funzione custom del tema
        $background_image = novi_get_page_header_background();
    }
    ?>

    <section class="page-header alignwide animation fade" aria-labelledby="page-header-title"
        style="background-image: url('<?php echo esc_url($background_image); ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">

        <div class="page-inner container has-global-padding">
            <div class="page-description">
                <?php if (function_exists('rank_math_the_breadcrumbs')) rank_math_the_breadcrumbs(); ?>
            </div>

            <div class="entry-page-header">
                <h1 id="page-header-title" class="page-title"><?php the_title(); ?></h1>

                <?php
                $novi_cta_title = esc_html(get_theme_mod('novi__home__cta__title', 'Call To Action'));
                $novi_cta_link = esc_url(get_theme_mod('novi__home__cta__link', '#'));

                if ($novi_cta_title && $novi_cta_link) : ?>
                    <div class="novi-cta">
                        <a href="<?php echo $novi_cta_link; ?>" class="site-button cta-button">
                            <?php echo $novi_cta_title; ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php elseif (is_404()) : ?>
    <?php
    $background_image = novi_get_page_header_background();
    ?>
    <section class="page-header page-header-full alignwide animation fade" aria-labelledby="page-header-title" style="background-image: url('<?php echo esc_url($background_image); ?>'); background-size: cover;
        background-position: center; background-repeat: no-repeat;">
        <div class="page-inner container has-global-padding">
            <div class="page-description">
                <?php if (function_exists('rank_math_the_breadcrumbs')) rank_math_the_breadcrumbs(); ?>
            </div>
            <div class="entry-page-header">
                <h1 id="page-header-title" class="page-title">
                    <?php echo esc_html__('Oops! That page can’t be found.', 'novi'); ?></h1>
                <p id="page-header-paragraph" class="page-paragraph">
                    <?php echo esc_html__('Sorry, but the page you were looking for could not be found.', 'novi'); ?></p>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="site-button"
                    aria-label="<?php esc_attr_e('Back to homepage', 'novi'); ?>">
                    <?php esc_html_e('Back Home', 'novi'); ?>
                </a>
            </div>
        </div>
    </section>
<?php elseif (is_single() || is_singular() || is_page()) : ?>
    <?php
    $background_image = novi_get_page_header_background();
    ?>
    <section class="page-header alignwide animation fade" aria-labelledby="page-header-title" style="background-image: url('<?php echo esc_url($background_image); ?>'); background-size: cover;
    background-position: center; background-repeat: no-repeat;">
        <div class="page-inner container has-global-padding">
            <div class="page-description">
                <?php if (function_exists('rank_math_the_breadcrumbs')) rank_math_the_breadcrumbs(); ?>
            </div>
            <div class="entry-page-header">
                <h1 id="page-header-title" class="page-title"><?php the_title(); ?></h1>
                <div class="entry-meta">
                    <span class="posted-on"><?php echo get_the_date(); ?></span>
                    <span class="posted-in"><?php the_category(', '); ?></span>
                </div>
            </div>
            <div class="site tags">
                <?php
                $tags_list = get_the_tag_list('<ul class="post-tags"><li>', '</li><li>', '</li></ul>');
                if ($tags_list) {
                    echo $tags_list;
                }
                ?>
            </div>
        </div>
    </section>
<?php elseif (is_home() || is_archive()) : ?>
    <?php
    $blog_page_id = get_option('page_for_posts');
    $background_image = novi_get_page_header_background($blog_page_id);
    ?>
    <section class="page-header alignwide animation fade" aria-label="<?php echo esc_attr(get_the_title($blog_page_id)); ?>"
        style="background-image: url('<?php echo esc_url($background_image); ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="page-inner container has-global-padding">
            <div class="page-description">
                <?php if (function_exists('rank_math_the_breadcrumbs')) rank_math_the_breadcrumbs(); ?>
            </div>
            <h1 id="page-header-title" class="page-title"><?php echo get_the_title($blog_page_id); ?></h1>
        </div>
    </section>
<?php elseif (is_search()) : ?>
    <?php
    $background_image = novi_get_page_header_background();
    ?>
    <section class="page-header alignwide animation fade" aria-labelledby="page-header-title" style="background-image: url('<?php echo esc_url($background_image); ?>'); background-size: cover;
    background-position: center; background-repeat: no-repeat;">
        <div class="page-inner container has-global-padding is-layout-constrained">
            <h1 id="page-header-title" class="page-title">
                <?php esc_html_e('Results for: ', 'novi'); ?><?php echo get_search_query(); ?>
            </h1>
        </div>
    </section>
<?php endif; ?>