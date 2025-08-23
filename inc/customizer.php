<?php

if (!defined('ABSPATH')) {
    exit;
}

/** Custom logo support */
function novi_custom_logo_setup()
{
    add_theme_support('custom-logo');
}
add_action('after_setup_theme', 'novi_custom_logo_setup');



/** Register customizer settings */
function novi_theme_customize_register($wp_customize)
{
    // Rimuove sezioni di default
    $wp_customize->remove_section("background_image");
    $wp_customize->remove_section("colors");

    // Call to Action
    $wp_customize->add_section('novi__home__cta', [
        'title' => esc_html__('Call To Action', 'novi'),
        'priority' => 80,
    ]);

    $wp_customize->add_setting('novi__home__cta__title', [
        'default' => esc_html__('Call To Action', 'novi'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('novi__home__cta__title', [
        'label' => esc_html__('Button text', 'novi'),
        'section' => 'novi__home__cta',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('novi__home__cta__link', [
        'default' => '#',
        'sanitize_callback' => 'esc_url_raw',
    ]);

    $wp_customize->add_control('novi__home__cta__link', [
        'label' => esc_html__('Link text', 'novi'),
        'section' => 'novi__home__cta',
        'type' => 'text',
    ]);

    // Colori personalizzati
    $colors = [
        'basecolor' => ['label' => 'Base color (content text)', 'default' => '#0a0e1a'],
        'contrastcolor' => ['label' => 'Contrast color (background)', 'default' => '#e6f1ff'],
        'primary' => ['label' => 'Primary', 'default' => '#0d47a1'],
        'secondary' => ['label' => 'Secondary', 'default' => '#1565c0'],
        'button' => ['label' => 'Button', 'default' => '#00e5ff'],
        'hover' => ['label' => 'Hover', 'default' => '#1de9b6'],
        'warning' => ['label' => 'Warning', 'default' => '#ff5252'],
        'focus' => ['label' => 'Focus', 'default' => '#64ffda'],
    ];

    $wp_customize->add_section('novi_color_section', [
        'title' => __('Novi Color Palette', 'novi'),
        'priority' => 90,
    ]);

    foreach ($colors as $slug => $data) {
        $wp_customize->add_setting("novi_theme_$slug", [
            'default' => $data['default'],
            'sanitize_callback' => 'sanitize_hex_color',
        ]);

        $wp_customize->add_control(new \WP_Customize_Color_Control(
            $wp_customize,
            "novi_theme_$slug",
            [
                'label' => $data['label'],
                'section' => 'novi_color_section',
                'settings' => "novi_theme_$slug",
            ]
        ));
    }

    // Tipografia personalizzata
    $wp_customize->add_section('novi_typography_section', [
        'title' => __('Novi typography', 'novi'),
        'priority' => 95,
    ]);

    // Font Body
    $wp_customize->add_setting('novi_font_body', [
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control(new \WP_Customize_Upload_Control($wp_customize, 'novi_font_body', [
        'label' => __('Upload Body Font (.woff2 or .ttf)', 'novi'),
        'section' => 'novi_typography_section',
    ]));

    // Font Heading
    $wp_customize->add_setting('novi_font_heading', [
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control(new \WP_Customize_Upload_Control($wp_customize, 'novi_font_heading', [
        'label' => __('Upload Heading Font (.woff2 or .ttf)', 'novi'),
        'section' => 'novi_typography_section',
    ]));

    // Nota MIME per font personalizzati
    $wp_customize->add_setting('novi_typography_mime_note', [
        'sanitize_callback' => '__return_null',
    ]);
    $doc_url = esc_url('https://cupiolistudio.it/novi/documentazione');

    $wp_customize->add_control('novi_typography_mime_note', [
        'type' => 'hidden',
        'label' => __('How to activate load font?', 'novi'),
        'section' => 'novi_typography_section',
        'description' => wp_kses_post(
            __('The font feature is deactivated by default.<br><br>', 'novi') .
                __('More precisely, it\'s not possible to upload fonts directly.<br><br>', 'novi') .
                '<a href="' . $doc_url . '" target="_blank" rel="noopener noreferrer">' .
                __('See the official documentation', 'novi') .
                '</a>'
        ),
    ]);
}
add_action('customize_register', 'novi_theme_customize_register');

/**
 * Genera il CSS per i font caricati dinamicamente (Customizer) senza stamparlo direttamente.
 * Restituisce una stringa CSS da agganciare a uno stylesheet enqueued tramite wp_add_inline_style().
 */
function novi_build_inline_font_css()
{
    $body_font    = get_theme_mod('novi_font_body');
    $heading_font = get_theme_mod('novi_font_heading');

    if (! $body_font && ! $heading_font) {
        return '';
    }

    $css = '';

    // Body font.
    if ($body_font) {
        $ext    = pathinfo($body_font, PATHINFO_EXTENSION);
        $format = ('ttf' === strtolower($ext)) ? 'truetype' : 'woff2';

        // Usa esc_url per l'URL; $format è una whitelist (truetype/woff2).
        $css .= "@font-face{font-family:'CustomBodyFont';src:url('" . esc_url($body_font) . "') format('{$format}');font-weight:400;font-style:normal;font-display:swap;}";
        $css .= ":root{--wp--preset--font-family--body:'CustomBodyFont',sans-serif;}";
    }

    // Heading font.
    if ($heading_font) {
        $ext    = pathinfo($heading_font, PATHINFO_EXTENSION);
        $format = ('ttf' === strtolower($ext)) ? 'truetype' : 'woff2';

        $css .= "@font-face{font-family:'CustomHeadingFont';src:url('" . esc_url($heading_font) . "') format('{$format}');font-weight:700;font-style:normal;font-display:swap;}";
        $css .= ":root{--wp--preset--font-family--heading:'CustomHeadingFont',sans-serif;}";
    }

    return $css;
}

/**
 * Aggancia il CSS dei font allo stylesheet frontend già enqueued.
 * Sostituisci 'novi-style' con l'handle reale del tuo tema, se diverso.
 */
function novi_add_inline_font_styles()
{
    $css = novi_build_inline_font_css();
    if ('' === $css) {
        return;
    }
    wp_add_inline_style('novi-main-style', $css);
}
add_action('wp_enqueue_scripts', 'novi_add_inline_font_styles', 20);

/**
 * Aggancia lo stesso CSS dei font anche nell'editor a blocchi.
 */
function novi_add_inline_font_styles_editor()
{
    $css = novi_build_inline_font_css();
    if ('' === $css) {
        return;
    }
    wp_add_inline_style('wp-block-library', $css);
}
add_action('enqueue_block_editor_assets', 'novi_add_inline_font_styles_editor', 20);

/**
 * Costruisce il CSS delle variabili colore a partire dai setting del Customizer.
 * Non stampa nulla: restituisce una stringa da agganciare con wp_add_inline_style().
 */
function novi_build_palette_css()
{
    $slugs = ['basecolor', 'contrastcolor', 'primary', 'secondary', 'button', 'hover', 'warning', 'focus'];
    $vars  = [];

    foreach ($slugs as $slug) {
        $raw = get_theme_mod("novi_theme_{$slug}");
        if (! $raw) {
            continue;
        }

        // 1) HEX sicuri (#fff / #ffffff)
        $hex = sanitize_hex_color($raw);
        if ($hex) {
            $vars[] = "--{$slug}: {$hex}; --wp--preset--color--{$slug}: var(--{$slug});";
            continue;
        }

        // 2) (Opzionale) consenti rgb/rgba molto basilari
        if (is_string($raw) && preg_match('/^rgba?\(\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*\d{1,3}(?:\s*,\s*(0|1|0?\.\d+))?\s*\)$/', trim($raw))) {
            $safe = trim($raw);
            $vars[] = "--{$slug}: {$safe}; --wp--preset--color--{$slug}: var(--{$slug});";
        }
    }

    if (empty($vars)) {
        return '';
    }

    return ':root{' . implode('', $vars) . '}';
}

/**
 * Aggancia il CSS della palette colori allo stylesheet frontend del tema.
 * Handle allineato a quello usato per i font: 'novi-main-style'.
 */
function novi_add_dynamic_palette_css()
{
    $css = novi_build_palette_css();
    if ('' === $css) {
        return;
    }
    // Assicurati che 'novi-main-style' sia effettivamente enqueued.
    wp_add_inline_style('novi-main-style', $css);
}
add_action('wp_enqueue_scripts', 'novi_add_dynamic_palette_css', 21);

/**
 * Aggancia la stessa palette anche nell'editor a blocchi.
 */
function novi_add_dynamic_palette_css_editor()
{
    $css = novi_build_palette_css();
    if ('' === $css) {
        return;
    }
    wp_add_inline_style('wp-block-library', $css);
}
add_action('enqueue_block_editor_assets', 'novi_add_dynamic_palette_css_editor', 21);
