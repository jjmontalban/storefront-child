<?php

/**
 * Storefront automatically loads the core CSS even if using a child theme as it is more efficient
 * than @importing it in the child theme style.css file.
 *
 * Uncomment the line below if you'd like to disable the Storefront Core CSS.
 *
 * If you don't plan to dequeue the Storefront Core CSS you can remove the subsequent line and as well
 * as the sf_child_theme_dequeue_style() function declaration.
 */
//add_action( 'wp_enqueue_scripts', 'sf_child_theme_dequeue_style', 999 );

/**
 * Dequeue the Storefront Parent theme core CSS
 */
function sf_child_theme_dequeue_style() 
{
    wp_dequeue_style( 'storefront-style' );
    wp_dequeue_style( 'storefront-woocommerce-style' );
}

/**
 * Note: DO NOT! alter or remove the code above this text and only add your custom PHP functions below this text.
 */

/* Añadir fuentes */
add_action('wp_enqueue_scripts', function () 
{
    wp_enqueue_style('medicall-font-css', get_stylesheet_directory_uri() . '/fonts/stylesheet.css');
});


//Limita el carrito de WooCommerce a un único producto
add_filter( 'woocommerce_add_cart_item_data', 'mk_only_one_item_in_cart', 10, 1 );
function mk_only_one_item_in_cart( $cartItemData ) 
{
	wc_empty_cart();

	return $cartItemData;
}


/* Cambiar boton de añadir al carrito  */

/* 1. Removes the default Add To Cart button from the WooCommerce loop*/
add_action('init','zpd_remove_wc_loop_add_to_cart_button');
function zpd_remove_wc_loop_add_to_cart_button()
{
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
}

/* 2. Replaces the Add To Cart button with a new button with custom text and a custom URL link */
add_action( 'woocommerce_after_shop_loop_item','zpd_replace_wc_add_to_cart_button' );
add_action( 'woocommerce_single_product_summary','zpd_replace_wc_add_to_cart_button' );
function zpd_replace_wc_add_to_cart_button()
{
    global $product;

	$button_text = 'Comprar';
	$link = get_bloginfo( 'url' ) . '/finalizar-compra/?add-to-cart=' . $product->get_id();

	echo '<p class="zpd-wc-reserve-item-button">';
	    echo do_shortcode('<a  href="'.$link.'" class="button addtocartbutton">' . $button_text . '</a>');
	echo '</p>';
}


/* Mostrar descripcion de los productos en la pagina de la tienda. Y la fecha de efecto */
add_action( 'woocommerce_after_shop_loop_item_title', 'woo_show_excerpt_shop_page', 5 );
function woo_show_excerpt_shop_page()
{
    global $product;

    if (!has_term( 'productos', 'product_cat', $product->get_id()))
    {
    ?>
        <div itemprop="description">
            <!-- Mostrar la fecha del efecto -->
            <?php
                echo apply_filters( 'the_content', $product->post->post_content );
                //echo apply_filters( 'woocommerce_short_description', $product->post->post_excerpt );
                $fecha_efecto = date('d-m-y');
                $hora_actual = date("h:i:s");
                $fecha_sig = new DateTime('+1 day');
                $hora_actual = date("h:i:s",strtotime( $fecha_efecto . "+ 1 hours" ) );
                
                if( $hora_actual > '18:45:00')
                {
                    $fecha_efecto = date("d-m-y",strtotime( $fecha_efecto . "+ 1 days" ) );
                    $fecha_sig = new DateTime('+2 day');
                }

                $product_id = get_the_ID();

                switch( $product_id )
                {
                    case 11:
                        ?>
                            <p>Efecto: <?php echo $fecha_efecto ?> - 19:00</p>
                            <a href="#" id="product_id_11" class="button quick-view-button" data-product_id="11">Saber más</a>
                        <?php
                        break;
                    
                    case 13:
                        ?>
                            <p>Efecto: <?php echo $fecha_efecto ?> a <?php echo $fecha_sig->format('d-m-y') ?></p>
                            <a href="#" id="product_id_11" class="button quick-view-button" data-product_id="11">Saber más</a>
                        <?php
                        break;
                    
                    case 14:
                        ?>
                            <p>Efecto: <?php echo $fecha_efecto ?> - 19:00</p>
                            <a href="#" id="product_id_14" class="button quick-view-button" data-product_id="14">Saber más</a>
                        <?php
                        break;
                    
                    default:
                        return 0;	
                }

                echo "<br><p class='gratis'>1ª COMPRA GRATUITA</p>";   
            ?>
        </div>
    <?php
    }
}


