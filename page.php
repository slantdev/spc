<?php

get_header();

get_template_part('template-parts/global/page-header', '', array('breadcrumbs' => true));

get_template_part('template-parts/page', 'builder');

get_footer();
