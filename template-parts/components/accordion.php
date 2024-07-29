<?php

// Extracting field and class from args with null coalescing operator
$field = $args['field'] ?? '';
$class = $args['class'] ?? '';

// Retrieving numbered list component data
$accordion_comp = is_array($field) ? $field : get_sub_field($field ?: 'accordion');

// Extracting numbered list repeater and settings
$accordion_repeater = $accordion_comp['accordion'] ?? [];
$more_settings = $accordion_comp['settings']['more_settings'] ?? [];

// Extracting circle size, color, and text color
// $circle_size = $more_settings['circle_size'] ?? '';
// $circle_color = $more_settings['circle_color'] ?? '';
// $text_color = $more_settings['text_color'] ?? '';

// Initializing default styles and classes
// $circle_class = 'w-24 h-24 text-4xl';
// $text_class = 'pb-10';
// $circle_style = '';
// $border_style = '';
// $text_style = '';

// Updating styles and classes based on settings
// switch ($circle_size) {
//   case "small":
//     $circle_class = 'w-20 h-20 text-3xl mb-8';
//     $text_class = 'pb-8';
//     break;
//   case "large":
//     $circle_class = 'w-28 h-28 text-5xl mb-12';
//     $text_class = 'pb-12';
//     break;
// }

// if ($circle_color) {
//   $circle_style = 'background-color: ' . $circle_color . ';';
//   $border_style = 'border-color: ' . $circle_color . ';';
// }

// if ($text_color) {
//   $text_style = 'color:' . $text_color . ';';
// }

$uniqid = uniqid();
$accordion_id = 'accordion-' . $uniqid;

// Outputting accordion if repeater exists
if ($accordion_repeater) { ?>
  <div id="<?php echo $accordion_id ?>" class="relative pb-20">
    <?php
    foreach ($accordion_repeater as $accordion) :
      $title = $accordion['title'] ?? '';
      $content = $accordion['content'] ?? '';
    ?>
      <div class="collapse collapse-plus bg-brand-light-gray rounded-md lg:rounded-lg border border-solid border-slate-300 shadow-md mb-6">
        <input type="checkbox" class="accordion-btn w-full h-full block" name="<?php echo $accordion_id ?>" />
        <div class="collapse-title bg-white text-lg lg:text-2xl text-left border-t-0 border-x-0 border-b border-solid border-slate-300 font-medium py-3 pl-4 pr-8 lg:py-5 lg:pl-8 lg:pr-12 after:font-thin after:!end-8 after:text-brand-tomato after:!top-2 after:text-3xl after:lg:text-5xl">
          <?php echo $title ?>
        </div>
        <div class="collapse-content p-0">
          <div class="p-4 lg:p-8">
            <div class="prose lg:prose-lg max-w-none text-left">
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
