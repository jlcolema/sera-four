<?php
/**
 * The template for displaying Search Results pages.
 * @package incubator
 * by KeyDesign
 */

 get_header(); ?>

 <?php
 	$redux_ThemeTek = get_option( 'redux_ThemeTek' );

   if (!isset($redux_ThemeTek['tek-blog-sidebar'])) {
 		$redux_ThemeTek['tek-blog-sidebar'] = 0;
 	}
 ?>

<div id="posts-content" class="container">
  <?php if (($redux_ThemeTek['tek-blog-sidebar'])) : ?>
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
  <?php else : ?>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
  <?php endif; ?>
    	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
				<h3 class="blog-single-title"><a href="<?php esc_url(the_permalink()); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
				<?php if ( 'post' === get_post_type() ) : ?>
					<div class="entry-meta">
						<span class="page-type"><span class="fa fa-file-text-o"></span><?php _e( 'Post', 'incubator' ); ?></span>
						<span class="published"><span class="fa fa-clock-o"></span><a href="<?php esc_url(the_permalink()); ?>" title="<?php the_title_attribute(); ?>"><?php  the_time( get_option('date_format') ); ?></a></span>
						<span class="author"><span class="fa fa-keyboard-o"></span><?php  the_author_posts_link(); ?></span>
						<span class="blog-label"><span class="fa fa-folder-open-o"></span><?php  the_category(', '); ?></span>
						<span class="comment-count"><span class="fa fa-comment-o"></span><?php  comments_popup_link( esc_html__('No comments yet', 'incubator'), esc_html__('1 comment', 'incubator'), esc_html__('% comments', 'incubator') ); ?></span>
					</div>
				<?php else : ?>
					<div class="entry-meta">
						<?php if ( 'page' === get_post_type() ) : ?>
							<span class="page-type"><span class="fa fa-file-text-o"></span><?php _e( 'Page', 'incubator' ); ?></span>
						<?php elseif ( 'portfolio' === get_post_type() ) : ?>
							<span class="page-type"><span class="fa fa-file-image-o"></span><?php _e( 'Portfolio', 'incubator' ); ?></span>
						<?php elseif ( 'product' === get_post_type() ) : ?>
							<span class="page-type"><span class="fa fa-shopping-cart"></span><?php _e( 'Product', 'incubator' ); ?></span>
						<?php endif; ?>
						<span class="published"><span class="fa fa-clock-o"></span><a href="<?php esc_url(the_permalink()); ?>" title="<?php the_title_attribute(); ?>"><?php  the_time( get_option('date_format') ); ?></a></span>
					</div>
				<?php endif; ?>
			</article>

		<?php endwhile; ?>
		<?php the_posts_pagination( array(
			'mid_size' => 1,
			'prev_text' => __( 'Previous', 'incubator' ),
			'next_text' => __( 'Next', 'incubator' ),
		) ); ?>
		<?php else : ?>
			<section id="no-posts-found">
				<div class="row" >
					<h2 class="section-title"><?php esc_html_e( 'Nothing Found', 'incubator' ); ?></h2>
					<?php
					if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
						<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'incubator' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>
					<?php elseif ( is_search() ) : ?>
						<p class="section-subheading"><?php _e( 'Sorry, but nothing matched your search terms. Please try again using different keywords.', 'incubator' ); ?></p>
						<?php get_search_form();
					else : ?>
						<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'incubator' ); ?></p>
						<?php get_search_form();
					endif; ?>
				</div>
			</section>
		<?php endif; ?>
    </div>
    <?php if (($redux_ThemeTek['tek-blog-sidebar'])) : ?>
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
			<?php get_sidebar(); ?>
		</div>
  	<?php endif; ?>
</div>

<?php get_footer(); ?>
