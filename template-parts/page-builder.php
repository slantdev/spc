<?php
$term_id = '';
if (is_tax()) {
  $term_id = ($queried_object = get_queried_object()) ? $queried_object->term_id : null;
}
$the_id = $term_id ? 'term_' . $term_id : get_the_ID();

if (have_rows('section', $the_id)) :

  // Loop through rows.
  while (have_rows('section', $the_id)) : the_row();

    if (get_row_layout() == 'hero_slider') :
      get_template_part('template-parts/sections/hero_slider');

    elseif (get_row_layout() == 'one_column') :
      get_template_part('template-parts/sections/one_column');

    elseif (get_row_layout() == 'two_columns') :
      get_template_part('template-parts/sections/two_columns');

    endif;

  // End loop.
  endwhile;

// No value.
else :
// Do something...
endif;
