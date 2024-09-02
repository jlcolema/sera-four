<?php
// ------------------------------------------------------------------------
// Register widgetized areas
// ------------------------------------------------------------------------
    function keydesign_sidebars_register() {
		register_sidebar( array(
            'name' => esc_html__( 'Blog Sidebar', 'incubator' ),
            'id' => 'blog-sidebar',
            'description' => esc_html__( 'Add widgets for the blog sidebar area. If none added, default sidebar widgets will be used.', 'incubator' ),
            'before_widget' => '<div id="%1$s" class="blog_widget widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h5 class="widget-title"><span>',
            'after_title' => '</span></h5>',
        ) );
        register_sidebar( array(
            'name' => esc_html__( 'Shop Sidebar', 'incubator' ),
            'id' => 'shop-sidebar',
            'description' => esc_html__( 'Add widgets for the shop sidebar area.', 'incubator' ),
            'before_widget' => '<div id="%1$s" class="blog_widget widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h5 class="widget-title"><span>',
            'after_title' => '</span></h5>',
        ) );

        register_sidebar( array(
            'name' => esc_html__( 'Footer first widget area', 'incubator' ),
            'id' => 'footer-first-widget-area',
            'description' => esc_html__( 'Add one widget for the first footer widget area.', 'incubator' ),
            'before_widget' => '<div id="%1$s" class="footer_widget widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h5 class="widget-title"><span>',
            'after_title' => '</span></h5>',
        ) );

        register_sidebar( array(
            'name' => esc_html__( 'Footer second widget area', 'incubator' ),
            'id' => 'footer-second-widget-area',
            'description' => esc_html__( 'Add one widget for the second footer widget area.', 'incubator' ),
            'before_widget' => '<div id="%1$s" class="footer_widget widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h5 class="widget-title"><span>',
            'after_title' => '</span></h5>',
        ) );

        register_sidebar( array(
            'name' => esc_html__( 'Footer third widget area', 'incubator' ),
            'id' => 'footer-third-widget-area',
            'description' => esc_html__( 'Add one widget for the third footer widget area.', 'incubator' ),
            'before_widget' => '<div id="%1$s" class="footer_widget widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h5 class="widget-title"><span>',
            'after_title' => '</span></h5>',
        ) );

        register_sidebar( array(
            'name' => esc_html__( 'Footer fourth widget area', 'incubator' ),
            'id' => 'footer-fourth-widget-area',
            'description' => esc_html__( 'Add one widget for the fourth footer widget area.', 'incubator' ),
            'before_widget' => '<div id="%1$s" class="footer_widget widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h5 class="widget-title"><span>',
            'after_title' => '</span></h5>',
        ) );
    }

    add_action( 'widgets_init', 'keydesign_sidebars_register' );
?>
