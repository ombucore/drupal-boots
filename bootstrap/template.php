<?php

/**
 * Preprocess variables for region.tpl.php
 *
 * @see region.tpl.php
 */
function bootstrap_preprocess_region(&$variables, $hook) {
  if ($variables['region'] == "header") {
    $variables['classes_array'][] = 'navbar';
    $variables['classes_array'][] = 'navbar-inverse';
    $variables['classes_array'][] = 'navbar-fixed-top';
    $variables['content'] = '<div class="container">' .
        $variables['content'] . '</div>';
  }
}

/**
 * Implements theme_preprocess_block().
 */
function bootstrap_preprocess_block(&$variables) {
  $block = $variables['block'];

  if ($block->region == 'header') {
    $variables['title_attributes_array']['class'][] = 'sr-only';
  }
  if ($block->region == 'sidebar_first') {
    $variables['classes_array'][] = 'well';
    if ($block->module == 'system' && $block->delta == 'main-menu') {
      $variables['classes_array'][] = 'sidebar-nav';
    }
  }
  if ($block->module == 'ombucleanup' && $block->delta == 'site_logo') {
    $variables['classes_array'][] = 'navbar-header';
  }
  if ($block->module == 'menu' && $block->delta == 'header-menu') {
    $variables['classes_array'][] = 'collapse navbar-collapse';
    $variables['classes_array'][] = 'collapse';
  }
}

/**
 * Default implementation for site logo block theme function.
 */
function bootstrap_ombucleanup_site_logo($variables) {
  return l($variables['site_name'], '<front>', array(
    'attributes' => array(
      'class' => array('navbar-brand'),
    )
  ));
}


/**
 * Makes menu items navbars
 * This works here because the only menu on the OMBU demo site is a navbar,
 * but we need a better solution.
 */
function bootstrap_menu_tree(&$variables) {
  return '<ul class="nav navbar-nav">' . $variables['tree'] . '</ul>';
}
