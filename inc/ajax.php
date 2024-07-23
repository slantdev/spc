<?php

// Load Posts
add_action('wp_ajax_pagination_load_posttypes', 'pagination_load_posttypes');
add_action('wp_ajax_nopriv_pagination_load_posttypes', 'pagination_load_posttypes');
function pagination_load_posttypes()
{
  global $wpdb;
  // Set default variables
  $msg = '';
  if (isset($_POST['page'])) {
    // Sanitize the received page
    $page = sanitize_text_field($_POST['page']);
    $post_type = sanitize_text_field($_POST['post_type']);
    $per_page = sanitize_text_field($_POST['per_page']);
    $pagination = sanitize_text_field($_POST['pagination']);
    $terms = sanitize_text_field($_POST['terms']);
    $terms = json_decode(stripslashes($terms));
    $cur_page = $page;
    $page -= 1;
    $previous_btn = true;
    $next_btn = true;
    $first_btn = true;
    $last_btn = true;
    $start = $page * $per_page;

    $defaultsArgs = array(
      'post_type'         => $post_type,
      'post_status'       => 'publish',
      'orderby'           => 'post_date',
      'order'             => 'DESC',
      'posts_per_page'    => $per_page,
      'offset'            => $start
    );
    $countDefaults = array(
      'post_type'         => $post_type,
      'post_status '      => 'publish',
      'posts_per_page'    => -1
    );

    if ($terms) {
      $taxonomy = 'category';
      if ($post_type == 'campaign') {
        $taxonomy = 'campaign-category';
      }
      $postArgs = array(
        'tax_query' => array(
          array(
            'taxonomy' => $taxonomy,
            'field' => 'id',
            'terms' => $terms,
          ),
        ),
      );
      $countArgs = array(
        'tax_query' => array(
          array(
            'taxonomy' => $taxonomy,
            'field' => 'id',
            'terms' => $terms,
          ),
        ),
      );
    }

    $args = wp_parse_args($postArgs, $defaultsArgs);
    $count = wp_parse_args($countArgs, $countDefaults);

    $all_posts = new WP_Query($args);
    $count_query = new WP_Query($count);

    $count = $count_query->post_count;

    if ($all_posts->have_posts()) {
      $postCount = 0;
      echo '<div class="grid grid-cols-1 gap-4 lg:grid-cols-3 lg:gap-6">';
      while ($all_posts->have_posts()) {
        $postCount++;
        $all_posts->the_post();
        $the_id = get_the_ID();
        $page_header = get_field('page_header', $the_id);
        $image = $page_header['page_header_settings']['background']['background_image']['url'] ?? '';
        if (has_post_thumbnail($the_id)) {
          $image = get_the_post_thumbnail_url($the_id, 'large');
        }
        $title =  get_the_title();
        // $date =  get_the_date();
        // $excerpt = wp_trim_words(get_the_excerpt(), $num_words = 30, $more = null);
        $link = get_the_permalink();
        if ($postCount == 1) { ?>
          <div class="md:col-span-3">
            <div class="card-wrapper">
              <a href="<?php echo $link ?>" class="group block relative rounded-lg xl:rounded-xl overflow-clip">
                <div class="aspect-w-1 aspect-h-1 md:aspect-w-16 md:aspect-h-6">
                  <?php if ($image) : ?>
                    <img class="featured-image object-cover w-full h-full transition-all duration-300 group-hover:scale-105" src="<?php echo $image ?>" alt="">
                  <?php else : ?>
                    <div class="w-full h-full bg-slate-50"></div>
                  <?php endif; ?>
                </div>
                <div class="absolute inset-0 bg-gradient-to-l from-black/80 via-transparent bg-blend-multiply"></div>
                <div class="absolute right-4 bottom-4 text-white">
                  <?php echo spc_icon(array('icon' => 'plus-circle', 'group' => 'utilities', 'size' => '64', 'class' => 'w-8 h-8 md:w-16 md:h-16')); ?>
                </div>
                <div class="hidden md:block absolute inset-0">
                  <div class="w-full h-full flex justify-end items-end">
                    <div class="w-2/5 px-12 py-8">
                      <h4 class="text-3xl leading-tight font-semibold text-white"><?php echo $title ?></h4>
                      <div class="text-lg text-white underline mt-24">Read More</div>
                    </div>
                  </div>
                </div>
              </a>
              <div class="md:hidden py-4">
                <h4><a href="<?php echo $link ?>" class="text-xl md:text-2xl leading-tight font-semibold text-brand-dark-blue hover:underline"><?php echo $title ?></a></h4>
              </div>
            </div>
          </div>
        <?php } else { ?>
          <div class="card-wrapper">
            <a href="<?php echo $link ?>" class="group block relative rounded-lg xl:rounded-xl overflow-clip">
              <div class="aspect-w-1 aspect-h-1">
                <?php if ($image) : ?>
                  <img class="object-cover w-full h-full transition-all duration-300 group-hover:scale-105" src="<?php echo $image ?>" alt="">
                <?php else : ?>
                  <div class="w-full h-full bg-slate-50"></div>
                <?php endif; ?>
              </div>
              <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent bg-blend-multiply"></div>
              <div class="absolute right-4 bottom-4 text-white">
                <?php echo spc_icon(array('icon' => 'plus-circle', 'group' => 'utilities', 'size' => '64', 'class' => 'w-8 h-8 md:w-16 md:h-16')); ?>
              </div>
            </a>
            <div class="py-4">
              <h4><a href="<?php echo $link ?>" class="text-xl md:text-2xl leading-tight font-semibold text-brand-dark-blue hover:underline"><?php echo $title ?></a></h4>
            </div>
          </div>
      <?php }
      }
      echo '</div>';
    }

    if ($pagination) :
      // Paginations
      $no_of_paginations = ceil($count / $per_page);
      if ($cur_page >= 7) {
        $start_loop = $cur_page - 3;
        if ($no_of_paginations > $cur_page + 3)
          $end_loop = $cur_page + 3;
        else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
          $start_loop = $no_of_paginations - 6;
          $end_loop = $no_of_paginations;
        } else {
          $end_loop = $no_of_paginations;
        }
      } else {
        $start_loop = 1;
        if ($no_of_paginations > 7)
          $end_loop = 7;
        else
          $end_loop = $no_of_paginations;
      }
      // Pagination Buttons logic
      ?>
      <div class='posts-pagination mt-10 pt-4 border-t border-slate-200'>
        <ul>
          <?php if ($first_btn && $cur_page > 1) { ?>
            <li data-page='1' class='active'>&laquo;</li>
          <?php } else if ($first_btn) { ?>
            <li data-page='1' class='inactive'>&laquo;</li>
          <?php } ?>
          <?php if ($previous_btn && $cur_page > 1) {
            $pre = $cur_page - 1;
          ?>
            <li data-page='<?php echo $pre; ?>' class='active'>&lsaquo;</li>
          <?php } else if ($previous_btn) { ?>
            <li class='inactive p-2'>&lsaquo;</li>
          <?php } ?>
          <?php for ($i = $start_loop; $i <= $end_loop; $i++) {
            if ($cur_page == $i) {
          ?>
              <li data-page='<?php echo $i; ?>' class='selected'><?php echo $i; ?></li>
            <?php } else { ?>
              <li data-page='<?php echo $i; ?>' class='active'><?php echo $i; ?></li>
          <?php }
          } ?>
          <?php if ($next_btn && $cur_page < $no_of_paginations) {
            $nex = $cur_page + 1; ?>
            <li data-page='<?php echo $nex; ?>' class='active'>&rsaquo;</li>
          <?php } else if ($next_btn) { ?>
            <li class='inactive'>&rsaquo;</li>
          <?php } ?>
          <?php if ($last_btn && $cur_page < $no_of_paginations) { ?>
            <li data-page='<?php echo $no_of_paginations; ?>' class='active'>&raquo;</li>
          <?php } else if ($last_btn) { ?>
            <li data-page='<?php echo $no_of_paginations; ?>' class='inactive'>&raquo;</li>
          <?php } ?>
        </ul>
      </div>
      <?php
    endif;
  }
  exit();
}

