<?php
// Get Colors from Site Settings
$primary_color = get_field('primary_color', 'option');
$secondary_color = get_field('secondary_color', 'option');
$tertiary_color = get_field('tertiary_color', 'option');

// Extracting field and class from args with null coalescing operator
$field = $args['field'] ?? '';
$class = $args['class'] ?? '';

// Getting buttons component
$buttons_comp = is_array($field) ? $field : get_sub_field($field ?: 'buttons');

// Setting default alignment class
$buttons_alignment_class = 'text-left';

// Checking if buttons settings exist and extracting alignment
if (isset($buttons_comp['settings']['buttons_alignment'])) {
  $buttons_alignment = $buttons_comp['settings']['buttons_alignment'];

  // Mapping alignment values to corresponding classes
  $buttons_alignment_classes = [
    "left" => 'text-left',
    "center" => 'text-center mx-auto',
    "right" => 'text-right',
  ];

  // Assigning alignment class based on alignment value
  $buttons_alignment_class = $buttons_alignment_classes[$buttons_alignment] ?? $buttons_alignment_class;
}

// Combining class and alignment class
$button_container_class = $class . ' ' . $buttons_alignment_class;

// Getting buttons repeater
$buttons_repeater = $buttons_comp['buttons_repeater'] ?? '';

