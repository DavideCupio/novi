<?php
// Template Name: Page Blank
// Description: A blank page template without header and footer.
?>
<main id="main-content" role="main" <?php post_class('site-main'); ?>>
    <div class="is-layout-constrained has-global-padding">
        <div class="page-content container">

            <?php
            while (have_posts()) :
                the_post();
                echo '<div class="is-layout-constrained has-global-padding">';
                the_content();
                echo '</div>';
            endwhile;
            ?>

        </div>
    </div>
</main>