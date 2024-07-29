<?php

// Extracting field and class from args with null coalescing operator
$field = $args['field'] ?? '';
$class = $args['class'] ?? '';

// Getting image component
$image_comp = is_array($field) ? $field : get_sub_field($field ?: 'image');

// Extracting image details
$image_source = $image_comp['image_source'] ?? [];
$image_url = $image_source['url'] ?? '';
$image_alt = $image_source['alt'] ?? '';

$more_settings = $image_comp['settings']['more_settings'] ?? [];
$aspect_ratio = $more_settings['aspect_ratio'] ?? '1_1';
$image_corners = $more_settings['image_corners'] ?? 'rounded-xl';
$image_max_width = $more_settings['image_max_width'] ?? '';

// Setting aspect ratio classes
$aspect_ratio_map = [
  "default" => ['aspect-w-1', 'aspect-h-1'],
  "1_1" => ['aspect-w-1', 'aspect-h-1'],
  "3_2" => ['aspect-w-3', 'aspect-h-2'],
  "5_4" => ['aspect-w-5', 'aspect-h-4'],
  "16_9" => ['aspect-w-16', 'aspect-h-9'],
  "4_3" => ['aspect-w-4', 'aspect-h-3'],
];
[$aspect_w, $aspect_h] = $aspect_ratio_map[$aspect_ratio];

// Setting image corners class
$image_corners = ($image_corners == 'default') ? 'rounded-xl' : $image_corners;

// Setting max width class
$max_w_class = '';
if ($image_max_width) {
  $max_w_class = ($image_max_width == 'default') ? ' max-w-full' : ' max-w-' . $image_max_width;
}

// Combining classes
$class_list = ['overflow-clip', $aspect_w, $aspect_h, $image_corners];
$class .= ' ' . implode(' ', $class_list);

// Outputting image if URL exists
if ($image_url) {
  echo '<div class="' . $max_w_class . '">';
  echo '<div class="' . $class . '">';
  echo '<img src="' . $image_url . '" alt="' . $image_alt . '" class="object-cover w-full h-full" />';
  echo '</div>';
  echo '</div>';
}
