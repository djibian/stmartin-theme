<?php
/**
 * Template used to display post content on single pages.
 *
 * @package storefront
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<header class="entry-header">
		<?php
			get_template_part( 'photo', 'single-producer' );
			the_title( '<h1 class="producer-name">', '</h1>' );
			$jobs = wp_get_post_terms($post->ID,'job');
			echo '<div class="dash"></div><h2 class="producer-job">';
			foreach($jobs as $key => $term)
			{
				$name = $term->to_array()['name'];
				if ($key != 0) {echo ' / ';}
				echo $name;
			}
			echo '</h2>';
			get_template_part( 'city', 'single-producer' );
		?>
		</header><!-- .entry-header -->

		<div class="entry-content">

		<?php
			the_content(
				sprintf(
					/* translators: %s: post title */
					__( 'Continue reading %s', 'storefront' ),
					'<span class="screen-reader-text">' . get_the_title() . '</span>'
				)
			);
			
		?>
		</div><!-- .entry-content -->

		<?php
		/* flèche de navigation */
		storefront_post_nav();
		/* Commentaires */
		storefront_display_comments()

	?>

</article><!-- #post-## -->
