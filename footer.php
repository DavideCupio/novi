<?php

/**
 * File: footer.php
 * Description: Output del footer del sito e chiusura del body e dell'HTML. Caricato in tutte le pagine.
 *
 * @package novi
 * @since 1.0.0
 */

?>


<footer class="footer site-footer" role="contentinfo"
    aria-label="<?php esc_attr_e('Informazioni sul sito', 'novi'); ?>">
    <div class="container is-layout-constrained has-global-padding">
        <div class="main-footer alignwide">
            <?php get_template_part('assets/components/scroll-top'); ?>
            <p class="footer-text">
                <?php
                printf(
                    esc_html__('&copy; %1$s %2$s. Tutti i diritti riservati.', 'novi'),
                    date('Y'),
                    get_bloginfo('name')
                );
                ?>
            </p>
        </div>
    </div>
</footer>

<div class="custom-cursor">
    <div class="cursor-inner"></div>
</div><!-- custom-cursor -->

<?php wp_footer(); ?>
</body>

</html>