<?php

/**
 * Implements hook_preprocess_page().
 */
function bootstrap_preprocess_page(&$variables) {
  $bootstrap_path = drupal_get_path('theme', 'boots_core') . '/../lib/bootstrap';
  drupal_add_js($bootstrap_path . '/js/transition.js');
  drupal_add_js($bootstrap_path . '/js/dropdown.js');
}

/**
 * Implements hook_preprocess_region().
 *
 * @see region.tpl.php
 */
function bootstrap_preprocess_region(&$variables) {
  switch ($variables['region']) {
    case 'header':
      $variables['classes_array'][] = 'navbar';
      $variables['classes_array'][] = 'navbar-inverse';
      $variables['classes_array'][] = 'navbar-fixed-top';

      $variables['site_logo'] = theme('ombucleanup_site_logo', array(
        'site_name' => variable_get('site_name', 'Site Name'),
      ));

      $variables['header_menu'] = menu_tree('header-menu');
      $variables['search'] = drupal_get_form('search_block_form');
      break;

    case 'footer':
      $variables['classes_array'][] = 'navbar';
      $variables['classes_array'][] = 'navbar-inverse';

      $variables['copyright'] = t('Copyright !year <a class="navbar-link" href="http://ombuweb.com">OMBU, Inc.</a> All rights reserved.', array(
        '!year' => date('Y'),
      ));
      $variables['footer_menu'] = menu_tree('footer-menu');

      break;
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
 * Overrides theme_menu_link().
 */
function bootstrap_menu_link($variables) {
  $element = $variables['element'];
  $sub_menu = '';

  $link = l($element['#title'], $element['#href'], $element['#localized_options']);

  if ($element['#below']) {
    $sub_menu .= drupal_render($element['#below']);

    $element['#attributes']['class'][] = 'dropdown';
    $link .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="caret"></span></a>';
  }

  return '<li' . drupal_attributes($element['#attributes']) . '>' . $link . $sub_menu . "</li>\n";
}

/**
 * Makes menu items navbars
 * This works here because the only menu on the OMBU demo site is a navbar,
 * but we need a better solution.
 */
function bootstrap_menu_tree__footer_menu($variables) {
  return '<ul class="nav navbar-nav navbar-right">' . $variables['tree'] . '</ul>';
}

/**
 * Makes menu items navbars
 * This works here because the only menu on the OMBU demo site is a navbar,
 * but we need a better solution.
 */
function bootstrap_menu_tree__header_menu($variables) {
  // God I hate theming menu trees. Why is there no freakin' context here as to
  // the tree. There is no way to determine whether the tree is a parent or
  // a child. In order to turn header menu into a proper dropdown, keep a static
  // variable that will be set for any children.
  static $child = TRUE;

  if ($child) {
    $child = FALSE;
    return '<ul class="dropdown-menu" role="menu">' . $variables['tree'] . '</ul>';
  }
  else {
    $child = TRUE;
    return '<ul class="nav navbar-nav">' . $variables['tree'] . '</ul>';
  }
}

/**
 * Makes menu items navbars
 * This works here because the only menu on the OMBU demo site is a navbar,
 * but we need a better solution.
 */
function bootstrap_menu_tree($variables) {
  return '<ul class="nav navbar-nav">' . $variables['tree'] . '</ul>';
}
