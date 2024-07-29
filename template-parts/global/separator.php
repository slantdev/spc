<?php

$location = $args['location'] ?? '';
$active = $args['active'] ?? '';
$color = $args['color'] ?? '';
$class = $args['class'] ?? '';

$style = ($color) ? 'color: ' . $color . ';' : '';

if ($location == 'top' && $active) :
  echo '<div class="section-separator-top absolute h-8 lg:h-12 w-px top-0 left-1/2 border-l border-solid border-slate-300" style="' . $style . '"></div>';
endif;

if ($location == 'bottom' && $active) :
  echo '<div class="section-separator-bottom absolute h-8 lg:h-12 w-px bottom-0 left-1/2 border-l border-solid border-slate-300" style="' . $style . '"></div>';
endif;
