<?php

namespace Novi\Theme;

if (! defined('ABSPATH')) {
    exit; // Sicurezza: blocca l'accesso diretto ai file PHP
}

// ✅ Definisce la costante della versione tema, usata per versionare CSS/JS (utile per il cache busting)
if (!defined('NOVI_VERSION')) {
    define('NOVI_VERSION', \wp_get_theme()->get('Version'));
}

// ✅ Include file esterni del tema
require_once get_template_directory() . '/inc/customizer.php';

$dynamic_css_path = get_template_directory() . '/inc/dynamic-css.php';
if (file_exists($dynamic_css_path)) {
    require_once $dynamic_css_path;
}

include(get_template_directory() . '/inc/gutemberg.php');


// ✅ Funzione di setup del tema (registrazione supporti, menu, traduzioni ecc.)
function novi_setup()
{
    // Titolo automatico gestito da WordPress
    add_theme_support('title-tag');

    // Feed RSS nel <head>
    add_theme_support('automatic-feed-links');

    // Supporto per logo personalizzato
    add_theme_support('custom-logo');

    // Abilita le immagini in evidenza per i post
    add_theme_support('post-thumbnails');

    // Permette di usare CSS personalizzati nell’editor Gutenberg
    add_theme_support('wp-block-styles');
    add_theme_support('editor-styles');
    add_editor_style('assets/css/editor-style.css'); // Il file CSS dell'editor

    // Supporto per i blocchi template-part (utile per l'adozione parziale del Full Site Editing)
    add_theme_support('block-template-parts');

    // Rende embed responsivi (video, iframe, ecc.)
    add_theme_support('responsive-embeds');

    // Abilita larghezze "wide" e "full" per i blocchi che lo supportano
    add_theme_support('align-wide');

    // Supporto per output HTML5 più pulito
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);

    // custom background
    add_theme_support('custom-background', array(
        'default-color'          => 'ffffff',
        'default-image'          => '',
        'default-repeat'         => 'no-repeat',
        'default-position-x'     => 'center',
        'default-position-y'     => 'top',
        'default-size'           => 'cover',
        'default-attachment'     => 'scroll',
    ));
    //custom header
    add_theme_support('custom-header', array(
        'width'         => 1920,
        'height'        => 600,
        'flex-width'    => true,
        'flex-height'   => true,
        'default-image' => get_template_directory_uri() . '/assets/img/fallback.jpg',
        'header-text'   => false,
    ));

    // Caricamento traduzioni dal percorso /languages
    load_theme_textdomain('novi', get_template_directory() . '/languages');

    // ✅ Registrazione del menu principale
    register_nav_menus([
        'header-menu' => __('Menu principale', 'novi'),
    ]);
}
add_action('after_setup_theme', __NAMESPACE__ . '\\novi_setup');


// ✅ Caricamento degli script e degli stili del tema nel frontend
function enqueue_scripts()
{
    $version = defined('NOVI_VERSION') ? NOVI_VERSION : \wp_get_theme()->get('Version');

    // ✅ CSS principale del tema
    \wp_enqueue_style('novi-style', \get_stylesheet_directory_uri() . '/assets/css/style.css', [], $version);

    // ✅ JS principale del tema
    \wp_enqueue_script('novi-script', \get_stylesheet_directory_uri() . '/assets/js/script.js', ['jquery'], $version, true);

    // ✅ Script per risposte ai commenti (solo se i commenti sono abilitati e si è in una singola pagina/post)
    if (\is_singular() && \comments_open() && \get_option('thread_comments')) {
        \wp_enqueue_script('comment-reply');
    }
}
\add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_scripts');


// ✅ Aggiunge tabindex ai link del menu per migliorare l'accessibilità
function add_tabindex_link_menu($atts, $item, $args)
{
    $atts['tabindex'] = '0';
    return $atts;
}
add_filter('nav_menu_link_attributes', __NAMESPACE__ . '\\add_tabindex_link_menu', 10, 3);


//!------------------------
//!------- TITOLI DEGLI ARCHIVI PERSONALIZZATI
//!------------------------
function custom_archive_title($title)
{
    if (is_home()) {
        $title = 'Blog';
    }
    // Categoria o tag
    elseif (is_category() || is_tag()) {
        $title = single_term_title('', false);
    }


    return $title;
}
add_filter('get_the_archive_title', __NAMESPACE__ . '\\custom_archive_title');


//!------------------------
//!------- GESTIONE DEGLI ESTRATTI PERSONALIZZATI
//!------------------------
function get_custom_excerpt($length = 16, $more = '...')
{
    $text = get_the_excerpt();

    // Se l'excerpt non esiste, usa il contenuto del post
    if (empty($text)) {
        $text = get_the_content();
    }

    // Rimuove shortcode e tag HTML per sicurezza e pulizia
    $text = strip_shortcodes($text);
    $text = wp_kses_post($text);

    // Taglia il testo al numero di parole desiderato
    return wp_trim_words($text, $length, $more);
}
//!------------------------
//!------- WIDGET
//!------------------------
function register_sidebars()
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
add_action('widgets_init', __NAMESPACE__ . '\\register_sidebars');

//!------------------------
//!------- BLOCK PATTERNS
//!------------------------
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
