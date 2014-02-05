<?php

function boots_grid_preprocess_page(&$variables) {
    $variables['regions']  = array('header', 'sidebar_first', 'content_top',
        'content', 'sidebar_second', 'footer');

    // Set the content area width
    $variables['content_col_width_xs']  = 12;
    $variables['content_col_width_sm']  = 12;
    $variables['content_col_width_md']  = 12;
    $variables['content_col_width_lg']  = 12;
    if (!empty($variables['page']['sidebar_first'])) {
        $variables['content_col_width_sm']  -= 3;
        $variables['content_col_width_md']  -= 3;
        $variables['content_col_width_lg']  -= 3;
    }
    if (!empty($variables['page']['sidebar_second'])) {
        $variables['content_col_width_md']  -= 3;
        $variables['content_col_width_lg']  -= 3;
    }
}

function boots_grid_preprocess_block(&$variables) {
    $b = $variables['block'];
    $ca = &$variables['classes_array'];

    // remove some classes
    unset($ca[array_search('block', $ca)]);
    unset($ca[array_search(str_replace('_', '-', 'block-' . $b->module), $ca)]);

    // add grid column class
    if (isset($b->width)) {
      $ca[] = 'col-xs-' . 12;
      $ca[] = 'col-sm-' . $b->width;
      $ca[] = 'col-md-' . $b->width;
      $ca[] = 'col-lg-' . $b->width;
    }
}

function boots_grid_preprocess_region(&$variables) {
    $ca = &$variables['classes_array'];

    // remove some classes
    unset($ca[array_search('region', $ca)]);
    unset($ca[array_search(str_replace('_', '-', 'region-' .
        $variables['region']), $ca)]);

    // Add column to sidebars
    if ($variables['region'] == 'sidebar_first') {
      $variables['classes_array'][] = 'col-xs-12';
      $variables['classes_array'][] = 'col-sm-3';
      $variables['classes_array'][] = 'col-md-3';
      $variables['classes_array'][] = 'col-lg-3';
    }
    elseif ($variables['region'] == 'sidebar_second') {
      $variables['classes_array'][] = 'col-xs-12';
      $variables['classes_array'][] = 'col-sm-12';
      $variables['classes_array'][] = 'col-md-3';
      $variables['classes_array'][] = 'col-lg-3';
    }
}

/**
 * Default implementation of theme_tiles_region().
 */
function boots_grid_tiles_region($variables) {
  return $variables['element']['#children'];
}

/**
 * Default implementation of theme_tiles_row().
 */
function boots_grid_tiles_row($variables) {
  return '<div class="row">' . $variables['element']['#children' ] . '</div>';
}

/**
 * Default implementation of theme_tiles_tile().
 */
function boots_grid_tiles_tile($variables) {
  return $variables['element']['#children'];
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
    if (isset($data['content']['#content'])) {
      foreach (element_children($data['content']['#content']) as $i) {
        $item = $data['content']['#content'][$i];
        $path = $item['#href'];
        $url = url($path);
        if ($path == $_GET['q'] || ($path == '<front>' && drupal_is_front_page())) {
          $default_value = $url;
        }
        $options[$url] = $item['#title'];
      }

      $data['content']['#content']['#attributes'] = array(
        'class' => array('hidden-phone'),
      );
      $data['content']['#content'] = array(
        'menu' => array(
          '#prefix' => '<div class="hidden-phone">',
          'content' => $data['content']['#content'],
          '#suffix' => '</div>',
        ),
        'select' => array(
          '#name' => 'select',
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

}
