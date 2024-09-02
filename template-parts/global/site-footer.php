<?php
$footer_style = get_field('footer_style', 'option');
$footer_top = $footer_style['footer_top'] ?? '';
$footer_top_bg_color = $footer_top['background_color'] ?? '';
$footer_top_text_color = $footer_top['text_color'] ?? '';
$footer_top_style = '';
if ($footer_top_bg_color) {
  $footer_top_style .= 'background-color: ' . $footer_top_bg_color . ';';
}
if ($footer_top_text_color) {
  $footer_top_style .= 'color: ' . $footer_top_text_color . ';';
}

$footer_bottom = $footer_style['footer_bottom'] ?? '';
$footer_bottom_bg_color = $footer_bottom['background_color'] ?? '';
$footer_bottom_text_color = $footer_bottom['text_color'] ?? '';
$footer_bottom_style = '';
if ($footer_bottom_bg_color) {
  $footer_bottom_style .= 'background-color: ' . $footer_bottom_bg_color . ';';
}
if ($footer_bottom_text_color) {
  $footer_bottom_style .= 'color: ' . $footer_bottom_text_color . ';';
}

$footer_links = get_field('footer_links', 'option');

$footer_links_1 = $footer_links['footer_links_1'] ?? '';
$footer_links_1_heading = $footer_links_1['heading'] ?? '';
$footer_links_1_links = $footer_links_1['links'] ?? ''; // Repeater

$footer_links_2 = $footer_links['footer_links_2'] ?? '';
$footer_links_2_heading = $footer_links_2['heading'] ?? '';
$footer_links_2_links = $footer_links_2['links'] ?? ''; // Repeater

$footer_links_3 = $footer_links['footer_links_3'] ?? '';
$footer_links_3_heading = $footer_links_3['heading'] ?? '';
$footer_links_3_links = $footer_links_3['links'] ?? ''; // Repeater

$social_media = $footer_links['social_media'] ?? '';
$social_media_heading = $social_media['heading'] ?? '';
$social_media_links = $social_media['social_media_links'] ?? ''; // Repeater

$footer_about = get_field('footer_about', 'option');
$footer_logo = $footer_about['logo'] ?? '';
$footer_address = $footer_about['address'] ?? '';

$copyright_info = get_field('copyright_info', 'option');
$copyright_site_name = $copyright_info['copyright_site_name'] ?? '';
$copyright_links = $copyright_info['copyright_links'] ?? '';


$subscribe = get_field('subscribe', 'option')['subscribe'] ?? '';
$subscribe_heading = $subscribe['heading'] ?? '';
$subscribe_desciption = $subscribe['description'] ?? '';
$subscribe_form_shortcode = $subscribe['form_shortcode'] ?? '';
$subscribe_colors = $subscribe['subscribe_colors'] ?? '';
$subscribe_background_color = $subscribe_colors['background_color'] ?? '';
$subscribe_text_color = $subscribe_colors['text_color'] ?? '';
$subscribe_style = '';
if ($subscribe_background_color) {
  $subscribe_style .= 'background-color: ' . $subscribe_background_color . ';';
}
if ($subscribe_text_color) {
  $subscribe_style .= 'color: ' . $subscribe_text_color . ';';
}

$term_id = '';
if (is_tax()) {
  //preint_r(get_queried_object());
  $term_id = get_queried_object()->term_id;
}
if ($term_id) {
  $the_id = 'term_' . $term_id;
} else {
  $the_id = get_the_ID();
}
$disable_subscribe = get_field('disable_subscribe', $the_id);
$disable_subscribe = true;

?>

<?php
if ($subscribe && !$disable_subscribe) :
  $section_id = 'section-subscribe-' . uniqid();
?>
  <section id="subscribe" class="<?php echo $section_id ?> relative bg-brand-blue print:hidden" style="<?php echo $subscribe_style ?>">
    <div class="relative pt-12 lg:pt-20 xl:pt-36 pb-12 lg:pb-20 xl:pb-36">
      <?php if ($subscribe_heading || $subscribe_desciption) : ?>
        <div class="relative container mx-auto max-w-screen-xl">
          <div class="text-center text-white max-w-prose mx-auto">
            <?php if ($subscribe_heading) : ?>
              <h3 class="mb-4 font-semibold text-3xl xl:text-4xl -tracking-[0.0125em] leading-tight"><?php echo $subscribe_heading ?></h3>
            <?php endif ?>
            <?php if ($subscribe_desciption) : ?>
              <div class="mt-4 text-lg xl:text-xl"><?php echo $subscribe_desciption ?></div>
            <?php endif ?>
          </div>
        </div>
      <?php endif; ?>
      <?php if ($subscribe_form_shortcode) : ?>
        <div class="relative container mx-auto max-w-screen-xl mt-8 xl:mt-16">
          <?php echo do_shortcode($subscribe_form_shortcode) ?>
        </div>
      <?php else : ?>
        <div class="relative container mx-auto max-w-screen-xl mt-8 xl:mt-16">
          <div class="flex flex-col gap-y-2 xl:flex-row xl:gap-x-6">
            <div class="xl:grow">
              <label class="form-control w-full">
                <div class="label">
                  <span class="label-text text-white">First Name</span>
                </div>
                <input type="text" placeholder="" class="input bg-white w-full rounded-full" />
              </label>
            </div>
            <div class="xl:grow">
              <label class="form-control w-full">
                <div class="label">
                  <span class="label-text text-white">Last Name</span>
                </div>
                <input type="text" placeholder="" class="input bg-white w-full rounded-full" />
              </label>
            </div>
            <div class="xl:grow">
              <label class="form-control w-full">
                <div class="label">
                  <span class="label-text text-white">Email Address</span>
                </div>
                <input type="text" placeholder="" class="input bg-white w-full rounded-full" />
              </label>
            </div>
            <div class="flex-none">
              <label class="form-control w-full">
                <div class="label">
                  <span class="label-text text-white">&nbsp;</span>
                </div>
                <button type="button" class="btn btn-primary text-lg rounded-full px-16 hover:brightness-110 hover:shadow-lg transition duration-300">Submit</button>
              </label>

            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </section>
