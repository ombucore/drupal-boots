<?php
/**
 * @file
 * - Adds Bootstrap markup
 * - Removes some default markup cruft
 */

/**
 * Implements hook_css_alter().
 *
 * Remove core stylesheets that are not necessary.
 */
function boots_core_css_alter(&$css) {
  $blacklist = array(
    'system' => array(
      'defaults.css','system.messages.css', 'system.menus.css',
      'system.theme.css', 'system.admin.css',
    ),
    'node' => 'node.css',
    'user' => 'user.css',
    // 'core' => 'vertical-tabs.css',
    'date_api' => 'date.css',
    'search' => 'search.css',
  );

  foreach ($blacklist as $module => $value) {
    if(is_array($value)) {
      foreach($value as $v) {
        unset($css[drupal_get_path('module', $module) . '/' . $v]);
      }
    }
    else {
      if($module == 'core') {
        unset($css['misc/' . $value]);
      }
      else {
        unset($css[drupal_get_path('module', $module) . '/' . $value]);
      }
    }
  }
}

/**
 * Implements hook_form_alter().
 */
function boots_core_form_alter(&$form, $form_state, $form_id) {
  if ($form_id == 'search_form') {
    $form['basic']['keys']['#attributes']['placeholder'] = t('Search');
  }
  elseif ($form_id == 'search_block_form') {
    $form['search_block_form']['#attributes']['placeholder'] = t('Search');
  }
}

/**
 * Returns HTML for a breadcrumb trail.
 *
 * @param $variables
 *   An associative array containing:
 *   - breadcrumb: An array containing the breadcrumb links.
 * @see theme_breadcrumb
 */
function boots_core_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];
  if (!empty($breadcrumb)) {
    // Fix breadcrumbs for Apachesolr searches.
    if (module_exists('apachesolr_search') && arg(0) == 'search') {
      // If site is being searched (i.e. not on the search landing page), remove
      // duplicate third breadcrumb.
      if (arg(2) || isset($_GET['f'])) {
        unset($breadcrumb[2]);
      }
      else {
        // Remove the second duplicate breadcrumb and append page title.
        unset($breadcrumb[1]);
        $breadcrumb[] = drupal_get_title();
      }
    }
    elseif (variable_get('breadcrumb_show_page_title', FALSE)) {
      $breadcrumb[] = drupal_get_title();
    }
    $output = '<ul class="breadcrumb"><li>' . implode(' <span class="divider">/</span></li><li>', $breadcrumb) . '</li></ul>';
    return $output;
  }
}

/**
 * Themes the pager with Bootstrap markup
 */
