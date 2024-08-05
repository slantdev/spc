<?php

get_header();

// $s = get_search_query();
// $args = array(
//   's' => $s
// );

global $post;
$search_query = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : null;
$search_page  = isset($_GET['swppg']) ? absint($_GET['swppg']) : 1;

$search_results    = [];
$search_pagination = '';
if (!empty($search_query) && class_exists('\\SearchWP\\Query')) {
  $searchwp_query = new \SearchWP\Query($search_query, [
    'engine' => 'default', // The Engine name.
    'fields' => 'all',          // Load proper native objects of each result.
    'page'   => $search_page,
  ]);

  $search_results = $searchwp_query->get_results();

  $search_pagination = paginate_links(array(
    'format'  => '?swppg=%#%',
    'current' => $search_page,
    'total'   => $searchwp_query->max_num_pages,
  ));
}
?>

<section class="relative border-t border-slate-200">
  <div class="container max-w-4xl relative py-16">
    <form id="" class="relative" method="get" action="<?php echo esc_url(home_url('/')); ?>">
      <input id="searchresult-input" type="text" class="w-full !shadow-inner !rounded-full bg-white !px-6 !py-3 !border !border-solid !border-slate-200 focus:border-brand-sea focus:ring-brand-sea" name="s" placeholder="Search" value="<?php echo $search_query ?>">
      <button type="submit" class="absolute right-4 top-3">
        <?php echo spc_icon(array('icon' => 'search', 'group' => 'utilities', 'size' => '24', 'class' => 'text-brand-sea')); ?>
      </button>
    </form>
  </div>
</section>


<section class="relative">
  <div class="container mx-auto max-w-4xl relative pb-16 lg:pb-24">
    <ul class="search-results" style="--section-link-color:#45C2BF;">
      <?php
      if (!empty($search_query) && !empty($search_results)) :
      ?>
        <?php foreach ($search_results as $search_result) : ?>
          <?php
          //preint_r($search_result);
          $post = $search_result;
          $show_post = TRUE;
          $post_type = $post->post_type;
          //echo $post_type;
          $link = get_the_permalink();
          $target = '_self';
          $content = get_the_excerpt();
          ?>
          <li class="mb-4 lg:mb-8">
            <a href="<?php echo $link; ?>" target="<?php echo $target; ?>" class="block bg-white shadow-md border border-gray-200 rounded-lg transition duration-300 hover:shadow-xl">
              <div class="flex flex-wrap md:flex-nowrap">
                <div class="w-full p-4 lg:p-8">
                  <h3 class="search-title font-medium text-brand-sea text-xl lg:text-2xl !leading-tight"><?php the_title(); ?></h3>
                  <?php if ($content) : ?>
                    <div class="search-excerpt text-sm mt-2 font-default text-slate-600"><?php the_excerpt() ?></div>
                  <?php endif; ?>
                </div>
              </div>
            </a>
          </li>
          <?php wp_reset_postdata(); ?>
        <?php endforeach; ?>
        <?php if ($searchwp_query->max_num_pages > 1) : ?>
          <div class="navigation pagination mt-16" role="navigation" style="--section-link-color:#45C2BF;">
            <h2 class="screen-reader-text">Results navigation</h2>
            <div class="nav-links"><?php echo wp_kses_post($search_pagination); ?></div>
          </div>
        <?php endif; ?>

      <?php elseif (!empty($search_query)) : ?>
        <li class="mb-4 lg:mb-8">
          <div class="block bg-white shadow-md border border-gray-200 rounded-lg transition duration-300 hover:shadow-xl">
            <div class="w-full flex p-4 lg:p-8">
              <div class="text-center"><?php _e('Sorry, no posts matched your criteria.'); ?></div>
            </div>
          </div>
        </li>
      <?php endif; ?>
    </ul>
  </div>

</section>

<?php
get_footer();
