<?php

// Extracting field and class from args with null coalescing operator
$field = $args['field'] ?? '';
$class = $args['class'] ?? '';
$align = $args['align'] ?? '';
$size = $args['size'] ?? '';
$weight = $args['weight'] ?? '';
$leading = $args['leading'] ?? '';

// Getting lead text component
$lead_text_comp = is_array($field) ? $field : get_sub_field($field ?: 'lead_text');

// Extracting lead text and text color
$lead_text = $lead_text_comp['lead_text']['lead_text'] ?? '';
$text_color = $lead_text_comp['lead_text']['settings']['text_color'] ?? '';

// Setting lead text style
$lead_text_style = $text_color ? '--tw-prose-lead:' . $text_color . ';color:' . $text_color . ';' : '';

// Assigning default classes
$text_size_class = $size ? $size : 'prose-lead:xl:text-2xl';
$text_align_class = $align ? $align : '';
$text_leading_class = $leading ? $leading : '';
$text_weight_class = $weight ? 'font-medium prose-lead:' . $weight : 'font-medium prose-lead:font-normal';

// Combining classes
$class_list = ['prose max-w-none', $text_size_class, $text_align_class, $text_leading_class, $text_weight_class,];
$class .= ' ' . implode(' ', $class_list);

// Outputting lead text if exists
if ($lead_text) {
  echo '<div class="' . $class . '" style="' . $lead_text_style . '">';
  echo '<p class="lead">' . $lead_text . '</p>';
  echo '</div>';
}
