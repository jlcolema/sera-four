<?php

	// Add theme support for woocommerce
	add_theme_support( 'woocommerce' );

	// Remove WooCommerce enqueued styles
	add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

	// Remove WooCommerce prettyPhoto
	add_action( 'wp_enqueue_scripts', 'keydesign_remove_woo_scripts', 99 );
	function keydesign_remove_woo_scripts() {
	    wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
	    wp_dequeue_script( 'prettyPhoto' );
	    wp_dequeue_script( 'prettyPhoto-init' );
	}

	function keydesign_remove_customizer($wp_customize) {
	  $wp_customize->remove_control('woocommerce_catalog_columns');
	}
	add_action('customize_register', 'keydesign_remove_customizer', 20, 1);

	// Remove WooCommerce breadcrumbs
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

	// Return number of products in shop page
	add_filter( 'loop_shop_per_page', 'keydesign_loop_shop_per_page' );
	function keydesign_loop_shop_per_page( $product_number ) {
		$redux_ThemeTek = get_option( 'redux_ThemeTek' );
    $product_number = $redux_ThemeTek['tek-woo-products-number'];
	  return $product_number;
	}

	function kd_get_gallery_image_html( $attachment_id, $main_image = false ) {
		$flexslider        = (bool) apply_filters( 'woocommerce_single_product_flexslider_enabled', get_theme_support( 'wc-product-gallery-slider' ) );
		$gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
		$thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
		$image_size        = apply_filters( 'woocommerce_gallery_image_size', $flexslider || $main_image ? 'woocommerce_single' : $thumbnail_size );
		$full_size         = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );
		$thumbnail_src     = wp_get_attachment_image_src( $attachment_id, $thumbnail_size );
		$full_src          = wp_get_attachment_image_src( $attachment_id, $full_size );
		$image             = wp_get_attachment_image( $attachment_id, $image_size, false, array(
			'title'                   => get_post_field( 'post_title', $attachment_id ),
			'data-caption'            => get_post_field( 'post_excerpt', $attachment_id ),
			'data-src'                => $full_src[0],
			'data-large_image'        => $full_src[0],
			'data-large_image_width'  => $full_src[1],
			'data-large_image_height' => $full_src[2],
			'class'                   => $main_image ? 'wp-post-image' : '',
		) );

		return '<div data-thumb="' . esc_url( $thumbnail_src[0] ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_src[0] ) . '" data-size="' . esc_attr($full_src[1]) . 'x' . esc_attr($full_src[2]) . '">' . $image . '</a></div>';
	}

	function keydesign_enqueue_woocommerce() {
		wp_enqueue_style( 'keydesign-woocommerce', get_template_directory_uri() . '/core/assets/css/woocommerce.css', array(), null, 'all' );
		wp_register_script( 'keydesign-ajaxcart', get_template_directory_uri() . '/core/assets/js/woocommerce-keydesign.js', array() , null );

		wp_localize_script(
			'keydesign-ajaxcart',
			'keydesign_menucart_ajax',array('nonce' => wp_create_nonce('keydesign-ajaxcart'))
		);

		wp_enqueue_script( 'keydesign-ajaxcart' );
	}
	add_action('wp_enqueue_scripts', 'keydesign_enqueue_woocommerce');

	function keydesign_get_cart_items() {
		global $woocommerce;

		$articles = sizeof( $woocommerce->cart->get_cart() );
		$cart = $items_total = '';

		if (  $articles > 0 ) {
			$items_total = $woocommerce->cart->cart_contents_count;
			foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_name  = apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
					$thumbnail     = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
					$product_price = apply_filters( 'woocommerce_cart_item_price', $woocommerce->cart->get_product_price( $_product ), $cart_item, $cart_item_key );

					$cart .= '<li class="cart-item-list clearfix">';
					if ( ! $_product->is_visible() ) {
						$cart .= str_replace( array( 'http:', 'https:' ), '', $thumbnail );
					} else {
						$cart .= '<a class="cart-thumb" href="'.esc_url(get_permalink( $product_id )).'">
									'.str_replace( array( 'http:', 'https:' ), '', $thumbnail ) . '
								</a>';
					}

					$cart .= '<div class="cart-desc"><a class="cart-item" href="'.esc_url(get_permalink( $product_id )).'">' . $product_name . '</a>';
					$cart .= '<span class="product-quantity">'. apply_filters( 'woocommerce_widget_cart_item_quantity',  '<span class="quantity-container">' . sprintf( '%s &times; %s',$cart_item['quantity'] , '</span>' . $product_price ) , $cart_item, $cart_item_key ) . '</span>';
					$cart .= '</div>';
					$cart .= '</li>';
				}
			}

			$cart .= '<li class="subtotal"><span><strong>' . esc_html__('Subtotal:', 'incubator') . '</strong> ' . $woocommerce->cart->get_cart_total() . '</span></li>';

			$cart .= '<li class="buttons clearfix">
						<a href="'.wc_get_cart_url().'" class="wc-forward btn btn-link"><i class="fa fa-bag"></i>'.esc_html__( 'View Cart', 'incubator' ).'</a>
						<a href="'.wc_get_checkout_url().'" class="checkout wc-forward btn btn-link"><i class="fa fa-square-check"></i>'.esc_html__( 'Checkout', 'incubator' ).'</a>
					  </li>';
		}

		return array('cart' => $cart, 'articles' => $items_total);
	}

	function keydesign_woomenucart_ajax() {
		if ( !wp_verify_nonce( $_REQUEST['nonce'], "keydesign-ajaxcart")) {
				exit();
		}

		$cart = keydesign_get_cart_items();
		echo json_encode($cart);
		die();
	}

	add_action( 'wp_ajax_woomenucart_ajax', 'keydesign_woomenucart_ajax');
	add_action( 'wp_ajax_nopriv_woomenucart_ajax', 'keydesign_woomenucart_ajax' );




	function keydesign_add_cart_in_menu() {
		global $woocommerce;

		$items_total = $woocommerce->cart->cart_contents_count;
		$get_cart_items = keydesign_get_cart_items();

		$cart_container = '<ul role="menu" class="drop-menu cart_list product_list_widget keydesign-cart-dropdown">'.((isset($get_cart_items['cart']) && $get_cart_items['cart'] !=='') ? $get_cart_items['cart'] : '<li><span class="empty-cart">' . esc_html__('Your cart is currently empty','incubator') . '</span></li>').'</ul>';
		$kd_cart_items = '<div class="keydesign-cart menu-item menu-item-has-children dropdown">
					      <a href="'.wc_get_cart_url().'" class="dropdown-toggle" title="cart">
						  <span class="cart-icon-container">';
		$kd_cart_items .= '<i class="fa fa-shopping-cart"></i>';
		$kd_cart_items .= (( $items_total !== 0 ) ? '<span class="badge">'.$items_total.'</span>' : '<span class="badge" style="display: none;"></span>').'</span></a>'.$cart_container.'</div>';

		return $kd_cart_items;
	}

	function keydesign_wc_loop_add_to_cart_scripts() {
    if ( is_shop() || is_product_category() || is_product_tag() || is_product() ) : ?>

	<script>
		jQuery( document ).ready( function( $ ) {
			$( document ).on( 'change', '.quantity .qty', function() {
				$( this ).parent( '.quantity' ).next( '.add_to_cart_button' ).attr( 'data-quantity', $( this ).val() );
			});
		});
	</script>
	    <?php endif;
	}

	add_action( 'wp_footer', 'keydesign_wc_loop_add_to_cart_scripts' );
?>
