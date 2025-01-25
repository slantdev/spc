<?php
$current_post_id = get_queried_object_id();
$post_ancestors = get_post_ancestors($current_post_id);
$menu_items = get_field('menu_items', 'option');

$primary_color = get_field('primary_color', 'option');
if ($primary_color) {
  echo '<style>';
  echo '.main-nav--ul > li > a:hover { color: ' . $primary_color . '; }';
  echo '.dropdown-menu li > a:hover { color: ' . $primary_color . '; }';
  echo '</style>';
}
?>

<div class="bg-white">
  <div class="container max-w-screen-2xl">
    <div class="main-nav--div grow">
      <div class="toolbar-wrapper hidden">
        <form id="header-searchform" class="relative" method="get" action="<?php echo esc_url(home_url('/')); ?>">
          <input id="searchform-input" type="text" class="w-48 xl:w-56 3xl:w-64 border-gray-300 shadow-inner text-sm !rounded-full bg-white !px-4 !py-2 2xl:!py-3 focus:border-brand-blue focus:ring-brand-blue" name="s" placeholder="Search" value="">
          <button type="submit" class="absolute right-4 top-2">
            <?php echo spc_icon(array('icon' => 'search', 'group' => 'utilities', 'size' => '24', 'class' => 'text-brand-blue w-5 h-5 2xl:w-6 2xl:h-6')); ?>
          </button>
        </form>
        <button class="menu-close-btn flex-none">
          <?php echo spc_icon(array('icon' => 'close', 'group' => 'utilities', 'size' => '24', 'class' => 'w-6 h-6')); ?>
        </button>
      </div>
      <?php if ($menu_items) : ?>
        <ul class="main-nav--ul">
          <?php
          foreach ($menu_items as $menu_id => $menu) :
            $menu_item = $menu['menu_item'] ?? [];
            //$submenu_type = $menu['submenu_type'] ?? '';
            //$megamenu_items = $menu['megamenu_items'] ?? [];
            $dropdown_menu_items = $menu['dropdown_menu_items']['submenu_items'] ?? [];

            $li_class = '';
            if ($dropdown_menu_items) {
              $li_class = 'has_submenu';
            }

            if ($menu_item) {
              $menu_item_title = $menu_item['title'] ?? '';
              $menu_item_url = $menu_item['url'] ?? '';
              $menu_item_target = $menu_item['target'] ?? '_self';
              $link_post_id = url_to_postid($menu_item_url);
              // if ($submenu_type == 'megamenu' && $megamenu_items) {
              //   $dropdown_menu_items = null;
              // } elseif ($submenu_type == 'dropdown' && $dropdown_menu_items) {
              //   $megamenu_items = null;
              // }
              $args = [
                'current_post_id' => $current_post_id,
                'link_post_id' => $link_post_id,
                'post_ancestors' => $post_ancestors,
                //'megamenu_items' => $megamenu_items,
                'dropdown_menu_items' => $dropdown_menu_items,
              ];
              $link_class = get_menu_link_class($args);

              // Output menu item
              echo '<li class="' . $li_class . '">';
              echo '<a href="' . esc_url($menu_item_url) . '" target="' . esc_attr($menu_item_target) . '" data-id="' . esc_attr($link_post_id) . '" class="' . esc_attr($link_class) . '">' . esc_html($menu_item_title) . '</a>';

              echo '<button class="menu-right-btn hidden">';
              echo spc_icon(array('icon' => 'chevron-down', 'group' => 'utilities', 'size' => '12', 'class' => 'w-3 h-3 -rotate-90'));
              echo '</button>';

              // Output submenu if exists
              // if ($submenu_type == 'megamenu' && $megamenu_items) {
              //   output_megamenu($menu_id, $megamenu_items);
              // } elseif ($submenu_type == 'dropdown' && $dropdown_menu_items) {
              //   output_dropdown_menu($menu_id, $dropdown_menu_items);
              // }
              if ($dropdown_menu_items) {
                output_dropdown_menu($menu_id, $dropdown_menu_items);
              }

              echo '</li>';
            }

          endforeach;
          ?>
        </ul>
      <?php endif ?>
    </div>
  </div>
