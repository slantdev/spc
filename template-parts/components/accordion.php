<?php

// Extracting field and class from args with null coalescing operator
$field = $args['field'] ?? '';
$class = $args['class'] ?? '';

// Retrieving \component data
$accordion_comp = is_array($field) ? $field : get_sub_field($field ?: 'accordion');

// Extracting repeater and settings
$accordion_repeater = $accordion_comp['accordion'] ?? [];
$more_settings = $accordion_comp['settings']['more_settings'] ?? [];

$uniqid = uniqid();
$accordion_id = 'accordion-' . $uniqid;

// $primary_color = get_field('primary_color', 'option');
// if ($primary_color) {
//   echo '<style>';
//   echo '.collapse-title { color: ' . $primary_color . '; }';
//   echo '.collapse-title:after { color: ' . $primary_color . '; }';
//   echo '</style>';
// }

// Outputting accordion if repeater exists
if ($accordion_repeater) { ?>
  <div id="<?php echo $accordion_id ?>" class="relative pb-20">
    <?php
    foreach ($accordion_repeater as $accordion) :
      $title = $accordion['title'] ?? '';
      $content = $accordion['content'] ?? '';
    ?>
      <div class="collapse collapse-plus bg-brand-light-gray rounded-md mb-6">
        <input type="checkbox" class="accordion-btn w-full h-full block" name="<?php echo $accordion_id ?>" />
        <div class="collapse-title bg-[#E2E2E2] text-black text-lg lg:text-2xl text-left border-t-0 border-x-0 font-medium py-3 pl-4 pr-8 lg:py-5 lg:pl-8 lg:pr-12 after:font-thin after:!end-8 after:text-black after:!top-2 after:text-3xl after:lg:text-5xl">
          <?php echo $title ?>
        </div>
        <div class="collapse-content p-0">
          <div class="p-4 lg:p-8">
            <div class="prose max-w-none text-left">
              <?php echo $content ?>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach ?>
  </div>
  <script type="text/javascript">
    jQuery(document).ready(function($) {

      $(document).on(
        'click',
        '#<?php echo $accordion_id ?> .accordion-btn',
        function() {
          setTimeout(() => {
            $('html, body').animate({
              scrollTop: $(this).offset().top - 100
            }, 200);
          }, 400);
        }
      );

    });
  </script>
<?php }
