<?php
/**
 * Customer processing order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-processing-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p>
	
	<style>
		* {
			font-family: "Calibri"; 
			text-align: justify; 
		  }

		.contenido {
			align-items: center;
			margin-left: auto;
			margin-right: auto; 
		}

      	h1, h3 { 
			font-family: "Calibri"; 
			text-align: center; 
			color: black; 
		}
      
		.image-product { 
			width: 40%; 
			margin-left: auto !important;
			margin-right: auto !important; 
		}
      
		.efecto-mail { 
			border-style: solid; 
			border-color: black; 
			border-radius: 5px; 
			background-color: trasparent; 
		}

	</style>
	
	<?php
	// getting the order products. System dont know that only have one product per order
	$items = $order->get_items();

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
	//$fecha_sig->format('d-m-y');
	// let's loop through each of them

	foreach ( $items as $item ) {
		$product = $item->get_product();
		$image = $product->get_image();

		//PEACE 14
		if( $item['product_id'] == 14 ) { ?>
			<div class="contenido">
				<p class="image-product"><?php echo $image; ?></p>
				<h1>E-PEACE</h1>
				<h3 class="efecto-mail">Efecto: <?php echo $fecha_efecto; ?> - 19:00 </h3>
				<p>Esta pastilla virtual ya ha sido “ingerida” en el momento de la compra.</p>
				<p>Lo único que debe hacer es comprobar los cambios comparando su estado de ánimo 10 minutos antes y 10 minutos después de las 19:00 del día <?php echo $fecha_efecto; ?>.</p>
				<p>E-PEACE puede ayudar a mejorar su estado de ánimo de forma automática. <strong>Resultado de larga duración.</strong></p>
				<p>Esta pastilla es de un solo uso. En el caso de obtener buenos resultados, adquirir otra pastilla para fijar los resultados en el tiempo.</p>
			</div>
		<?php }

		//SLEEP 13
		elseif( $item['product_id'] == 13 ) { ?>
			<div class="contenido">
				<p class="image-product"><?php echo $image; ?></p>
				<h1>E-SLEEP</h1>
				<h3 class="efecto-mail">Efecto: <?php echo $fecha_efecto; ?> a <?php echo $fecha_sig->format('d-m-y'); ?></h3>
				<p>Esta pastilla virtual ya ha sido “ingerida” en el momento de la compra.</p>
				<p>Lo único que debe hacer es comprobar los cambios analizando la calidad de su sueño la mañana del día <strong><?php echo $fecha_sig->format('d-m-y'); ?>.</strong></p>
				<p><strong>E-SLEEP</strong> puede ayudar a la reducción o eliminación del insomnio de forma automática.</p>
				<p>Esta pastilla es de un solo uso. En el caso de obtener buenos resultados, adquirir otra pastilla para fijar los resultados en el tiempo.</p>
			</div>
		<?php }
		//RELAX
		elseif ( $item['product_id'] == 11 ) { ?>
			<div class="contenido">
				<p class="image-product"><?php echo $image; ?></p>
				<h1>E-RELAX</h1>
				<h3 class="efecto-mail">Efecto: <?php echo $fecha_efecto; ?> - 19:00 </h3>
				<p>Esta pastilla virtual ya ha sido “ingerida” en el momento de la compra.</p>
				<p>Lo único que debe hacer es comprobar los cambios comparando su estado de ánimo 10 minutos antes y 10 minutos después de las 19:00 del día <?php echo $fecha_efecto; ?>.</p>
				<p><strong>E-RELAX</strong>  puede ayudar a la reducción del estrés y tensión interior de forma automática. <strong>Resultado de larga duración.</strong></p>
				<p>Esta pastilla es de un solo uso. En el caso de obtener buenos resultados, adquirir otra pastilla para fijar los resultados en el tiempo.</p>
			</div>
		<?php }
	}


?></p>

<?php

/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
/* do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email ); */

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
/* do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email ); */

/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
/* do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email ); */

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
/* if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
} */

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
/* do_action( 'woocommerce_email_footer', $email ); */