</div>
<?php
function get_menu_link_class($args)
{
  $current_post_id = $args['current_post_id'] ?? '';
  $link_post_id = $args['link_post_id'] ?? '';
  $post_ancestors = $args['post_ancestors'] ?? '';
  $megamenu_items = $args['megamenu_items'] ?? '';
  $dropdown_menu_items = $args['dropdown_menu_items'] ?? '';

  $megamenu_submenu_items = isset($megamenu_items['submenu_group']) ? $megamenu_items['submenu_group'] : [];
  $megamenu_link_ids = [];
  if (!empty($megamenu_submenu_items)) {
    foreach ($megamenu_submenu_items as $submenu) {
      $submenu_items = isset($submenu['submenu_items']) ? $submenu['submenu_items'] : [];
      if (!empty($submenu_items)) {
        foreach ($submenu_items as $item) {
          if (!empty($item['submenu_link']['url'])) {
            $submenu_post_id = url_to_postid($item['submenu_link']['url']);
            if ($submenu_post_id !== 0) {
              array_push($megamenu_link_ids, $submenu_post_id);
            }
          }
        }
      }
    }
  }

  $dropdown_menu_items = isset($dropdown_menu_items) ? $dropdown_menu_items : [];
  $dropdown_menu_link_ids = [];
  if (!empty($dropdown_menu_items)) {
    foreach ($dropdown_menu_items as $submenu) {
      if (!empty($submenu['submenu_link']['url'])) {
        $submenu_post_id = url_to_postid($submenu['submenu_link']['url']);
        if ($submenu_post_id !== 0) {
          array_push($dropdown_menu_link_ids, $submenu_post_id);
        }
      }
    }
  }

  $link_class = '';

  if ($current_post_id == $link_post_id) {
    $link_class = 'current-menu';
  } elseif (in_array($link_post_id, $post_ancestors) || in_array($current_post_id, $megamenu_link_ids) || in_array($current_post_id, $dropdown_menu_link_ids)) {
    $link_class = 'current-menu-ancestor';
  }


  return $link_class;
}

