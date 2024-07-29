<?php

$location = $args['location'] ?? '';
$shape = $args['shape'] ?? '';
$color = $args['color'] ?? '';
$position = $args['position'] ?? '';
$class = $args['class'] ?? '';

$style = ($shape !== 'none' && $color) ? 'color: ' . $color . ';' : '';

$position_classes = [
  "default" => 'absolute top-0 left-0 text-brand-light-gray -translate-x-1/2 -translate-y-1/4',
  "top_left" => 'absolute top-0 left-0 text-brand-light-gray -translate-x-1/2 -translate-y-1/4',
  "top_right" => 'absolute top-0 right-0 text-brand-light-gray translate-x-1/2 -translate-y-1/4',
  "bottom_left" => 'absolute bottom-0 left-0 text-brand-light-gray -translate-x-1/2 translate-y-1/4',
  "bottom_right" => 'absolute bottom-0 right-0 text-brand-light-gray translate-x-1/2 translate-y-1/4',
];

$position_class = $position_classes[$position] ?? 'absolute top-0 left-0 text-brand-light-gray -translate-x-1/2 -translate-y-1/4';

$position_location = in_array($position, ['default', 'top_left', 'top_right']) ? 'top' : (in_array($position, ['bottom_left', 'bottom_right']) ? 'bottom' : '');

if ($shape !== 'none' && $location == $position_location) :
?>

  <div class="container mx-auto max-w-screen-2xl <?= $class ?>">
    <div class="relative mx-auto h-0 z-0">
      <div class="<?= $position_class ?>" style="<?= $style ?>">
        <?php if ($shape == 'logo') : ?>
          <?= spc_svg(['svg' => 'spc', 'group' => 'shapes', 'size' => false, 'class' => 'w-[180px] xl:w-[480px] h-auto']) ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

<?php endif;
