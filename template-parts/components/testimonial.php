<?php

// Extracting field and class from args with null coalescing operator
$field = $args['field'] ?? '';
$class = $args['class'] ?? '';

// Retrieving \component data
$testimonial_comp = is_array($field) ? $field : get_sub_field($field ?: 'testimonial');

// Extracting repeater and settings
$testimonial_repeater = $testimonial_comp['testimonial'] ?? [];
$more_settings = $testimonial_comp['settings']['more_settings'] ?? [];
$text_color = $more_settings['text_color'] ?? '';
$line_color = $more_settings['line_color'] ?? '';
$columns = $more_settings['columns'] ?? '';

$text_style = '';
$line_style = '';
if ($text_color) {
  $text_style = 'color:' . $text_color . ';';
}
if ($line_color) {
  $line_style = 'border-color:' . $line_color . ';';
}

//preint_r($more_settings);

$uniqid = uniqid();
$testimonial_id = 'testimonial-' . $uniqid;

// Outputting testimonial if repeater exists
if ($testimonial_repeater) { ?>
  <div id="<?php echo $testimonial_id ?>" class="relative pb-20">
    <div class="swiper">
      <div class="swiper-wrapper">
        <?php
        foreach ($testimonial_repeater as $testimonial) :
          $name = $testimonial['name'] ?? '';
          $designation = $testimonial['designation'] ?? '';
          $content = $testimonial['content'] ?? '';
        ?>
          <div class="swiper-slide relative" style="<?php echo $text_style ?>">
            <h4 class="font-medium text-2xl text-left mb-2"><?php echo $name ?></h4>
            <h5 class="text-sm text-left"><?php echo $designation ?></h5>
            <div class="border-b border-solid border-gray-200 my-4" style="<?php echo $line_style ?>"></div>
            <div class="text-left"><?php echo $content ?></div>
          </div>
        <?php endforeach ?>
      </div>
    </div>
    <div class="swiper-arrows-container absolute inset-0">
      <div class="container max-w-screen-2xl relative h-full">
        <button type="button" class="swiper-btn-prev absolute z-10 left-0 xl:-left-32 top-2 lg:top-1/2 -translate-y-1/2 w-8 h-8 xl:w-8 xl:h-8 flex items-center justify-center text-slate-300 hover:text-brand-blue transition-all duration-200">
          <?php echo spc_icon(array('icon' => 'chevron-left', 'group' => 'utilities', 'size' => '96', 'class' => 'w-10 h-10')); ?>
        </button>
        <button type="button" class="swiper-btn-next absolute z-10 right-0 xl:-right-32 top-2 lg:top-1/2 -translate-y-1/2 w-8 h-8 xl:w-8 xl:h-8 flex items-center justify-center text-slate-300 hover:text-brand-blue transition-all duration-200">
          <?php echo spc_icon(array('icon' => 'chevron-right', 'group' => 'utilities', 'size' => '96', 'class' => 'w-8 h-8')); ?>
        </button>
      </div>
    </div>
    <script type="text/javascript">
      jQuery(function($) {
        new Swiper('#<?php echo $testimonial_id ?> .swiper', {
          slidesPerView: 2,
          spaceBetween: 0,
          loop: true,
          //loop: false,
          speed: 500,
          watchOverflow: true,
          // effect: 'fade',
          // fadeEffect: {
          //   crossFade: true
          // },
          autoplay: {
            delay: 8000,
          },
          spaceBetween: 80,
          navigation: {
            nextEl: '#<?php echo $testimonial_id ?> .swiper-btn-next',
            prevEl: '#<?php echo $testimonial_id ?> .swiper-btn-prev',
          },
        });
      });
    </script>
  </div>
<?php }
