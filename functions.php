<?php

if (! defined('ABSPATH')) {
    exit; // Sicurezza: blocca l'accesso diretto ai file PHP
}

// Definisce la costante della versione tema, usata per versionare CSS/JS (utile per il cache busting)
if (!defined('NOVI_VERSION')) {
    define('NOVI_VERSION', wp_get_theme()->get('Version'));
}

// Funzione di setup del tema (registrazione supporti, menu, traduzioni ecc.)
function novi_setup()
{
    add_theme_support('title-tag');
    add_theme_support('automatic-feed-links');
    add_theme_support('custom-logo');
    add_theme_support('post-thumbnails');
    add_theme_support('wp-block-styles');
    add_theme_support('editor-styles');
    add_editor_style('assets/css/editor-style.css');
    add_theme_support('block-template-parts');
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');

    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ]);

    add_theme_support('custom-background', [
        'default-color' => 'ffffff',
        'default-image' => '',
        'default-repeat' => 'no-repeat',
        'default-position-x' => 'center',
        'default-position-y' => 'top',
        'default-size' => 'cover',
        'default-attachment' => 'scroll',
    ]);

    add_theme_support('custom-header', [
        'width' => 1920,
        'height' => 600,
        'flex-width' => true,
        'flex-height' => true,
        'default-image' => get_template_directory_uri() . '/assets/img/fallback.jpg',
        'header-text' => false,
    ]);

    load_theme_textdomain('novi', get_template_directory() . '/languages');

    register_nav_menus([
        'header-menu' => __('Menu principale', 'novi'),
    ]);
}
add_action('after_setup_theme', 'novi_setup');

// Caricamento degli script e degli stili del tema nel frontend
function enqueue_scripts()
{
    $version = defined('NOVI_VERSION') ? NOVI_VERSION : wp_get_theme()->get('Version');

    wp_enqueue_style('novi-style', get_stylesheet_directory_uri() . '/assets/css/style.css', [], $version);
    wp_enqueue_script('novi-script', get_stylesheet_directory_uri() . '/assets/js/script.js', ['jquery'], $version, true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'enqueue_scripts');

// Inclusione dei file esterni
function require_theme_files()
{
    require_once get_template_directory() . '/inc/customizer.php';
    $dynamic_css_path = get_template_directory() . '/inc/dynamic-css.php';
    if (file_exists($dynamic_css_path)) {
        require_once $dynamic_css_path;
    }
    include get_template_directory() . '/inc/gutemberg.php';
}
add_action('after_setup_theme', 'require_theme_files');

// Aggiunge tabindex ai link del menu per migliorare l'accessibilità
function add_tabindex_link_menu($atts, $item, $args)
{
    $atts['tabindex'] = '0';
    return $atts;
}
add_filter('nav_menu_link_attributes', 'add_tabindex_link_menu', 10, 3);

// Titoli degli archivi personalizzati
function custom_archive_title($title)
{
    if (is_home()) {
        $title = 'Blog';
    } elseif (is_category() || is_tag()) {
        $title = single_term_title('', false);
    }
    return $title;
}
add_filter('get_the_archive_title', 'custom_archive_title');

// Calcolo tempo di lettura
function novi_post_reading_time($post_id = null)
{
    $post_id = $post_id ?: get_the_ID();
    $content = get_post_field('post_content', $post_id);

    if (empty($content)) {
        return esc_html__('Tempo di lettura non disponibile', 'novi');
    }

    $word_count = str_word_count(strip_tags(strip_shortcodes($content)));
    $minutes = ceil($word_count / 200);

    return sprintf(
        _n('%d minuto di lettura', '%d minuti di lettura', $minutes, 'novi'),
        $minutes
    );
}

// Estratti personalizzati
function novi_get_custom_excerpt($length = 16, $more = '...')
{
    $text = get_the_excerpt();

    if (empty($text)) {
        $text = get_the_content();
    }

    $text = strip_shortcodes($text);
    $text = wp_kses_post($text);

    return wp_trim_words($text, $length, $more);
}

// Registrazione delle sidebar
function novi_register_sidebars()
{
    register_sidebar([
        'name' => esc_html__('Sidebar', 'novi'),
        'id' => 'sidebar',
        'description' => esc_html__('Sidebar principale', 'novi'),
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ]);

    register_sidebar([
        'name' => esc_html__('Footer left', 'novi'),
        'id' => 'footer-left',
        'description' => esc_html__('Widget nel footer', 'novi'),
        'before_widget' => '<div class="footer-widget-left %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ]);

    register_sidebar([
        'name' => esc_html__('Footer right', 'novi'),
        'id' => 'footer_right',
        'description' => esc_html__('Widget nel footer', 'novi'),
        'before_widget' => '<div class="footer-widget-right %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ]);
}
add_action('widgets_init', 'novi_register_sidebars');

// Registrazione dei block pattern
function register_novi_block_patterns()
{
    register_block_pattern(
        'novi/call-to-action',
        [
            'title'       => __('Call to Action', 'novi'),
            'description' => __('A centered call to action section.', 'novi'),
            'categories'  => ['buttons'],
            'content'     => '<!-- wp:group {"align":"full","className":"novi-cta"} -->
            <div class="wp-block-group alignfull novi-cta">
                <h2>Scopri il nostro prodotto</h2>
                <p>Un breve testo persuasivo qui.</p>
                <div class="wp-block-buttons"><div class="wp-block-button"><a class="wp-block-button__link">Scopri di più</a></div></div>
            </div>
            <!-- /wp:group -->',
        ]
    );
}
add_action('init', 'register_novi_block_patterns');
