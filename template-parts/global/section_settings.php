<?php
/*
 * Section Settings Variables
 */

$section_settings = get_sub_field('section_settings') ?: [];

// Variables
$section_style = '';
$section_container_class = '';
$entrance_animation_class = '';

// Spacings - Paddings
$spacing_top = $section_settings['section_spacing']['spacing']['spacing_top'] ?? '';
$spacing_bottom = $section_settings['section_spacing']['spacing']['spacing_bottom'] ?? '';
$spacing_top_map = [
  'none' => 'pt-0',
  'xs' => 'pt-4 lg:pt-8 xl:pt-8',
  'sm' => 'pt-4 lg:pt-6 xl:pt-14',
  'md' => 'pt-8 lg:pt-12 xl:pt-20',
  'lg' => 'pt-10 lg:pt-16 xl:pt-28',
  'xl' => 'pt-12 lg:pt-20 xl:pt-36',
  '2xl' => 'pt-12 lg:pt-24 xl:pt-40',
  'default' => 'pt-12 lg:pt-20 xl:pt-36'
];
$spacing_bottom_map = [
  'none' => 'pb-0',
  'xs' => 'pb-4 lg:pb-8 xl:pb-8',
  'sm' => 'pb-4 lg:pb-6 xl:pb-14',
  'md' => 'pb-8 lg:pb-12 xl:pb-20',
  'lg' => 'pb-10 lg:pb-16 xl:pb-28',
  'xl' => 'pb-12 lg:pb-20 xl:pb-36',
  '2xl' => 'pb-12 lg:pb-24 xl:pb-40',
  'default' => 'pb-12 lg:pb-20 xl:pb-36'
];
$section_padding_top = $spacing_top_map[$spacing_top] ?? '';
$section_padding_bottom = $spacing_bottom_map[$spacing_bottom] ?? '';
$section_container_class = $section_padding_top . ' ' . $section_padding_bottom  . ' ';

// Spacing - Separator
$separator_settings = $section_settings['section_spacing']['line_separator'] ?? '';
$top_separator = $separator_settings['top_separator']['show_top_separator'] ?? false;
$bottom_separator = $separator_settings['bottom_separator']['show_bottom_separator'] ?? false;
$top_separator_color = $separator_settings['top_separator']['top_separator_color'] ?? '';
$bottom_separator_color = $separator_settings['bottom_separator']['bottom_separator_color'] ?? '';
$top_separator_style = $top_separator ? "border-color: $top_separator_color;" : '';
$bottom_separator_style = $bottom_separator ? "border-color: $bottom_separator_color;" : '';

// Background - Color/Image
$background_settings = $section_settings['background'] ?? '';
$section_background_color = $background_settings['background_color'] ?? '';
$section_background_overlay = $background_settings['background_overlay'] ?? '';
$section_background_image = $background_settings['background_image']['url'] ?? '';
$section_style .= $section_background_color ? "background-color: $section_background_color;" : '';
$section_style .= $section_background_image ? "background-image: url($section_background_image); background-size: cover; background-repeat: no-repeat;" : '';

$split_background_horizontally = $section_settings['split_background_horizontally'] ?? '';
$split_background = $split_background_horizontally['split_background'] ?? '';
$secondary_background = $split_background_horizontally['secondary_background'] ?? '';
$position_from_top = $split_background_horizontally['position_from_top'] ?? '';

// Background - Ornament
$ornament_settings = $section_settings['background_ornament'] ?? '';
$section_ornament_shape = $ornament_settings['ornament_shape'] ?? '';
$section_ornament_color = $ornament_settings['ornament_color'] ?? '';
$section_ornament_position = $ornament_settings['ornament_position'] ?? '';

// Text & Link
$text_link_settings = $section_settings['text_link'] ?? '';
$section_text_color = $text_link_settings['text_color'] ?? '';
$section_link_color = $text_link_settings['link_color'] ?? '';
$section_style .= $section_text_color && $section_text_color !== 'default' ? "color: $section_text_color; --section-text-color: $section_text_color;" : '';
$section_style .= $section_link_color ? "--section-link-color: $section_link_color;" : '';

// Extras - Anchor
$section_id = '';
if ($section_settings['extra_settings']['section_anchor']['add_section_anchor'] ?? false) {
  $section_id = $section_settings['extra_settings']['section_anchor']['section_id'] ?? '';
}

// Extras - Entrance Animation
$entrance_animation = $section_settings['animations']['entrance_animation'] ?? '';
$entrance_animation_map = [
  'none' => '',
  'fadeInUp' => 'animation-item animation-fadeInUp',
];
$entrance_animation_class = $entrance_animation_map[$entrance_animation] ?? '';