// Load Posts Grid
add_action('wp_ajax_pagination_load_postgrid', 'pagination_load_postgrid');
add_action('wp_ajax_nopriv_pagination_load_postgrid', 'pagination_load_postgrid');
function pagination_load_postgrid()
{
  global $wpdb;
  // Set default variables
  $msg = '';
  if (isset($_POST['page'])) {
    // Sanitize the received page
    $page = sanitize_text_field($_POST['page']);
    $post_type = sanitize_text_field($_POST['post_type']);
    $per_page = sanitize_text_field($_POST['per_page']);
    $pagination = sanitize_text_field($_POST['pagination']);
    $terms = sanitize_text_field($_POST['terms']);
    $terms = json_decode(stripslashes($terms));
    $cur_page = $page;
    $page -= 1;
    $previous_btn = true;
    $next_btn = true;
    $first_btn = true;
    $last_btn = true;
    $start = $page * $per_page;

    $defaultsArgs = array(
      'post_type'         => $post_type,
      'post_status '      => 'publish',
      'orderby'           => 'post_date',
      'order'             => 'DESC',
      'posts_per_page'    => $per_page,
      'offset'            => $start
    );
    $countDefaults = array(
      'post_type'         => $post_type,
      'post_status '      => 'publish',
      'posts_per_page'    => -1
    );

    if ($terms) {
      $taxonomy = 'category';
      if ($post_type == 'campaign') {
        $taxonomy = 'campaign-category';
      }
      $postArgs = array(
        'tax_query' => array(
          array(
            'taxonomy' => $taxonomy,
            'field' => 'id',
            'terms' => $terms,
          ),
        ),
      );
      $countArgs = array(
        'tax_query' => array(
          array(
            'taxonomy' => $taxonomy,
            'field' => 'id',
            'terms' => $terms,
          ),
        ),
      );
    }

    $args = wp_parse_args($postArgs, $defaultsArgs);
    $count = wp_parse_args($countArgs, $countDefaults);

    $all_posts = new WP_Query($args);
    $count_query = new WP_Query($count);

    $count = $count_query->post_count;
    if ($all_posts->have_posts()) {
      echo '<div class="grid grid-cols-1 gap-4 lg:grid-cols-3 lg:gap-8">';
      while ($all_posts->have_posts()) {
        $all_posts->the_post();
        $the_id = get_the_ID();
        $page_header = get_field('page_header', $the_id);
        $image = $page_header['page_header_settings']['background']['background_image']['url'] ?? '';
        if (has_post_thumbnail($the_id)) {
          $image = get_the_post_thumbnail_url($the_id, 'large');
        }
        $title =  get_the_title();
        // $date =  get_the_date();
        $excerpt = wp_trim_words(get_the_excerpt(), $num_words = 20, $more = null);
        $link = get_the_permalink();
      ?>
        <div class="card-wrapper rounded-lg xl:rounded-xl overflow-clip shadow-lg bg-white flex flex-col">
          <a href="<?php echo $link ?>" class="group block relative rounded-t-xl overflow-clip">
            <div class="aspect-w-16 aspect-h-9">
              <?php if ($image) : ?>
                <img class="object-cover w-full h-full transition-all duration-300 group-hover:scale-105" src="<?php echo $image ?>" alt="">
              <?php else : ?>
                <div class="w-full h-full bg-slate-50"></div>
              <?php endif; ?>
            </div>
          </a>
          <div class="p-4 xl:p-6 bg-white grow flex flex-col">
            <h4 class="mb-4"><a href="<?php echo $link ?>" class="text-xl lg:text-2xl leading-tight font-semibold text-brand-dark-blue hover:underline" style="color: var(--section-link-color)"><?php echo $title ?></a></h4>
            <div class="mb-6 text-sm"><?php echo $excerpt ?></div>
            <div class="mt-auto"><a href="<?php echo $link ?>" class="text-sm xl:text-base font-semibold text-brand-dark-blue uppercase underline hover:no-underline" style="color: var(--section-link-color)">Learn More</a></div>
          </div>
        </div>
      <?php }
      echo '</div>';
    }

    if ($pagination) :
      // Paginations
      $no_of_paginations = ceil($count / $per_page);
      if ($no_of_paginations > 1) :
        if ($cur_page >= 7) {
          $start_loop = $cur_page - 3;
          if ($no_of_paginations > $cur_page + 3)
            $end_loop = $cur_page + 3;
          else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
            $start_loop = $no_of_paginations - 6;
            $end_loop = $no_of_paginations;
          } else {
            $end_loop = $no_of_paginations;
          }
        } else {
          $start_loop = 1;
          if ($no_of_paginations > 7)
            $end_loop = 7;
          else
            $end_loop = $no_of_paginations;
        }
        // Pagination Buttons logic
      ?>
        <div class='posts-pagination mt-10 pt-4 border-t border-slate-200'>
          <ul>
            <?php if ($first_btn && $cur_page > 1) { ?>
              <li data-page='1' class='active'>&laquo;</li>
            <?php } else if ($first_btn) { ?>
              <li data-page='1' class='inactive'>&laquo;</li>
            <?php } ?>
            <?php if ($previous_btn && $cur_page > 1) {
              $pre = $cur_page - 1;
            ?>
              <li data-page='<?php echo $pre; ?>' class='active'>&lsaquo;</li>
            <?php } else if ($previous_btn) { ?>
              <li class='inactive p-2'>&lsaquo;</li>
            <?php } ?>
            <?php for ($i = $start_loop; $i <= $end_loop; $i++) {
              if ($cur_page == $i) {
            ?>
                <li data-page='<?php echo $i; ?>' class='selected'><?php echo $i; ?></li>
              <?php } else { ?>
                <li data-page='<?php echo $i; ?>' class='active'><?php echo $i; ?></li>
            <?php }
            } ?>
            <?php if ($next_btn && $cur_page < $no_of_paginations) {
              $nex = $cur_page + 1; ?>
              <li data-page='<?php echo $nex; ?>' class='active'>&rsaquo;</li>
            <?php } else if ($next_btn) { ?>
              <li class='inactive'>&rsaquo;</li>
            <?php } ?>
            <?php if ($last_btn && $cur_page < $no_of_paginations) { ?>
              <li data-page='<?php echo $no_of_paginations; ?>' class='active'>&raquo;</li>
            <?php } else if ($last_btn) { ?>
              <li data-page='<?php echo $no_of_paginations; ?>' class='inactive'>&raquo;</li>
            <?php } ?>
          </ul>
        </div>
      <?php
      endif;
    endif;
  }
  exit();
}

// Load Adopt Slide
add_action('wp_ajax_load_adopt_cat', 'load_adopt_cat');
add_action('wp_ajax_nopriv_load_adopt_cat', 'load_adopt_cat');
function load_adopt_cat()
{
  global $wpdb;
  if (isset($_POST['page'])) {
    // Sanitize the received page
    $page = sanitize_text_field($_POST['page']);
    $post_type = 'adopt-cat';
    $per_page = sanitize_text_field($_POST['per_page']);
    $style = sanitize_text_field($_POST['style']);
    $show_pagination = sanitize_text_field($_POST['show_pagination']);
    $section_class = sanitize_text_field($_POST['section_class']);
    $cur_page = $page;
    $page -= 1;
    $previous_btn = true;
    $next_btn = true;
    $first_btn = true;
    $last_btn = true;
    $start = $page * $per_page;

    $all_posts = new WP_Query(
      array(
        'post_type'         => $post_type,
        'post_status '      => 'publish',
        'orderby'           => 'menu_order',
        'order'             => 'ASC',
        'posts_per_page'    => $per_page,
        'offset'            => $start
      )
    );
    $count = new WP_Query(
      array(
        'post_type'         => $post_type,
        'post_status '      => 'publish',
        'posts_per_page'    => -1
      )
    );

    $count = $count->post_count;
    if ($all_posts->have_posts()) {
      if ($style == 'slider') {
      ?>
        <div class="swiper -mx-2 xl:-mx-6">
          <div class="swiper-wrapper">
            <?php while ($all_posts->have_posts()) {
              $all_posts->the_post();
              $id = get_the_ID();
              $title =  get_the_title();
              $link = get_the_permalink();
              $adopt_cat_data = get_field('adopt_cat_data', $id);
              $birth = $adopt_cat_data['birth'] ?? '';
              $status = $adopt_cat_data['status'] ?? '';
              $age = '';
              if ($birth) {
                $dateString = $birth;
                $birthdate = DateTime::createFromFormat('d/m/Y', $birth);
                $currentDate = new DateTime();
                $interval = $currentDate->diff($birthdate);
                $years = $interval->y;
                $months = $interval->m;
                $ageString = "";
                if ($years > 0) {
                  $ageString .= $years . " Year";
                  if ($years > 1) {
                    $ageString .= "s";
                  }
                }
                if ($months > 0) {
                  if ($years > 0) {
                    $ageString .= " ";
                  }
                  $ageString .= $months . " Month";
                  if ($months > 1) {
                    $ageString .= "s";
                  }
                }
                $age = $ageString;
              }
              $cat_photos = get_field('cat_photos', $id);
              $cat_gallery = $cat_photos['cat_photos'] ?? '';
              $featured_thumbnail = $cat_photos['featured_thumbnail'] ?? '';
              if ($cat_gallery) {
                $image = $cat_gallery[0]['url'] ?? '';
              }
              if ($featured_thumbnail) {
                $image = $featured_thumbnail['url'] ?? '';
              } else if (has_post_thumbnail($id)) {
                $image = get_the_post_thumbnail_url($id, 'large');
              }
            ?>
              <div class="swiper-slide p-1 lg:p-4">
                <a href="<?php echo $link ?>" class="relative block bg-white h-full rounded-md lg:rounded-xl overflow-clip transition-all duration-300 hover:shadow-lg hover:scale-[1.02]">
                  <div class="aspect-w-1 aspect-h-1 overflow-hidden">
                    <?php if ($image) : ?>
                      <img src="<?php echo $image ?>" class="object-cover h-full w-full" />
                    <?php else : ?>
                      <div class="w-full h-full bg-slate-50"></div>
                    <?php endif; ?>
                  </div>
                  <?php
                  if ($status == 'available') {
                    echo '<div class="absolute top-3 lg:top-5 right-0 py-1 px-3 lg:py-2 lg:px-4 text-white text-xs lg:text-sm bg-brand-blue rounded-l-full">Available</div>';
                  } else if ($status == 'adopted') {
                    echo '<div class="absolute top-3 lg:top-5 right-0 py-1 px-3 lg:py-2 lg:px-4 text-white text-xs lg: bg-brand-teal rounded-l-full">Adopted</div>';
                  } else if ($status == 'foster') {
                    echo '<div class="absolute top-3 lg:top-5 right-0 py-1 px-3 lg:py-2 lg:px-4 text-white text-xs lg: bg-brand-yellow rounded-l-full">In Foster Care</div>';
                  }
                  ?>
                  <div class="px-3 py-2 lg:px-4 lg:py-2 xl:px-8 xl:py-4">
                    <div class="flex flex-col gap-y-0.5 lg:flex-row justify-between items-center lg:gap-x-4">
                      <div class="text-base lg:text-xl"><?php echo $title ?></div>
                      <div class="text-xs lg:text-sm text-slate-500 text-right"><?php echo $age ?></div>
                    </div>
                  </div>
                </a>
              </div>
            <?php } ?>
          </div>
        </div>
        <div class="absolute inset-0">
          <div class="container max-w-screen-2xl relative h-full">
            <button type="button" class="swiper-btn-prev absolute z-10 left-3 xl:-left-32 top-auto lg:top-1/2 -bottom-14 lg:bottom-auto -translate-y-1/2 w-6 h-6 xl:w-10 xl:h-10 flex items-center justify-center text-slate-300 hover:text-brand-blue transition-all duration-200">
              <?php echo spc_icon(array('icon' => 'chevron-left', 'group' => 'utilities', 'size' => '96', 'class' => 'w-10 h-10')); ?>
            </button>
            <button type="button" class="swiper-btn-next absolute z-10 right-3 xl:-right-32 top-auto lg:top-1/2 -bottom-14 lg:bottom-auto -translate-y-1/2 w-6 h-6 xl:w-10 xl:h-10 flex items-center justify-center text-slate-300 hover:text-brand-blue transition-all duration-200">
              <?php echo spc_icon(array('icon' => 'chevron-right', 'group' => 'utilities', 'size' => '96', 'class' => 'w-10 h-10')); ?>
            </button>
          </div>
        </div>
        <div class="absolute -bottom-14 lg:-bottom-20 left-4 right-4 lg:left-0 lg:right-0">
          <div class="container max-w-screen-2xl px-4 xl:px-8">
            <div class="relative">
              <div class="swiper-pagination -translate-y-[10px] px-4 flex justify-center [&>.swiper-pagination-bullet]:grow [&>.swiper-pagination-bullet]:rounded-lg [&>.swiper-pagination-bullet]:max-w-[80px] [&>.swiper-pagination-bullet]:lg:w-20 [&>.swiper-pagination-bullet]:h-1.5 [&>.swiper-pagination-bullet]:lg:h-2" style="--swiper-pagination-color:#1068F0;"></div>
            </div>
          </div>
        </div>
        <script type="text/javascript">
          jQuery(function($) {
            new Swiper('.<?php echo $section_class ?> .swiper', {
              slidesPerView: 2,
              spaceBetween: 0,
              loop: false,
              speed: 500,
              //watchOverflow: true,
              //centerInsufficientSlides: true,
              //autoHeight: true,
              slidesPerGroup: 2,
              grid: {
                fill: 'row',
                rows: 2,
              },
              pagination: {
                el: ".<?php echo $section_class ?> .swiper-pagination",
                clickable: true
              },
              navigation: {
                nextEl: '.<?php echo $section_class ?> .swiper-btn-next',
                prevEl: '.<?php echo $section_class ?> .swiper-btn-prev',
              },
              breakpoints: {
                768: {
                  slidesPerView: 3,
                  spaceBetween: 24
                },
                1280: {
                  slidesPerView: 4,
                  spaceBetween: 0
                }
              }
            });
          });
        </script>
      <?php } else { ?>
        <div class="grid grid-cols-2 gap-2 lg:grid-cols-4 lg:gap-5 mt-12">
          <?php while ($all_posts->have_posts()) {
            $all_posts->the_post();
            $id = get_the_ID();
            $title =  get_the_title();
            $link = get_the_permalink();
            $adopt_cat_data = get_field('adopt_cat_data', $id);
            $birth = $adopt_cat_data['birth'] ?? '';
            $status = $adopt_cat_data['status'] ?? '';
            $age = '';
            if ($birth) {
              $dateString = $birth;
              $birthdate = DateTime::createFromFormat('d/m/Y', $birth);
              $currentDate = new DateTime();
              $interval = $currentDate->diff($birthdate);
              $years = $interval->y;
              $months = $interval->m;
              $ageString = "";
              if ($years > 0) {
                $ageString .= $years . " Year";
                if ($years > 1) {
                  $ageString .= "s";
                }
              }
              if ($months > 0) {
                if ($years > 0) {
                  $ageString .= " ";
                }
                $ageString .= $months . " Month";
                if ($months > 1) {
                  $ageString .= "s";
                }
              }
              $age = $ageString;
            }
            $cat_photos = get_field('cat_photos', $id);
            $cat_gallery = $cat_photos['cat_photos'] ?? '';
            $featured_thumbnail = $cat_photos['featured_thumbnail'] ?? '';
            if ($cat_gallery) {
              $image = $cat_gallery[0]['url'] ?? '';
            }
            if ($featured_thumbnail) {
              $image = $featured_thumbnail['url'] ?? '';
            } else if (has_post_thumbnail($id)) {
              $image = get_the_post_thumbnail_url($id, 'large');
            }
          ?>
            <div class="">
              <a href="<?php echo $link ?>" class="relative block h-full bg-white rounded-md xl:rounded-xl overflow-clip transition-all duration-300 hover:shadow-lg hover:scale-[1.02]">
                <div class="aspect-w-1 aspect-h-1 overflow-hidden">
                  <?php if ($image) : ?>
                    <img src="<?php echo $image ?>" class="object-cover h-full w-full" />
                  <?php else : ?>
                    <div class="w-full h-full bg-slate-50"></div>
                  <?php endif; ?>
                </div>
                <?php
                if ($status == 'available') {
                  echo '<div class="absolute top-3 lg:top-5 right-0 py-1 px-3 lg:py-2 lg:px-4 text-white text-xs lg:text-sm bg-brand-blue rounded-l-full">Available</div>';
                } else if ($status == 'adopted') {
                  echo '<div class="absolute top-3 lg:top-5 right-0 py-1 px-3 lg:py-2 lg:px-4 text-white text-xs lg: bg-brand-teal rounded-l-full">Adopted</div>';
                } else if ($status == 'foster') {
                  echo '<div class="absolute top-3 lg:top-5 right-0 py-1 px-3 lg:py-2 lg:px-4 text-white text-xs lg: bg-brand-yellow rounded-l-full">In Foster Care</div>';
                }
                ?>
                <div class="px-3 py-2 lg:px-4 lg:py-2 xl:px-8 xl:py-4">
                  <div class="flex flex-col gap-y-0.5 lg:flex-row justify-between items-center lg:gap-x-4">
                    <div class="text-base lg:text-xl"><?php echo $title ?></div>
                    <div class="text-xs lg:text-sm text-slate-500 text-right"><?php echo $age ?></div>
                  </div>
                </div>
              </a>
            </div>
          <?php } ?>
        </div>
      <?php }
    }
  }
  exit();
}

