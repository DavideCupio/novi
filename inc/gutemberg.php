<?php

/**
 * File: block-styles.php
 * Description: Gestione dinamica block style + CSS condizionale
 *
 * @package novi
 * @since 1.0.0
 */


if (!defined('ABSPATH')) {
    exit;
}

/**
 * Mappa variazioni personalizzate
 */
// Restituisce una mappa dei blocchi con le loro variazioni di stile personalizzate
function novi_get_block_style_variations()
{
    return array(
        'core/media-text' => array(
            'mobile-reverse' => __('Mobile Reverse', 'novi'),
        ),
        'core/button' => array(
            'negative' => __('Negative', 'novi'),
        ),
    );
}

/**
 * Registra le variazioni personalizzate
 */
// Registra le variazioni di stile personalizzate dei blocchi nel sistema di Gutenberg
function novi_register_custom_block_styles()
{
    $block_styles = novi_get_block_style_variations();

    foreach ($block_styles as $block => $styles) {
        foreach ($styles as $style_name => $label) {
            register_block_style(
                $block,
                array(
                    'name'  => $style_name,
                    'label' => $label,
                )
            );
        }
    }
}
add_action('init', 'novi_register_custom_block_styles');

/**
 * Controlla se il blocco è usato nella pagina
 */
// Controlla se un blocco specifico è presente nei contenuti della pagina
function novi_is_block_used($blocks, $block_name)
{
    foreach ($blocks as $block) {
        if (!is_array($block)) continue;

        if (isset($block['blockName']) && $block['blockName'] === $block_name) {
            return true;
        }

        if (!empty($block['innerBlocks'])) {
            if (novi_is_block_used($block['innerBlocks'], $block_name)) {
                return true;
            }
        }
    }
    return false;
}

/**
 * Controlla se una variazione di stile è usata
 */
// Controlla se una variazione di stile è stata applicata a un blocco nei contenuti della pagina
function novi_is_block_style_used(array $blocks, string $block_name, string $style_name): bool
{
    // Normalizza lo style name a slug (come usa WP nelle classi CSS).
    $style_slug = sanitize_title($style_name);

    // Cerca "is-style-{slug}" come classe intera separata da inizio/fine o spazi.
    $pattern = '/(?:^|\\s)is-style-' . preg_quote($style_slug, '/') . '(?:\\s|$)/';

    foreach ($blocks as $block) {
        if (!is_array($block)) {
            continue;
        }

        $name = isset($block['blockName']) ? $block['blockName'] : '';
        if ($name === $block_name) {
            $class = (isset($block['attrs']['className']) && is_string($block['attrs']['className']))
                ? $block['attrs']['className']
                : '';

            if ($class !== '' && preg_match($pattern, $class)) {
                return true;
            }
        }

        if (!empty($block['innerBlocks']) && is_array($block['innerBlocks'])) {
            if (novi_is_block_style_used($block['innerBlocks'], $block_name, $style_slug)) {
                return true;
            }
        }
    }

    return false;
}

/**
 * Carica CSS solo se blocchi/stili sono usati nel frontend
 */
// Carica i file CSS dei blocchi e delle variazioni solo se usati nel frontend
function novi_enqueue_custom_block_styles()
{
    if (!is_singular()) return;

    global $post;
    if (!$post instanceof WP_Post) return;

    // Analizza il contenuto del post e restituisce una struttura ad albero dei blocchi
    $blocks = parse_blocks($post->post_content);
    $style_dir_uri  = get_template_directory_uri() . '/assets/css/';
    $style_dir_path = get_template_directory() . '/assets/css/';

    // ▶ Variazioni personalizzate
    $block_styles = novi_get_block_style_variations();

    foreach ($block_styles as $block => $styles) {
        foreach ($styles as $style_name => $_) {
            if (novi_is_block_style_used($blocks, $block, $style_name)) {
                $filename = str_replace('/', '-', $block) . "--{$style_name}.css";
                $path     = $style_dir_path . $filename;

                // Se il file CSS esiste, lo enqueue con versione basata su filemtime per il cache busting
                if (file_exists($path)) {
                    wp_enqueue_style(
                        "novi-block-style-{$style_name}",
                        $style_dir_uri . $filename,
                        [],
                        filemtime($path)
                    );
                }
            }
        }
    }

    // ▶ Blocchi base (core-media-text.css ecc.)
    $core_blocks = array(
        'core/media-text',
        'core/button',
    );

    foreach ($core_blocks as $block) {
        $filename = str_replace('/', '-', $block) . '.css';
        $path     = $style_dir_path . $filename;

        // Se il file CSS esiste e il blocco è usato nella pagina, enqueue lo stile
        if (file_exists($path) && novi_is_block_used($blocks, $block)) {
            wp_enqueue_style(
                'novi-block-css-' . sanitize_title($block),
                $style_dir_uri . $filename,
                [],
                filemtime($path)
            );
        }
    }
}
add_action('wp', 'novi_enqueue_custom_block_styles');

/**
 * Carica sempre i CSS nell'editor
 */
// Carica sempre i CSS dei blocchi e delle variazioni nell'editor di Gutenberg
function novi_enqueue_editor_block_styles()
{
    $style_dir_uri  = get_template_directory_uri() . '/assets/css/';
    $style_dir_path = get_template_directory() . '/assets/css/';

    // ▶ Variazioni personalizzate
    $block_styles = novi_get_block_style_variations();

    foreach ($block_styles as $block => $styles) {
        foreach ($styles as $style_name => $_) {
            $filename = str_replace('/', '-', $block) . "--{$style_name}.css";
            $path     = $style_dir_path . $filename;

            // Se il file CSS esiste, enqueue lo stile per l'editor
            if (file_exists($path)) {
                wp_enqueue_style(
                    "novi-block-style-{$style_name}-editor",
                    $style_dir_uri . $filename,
                    [],
                    filemtime($path)
                );
            }
        }
    }

    // ▶ Blocchi base
    $core_blocks = array(
        'core/media-text',
        'core/button',
    );

    foreach ($core_blocks as $block) {
        $filename = str_replace('/', '-', $block) . '.css';
        $path     = $style_dir_path . $filename;

        // Se il file CSS esiste, enqueue lo stile base per l'editor
        if (file_exists($path)) {
            wp_enqueue_style(
                'novi-block-css-editor-' . sanitize_title($block),
                $style_dir_uri . $filename,
                [],
                filemtime($path)
            );
        }
    }
}
add_action('enqueue_block_assets', 'novi_enqueue_editor_block_styles');
