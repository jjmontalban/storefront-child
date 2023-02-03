<?php
/**
 * Quick view template
 *
 * Uses same hooks as single product template so more plugins will work with
 * quick view.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

// Change form action to avoid redirect to product page.
add_filter( 'woocommerce_add_to_cart_form_action', '__return_empty_string' );

/* do_action( 'wc_quick_view_before_single_product' ); */
?>
<div class="woocommerce quick-view single-product">

	<div id="product-<?php the_ID(); ?>" <?php wc_product_class(); ?>>
		<?php
			/**
			* Hook: woocommerce_before_single_product_summary.
			*
			* @hooked woocommerce_show_product_sale_flash - 10
			* @hooked woocommerce_show_product_images - 20
			*/
			/* do_action( 'woocommerce_before_single_product_summary' ); */
			?>
		<div class="summary entry-summary">
			<?php
				/**
				 * Hook: woocommerce_single_product_summary.
				 *
				 * @hooked woocommerce_template_single_title - 5
				 * @hooked woocommerce_template_single_rating - 10
				 * @hooked woocommerce_template_single_price - 10
				 * @hooked woocommerce_template_single_excerpt - 20
				 * @hooked woocommerce_template_single_add_to_cart - 30
				 * @hooked woocommerce_template_single_meta - 40
				 * @hooked woocommerce_template_single_sharing - 50
				 * @hooked WC_Structured_Data::generate_product_data() - 60
				 */

				$product_id = get_the_ID();

				//Calcular las fechas
				$fecha_efecto = date('d-m-y');
                $hora_actual = date("h:i:s");
				$fecha_sig = new DateTime('+1 day');
                $hora_actual = date("h:i:s",strtotime( $fecha_efecto . "+ 1 hours"));

                if( $hora_actual > '18:45:00')
				{
                    $fecha_efecto = date("d-m-Y",strtotime( $fecha_efecto . "+ 1 days"));
					$fecha_sig = new DateTime('+2 day');
				}

				switch( $product_id )
				{
					case 11:
						?>
						<h2>E-RELAX</h2>
						<p>E-RELAX ayuda a autorregularte el estrés con un solo clic, proporcionando tranquilidad</p>
						<p>Es fácil comprobar su efectividad. Comprueba el efecto el <?php echo $fecha_efecto; ?> comparando tu estado de ánimo 10min antes y  10min después de las 19:00</p>
						<p>Te invitamos a probar los resultados, por ello tu primera compra será completamente gratis.</p>
						<a href="https://emotionpills.com/finalizar-compra/?add-to-cart=11" class="button btn-relax-quick">Comprar</a>
						<?php
						break;

					case 13:
						?>
						<h2>E-SLEEP</h2>
						<p>E-SLEEP ayuda a autorregularte el estrés con un solo clic, proporcionando tranquilidad.</p>
						<p>Es fácil comprobar su efectividad. Comprueba el efecto la noche del <?php echo $fecha_efecto; ?> al <?php echo $fecha_sig->format('d-m-y'); ?> analizando la calidad de tu sueño al despertar.</p>
						<p>Te invitamos a probar los resultados, por ello tu primera compra será completamente gratis.</p>
						<a href="https://emotionpills.com/finalizar-compra/?add-to-cart=13" class="button btn-sleep-quick">Comprar</a>
						<?php
						break;

					case 14:
						?>
						<h2>E-RELAX</h2>
						<p>E-RELAX ayuda a autorregularte la tristeza con un solo clic mejorando su estado de ánimo.</p>
						<p>Es fácil comprobar su efectividad. Comprueba el efecto el <?php echo $fecha_efecto; ?> comparando tu estado de ánimo 10min antes y  10min después de las 19:00</p>
						<p>Te invitamos a probar los resultados, por ello tu primera compra será completamente gratis.</p>
						<a href="https://emotionpills.com/finalizar-compra/?add-to-cart=14" class="button btn-peace-quick">Comprar</a>
						<?php
						break;

					default:
						return 0;
						?>
				<?php
				}
				?> 
		</div>
	</div>
</div>

<?php
/**
 * Hook: wc_quick_view_after_single_product.
 */
do_action( 'wc_quick_view_after_single_product' );

remove_filter( 'woocommerce_add_to_cart_form_action', '__return_empty_string' );
?>