// Load Foster Grid
add_action('wp_ajax_pagination_load_fostergrid', 'pagination_load_fostergrid');
add_action('wp_ajax_nopriv_pagination_load_fostergrid', 'pagination_load_fostergrid');
function pagination_load_fostergrid()
{
  global $wpdb;
  // Set default variables
  $msg = '';
  if (isset($_POST['page'])) {
    // Sanitize the received page
    $page = sanitize_text_field($_POST['page']);
    $post_type = 'foster-care';
    $per_page = sanitize_text_field($_POST['per_page']);
    $pagination = sanitize_text_field($_POST['pagination']);
    $cur_page = $page;
    $page -= 1;
    $previous_btn = true;
    $next_btn = true;
    $first_btn = true;
    $last_btn = true;
    $start = $page * $per_page;

    $all_posts = new WP_Query(
      array(
        'post_type'         => $post_type,
        'post_status '      => 'publish',
        'orderby'           => 'menu_order',
        'order'             => 'ASC',
        'posts_per_page'    => $per_page,
        'offset'            => $start
      )
    );
    $count = new WP_Query(
      array(
        'post_type'         => $post_type,
        'post_status '      => 'publish',
        'posts_per_page'    => -1
      )
    );

    $count = $count->post_count;
    if ($all_posts->have_posts()) {
      $postCount = 0;
      echo '<div class="grid grid-cols-1 gap-4 lg:grid-cols-4 lg:gap-6">';
      while ($all_posts->have_posts()) {
        $postCount++;
        $all_posts->the_post();
        $id = get_the_ID();
        $image = $page_header['page_header_settings']['background']['background_image']['url'] ?? '';
        $title =  get_the_title();
        $link = get_the_permalink();
        $foster_cat_data = get_field('foster_cat_data', $id);
        $cat_name = $foster_cat_data['cat_name'] ?? '';
        if ($cat_name) {
          $title = $cat_name;
        }
        $gender = $foster_cat_data['gender'] ?? '';
        $breed = $foster_cat_data['breed'] ?? '';
        $birth = $foster_cat_data['birth'] ?? '';
        $age = '';
        if ($birth) {
          $dateString = $birth;
          $birthdate = DateTime::createFromFormat('d/m/Y', $birth);
          $currentDate = new DateTime();
          $interval = $currentDate->diff($birthdate);
          $years = $interval->y;
          $months = $interval->m;
          $ageString = "";
          if ($years > 0) {
            $ageString .= $years . " Year";
            if ($years > 1) {
              $ageString .= "s";
            }
          }
          if ($months > 0) {
            if ($years > 0) {
              $ageString .= " ";
            }
            $ageString .= $months . " Month";
            if ($months > 1) {
              $ageString .= "s";
            }
          }
          $age = $ageString;
        }
        $cat_photos = get_field('cat_photos', $id);
        $cat_gallery = $cat_photos['cat_photos'] ?? '';
        $featured_thumbnail = $cat_photos['featured_thumbnail'] ?? '';
        if ($cat_gallery) {
          $image = $cat_gallery[0]['url'] ?? '';
        }
        if ($featured_thumbnail) {
          $image = $featured_thumbnail['url'] ?? '';
        } else if (has_post_thumbnail($id)) {
          $image = get_the_post_thumbnail_url($id, 'large');
        }
      ?>
        <a href="<?php echo $link ?>" class="block bg-white rounded-lg xl:rounded-xl overflow-clip transition-all duration-300 shadow-sm hover:shadow-lg hover:scale-[1.02]">
          <div class="aspect-w-1 aspect-h-1">
            <?php if ($image) : ?>
              <img class="object-cover w-full h-full transition-all duration-300 group-hover:scale-105" src="<?php echo $image ?>" alt="">
            <?php else : ?>
              <div class="w-full h-full bg-slate-50"></div>
            <?php endif; ?>
          </div>
          <div class="px-4 py-2 xl:px-8 xl:py-4">
            <h4 class="text-2xl xl:text-3xl text-center font-semibold text-brand-tomato" style="color: var(--section-link-color)"><?php echo $title ?></h4>
            <div class="text-sm xl:text-base text-center leading-snug text-slate-500 mt-2"><?php echo $age ?>, <?php echo $gender ?> <?php echo $breed ?></div>
          </div>
        </a>
      <?php }
      echo '</div>';
    }

    if ($pagination) :
      // Paginations
      $no_of_paginations = ceil($count / $per_page);
      if ($cur_page >= 7) {
        $start_loop = $cur_page - 3;
        if ($no_of_paginations > $cur_page + 3)
          $end_loop = $cur_page + 3;
        else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
          $start_loop = $no_of_paginations - 6;
          $end_loop = $no_of_paginations;
        } else {
          $end_loop = $no_of_paginations;
        }
      } else {
        $start_loop = 1;
        if ($no_of_paginations > 7)
          $end_loop = 7;
        else
          $end_loop = $no_of_paginations;
      }
      // Pagination Buttons logic
      ?>
      <div class='posts-pagination mt-10 pt-4 border-t border-slate-200'>
        <ul>
          <?php if ($first_btn && $cur_page > 1) { ?>
            <li data-page='1' class='active'>&laquo;</li>
          <?php } else if ($first_btn) { ?>
            <li data-page='1' class='inactive'>&laquo;</li>
          <?php } ?>
          <?php if ($previous_btn && $cur_page > 1) {
            $pre = $cur_page - 1;
          ?>
            <li data-page='<?php echo $pre; ?>' class='active'>&lsaquo;</li>
          <?php } else if ($previous_btn) { ?>
            <li class='inactive p-2'>&lsaquo;</li>
          <?php } ?>
          <?php for ($i = $start_loop; $i <= $end_loop; $i++) {
            if ($cur_page == $i) {
          ?>
              <li data-page='<?php echo $i; ?>' class='selected'><?php echo $i; ?></li>
            <?php } else { ?>
              <li data-page='<?php echo $i; ?>' class='active'><?php echo $i; ?></li>
          <?php }
          } ?>
          <?php if ($next_btn && $cur_page < $no_of_paginations) {
            $nex = $cur_page + 1; ?>
            <li data-page='<?php echo $nex; ?>' class='active'>&rsaquo;</li>
          <?php } else if ($next_btn) { ?>
            <li class='inactive'>&rsaquo;</li>
          <?php } ?>
          <?php if ($last_btn && $cur_page < $no_of_paginations) { ?>
            <li data-page='<?php echo $no_of_paginations; ?>' class='active'>&raquo;</li>
          <?php } else if ($last_btn) { ?>
            <li data-page='<?php echo $no_of_paginations; ?>' class='inactive'>&raquo;</li>
          <?php } ?>
        </ul>
      </div>
      <?php
    endif;
  }
  exit();
}

