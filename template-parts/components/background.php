<?php

$field = $args['field'] ?? '';
$class = $args['class'] ?? '';

$bg_comp = is_array($field) ? $field : get_sub_field($field ?: 'background');

$background_image = $bg_comp['background_image'] ?? '';
$background_position = $bg_comp['background_position'] ?? '';
$background_color = $bg_comp['background_color'] ?? '';
$background_overlay = $bg_comp['background_overlay'] ?? '';
$bg_image_class = $background_position ? ' object-' . $background_position : '';
$bg_style = '';
if ($background_color) {
  $bg_style .= 'background-color:' . $background_color . ';';
}

?>

<?php if ($background_image) : ?>
  <div class="absolute inset-0 z-0" style="<?php echo $bg_style ?>">
    <img class="object-cover w-full h-full transition-all duration-300 <?php echo $bg_image_class ?>" src="<?php echo $background_image['url'] ?>" alt="">
    <?php if ($background_overlay) : ?>
      <div class="absolute inset-0 z-0" style="background-color: <?php echo $background_overlay; ?>"></div>
    <?php endif ?>
  </div>
<?php endif; ?>