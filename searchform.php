<?php

/**
 * The search form template file
 *
 * @package novi
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

?>

<div class="search-form">
    <form role="search" method="get" class="ricerca-form" action="<?php echo esc_url(home_url('/')); ?>"
        aria-labelledby="search-label">

        <label id="search-label" for="search-field" class="screen-reader-text">
            <?php esc_html_e('Cerca nel sito:', 'novi'); ?>
        </label>

        <div class="search-container">
            <input type="search" id="search-field" class="search-form-input"
                placeholder="<?php esc_attr_e('Cerca...', 'novi'); ?>" value="<?php echo get_search_query(); ?>"
                name="s" autocomplete="search" aria-required="true" required />

            <input type="hidden" name="post_type" value="post" />

            <button type="submit" aria-label="<?php esc_attr_e('Avvia ricerca', 'novi'); ?>"
                title="<?php esc_attr_e('Cerca', 'novi'); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#ffffff" width="21px" height="21px"
                    viewBox="0 0 1920 1920">
                    <path
                        d="M790.588 1468.235c-373.722 0-677.647-303.924-677.647-677.647 0-373.722 303.925-677.647 677.647-677.647 373.723 0 677.647 303.925 677.647 677.647 0 373.723-303.924 677.647-677.647 677.647Zm596.781-160.715c120.396-138.692 193.807-319.285 193.807-516.932C1581.176 354.748 1226.428 0 790.588 0S0 354.748 0 790.588s354.748 790.588 790.588 790.588c197.647 0 378.24-73.411 516.932-193.807l516.028 516.142 79.963-79.963-516.142-516.028Z"
                        fill-rule="evenodd" />
                </svg>
            </button>
        </div>

    </form>
</div> <!-- search form -->