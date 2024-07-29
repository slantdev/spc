<?php

// Extracting field and class from args with null coalescing operator
$field = $args['field'] ?? '';
$class = $args['class'] ?? '';

// Getting content editor component
$content_editor_comp = is_array($field) ? $field : get_sub_field($field ?: 'content_editor');

// Extracting content editor and text color from content editor component
$content_editor = $content_editor_comp['content_editor']['content_editor'] ?? '';
$text_color = $content_editor_comp['content_editor']['settings']['text_color'] ?? '';

$content_editor_style = '';

// Assigning text color to content editor style if available
if ($text_color) {
  $content_editor_style = 'color:' . $text_color . ';';
}

// Outputting content editor if available
if ($content_editor) {
  echo '<div class="prose font-medium max-w-none xl:prose-lg prose-lead:xl:text-2xl prose-lead:font-normal" style="' . $content_editor_style . '">';
  echo $content_editor;
  echo '</div>';
}
