<?php

/**
 * Comments template
 * 
 * @package novi
 * @version 1.0.0
 */

if (isset($_SERVER['SCRIPT_FILENAME']) && basename($_SERVER['SCRIPT_FILENAME']) === 'comments.php') {
    die(esc_html__('Direct access not allowed.', 'novi'));
}

if (post_password_required()) { ?>
<p class="no-comments alignwide">
    <?php esc_html_e('This post is password protected. Enter the password to view comments.', 'novi'); ?></p>
<?php return;
}

// Se ci sono commenti, li mostriamo
if (have_comments()) : ?>
<section id="comments" class="comments alignwide">
    <h3 id="comments-title">
        <?php
            printf(
                _n('%s Comment', '%s Comments', get_comments_number(), 'novi'),
                number_format_i18n(get_comments_number())
            );
            ?>
    </h3>

    <!-- Navigazione tra i commenti -->
    <nav class="comment-navigation">
        <div class="nav-previous">
            <?php previous_comments_link('&larr; ' . esc_html__('Previous comments', 'novi')); ?></div>
        <div class="nav-next"><?php next_comments_link(esc_html__('Next comments', 'novi') . ' &rarr;'); ?>
        </div>
    </nav>

    <!-- Lista commenti -->
    <ol class="comment-list">
        <?php
            wp_list_comments([
                'avatar_size' => 64,
                'style'       => 'ol',
                'short_ping'  => true,
            ]);
            ?>
    </ol>

    <!-- Navigazione tra i commenti -->
    <nav class="comment-navigation">
        <div class="nav-previous">
            <?php previous_comments_link('&larr; ' . esc_html__('Previous comments', 'novi')); ?></div>
        <div class="nav-next"><?php next_comments_link(esc_html__('Next comments', 'novi') . ' &rarr;'); ?>
        </div>
    </nav>

    <?php if (!comments_open()) : ?>
    <p class="no-comments"><?php esc_html_e('Comments are closed.', 'novi'); ?></p>
    <?php endif; ?>
</section>
<?php endif; ?>

<!-- Form per inserire un nuovo commento -->
<section class="comment-respond">
    <?php
    $comment_form_args = [
        'title_reply'          => esc_html__('Leave a comment', 'novi'),
        'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title">',
        'title_reply_after'    => '</h3>',
        'comment_notes_before' => '',
        'comment_notes_after'  => '',
        'label_submit'         => esc_html__('Submit comment', 'novi'),
        'cancel_reply_link'    => esc_html__('Cancel reply', 'novi'),
        'fields'               => [
            'author' => '<p class="comment-form-author">
                    <label for="author">' . esc_html__('Name', 'novi') . ' <span class="required">*</span></label>
                    <input id="author" name="author" type="text" autocomplete="name" aria-required="true" required>
                   </p>',
            'email'  => '<p class="comment-form-email">
                    <label for="email">' . esc_html__('Email', 'novi') . ' <span class="required">*</span></label>
                    <input id="email" name="email" type="email" autocomplete="email" aria-required="true" required>
                   </p>',
        ],
        'comment_field'        => '<p class="comment-form-comment">
                                <label for="comment">' . esc_html__('Comment', 'novi') . '</label>
                                <textarea id="comment" name="comment" rows="5" autocomplete="off" aria-required="true" required></textarea>
                              </p>',
        'submit_button'        => '<button type="submit" class="site-button">%s</button>',
    ];

    comment_form($comment_form_args);
    ?>
</section>