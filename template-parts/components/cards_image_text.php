<?php

// Extracting field and class from args with null coalescing operator
$field = $args['field'] ?? '';
$class = $args['class'] ?? '';

// Retrieving numbered list component data
$card_comp = is_array($field) ? $field : get_sub_field($field ?: 'cards_image_text');

// Extracting numbered list repeater and settings
$card_repeater = $card_comp['cards_image_text'] ?? [];
$more_settings = $card_comp['settings']['more_settings'] ?? [];

$card_css_style = '';
$icon_css_style = '';
$title_css_style = '';
$text_css_style = '';
$link_css_style = '';

$card_style = $more_settings['card_style'] ?? 'boxed';
$grid_columns = $more_settings['grid_columns'] ?? '3';
$background_color = $more_settings['background_color'] ?? '';
if ($background_color) {
  $card_css_style .= 'background-color: ' . $background_color . ';';
}
$title_color = $more_settings['title_color'] ?? '';
if ($title_color) {
  $title_css_style .= 'color: ' . $title_color . ';';
}
$text_color = $more_settings['text_color'] ?? '';
if ($text_color) {
  $text_css_style .= 'color: ' . $text_color . ';';
}
$icon_color = $more_settings['icon_color'] ?? '';
$icon_bg_color = $more_settings['icon_bg_color'] ?? '';
if ($icon_bg_color) {
  $icon_css_style .= 'background-color: ' . $icon_bg_color . ';';
}
$text_alignment = $more_settings['text_alignment'] ?? '';
if ($text_alignment) {
  $card_css_style .= 'text-align: ' . $text_alignment . ';';
}
$link_color = $more_settings['link_color'] ?? '';
if ($link_color) {
  $link_css_style .= 'color: ' . $link_color . ';';
}


//preint_r($card_repeater);

$uniqid = uniqid();
$card_id = 'card-' . $uniqid;

// Outputting card if repeater exists
if ($card_repeater) { ?>
  <div id="<?php echo $card_id ?>" class="relative">
    <div class="grid grid-cols-1 xl:grid-cols-<?php echo $grid_columns ?> gap-8 xl:gap-12">
      <?php
      foreach ($card_repeater as $card) :
        $image_or_icon = $card['image_or_icon'] ?? [];
        $use_image_or_icon = $image_or_icon['use_image_or_icon'] ?? '';
        $image = $image_or_icon['image'] ?? '';
        $icon = $image_or_icon['icon'] ?? '';
        $icon_url = $image_or_icon['icon']['url'] ?? '';
        $image_url = $image['url'] ?? '';
        $image_alt = $image['image']['alt'] ?? '';
        $title = $card['title'] ?? '';
        $text = $card['text'] ?? '';
        $link_url = $card['link']['url'] ?? '';
        $link_title = $card['link']['title'] ?? '';
        $link_target = $card['link']['target'] ?? '';
        //preint_r($image_or_icon);
      ?>
        <?php if ($card_style == 'boxed') : ?>
          <div class="shadow-lg bg-stone-100 rounded-lg overflow-clip text-left" style="<?php echo $card_css_style ?>">
            <?php if ($use_image_or_icon == 'image' && $image_url) : ?>
              <div class="aspect-w-16 aspect-h-9">
                <img src="<?php echo $image_url ?>" alt="<?php echo $image_alt ?>" class="w-full h-full object-cover">
              </div>
            <?php elseif ($use_image_or_icon == 'icon' && $icon_url) : ?>
              <div class="pt-4 px-4 xl:pt-8 xl:px-6">
                <div class="p-6 rounded-full inline-block" style="<?php echo $icon_css_style ?>">
                  <img src="<?php echo $icon_url ?>" alt="" class="w-auto h-[70px]">
                </div>
              </div>
            <?php else : $use_image_or_icon == 'image' && $image_url ?>
              <div class="aspect-w-16 aspect-h-9">
                <div class="bg-slate-200 w-full h-full"></div>
              </div>
            <?php endif ?>
            <div class="p-4 xl:p-6">
              <?php if ($title) : ?>
                <h4 class="text-lg font-bold mb-4" style="<?php echo $title_css_style ?>"><?php echo $title ?></h4>
              <?php endif ?>
              <?php if ($text) : ?>
                <div class="text-[15px] leading-normal" style="<?php echo $text_css_style ?>"><?php echo $text ?></div>
              <?php endif ?>
              <?php if ($link_url) : ?>
                <div class="mt-4">
                  <a href="<?php echo $link_url ?>" target="<?php echo $link_target ?>" class="text-[15px] font-medium underline" style="<?php echo $link_css_style ?>"><?php echo $link_title ?></a>
                </div>
              <?php endif; ?>
            </div>
          </div>
        <?php else : ?>
          <div class="text-left">
            <?php if ($use_image_or_icon == 'image' && $image_url) : ?>
              <div class="rounded-lg overflow-clip">
                <div class="aspect-w-16 aspect-h-8">
                  <img src="<?php echo $image_url ?>" alt="<?php echo $image_alt ?>" class="w-full h-full object-cover">
                </div>
              </div>
            <?php elseif ($use_image_or_icon == 'icon' && $icon_url) : ?>
              <div class="pt-4">
                <div class="p-6 rounded-full inline-block" style="<?php echo $icon_css_style ?>">
                  <img src="<?php echo $icon_url ?>" alt="" class="w-auto h-[70px]">
                </div>
              </div>
            <?php else : $use_image_or_icon == 'image' && $image_url ?>
              <div class="rounded-lg overflow-clip">
                <div class="aspect-w-16 aspect-h-8">
                  <div class="bg-slate-200 w-full h-full"></div>
                </div>
              </div>
            <?php endif ?>
            <div class="py-4 xl:py-6">
              <?php if ($title) : ?>
                <h4 class="text-lg font-bold mb-4" style="<?php echo $title_css_style ?>"><?php echo $title ?></h4>
              <?php endif ?>
              <?php if ($text) : ?>
                <div class="text-[15px] leading-normal" style="<?php echo $text_css_style ?>"><?php echo $text ?></div>
              <?php endif ?>
              <?php if ($link_url) : ?>
                <div class="mt-4">
                  <a href="<?php echo $link_url ?>" target="<?php echo $link_target ?>" class="text-[15px] font-medium underline" style="<?php echo $link_css_style ?>"><?php echo $link_title ?></a>
                </div>
              <?php endif; ?>
            </div>
          </div>
        <?php endif ?>
      <?php endforeach ?>
    </div>
  </div>
<?php }
