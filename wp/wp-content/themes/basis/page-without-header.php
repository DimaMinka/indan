<?php
/**
 * Template Name: Page without Header Visual Composer
 * Description: A Page Template without header
 *
 * @package WordPress
 * @subpackage Basis
 * @since Basis 1.0
 */

get_header(); ?>

	<div id="main" class="clearfix">

		<div id="primary">
			<div id="content" role="main">
			
				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'page' ); ?>

				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>