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
$section_class = 'section-onecolumn-' . uniqid();

$one_column = get_sub_field('one_column');
$components = $one_column['components'] ?? '';
//preint_r($one_column);


// Notes:
// Columns settings doesn't work yet
$column_settings = $one_column['column_settings'] ?? [];
$alignment = $column_settings['alignment'] ?? '';
$alignment_classes = [
  "left" => 'text-left mr-auto',
  "center" => 'text-center mx-auto',
  "right" => 'text-right ml-auto',
];
$alignment_class = $alignment_classes[$alignment] ?? 'text-center';
$max_width = $column_settings['max_width'] ?? '';
$max_width_classes = [
  "default" => 'container mx-auto max-w-screen-2xl',
  "none" => 'max-w-none',
  "xs" => 'container mx-auto max-w-screen-xs',
  "sm" => 'container mx-auto max-w-screen-sm',
  "md" => 'container mx-auto max-w-screen-md',
  "lg" => 'container mx-auto max-w-screen-lg',
  "xl" => 'container mx-auto max-w-screen-xl',
  "2xl" => 'container mx-auto max-w-screen-2xl',
];
$max_width_class = $max_width_classes[$max_width] ?? '';

// Combining classes
$class_list = ['text-brand-dark-blue', $alignment_class, $max_width_class];
$content_class = implode(' ', $class_list);

?>

<section <?php echo $section_id ?> class="<?php echo $section_class ?> section-wrapper relative overflow-hidden" style="<?php echo $section_style ?>">
  <?php if ($section_background_overlay) {
    echo '<div class="absolute inset-0 w-full h-full" style="background-color: ' . $section_background_overlay . '"></div>';
  } ?>
  <?php if ($split_background) {
    echo '<div class="absolute left-0 right-0 bottom-0 w-full" style="background-color: ' . $secondary_background . '; top: ' . $position_from_top . '%;"></div>';
  } ?>
  <?php get_template_part('template-parts/global/separator', '', array('location' => 'top', 'active' => $top_separator, 'color' => $top_separator_color, 'class' => '')); ?>
  <div class="section-spacing relative <?php echo $section_padding_top . ' ' . $section_padding_bottom ?>">
    <?php get_template_part('template-parts/global/background_ornament', '', array('location' => 'top', 'shape' => $section_ornament_shape, 'color' => $section_ornament_color, 'position' => $section_ornament_position, 'class' => '')); ?>

    <div class="section-content animation-wrapper">
      <div class="<?php echo $content_class ?> <?php echo $entrance_animation_class ?>">
        <?php get_template_part('template-parts/components/components', '', array('field' => $components)); ?>
      </div>
    </div>

    <?php get_template_part('template-parts/global/background_ornament', '', array('location' => 'bottom', 'shape' => $section_ornament_shape, 'color' => $section_ornament_color, 'position' => $section_ornament_position, 'class' => '')); ?>
  </div>
  <?php get_template_part('template-parts/global/separator', '', array('location' => 'bottom', 'active' => $bottom_separator, 'color' => $bottom_separator_color, 'class' => '')); ?>
</section>