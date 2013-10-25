<?php

/**
 * Returns HTML for an administrative block for display.
 *
 * @param $variables
 *   An associative array containing:
 *   - block: An array containing information about the block:
 *     - show: A Boolean whether to output the block. Defaults to FALSE.
 *     - title: The block's title.
 *     - content: (optional) Formatted content for the block.
 *     - description: (optional) Description of the block. Only output if
 *       'content' is not set.
 *
 * @ingroup themeable
 */
function boots_admin_admin_block($variables) {
  $block = $variables['block'];
  $output = '';

  // Don't display the block if it has no content to display.
  if (empty($block['show'])) {
    return $output;
  }

  $output .= '<aside>';
  if (!empty($block['title'])) {
    $output .= '<h3>' . $block['title'] . '</h3>';
  }
  if (!empty($block['content'])) {
    $output .= '<div class="body">' . $block['content'] . '</div>';
  }
  else {
    $output .= '<div class="description">' . $block['description'] . '</div>';
  }
  $output .= '</aside>';

  return $output;
}

/**
 * Returns HTML for the content of an administrative block.
 *
 * @param $variables
 *   An associative array containing:
 *   - content: An array containing information about the block. Each element
 *     of the array represents an administrative menu item, and must at least
 *     contain the keys 'title', 'href', and 'localized_options', which are
 *     passed to l(). A 'description' key may also be provided.
 *
 * @ingroup themeable
 */
function boots_admin_admin_block_content($variables) {
  $content = $variables['content'];
  $output = '';

  if (!empty($content)) {
    $output .= '<dl>';
    foreach ($content as $item) {
      $output .= '<dt>' . l($item['title'], $item['href'], $item['localized_options']) . '</dt>';
      if (isset($item['description'])) {
        $output .= '<dd>' . filter_xss_admin($item['description']) . '</dd>';
      }
    }
    $output .= '</dl>';
  }
  return $output;
}

function boots_admin_status_report($variables) {
  $requirements = $variables['requirements'];
  $severities = array(
    REQUIREMENT_INFO => array(
      'title' => t('Info'), 
      'class' => 'alert alert-info',
      'icon-class'  => 'icon-info-sign',
    ), 
    REQUIREMENT_OK => array(
      'title' => t('OK'), 
      'class' => 'alert alert-success',
      'icon-class'  => 'icon-ok',
    ), 
    REQUIREMENT_WARNING => array(
      'title' => t('Warning'), 
      'class' => 'alert',
      'icon-class'  => 'icon-question-sign',
    ), 
    REQUIREMENT_ERROR => array(
      'title' => t('Error'), 
      'class' => 'alert alert-error',
      'icon-class'  => 'icon-warning-sign',
    ),
  );
  $output = '<table class="table table-bordered system-status-report">';

  foreach ($requirements as $requirement) {
    if (empty($requirement['#type'])) {
      $severity = $severities[isset($requirement['severity']) ? (int) $requirement['severity'] : 0];
      $severity['icon'] = '<i title="' . $severity['title'] . '" class="' . $severity['icon-class'] . '" "><span class="element-invisible">' . $severity['title'] . '</span></i>';

      // Output table row(s)
      if (!empty($requirement['description'])) {
        $output .= '<tr class="' . $severity['class'] . ' merge-down"><td class="status-icon">' . $severity['icon'] . '</td><td class="status-title">' . $requirement['title'] . '</td><td class="status-value">' . $requirement['value'] . '</td></tr>';
        $output .= '<tr class="' . $severity['class'] . ' merge-up"><td colspan="3" class="status-description">' . $requirement['description'] . '</td></tr>';
      }
      else {
        $output .= '<tr class="' . $severity['class'] . '"><td class="status-icon">' . $severity['icon'] . '</td><td class="status-title">' . $requirement['title'] . '</td><td class="status-value">' . $requirement['value'] . '</td></tr>';
      }
    }
  }

  $output .= '</table>';
  return $output;
}

/**
 * Overrides theme_tiles_tile() to prevent outputting tiles wrappers
 * since Boots Admin doesn't currently use a grid.
 */
function boots_admin_tiles_tile($variables) {
  return $variables['element']['#children'];
}

/**
 * Overrides theme_tiles_row() to prevent outputting tiles wrappers
 * since Boots Admin doesn't currently use a grid.
 */
function boots_admin_tiles_row($variables) {
  return $variables['element']['#children'];
}

/**
 * Overrides theme_tiles_region() to prevent outputting tiles wrappers
 * since Boots Admin doesn't currently use a grid.
 */
function boots_admin_tiles_region($variables) {
  return $variables['element']['#children'];
}
