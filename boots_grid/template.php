<?php

function boots_grid_preprocess_page(&$variables) {
    $variables['regions']  = array('header', 'sidebar_first', 'content_top',
        'content', 'sidebar_second', 'footer');

    // Set the content area width
    $variables['content_col_span']  = 12;
    if($variables['page']['sidebar_first']) {
        $variables['content_col_span']  -= 3;
    }
    if($variables['page']['sidebar_second']) {
        $variables['content_col_span']  -= 3;
    }
}

function boots_grid_preprocess_block(&$variables) {
    $b = $variables['block'];
    $ca = &$variables['classes_array'];

    // remove some classes
    unset($ca[array_search('block', $ca)]);
    unset($ca[array_search(str_replace('_', '-', 'block-' . $b->module), $ca)]);

    // add grid span class
    $w = boots_grid_get_block_width($b->module, $b->delta);
    if($w) {
        $ca[] = 'span' . $w;
    }
}

function boots_grid_preprocess_region(&$variables) {
    $ca = &$variables['classes_array'];

    // remove some classes
    unset($ca[array_search('region', $ca)]);
    unset($ca[array_search(str_replace('_', '-', 'region-' .
        $variables['region']), $ca)]);

    // Add span to sidebars
    if ($variables['region'] == 'sidebar_first') {
      $variables['classes_array'][] = 'span3';
    }
    elseif ($variables['region'] == 'sidebar_second') {
      $variables['classes_array'][] = 'span3';
    }
}

/**
 * Implements hook_page_alter().
 *
 * Wraps blocks in each region with rows based on block widths.
 */
function boots_grid_page_alter(&$page) {
  global $theme;
  if ($theme === variable_get('admin_theme', 'ombuadmin')) {
    return;
  }

  $default_width = 12;
  $max_cols_per_row = $default_width;
  foreach (element_children($page) as $region_key) {
    // Make sure blocks are properly sorted.
    unset($page[$region_key]['#sorted']);
    $region_children = element_children($page[$region_key], TRUE);

    $col_count = $row = 0;
    $row_key = 'row_'. $row;

    $page[$region_key]['#original'] = array();
    $page[$region_key]['rows'] = array();
    $page[$region_key]['rows'][$row_key] = array(
      '#prefix' => '<div class="row-fluid">',
      '#suffix' => '</div>',
    );

    foreach ($region_children as $delta) {

      // Only operate on blocks
      if (!array_key_exists('#block', $page[$region_key][$delta])) {
          continue;
      }

      $block = $page[$region_key][$delta]['#block'];
      $width = boots_grid_get_block_width($block->module, $block->delta);

      if (($col_count + $width) <= $max_cols_per_row) {
        $col_count += $width;
      }
      else {
        $col_count = $width;
        $row++;
        $row_key = 'row_'. $row;
        $page[$region_key]['rows'][$row_key] = array(
          '#prefix' => '<div class="row-fluid">',
          '#suffix' => '</div>',
        );
      }

      // Add block to current row
      $page[$region_key]['rows'][$row_key][$delta] = $page[$region_key][$delta];

      // Stash the block in the #original key
      $page[$region_key]['#original'][$delta] = $page[$region_key][$delta];

      // Remove block from old position in region
      unset($page[$region_key][$delta]);
    }
  }
}

/**
 * Returns a block's width
 *
 * The block's width is determined by:
 * - Checking for values from hook_block_widths().
 * - Checking if it is an ombublock that has a width value. (overwrites
 *   previous)
 * - Setting to default if none of the above produced a width.
 *
 * @return int $width
 */
function boots_grid_get_block_width($module, $delta) {

  $default_width = 12;
  $width = FALSE;
  $block_widths = module_invoke_all('block_widths');

  // Get width from hook_block_widths().
  if (isset($block_widths[$module][$delta])) {
    $width = (int) $block_widths[$module][$delta];
  }

  // Get width from tiles
  if (function_exists('tiles_is_tile') && tiles_is_tile($module, $delta)) {
    $tile_width = tiles_block_get_width($module, $delta);
    if ($tile_width) {
      $width = $tile_width;
    }
  }

  if ($width === FALSE) {
    $width = $default_width;
  }

  return $width;
}

/**
 * Implements hook_block_view_alter().
 */
function boots_grid_block_view_alter(&$data, $block) {

  // Add `<select>` menu that mirrors the menu being rendered.
  if (in_array($block->module, array('menu_block'))) {

    $options = array(
      '' => '- Menu -',
    );
    $default_value = '';
    foreach (element_children($data['content']['#content']) as $i) {
      $item = $data['content']['#content'][$i];
      $path = $item['#href'];
      $url = url($path);
      if ($path == $_GET['q'] || ($path == '<front>' && drupal_is_front_page())) {
        $default_value = $url;
      }
      $options[$url] = $item['#title'];
    }

    $data['content']['#content']['attributes'] = array(
      'class' => array('hidden-phone'),
    );
    $data['content']['#content'] = array(
      'menu' => array(
        '#prefix' => '<div class="hidden-phone">',
        'content' => $data['content']['#content'],
        '#suffix' => '</div>',
      ),
      'select' => array(
        '#type' => 'select',
        '#options' => $options,
        '#value' => $default_value,
        '#attributes' => array(
          'class' => array('select-menu', 'visible-phone'),
          'onChange' => 'window.location.replace(this.options[this.selectedIndex].value);',
        ),
      ),
    );
  }

}
