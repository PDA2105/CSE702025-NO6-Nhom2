<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package generatePress
 */

get_header();
?>
	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			// Use simple_product shortcode to display product details
			echo do_shortcode('[simple_product]');

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
