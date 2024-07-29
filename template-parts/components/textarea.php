<?php

// Extracting field, class, and align from args with null coalescing operator
$field = $args['field'] ?? '';
$class = $args['class'] ?? '';
$size = $args['size'] ?? '';
$align = $args['align'] ?? '';
$leading = $args['leading'] ?? '';
$weight = $args['weight'] ?? '';

// Retrieving textarea component data
$textarea_component = is_array($field) ? $field : get_sub_field($field ?: 'text_area');

// Extracting textarea and text color from textarea component
$textarea = $textarea_component['text_area']['text_area'] ?? '';
$text_color = $textarea_component['text_area']['settings']['text_color'] ?? '';

// Setting textarea class to align
$text_size_class = $size ? $size : 'xl:prose-lg';
$text_align_class = $align ? $align : '';
$text_leading_class = $leading ? $leading : '';
$text_weight_class = $weight ? $weight : 'font-medium';

// Initializing textarea style
$textarea_style = '';

// Applying text color style if available
if ($text_color) {
  $textarea_style .= 'color:' . $text_color . ';';
}

// Combining classes
$textarea_class = '';
$class_list = ['prose max-w-none', $text_size_class, $text_align_class, $text_leading_class, $text_weight_class];
$textarea_class .= implode(' ', $class_list);

// Outputting textarea if available
if ($textarea) {
  echo '<div class="' . $textarea_class . '" style="' . $textarea_style . '">';
  echo '<p>' . $textarea . '</p>';
  echo '</div>';
}