// Load Found Cats Grid
add_action('wp_ajax_pagination_load_foundcats', 'pagination_load_foundcats');
add_action('wp_ajax_nopriv_pagination_load_foundcats', 'pagination_load_foundcats');
function pagination_load_foundcats()
{
  global $wpdb;
  // Set default variables
  $msg = '';
  if (isset($_POST['page'])) {
    // Sanitize the received page
    $page = sanitize_text_field($_POST['page']);
    $post_type = 'found-cat';
    $per_page = sanitize_text_field($_POST['per_page']);
    $pagination = sanitize_text_field($_POST['pagination']);
    $cur_page = $page;
    $page -= 1;
    $previous_btn = true;
    $next_btn = true;
    $first_btn = true;
    $last_btn = true;
    $start = $page * $per_page;

    $all_posts = new WP_Query(
      array(
        'post_type'         => $post_type,
        'post_status '      => 'publish',
        'orderby'           => 'menu_order',
        'order'             => 'ASC',
        'posts_per_page'    => $per_page,
        'offset'            => $start
      )
    );
    $count = new WP_Query(
      array(
        'post_type'         => $post_type,
        'post_status '      => 'publish',
        'posts_per_page'    => -1
      )
    );

    $count = $count->post_count;
    if ($all_posts->have_posts()) {
      $postCount = 0;
      echo '<div class="grid grid-cols-1 gap-4 lg:grid-cols-4 lg:gap-6">';
      while ($all_posts->have_posts()) {
        $postCount++;
        $all_posts->the_post();
        $id = get_the_ID();
        $image = $page_header['page_header_settings']['background']['background_image']['url'] ?? '';
        $title =  get_the_title();
        $link = get_the_permalink();
        $found_cat_data = get_field('found_cat_data', $id);
        $cat_name = $title;
        if ($cat_name) {
          $title = $cat_name;
        }
        $gender = $found_cat_data['gender'] ?? '';
        $age = $found_cat_data['approximate_age'] ?? '';
        $date_of_arrival = $found_cat_data['date_of_arrival'] ?? '';
        $where_found = $found_cat_data['where_found'] ?? '';
        $cat_photos = get_field('cat_photos', $id);
        $cat_gallery = $cat_photos['cat_photos'] ?? '';
        $featured_thumbnail = $cat_photos['featured_thumbnail'] ?? '';
        if ($cat_gallery) {
          $image = $cat_gallery[0]['url'] ?? '';
        }
        if ($featured_thumbnail) {
          $image = $featured_thumbnail['url'] ?? '';
        } else if (has_post_thumbnail($id)) {
          $image = get_the_post_thumbnail_url($id, 'large');
        }
      ?>
        <a href="<?php echo $link ?>" class="block bg-white rounded-md lg:rounded-lg xl:rounded-xl overflow-clip transition-all duration-300 shadow-sm hover:shadow-lg hover:scale-[1.02]">
          <div class="aspect-w-1 aspect-h-1">
            <?php if ($image) : ?>
              <img class="object-cover w-full h-full transition-all duration-300 group-hover:scale-105" src="<?php echo $image ?>" alt="">
            <?php else : ?>
              <div class="w-full h-full bg-slate-50"></div>
            <?php endif; ?>
          </div>
          <div class="px-4 py-2 xl:px-8 xl:py-4">
            <h4 class="text-xl leading-tight text-center font-semibold text-brand-tomato" style="color: var(--section-link-color)"><?php echo $title ?></h4>
            <div class="text-base text-center leading-snug text-slate-500 mt-2"><?php echo $age ?>, <?php echo $gender ?></div>
          </div>
        </a>
      <?php }
      echo '</div>';
    }

    if ($pagination) :
      // Paginations
      $no_of_paginations = ceil($count / $per_page);
      if ($cur_page >= 7) {
        $start_loop = $cur_page - 3;
        if ($no_of_paginations > $cur_page + 3)
          $end_loop = $cur_page + 3;
        else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
          $start_loop = $no_of_paginations - 6;
          $end_loop = $no_of_paginations;
        } else {
          $end_loop = $no_of_paginations;
        }
      } else {
        $start_loop = 1;
        if ($no_of_paginations > 7)
          $end_loop = 7;
        else
          $end_loop = $no_of_paginations;
      }
      // Pagination Buttons logic
      ?>
      <div class='posts-pagination mt-10 pt-4 border-t border-slate-200'>
        <ul>
          <?php if ($first_btn && $cur_page > 1) { ?>
            <li data-page='1' class='active'>&laquo;</li>
          <?php } else if ($first_btn) { ?>
            <li data-page='1' class='inactive'>&laquo;</li>
          <?php } ?>
          <?php if ($previous_btn && $cur_page > 1) {
            $pre = $cur_page - 1;
          ?>
            <li data-page='<?php echo $pre; ?>' class='active'>&lsaquo;</li>
          <?php } else if ($previous_btn) { ?>
            <li class='inactive p-2'>&lsaquo;</li>
          <?php } ?>
          <?php for ($i = $start_loop; $i <= $end_loop; $i++) {
            if ($cur_page == $i) {
          ?>
              <li data-page='<?php echo $i; ?>' class='selected'><?php echo $i; ?></li>
            <?php } else { ?>
              <li data-page='<?php echo $i; ?>' class='active'><?php echo $i; ?></li>
          <?php }
          } ?>
          <?php if ($next_btn && $cur_page < $no_of_paginations) {
            $nex = $cur_page + 1; ?>
            <li data-page='<?php echo $nex; ?>' class='active'>&rsaquo;</li>
          <?php } else if ($next_btn) { ?>
            <li class='inactive'>&rsaquo;</li>
          <?php } ?>
          <?php if ($last_btn && $cur_page < $no_of_paginations) { ?>
            <li data-page='<?php echo $no_of_paginations; ?>' class='active'>&raquo;</li>
          <?php } else if ($last_btn) { ?>
            <li data-page='<?php echo $no_of_paginations; ?>' class='inactive'>&raquo;</li>
          <?php } ?>
        </ul>
      </div>
      <?php
    endif;
  }
  exit();
}

// Where They Grid
add_action('wp_ajax_pagination_load_wherethey', 'pagination_load_wherethey');
add_action('wp_ajax_nopriv_pagination_load_wherethey', 'pagination_load_wherethey');
function pagination_load_wherethey()
{
  global $wpdb;
  // Set default variables
  $msg = '';
  if (isset($_POST['page'])) {
    // Sanitize the received page
    $page = sanitize_text_field($_POST['page']);
    $post_type = 'where-are-they';
    $per_page = sanitize_text_field($_POST['per_page']);
    $pagination = sanitize_text_field($_POST['pagination']);
    $link_color = sanitize_text_field($_POST['link_color']);
    $cur_page = $page;
    $page -= 1;
    $previous_btn = true;
    $next_btn = true;
    $first_btn = true;
    $last_btn = true;
    $start = $page * $per_page;

    $all_posts = new WP_Query(
      array(
        'post_type'         => $post_type,
        'post_status '      => 'publish',
        'orderby'           => 'menu_order',
        'order'             => 'ASC',
        'posts_per_page'    => $per_page,
        'offset'            => $start
      )
    );
    $count = new WP_Query(
      array(
        'post_type'         => $post_type,
        'post_status '      => 'publish',
        'posts_per_page'    => -1
      )
    );

    $count = $count->post_count;
    if ($all_posts->have_posts()) {
      echo '<div class="grid grid-cols-1 gap-4 lg:grid-cols-3 lg:gap-6">';
      while ($all_posts->have_posts()) {
        $all_posts->the_post();
        $the_id = get_the_ID();
        $image = '';
        if (has_post_thumbnail($the_id)) {
          $image = get_the_post_thumbnail_url($the_id, 'large') ?? '';
        }
        $title =  get_the_title();
        $excerpt = wp_trim_words(get_the_excerpt(), $num_words = 20, $more = null);
        $link = get_the_permalink();
      ?>
        <div class="card-wrapper rounded-md lg:rounded-lg xl:rounded-xl overflow-clip shadow-lg bg-white flex flex-col">
          <button type="button" data-fancybox="where" data-src="#cat-<?php echo $the_id ?>" class="group block relative rounded-t-xl overflow-clip">
            <div class="aspect-w-16 aspect-h-9 overflow-clip">
              <?php if ($image) : ?>
                <img class="object-cover w-full h-full transition-all duration-300 group-hover:scale-105" src="<?php echo $image ?>" alt="">
              <?php else : ?>
                <div class="w-full h-full bg-slate-50"></div>
              <?php endif; ?>
            </div>
            <div class="p-4 xl:p-6 bg-white grow flex flex-col text-left">
              <h4 class="text-left text-xl xl:text-2xl leading-tight font-semibold text-brand-dark-blue hover:underline mb-4" style="color: var(--section-link-color)"><?php echo $title ?></h4>
              <div class="mb-6 text-sm"><?php echo $excerpt ?></div>
              <div class="mt-auto text-sm xl:text-base font-semibold text-brand-dark-blue uppercase underline hover:no-underline" style="color: var(--section-link-color)">Learn More</div>
            </div>
          </button>
        </div>
      <?php }
      echo '</div>';
      echo '
        <style>
        .fancybox__content>.f-button.is-close-btn {
          top: 20px;
          right: 20px;
          height: 40px;
          width: 40px;
          border-radius: 999px;
          background: rgba(0,0,0,.5);
        }
        .fancybox__nav {
          display: none;
        }
        @media (min-width: 1024px) {
          .fancybox__nav {
            display: block;
          }
        }
        </style>
      ';
    }

    if ($all_posts->have_posts()) {
      $title_style = '';
      if ($link_color) {
        $title_style = 'color:' . $link_color . ';';
      }
      echo '<div class="modals">';
      while ($all_posts->have_posts()) {
        $all_posts->the_post();
        $the_id = get_the_ID();
        $image = '';
        if (has_post_thumbnail($the_id)) {
          $image = get_the_post_thumbnail_url($the_id, 'large') ?? '';
        }
        $title =  get_the_title();
        $excerpt = wp_trim_words(get_the_excerpt(), $num_words = 20, $more = null);
      ?>
        <div id="cat-<?php echo $the_id ?>" class="max-w-screen-xs lg:max-w-screen-lg bg-white rounded-md lg:rounded-lg xl:rounded-xl overflow-clip !p-0" style="display:none;">
          <div class="flex flex-col xl:flex-row">
            <div class="w-full xl:w-1/2">
              <div class="aspect-w-16 aspect-h-9 xl:aspect-none xl:w-full xl:h-full">
                <?php if ($image) : ?>
                  <img class="object-cover !w-full !h-full transition-all duration-300 group-hover:scale-105" src="<?php echo $image ?>" alt="">
                <?php else : ?>
                  <div class="w-full h-full bg-slate-50"></div>
                <?php endif; ?>
              </div>
            </div>
            <div class="w-full xl:w-1/2">
              <div class="pl-6 pt-6 pr-2 pb-6 xl:pt-20 xl:pl-12 xl:pr-8 xl:pb-12 flex flex-col max-h-full lg:max-h-[700px]">
                <h3 class="text-left text-2xl xl:text-3xl leading-tight font-semibold text-brand-blue flex-none" style="<?php echo $title_style ?>"><?php echo $title ?></h3>
                <div class="mt-6 overflow-y-auto">
                  <div class="prose pr-2">
                    <?php the_content() ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php }
      echo '</div>';
    }

    if ($pagination) :
      // Paginations
      $no_of_paginations = ceil($count / $per_page);
      if ($cur_page >= 7) {
        $start_loop = $cur_page - 3;
        if ($no_of_paginations > $cur_page + 3)
          $end_loop = $cur_page + 3;
        else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
          $start_loop = $no_of_paginations - 6;
          $end_loop = $no_of_paginations;
        } else {
          $end_loop = $no_of_paginations;
        }
      } else {
        $start_loop = 1;
        if ($no_of_paginations > 7)
          $end_loop = 7;
        else
          $end_loop = $no_of_paginations;
      }
      // Pagination Buttons logic
      ?>
      <div class='posts-pagination mt-10 pt-4 border-t border-slate-200'>
        <ul>
          <?php if ($first_btn && $cur_page > 1) { ?>
            <li data-page='1' class='active'>&laquo;</li>
          <?php } else if ($first_btn) { ?>
            <li data-page='1' class='inactive'>&laquo;</li>
          <?php } ?>
          <?php if ($previous_btn && $cur_page > 1) {
            $pre = $cur_page - 1;
          ?>
            <li data-page='<?php echo $pre; ?>' class='active'>&lsaquo;</li>
          <?php } else if ($previous_btn) { ?>
            <li class='inactive p-2'>&lsaquo;</li>
          <?php } ?>
          <?php for ($i = $start_loop; $i <= $end_loop; $i++) {
            if ($cur_page == $i) {
          ?>
              <li data-page='<?php echo $i; ?>' class='selected'><?php echo $i; ?></li>
            <?php } else { ?>
              <li data-page='<?php echo $i; ?>' class='active'><?php echo $i; ?></li>
          <?php }
          } ?>
          <?php if ($next_btn && $cur_page < $no_of_paginations) {
            $nex = $cur_page + 1; ?>
            <li data-page='<?php echo $nex; ?>' class='active'>&rsaquo;</li>
          <?php } else if ($next_btn) { ?>
            <li class='inactive'>&rsaquo;</li>
          <?php } ?>
          <?php if ($last_btn && $cur_page < $no_of_paginations) { ?>
            <li data-page='<?php echo $no_of_paginations; ?>' class='active'>&raquo;</li>
          <?php } else if ($last_btn) { ?>
            <li data-page='<?php echo $no_of_paginations; ?>' class='inactive'>&raquo;</li>
          <?php } ?>
        </ul>
      </div>
    <?php
    endif;
  }
  exit();
}


