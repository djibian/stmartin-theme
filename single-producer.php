<?php
/**
 * The template for displaying all single posts.
 *
 * @package storefront
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'content', 'single-producer' );

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<div id="secondary" role="complementary">
	<?php	
	get_template_part( 'photo', 'single-producer' );
	get_template_part( 'city', 'single-producer' );
	?>
</div><!-- #secondary -->
<?php
get_footer();
