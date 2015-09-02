<?php
/**
 * The template for displaying pages
 *
 */

get_header(); ?>

	<div id="main" class="clearfix">

		<div id="primary">
			<div id="content" role="main">
				<div class="entry-header-wrapper">
					<header class="entry-header clearfix">
						<h1 class="entry-title"><?php echo the_title();?></h1>
					</header><!-- .entry-header -->
				</div>
				
				<?php
				// Start the loop.
				while ( have_posts() ) : the_post();

					// Include the page content template.
					get_template_part( 'content', 'page' );

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

				// End the loop.
				endwhile;
				?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>