<?php endif; ?>

<footer class="print:hidden">
  <div class="bg-brand-dark-blue py-12 xl:py-24 text-white" style="<?php echo $footer_top_style ?>">
    <div class="container max-w-screen-2xl">
      <div class="flex flex-col gap-y-6 xl:flex-row xl:gap-x-20">
        <div class="w-full xl:w-3/4">
          <div class="flex flex-col gap-y-8 pt-8 xl:pt-0 xl:flex-row xl:gap-x-10">
            <?php if ($footer_links_1) : ?>
              <div class="w-full xl:w-1/3">
                <?php if ($footer_links_1_heading) {
                  echo '<h4 class="text-lg font-bold mb-6">' . $footer_links_1_heading . '</h4>';
                } ?>
                <ul class="flex flex-col gap-y-4">
                  <?php foreach ($footer_links_1_links as $link) {
                    $link_url = $link['link']['url'] ?? '#';
                    $link_target = $link['link']['target'] ?? '_self';
                    $link_title = $link['link']['title'] ?? '';
                    echo '<li><a href="' . $link_url . '" target="' . $link_target . '" class="hover:underline opacity-60 hover:opacity-100">' . $link_title . '</a></li>';
                  } ?>
                </ul>
              </div>
            <?php endif; ?>
            <?php if ($footer_links_2) : ?>
              <div class="w-full xl:w-1/3">
                <?php if ($footer_links_2_heading) {
                  echo '<h4 class="text-lg font-bold mb-6">' . $footer_links_2_heading . '</h4>';
                } ?>
                <ul class="flex flex-col gap-y-4">
                  <?php foreach ($footer_links_2_links as $link) {
                    $link_url = $link['link']['url'] ?? '#';
                    $link_target = $link['link']['target'] ?? '_self';
                    $link_title = $link['link']['title'] ?? '';
                    echo '<li><a href="' . $link_url . '" target="' . $link_target . '" class="hover:underline opacity-60 hover:opacity-100">' . $link_title . '</a></li>';
                  } ?>
                </ul>
              </div>
            <?php endif; ?>
            <?php if ($footer_links_3) : ?>
              <div class="w-full xl:w-1/3">
                <?php if ($footer_links_3_heading) {
                  echo '<h4 class="text-lg font-bold mb-6">' . $footer_links_3_heading . '</h4>';
                } ?>
                <ul class="flex flex-col gap-y-4">
                  <?php foreach ($footer_links_3_links as $link) {
                    $link_url = $link['link']['url'] ?? '#';
                    $link_target = $link['link']['target'] ?? '_self';
                    $link_title = $link['link']['title'] ?? '';
                    echo '<li><a href="' . $link_url . '" target="' . $link_target . '" class="hover:underline opacity-60 hover:opacity-100">' . $link_title . '</a></li>';
                  } ?>
                </ul>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <div class="w-full xl:w-1/4">
          <?php if ($social_media_links) : ?>
            <div class="mb-8">
              <h4 class="text-lg font-bold mb-6"><?php echo $social_media_heading; ?></h4>
              <div>Social Icons</div>
            </div>
          <?php endif ?>
          <?php if ($footer_address) : ?>
            <div class="mb-8">
              <h4 class="text-lg font-bold mb-6">Contact Us</h4>
              <div class="flex flex-col gap-y-0.5 text-sm xl:text-base">
                <?php echo '<div class="opacity-60">' . $footer_address . '</div>'; ?>
              </div>
            </div>
          <?php endif ?>
        </div>
      </div>
    </div>
  </div>
  <div class="bg-brand-medium-blue text-white py-6 xl:py-4" style="<?php echo $footer_bottom_style ?>">
    <div class="container max-w-screen-2xl">
      <div class="flex flex-col gap-y-1 xl:flex-row xl:gap-x-20 xl:justify-between xl:items-center">
        <div class="w-full mb-4 md:mb-0">
          <div class="flex flex-col gap-y-1 md:flex-row md:gap-x-20">
            <?php if ($copyright_site_name) : ?>
              <span class="text-sm opacity-70"><?php echo $copyright_site_name ?></span>
            <?php endif; ?>
            <?php if ($copyright_links) : ?>
              <div class="flex flex-col gap-y-1 xl:flex-row xl:gap-x-8 text-sm">
                <?php
                foreach ($copyright_links as $link) :
                  $link_url = $link['link']['url'] ?? '';
                  $link_title = $link['link']['title'] ?? '';
                ?>
                  <a href="<?php echo $link_url ?>" class="text-sm hover:underline opacity-70"><?php echo $link_title ?></a>
                <?php endforeach ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <?php
        if ($footer_logo) :
          $logo_url = $footer_logo['url'] ?? '';
          $logo_alt = $footer_logo['alt'] ?? '';
        ?>
          <div class="w-full flex lg:justify-end">
            <a href="<?php echo site_url() ?>">
              <img src="<?php echo $logo_url ?>" alt="<?php echo $logo_alt ?>" class="w-64 h-auto xl:!h-14 xl:!w-auto">
            </a>
          </div>
        <?php endif ?>
      </div>
    </div>
  </div>
</footer>