<?php
/**
 * The Loop
 *
 * The Loop is PHP code used by WordPress to display posts.
 * Using The Loop, WordPress processes each post to be displayed
 * on the current page, and formats it according to how it matches
 * specified criteria within The Loop tags.
 * Learn more: https://codex.wordpress.org/The_Loop
 *
 * @package incubator
 * by KeyDesign
 */
?>

<?php
   $themetek_parallax_class = $section_style = $css_classes = $wrapper_attributes = $themetek_page_overlay_class = '';

   $redux_ThemeTek = get_option( 'redux_ThemeTek' );
   $themetek_parallax_src = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full', false, '' );
   $themetek_page_bgcolor = get_post_meta( get_the_ID(), '_themetek_page_bgcolor', true );
   $themetek_page_overlay = get_post_meta( get_the_ID(), '_themetek_page_overlay', true );
   $themetek_page_layout = get_post_meta( get_the_ID(), '_themetek_page_layout', true );
   $themetek_page_showhide_title = get_post_meta( get_the_ID(), '_themetek_page_showhide_title', true );
   $themetek_page_subtitle = get_post_meta( get_the_ID(), '_themetek_page_subtitle', true );
   $themetek_page_title_color = get_post_meta( get_the_ID(), '_themetek_page_title_color', true );
   $themetek_page_title_subtitle_color = ' color:'.$themetek_page_title_color.';';
   $themetek_page_top_padding = get_post_meta( get_the_ID(), '_themetek_page_top_padding', true );
   $themetek_page_bottom_padding = get_post_meta( get_the_ID(), '_themetek_page_bottom_padding', true );

   if ( !empty($themetek_parallax_src[0])) {
     $themetek_parallax_class = 'parallax';
   }

   if ( !empty($themetek_page_overlay) ) {
     $themetek_page_overlay_class = 'with-overlay';
   }

   $css_classes = implode(' ', array( 'section', $themetek_parallax_class, $themetek_page_overlay_class ));

   if ( '' !== $themetek_page_bgcolor ) {
     $section_style .= 'background-color:' .$themetek_page_bgcolor .';';
   }
   if ( '' !== $themetek_page_top_padding ) {
     $section_style .= 'padding-top:' . ( preg_match( '/(px|em|\%|pt|cm)$/', $themetek_page_top_padding ) ? $themetek_page_top_padding : $themetek_page_top_padding . 'px' ) . ';';
   }
   if ( '' !== $themetek_page_bottom_padding ) {
     $section_style .= 'padding-bottom:' . ( preg_match( '/(px|em|\%|pt|cm)$/', $themetek_page_bottom_padding ) ? $themetek_page_bottom_padding : $themetek_page_bottom_padding . 'px' ) . ';';
   }
?>

<section id="<?php echo esc_attr($post->post_name);?>" class="<?php echo esc_attr( trim( $css_classes ) ); ?>" <?php echo ('' !== $section_style) ? 'style="'. esc_attr($section_style).'"' : ''; ?>>
   <?php  if( !empty($themetek_parallax_src[0])) : ?>
     <div class="parallax-overlay" style="background-image:url('<?php echo esc_url($themetek_parallax_src[0]); ?>')"></div>
   <?php endif; ?>
   <div class="container <?php echo ( !empty($themetek_page_layout) ? 'fullwidth' : '' );?>" >
      <div class="row">
        <?php if (empty($themetek_page_showhide_title)) : ?>
          <h2 class="section-heading" <?php if (!empty($themetek_page_title_color)) : ?> style="<?php echo esc_html($themetek_page_title_subtitle_color); ?>"<?php endif; ?>><?php the_title(); ?></h2>
        <?php endif; ?>
        <?php if (!empty($themetek_page_subtitle)) : ?>
          <p class="section-subheading" <?php if (!empty($themetek_page_title_color)) : ?> style="<?php echo esc_html($themetek_page_title_subtitle_color); ?>"<?php endif; ?>><?php echo esc_html($themetek_page_subtitle); ?></p>
        <?php endif; ?>
      </div>
      <div class="row">
         <?php the_content(); ?>
      </div>
   </div>
</section>
