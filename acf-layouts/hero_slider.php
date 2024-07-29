<?php
/*
 * https://www.acf-extended.com/features/fields/flexible-content
 *
 * @array   $layout Layout settings (without values)
 * @array   $field Flexible content field settings
 * @bool    $is_preview True during AJAX preview
 */

?>
<div class="acf-layout <?php echo ($is_preview) ? 'is-preview' : ''; ?>">
  <?php
  include get_template_directory() . '/template-parts/sections/hero_slider.php';
  ?>
</div>