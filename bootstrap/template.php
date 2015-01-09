<?php

/**
 * Implements hook_preprocess_page().
 */
function bootstrap_preprocess_page(&$variables) {
  $bootstrap_path = drupal_get_path('theme', 'boots_core') . '/../lib/bootstrap';
  drupal_add_js($bootstrap_path . '/js/transition.js');
}

/**
 * Implements hook_preprocess_region().
 *
 * @see region.tpl.php
 */
function bootstrap_preprocess_region(&$variables, $hook) {
  if ($variables['region'] == "header") {
    $variables['classes_array'][] = 'navbar';
    $variables['classes_array'][] = 'navbar-inverse';
    $variables['classes_array'][] = 'navbar-fixed-top';

    $variables['site_logo'] = theme('ombucleanup_site_logo', array(
      'site_name' => variable_get('site_name', 'Site Name'),
    ));

    $variables['header_menu'] = theme('links__header_menu', array(
      'links' => menu_navigation_links('header-menu'),
      'attributes' => array(
        'class' => array('nav', 'navbar-nav', 'navbar-left'),
      ),
    ));

    $variables['search'] = drupal_get_form('search_block_form');
  }
}

/**
 * Implements hook_preprocess_block().
 */
function bootstrap_preprocess_block(&$variables) {
  $block = $variables['block'];

  if ($block->region == 'sidebar_first') {
    $variables['classes_array'][] = 'well';
    if ($block->module == 'system' && $block->delta == 'main-menu') {
      $variables['classes_array'][] = 'sidebar-nav';
    }
  }
}

/**
 * Overrides theme_ombucleanup_site_logo().
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
