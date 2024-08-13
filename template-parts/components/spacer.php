<?php

$field = $args['field'] ?? '';
$class = $args['class'] ?? '';

$spacer = $field && is_array($field) ? $field : get_sub_field($field ?: 'spacer');
$spacer = $spacer['spacer'] ?? '';

$spacer_height = $spacer['spacer_height'] ?? '';
$spacer_mobile = $spacer_height['mobile'] ?? '';
$spacer_tablet = $spacer_height['tablet'] ?? '';
$spacer_laptop = $spacer_height['laptop'] ?? '';
$spacer_desktop = $spacer_height['desktop'] ?? '';

//preint_r($spacer);

$uniqid = uniqid();
$spacer_id = 'spacer-' . $uniqid;

$spacer_style = '';

if ($spacer_desktop) {
  echo '<div id="' . $spacer_id . '"></div>';
  echo '<style>';
  if ($spacer_mobile) {
    echo '#' . $spacer_id . ' {';
    echo 'height: ' . $spacer_mobile . 'px;';
    echo '}';
  }
  if ($spacer_tablet) {
    echo '@media (min-width: 768px) {';
    echo '#' . $spacer_id . ' {';
    echo 'height: ' . $spacer_tablet . 'px;';
    echo '}';
    echo '}';
  }
  if ($spacer_laptop) {
    echo '@media (min-width: 1280px) {';
    echo '#' . $spacer_id . ' {';
    echo 'height: ' . $spacer_laptop . 'px;';
    echo '}';
    echo '}';
  }
  if ($spacer_desktop) {
    echo '@media (min-width: 1536px) {';
    echo '#' . $spacer_id . ' {';
    echo 'height: ' . $spacer_desktop . 'px;';
    echo '}';
    echo '}';
  }
  echo '</style>';
}