// Load FAQS
add_action('wp_ajax_pagination_load_faqs', 'pagination_load_faqs');
add_action('wp_ajax_nopriv_pagination_load_faqs', 'pagination_load_faqs');
function pagination_load_faqs()
{
  global $wpdb;
  // Set default variables
  $msg = '';
  if (isset($_POST['page'])) {
    // Sanitize the received page
    $faq_id = sanitize_text_field($_POST['faq_id']);
    $page = sanitize_text_field($_POST['page']);
    $per_page = sanitize_text_field($_POST['per_page']);
    $pagination = sanitize_text_field($_POST['pagination']);
    $terms = sanitize_text_field($_POST['terms']);
    $terms = json_decode(stripslashes($terms));
    $cur_page = $page;
    $page -= 1;
    $previous_btn = true;
    $next_btn = true;
    $first_btn = true;
    $last_btn = true;
    $start = $page * $per_page;

    if ($terms) {
      $all_faqs = new WP_Query(
        array(
          'post_type'         => 'faq',
          'post_status '      => 'publish',
          'orderby'           => 'menu_order',
          'order'             => 'ASC',
          'posts_per_page'    => $per_page,
          'offset'            => $start,
          'tax_query' => array(
            array(
              'taxonomy' => 'faq-category',
              'field' => 'id',
              'terms' => $terms,
            ),
          ),
        )
      );
      $count = new WP_Query(
        array(
          'post_type'         => 'faq',
          'post_status '      => 'publish',
          'orderby'           => 'menu_order',
          'order'             => 'ASC',
          'posts_per_page'    => -1,
          'tax_query' => array(
            array(
              'taxonomy' => 'faq-category',
              'field' => 'id',
              'terms' => $terms,
            ),
          ),
        )
      );
    } else {
      $all_faqs = new WP_Query(
        array(
          'post_type'         => 'faq',
          'post_status '      => 'publish',
          'orderby'           => 'menu_order',
          'order'             => 'ASC',
          'posts_per_page'    => $per_page,
          'offset'            => $start
        )
      );
      $count = new WP_Query(
        array(
          'post_type'         => 'faq',
          'post_status '      => 'publish',
          'orderby'           => 'menu_order',
          'order'             => 'ASC',
          'posts_per_page'    => -1
        )
      );
    }

    $count = $count->post_count;
    if ($all_faqs->have_posts()) {
      while ($all_faqs->have_posts()) {
        $all_faqs->the_post();
        echo '<div class="collapse collapse-plus bg-brand-light-gray rounded-md lg:rounded-lg border border-solid border-slate-300 shadow-md mb-6">';
        echo '<input type="checkbox" class="faq-radio-btn w-full h-full block" name="faq-' . $faq_id . '" />';
        echo '<div class="collapse-title bg-white text-lg lg:text-2xl border-t-0 border-x-0 border-b border-solid border-slate-300 font-medium py-4 pl-4 pr-8 lg:py-5 lg:pl-8 lg:pr-12 after:font-thin after:!end-8 after:text-brand-tomato after:!top-2 after:text-3xl after:lg:text-5xl">';
        echo get_the_title();
        echo '</div>';
        echo '<div class="collapse-content p-0">';
        echo '<div class="p-4 lg:p-8">';
        echo '<div class="prose lg:prose-lg max-w-none">';
        echo get_field('faq_post')['faq_content'];
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
      }
    }

    if ($pagination) :
      // Paginations
      $no_of_paginations = ceil($count / $per_page);
      if ($cur_page >= 7) {
        $start_loop = $cur_page - 3;
        if ($no_of_paginations > $cur_page + 3)
          $end_loop = $cur_page + 3;
        else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
          $start_loop = $no_of_paginations - 6;
          $end_loop = $no_of_paginations;
        } else {
          $end_loop = $no_of_paginations;
        }
      } else {
        $start_loop = 1;
        if ($no_of_paginations > 7)
          $end_loop = 7;
        else
          $end_loop = $no_of_paginations;
      }
      // Pagination Buttons logic
    ?>
      <div class='posts-pagination mt-10 pt-4 border-x-0 border-b-0 border-t border-solid border-slate-200'>
        <ul>
          <?php if ($first_btn && $cur_page > 1) { ?>
            <li data-page='1' class='active'>&laquo;</li>
          <?php } else if ($first_btn) { ?>
            <li data-page='1' class='inactive'>&laquo;</li>
          <?php } ?>
          <?php if ($previous_btn && $cur_page > 1) {
            $pre = $cur_page - 1;
          ?>
            <li data-page='<?php echo $pre; ?>' class='active'>&lsaquo;</li>
          <?php } else if ($previous_btn) { ?>
            <li class='inactive p-2'>&lsaquo;</li>
          <?php } ?>
          <?php for ($i = $start_loop; $i <= $end_loop; $i++) {
            if ($cur_page == $i) {
          ?>
              <li data-page='<?php echo $i; ?>' class='selected'><?php echo $i; ?></li>
            <?php } else { ?>
              <li data-page='<?php echo $i; ?>' class='active'><?php echo $i; ?></li>
          <?php }
          } ?>
          <?php if ($next_btn && $cur_page < $no_of_paginations) {
            $nex = $cur_page + 1; ?>
            <li data-page='<?php echo $nex; ?>' class='active'>&rsaquo;</li>
          <?php } else if ($next_btn) { ?>
            <li class='inactive'>&rsaquo;</li>
          <?php } ?>
          <?php if ($last_btn && $cur_page < $no_of_paginations) { ?>
            <li data-page='<?php echo $no_of_paginations; ?>' class='active'>&raquo;</li>
          <?php } else if ($last_btn) { ?>
            <li data-page='<?php echo $no_of_paginations; ?>' class='inactive'>&raquo;</li>
          <?php } ?>
        </ul>
      </div>
      <?php
    endif;
  }
  exit();
}

// Load Reports Grid
add_action('wp_ajax_pagination_load_reportsgrid', 'pagination_load_reportsgrid');
add_action('wp_ajax_nopriv_pagination_load_reportsgrid', 'pagination_load_reportsgrid');
function pagination_load_reportsgrid()
{
  global $wpdb;
  // Set default variables
  $msg = '';
  if (isset($_POST['page'])) {
    // Sanitize the received page
    $page = sanitize_text_field($_POST['page']);
    $post_type = 'reports';
    $per_page = sanitize_text_field($_POST['per_page']);
    $pagination = sanitize_text_field($_POST['pagination']);
    $terms = sanitize_text_field($_POST['terms']);
    $terms = json_decode(stripslashes($terms));
    $cur_page = $page;
    $page -= 1;
    $previous_btn = true;
    $next_btn = true;
    $first_btn = true;
    $last_btn = true;
    $start = $page * $per_page;

    $defaultsArgs = array(
      'post_type'         => $post_type,
      'post_status '      => 'publish',
      'orderby'           => 'menu_order',
      'order'             => 'ASC',
      'posts_per_page'    => $per_page,
      'offset'            => $start
    );
    $countDefaults = array(
      'post_type'         => $post_type,
      'post_status '      => 'publish',
      'posts_per_page'    => -1
    );

    //$args = wp_parse_args($postArgs, $defaultsArgs);
    //$count = wp_parse_args($countArgs, $countDefaults);

    $all_posts = new WP_Query($defaultsArgs);
    $count_query = new WP_Query($countDefaults);

    $count = $count_query->post_count;
    if ($all_posts->have_posts()) {
      echo '<div class="grid grid-cols-1 gap-4 lg:grid-cols-4 lg:gap-8">';
      while ($all_posts->have_posts()) {
        $all_posts->the_post();
        $the_id = get_the_ID();
        $annual_report = get_field('annual_report', $the_id);
        $report_date = $annual_report['report_date'] ?? '';
        $title = $annual_report['title'] ?? '';
        $link_text = $annual_report['link_text'] ?? '';
        $file_pdf = $annual_report['file_pdf'] ?? '';
        $cover_image = $annual_report['cover_image'] ?? '';
      ?>
        <div class="card-wrapper rounded-md lg:rounded-lg xl:rounded-xl overflow-clip shadow-lg bg-white flex flex-col">
          <a href="<?php echo $file_pdf ?>" target="_blank" class="group block relative rounded-t-xl overflow-clip">
            <div class="aspect-w-[128] aspect-h-[181]">
              <?php if ($cover_image) : ?>
                <img class="object-cover w-full h-full transition-all duration-300 group-hover:scale-105" src="<?php echo $cover_image ?>" alt="">
              <?php else : ?>
                <div class="w-full h-full bg-slate-50"></div>
              <?php endif; ?>
            </div>
          </a>
          <div class="p-4 xl:p-6 bg-white grow flex flex-col">
            <h4 class="mb-4 text-lg lg:text-[20px] leading-tight font-semibold text-brand-dark-blue"><?php echo $title ?></h4>
            <div class="mt-auto"><a href="<?php echo $file_pdf ?>" target="_blank" class="font-semibold text-sm text-brand-dark-blue uppercase underline hover:no-underline" style="color: var(--section-link-color)"><?php echo $link_text ?></a></div>
          </div>
        </div>
      <?php }
      echo '</div>';
    }

    if ($pagination) :
      // Paginations
      $no_of_paginations = ceil($count / $per_page);
      if ($no_of_paginations > 1) :
        if ($cur_page >= 7) {
          $start_loop = $cur_page - 3;
          if ($no_of_paginations > $cur_page + 3)
            $end_loop = $cur_page + 3;
          else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
            $start_loop = $no_of_paginations - 6;
            $end_loop = $no_of_paginations;
          } else {
            $end_loop = $no_of_paginations;
          }
        } else {
          $start_loop = 1;
          if ($no_of_paginations > 7)
            $end_loop = 7;
          else
            $end_loop = $no_of_paginations;
        }
        // Pagination Buttons logic
      ?>
        <div class='posts-pagination mt-10 pt-4 border-t border-slate-200'>
          <ul>
            <?php if ($first_btn && $cur_page > 1) { ?>
              <li data-page='1' class='active'>&laquo;</li>
            <?php } else if ($first_btn) { ?>
              <li data-page='1' class='inactive'>&laquo;</li>
            <?php } ?>
            <?php if ($previous_btn && $cur_page > 1) {
              $pre = $cur_page - 1;
            ?>
              <li data-page='<?php echo $pre; ?>' class='active'>&lsaquo;</li>
            <?php } else if ($previous_btn) { ?>
              <li class='inactive p-2'>&lsaquo;</li>
            <?php } ?>
            <?php for ($i = $start_loop; $i <= $end_loop; $i++) {
              if ($cur_page == $i) {
            ?>
                <li data-page='<?php echo $i; ?>' class='selected'><?php echo $i; ?></li>
              <?php } else { ?>
                <li data-page='<?php echo $i; ?>' class='active'><?php echo $i; ?></li>
            <?php }
            } ?>
            <?php if ($next_btn && $cur_page < $no_of_paginations) {
              $nex = $cur_page + 1; ?>
              <li data-page='<?php echo $nex; ?>' class='active'>&rsaquo;</li>
            <?php } else if ($next_btn) { ?>
              <li class='inactive'>&rsaquo;</li>
            <?php } ?>
            <?php if ($last_btn && $cur_page < $no_of_paginations) { ?>
              <li data-page='<?php echo $no_of_paginations; ?>' class='active'>&raquo;</li>
            <?php } else if ($last_btn) { ?>
              <li data-page='<?php echo $no_of_paginations; ?>' class='inactive'>&raquo;</li>
            <?php } ?>
          </ul>
        </div>
      <?php
      endif;
    endif;
  }
  exit();
}

