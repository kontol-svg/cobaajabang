<?php
/**
 * The template for displaying the footer.
 *
 * @package GeneratePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

	</div>
</div>

<?php
/**
 * generate_before_footer hook.
 *
 * @since 0.1
 */
do_action( 'generate_before_footer' );
?>

<div <?php generate_do_attr( 'footer' ); ?>>
	<?php
	/**
	 * generate_before_footer_content hook.
	 *
	 * @since 0.1
	 */
	do_action( 'generate_before_footer_content' );

	/**
	 * generate_footer hook.
	 *
	 * @since 1.3.42
	 *
	 * @hooked generate_construct_footer_widgets - 5
	 * @hooked generate_construct_footer - 10
	 */
	do_action( 'generate_footer' );

	/**
	 * generate_after_footer_content hook.
	 *
	 * @since 0.1
	 */
	do_action( 'generate_after_footer_content' );
	?>
</div>

<?php
/**
 * generate_after_footer hook.
 *
 * @since 2.1
 */
do_action( 'generate_after_footer' );

wp_footer();
?>
<div style="display:none"><ul><li><a href="https://boucherieus.org/">https://boucherieus.org/</a></li><li><a href="https://boucherieus.org/boucherie-west-village-menus/">https://boucherieus.org/boucherie-west-village-menus/</a></li><li><a href="https://boucherieus.org/location/boucherie-west-village/">https://boucherieus.org/location/boucherie-west-village/</a></li><li><a href="https://boucherieus.org/location/la-grande-boucherie-dc/">https://boucherieus.org/location/la-grande-boucherie-dc/</a></li><li><a href="https://boucherieus.org/location/chicago/">https://boucherieus.org/location/chicago/</a></li><li><a href="https://boucherieus.org/location/la-grande-boucherie-miami/">https://boucherieus.org/location/la-grande-boucherie-miami/</a></li><li><a href="https://boucherieus.org/events/">https://boucherieus.org/events/</a></li><li><a href="https://boucherieus.org/la-grande-ny-reservations/">https://boucherieus.org/la-grande-ny-reservations/</a></li><li><a href="https://boucherieus.org/la-grande-mia-reservations/">https://boucherieus.org/la-grande-mia-reservations/</a></li><li><a href="https://bubbagump.org/">https://bubbagump.org/</a></li><li><a href="https://bubbagump.org/location/bubba-gump-orlando-fl/">https://bubbagump.org/location/bubba-gump-orlando-fl/</a></li><li><a href="https://bubbagump.org/menu/food-bgkh/">https://bubbagump.org/menu/food-bgkh/</a></li><li><a href="https://bubbagump.org/view-all-locations/">https://bubbagump.org/view-all-locations/</a></li><li><a href="https://bubbagump.org/location/bubba-gump-gatlinburg-tn/">https://bubbagump.org/location/bubba-gump-gatlinburg-tn/</a></li><li><a href="https://indofood.org/product/sarimi">https://indofood.org/product/sarimi</a></li><li><a href="https://indofood.org/product/milkuat/product">https://indofood.org/product/milkuat/product</a></li><li><a href="https://indofood.org/product/tiga-sapi">https://indofood.org/product/tiga-sapi</a></li><li><a href="https://indofood.org/career/">hhttps://indofood.org/career/</a></li><li><a href="https://indofood.org/company/board-of-commissioners/hans-kartikahadi.html">https://indofood.org/company/board-of-commissioners/hans-kartikahadi.html</a></li></ul></div>
</body>
</html>
