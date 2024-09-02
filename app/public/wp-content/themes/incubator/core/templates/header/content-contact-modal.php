<?php
  $redux_ThemeTek = get_option( 'redux_ThemeTek' );
?>
<div class="modal fade popup-modal" id="popup-modal" role="dialog">
  <div class="modal-content">
      <div class="row">
        <?php if (isset($redux_ThemeTek['tek-modal-title']) && $redux_ThemeTek['tek-modal-title'] != '' ) : ?>
            <h2><?php echo esc_attr($redux_ThemeTek['tek-modal-title']); ?></h2>
        <?php endif; ?>
            <div class="modal-content-inner">
              <?php if (isset($redux_ThemeTek['tek-modal-content']) && $redux_ThemeTek['tek-modal-content'] != '' ) : ?>
                  <p class="modal-subheading"><?php echo esc_attr($redux_ThemeTek['tek-modal-content']); ?></p>
              <?php endif; ?>
              <?php if (isset($redux_ThemeTek['tek-modal-form-select']) && $redux_ThemeTek['tek-modal-form-select'] != '' ) : ?>
                   <?php if ($redux_ThemeTek['tek-modal-form-select'] == '1' && $redux_ThemeTek['tek-modal-contactf7-formid'] != '') : ?>
                        <?php echo do_shortcode('[contact-form-7 id="'. esc_attr($redux_ThemeTek['tek-modal-contactf7-formid']).'"]'); ?>
                   <?php elseif ($redux_ThemeTek['tek-modal-form-select'] == '2' && $redux_ThemeTek['tek-modal-ninja-formid'] != '') : ?>
                        <?php echo do_shortcode('[ninja_form id="'. esc_attr($redux_ThemeTek['tek-modal-ninja-formid']).'"]'); ?>
                   <?php elseif ($redux_ThemeTek['tek-modal-form-select'] == '3' && $redux_ThemeTek['tek-modal-gravity-formid'] != '') : ?>
                        <?php echo do_shortcode('[gravityform id="'. esc_attr($redux_ThemeTek['tek-modal-gravity-formid']).'"]'); ?>
                   <?php elseif ($redux_ThemeTek['tek-modal-form-select'] == '4' && $redux_ThemeTek['tek-modal-wp-formid'] != '') : ?>
                        <?php echo do_shortcode('[wpforms id="'. esc_attr($redux_ThemeTek['tek-modal-wp-formid']).'"]'); ?>
                   <?php endif; ?>
              <?php endif; ?>
            </div>
      </div>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
  </div>
</div>