// Load Media Releases Grid
add_action('wp_ajax_pagination_load_mediareleasesgrid', 'pagination_load_mediareleasesgrid');
add_action('wp_ajax_nopriv_pagination_load_mediareleasesgrid', 'pagination_load_mediareleasesgrid');
function pagination_load_mediareleasesgrid()
{
  global $wpdb;
  // Set default variables
  $msg = '';
  if (isset($_POST['page'])) {
    // Sanitize the received page
    $page = sanitize_text_field($_POST['page']);
    $post_type = 'media-release';
    $per_page = sanitize_text_field($_POST['per_page']);
    $pagination = sanitize_text_field($_POST['pagination']);
    // $terms = sanitize_text_field($_POST['terms']);
    // $terms = json_decode(stripslashes($terms));
    $cur_page = $page;
    $page -= 1;
    $previous_btn = true;
    $next_btn = true;
    $first_btn = true;
    $last_btn = true;
    $start = $page * $per_page;

    $defaultsArgs = array(
      'post_type'         => $post_type,
      'post_status '      => 'publish',
      'orderby'           => 'menu_order',
      'order'             => 'ASC',
      'posts_per_page'    => $per_page,
      'offset'            => $start
    );
    $countDefaults = array(
      'post_type'         => $post_type,
      'post_status '      => 'publish',
      'posts_per_page'    => -1
    );

    //$args = wp_parse_args($postArgs, $defaultsArgs);
    //$count = wp_parse_args($countArgs, $countDefaults);

    $all_posts = new WP_Query($defaultsArgs);
    $count_query = new WP_Query($countDefaults);

    $count = $count_query->post_count;
    if ($all_posts->have_posts()) {
      echo '<div class="grid grid-cols-1 gap-4 lg:grid-cols-4 lg:gap-8">';
      while ($all_posts->have_posts()) {
        $all_posts->the_post();
        $the_id = get_the_ID();
        $media_release = get_field('media_release', $the_id);
        $report_date = $media_release['release_date'] ?? '';
        $title = $media_release['title'] ?? '';
        $link_text = $media_release['link_text'] ?? '';
        $file_pdf = $media_release['file_pdf'] ?? '';
        $cover_image = $media_release['cover_image'] ?? '';
      ?>
        <div class="card-wrapper rounded-md lg:rounded-lg xl:rounded-xl overflow-clip shadow-lg bg-white flex flex-col">
          <a href="<?php echo $file_pdf ?>" target="_blank" class="group block relative rounded-t-xl overflow-clip">
            <div class="aspect-w-[128] aspect-h-[181]">
              <?php if ($cover_image) : ?>
                <img class="object-cover w-full h-full transition-all duration-300 group-hover:scale-105" src="<?php echo $cover_image ?>" alt="">
              <?php else : ?>
                <div class="w-full h-full bg-slate-50"></div>
              <?php endif; ?>
            </div>
          </a>
          <div class="p-4 xl:p-6 bg-white grow flex flex-col">
            <h4 class="mb-4 text-[20px] leading-tight font-semibold text-brand-dark-blue"><?php echo $title ?></h4>
            <div class="mt-auto"><a href="<?php echo $file_pdf ?>" target="_blank" class="font-semibold text-sm text-brand-dark-blue uppercase underline hover:no-underline" style="color: var(--section-link-color)"><?php echo $link_text ?></a></div>
          </div>
        </div>
      <?php }
      echo '</div>';
    }

    if ($pagination) :
      // Paginations
      $no_of_paginations = ceil($count / $per_page);
      if ($no_of_paginations > 1) :
        if ($cur_page >= 7) {
          $start_loop = $cur_page - 3;
          if ($no_of_paginations > $cur_page + 3)
            $end_loop = $cur_page + 3;
          else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
            $start_loop = $no_of_paginations - 6;
            $end_loop = $no_of_paginations;
          } else {
            $end_loop = $no_of_paginations;
          }
        } else {
          $start_loop = 1;
          if ($no_of_paginations > 7)
            $end_loop = 7;
          else
            $end_loop = $no_of_paginations;
        }
        // Pagination Buttons logic
      ?>
        <div class='posts-pagination mt-10 pt-4 border-t border-slate-200'>
          <ul>
            <?php if ($first_btn && $cur_page > 1) { ?>
              <li data-page='1' class='active'>&laquo;</li>
            <?php } else if ($first_btn) { ?>
              <li data-page='1' class='inactive'>&laquo;</li>
            <?php } ?>
            <?php if ($previous_btn && $cur_page > 1) {
              $pre = $cur_page - 1;
            ?>
              <li data-page='<?php echo $pre; ?>' class='active'>&lsaquo;</li>
            <?php } else if ($previous_btn) { ?>
              <li class='inactive p-2'>&lsaquo;</li>
            <?php } ?>
            <?php for ($i = $start_loop; $i <= $end_loop; $i++) {
              if ($cur_page == $i) {
            ?>
                <li data-page='<?php echo $i; ?>' class='selected'><?php echo $i; ?></li>
              <?php } else { ?>
                <li data-page='<?php echo $i; ?>' class='active'><?php echo $i; ?></li>
            <?php }
            } ?>
            <?php if ($next_btn && $cur_page < $no_of_paginations) {
              $nex = $cur_page + 1; ?>
              <li data-page='<?php echo $nex; ?>' class='active'>&rsaquo;</li>
            <?php } else if ($next_btn) { ?>
              <li class='inactive'>&rsaquo;</li>
            <?php } ?>
            <?php if ($last_btn && $cur_page < $no_of_paginations) { ?>
              <li data-page='<?php echo $no_of_paginations; ?>' class='active'>&raquo;</li>
            <?php } else if ($last_btn) { ?>
              <li data-page='<?php echo $no_of_paginations; ?>' class='inactive'>&raquo;</li>
            <?php } ?>
          </ul>
        </div>
      <?php
      endif;
    endif;
  }
  exit();
}

/* ######
 * Ajax function filter posts
 * ###### 
 */

// Filter FAQ
function filter_faqs()
{
  $faq_id = sanitize_text_field($_POST['faq_id']);
  $faq_term = sanitize_text_field($_POST['faq_term']);

  $args = array(
    'post_type'         => 'faq',
    'post_status '      => 'publish',
    'orderby'           => 'menu_order',
    'order'             => 'ASC',
    'posts_per_page'    => '-1',
    'tax_query' => array(
      array(
        'taxonomy' => 'faq-category',
        'field' => 'id',
        'terms' => $faq_term,
      ),
    ),
  );

  $ajaxposts = new WP_Query($args);

  $response = '';

  if ($ajaxposts->have_posts()) {
    while ($ajaxposts->have_posts()) {
      $ajaxposts->the_post();
      $id = get_the_ID();
      // $content = get_the_content('null', false, $id);
      // $content = apply_filters('the_content', $content);
      // $content = str_replace(']]>', ']]&gt;', $content);
      $content = get_field('faq_post', $id)['faq_content'];

      $response .= '<div class="collapse collapse-plus bg-brand-light-gray rounded-md lg:rounded-lg border border-solid border-slate-300 shadow-md mb-6">';
      $response .=  '<input type="radio" class="faq-radio-btn w-full h-full block" name="faq-' . $faq_id . '" />';
      $response .=  '<div class="collapse-title bg-white text-xl lg:text-2xl border-t-0 border-x-0 border-b border-solid border-slate-300 font-medium py-4 pl-4 pr-8 lg:py-5 lg:pl-8 lg:pr-12 after:font-thin after:!end-8 after:text-brand-tomato after:!top-2 after:text-3xl after:lg:text-5xl">';
      $response .=  get_the_title();
      $response .=  '</div>';
      $response .=  '<div class="collapse-content p-0">';
      $response .=  '<div class="p-4 lg:p-8">';
      $response .=  '<div class="prose lg:prose-lg max-w-none">';
      $response .= $content;
      $response .=  '</div>';
      $response .=  '</div>';
      $response .=  '</div>';
      $response .=  '</div>';
    }
  } else {
    $response = '<div class="text-center py-4 px-8">No FAQs Found</div>';
  }

  echo $response;
  exit;
}
add_action('wp_ajax_filter_faqs', 'filter_faqs');
add_action('wp_ajax_nopriv_filter_faqs', 'filter_faqs');

