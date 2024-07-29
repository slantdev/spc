<?php

// Extracting field and class from args with null coalescing operator
$field = $args['field'] ?? '';
$class = $args['class'] ?? '';

// Retrieving video data
$video = is_array($field) ? $field : get_sub_field($field ?: 'video');

// Extracting video_embed from video array
$video_embed = $video['video_embed'] ?? '';

// Outputting video_embed if available
if ($video_embed) {
  echo '<div class="aspect-w-16 aspect-h-9">';
  echo $video_embed;
  echo '</div>';
}
