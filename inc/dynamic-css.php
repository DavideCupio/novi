<?php

/**
 * File: dynamic-css.php
 * Description: Generate dynamic CSS from Customizer settings and attach it to an enqueued stylesheet.
 *
 * @package novi
 * @since 1.0.0
 */

if (! function_exists('novi_build_palette_css')) {
    function novi_build_palette_css(): string
    {
        $slugs = ['basecolor', 'contrastcolor', 'primary', 'secondary', 'button', 'hover', 'warning', 'focus'];
        $vars  = [];

        foreach ($slugs as $slug) {
            $val = get_theme_mod("novi_theme_{$slug}");
            if (! $val) {
                continue;
            }

            // Sanitize HEX (#fff / #ffffff)
            $hex = sanitize_hex_color($val);
            if ($hex) {
                $vars[] = "--{$slug}: {$hex}; --wp--preset--color--{$slug}: var(--{$slug});";
                continue;
            }

            // (Facoltativo) consenti formati rgb/rgba molto basici.
            if (is_string($val) && preg_match('/^rgba?\(\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*\d{1,3}(?:\s*,\s*(0|1|0?\.\d+))?\s*\)$/', trim($val))) {
                $safe = trim($val);
                $vars[] = "--{$slug}: {$safe}; --wp--preset--color--{$slug}: var(--{$slug});";
            }
        }

        if (empty($vars)) {
            return '';
        }

        return ':root{' . implode('', $vars) . '}';
    }
}

if (! function_exists('novi_add_dynamic_palette_css')) {
    function novi_add_dynamic_palette_css(): void
    {
        $css = novi_build_palette_css();
        if ('' === $css) {
            return;
        }
        // Sostituisci 'novi-style' con il tuo handle se necessario.
        wp_add_inline_style('novi-main-style', $css);
    }
    // Dopo che gli stili sono stati enqueued.
    add_action('wp_enqueue_scripts', 'novi_add_dynamic_palette_css', 20);
}

if (! function_exists('novi_add_dynamic_palette_css_editor')) {
    // Applica le stesse variabili nell’editor blocchi.
    function novi_add_dynamic_palette_css_editor(): void
    {
        $css = novi_build_palette_css();
        if ('' === $css) {
            return;
        }
        wp_add_inline_style('wp-block-library', $css);
    }
    add_action('enqueue_block_editor_assets', 'novi_add_dynamic_palette_css_editor', 20);
}