// Filter Adopt Cat
function filter_adopt_cat()
{
  global $wpdb;
  if (isset($_POST['page'])) {
    // Sanitize the received page
    $page = sanitize_text_field($_POST['page']);
    $post_type = 'adopt-cat';
    $per_page = sanitize_text_field($_POST['per_page']);
    $per_page = '-1';
    $style = sanitize_text_field($_POST['style']);
    $show_pagination = sanitize_text_field($_POST['show_pagination']);
    $section_class = sanitize_text_field($_POST['section_class']);
    $meta_array_json = $_POST['meta_array'];
    $meta_array = json_decode(stripslashes($meta_array_json));
    $filter_array_json = $_POST['filter_array'];
    $filter_array = (array) json_decode(stripslashes($filter_array_json));
    $cur_page = $page;
    $page -= 1;
    $previous_btn = true;
    $next_btn = true;
    $first_btn = true;
    $last_btn = true;
    $start = $page * $per_page;

    // preint_r($meta_array);
    // preint_r($filter_array);

    $meta_query = array('relation' => 'AND');
    foreach ($meta_array as $meta_value) {
      $meta_query[] = array(
        'key'     => 'cat_filters_cat_filters',
        'value'   => $meta_value,
        'compare' => 'LIKE'
      );
    }

    $currentDate = new DateTime();
    $filter_shelter = $filter_array['shelter'] ?? '';
    if ($filter_shelter == 'shelter_1') {
      // Less than 1 month
      $compared_date = clone $currentDate;
      $compared_date->modify('-1 month');
      $comparedDate = $compared_date->format('Ymd');
      $start_date = clone $currentDate;
      $startDate = $start_date->format('Ymd');
      $meta_query[] = array(
        'key'     => 'adopt_cat_data_available_since',
        'value' => array($comparedDate, $startDate),
        'compare' => 'BETWEEN',
        'type' => 'DATE'
      );
    } else if ($filter_shelter == 'shelter_2') {
      // 1-3 month
      $compared_date = clone $currentDate;
      $compared_date->modify('-3 months');
      $comparedDate = $compared_date->format('Ymd');
      $start_date = clone $currentDate;
      $start_date->modify('-1 month');
      $startDate = $start_date->format('Ymd');
      $meta_query[] = array(
        'key'     => 'adopt_cat_data_available_since',
        'value' => array($comparedDate, $startDate),
        'compare' => 'BETWEEN',
        'type' => 'DATE'
      );
    } else if ($filter_shelter == 'shelter_3') {
      // 3 months+
      $compared_date = clone $currentDate;
      $compared_date->modify('-3 months');
      $comparedDate = $compared_date->format('Ymd');
      $meta_query[] = array(
        'key'     => 'adopt_cat_data_available_since',
        'value' => $comparedDate,
        'compare'   => '<',
        'type' => 'DATE'
      );
    }

    $filter_age = $filter_array['age'] ?? '';
    if ($filter_age == 'age_kitten') {
      //3-6 months      
      $compared_date = clone $currentDate;
      $compared_date->modify('-6 months');
      $comparedDate = $compared_date->format('Ymd');
      $start_date = clone $currentDate;
      $start_date->modify('-3 months');
      $startDate = $start_date->format('Ymd');
      $meta_query[] = array(
        'key'     => 'adopt_cat_data_birth',
        'value' => array($comparedDate, $startDate),
        'compare' => 'BETWEEN',
        'type' => 'DATE'
      );
    } else if ($filter_age == 'age_teenager') {
      //6 months to 2 years
      $compared_date = clone $currentDate;
      $compared_date->modify('-2 years');
      $comparedDate = $compared_date->format('Ymd');
      $start_date = clone $currentDate;
      $start_date->modify('-6 months');
      $startDate = $start_date->format('Ymd');
      $meta_query[] = array(
        'key'     => 'adopt_cat_data_birth',
        'value' => array($comparedDate, $startDate),
        'compare' => 'BETWEEN',
        'type' => 'DATE'
      );
    } else if ($filter_age == 'age_adult') {
      // 2 years to 7 years
      $compared_date = clone $currentDate;
      $compared_date->modify('-7 years');
      $comparedDate = $compared_date->format('Ymd');
      $start_date = clone $currentDate;
      $start_date->modify('-2 years');
      $startDate = $start_date->format('Ymd');
      $meta_query[] = array(
        'key'     => 'adopt_cat_data_birth',
        'value' => array($comparedDate, $startDate),
        'compare' => 'BETWEEN',
        'type' => 'DATE'
      );
    } else if ($filter_age == 'age_senior') {
      //7 years +
      $compared_date = clone $currentDate;
      $compared_date->modify('-7 years');
      $comparedDate = $compared_date->format('Ymd');
      $meta_query[] = array(
        'key'     => 'adopt_cat_data_birth',
        'value' => $comparedDate,
        'compare'   => '<',
        'type' => 'DATE'
      );
    }

    $filter_gender = $filter_array['gender'] ?? '';
    if ($filter_gender == 'gender_male') {
      $meta_query[] = array(
        'key'     => 'adopt_cat_data_gender',
        'value'   => sprintf('"%s"', 'Male'),
        'compare' => 'LIKE'
      );
    } else if ($filter_gender == 'gender_female') {
      $meta_query[] = array(
        'key'     => 'adopt_cat_data_gender',
        'value'   => sprintf('"%s"', 'Female'),
        'compare' => 'LIKE'
      );
    }

    //echo $meta_query;
    //preint_r($meta_query);

    //exit();

    $all_posts = new WP_Query(
      array(
        'post_type'         => $post_type,
        'post_status '      => 'publish',
        'orderby'           => 'menu_order',
        'order'             => 'ASC',
        'posts_per_page'    => $per_page,
        'offset'            => $start,
        'meta_query'        => $meta_query
      )
    );

    //preint_r($all_posts);

    // exit();

    $count = new WP_Query(
      array(
        'post_type'         => $post_type,
        'post_status '      => 'publish',
        'posts_per_page'    => -1
      )
    );

    $count = $count->post_count;
    if ($all_posts->have_posts()) {
      if ($style == 'slider') {
      ?>
        <div class="swiper -mx-4 xl:-mx-6">
          <div class="swiper-wrapper">
            <?php while ($all_posts->have_posts()) {
              $all_posts->the_post();
              $id = get_the_ID();
              $title =  get_the_title();
              $link = get_the_permalink();
              $adopt_cat_data = get_field('adopt_cat_data', $id);
              $birth = $adopt_cat_data['birth'] ?? '';
              $status = $adopt_cat_data['status'] ?? '';
              $age = '';
              if ($birth) {
                $dateString = $birth;
                $birthdate = DateTime::createFromFormat('d/m/Y', $birth);
                $currentDate = new DateTime();
                $interval = $currentDate->diff($birthdate);
                $years = $interval->y;
                $months = $interval->m;
                $ageString = "";
                if ($years > 0) {
                  $ageString .= $years . " Year";
                  if ($years > 1) {
                    $ageString .= "s";
                  }
                }
                if ($months > 0) {
                  if ($years > 0) {
                    $ageString .= " ";
                  }
                  $ageString .= $months . " Month";
                  if ($months > 1) {
                    $ageString .= "s";
                  }
                }
                $age = $ageString;
              }
              $cat_photos = get_field('cat_photos', $id);
              $cat_gallery = $cat_photos['cat_photos'] ?? '';
              $featured_thumbnail = $cat_photos['featured_thumbnail'] ?? '';
              if ($cat_gallery) {
                $image = $cat_gallery[0]['url'] ?? '';
              }
              if ($featured_thumbnail) {
                $image = $featured_thumbnail['url'] ?? '';
              } else if (has_post_thumbnail($id)) {
                $image = get_the_post_thumbnail_url($id, 'large');
              }
            ?>
              <div class="swiper-slide p-4">
                <a href="<?php echo $link ?>" class="relative block bg-white rounded-md lg:rounded-lg xl:rounded-xl overflow-clip transition-all duration-300 hover:shadow-lg hover:scale-[1.02]">
                  <div class="aspect-w-1 aspect-h-1 overflow-hidden">
                    <?php if ($image) : ?>
                      <img src="<?php echo $image ?>" class="object-cover h-full w-full" />
                    <?php else : ?>
                      <div class="w-full h-full bg-slate-50"></div>
                    <?php endif; ?>
                  </div>
                  <?php
                  if ($status == 'available') {
                    echo '<div class="absolute top-3 lg:top-5 right-0 py-1 px-3 lg:py-2 lg:px-4 text-white text-xs lg:text-sm bg-brand-blue rounded-l-full">Available</div>';
                  } else if ($status == 'adopted') {
                    echo '<div class="absolute top-3 lg:top-5 right-0 py-1 px-3 lg:py-2 lg:px-4 text-white text-xs lg: bg-brand-teal rounded-l-full">Adopted</div>';
                  } else if ($status == 'foster') {
                    echo '<div class="absolute top-3 lg:top-5 right-0 py-1 px-3 lg:py-2 lg:px-4 text-white text-xs lg: bg-brand-yellow rounded-l-full">In Foster Care</div>';
                  }
                  ?>
                  <div class="px-4 py-2 xl:px-8 xl:py-4">
                    <div class="flex justify-between items-center gap-x-4">
                      <div class="text-xl"><?php echo $title ?></div>
                      <div class="text-sm text-slate-500 text-right"><?php echo $age ?></div>
                    </div>
                  </div>
                </a>
              </div>
            <?php } ?>
          </div>
        </div>
        <div class="absolute inset-0">
          <div class="container max-w-screen-2xl relative h-full">
            <button type="button" class="swiper-btn-prev absolute z-10 left-0 xl:-left-32 top-2 lg:top-1/2 -translate-y-1/2 w-9 h-9 xl:w-10 xl:h-10 flex items-center justify-center text-slate-300 hover:text-brand-blue transition-all duration-200">
              <?php echo spc_icon(array('icon' => 'chevron-left', 'group' => 'utilities', 'size' => '96', 'class' => 'w-10 h-10')); ?>
            </button>
            <button type="button" class="swiper-btn-next absolute z-10 right-0 xl:-right-32 top-2 lg:top-1/2 -translate-y-1/2 w-9 h-9 xl:w-10 xl:h-10 flex items-center justify-center text-slate-300 hover:text-brand-blue transition-all duration-200">
              <?php echo spc_icon(array('icon' => 'chevron-right', 'group' => 'utilities', 'size' => '96', 'class' => 'w-10 h-10')); ?>
            </button>
          </div>
        </div>
        <div class="absolute -bottom-20 left-0 right-0">
          <div class="container max-w-screen-2xl px-4 xl:px-8">
            <div class="relative">
              <div class="swiper-pagination [&>.swiper-pagination-bullet]:rounded-lg" style="--swiper-pagination-bullet-width: 80px;--swiper-pagination-color:#1068F0;"></div>
            </div>
          </div>
        </div>
        <script type="text/javascript">
          jQuery(function($) {
            new Swiper('.<?php echo $section_class ?> .swiper', {
              slidesPerView: 4,
              spaceBetween: 0,
              loop: false,
              speed: 500,
              //watchOverflow: true,
              //centerInsufficientSlides: true,
              //autoHeight: true,
              slidesPerGroup: 2,
              grid: {
                fill: 'row',
                rows: 2,
              },
              pagination: {
                el: ".<?php echo $section_class ?> .swiper-pagination",
                clickable: true
              },
              navigation: {
                nextEl: '.<?php echo $section_class ?> .swiper-btn-next',
                prevEl: '.<?php echo $section_class ?> .swiper-btn-prev',
              },
              breakpoints: {
                768: {
                  slidesPerView: 'auto',
                  spaceBetween: 24
                },
                1280: {
                  slidesPerView: 4,
                  spaceBetween: 0
                }
              }
            });
          });
        </script>
      <?php } else { ?>
        <div class="grid grid-cols-1 gap-3 lg:grid-cols-4 lg:gap-5 mt-12">
          <?php while ($all_posts->have_posts()) {
            $all_posts->the_post();
            $id = get_the_ID();
            $title =  get_the_title();
            $link = get_the_permalink();
            $adopt_cat_data = get_field('adopt_cat_data', $id);
            $birth = $adopt_cat_data['birth'] ?? '';
            $status = $adopt_cat_data['status'] ?? '';
            $age = '';
            if ($birth) {
              $dateString = $birth;
              $birthdate = DateTime::createFromFormat('d/m/Y', $birth);
              $currentDate = new DateTime();
              $interval = $currentDate->diff($birthdate);
              $years = $interval->y;
              $months = $interval->m;
              $ageString = "";
              if ($years > 0) {
                $ageString .= $years . " Year";
                if ($years > 1) {
                  $ageString .= "s";
                }
              }
              if ($months > 0) {
                if ($years > 0) {
                  $ageString .= " ";
                }
                $ageString .= $months . " Month";
                if ($months > 1) {
                  $ageString .= "s";
                }
              }
              $age = $ageString;
            }
            $cat_photos = get_field('cat_photos', $id);
            $cat_gallery = $cat_photos['cat_photos'] ?? '';
            $featured_thumbnail = $cat_photos['featured_thumbnail'] ?? '';
            if ($cat_gallery) {
              $image = $cat_gallery[0]['url'] ?? '';
            }
            if ($featured_thumbnail) {
              $image = $featured_thumbnail['url'] ?? '';
            } else if (has_post_thumbnail($id)) {
              $image = get_the_post_thumbnail_url($id, 'large');
            }
          ?>
            <div class="">
              <a href="<?php echo $link ?>" class="relative block bg-white rounded-md lg:rounded-lg xl:rounded-xl overflow-clip transition-all duration-300 hover:shadow-lg hover:scale-[1.02]">
                <div class="aspect-w-1 aspect-h-1 overflow-hidden">
                  <?php if ($image) : ?>
                    <img src="<?php echo $image ?>" class="object-cover h-full w-full" />
                  <?php else : ?>
                    <div class="w-full h-full bg-slate-50"></div>
                  <?php endif; ?>
                </div>
                <?php
                if ($status == 'available') {
                  echo '<div class="absolute top-3 lg:top-5 right-0 py-1 px-3 lg:py-2 lg:px-4 text-white text-xs lg:text-sm bg-brand-blue rounded-l-full">Available</div>';
                } else if ($status == 'adopted') {
                  echo '<div class="absolute top-3 lg:top-5 right-0 py-1 px-3 lg:py-2 lg:px-4 text-white text-xs lg: bg-brand-teal rounded-l-full">Adopted</div>';
                } else if ($status == 'foster') {
                  echo '<div class="absolute top-3 lg:top-5 right-0 py-1 px-3 lg:py-2 lg:px-4 text-white text-xs lg: bg-brand-yellow rounded-l-full">In Foster Care</div>';
                }
                ?>
                <div class="px-4 py-2 xl:px-8 xl:py-4">
                  <div class="flex justify-between items-center gap-x-4">
                    <div class="text-xl"><?php echo $title ?></div>
                    <div class="text-sm text-slate-500 text-right"><?php echo $age ?></div>
                  </div>
                </div>
              </a>
            </div>
          <?php } ?>
        </div>
      <?php }
    } else {
      echo '<div class="text-center lg:text-lg">Sorry, there are no cats available in this criteria.</div>';
    }
  }
  exit();
}
add_action('wp_ajax_filter_adopt_cat', 'filter_adopt_cat');
add_action('wp_ajax_nopriv_filter_adopt_cat', 'filter_adopt_cat');


