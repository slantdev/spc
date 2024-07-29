<?php

// Extracting field and class from args with null coalescing operator
$field = $args['field'] ?? '';
$class = $args['class'] ?? '';

// Retrieving numbered list component data
$imageslider_comp = is_array($field) ? $field : get_sub_field($field ?: 'images');

// Extracting numbered list repeater and settings
$imageslider_repeater = $imageslider_comp['images'] ?? [];
$more_settings = $imageslider_comp['settings']['more_settings'] ?? [];

//preint_r($imageslider_comp);

// Extracting circle size, color, and text color
$autoplay_delay = $more_settings['autoplay_delay'] ?? '';
$autoplay_delay_setting = '8000';
if ($autoplay_delay) {
  $autoplay_delay_setting = $autoplay_delay;
}
$loop = $more_settings['loop'] ?? '';
$loop_setting = 'false';
if ($loop) {
  $loop_setting = 'true';
}
$pagination_default = $more_settings['pagination_default'] ?? '';
$pagination_active = $more_settings['pagination_active'] ?? '';
$pagination_style = '';
if ($pagination_default) {
  $pagination_style .= '--swiper-pagination-bullet-inactive-color: ' . $pagination_default . ';';
}
if ($pagination_active) {
  $pagination_style .= '--swiper-pagination-color: ' . $pagination_active . ';';
}


$uniqid = uniqid();
$imageslider_id = 'imageslider-' . $uniqid;

// Outputting image slider if repeater exists
if ($imageslider_repeater) { ?>
  <div id="<?php echo $imageslider_id ?>" class="relative">
    <div class="swiper">
      <div class="swiper-wrapper">
        <?php
        foreach ($imageslider_repeater as $image) :
          $image_url = $image['url'] ?? '';
          $image_alt = $image['alt'] ?? '';
        ?>
          <div class="swiper-slide relative">
            <div class="rounded-lg overflow-clip">
              <div class="aspect-w-16 aspect-h-9">
                <img src="<?php echo $image_url ?>" alt="<?php echo $image_alt ?>" class="w-full h-full object-cover">
              </div>
            </div>
          </div>
        <?php endforeach ?>
      </div>
    </div>
    <div class="swiper-pagination !static mt-6 [&>.swiper-pagination-bullet]:rounded-md" style="--swiper-pagination-bullet-width: 60px;--swiper-pagination-bullet-height: 6px;<?php echo $pagination_style ?>"></div>
  </div>
  <script type="text/javascript">
    jQuery(function($) {
      new Swiper('#<?php echo $imageslider_id ?> .swiper', {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: <?php echo $loop_setting ?>,
        speed: 500,
        watchOverflow: true,
        effect: 'fade',
        fadeEffect: {
          crossFade: true
        },
        autoplay: {
          delay: <?php echo $autoplay_delay_setting ?>,
        },
        pagination: {
          el: ".swiper-pagination",
        },
      });
    });
  </script>
<?php }
