<?php 
// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

$widget[1] = is_active_sidebar( 'fury-footer-widget-1' );
$widget[2] = is_active_sidebar( 'fury-footer-widget-2' );
$widget[3] = is_active_sidebar( 'fury-footer-widget-3' );
$widget[4] = is_active_sidebar( 'fury-footer-widget-4' ); 
$widget[5] = is_active_sidebar( 'fury-footer-widget-5' );
$widget[6] = is_active_sidebar( 'fury-footer-widget-6' ); 

$copyright = strip_tags( 
    get_theme_mod( 
        'fury_footer_copyright', 
        'Fury theme by <a href="https://theme-vision.com" target="_blank">ThemeVision</a>' 
    ), 
    '<a><code><em><i><img><ul><ol><li><strong>' 
); ?>

        <?php do_action( 'fury_content_wrapper_end' ); ?>
        
        <!-- Footer -->
        <footer class="site-footer">
            <div class="container">
                
                <?php if( $widget[1] || $widget[2] || $widget[3] || $widget[4] ): ?>
                <div class="row">
                    
                    <?php if( $widget[1] ): ?>
                    <div class="col-lg-3 col-md-6">
                         <?php dynamic_sidebar( 'fury-footer-widget-1' ); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if( $widget[2] ): ?>
                    <div class="col-lg-3 col-md-6">
                       <?php dynamic_sidebar( 'fury-footer-widget-2' ); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if( $widget[3] ): ?>
                    <div class="col-lg-3 col-md-6">
                        <?php dynamic_sidebar( 'fury-footer-widget-3' ); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if( $widget[4] ): ?>
                    <div class="col-lg-3 col-md-6">
                        <?php dynamic_sidebar( 'fury-footer-widget-4' ); ?>
                    </div>
                    <?php endif; ?>
                    
                </div>
                
                <hr class="hr-light mt-2 margin-bottom-2x">
                
                <?php endif; ?>
                
                <?php if( $widget[5] || $widget[6] ): ?>
                <div class="row">
                    
                    <?php if( $widget[5] ): ?>
                    <div class="col-md-7 padding-bottom-1x">
                        <?php dynamic_sidebar( 'fury-footer-widget-5' ); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if( $widget[6] ): ?>
                    <div class="col-md-5 padding-bottom-1x">
                        <?php dynamic_sidebar( 'fury-footer-widget-6' ); ?>
                    </div>
                    <?php endif; ?>
                    
                </div>
                <?php endif; ?>
                
                <p class="footer-copyright">
                    <?php echo wp_kses_post( $copyright ); ?>
                </p>
                
            </div>
        </footer><!-- Footer End -->
    
    </div><!-- Off-Canvas Wrapper End -->

</div><!-- Fury Main Wrapper End -->

    <!-- Back To Top Button -->
    <a class="scroll-to-top-btn" href="#"><i class="icon-arrow-up"></i></a><!-- Back To Top Button End -->

    <!-- Backdrop -->
    <div class="site-backdrop"></div>
    
    <?php wp_footer(); ?>

</body>
</html>