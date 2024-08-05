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
    <div class="swiper-arrows-container absolute inset-0">
      <div class="container max-w-screen-2xl relative h-full">
        <button type="button" class="swiper-btn-prev absolute z-10 left-0 xl:-left-16 top-2 lg:top-1/2 -translate-y-1/2 w-8 h-8 xl:w-8 xl:h-8 flex items-center justify-center text-slate-300 hover:text-brand-blue transition-all duration-200">
          <?php echo spc_icon(array('icon' => 'chevron-left', 'group' => 'utilities', 'size' => '96', 'class' => 'w-8 h-8')); ?>
        </button>
        <button type="button" class="swiper-btn-next absolute z-10 right-0 xl:-right-16 top-2 lg:top-1/2 -translate-y-1/2 w-8 h-8 xl:w-8 xl:h-8 flex items-center justify-center text-slate-300 hover:text-brand-blue transition-all duration-200">
          <?php echo spc_icon(array('icon' => 'chevron-right', 'group' => 'utilities', 'size' => '96', 'class' => 'w-8 h-8')); ?>
        </button>
      </div>
    </div>
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
          el: "#<?php echo $imageslider_id ?> .swiper-pagination",
          clickable: true,
        },
        navigation: {
          nextEl: '#<?php echo $imageslider_id ?> .swiper-btn-next',
          prevEl: '#<?php echo $imageslider_id ?> .swiper-btn-prev',
        },
      });
    });
  </script>
<?php }
