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
        'class' => array('hidden-sm', 'hidden-xs'),
      );

      $menu_toggle = '<dl class="menu-toggle"><dt class="open"><span>&#9660;</span></dt><dd class="open"><span>Open menu</span></dd><dt class="close"><span>&#9650;</span></dt><dd class="close"><span>Close menu</span></dd></dl>';

      $data['content']['#content'] = array(
        'menu' => array(
          '#prefix' => $menu_toggle . '<div class="hidden-sm hidden-xs menu-root">',
          'content' => $data['content']['#content'],
          '#suffix' => '</div>',
        ),
        'select' => array(
          '#name' => 'select',
          '#type' => 'select',
          '#options' => $options,
          '#value' => $default_value,
          '#attributes' => array(
            'class' => array('select-menu', 'visible-sm', 'visible-xs'),
            'onChange' => 'window.location.replace(this.options[this.selectedIndex].value);',
          ),
        ),
      );
    }
  }

}

/**
 * Themes messages with Bootstrap
 * TODO use Bootstrap JS for closing
 */
function boots_grid_status_messages($variables) {
  $display = $variables['display'];
  $output = '';

  $status_heading = array(
    'status' => t('Status message'),
    'error' => t('Error message'),
    'warning' => t('Warning message'),
  );
  $class_rename = array(
    'status' => 'alert-success',
    'error' => 'alert-error',
    'warning' => '',
  );
  foreach (drupal_get_messages($display) as $type => $messages) {
    $classes = 'block-inner alert alert-block';
    if (!empty($class_rename)) {
      $classes .= ' ' . $class_rename[$type];
    }
    $output .= '<div data-type="region" data-name="console" class="console-' . $type . "\">\n<div class=\"container\"><div class=\"row\">";
    $output .= '<aside class="col-xs-12 col-sm-12 col-md-12 col-lg-12 clearfix"><div class="' . $classes . '"><div class="contents">';
    if (!empty($status_heading[$type])) {
      $output .= '<h3 class="caption status-type">' . $status_heading[$type] . "</h3>\n";
    }
    $output .= '<a class="dismiss" data-dismiss="alert" data-selector=".alert-block" href="#"><span>Dismiss</span></a>';
    if (count($messages) > 1) {
      $output .= ' <div class="messages"><ul>' . "\n";
      foreach ($messages as $message) {
        $output .= '  <li>' . $message . "</li>\n";
      }
      $output .= " </ul></div>\n";
    }
    else {
      $output .= '<div class="messages"><p>' . $messages[0] . '</p></div>';
    }
    $output .= "</div></div></aside>";
    $output .= "</div></div></div>\n";
  }
  return $output;
}

function boots_grid_menu_link($variables) {
  $element = $variables['element'];
  $sub_menu = '';

  if ($element['#below']) {
    $sub_menu = '<div class="submenu-toggle"><a href="#"><strong class="when-closed">+</strong><strong class="when-open">&minus;</strong><span> Submenu</span></a></div>';
    $sub_menu .= drupal_render($element['#below']);
  }

  $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}