// Outputting buttons if repeater exists
if ($buttons_repeater) {
  $button_count = count($buttons_repeater);
  $print_button_margin = $button_count > 1 ? 'mr-4 mb-4' : '';

  // Opening button container div
  echo '<div class="mb-6 xl:mb-0 ' . $button_container_class . '">';

  foreach ($buttons_repeater as $button) {
    // Extracting button details
    $button_link = $button['button_link'];
    $button_title = $button_link['title'] ?? '';
    $button_url = $button_link['url'] ?? '';
    $button_target = $button_link['target'] ?? '_self';

    $button_settings = $button['settings']['more_settings'] ?? [];
    $button_style = $button_settings['button_style'] ?? '';
    $button_size = $button_settings['button_size'] ?? '';
    $button_bg_color = $button_settings['button_bg_color'] ?? '';
    $button_border_color = $button_settings['button_border_color'] ?? '';
    $button_text_color = $button_settings['button_text_color'] ?? '';

    // Setting default button classes and styles
    $print_button_class = '';
    $print_button_style = '';

    // Mapping button style to corresponding classes
    $button_styles_map = [
      "primary-filled" => 'btn-primary text-white hover:brightness-125',
      "secondary-filled" => 'btn-secondary text-white hover:brightness-125',
      "tertiary-filled" => 'btn-accent text-white hover:brightness-125',
      "white" => 'bg-white border-white hover:brightness-90',
      "primary-outline" => 'btn-outline btn-primary',
      "secondary-outline" => 'btn-outline btn-secondary',
      "tertiary-outline" => 'btn-outline btn-accent',
      "white-outline" => 'btn-outline border-white text-white shadow-md hover:bg-white hover:border-gray-500 hover:text-white hover:bg-gray-500',
    ];
    if ($primary_color && $button_style == 'primary-filled') {
      $print_button_style .= 'background-color: ' . $primary_color . '; border-color: ' . $primary_color . ';';
    }
    if ($secondary_color && $button_style == 'secondary-filled') {
      $print_button_style .= 'background-color: ' . $secondary_color . '; border-color: ' . $secondary_color . ';';
    }
    if ($tertiary_color && $button_style == 'tertiary-filled') {
      $print_button_style .= 'background-color: ' . $tertiary_color . '; border-color: ' . $tertiary_color . ';';
    }
    if ($button_style == 'white') {
      $print_button_style .= 'color: #404041;';
    }
    if ($primary_color && $button_style == 'primary-outline') {
      //$print_button_style .= 'border-color: ' . $primary_color . '; color: ' . $primary_color . '; --btn-color: ' . $primary_color . ';';
      $print_button_style .= '--btn-color: ' . $primary_color . '; --fallback-p: ' . $primary_color . ';';
    }
    if ($secondary_color && $button_style == 'secondary-outline') {
      //$print_button_style .= 'border-color: ' . $secondary_color . '; color: ' . $secondary_color . ';';
      $print_button_style .= '--btn-color: ' . $secondary_color . '; --fallback-s: ' . $secondary_color . ';';
    }
    if ($tertiary_color && $button_style == 'tertiary-outline') {
      //$print_button_style .= 'border-color: ' . $tertiary_color . '; color: ' . $tertiary_color . ';';
      $print_button_style .= '--btn-color: ' . $tertiary_color . '; --fallback-a: ' . $tertiary_color . '; --fallback-ac: #ffffff;';
    }

    // Assigning button class based on button style
    $print_button_class = $button_styles_map[$button_style] ?? '';

    // Assigning button size class
    switch ($button_size) {
      case "xs":
        $print_button_class .= ' btn-xs px-3';
        break;
      case "sm":
        $print_button_class .= ' btn-sm px-4';
        break;
      case "md":
        $print_button_class .= ' btn-md px-10 xl:text-lg';
        break;
      case "lg":
        $print_button_class .= ' btn-lg px-12 xl:text-xl';
        break;
      default:
        $print_button_class .= ' btn-md px-10 xl:text-lg';
    }

    // Adding custom button styles if available
    if ($button_style == 'custom') {
      $print_button_class .= ' hover:brightness-125';
      $print_button_style .= $button_bg_color ? 'background-color: ' . $button_bg_color . ';' : '';
      $print_button_style .= $button_text_color ? 'color: ' . $button_text_color . ';' : '';
      $print_button_style .= $button_border_color ? 'border-color: ' . $button_border_color . ';' : '';
    }

    $class_list = ['btn rounded-full hover:shadow-lg transition-all duration-300', $print_button_class, $print_button_margin];
    $class = implode(' ', $class_list);

    // Add Icon
    $icon = $button_settings['icon'] ?? '';
    $icon_position = $button_settings['icon_position'] ?? '';
    $print_icon = '';
    $print_icon_left = '';
    $print_icon_right = '';
    // Handle if the return type is a string.
    if (is_string($icon)) {
      // If the type selected was a Dashicon, the value of $icon will be the dashicon class string.
      // If the type selected was a Media Library image, the value of $icon will be the URL to the image.
      // If the type selected was a URL, the value of $icon will be the URL to the image.
      $print_icon = esc_html($icon);
    } else {
      // Handle if the return type is an array.
      // If the type selected was a Dashicon, render a div with the dashicon class.
      if ('dashicons' === $icon['type']) {
        $print_icon = '<span class="btn-icon dashicons ' . esc_attr($icon['value']) . '"></span>';
      }
      // If the type selected was a Media Library image, use the attachment ID to get and render the image.
      if ('media_library' === $icon['type']) {
        $attachment_id = $icon['value'];
        $size = 'full'; // (thumbnail, medium, large, full, or custom size)

        $image_html = wp_get_attachment_image($attachment_id, $size);
        $print_icon =  wp_kses_post($image_html);
      }
      // If the type selected was a URL, render an image tag with the URL.
      if ('url' === $icon['type']) {
        $url = $icon['value'];
        $print_icon =  '<span class="btn-icon"><img src="' . esc_url($url) . '" alt=""></span>';
      }
    }

    if ($icon_position == 'left') {
      $print_icon_left = $print_icon;
      $print_icon_right = '';
    } else {
      $print_icon_left = '';
      $print_icon_right = $print_icon;
    }

    // Outputting button HTML
    if ($button_url) {
      echo '<a href="' . $button_url . '" class="' . $class . '" style="' . $print_button_style . '" title="' . $button_title . '" target="' . $button_target . '">' . $print_icon_left . '<span>' . $button_title . '</span>' . $print_icon_right . '</a>';
    }
  }

  echo '</div>';
}
