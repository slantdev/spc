<?php

// Extracting field and class from args with null coalescing operator
$field = $args['field'] ?? '';
$class = $args['class'] ?? '';

// Retrieving numbered list component data
$stats_comp = is_array($field) ? $field : get_sub_field($field ?: 'stats');

// Extracting numbered list repeater and settings
$stats_repeater = $stats_comp['stats'] ?? [];
$more_settings = $stats_comp['settings']['more_settings'] ?? [];

//preint_r($stats_repeater);

// Extracting circle size, color, and text color
$number_color = $more_settings['number_color'] ?? '';
$title_color = $more_settings['title_color'] ?? '';

$number_style = '';
$title_style = '';
if ($number_color) {
  $number_style = 'color : ' . $number_color . ';';
}
if ($title_color) {
  $title_style =  'color : ' . $title_color . ';';
}

// Initializing default styles and classes
// $circle_class = 'w-24 h-24 text-4xl';
// $text_class = 'pb-10';
// $circle_style = '';
// $border_style = '';
// $text_style = '';

// Updating styles and classes based on settings
// switch ($circle_size) {
//   case "small":
//     $circle_class = 'w-20 h-20 text-3xl mb-8';
//     $text_class = 'pb-8';
//     break;
//   case "large":
//     $circle_class = 'w-28 h-28 text-5xl mb-12';
//     $text_class = 'pb-12';
//     break;
// }

// if ($circle_color) {
//   $circle_style = 'background-color: ' . $circle_color . ';';
//   $border_style = 'border-color: ' . $circle_color . ';';
// }

// if ($text_color) {
//   $text_style = 'color:' . $text_color . ';';
// }

$uniqid = uniqid();
$stats_id = 'stats-' . $uniqid;

// Outputting stats if repeater exists
if ($stats_repeater) { ?>
  <div id="<?php echo $stats_id ?>" class="relative">
    <div class="flex flex-col md:flex-row gap-8 md:justify-between">
      <?php
      foreach ($stats_repeater as $stats) :
        $stats_number = $stats['stats_number'] ?? '';
        $title = $stats['title'] ?? '';
      ?>
        <div class="text-center">
          <div class="text-5xl md:text-6xl font-bold" style="<?php echo $number_style ?>"><span class="counterNumber"><?php echo $stats_number ?></span></div>
          <div class="uppercase font-semibold text-base md:text-lg" style="<?php echo $title_style ?>"><?php echo $title ?></div>
        </div>
      <?php endforeach ?>
    </div>
  </div>
<?php }
