<?php
include get_template_directory() . '/template-parts/global/section_settings.php';
/*
 * Available section variables
 * $section_id
 * $section_style
 * $section_padding_top
 * $section_padding_bottom
*/

$section_id = $section_id ? 'id="' . $section_id . '"' : '';

$two_columns = get_sub_field('two_columns');
$left_column_components = isset($two_columns['left_column_components_components']) ? $two_columns['left_column_components_components'] : '';
$right_column_components = isset($two_columns['right_column_components_components']) ? $two_columns['right_column_components_components'] : '';

$column_settings = isset($two_columns['column_settings']) ? $two_columns['column_settings'] : '';
$col_left_class = '';
$col_right_class = '';
switch ($column_settings) {
  case "half":
    $col_left_class .= 'w-full lg:w-1/2 xl:w-1/2';
    $col_right_class .= 'w-full lg:w-1/2 xl:w-1/2';
    break;
  case "one_two_third":
    $col_left_class .= 'w-full lg:w-1/3 xl:w-1/3';
    $col_right_class .= 'w-full lg:w-2/3 xl:w-2/3';
    break;
  case "two_one_third":
    $col_left_class .= 'w-full lg:w-2/3 xl:w-2/3';
    $col_right_class .= 'w-full lg:w-1/3 xl:w-1/3';
    break;
  case "one_three_fourth":
    $col_left_class .= 'w-full lg:w-1/4 xl:w-1/4';
    $col_right_class .= 'w-full lg:w-3/4 xl:w-3/4';
    break;
  case "three_one_fourth":
    $col_left_class .= 'w-full lg:w-3/4 xl:w-3/4';
    $col_right_class .= 'w-full lg:w-1/4 xl:w-1/4';
    break;
  case "two_three_five":
    $col_left_class .= 'w-full lg:w-2/5 xl:w-2/5';
    $col_right_class .= 'w-full lg:w-3/5 xl:w-3/5';
    break;
  case "three_two_five":
    $col_left_class .= 'w-full lg:w-3/5 xl:w-3/5';
    $col_right_class .= 'w-full lg:w-2/5 xl:w-2/5';
    break;
}

//preint_r($column_settings);

?>

<section <?php echo $section_id ?> class="section-wrapper section-two_columns relative" style="<?php echo $section_style ?>">

  <?php get_template_part('template-parts/global/separator', '', array('location' => 'top', 'active' => $top_separator, 'color' => $top_separator_color, 'class' => '')); ?>
  <div class="section-spacing relative <?php echo $section_padding_top . ' ' . $section_padding_bottom ?>">
    <?php get_template_part('template-parts/global/background_ornament', '', array('location' => 'top', 'shape' => $section_ornament_shape, 'color' => $section_ornament_color, 'position' => $section_ornament_position, 'class' => '')); ?>
    <div class="section-content container mx-auto max-w-screen-2xl animation-wrapper">
      <div class="relative z-10 flex flex-col xl:flex-row xl:gap-x-12 2xl:gap-x-24 <?php echo $entrance_animation_class ?>">
        <div class="<?php echo $col_left_class ?>">
          <?php get_template_part('template-parts/components/components', '', array('field' => $left_column_components)); ?>
        </div>
        <div class="<?php echo $col_right_class ?>">
          <?php get_template_part('template-parts/components/components', '', array('field' => $right_column_components)); ?>
        </div>
      </div>
    </div>
    <?php get_template_part('template-parts/global/background_ornament', '', array('location' => 'bottom', 'shape' => $section_ornament_shape, 'color' => $section_ornament_color, 'position' => $section_ornament_position, 'class' => '')); ?>
  </div>
  <?php get_template_part('template-parts/global/separator', '', array('location' => 'bottom', 'active' => $bottom_separator, 'color' => $bottom_separator_color, 'class' => '')); ?>

</section>