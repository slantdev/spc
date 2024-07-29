<?php

// Extracting field from args with null coalescing operator
$field = $args['field'] ?? 'components';

// Outputting components if field is set
if ($field) {
  foreach ($field as $layout) {
    $acf_fc_layout = $layout['acf_fc_layout'] ?? '';
    // Outputting component template if layout is set
    if ($acf_fc_layout) {
      $template = 'template-parts/components/' . $acf_fc_layout;
      echo '<div class="mb-8">';
      get_template_part($template, '', array('field' => $layout));
      echo '</div>';
    }
  }
}