function output_megamenu($menu_id, $megamenu_items)
{
  if (empty($megamenu_items)) {
    return;
  }
?>
  <div class="mega-menu" tabindex="0">
    <div class="menu-back-wrapper">
      <button class="menu-back-btn">
        <?php echo spc_icon(array('icon' => 'chevron-down', 'group' => 'utilities', 'size' => '12', 'class' => 'w-3 h-3 rotate-90')); ?><span class="text-sm font-semibold">Back</span>
      </button>
    </div>
    <div class="mega-menu--wrapper">
      <?php
      $menu_heading = $megamenu_items['menu_heading'] ?? '';
      $menu_description = $megamenu_items['menu_description'] ?? '';
      $menu_background = $megamenu_items['menu_background']['background'] ?? [];
      $submenu_group = $megamenu_items['submenu_group'] ?? [];
      $submenu_items = $megamenu_items['submenu_items'] ?? []; // Repeater
      ?>
      <div class="mega-menu--col-header">
        <?php
        //preint_r($megamenu_items);
        if ($menu_background) {
          get_template_part('template-parts/components/background', '', array('field' => $menu_background));
        }
        ?>
        <div class="relative z-[1]">
          <?php
          if ($menu_heading) {
            echo '<h4 class="col-header--heading">';
            echo esc_html($menu_heading);
            echo '</h4>';
          }
          if ($menu_description) {
            echo '<div class="col-header--desc">' . esc_html($menu_description) . '</div>';
          }
          ?>
        </div>
      </div>
      <div class="mega-menu--col-content">
        <?php if ($submenu_items) : ?>
          <div class="flex xl:gap-x-12">
            <div class="xl:w-1/4">
              <ul class="flex flex-col xl:text-lg">
                <?php
                foreach ($submenu_items as $submenu_id => $item) {
                  $submenu_link = $item['submenu_item'] ?? [];
                  $submenu_link_url = $submenu_link['url'] ?? '';
                  $submenu_link_title = $submenu_link['title'] ?? '';
                  $submenu_link_target = $submenu_link['target'] ?? '_self';
                  $submenu_class = '';
                  $data_target = '';
                  $featured = $item['featured'] ?? [];
                  if ($featured) {
                    $submenu_class = 'menu-has-article';
                    $data_target = 'article-' . $menu_id . $submenu_id;
                  }
                  if ($submenu_link_url) {
                    echo '<li>';
                    echo '<a href="' . $submenu_link_url . '" target="' . $submenu_link_target . '" data-target="' . $data_target . '" class="' . $submenu_class . ' flex w-full relative py-2.5 justify-between border-b border-gray-300">';
                    echo '<span>' . $submenu_link_title . '</span>';
                    echo '<div class="menu-icon">';
                    if ($featured) {
                      echo spc_icon(array('icon' => 'chevron-right', 'group' => 'utilities', 'size' => '16', 'class' => 'text-brand-tomato'));
                    }
                    echo '</div>';
                    echo '</a>';
                    echo '</li>';
                  }
                }
                ?>
              </ul>
            </div>
            <div class="submenu-featured xl:w-2/5">
              <?php
              foreach ($submenu_items as $submenu_id => $item) :
                $featured = $item['featured'];
                $article_id = '';
                if ($featured) {
                  $article_id = 'article-' . $menu_id . $submenu_id;
                }
                $title = $featured['title'] ?? '';
                $description = $featured['description'] ?? '';
                $thumbnail = $featured['image'] ?? '';
                $link = $featured['link'] ?? '';
                $link_url = $link['url'] ?? '';
                $link_title = $link['title'] ?? 'Learn More';
                $link_target = $link['target'] ?? '_self';
                $active_class = 'inactive';
                if ($submenu_id == '0') {
                  $active_class = 'active';
                }
              ?>
                <?php if ($featured) : ?>
                  <div id="<?php echo $article_id; ?>" class="menu-article <?php echo $active_class ?>" tabindex="-1">
                    <div class="flex flex-col gap-y-4">
                      <?php if ($thumbnail) : ?>
                        <?php if ($link_url) {
                          echo '<a href="' . $link_url . '" target="' . $link_target . '">';
                        } ?>
                        <div class="aspect-w-16 aspect-h-8">
                          <img src="<?php echo $thumbnail['url'] ?>" alt="<?php echo $title ?>" class="object-cover h-full w-full rounded-xl" />
                        </div>
                        <?php if ($link_url) {
                          echo '</a>';
                        } ?>
                      <?php endif; ?>
                      <?php if ($title) : ?>
                        <h2 class="text-[28px] font-bold text-brand-dark-blue line-clamp-2"><?php echo $title ?></h2>
                      <?php endif; ?>
                      <?php if ($description) : ?>
                        <p class="text-lg line-clamp-2">
                          <?php echo $description ?>
                        </p>
                      <?php endif; ?>
                      <?php
                      if ($link_url) {
                        echo '<a href="' . $link_url . '" target="' . $link_target . '" class="text-brand-tomato font-semibold underline hover:no-underline">' . $link_title . ' &rsaquo;</a>';
                      }
                      ?>

                    </div>
                  </div>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif ?>
        <?php
        if (!empty($submenu_group)) {
          echo '<div class="col-content--grid">';
          foreach ($submenu_group as $submenu) {
            $submenu_heading = isset($submenu['submenu_heading']) ? $submenu['submenu_heading'] : '';
            $submenu_items = isset($submenu['submenu_items']) ? $submenu['submenu_items'] : [];
            echo '<div>';
            if (!empty($submenu_heading)) {
              echo '<h4 class="col-content--heading">' . esc_html($submenu_heading) . '</h4>';
            }
            if (!empty($submenu_items)) {
              echo '<ul>';
              foreach ($submenu_items as $item) {
                if (!empty($item['submenu_link']['url'])) {
                  echo '<li><a href="' . esc_url($item['submenu_link']['url']) . '" target="' . esc_attr($item['submenu_link']['target']) . '" class="col-content--link">' . esc_html($item['submenu_link']['title']) . '</a></li>';
                }
              }
              echo '</ul>';
            }
            echo '</div>';
          }
          echo '</div>';
        }
        ?>
      </div>
    </div>
  </div>
<?php
}

function output_dropdown_menu($menu_id, $dropdown_menu_items)
{
  if (empty($dropdown_menu_items)) {
    return;
  }
?>
  <div class="dropdown-menu" tabindex="0">
    <div class="menu-back-wrapper">
      <button class="menu-back-btn">
        <?php echo spc_icon(array('icon' => 'chevron-down', 'group' => 'utilities', 'size' => '12', 'class' => 'w-3 h-3 rotate-90')); ?><span class="text-sm font-semibold">Back</span>
      </button>
    </div>
    <div class="dropdown-wrapper">
      <ul>
        <?php foreach ($dropdown_menu_items as $item) : ?>
          <?php
          $submenu_link = $item['submenu_link'] ?? [];
          $submenu_link_url = $submenu_link['url'] ?? '';
          $submenu_link_target = $submenu_link['target'] ?? '_self';
          $submenu_link_title = $submenu_link['title'] ?? '';
          if (empty($submenu_link_url)) {
            continue;
          }
          ?>
          <li><a href="<?php echo esc_url($submenu_link_url); ?>" target="<?php echo esc_attr($submenu_link_target); ?>" class=""><?php echo esc_html($submenu_link_title); ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
<?php
}
?>