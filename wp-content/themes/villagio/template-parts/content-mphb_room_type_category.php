<?php
/**
 * Template part for displaying mphb_room
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Villagio
 */

$templateAtts = array(
	'isShowGallery'    => 'true',
	'isShowImage'      => 'true',
	'isShowTitle'      => 'true',
	'isShowExcerpt'    => 'true',
	'isShowDetails'    => 'true',
	'isShowPrice'      => 'true',
	'isShowViewButton' => 'true',
	'isShowBookButton' => 'true',
);
mphb_get_template_part( 'shortcodes/rooms/room-content', $templateAtts );