function boots_core_pager($variables) {
  $tags = $variables['tags'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  $quantity = $variables['quantity'];
  global $pager_page_array, $pager_total;

  // Calculate various markers within this pager piece:
  // Middle is used to "center" pages around the current page.
  $pager_middle = ceil($quantity / 2);
  // current is the page we are currently paged to
  $pager_current = $pager_page_array[$element] + 1;
  // first is the first page listed by this pager piece (re quantity)
  $pager_first = $pager_current - $pager_middle + 1;
  // last is the last page listed by this pager piece (re quantity)
  $pager_last = $pager_current + $quantity - $pager_middle;
  // max is the maximum page number
  $pager_max = $pager_total[$element];
  // End of marker calculations.

  // Prepare for generation loop.
  $i = $pager_first;
  if ($pager_last > $pager_max) {
    // Adjust "center" if at end of query.
    $i = $i + ($pager_max - $pager_last);
    $pager_last = $pager_max;
  }
  if ($i <= 0) {
    // Adjust "center" if at start of query.
    $pager_last = $pager_last + (1 - $i);
    $i = 1;
  }
  // End of generation loop preparation.

  $li_previous = theme('pager_previous', array('text' => (isset($tags[1]) ? $tags[1] : t('← previous')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_next = theme('pager_next', array('text' => (isset($tags[3]) ? $tags[3] : t('next →')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));

  if ($pager_total[$element] > 1) {
    if ($li_previous) {
      $items[] = array(
        'class' => array('pager-previous'),
        'data' => $li_previous,
      );
    }

    // When there is more than one page, create the pager list.
    if ($i != $pager_max) {
      if ($i > 1) {
        $items[] = array(
          'class' => array('pager-ellipsis'),
          'data' => '<a href="#">…</a>',
        );
      }
      // Now generate the actual pager piece.
      for (; $i <= $pager_last && $i <= $pager_max; $i++) {
        if ($i < $pager_current) {
          $items[] = array(
            'class' => array('pager-item'),
            'data' => theme('pager_previous', array('text' => $i, 'element' => $element, 'interval' => ($pager_current - $i), 'parameters' => $parameters)),
          );
        }
        if ($i == $pager_current) {
          $items[] = array(
            'class' => array('active'),
            'data' => '<a href="#">' . $i . '</a>',
          );
        }
        if ($i > $pager_current) {
          $items[] = array(
            'class' => array('pager-item'),
            'data' => theme('pager_next', array('text' => $i, 'element' => $element, 'interval' => ($i - $pager_current), 'parameters' => $parameters)),
          );
        }
      }
      if ($i < $pager_max) {
        $items[] = array(
          'class' => array('pager-ellipsis disabled'),
          'data' => '<a href="#">…</a>',
        );
      }
    }
    // End generation.
    if ($li_next) {
      $items[] = array(
        'class' => array('pager-next'),
        'data' => $li_next,
      );
    }
    return '<h2 class="element-invisible">' . t('Pages') . '</h2>' . '<div class="pagination">'. theme('item_list', array(
      'items' => $items,
    )) . '</div>';
  }
}

/**
 * Adds `btn` class to all submit buttons with special treatment for Save &
 * Delete buttons
 */
function boots_core_preprocess_button(&$variables) {
  $variables['element']['#attributes']['class'] = array();
  $variables['element']['#attributes']['class'][] = 'btn';

  // Primary Buttons
  $btn_primary_class = 'btn-primary';
  if (stristr($variables['element']['#value'], 'save') !== FALSE) {
    $variables['element']['#attributes']['class'][] = $btn_primary_class;
  }
  if (stristr($variables['element']['#value'], 'create') !== FALSE) {
    $variables['element']['#attributes']['class'][] = $btn_primary_class;
  }

  // Danger Buttons
  if (stristr($variables['element']['#value'], 'Delete') !== FALSE) {
    $variables['element']['#attributes']['class'][] = 'btn-danger';
  }
}

/**
 * Themes messages with Bootstrap
 * TODO use Bootstrap JS for closing
 */
function boots_core_status_messages($variables) {
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
    $classes = 'alert alert-block';
    if (!empty($class_rename)) {
      $classes .= ' ' . $class_rename[$type];
    }
    $output .= '<div class="' . $classes . "\">\n";
    if (!empty($status_heading[$type])) {
      $output .= '<h2 class="element-invisible">' . $status_heading[$type] . "</h2>\n";
    }
    $output .= '<a class="close" data-dismiss="alert" href="#">&times;</a>';
    if (count($messages) > 1) {
      $output .= " <ul>\n";
      foreach ($messages as $message) {
        $output .= '  <li>' . $message . "</li>\n";
      }
      $output .= " </ul>\n";
    }
    else {
      $output .= $messages[0];
    }
    $output .= "</div>\n";
  }
  return $output;
}

// Action Links into Button Group
function boots_core_menu_local_action($variables) {
  $link = $variables['element']['#link'];

  $link['localized_options']['attributes']['class'][]='btn btn-small';
  $output = '';
  if (isset($link['href'])) {
    $output .= l($link['title'], $link['href'], isset($link['localized_options']) ? $link['localized_options'] : array());
  }
  elseif (!empty($link['localized_options']['html'])) {
    $output .= $link['title'];
  }
  else {
    $output .= check_plain($link['title']);
  }

  return $output;
}

/**
 * Implements theme_preprocess_block().
 */
function boots_core_preprocess_block(&$variables) {
  $title_classes = &$variables['title_attributes_array']['class'];
  $block = $variables['block'];

  if ($block->module == 'system' && $block->delta == 'main-menu') {
    $title_classes[] = 'nav-header';
  }

  $variables['attributes_array']['data-module'] = $variables['block']->module;
  $variables['attributes_array']['data-delta'] = $variables['block']->delta;
}

/**
 * Themes menu wrappers with Bootstrap
 */
function boots_core_menu_tree(&$variables) {
  return '<ul class="nav">' . $variables['tree'] . '</ul>';
}

/**
 * Implements hook_preprocess_menu_link.
 */
function boots_core_preprocess_menu_link(&$variables) {
  // Add .active class to <li>.  Checks if <li> is in active-trail, but since
  // theme_menu_link() doesn't add active-trail to homepage menu items, also
  // need to check if link path matches current path.
  $path = $variables['element']['#href'];
  if (
    isset($variables['element']['#attributes']['class']) && in_array('active-trail', $variables['element']['#attributes']['class']) ||
    ($path == $_GET['q'] || ($path == '<front>' && drupal_is_front_page()))
  ) {
    $variables['element']['#attributes']['class'][] = 'active';
  }
}

/**
 * Themes tables with Bootstrap
 */
 function boots_core_preprocess_table(&$variables) {
  $variables['attributes']['class'][] = 'table table-striped table-bordered';
}

/**
 * Themes draggableviews tables with Bootstrap
 */
 function boots_core_preprocess_draggableviews_view_draggabletable(&$variables) {
  $variables['classes_array'][] = 'table table-striped table-bordered';
}

/**
 * Themes item lists with Bootstrap
 */
function boots_core_item_list($variables) {
  $items = $variables['items'];
  $title = $variables['title'];
  $type = $variables['type'];
  $attributes = $variables['attributes'];

  $output = '';
  if (isset($title)) {
    $output .= '<h3>' . $title . '</h3>';
  }

  if (!empty($items)) {
    $output .= "<$type" . drupal_attributes($attributes) . '>';
    $num_items = count($items);
    foreach ($items as $i => $item) {
      $attributes = array();
      $children = array();
      $data = '';
      if (is_array($item)) {
        foreach ($item as $key => $value) {
          if ($key == 'data') {
            $data = $value;
          }
          elseif ($key == 'children') {
            $children = $value;
          }
          else {
            $attributes[$key] = $value;
          }
        }
      }
      else {
        $data = $item;
      }
      if (count($children) > 0) {
        // Render nested list.
        $data .= theme_item_list(array('items' => $children, 'title' => NULL, 'type' => $type, 'attributes' => $attributes));
      }
      if ($i == 0) {
        $attributes['class'][] = 'first';
      }
      if ($i == $num_items - 1) {
        $attributes['class'][] = 'last';
      }
      $output .= '<li' . drupal_attributes($attributes) . '>' . $data . "</li>\n";
    }
    $output .= "</$type>";
  }
  return $output;

}

/**
 * Bean Containers: User Bootstrap tab js.
 */
function boots_core_preprocess_bean_container(&$variables) {
  $bootstrap_path = drupal_get_path('theme', 'boots_core') . '/bootstrap';
  drupal_add_js($bootstrap_path . '/js/bootstrap-tab.js');
}

/**
 * Themes bean containers with Bootstrap
 */
function boots_core_bean_container($variables) {
  $children = $variables['children'];
  $parent = $variables['parent'];
  $output = '';

  if (empty($children)) {
    if (user_access('edit any bean_container bean')) {
      $output .= t('This is an empty block container. You can add blocks to it by clicking <a href="!url">"Manage Children"</a> on the container cog menu', array(
        '!url' => url($variables['parent']->url() . '/manage-children'),
      ));
    }
    return $output;
  }

  if ($variables['display_type'] == 'tab') {
    $output .= '<div class="tabbable">';

    $nav = array();
    $items = array();
    foreach ($children as $key => $child) {
      // Generate nav.
      $nav[] = array(
        'data' => '<a data-toggle="tab" href="#' . $parent->delta . '-' . $key . '">' . $child->title . '</a>',
        'class' => $key == 0 ? array('active') : array(),
      );

      // Generate items.
      $content = $child->view();
      $content['#prefix'] = '<div class="' . drupal_clean_css_identifier($child->type) . '">';
      $content['#suffix'] = '</div>';
      $item_output = '<div class="tab-pane' . ($key == 0 ? ' active' : '') . '" id="' . $parent->delta . '-' . $key . '">';
      $item_output .= drupal_render($content);
      $item_output .= '</div>';
      $items[] = $item_output;
    }

    $output .= theme('item_list', array(
      'items' => $nav,
      'attributes' => array(
        'class' => array('nav', 'nav-tabs'),
      ),
    ));

    $output .= '<div class="tab-content">' . join('', $items) . '</div>';

    $output .= '</div>';
  }
  else {
    $output = '';
    foreach ($children as $key => $child) {
      $content = $child->view();
      $content['#prefix'] = '<div class="' . drupal_clean_css_identifier($child->type) . '">';
      if (!empty($child->title)) {
        $content['#prefix'] .= '<h2>' . $child->title . '</h2>';
      }
      $content['#suffix'] = '</div>';
      $output .= drupal_render($content);
    }
  }

  return $output;
}

/**
 * Implements hook_preprocess_field().
 */
function boots_core_preprocess_field(&$variables) {
  if ($variables['element']['#field_type'] == 'text_with_summary' || $variables['element']['#field_type'] == 'text_long') {
    $variables['classes_array'][] = 'c';
  }
}

/**
 * Form Elements
 */

/**
 * Returns HTML for a form element.
 *
 * Each form element is wrapped in a DIV container having the following CSS
 * classes:
 * - form-item: Generic for all form elements.
 * - form-type-#type: The internal element #type.
 * - form-item-#name: The internal form element #name (usually derived from the
 *   $form structure and set via form_builder()).
 * - form-disabled: Only set if the form element is #disabled.
 *
 * In addition to the element itself, the DIV contains a label for the element
 * based on the optional #title_display property, and an optional #description.
 *
 * The optional #title_display property can have these values:
 * - before: The label is output before the element. This is the default.
 *   The label includes the #title and the required marker, if #required.
 * - after: The label is output after the element. For example, this is used
 *   for radio and checkbox #type elements as set in system_element_info().
 *   If the #title is empty but the field is #required, the label will
 *   contain only the required marker.
 * - invisible: Labels are critical for screen readers to enable them to
 *   properly navigate through forms but can be visually distracting. This
 *   property hides the label for everyone except screen readers.
 * - attribute: Set the title attribute on the element to create a tooltip
 *   but output no label element. This is supported only for checkboxes
 *   and radios in form_pre_render_conditional_form_element(). It is used
 *   where a visual label is not needed, such as a table of checkboxes where
 *   the row and column provide the context. The tooltip will include the
 *   title and required marker.
 *
 * If the #title property is not set, then the label and any required marker
 * will not be output, regardless of the #title_display or #required values.
 * This can be useful in cases such as the password_confirm element, which
 * creates children elements that have their own labels and required markers,
 * but the parent element should have neither. Use this carefully because a
 * field without an associated label can cause accessibility challenges.
 *
 * @param $variables
 *   An associative array containing:
 *   - element: An associative array containing the properties of the element.
 *     Properties used: #title, #title_display, #description, #id, #required,
 *     #children, #type, #name.
 *
 * @ingroup themeable
 */
function boots_core_form_element($variables) {
  $element = &$variables['element'];

  // This is also used in the installer, pre-database setup.
  $t = get_t();

  // This function is invoked as theme wrapper, but the rendered form element
  // may not necessarily have been processed by form_builder().
  $element += array(
    '#title_display' => 'before',
  );

  // Run body field through default theme function as a fix for summary field
  // label not being displayed properly.
  if (isset($element['#field_name']) && $element['#field_name'] == 'body') {
    return theme_form_element($variables);
  }

  // Allow types to simply passthru theme handling to field theme function.
  // E.g. for checkbox all label handling is handled in theme_checkbox().
  $passthru_types = array(
    'checkbox',
    'radio',
  );
  if (in_array($element['#type'], $passthru_types)) {
    $output = $element['#children'];
    if (!empty($element['#description'])) {
      $output .= '<p class="help-block">' . $element['#description'] . "</p>\n";
    }
  }
  else {
    // Add element #id for #type 'item'.
    if (isset($element['#markup']) && !empty($element['#id'])) {
      $attributes['id'] = $element['#id'];
    }
    // Add element's #type and #name as class to aid with JS/CSS selectors.
    $attributes['class'] = array('control-group', 'form-item');
    if (!empty($element['#type'])) {
      $attributes['class'][] = 'form-type-' . strtr($element['#type'], '_', '-');
    }
    if (!empty($element['#name'])) {
      $attributes['class'][] = 'form-item-' . strtr($element['#name'], array(' ' => '-', '_' => '-', '[' => '-', ']' => ''));
    }
    // Add a class for disabled elements to facilitate cross-browser styling.
    if (!empty($element['#attributes']['disabled'])) {
      $attributes['class'][] = 'form-disabled';
    }
    // Add an error to control group.
    if (isset($element['#parents']) && form_get_error($element)) {
      $attributes['class'][] = 'error';
    }
    $output = '<div' . drupal_attributes($attributes) . '>' . "\n";

    // If #title is not set, we don't display any label or required marker.
    if (!isset($element['#title'])) {
      $element['#title_display'] = 'none';
    }
    $prefix = isset($element['#field_prefix']) ? '<span class="field-prefix">' . $element['#field_prefix'] . '</span> ' : '';
    $suffix = isset($element['#field_suffix']) ? ' <span class="field-suffix">' . $element['#field_suffix'] . '</span>' : '';

    switch ($element['#title_display']) {
      case 'before':
      case 'invisible':
        $output .= ' ' . theme('form_element_label', $variables);

        $output .= '<div class="controls">';
        $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
        // Add inline errors.
        // @todo: figure out how to remove errors from messages area.
        // if (($error = form_get_error($element))) {
        //   $output .= '<span class="help-inline">' . $error . '</span>';
        // }
        if (!empty($element['#description'])) {
          $output .= '<p class="help-block">' . $element['#description'] . "</p>\n";
        }
        $output .= '</div>';

        break;

      case 'after':
        $output .= ' ' . $prefix . $element['#children'] . $suffix;
        $output .= ' ' . theme('form_element_label', $variables) . "\n";
        // Displays error messages inline.
        // @todo: figure out how to remove individual errors from message block.
        // if (($error = form_get_error($element))) {
        //   $output .= '<span class="help-inline">' . $error . '</span>';
        // }
        if (!empty($element['#description'])) {
          $output .= '<p class="help-block">' . $element['#description'] . "</p>\n";
        }

        break;

      case 'none':
      case 'attribute':
        // Output no label and no required marker, only the children.
        $output .= '<div class="controls">';
        $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
        if (!empty($element['#description'])) {
          $output .= '<p class="help-block">' . $element['#description'] . "</p>\n";
        }
        $output .= '</div>';

        break;
    }

    $output .= "</div>\n";
  }

  return $output;
}

/**
 * Returns HTML for a form element label and required marker.
 *
 * Form element labels include the #title and a #required marker. The label is
 * associated with the element itself by the element #id. Labels may appear
 * before or after elements, depending on theme_form_element() and #title_display.
 *
 * This function will not be called for elements with no labels, depending on
 * #title_display. For elements that have an empty #title and are not required,
 * this function will output no label (''). For required elements that have an
 * empty #title, this will output the required marker alone within the label.
 * The label will use the #id to associate the marker with the field that is
 * required. That is especially important for screenreader users to know
 * which field is required.
 *
 * @param $variables
 *   An associative array containing:
 *   - element: An associative array containing the properties of the element.
 *     Properties used: #required, #title, #id, #value, #description.
 *
 * @ingroup themeable
 */
function boots_core_form_element_label($variables) {
  $element = $variables['element'];
  // This is also used in the installer, pre-database setup.
  $t = get_t();

  // If title and required marker are both empty, output no label.
  if ((!isset($element['#title']) || $element['#title'] === '') && empty($element['#required'])) {
    return '';
  }

  // If the element is required, a required marker is appended to the label.
  $required = !empty($element['#required']) ? theme('form_required_marker', array('element' => $element)) : '';

  $title = filter_xss_admin($element['#title']);

  $attributes = array();
  // Add bootstrap class
  $attributes['class'] = 'control-label';

  // Style the label as class option to display inline with the element.
  if ($element['#title_display'] == 'after') {
    $attributes['class'] .= ' option';
  }
  // Show label only to screen readers to avoid disruption in visual flows.
  elseif ($element['#title_display'] == 'invisible') {
    $attributes['class'] .= ' element-invisible';
  }


  if (!empty($element['#id'])) {
    $attributes['for'] = $element['#id'];
  }

  // The leading whitespace helps visually separate fields from inline labels.
  return ' <label' . drupal_attributes($attributes) . '>' . $t('!title !required', array('!title' => $title, '!required' => $required)) . "</label>\n";
}

/**
 * Overrides theme_checkboxes.
 */
function boots_core_checkboxes($variables) {
  $element = $variables['element'];
  $attributes = array();
  if (isset($element['#id'])) {
    $attributes['id'] = $element['#id'];
  }
  $attributes['class'][] = 'form-checkboxes';
  if (!empty($element['#attributes']['class'])) {
    $attributes['class'] = array_merge($attributes['class'], $element['#attributes']['class']);
  }
  if (isset($element['#attributes']['title'])) {
    $attributes['title'] = $element['#attributes']['title'];
  }
  return '<div' . drupal_attributes($attributes) . '>' . (!empty($element['#children']) ? $element['#children'] : '') . '</div>';
}

/**
 * Overrides theme_checkbox.
 */
function boots_core_checkbox($variables) {
  $element = $variables['element'];
  $t = get_t();
  $element['#attributes']['type'] = 'checkbox';
  element_set_attributes($element, array('id', 'name', '#return_value' => 'value'));

  // Unchecked checkbox has #value of integer 0.
  if (!empty($element['#checked'])) {
    $element['#attributes']['checked'] = 'checked';
  }
  _form_set_class($element, array('form-checkbox'));

  $classes = 'checkbox';

  if (isset($element['#parents']) && form_get_error($element)) {
    $classes .= ' error';
  }

  // Always wrap checkbox with label (overrides default title handling from
  // theme_form_element()).
  if (isset($element['#title']) && $element['#title'] && $element['#title_display'] == 'after') {
    $output = '<label class="' . $classes . '">';
    $output .= '<input' . drupal_attributes($element['#attributes']) . ' />';
    $output .= '<span>' . $element['#title'] . '</span>';
    $output .= '</label>';
  }
  else {
    $output = '<input' . drupal_attributes($element['#attributes']) . ' />';
  }

  return $output;
}

/**
 * Overrides theme_radio.
 */
function boots_core_radio($variables) {
  $element = $variables['element'];
  $element['#attributes']['type'] = 'radio';
  element_set_attributes($element, array('id', 'name', '#return_value' => 'value'));

  if (isset($element['#return_value']) && $element['#value'] !== FALSE && $element['#value'] == $element['#return_value']) {
    $element['#attributes']['checked'] = 'checked';
  }
  _form_set_class($element, array('form-radio'));

  // Always wrap radio with label (overrides default title handling from
  // theme_form_element()).
  if ($element['#title']) {
    $output = '<label class="radio">';
    $output .= '<input' . drupal_attributes($element['#attributes']) . ' />';
    $output .= '<span>' . $element['#title'] . '</span>';
    $output .= '</label>';
  }
  else {
    $output = '<input' . drupal_attributes($element['#attributes']) . ' />';
  }

  return $output;
}

/**
 * Themes the progress bar with Bootstrap markup
 */
function boots_core_progress_bar($variables) {
  $output = '<div class="progress progress-striped active">';
  $output .= '<div class="bar" style="width: ' . $variables['percent'] . '%"></div>';
  $output .= '<div class="percentage">' . $variables['percent'] . '%</div>';
  $output .= '<div class="message">' . $variables['message'] . '</div>';
  $output .= '</div>';

  return $output;
}

/**
 * Overrides theme_facetapi_link_active().
 */
function boots_core_facetapi_link_active($variables) {

  // Sanitizes the link text if necessary.
  $sanitize = empty($variables['options']['html']);
  $link_text = ($sanitize) ? check_plain($variables['text']) : $variables['text'];

  // Theme function variables fro accessible markup.
  // @see http://drupal.org/node/1316580
  $accessible_vars = array(
    'text' => $variables['text'],
    'active' => TRUE,
  );

  // Builds link, passes through t() which gives us the ability to change the
  // position of the widget on a per-language basis.
  $replacements = array(
    '!link_text' => '<span class="term">' . $link_text . '</span>',
    '!facetapi_deactivate_widget' => '<button class="close">&times;</button>',
    '!facetapi_accessible_markup' => theme('facetapi_accessible_markup', $accessible_vars),
  );
  $variables['text'] = t('!link_text !facetapi_deactivate_widget !facetapi_accessible_markup', $replacements);
  $variables['options']['html'] = TRUE;
  return  theme_link($variables);
}
