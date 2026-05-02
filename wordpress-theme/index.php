<?php
/**
 * TSF Monitor — index.php (fallback template)
 * Redirects to front-page.php for static front page.
 */
get_header();
if (have_posts()) :
    while (have_posts()) : the_post();
        get_template_part('template-parts/content', get_post_type());
    endwhile;
endif;
get_footer();
