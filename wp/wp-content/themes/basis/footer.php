<?php
/**
 * The template for displaying the footer.
 *
 * @package WordPress
 * @subpackage Basis
 * @since Basis 1.0
 */
?>

	</div><!-- #main -->

	<!-- Footer
    ================================================== -->
	<footer id="colophon" role="contentinfo">

		<?php get_sidebar( 'footer' ); ?>
		<div id="site-generator-wrapper">
			<section id="site-generator" class="clearfix">
				<div class="clearfix">
				
				<?php
				$enable_social_icons = ot_get_option( 'enable_social_icons' );
				if ( ! empty( $enable_social_icons ) ) {
					
					$footer_social_icons_position = ot_get_option( 'footer_social_icons_position' );
					if ( empty( $footer_social_icons_position ) ) {
						$footer_social_icons_position == 'right';
					}
					?>
					<div class="social-accounts-wrapper <?php echo sanitize_html_class( $footer_social_icons_position ); ?> clearfix">
						<?php get_template_part( 'social-accounts' ); // Social accounts ?>
					</div>
				<?php } ?>
				
				
				<?php
				/*
				 * Print the footer info.
				 */
				$footer_info = ot_get_option( 'footer_info' );
				
				if ( function_exists( 'icl_t' ) ) {
					echo icl_t( 'OptionTree', 'footer_info', $footer_info );
				} else {
					if ( empty( $footer_info ) ) {
						$footer_info == '&copy; Basis Portfolio / Photography WordPress Theme';
					}
					echo wpautop( $footer_info );
				}
				?>
				
				<?php
				$back_to_top_button = ot_get_option( 'back_to_top_button' );
				if ( $back_to_top_button == 'enable' ) {
				?>
				<a href="javascript:void(null);" id="to-top"><div></div></a>
				<?php } ?>
				</div>
			</section>
		</div><!-- #site-generator-wrapper -->
	</footer><!-- #colophon -->
</section><!-- #page -->

<?php //wp_footer(); ?>

</body>
<?php wp_footer(); ?>
</html>