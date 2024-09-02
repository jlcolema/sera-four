<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$redux_ThemeTek = get_option( 'redux_ThemeTek' );

$shop_sidebar = $shop_active_widgets = $shop_cols_class = '';

if (isset($redux_ThemeTek['tek-woo-sidebar-position'])) {
	$shop_sidebar = $redux_ThemeTek['tek-woo-sidebar-position'];
}

$shop_active_widgets = is_active_sidebar( 'shop-sidebar' );

if ($shop_active_widgets) {
		$shop_cols_class = "col-xs-12 col-sm-12 col-md-9 col-lg-9";
} else {
		$shop_cols_class = "col-xs-12 col-sm-12 col-md-12 col-lg-12";
}

get_header( 'shop' ); ?>

<section class="container">
	<?php if ($shop_active_widgets) : ?>
		<?php if ($shop_sidebar == 'woo-sidebar-left') : ?>
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<div class="woo-sidebar woo-sidebar-left">
					<?php dynamic_sidebar('shop-sidebar'); ?>
				</div>
			</div>
		<?php endif; ?>
	<?php endif; ?>
  <div class="<?php echo esc_html($shop_cols_class); ?>">
		<?php if ( have_posts() ) : ?>
		<div class="ShopFiltersWrapper">
			<?php
				/**
				 * woocommerce_before_shop_loop hook.
				 *
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );
			?>
		</div>

			<?php woocommerce_product_loop_start(); ?>
				<?php woocommerce_product_subcategories(); ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php wc_get_template_part( 'content', 'product' ); ?>
				<?php endwhile; // end of the loop. ?>
			<?php woocommerce_product_loop_end(); ?>
			<?php
				/**
				 * woocommerce_after_shop_loop hook.
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			?>
		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>
			<?php wc_get_template( 'loop/no-products-found.php' ); ?>
		<?php endif; ?>
	</div>

	<?php if ($shop_active_widgets) : ?>
		<?php if ($shop_sidebar == 'woo-sidebar-right') : ?>
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<div class="woo-sidebar woo-sidebar-right">
					<?php dynamic_sidebar('shop-sidebar'); ?>
				</div>
			</div>
		<?php endif; ?>
	<?php endif; ?>

</section>
<?php get_footer( 'shop' ); ?>
