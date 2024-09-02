<?php
/**
* The template for displaying all pages. *
* This is the template that displays all pages by default.
* Please note that this is the WordPress construct of pages
* and that other 'pages' on your WordPress site will use a
* different template. *
* @package incubator
* by KeyDesign
*/
?>

<?php
  $section_style = $css_classes = $page_headerimg_class = '';

  $redux_ThemeTek = get_option( 'redux_ThemeTek' );
  $themetek_page_subtitle = get_post_meta( get_the_ID(), '_themetek_page_subtitle', true );
  $themetek_page_showhide_title = get_post_meta( get_the_ID(), '_themetek_page_showhide_title', true );
  $themetek_page_bgcolor = get_post_meta( get_the_ID(), '_themetek_page_bgcolor', true );
  $themetek_page_title_color = get_post_meta( get_the_ID(), '_themetek_page_title_color', true );
  $themetek_page_title_subtitle_color = ' color:'.$themetek_page_title_color.';';
  $themetek_page_layout = get_post_meta( get_the_ID(), '_themetek_page_layout', true );
  $themetek_page_top_padding = get_post_meta( get_the_ID(), '_themetek_page_top_padding', true );
  $themetek_page_bottom_padding = get_post_meta( get_the_ID(), '_themetek_page_bottom_padding', true );
  $themetek_post_id = get_the_ID();
  $keydesign_header_image = wp_get_attachment_image_src( get_post_thumbnail_id($themetek_post_id), 'full', false );

  if (!$keydesign_header_image[0]) {
    $page_headerimg_class = "no-header-image";
  }

  $css_classes = implode(' ', array( 'section', $page_headerimg_class, $post->post_name ));

  if ( '' !== $themetek_page_bgcolor ) {
    $section_style .= 'background-color:' .$themetek_page_bgcolor .';';
  }
  if ( '' !== $themetek_page_top_padding ) {
    $section_style .= 'padding-top:' . ( preg_match( '/(px|em|\%|pt|cm)$/', $themetek_page_top_padding ) ? $themetek_page_top_padding : $themetek_page_top_padding . 'px' ) . ';';
  }
  if ( '' !== $themetek_page_bottom_padding ) {
    $section_style .= 'padding-bottom:' . ( preg_match( '/(px|em|\%|pt|cm)$/', $themetek_page_bottom_padding ) ? $themetek_page_bottom_padding : $themetek_page_bottom_padding . 'px' ) . ';';
  }

  get_header();
?>

  <section id="single-page" class="<?php echo esc_attr( trim( $css_classes ) ); ?>" <?php echo ('' !== $section_style) ? 'style="'. esc_attr($section_style).'"' : ''; ?>>
   <?php if (empty($themetek_page_showhide_title)) { ?>
   <div class="row single-page-heading">
     <?php if ($keydesign_header_image[0]) : ?>
       <div class="header-overlay parallax-overlay" style="background-image:url('<?php echo esc_url($keydesign_header_image[0]); ?>')"></div>
     <?php endif; ?>
    <div class="container <?php echo ( !empty($themetek_page_layout) ? 'fullwidth' : '' );?>">
        <?php if(function_exists('bcn_display')) : ?>
          <div class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
            <?php bcn_display(); ?>
          </div>
        <?php endif; ?>
        <?php if (empty($themetek_page_showhide_title)) : ?>
          <h1 class="section-heading" <?php if (!empty($themetek_page_title_color)) : ?> style="<?php echo esc_html($themetek_page_title_subtitle_color); ?>"<?php endif; ?>><?php the_title(); ?></h1>
        <?php endif; ?>
        <?php if (!empty($themetek_page_subtitle)) : ?>
          <p class="section-subheading" <?php if (!empty($themetek_page_title_color)) : ?> style="<?php echo esc_html($themetek_page_title_subtitle_color); ?>"<?php endif; ?>><?php echo esc_html($themetek_page_subtitle); ?></p>
        <?php endif; ?>
      </div>
    </div>
   <?php } ?>
    <div class="container <?php echo ( !empty($themetek_page_layout) ? 'fullwidth' : '' );?>">
      <div class="row single-page-content">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
          <?php the_content(); ?>
          <?php wp_link_pages(
						array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'incubator' ),
							'after'  => '</div>',
						)
					); ?>
          <?php
              if ( comments_open() || '0' != get_comments_number() ) { ?>
              <div class="page-content comments-content container">
                <?php comments_template(); ?>
              </div>
             <?php }
          ?>
          <?php endwhile; else: ?>
          <p><?php esc_html_e('Sorry, this page does not exist.', 'incubator'); ?></p>
        <?php endif; ?>
      </div>
    </div>
  </section>

<?php get_footer();?>
