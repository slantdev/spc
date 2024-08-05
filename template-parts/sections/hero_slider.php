<?php
include get_template_directory() . '/template-parts/global/section_settings.php';

// Set section ID attribute
$section_id_attr = $section_id ? 'id="' . $section_id . '"' : '';

// Retrieve hero slider and stats repeater
$hero_slider = get_sub_field('hero_slider') ?: [];
$hero_slider_repeater = $hero_slider['hero_slider'] ?? '';

?>
<section <?php echo $section_id_attr ?> class="section-hero relative" style="<?php echo $section_style ?>">
  <?php if ($hero_slider_repeater) : ?>
    <div class="swiper">
      <div class="swiper-wrapper">
        <?php foreach ($hero_slider_repeater as $hero) : ?>
          <?php
          $headline = $hero['headline'] ?? [];
          $headline_text = $headline['heading']['heading_text'] ?? '';
          $pre_headline = $hero['pre_headline'] ?? '';
          $pre_headline_text = $pre_headline['heading']['heading_text'] ?? '';
          //preint_r($pre_headline);
          $text_area = $hero['text_area']['text_area'] ?? '';
          $background = $hero['background'] ?? '';
          $background_image = $background['background_image'] ?? '';
          $background_overlay = $background['background_overlay'] ?? '';
          $background_position = $background['background_position'] ?? '';
          $background_class = $background_position ? 'object-' . $background_position : '';
          $buttons_repeater = $hero['buttons_repeater'] ?? '';
          ?>
          <div class="swiper-slide relative">
            <?php if ($background_image) : ?>
              <div class="absolute inset-0 -z-1">
                <img class="object-cover w-full h-full <?php echo $background_class ?>" src="<?php echo $background_image['url'] ?>" alt="<?php echo $background_image['alt'] ?>">
                <?php if ($background_overlay) : ?>
                  <div class="absolute inset-0 -z-1 text-4xl" style="background-color: <?php echo $background_overlay ?>;"></div>
                <?php endif ?>
              </div>
            <?php endif ?>
            <div class="relative z-10 min-h-[620px] flex flex-col justify-center">
              <div class="relative z-10 container max-w-screen-2xl">
                <div class="flex flex-col xl:flex-row xl:gap-x-20 pt-24 pb-12 xl:py-16 xl:items-end">
                  <div class="hero-title-container w-full xl:w-1/2">
                    <?php
                    if ($pre_headline_text) {
                      get_template_part('template-parts/components/heading', '', array('field' => $pre_headline, 'align' => 'text-left', 'size' => 'text-lg xl:text-lg mb-4 xl:mb-4', 'weight' => 'font-bold', 'leading' => 'leading-[1.1em] xl:leading-[1.1em]', 'class' => 'hero-pre-heading'));
                    }
                    ?>
                    <?php
                    if ($headline_text) {
                      get_template_part('template-parts/components/heading', '', array('field' => $headline, 'align' => 'text-left', 'size' => 'text-3xl xl:text-4xl font-bold mb-4 xl:mb-0', 'weight' => 'font-bold',  'leading' => 'leading-[1.1em] xl:leading-[1.1em]', 'class' => 'hero-heading'));
                    }
                    ?>
                    <?php
                    if ($buttons_repeater) {
                      echo '<div class="mt-8">';
                      get_template_part('template-parts/components/buttons', '', array('field' => $hero));
                      echo '</div>';
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach ?>
      </div>
      <div class="swiper-arrows-container absolute inset-0">
        <div class="container max-w-screen-2xl relative h-full">
          <button type="button" class="swiper-btn-prev absolute z-10 left-0 xl:-left-32 top-2 lg:top-1/2 -translate-y-1/2 w-9 h-9 xl:w-10 xl:h-10 flex items-center justify-center text-slate-300 hover:text-brand-blue transition-all duration-200">
            <?php echo spc_icon(array('icon' => 'chevron-left', 'group' => 'utilities', 'size' => '96', 'class' => 'w-10 h-10')); ?>
          </button>
          <button type="button" class="swiper-btn-next absolute z-10 right-0 xl:-right-32 top-2 lg:top-1/2 -translate-y-1/2 w-9 h-9 xl:w-10 xl:h-10 flex items-center justify-center text-slate-300 hover:text-brand-blue transition-all duration-200">
            <?php echo spc_icon(array('icon' => 'chevron-right', 'group' => 'utilities', 'size' => '96', 'class' => 'w-10 h-10')); ?>
          </button>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      jQuery(function($) {
        new Swiper('.section-hero .swiper', {
          slidesPerView: 1,
          spaceBetween: 0,
          loop: true,
          //loop: false,
          speed: 500,
          watchOverflow: true,
          effect: 'fade',
          fadeEffect: {
            crossFade: true
          },
          autoplay: {
            delay: 8000,
          },
          navigation: {
            nextEl: '.section-hero .swiper-btn-next',
            prevEl: '.section-hero .swiper-btn-prev',
          },
        });
      });
    </script>
  <?php endif ?>
</section>