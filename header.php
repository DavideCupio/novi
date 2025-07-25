<?php

/**
 * File: header.php
 * Description: Contiene l'output dell'intestazione del sito, incluso il doctype, head e apertura body. Caricato in tutte le pagine.
 *
 * @package novi
 * @since 1.0.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <a class="skip-link screen-reader-text" href="#main-content">
        <?php esc_html_e('Salta al contenuto', 'novi'); ?>
    </a>
    <?php wp_body_open(); ?>


    <header class="header site-header" role="banner">
        <div class="container has-global-padding is-layout-constrained">
            <div class="wrap-navigation alignwide">
                <div class="main-header">
                    <div class="site-branding">
                        <?php
                        if (has_custom_logo()) :
                            $custom_logo_id = get_theme_mod('custom_logo');
                            $logo = wp_get_attachment_image_src($custom_logo_id, 'large');
                        ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>" title="<?php bloginfo('name'); ?>"
                                class="brand">
                                <img src="<?php echo esc_url($logo[0]); ?>"
                                    alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
                            </a>
                        <?php else : ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="site-title">
                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logo-novi.png'); ?>"
                                    alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
                            </a>
                        <?php endif; ?>
                    </div>

                    <button id="burger-toggle" class="burger-menu" aria-expanded="false" aria-controls="header-menu"
                        aria-label="<?php esc_attr_e('Apri o chiudi il menu principale', 'novi'); ?>">
                        <span class="burger-bar"></span>
                        <span class="burger-bar"></span>
                    </button><!-- burger-menu -->

                </div><!-- main-header -->

                <nav id="header-menu" class="header-navigation" role="navigation"
                    aria-label='<?php _e('Main menu ', 'novi'); ?>' aria-expanded="false" aria-hidden="true">
                    <h2 class="screen-reader-text"><?php _e('Main menu ', 'novi'); ?></h2>
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'header-menu',
                        'container'      => false,
                        'items_wrap'     => '<ul class="header-navigation__list">%3$s</ul>',
                    ]);
                    ?>
            </div><!-- wrap-navigation -->
        </div><!-- container -->
    </header><!-- site-header -->