<?php

namespace Novi\Theme;

if (!defined('ABSPATH')) {
    exit;
}

/** Custom logo support */
function novi_custom_logo_setup()
{
    add_theme_support('custom-logo');
}
add_action('after_setup_theme', __NAMESPACE__ . '\\novi_custom_logo_setup');

/** Register customizer settings */
function novi_theme_customize_register($wp_customize)
{
    // Rimuove sezioni di default
    $wp_customize->remove_section("background_image");
    $wp_customize->remove_section("colors");

    // Call to Action
    $wp_customize->add_section('novi__home__cta', [
        'title'    => esc_html__('Call To Action', 'novi'),
        'priority' => 80,
    ]);

    $wp_customize->add_setting('novi__home__cta__title', [
        'default'           => esc_html__('Call To Action', 'novi'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('novi__home__cta__title', [
        'label'   => esc_html__('Button text', 'novi'),
        'section' => 'novi__home__cta',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('novi__home__cta__link', [
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ]);

    $wp_customize->add_control('novi__home__cta__link', [
        'label'   => esc_html__('Link text', 'novi'),
        'section' => 'novi__home__cta',
        'type'    => 'text',
    ]);

    // Colori personalizzati
    $colors = [
        'basecolor'    => ['label' => 'Base color (content text)', 'default' => '#0a0e1a'],
        'contrastcolor' => ['label' => 'Contrast color (background)', 'default' => '#e6f1ff'],
        'primary'      => ['label' => 'Primary', 'default' => '#0d47a1'],
        'secondary'    => ['label' => 'Secondary', 'default' => '#1565c0'],
        'button'       => ['label' => 'Button', 'default' => '#00e5ff'],
        'hover'        => ['label' => 'Hover', 'default' => '#1de9b6'],
        'warning'      => ['label' => 'Warning', 'default' => '#ff5252'],
        'focus'        => ['label' => 'Focus', 'default' => '#64ffda'],
    ];

    $wp_customize->add_section('novi_color_section', [
        'title'    => __('Novi Color Palette', 'novi'),
        'priority' => 90,
    ]);

    foreach ($colors as $slug => $data) {
        $wp_customize->add_setting("novi_theme_$slug", [
            'default'           => $data['default'],
            'sanitize_callback' => 'sanitize_hex_color',
        ]);

        $wp_customize->add_control(new \WP_Customize_Color_Control(
            $wp_customize,
            "novi_theme_$slug",
            [
                'label'    => $data['label'],
                'section'  => 'novi_color_section',
                'settings' => "novi_theme_$slug",
            ]
        ));
    }

    // Tipografia personalizzata
    $wp_customize->add_section('novi_typography_section', [
        'title'    => __('Typography', 'novi'),
        'priority' => 95,
    ]);

    // Font Body
    $wp_customize->add_setting('novi_font_body', [
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control(new \WP_Customize_Upload_Control($wp_customize, 'novi_font_body', [
        'label'    => __('Upload Body Font (.woff2 or .ttf)', 'novi'),
        'section'  => 'novi_typography_section',
    ]));

    // Font Heading
    $wp_customize->add_setting('novi_font_heading', [
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control(new \WP_Customize_Upload_Control($wp_customize, 'novi_font_heading', [
        'label'    => __('Upload Heading Font (.woff2 or .ttf)', 'novi'),
        'section'  => 'novi_typography_section',
    ]));

    // Nota MIME per font personalizzati
    $wp_customize->add_setting('novi_typography_mime_note', [
        'sanitize_callback' => '__return_null',
    ]);
    $doc_url = esc_url('https://cupiolistudio.it/novi/documentazione');

    $wp_customize->add_control('novi_typography_mime_note', [
        'type'        => 'hidden',
        'label'       => __('How to activate load font?', 'novi'),
        'section'     => 'novi_typography_section',
        'description' => wp_kses_post(
            __('The font feature is deactivated by default.<br><br>', 'novi') .
                __('More precisely, it\'s not possible to upload fonts directly.<br><br>', 'novi') .
                '<a href="' . $doc_url . '" target="_blank" rel="noopener noreferrer">' .
                __('See the official documentation', 'novi') .
                '</a>'
        ),
    ]);
}
add_action('customize_register', __NAMESPACE__ . '\\novi_theme_customize_register');

/** Inietta i font caricati dinamicamente nel <head> */
function novi_inline_font_styles()
{
    $body_font    = get_theme_mod('novi_font_body');
    $heading_font = get_theme_mod('novi_font_heading');

    if (!$body_font && !$heading_font) {
        return;
    }

    echo '<style>';

    if ($body_font) {
        $ext    = pathinfo($body_font, PATHINFO_EXTENSION);
        $format = ($ext === 'ttf') ? 'truetype' : 'woff2';

        echo "@font-face {
            font-family: 'CustomBodyFont';
            src: url('" . esc_url($body_font) . "') format('" . esc_attr($format) . "');
            font-weight: 400;
            font-style: normal;
            font-display: swap;
        }";
        echo ":root { --wp--preset--font-family--body: 'CustomBodyFont', sans-serif; }";
    }

    if ($heading_font) {
        $ext    = pathinfo($heading_font, PATHINFO_EXTENSION);
        $format = ($ext === 'ttf') ? 'truetype' : 'woff2';

        echo "@font-face {
            font-family: 'CustomHeadingFont';
            src: url('" . esc_url($heading_font) . "') format('" . esc_attr($format) . "');
            font-weight: 700;
            font-style: normal;
            font-display: swap;
        }";
        echo ":root { --wp--preset--font-family--heading: 'CustomHeadingFont', sans-serif; }";
    }

    echo '</style>';
}
add_action('wp_head', __NAMESPACE__ . '\\novi_inline_font_styles');