<?php
// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Fury Helper Class
 *
 * @since 1.0
 */
if( ! class_exists( 'Fury_Helper' ) ) {
    class Fury_Helper {

        /**
         * Get Post Tags
         *
         * @used in class-fury.php
         * @since 1.0
         */
        public static function get_tags() {
            if( has_tag() ) {
                $html = '<li>';
                    $html .= '<i class="icon-tag"></i> ';
                    foreach( get_the_tags() as $tag ) {
                        $tag_link = get_tag_link( $tag->term_id );

                        $html .= '<a href="'. esc_url( $tag_link ) .'" 
                                     title="'. esc_attr( $tag->name ) .' '. esc_attr__( 'Tag', 'fury' ) .'" 
                                     class="'. esc_attr( $tag->slug ) .'">';
                        $html .= esc_html( $tag->name ) .'</a>,';
                    }
                $html .= '</li>';
                return $html;
            }
        }

        /**
         * Count Comments
         *
         * @used in class-fury.php
         * @since 1.0
         */
        public static function comments_count() {
            $comments = esc_html__( 'no comments', 'fury' );
            if( comments_open() ) {
                $comments = get_comments_number();
                if( $comments ) {
                    $comments .= ' ' . esc_html__( 'comment', 'fury' );
                } else {
                    $comments .= ' ' . esc_html__( 'comments', 'fury' );
                }
            }
            return $comments;
        }

        /**
         * Get All Created Pages
         *
         * @access public
         * @since 1.1.6
         * @return array() #id -> #pagename
         */
        public static function dropdown_pages() {
            $pages = get_pages();

            foreach( $pages as $page ) {
                $output[$page->ID] = esc_html( $page->post_title );
            }

           return $output;
        }

    }
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
