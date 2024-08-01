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

    <script type="text/javascript">
      jQuery(function($) {
        new Swiper('#<?php echo $testimonial_id ?> .swiper', {
          slidesPerView: 2,
          spaceBetween: 0,
          //loop: true,
          loop: false,
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
          // navigation: {
          //   nextEl: '.section-hero .swiper-btn-next',
          //   prevEl: '.section-hero .swiper-btn-prev',
          // },
        });
      });
    </script>
  </div>
<?php }
