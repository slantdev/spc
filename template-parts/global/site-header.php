<?php
$top_navigation = get_field('top_navigation', 'option')['top_navigation'] ?? '';
$top_links = $top_navigation['top_links'] ?? '';
// $social_links = $top_navigation['social_links'] ?? '';
$countries_links = $top_navigation['countries_links'] ?? '';
?>
<div class="top-header hidden xl:block relative z-50 bg-brand-dark-blue py-3 print:hidden">
  <div class="container max-w-screen-2xl">
    <div class="flex items-center justify-end gap-x-4">
      <?php if ($top_links) : ?>
        <ul class="top-nav flex gap-x-6 text-sm 4xl:text-base leading-tight">
          <?php
          foreach ($top_links as $link) :
            $link_url = $link['link']['url'] ?? '';
            $link_title = $link['link']['title'] ?? '';
            $link_target = $link['link']['target'] ?? '_self';
          ?>
            <?php if ($link_url) : ?>
              <li><a href="<?php echo $link_url ?>" target="<?php echo $link_target ?>" class="text-white font-medium hover:opacity-75"><?php echo $link_title ?></a></li>
            <?php endif; ?>
          <?php endforeach ?>
        </ul>
      <?php endif; ?>

      <?php if ($countries_links) : ?>
        <div class="dropdown dropdown-bottom dropdown-end">
          <?php
          $first_country = $countries_links[0];
          $first_country_flag = $first_country['country_flag'];
          if ($first_country_flag == 'australia') {
            $first_country_icon = get_stylesheet_directory_uri() . '/assets/images/icon/au-icon.png';
            $first_country_label = 'AU';
          } else {
            $first_country_icon = get_stylesheet_directory_uri() . '/assets/images/icon/nz-icon.png';
            $first_country_label = 'NZ';
          }
          $first_country_link = $first_country['country_link'] ?? '';
          ?>
          <div tabindex="0" role="button" class="m-1 flex items-center gap-3"><span class="inline-block"><img src="<?php echo $first_country_icon ?>" alt="" class="w-7 h-7 border-white border rounded-full"></span><span class="inline-block text-white font-semibold"><?php echo $first_country_label ?></span><?php echo spc_icon(array('icon' => 'chevron-down', 'group' => 'utilities', 'size' => '16', 'class' => 'text-white w-3 h-3')); ?></div>
          <ul tabindex="0" class="dropdown-content !top-0 menu bg-base-100 rounded-box z-[1] w-auto p-2 shadow">
            <?php
            foreach ($countries_links as $link) :
              $country_flag = $link['country_flag'] ?? '';
              if ($country_flag == 'australia') {
                $country_icon = get_stylesheet_directory_uri() . '/assets/images/icon/au-icon.png';
                $country_label = 'AU';
              } else {
                $country_icon = get_stylesheet_directory_uri() . '/assets/images/icon/nz-icon.png';
                $country_label = 'NZ';
              }
              $country_link = $link['country_link'] ?? '';
            ?>
              <li><a href="<?php echo $country_link ?>" class="flex items-center gap-3 pl-2 pr-4"><span class="inline-block flex-none"><img src="<?php echo $country_icon ?>" alt="" class="w-7 h-7 border-white border rounded-full"></span><span class="inline-block font-semibold"><?php echo $country_label ?></span></a></li>
            <?php endforeach ?>
          </ul>
        </div>
      <?php endif; ?>

    </div>
  </div>
</div>

<?php
$header_logo = get_field('header_logo', 'option');
$site_logo = $header_logo['site_logo']['url'] ?? get_stylesheet_directory_uri() . '/assets/images/logo/logo-spotlight-careers.png';
//preint_r($header_logo);
?>
<header class="fixed z-40 w-full top-0 left-0 xl:static print:hidden">
  <div class="main-header bg-brand-medium-blue ">
    <div class="container max-w-screen-2xl">
      <div class="relative xl:flex xl:justify-between">
        <div class="flex justify-between items-center xl:justify-normal">
          <div class="site-logo py-4 xl:py-6">
            <a href="<?php echo site_url() ?>"><img src="<?php echo $site_logo ?>" alt="<?php echo get_bloginfo('name'); ?>" class="w-64 h-auto xl:!h-16 3xl:!h-16 xl:!w-auto"></a>
          </div>
          <button type="button" aria-label="Toggle navigation" id="primary-menu-toggle" class="menu-open-btn xl:hidden">
            <svg viewBox="0 0 20 20" class="inline-block w-5 h-5 text-white" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
              <g stroke="none" stroke-width="1" fill="currentColor" fill-rule="evenodd">
                <g id="icon-shape">
                  <path d="M0,3 L20,3 L20,5 L0,5 L0,3 Z M0,9 L20,9 L20,11 L0,11 L0,9 Z M0,15 L20,15 L20,17 L0,17 L0,15 Z" id="Combined-Shape"></path>
                </g>
              </g>
            </svg>
          </button>
        </div>
        <div class="hidden xl:flex items-start xl:justify-end py-3 xl:py-6">
          <div id="search-form-container">
            <div class="py-4 flex items-center w-full h-full">
              <div class="flex w-full gap-x-4 items-center">
                <form id="header-searchform" class="relative grow" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                  <input id="header-searchform-input" type="text" class="w-full border-gray-300 shadow-inner !rounded-full bg-white !px-6 !py-2.5 2xl:!py-3 focus:border-brand-light-blue focus:ring-brand-light-blue" name="s" placeholder="Search" value="">
                  <button type="submit" class="absolute right-4 top-3">
                    <?php echo spc_icon(array('icon' => 'search', 'group' => 'utilities', 'size' => '24', 'class' => 'w-5 h-5 2xl:w-6 2xl:h-6')); ?>
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php get_template_part('template-parts/components/mainmenu'); ?>
  <div class="menu-overlay"></div>
</header>