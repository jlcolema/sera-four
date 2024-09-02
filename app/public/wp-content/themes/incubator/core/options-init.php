<?php
if (!class_exists('keydesign_Redux_Framework_config')) {
    class keydesign_Redux_Framework_config {
        public $args = array();
        public $sections = array();
        public $theme;
        public $ReduxFramework;
        public function __construct() {
            if (!class_exists('ReduxFramework')) {
                return;
            }
            // This is needed. Bah WordPress bugs.  ;)
            if (true == Redux_Helpers::isTheme(__FILE__)) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array(
                    $this,
                    'initSettings'
                ), 10);
            }
        }
        public function initSettings() {
            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();
            // Set the default arguments
            $this->setArguments();
            // Set a few help tabs so you can see how it's done
            $this->setSections();
            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }
            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }
        /**
        * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
        * Simply include this function in the child themes functions.php file.

        * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
        * so you must use get_template_directory_uri() if you want to use any of the built in icons
        * */
        function dynamic_section($sections) {
            //$sections = array();
            $sections[] = array(
                'title' => esc_html__('Section via hook', 'incubator'),
                'desc' => esc_html__('This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.', 'incubator'),
                'icon' => 'el-icon-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );
            return $sections;
        }
        /**
        * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
        * */
        function change_arguments($args) {
            return $args;
        }
        /**
        * Filter hook for filtering the default value of any given field. Very useful in development mode.
        * */
        function change_defaults($defaults) {
            $defaults['str_replace'] = 'Testing filter hook!';
            return $defaults;
        }

        public function setSections() {
            /**
            * Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
            * */
            // Background Patterns Reader
            $sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
            $sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
            $sample_patterns      = array();
            if (is_dir($sample_patterns_path)):
                if ($sample_patterns_dir = opendir($sample_patterns_path)):
                    $sample_patterns = array();
                    while (($sample_patterns_file = readdir($sample_patterns_dir)) !== false) {
                        if (stristr($sample_patterns_file, '.png') !== false || stristr($sample_patterns_file, '.jpg') !== false) {
                            $name              = explode('.', $sample_patterns_file);
                            $name              = str_replace('.' . end($name), '', $sample_patterns_file);
                            $sample_patterns[] = array(
                                'alt' => $name,
                                'img' => $sample_patterns_url . $sample_patterns_file
                            );
                        }
                    }
                endif;
            endif;
            ob_start();
            $ct              = wp_get_theme();
            $this->theme     = $ct;
            $item_name       = $this->theme->get('Name');
            $tags            = $this->theme->Tags;
            $screenshot      = $this->theme->get_screenshot();
            $class           = $screenshot ? 'has-screenshot' : '';
            $customize_title = sprintf(esc_html__('Customize &#8220;%s&#8221;', 'incubator'), $this->theme->display('Name'));
            ?>
    <div id="current-theme" class="<?php echo esc_attr($class); ?>">
      <?php if ($screenshot): ?>
        <?php if (current_user_can('edit_theme_options')): ?>
          <a href="<?php echo esc_url(wp_customize_url()); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
            <img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview','incubator'); ?>" />
          </a>
        <?php endif; ?>
        <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview','incubator'); ?>" />
      <?php endif; ?>
      <h4><?php echo esc_html($this->theme->display('Name')); ?></h4>
      <div>
        <ul class="theme-info">
          <li><?php printf(esc_html__('By %s', 'incubator'), $this->theme->display('Author')); ?></li>
          <li><?php printf(esc_html__('Version %s', 'incubator'), $this->theme->display('Version')); ?></li>
          <li><?php echo '<strong>' . esc_html__('Tags', 'incubator') . ':</strong>'; ?> <?php printf($this->theme->display('Tags')); ?></li>
        </ul>
        <p class="theme-description"><?php echo esc_attr($this->theme->display('Description')); ?></p>
      </div>
    </div>

<?php
            $item_info = ob_get_contents();
            ob_end_clean();
            $sampleHTML = '';
            // ACTUAL DECLARATION OF SECTIONS
            $this->sections[] = array(
                'icon' => 'el-icon-globe',
                'title' => esc_html__('Global Options', 'incubator'),
                'fields' => array(
                    array(
                        'id' => 'tek-main-color',
                        'type' => 'color',
                        'transparent' => false,
                        'title' => esc_html__('Main Theme Color', 'incubator'),
                        'default' => '#0030b8',
                        'validate' => 'color'
                    ),
                    array(
                        'id' => 'tek-preloader',
                        'type' => 'switch',
                        'title' => esc_html__('Preloader', 'incubator'),
                        'subtitle' => esc_html__('Enabling this option will add a preloading screen with a nice transition.', 'incubator'),
                        'default' => true
                    ),
                    array(
                        'id' => 'tek-google-api',
                        'type' => 'text',
                        'title' => esc_html__('Google Map API Key', 'incubator'),
                        'default' => '',
                        'subtitle' => esc_html__('Generate, copy and paste here Google Maps API Key', 'incubator'),
                    ),
                    array(
                        'id' => 'tek-disable-animations',
                        'type' => 'switch',
                        'title' => esc_html__('Disable Animations on Mobile', 'incubator'),
                        'subtitle' => esc_html__('Globally turn on/off animations on mobile devices.', 'incubator'),
                        'default' => false
                    ),
                )
            );

            $this->sections[] = array(
                'icon' => 'el-icon-star',
                'title' => esc_html__('Logo', 'incubator'),
                'fields' => array(
                  array(
                      'id' => 'tek-logo',
                      'type' => 'media',
                      'readonly' => false,
                      'url' => true,
                      'title' => esc_html__('Primary Logo', 'incubator'),
                      'subtitle' => esc_html__('Upload logo image', 'incubator'),
                      'default' => array(
                          'url' => get_template_directory_uri() . '/images/logo.png'
                      )
                  ),
                  array(
                      'id' => 'tek-logo2',
                      'type' => 'media',
                      'readonly' => false,
                      'url' => true,
                      'title' => esc_html__('Secondary Logo', 'incubator'),
                      'subtitle' => esc_html__('Upload logo image for sticky navbar', 'incubator'),
                      'default' => array(
                          'url' => get_template_directory_uri() . '/images/logo-2.png'
                      )
                  ),
                  array(
                      'id' => 'tek-logo-size',
                      'type' => 'dimensions',
                      'height' => false,
                      'units' => false,
                      'url' => true,
                      'title' => esc_html__('Logo Size', 'incubator'),
                      'subtitle' => esc_html__('Choose logo width - in pixels', 'incubator')
                  ),
                  array(
                      'id' => 'tek-favicon',
                      'type' => 'media',
                      'readonly' => false,
                      'preview' => false,
                      'url' => true,
                      'title' => esc_html__('Favicon', 'incubator'),
                      'subtitle' => esc_html__('Upload favicon image', 'incubator'),
                      'default' => array(
                          'url' => get_template_directory_uri() . '/images/favicon.png'
                      )
                  ),
                )
            );

            $this->sections[] = array(
                'icon' => 'el-icon-lines',
                'title' => esc_html__('Header', 'incubator'),
                'fields' => array(
                    array(
                        'id'=>'tek-header-bar-section-start',
                        'type' => 'section',
                        'title' => esc_html__('Header Bar Settings', 'incubator'),
                        'indent' => true,
                    ),
                    array(
                        'id' => 'tek-menu-style',
                        'type' => 'button_set',
                        'title' => esc_html__('Header Bar Width', 'incubator'),
                        'subtitle' => esc_html__('You can choose between full width and contained.', 'incubator'),
                        'options' => array(
                            '1' => 'Full width',
                            '2' => 'Contained'
                         ),
                        'default' => '1'
                    ),
                    array(
                        'id' => 'tek-menu-behaviour',
                        'type' => 'button_set',
                        'title' => esc_html__('Header Bar Behaviour', 'incubator'),
                        'subtitle' => esc_html__('You can choose between a sticky or a fixed top menu.', 'incubator'),
                        'options' => array(
                            '1' => 'Sticky',
                            '2' => 'Fixed'
                         ),
                        'default' => '1'
                    ),
                    array(
                        'id' => 'tek-search-bar',
                        'type' => 'switch',
                        'title' => esc_html__('Search Bar', 'incubator'),
                        'subtitle' => esc_html__('Turn on to display search bar.', 'incubator'),
                        'default' => true
                    ),
                    array(
                        'id' => 'tek-header-menu-bg',
                        'type' => 'color',
                        'title' => esc_html__('Header Bar Background Color', 'incubator'),
                        'default' => '',
                        'validate' => 'color'
                    ),
                    array(
                        'id' => 'tek-header-menu-bg-sticky',
                        'type' => 'color',
                        'transparent' => false,
                        'title' => esc_html__('Sticky Header Bar Background Color', 'incubator'),
                        'default' => '',
                        'validate' => 'color'
                    ),
                    array(
                      	'id'=>'tek-header-bar-section-end',
                      	'type' => 'section',
                      	'indent' => false,
                    ),
                    array(
                      	'id'=>'tek-menu-settings-section-start',
                      	'type' => 'section',
                      	'title' => esc_html__('Main Menu Settings', 'incubator'),
                      	'indent' => true,
                    ),
                    array(
                        'id' => 'tek-menu-typo',
                        'type' => 'typography',
                        'title' => esc_html__('Menu font settings', 'incubator'),
                        'google' => true,
                        'font-style' => true,
                        'font-size' => true,
                        'line-height' => false,
                        'color' => false,
                        'text-transform' => true,
                        'text-align' => false,
                        'preview' => true,
                        'all_styles' => false,
                        'units' => 'px',
                        'preview' => array(
                            'text' => 'Menu Item'
                        )
                    ),
                    array(
                        'id' => 'tek-header-menu-color',
                        'type' => 'color',
                        'transparent' => false,
                        'title' => esc_html__('Menu Link Color', 'incubator'),
                        'default' => '',
                        'validate' => 'color'
                    ),
                    array(
                        'id' => 'tek-header-menu-color-hover',
                        'type' => 'color',
                        'transparent' => false,
                        'title' => esc_html__('Menu Link Hover Color', 'incubator'),
                        'default' => '',
                        'validate' => 'color'
                    ),
                    array(
                        'id' => 'tek-header-menu-color-sticky',
                        'type' => 'color',
                        'transparent' => false,
                        'title' => esc_html__('Sticky Menu Link Color', 'incubator'),
                        'default' => '',
                        'validate' => 'color'
                    ),
                    array(
                        'id' => 'tek-header-menu-color-sticky-hover',
                        'type' => 'color',
                        'transparent' => false,
                        'title' => esc_html__('Sticky Menu Link Hover Color', 'incubator'),
                        'default' => '',
                        'validate' => 'color'
                    ),
                    array(
                      	'id'=>'tek-menu-settings-section-end',
                      	'type' => 'section',
                      	'indent' => false,
                    ),
                )
            );

            $this->sections[] = array(
                'title' => esc_html__('Header Button', 'incubator'),
                'subsection' => true,
                'fields' => array(
                    array(
                        'id' => 'tek-header-button',
                        'type' => 'switch',
                        'title' => esc_html__('Enable Header Button', 'incubator'),
                        'default' => false
                    ),
                    array(
                        'id' => 'tek-header-button-text',
                        'type' => 'text',
                        'title' => esc_html__('Button Text', 'incubator'),
                        'required' => array('tek-header-button','equals', true),
                        'default' => 'Let`s Talk'
                    ),
                    array(
                        'id' => 'tek-header-button-action',
                        'type' => 'select',
                        'title' => esc_html__('Button Action', 'incubator'),
                        'select2' => array('allowClear' => false, 'minimumResultsForSearch' => '-1'),
                        'required' => array('tek-header-button','equals', true),
                        'options'  => array(
                            '1' => 'Open modal window with contact form',
                            '2' => 'Scroll to section',
                            '3' => 'Open a new page'
                        ),
                        'default' => '1'
                    ),
                    array(
                        'id' => 'tek-modal-title',
                        'type' => 'text',
                        'title' => esc_html__('Modal Title', 'incubator'),
                        'required' => array('tek-header-button-action','equals','1'),
                        'default' => 'Just ask. Get answers.'
                    ),
                    array(
                        'id' => 'tek-modal-content',
                        'type' => 'text',
                        'title' => esc_html__('Modal Subtitle', 'incubator'),
                        'required' => array('tek-header-button-action','equals','1'),
                        'default' => 'Your questions and comments are important to us.'
                    ),
                    array(
                        'id' => 'tek-modal-form-select',
                        'type' => 'select',
                        'title' => esc_html__('Contact Form Plugin', 'incubator'),
                        'select2' => array('allowClear' => false, 'minimumResultsForSearch' => '-1'),
                        'required' => array('tek-header-button-action','equals','1'),
                        'options'  => array(
                            '1' => 'Contact Form 7',
                            '2' => 'Ninja Forms',
                            '3' => 'Gravity Forms',
                            '4' => 'WP Forms',
                        ),
                        'default' => '1'
                    ),
                    array(
                        'id' => 'tek-modal-contactf7-formid',
                        'type' => 'select',
                        'data' => 'posts',
                        'args' => array( 'post_type' => 'wpcf7_contact_form', ),
                        'title' => esc_html__('Contact Form 7 Title', 'incubator'),
                        'required' => array('tek-modal-form-select','equals','1'),
                        'default' => ''
                    ),
                    array(
                        'id' => 'tek-modal-ninja-formid',
                        'type' => 'text',
                        'title' => esc_html__('Ninja Form ID', 'incubator'),
                        'required' => array('tek-modal-form-select','equals','2'),
                        'default' => ''
                    ),
                    array(
                        'id' => 'tek-modal-gravity-formid',
                        'type' => 'text',
                        'title' => esc_html__('Gravity Form ID', 'incubator'),
                        'required' => array('tek-modal-form-select','equals','3'),
                        'default' => ''
                    ),
                    array(
                        'id' => 'tek-modal-wp-formid',
                        'type' => 'text',
                        'title' => esc_html__('WP Form ID', 'incubator'),
                        'required' => array('tek-modal-form-select','equals','4'),
                        'default' => ''
                    ),
                    array(
                        'id' => 'tek-scroll-id',
                        'type' => 'text',
                        'title' => esc_html__('Scroll to Section ID', 'incubator'),
                        'required' => array('tek-header-button-action','equals','2'),
                        'default' => '#download-incubator'
                    ),

                    array(
                        'id' => 'tek-button-new-page',
                        'type' => 'text',
                        'title' => esc_html__('Button Link', 'incubator'),
                        'required' => array('tek-header-button-action','equals','3'),
                        'default' => ''
                    ),

                    array(
                        'id' => 'tek-button-target',
                        'type' => 'select',
                        'title' => esc_html__('Link Target', 'incubator'),
                        'required' => array('tek-header-button-action','equals','3'),
                        'options'  => array(
                            'new-page' => 'Open in a new page',
                            'same-page' => 'Open in same page'
                        ),
                        'default' => 'new-page'
                    ),
                )
            );

            $this->sections[] = array(
                'icon' => 'el-icon-website',
                'title' => esc_html__('Home Slider', 'incubator'),
                'compiler' => 'true',
                'fields' => array(
                    array(
                        'id' => 'tek-slider',
                        'type' => 'text',
                        'title' => esc_html__('Revolution Slider Alias Name', 'incubator'),
                        'subtitle' => esc_html__('Insert Revolution Slider alias here', 'incubator'),
                        'default' => ''
                    ),
                    array(
                        'id' => 'tek-slider-scroll',
                        'type' => 'switch',
                        'title' => esc_html__('Scroll Down Button', 'incubator'),
                        'subtitle' => esc_html__('Enabling this option will display a nice down arrow under the slider with a smooth scroll effect.', 'incubator'),
                        'default' => true
                    ),
                )
            );

            $this->sections[] = array(
                'icon' => 'el-icon-thumbs-up',
                'title' => esc_html__('Footer', 'incubator'),
                'fields' => array(

                    array(
                        'id' => 'tek-footer-fixed',
                        'type' => 'switch',
                        'title' => esc_html__('Fixed Footer', 'incubator'),
                        'subtitle' => esc_html__('Enabling this option will set the Footer in a fixed position.', 'incubator'),
                        'default' => true
                    ),
                    array(
                        'id' => 'tek-backtotop',
                        'type' => 'switch',
                        'title' => esc_html__('Enable Go to Top Button', 'incubator'),
                        'subtitle' => esc_html__('Enabling this option will display a "back to top" button at the bottom right corner of every page.', 'incubator'),
                        'default' => true
                    ),
                    array(
                        'id' => 'tek-upper-footer-color',
                        'type' => 'color',
                        'transparent' => false,
                        'title' => esc_html__('Upper Footer Background', 'incubator'),
                        'default' => '#fafafa',
                        'validate' => 'color'
                    ),
                    array(
                        'id' => 'tek-lower-footer-color',
                        'type' => 'color',
                        'transparent' => false,
                        'title' => esc_html__('Lower Footer Background', 'incubator'),
                        'default' => '#fff',
                        'validate' => 'color'
                    ),
                    array(
                        'id' => 'tek-footer-heading-color',
                        'type' => 'color',
                        'transparent' => false,
                        'title' => esc_html__('Footer Headings Color', 'incubator'),
                        'default' => '#666',
                        'validate' => 'color'
                    ),
                    array(
                        'id' => 'tek-footer-text-color',
                        'type' => 'color',
                        'transparent' => false,
                        'title' => esc_html__('Footer Text Color', 'incubator'),
                        'default' => '#666',
                        'validate' => 'color'
                    ),
                    array(
                        'id' => 'tek-footer-text',
                        'type' => 'text',
                        'title' => esc_html__('Copyright Text', 'incubator'),
                        'subtitle' => esc_html__('Enter footer bottom copyright text', 'incubator'),
                        'default' => 'Incubator by KeyDesign. All rights reserved.'
                    ),
                    array(
                        'id' => 'tek-social-icons',
                        'type' => 'checkbox',
                        'title' => esc_html__('Social Icons', 'incubator'),
                        'subtitle' => esc_html__('Select visible social icons', 'incubator'),
                        'options' => array(
                            '1' => 'Facebook',
                            '2' => 'Twitter',
                            '3' => 'Google+',
                            '4' => 'Pinterest',
                            '5' => 'Youtube',
                            '6' => 'Linkedin',
                            '7' => 'Instagram',
                            '8' => 'Xing'
                        ),
                        'default' => array(
                            '1' => '1',
                            '2' => '1',
                            '3' => '1',
                            '4' => '0',
                            '5' => '0',
                            '6' => '1',
                            '7' => '0',
                        )
                    ),
                    array(
                        'id' => 'tek-facebook-url',
                        'type' => 'text',
                        'title' => esc_html__('Facebook Link', 'incubator'),
                        'subtitle' => esc_html__('Enter Facebook URL', 'incubator'),
                        'validate' => 'url',
                        'default' => 'https://www.facebook.com/'
                    ),

                    array(
                        'id' => 'tek-twitter-url',
                        'type' => 'text',
                        'title' => esc_html__('Twitter Link', 'incubator'),
                        'subtitle' => esc_html__('Enter Twitter URL', 'incubator'),
                        'validate' => 'url',
                        'default' => 'https://www.twitter.com/'
                    ),

                    array(
                        'id' => 'tek-google-url',
                        'type' => 'text',
                        'title' => esc_html__('Google+ Link', 'incubator'),
                        'subtitle' => esc_html__('Enter Google+ URL', 'incubator'),
                        'default' => 'https://plus.google.com/'
                    ),
                    array(
                        'id' => 'tek-pinterest-url',
                        'type' => 'text',
                        'title' => esc_html__('Pinterest Link', 'incubator'),
                        'subtitle' => esc_html__('Enter Pinterest URL', 'incubator'),
                        'validate' => 'url',
                        'default' => 'https://www.pinterest.com/'
                    ),

                    array(
                        'id' => 'tek-youtube-url',
                        'type' => 'text',
                        'title' => esc_html__('Youtube Link', 'incubator'),
                        'subtitle' => esc_html__('Enter Youtube URL', 'incubator'),
                        'validate' => 'url',
                        'default' => 'https://www.youtube.com/'
                    ),
                    array(
                        'id' => 'tek-linkedin-url',
                        'type' => 'text',
                        'title' => esc_html__('Linkedin Link', 'incubator'),
                        'subtitle' => esc_html__('Enter Linkedin URL', 'incubator'),
                        'validate' => 'url',
                        'default' => 'https://www.linkedin.com/'
                    ),
                    array(
                        'id' => 'tek-instagram-url',
                        'type' => 'text',
                        'title' => esc_html__('Instagram Link', 'incubator'),
                        'subtitle' => esc_html__('Enter Instagram URL', 'incubator'),
                        'validate' => 'url',
                        'default' => 'https://www.instagram.com/'
                    ),
                    array(
                        'id' => 'tek-xing-url',
                        'type' => 'text',
                        'title' => esc_html__('Xing Link', 'incubator'),
                        'subtitle' => esc_html__('Enter Xing URL', 'incubator'),
                        'validate' => 'url',
                        'default' => 'https://www.xing.com/'
                    ),

                )
            );

            $this->sections[] = array(
                'icon' => 'el-icon-fontsize',
                'title' => esc_html__('Typography', 'incubator'),
                'compiler' => true,
                'fields' => array(
                    array(
                        'id' => 'tek-default-typo',
                        'type' => 'typography',
                        'title' => esc_html__('Body Typography', 'incubator'),
                        'google' => true,
                        'font-style' => true,
                        'font-size' => true,
                        'line-height' => true,
                        'color' => true,
                        'text-align' => true,
                        'preview' => true,
                        'all_styles' => true,
                        'units' => 'px',
                        'default' => array(
                            'color' => '#666',
                            'font-weight' => '400',
                            'font-family' => 'Open Sans',
                            'google' => true,
                            'font-size' => '16px',
                            'text-align' => 'left',
                            'line-height' => '30px'
                        ),
                        'preview' => array(
                            'text' => 'Sample Text'
                        )
                    ),
                    array(
                        'id' => 'tek-heading-typo',
                        'type' => 'typography',
                        'title' => esc_html__('Heading Typography', 'incubator'),
                        'google' => true,
                        'font-style' => true,
                        'font-size' => true,
                        'line-height' => true,
                        'color' => true,
                        'text-align' => true,
                        'preview' => true,
                        'all_styles' => true,
                        'units' => 'px',
                        'default' => array(
                            'color' => '#333',
                            'font-weight' => '700',
                            'font-family' => 'Work Sans',
                            'google' => true,
                            'font-size' => '40px',
                            'text-align' => 'center',
                            'line-height' => '48px'
                        ),
                        'preview' => array(
                            'text' => 'Incubator Sample Text'
                        )
                    ),
                )
            );

            $this->sections[] = array(
                'icon' => 'el-icon-th-list',
                'title' => esc_html__('Portfolio', 'incubator'),
                'compiler' => 'true',
                'fields' => array(
                    array(
                				'id' =>	'tek-portfolio-title',
                				'type' => 'switch',
                				'title' => esc_html__('Show Title', 'incubator'),
                				'subtitle' => esc_html__('Activate to display the portfolio item title in the content area.', 'incubator'),
                				'default' => '1',
                				'on' => 'Yes',
                				'off' => 'No',
              			),
                    array(
                				'id' =>	'tek-portfolio-meta',
                				'type' => 'switch',
                				'title' => esc_html__('Meta Section', 'incubator'),
                				'subtitle' => esc_html__('Activate to display the meta section (Category, Tags, Publish Date).', 'incubator'),
                				'default' => '1',
                				'on' => 'Yes',
                				'off' => 'No',
              			),
                    array(
                				'id' =>	'tek-portfolio-social',
                				'type' => 'switch',
                				'title' => esc_html__('Social Media Section', 'incubator'),
                				'subtitle' => esc_html__('Activate to display the share on social media buttons.', 'incubator'),
                				'default' => '1',
                				'on' => 'Yes',
                				'off' => 'No',
              			),
                    array(
                        'id' => 'tek-portfolio-bgcolor',
                        'type' => 'color',
                        'transparent' => false,
                        'title' => esc_html__('Page Background Color', 'incubator'),
                        'subtitle' => esc_html__('Select the background color for the content area.', 'incubator'),
                        'default' => '#fafafa',
                        'validate' => 'color'
                    ),
                )
            );

            $this->sections[] = array(
                'icon' => 'el-icon-shopping-cart',
                'title' => esc_html__('WooCommerce', 'incubator'),
                'compiler' => 'true',
                'fields' => array(
                    array(
                        'id' => 'tek-woo-products-number',
                        'type' => 'text',
                        'title' => __('Products per Page', 'incubator'),
                        'subtitle' => esc_html__('Change the products number listed per page.', 'incubator'),
                        'default' => '9',
                    ),
                    array(
                        'id' => 'tek-woo-sidebar-position',
                        'type' => 'select',
                        'title' => esc_html__('Shop Sidebar Position', 'incubator'),
                        'select2' => array('allowClear' => false, 'minimumResultsForSearch' => '-1'),
                        'options'  => array(
                            'woo-sidebar-left' => 'Left',
                            'woo-sidebar-right' => 'Right',
                        ),
                        'default' => 'woo-sidebar-right'
                    ),
                    array(
                        'id' => 'tek-woo-single-sidebar',
                        'type' => 'switch',
                        'title' => esc_html__('Single Product Sidebar', 'incubator'),
                        'subtitle' => esc_html__('Enable/Disable shop sidebar on single product page.', 'incubator'),
                        'default' => '1',
                        '1' => 'Yes',
                        '0' => 'No',
                    ),
                    array(
                        'id' => 'tek-woo-cart',
                        'type' => 'switch',
                        'title' => esc_html__('Cart Icon', 'incubator'),
                        'subtitle' => esc_html__('Turn on to display shopping cart icon in header.', 'incubator'),
                        'default' => '1',
                        '1' => 'On',
                        '0' => 'Off',
                    ),
                )
            );

            $this->sections[] = array(
                'icon' => 'el-icon-pencil-alt',
                'title' => esc_html__('Blog', 'incubator'),
                'fields' => array(
                    array(
                        'id' => 'tek-blog-subtitle',
                        'type' => 'text',
                        'title' => esc_html__('Blog Subtitle', 'incubator'),
                        'default' => 'The hardest part of starting up is starting out.'
                        //
                    ),
                    array(
                        'id' => 'tek-blog-sidebar',
                        'type' => 'switch',
                        'title' => esc_html__('Display Sidebar', 'incubator'),
                        'subtitle' => esc_html__('Turn on/off blog sidebar', 'incubator'),
                        'default' => true
                    ),
                    array(
                        'id' => 'tek-blog-minimal',
                        'type' => 'switch',
                        'title' => esc_html__('Minimal Blog', 'incubator'),
                        'subtitle' => esc_html__('Change blog layout to minimal style', 'incubator'),
                        'default' => false
                    )
                )
            );
            $this->sections[] = array(
                'icon' => 'el-icon-error-alt',
                'title' => esc_html__('404 page', 'incubator'),
                'fields' => array(
                    array(
                        'id' => 'tek-404-title',
                        'type' => 'text',
                        'title' => esc_html__('Title', 'incubator'),
                        'default' => 'Error 404'
                    ),
                    array(
                        'id' => 'tek-404-subtitle',
                        'type' => 'text',
                        'title' => esc_html__('Subtitle', 'incubator'),
                        'default' => 'This page could not be found!'
                    ),
                    array(
                        'id' => 'tek-404-back',
                        'type' => 'text',
                        'title' => esc_html__('Back to Homepage Text', 'incubator'),
                        'default' => 'Back to homepage'
                    ),
                    array(
                        'id' => 'tek-404-img',
                        'type' => 'media',
                        'readonly' => false,
                        'url' => true,
                        'title' => esc_html__('Background Image', 'incubator'),
                        'subtitle' => esc_html__('Upload 404 overlay image', 'incubator'),
                        'default' => array(
                            'url' => get_template_directory_uri() . '/images/page-404.jpg'
                        )
                    )
                )
            );

            $this->sections[] = array(
                'icon' => 'el-icon-wrench-alt',
                'title' => esc_html__('Maintenance Mode', 'incubator'),
                'fields' => array(
                    array(
                        'id' => 'tek-maintenance-mode',
                        'type' => 'switch',
                        'title' => __('Enable Maintenance Mode', 'incubator'),
                        'subtitle' => esc_html__('Activate to enable maintenance mode.', 'incubator'),
                        'default' => false
                    ),
                    array(
                        'id' => 'tek-maintenance-title',
                        'type' => 'text',
                        'title' => esc_html__('Page Title', 'incubator'),
                        'required' => array('tek-maintenance-mode','equals', true),
                        'default' => 'StartUp launching soon'
                    ),
                    array(
                        'id' => 'tek-maintenance-content',
                        'type' => 'editor',
                        'title' => esc_html__('Page Content', 'incubator'),
                        'required' => array('tek-maintenance-mode','equals', true),
                        'default' => '',
		                    'args'   => array(
                          'teeny'  => true,
                          'textarea_rows' => 10,
                          'media_buttons' => false,
			                  )
                    ),
                    array(
                        'id' => 'tek-maintenance-countdown',
                        'type' => 'switch',
                        'title' => __('Enable Countdown', 'incubator'),
                        'subtitle' => esc_html__('Activate to enable the countdown timer.', 'incubator'),
                        'required' => array('tek-maintenance-mode','equals', true),
                        'default' => false
                    ),
                    array(
                        'id' => 'tek-maintenance-count-day',
                        'type' => 'text',
                        'title' => esc_html__('End Day', 'incubator'),
                        'subtitle' => esc_html__('Enter day value. Eg. 05', 'incubator'),
                        'required' => array('tek-maintenance-countdown','equals', true),
                        'default' => ''
                    ),
                    array(
                        'id' => 'tek-maintenance-count-month',
                        'type' => 'text',
                        'title' => esc_html__('End Month', 'incubator'),
                        'subtitle' => esc_html__('Enter month value. Eg. 09', 'incubator'),
                        'required' => array('tek-maintenance-countdown','equals', true),
                        'default' => ''
                    ),
                    array(
                        'id' => 'tek-maintenance-count-year',
                        'type' => 'text',
                        'title' => esc_html__('End Year', 'incubator'),
                        'subtitle' => esc_html__('Enter year value. Eg. 2020', 'incubator'),
                        'required' => array('tek-maintenance-countdown','equals', true),
                        'default' => ''
                    ),
                    array(
                        'id' => 'tek-maintenance-days-text',
                        'type' => 'text',
                        'title' => esc_html__('Days Label', 'incubator'),
                        'subtitle' => esc_html__('Enter days text label.', 'incubator'),
                        'required' => array('tek-maintenance-countdown','equals', true),
                        'default' => 'Days'
                    ),
                    array(
                        'id' => 'tek-maintenance-hours-text',
                        'type' => 'text',
                        'title' => esc_html__('Hours Label', 'incubator'),
                        'subtitle' => esc_html__('Enter hours text label.', 'incubator'),
                        'required' => array('tek-maintenance-countdown','equals', true),
                        'default' => 'Hours'
                    ),
                    array(
                        'id' => 'tek-maintenance-minutes-text',
                        'type' => 'text',
                        'title' => esc_html__('Minutes Label', 'incubator'),
                        'subtitle' => esc_html__('Enter minutes text label.', 'incubator'),
                        'required' => array('tek-maintenance-countdown','equals', true),
                        'default' => 'Minutes'
                    ),
                    array(
                        'id' => 'tek-maintenance-seconds-text',
                        'type' => 'text',
                        'title' => esc_html__('Seconds Label', 'incubator'),
                        'subtitle' => esc_html__('Enter seconds text label.', 'incubator'),
                        'required' => array('tek-maintenance-countdown','equals', true),
                        'default' => 'Seconds'
                    ),
                    array(
                        'id' => 'tek-maintenance-subscribe',
                        'type' => 'switch',
                        'title' => __('Enable Contact Form', 'incubator'),
                        'subtitle' => esc_html__('Activate to enable contact form on page.', 'incubator'),
                        'required' => array('tek-maintenance-mode','equals', true),
                        'default' => false
                    ),
                    array(
                        'id' => 'tek-maintenance-form-select',
                        'type' => 'select',
                        'title' => esc_html__('Contact Form Plugin', 'incubator'),
                        'select2' => array('allowClear' => false, 'minimumResultsForSearch' => '-1'),
                        'required' => array('tek-maintenance-subscribe','equals',true),
                        'options'  => array(
                            '1' => 'Contact Form 7',
                            '2' => 'Ninja Forms',
                        ),
                        'default' => '1'
                    ),
                    array(
                        'id' => 'tek-maintenance-contactf7-formid',
                        'type' => 'select',
                        'data' => 'posts',
                        'args' => array( 'post_type' => 'wpcf7_contact_form', 'posts_per_page' => -1, ),
                        'title' => esc_html__('Contact Form 7 Title', 'incubator'),
                        'required' => array('tek-maintenance-form-select','equals','1'),
                        'default' => ''
                    ),
                    array(
                        'id' => 'tek-maintenance-ninja-formid',
                        'type' => 'text',
                        'title' => esc_html__('Ninja Form ID', 'incubator'),
                        'required' => array('tek-maintenance-form-select','equals','2'),
                        'default' => ''
                    ),

                )
            );

            $this->sections[] = array(
                'icon' => 'el-icon-css',
                'title' => esc_html__('Custom CSS/JS', 'incubator'),
                'fields' => array(
                    array(
                        'id' => 'tek-css',
                        'type' => 'ace_editor',
                        'title' => esc_html__('CSS', 'incubator'),
                        'subtitle' => esc_html__('Enter your CSS code in the side field. Do not include any tags or HTML in the field. Custom CSS entered here will override the theme CSS.', 'incubator'),
                        'mode' => 'css',
                        'theme' => 'chrome',
                    ),
                    array(
                  			'id' => 'tek-javascript',
                  			'type' => 'ace_editor',
                  			'title' => esc_html__( 'Javascript', 'incubator' ),
                  			'subtitle' => esc_html__( 'Only accepts Javascript code.', 'incubator' ),
                  			'mode' => 'html',
                  			'theme' => 'chrome',
                		),
                )
            );
            $this->sections[] = array(
                'title' => esc_html__('Import Demos ', 'incubator'),
                'desc' => esc_html__('Import demo content', 'incubator'),
                'icon' => 'el-icon-magic',
                'fields' => array(
                    array(
                        'id' => 'opt-import-export',
                        'type' => 'import_export',
                        'title' => esc_html__('Import demo content', 'incubator'),
                        'subtitle' => '',
                        'full_width' => false
                    )
                )
            );
        }
        /**
        * All the possible arguments for Redux.
        * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
        * */

        public function setArguments() {
            $theme = wp_get_theme(); // For use with some settings. Not necessary.
            $this->args = array(
                'opt_name' => 'redux_ThemeTek',
                'menu_type' => 'submenu',

                'menu_title' => 'Theme Options',
                'page_title' => 'Theme Options',

                'async_typography' => false,
                'admin_bar' => false,
                'dev_mode' => false,
                'show_options_object'  => false,
                'customizer' => false,
                'show_import_export' => true,

                'page_parent' => 'themes.php',
                'page_permissions' => 'manage_options',
                'page_slug' => 'theme-options',
                'hints' => array(
                    'icon' => 'el-icon-question-sign',
                    'icon_position' => 'right',
                    'icon_size' => 'normal',
                    'tip_style' => array(
                        'color' => 'light'
                    ),
                    'tip_position' => array(
                        'my' => 'top left',
                        'at' => 'bottom right'
                    ),
                    'tip_effect' => array(
                        'show' => array(
                            'duration' => '500',
                            'event' => 'mouseover'
                        ),
                        'hide' => array(
                            'duration' => '500',
                            'event' => 'mouseleave unfocus'
                        )
                    )
                ),
                'output' => '1',
                'output_tag' => '1',
                'compiler' => '0',
                'page_icon' => 'icon-themes',
                'save_defaults' => '1',
                'transient_time' => '3600',
                'network_sites' => '1',
            );
            $theme = wp_get_theme(); // For use with some settings. Not necessary.
            $this->args["display_name"] = $theme->get("Name");
            $this->args["display_version"] = $theme->get("Version");

        }
    }
    global $reduxConfig;
    $reduxConfig = new keydesign_Redux_Framework_config();
}