// Load Posts Grid
add_action('wp_ajax_filter_postgrid', 'filter_postgrid');
add_action('wp_ajax_nopriv_filter_postgrid', 'filter_postgrid');
function filter_postgrid()
{
  global $wpdb;
  // Set default variables
  $msg = '';
  if (isset($_POST['page'])) {
    // Sanitize the received page
    $page = sanitize_text_field($_POST['page']);
    $post_type = sanitize_text_field($_POST['post_type']);
    $per_page = sanitize_text_field($_POST['per_page']);
    //$pagination = sanitize_text_field($_POST['pagination']);
    $pagination = false;
    $terms = sanitize_text_field($_POST['terms']);
    $terms = json_decode(stripslashes($terms));
    $cur_page = $page;
    $page -= 1;
    $previous_btn = true;
    $next_btn = true;
    $first_btn = true;
    $last_btn = true;
    $start = $page * $per_page;

    $defaultsArgs = array(
      'post_type'         => $post_type,
      'post_status '      => 'publish',
      'orderby'           => 'post_date',
      'order'             => 'DESC',
      'posts_per_page'    => $per_page,
      'offset'            => $start
    );
    $countDefaults = array(
      'post_type'         => $post_type,
      'post_status '      => 'publish',
      'posts_per_page'    => -1
    );

    if ($terms) {
      $taxonomy = 'category';
      if ($post_type == 'campaign') {
        $taxonomy = 'campaign-category';
      }
      $postArgs = array(
        'tax_query' => array(
          array(
            'taxonomy' => $taxonomy,
            'field' => 'id',
            'terms' => $terms,
          ),
        ),
      );
      $countArgs = array(
        'tax_query' => array(
          array(
            'taxonomy' => $taxonomy,
            'field' => 'id',
            'terms' => $terms,
          ),
        ),
      );
    }

    $args = wp_parse_args($postArgs, $defaultsArgs);
    $count = wp_parse_args($countArgs, $countDefaults);

    $all_posts = new WP_Query($args);
    $count_query = new WP_Query($args);

    $count = $count_query->post_count;
    if ($all_posts->have_posts()) {
      echo '<div class="grid grid-cols-1 gap-4 lg:grid-cols-3 lg:gap-8">';
      while ($all_posts->have_posts()) {
        $all_posts->the_post();
        $the_id = get_the_ID();
        $page_header = get_field('page_header', $the_id);
        $image = $page_header['page_header_settings']['background']['background_image']['url'] ?? '';
        if (has_post_thumbnail($the_id)) {
          $image = get_the_post_thumbnail_url($the_id, 'large');
        }
        $title =  get_the_title();
        // $date =  get_the_date();
        $excerpt = wp_trim_words(get_the_excerpt(), $num_words = 20, $more = null);
        $link = get_the_permalink();
      ?>
        <div class="card-wrapper rounded-md lg:rounded-lg xl:rounded-xl overflow-clip shadow-lg bg-white flex flex-col">
          <a href="<?php echo $link ?>" class="group block relative rounded-t-xl overflow-clip">
            <div class="aspect-w-16 aspect-h-9">
              <?php if ($image) : ?>
                <img class="object-cover w-full h-full transition-all duration-300 group-hover:scale-105" src="<?php echo $image ?>" alt="">
              <?php else : ?>
                <div class="w-full h-full bg-slate-50"></div>
              <?php endif; ?>
            </div>
          </a>
          <div class="p-4 xl:p-6 bg-white grow flex flex-col">
            <h4 class="mb-4"><a href="<?php echo $link ?>" class="text-xl lg:text-2xl leading-tight font-semibold text-brand-dark-blue hover:underline" style="color: var(--section-link-color)"><?php echo $title ?></a></h4>
            <div class="mb-6 text-sm"><?php echo $excerpt ?></div>
            <div class="mt-auto"><a href="<?php echo $link ?>" class="text-sm xl:text-base font-semibold text-brand-dark-blue uppercase underline hover:no-underline" style="color: var(--section-link-color)">Learn More</a></div>
          </div>
        </div>
      <?php }
      echo '</div>';
    } else {
      echo '<div class="text-center lg:text-lg">Sorry, there are no posts in this criteria.</div>';
    }

    if ($pagination) :
      // Paginations
      $no_of_paginations = ceil($count / $per_page);
      if ($no_of_paginations > 1) :
        if ($cur_page >= 7) {
          $start_loop = $cur_page - 3;
          if ($no_of_paginations > $cur_page + 3)
            $end_loop = $cur_page + 3;
          else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
            $start_loop = $no_of_paginations - 6;
            $end_loop = $no_of_paginations;
          } else {
            $end_loop = $no_of_paginations;
          }
        } else {
          $start_loop = 1;
          if ($no_of_paginations > 7)
            $end_loop = 7;
          else
            $end_loop = $no_of_paginations;
        }
        // Pagination Buttons logic
      ?>
        <div class='posts-pagination mt-10 pt-4 border-t border-slate-200'>
          <ul>
            <?php if ($first_btn && $cur_page > 1) { ?>
              <li data-page='1' class='active'>&laquo;</li>
            <?php } else if ($first_btn) { ?>
              <li data-page='1' class='inactive'>&laquo;</li>
            <?php } ?>
            <?php if ($previous_btn && $cur_page > 1) {
              $pre = $cur_page - 1;
            ?>
              <li data-page='<?php echo $pre; ?>' class='active'>&lsaquo;</li>
            <?php } else if ($previous_btn) { ?>
              <li class='inactive p-2'>&lsaquo;</li>
            <?php } ?>
            <?php for ($i = $start_loop; $i <= $end_loop; $i++) {
              if ($cur_page == $i) {
            ?>
                <li data-page='<?php echo $i; ?>' class='selected'><?php echo $i; ?></li>
              <?php } else { ?>
                <li data-page='<?php echo $i; ?>' class='active'><?php echo $i; ?></li>
            <?php }
            } ?>
            <?php if ($next_btn && $cur_page < $no_of_paginations) {
              $nex = $cur_page + 1; ?>
              <li data-page='<?php echo $nex; ?>' class='active'>&rsaquo;</li>
            <?php } else if ($next_btn) { ?>
              <li class='inactive'>&rsaquo;</li>
            <?php } ?>
            <?php if ($last_btn && $cur_page < $no_of_paginations) { ?>
              <li data-page='<?php echo $no_of_paginations; ?>' class='active'>&raquo;</li>
            <?php } else if ($last_btn) { ?>
              <li data-page='<?php echo $no_of_paginations; ?>' class='inactive'>&raquo;</li>
            <?php } ?>
          </ul>
        </div>
<?php
      endif;
    endif;
  }
  exit();
}