/* Ocultar campos del checkout */
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) 
{
    unset($fields['billing']['billing_first_name']);
    unset($fields['billing']['billing_last_name']);
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_1']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_phone']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_last_name']);
    unset($fields['billing']['billing_city']);
    
    unset($fields['order']['order_comments']);
    
    return $fields;
}


/* Eliminar productos desde el checkout */
add_filter( 'woocommerce_cart_item_name', 'lionplugins_woocommerce_checkout_remove_item', 10, 3 );
function lionplugins_woocommerce_checkout_remove_item( $product_name, $cart_item, $cart_item_key ) 
{
	if ( is_checkout() ) {
		$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
		$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

		$remove_link = apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
			'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">×</a>',
			esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
			__( 'Remove this item', 'woocommerce' ),
			esc_attr( $product_id ),
			esc_attr( $_product->get_sku() )
        ), $cart_item_key );

		return '<span>' . $remove_link . '</span> <span>' . $product_name . '</span>';
	}

	return $product_name;
}


/* Redireccionar checkout vacío al ppal */
add_action('template_redirect', 'we_redirection_function');
function we_redirection_function()
{
    global $woocommerce;

    if( is_checkout() && 0 == sprintf(_n('%d', '%d', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count) && !isset($_GET['key']) ) {
        wp_redirect( 'https://emotionpills.com' );
        exit;
    }
}


/* Aplicar cupon automaticamente */
add_action( 'woocommerce_add_to_cart', 'apply_matched_coupons' );
function apply_matched_coupons()
{
    $coupon_code = 'primeracompra';
    
    // If the current user is a shop admin
    if ( current_user_can( 'manage_woocommerce' ) ) return;

    // If the user is on the cart or checkout page
    if ( is_cart() || is_checkout() ) return;

    if ( WC()->cart->has_discount( $coupon_code ) ) return;

    WC()->cart->add_discount( $coupon_code );
}


/* Eliminar cupón de descuento automáticamente si falla si aplicación */
add_filter( 'woocommerce_coupon_error','coupon_error_message_change',10,3 );
function coupon_error_message_change($err, $err_code, $WC_Coupon)
{
    $coupon_code = 'primeracompra';

    WC()->cart->remove_coupon( $coupon_code );

    return $err;
}


/* Disable Link to Products @ Loop */
 remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
 remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );


/* Enviar email al admin por cada registro de cliente */
add_action( 'woocommerce_created_customer', 'woocommerce_created_customer_admin_notification' );
function woocommerce_created_customer_admin_notification( $customer_id ) 
{
    wp_send_new_user_notifications( $customer_id, 'admin' );
}


/* Separar login & registro*/

/* 1. WooCommerce User Registration Shortcode */
add_shortcode( 'wc_reg_form_bbloomer', 'bbloomer_separate_registration_form' );
function bbloomer_separate_registration_form() 
{
    if ( is_admin() ) return;
    if ( is_user_logged_in() ) return;
    ob_start();
  
    do_action( 'woocommerce_before_customer_login_form' );
    ?>
       <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >
          <?php do_action( 'woocommerce_register_form_start' ); ?>
          <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
             <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="reg_username"><?php esc_html_e( 'Username', 'woocommerce' ); ?> <span class="required">*</span></label>
                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
             </p>
  
          <?php endif; ?>
  
          <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">   
             <input type="email" placeholder="Correo electrónico" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
          </p>
  
          <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
             <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <input type="password" placeholder="Contraseña" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
             </p>
          <?php else : ?>
             <p><?php esc_html_e( 'A password will be sent to your email address.', 'woocommerce' ); ?></p>
          <?php endif; ?>
  
          <?php do_action( 'woocommerce_register_form' ); ?>
  
          <p class="woocommerce-FormRow form-row">
             <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
             <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Register', 'woocommerce' ); ?></button>
          </p>
            <?php
                do_action( 'woocommerce_register_form_end' ); 
            ?>
       </form>
    <?php
      
    return ob_get_clean();
 }


 /* 2. WooCommerce User Login Shortcode */
add_shortcode( 'wc_login_form_bbloomer', 'bbloomer_separate_login_form' );  
function bbloomer_separate_login_form() 
{
   if ( is_admin() ) return;
 
   if ( is_user_logged_in() ) return; 
 
   ob_start();
   do_action( 'woocommerce_before_customer_login_form' );
   woocommerce_login_form( array( 'redirect' => '/my-account' ) );
   
   return ob_get_clean();
}


/* Ocultar Secciones en Mi Cuenta Woocommerce */
add_filter( 'woocommerce_account_menu_items', 'hideSectionProfile', 999 );
function hideSectionProfile( $items ) 
{
	unset($items['downloads']);
	unset($items['payment-methods']);
	unset($items['edit-address']);
	unset($items['dashboard']);

    return $items;
}