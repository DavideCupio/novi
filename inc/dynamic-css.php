<?php

namespace Novi\Theme;

/**
 * File: dynamic-css.php
 * Description: Generates dynamic CSS for theme colors based on customizer settings.
 *
 * @package novi
 * @since 1.0.0
 */

if (!function_exists('novi_generate_theme_colors_css')) {
    function novi_generate_theme_colors_css()
    {
        $slugs = ['basecolor', 'contrastcolor', 'primary', 'secondary', 'button', 'hover', 'warning', 'focus'];

        echo '<style id="novi-dynamic-palette">:root {';
        foreach ($slugs as $slug) {
            $color = get_theme_mod("novi_theme_$slug");
            if ($color) {
                echo "--$slug: " . esc_attr($color) . ";";
                echo "--wp--preset--color--$slug: var(--$slug);";
            }
        }
        echo '}</style>';
    }
    add_action('wp_head', __NAMESPACE__ . '\\novi_generate_theme_colors_css');
}
