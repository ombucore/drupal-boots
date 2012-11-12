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
    $variables['content'] = '<div class="navbar-inner"><div class="container-fluid">' .
        $variables['content'] . '</div></div>';
  }
}

/**
 * Implements theme_preprocess_block().
 */
function bootstrap_preprocess_block(&$variables) {
  $block = $variables['block'];
  $title_classes = &$variables['title_attributes_array']['class'] || array();

  if ($block->region == 'header') {
    $variables['title_attributes_array']['class'] = 'element-invisible';
  }
  if ($block->region == 'sidebar_first') {
    $variables['classes_array'][] = 'well';
    if ($block->module == 'system' && $block->delta == 'main-menu') {
      $variables['classes_array'][] = 'sidebar-nav';
    }
  }
}
