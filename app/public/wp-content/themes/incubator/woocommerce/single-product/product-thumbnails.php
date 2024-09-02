<?php
/**
 * Single Product Thumbnails
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.5.1
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'kd_get_gallery_image_html' ) ) {
	return;
}

global $product;

$attachment_ids = $product->get_gallery_image_ids();

if ( $attachment_ids && $product->get_image_id() ) { ?>
	<div class="thumbnails">
		<?php
			foreach ( $attachment_ids as $attachment_id ) {
				echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', kd_get_gallery_image_html( $attachment_id  ), $attachment_id );
			}
		?>
	</div>
<?php } ?>
