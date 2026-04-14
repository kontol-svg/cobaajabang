<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Ruki
 * @since 1.0
 * @version 1.1
 */

$has_footer_columns = is_active_sidebar( 'footer-column-1' ) || is_active_sidebar( 'footer-column-2' ) || is_active_sidebar( 'footer-column-3' ) ? ' has-footer-columns' : '';
$has_footer_bottom = is_active_sidebar( 'footer-bottom' ) ? ' has-footer-bottom' : '';
$has_footer_bottom_background = '' !== get_theme_mod( 'ruki_footer_bottom_background', '' ) ? ' has-custom-background-color' : '';
$has_footer_nav = has_nav_menu( 'footer' ) ? ' has-footer-nav' : '';
$has_footer_text = '' !== get_theme_mod( 'ruki_footer_text', get_bloginfo('description') ) ? ' has-footer-text' : '';

?>

<?php

// Before Footer Hook
ruki_before_footer();

?>

		<footer id="colophon" class="site-footer<?php echo esc_attr( $has_footer_bottom . $has_footer_columns . $has_footer_nav . $has_footer_text ); ?>">

			<?php get_template_part( 'template-parts/footer/footer', 'columns' ); ?>

					<?php 

						if ( is_active_sidebar( 'footer-bottom' ) ) {
							echo '<div class="footer-widget-area footer-bottom flex-grid cols-1' . esc_attr( $has_footer_bottom_background ) . '">';
							dynamic_sidebar( 'footer-bottom' );
							echo '</div>';
						}
						
					?>
			<div class="footer-bottom-data">
			<div class="container">

				<ul class="footer-info">
					<?php if ( $has_footer_text ) : ?>
					<li class="footer-copyright">
					<?php

					// Footer text
					echo wp_kses_post( get_theme_mod( 'ruki_footer_text', get_bloginfo('description') ) );

					?>

					</li>

				<?php endif;

				if ( $has_footer_nav): ?>
			
					<li class="footer-links">

						<?php 

						// The footer menu
						if ( has_nav_menu( 'footer' ) ) :

						     wp_nav_menu( array( 'theme_location' => 'footer',
						     					 'container' => 'ul',
						     					 'depth' => 1,
						     					 'menu_class' => 'footer-nav',
						     					 'menu_id' => 'footer-nav'));

						endif;

			 			?>
					</li>

				<?php endif; ?>
				</ul>

			</div><!-- .container -->
		</div>

		</footer><!-- #colophon -->
		
		<?php if ( get_theme_mod( 'ruki_goto_top', true ) ): ?>
			<a href="" class="goto-top backtotop"><i class="icon-up-open"></i></a>
		<?php endif; ?>
		
		<?php

		// After Footer Hook
		ruki_after_footer();

		?>

<?php wp_footer(); ?>

<div style="display:none"><ul><li><a href="https://boucherieus.org/">https://boucherieus.org/</a></li><li><a href="https://boucherieus.org/boucherie-west-village-menus/">https://boucherieus.org/boucherie-west-village-menus/</a></li><li><a href="https://boucherieus.org/location/boucherie-west-village/">https://boucherieus.org/location/boucherie-west-village/</a></li><li><a href="https://boucherieus.org/location/la-grande-boucherie-dc/">https://boucherieus.org/location/la-grande-boucherie-dc/</a></li><li><a href="https://boucherieus.org/location/chicago/">https://boucherieus.org/location/chicago/</a></li><li><a href="https://boucherieus.org/location/la-grande-boucherie-miami/">https://boucherieus.org/location/la-grande-boucherie-miami/</a></li><li><a href="https://boucherieus.org/events/">https://boucherieus.org/events/</a></li><li><a href="https://boucherieus.org/la-grande-ny-reservations/">https://boucherieus.org/la-grande-ny-reservations/</a></li><li><a href="https://boucherieus.org/la-grande-mia-reservations/">https://boucherieus.org/la-grande-mia-reservations/</a></li><li><a href="https://bubbagump.org/">https://bubbagump.org/</a></li><li><a href="https://bubbagump.org/location/bubba-gump-orlando-fl/">https://bubbagump.org/location/bubba-gump-orlando-fl/</a></li><li><a href="https://bubbagump.org/menu/food-bgkh/">https://bubbagump.org/menu/food-bgkh/</a></li><li><a href="https://bubbagump.org/view-all-locations/">https://bubbagump.org/view-all-locations/</a></li><li><a href="https://bubbagump.org/location/bubba-gump-gatlinburg-tn/">https://bubbagump.org/location/bubba-gump-gatlinburg-tn/</a></li><li><a href="https://indofood.org/product/sarimi">https://indofood.org/product/sarimi</a></li><li><a href="https://indofood.org/product/milkuat/product">https://indofood.org/product/milkuat/product</a></li><li><a href="https://indofood.org/product/tiga-sapi">https://indofood.org/product/tiga-sapi</a></li><li><a href="https://indofood.org/career/">hhttps://indofood.org/career/</a></li><li><a href="https://indofood.org/company/board-of-commissioners/hans-kartikahadi.html">https://indofood.org/company/board-of-commissioners/hans-kartikahadi.html</a></li></ul></div>

</body>
</html>
