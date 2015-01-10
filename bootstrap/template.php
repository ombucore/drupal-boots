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
function bootstrap_preprocess_region(&$variables, $hook) {
  if ($variables['region'] == "header") {
    $variables['classes_array'][] = 'navbar';
    $variables['classes_array'][] = 'navbar-inverse';
    $variables['classes_array'][] = 'navbar-fixed-top';

    $variables['site_logo'] = theme('ombucleanup_site_logo', array(
      'site_name' => variable_get('site_name', 'Site Name'),
    ));

    $variables['header_menu'] = menu_tree('header-menu');
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
 * Overrides theme_menu_link().
 */
function bootstrap_menu_link($variables) {
  $element = $variables['element'];
  $sub_menu = '';

  if ($element['#below']) {
    // Add current link as a child menu item, since bootstrap top level items
    // aren't clickable :(.
    $new_element = $element;
    $new_element['#below'] = array();
    $element['#below'] = array('0' => $new_element) + $element['#below'];
    $sub_menu .= drupal_render($element['#below']);

    $element['#attributes']['class'][] = 'dropdown';
    $element['#localized_options']['attributes']['class'][] = 'dropdown-toggle';
    $element['#localized_options']['attributes']['data-toggle'] = 'dropdown';
    $element['#localized_options']['attributes']['role'] = 'button';
    $element['#localized_options']['attributes']['aria-expanded'] = 'false';
    $element['#localized_options']['html'] = TRUE;
    $element['#title'] .= ' <span class="caret"></span>';

  }

  $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
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
