<?php
/**
 * @file
 * Contains theme override functions and preprocess functions for the aguayo theme.
 */

/**
 * Implements hook_css_alter().
 *
 * @TODO: Once http://drupal.org/node/901062 is resolved, determine whether
 * this can be implemented in the .info file instead.
 *
 * Omitted:
 * - color.css
 * - contextual.css
 * - dashboard.css
 * - field_ui.css
 * - image.css
 * - locale.css
 * - shortcut.css
 * - simpletest.css
 * - toolbar.css
 */
function aguayo_css_alter(&$css) {
  $exclude = array(
    // Drupal.
    'misc/vertical-tabs.css' => FALSE,
    'modules/aggregator/aggregator.css' => FALSE,
    'modules/block/block.css' => FALSE,
    'modules/book/book.css' => FALSE,
    'modules/comment/comment.css' => FALSE,
    'modules/dblog/dblog.css' => FALSE,
    'modules/field/theme/field.css' => FALSE,
    'modules/file/file.css' => FALSE,
    'modules/filter/filter.css' => FALSE,
    'modules/forum/forum.css' => FALSE,
    'modules/help/help.css' => FALSE,
    'modules/menu/menu.css' => FALSE,
    'modules/node/node.css' => FALSE,
    'modules/openid/openid.css' => FALSE,
    'modules/poll/poll.css' => FALSE,
    'modules/profile/profile.css' => FALSE,
    'modules/search/search.css' => FALSE,
    'modules/statistics/statistics.css' => FALSE,
    'modules/syslog/syslog.css' => FALSE,
    'modules/system/admin.css' => FALSE,
    'modules/system/maintenance.css' => FALSE,
    'modules/system/system.css' => FALSE,
    'modules/system/system.admin.css' => FALSE,
    'modules/system/system.base.css' => FALSE,
    'modules/system/system.maintenance.css' => FALSE,
    'modules/system/system.menus.css' => FALSE,
    'modules/system/system.messages.css' => FALSE,
    'modules/system/system.theme.css' => FALSE,
    'modules/taxonomy/taxonomy.css' => FALSE,
    'modules/tracker/tracker.css' => FALSE,
    'modules/update/update.css' => FALSE,
    'modules/user/user.css' => FALSE,
    // Contrib.
    drupal_get_path('module', 'field_collection') . '/field_collection.theme.css' => FALSE,
  );
  $css = array_diff_key($css, $exclude);
}

/**
 * Implements hook_html_head_alter().
 * Changes the default meta content-type tag to the shorter HTML5 version
 */
function aguayo_html_head_alter(&$head_elements) {
  $head_elements['system_meta_content_type']['#attributes'] = array(
    'charset' => 'utf-8',
  );
}

/**
 * Changes the search form to use the HTML5 "search" input attribute
 */
function aguayo_preprocess_search_block_form(&$vars) {
  $vars['search_form'] = str_replace('type="text"', 'type="search"', $vars['search_form']);
}

/**
 * Implements hook_process_HOOK().
 *
 * Uses RDFa attributes if the RDF module is enabled
 * Lifted from Adaptivetheme for D7, full credit to Jeff Burnz
 * ref: http://drupal.org/node/887600
 */
function aguayo_preprocess_html(&$vars) {

  // Ensure that the $vars['rdf'] variable is an object.
  if (!isset($vars['rdf']) || !is_object($vars['rdf'])) {
    $vars['rdf'] = new StdClass();
  }

  if (module_exists('rdf')) {
    $vars['doctype'] = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML+RDFa 1.1//EN">' . "\n";
    $vars['rdf']->version = 'version="HTML+RDFa 1.1"';
    $vars['rdf']->namespaces = $vars['rdf_namespaces'];
    $vars['rdf']->profile = ' profile="' . $vars['grddl_profile'] . '"';
  }
  else {
    $vars['doctype'] = '<!DOCTYPE html>' . "\n";
    $vars['rdf']->version = '';
    $vars['rdf']->namespaces = '';
    $vars['rdf']->profile = '';
  }

  // Use html5shiv.
  if (theme_get_setting('html5shim')) {
    $element = array(  
      'element' => array(
        '#tag' => 'script',
        '#value' => '',
        '#attributes' => array(
          'type' => 'text/javascript',
          'src' => file_create_url(drupal_get_path('theme', 'aguayo') . '/js/html5shiv-printshiv.js'),
        ),
      ),
    );
    $html5shim = array(
      '#type' => 'markup',
      '#markup' => "<!--[if lt IE 9]>\n" . theme('html_tag', $element) . "<![endif]-->\n",
    );
    drupal_add_html_head($html5shim, 'aguayo_html5shim');
  }

  // Use css3-mediaqueries-js.
  if (theme_get_setting('css3_mediaqueries')) {
    drupal_add_js('//css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js', array('type' => 'external', 'group' => JS_LIBRARY, 'weight' => -100));
  }

  // Use normalize.css
  if (theme_get_setting('normalize_css')) {
    drupal_add_css(drupal_get_path('theme', 'aguayo') . '/css/normalize.css', array('group' => CSS_SYSTEM, 'weight' => -100));
  }

  if (!module_exists('aguayo_tools')) {
    // We depend on aguayo_tools for many things.
    $vars['page']['page_top']['aguayo_tools_error'] = array(
      '#markup' => '<div="aguayo-tools-error">' . t('Module aguayo Tools (!aguayo_tools) is required for this theme to work properly', array('!aguayo_tools' => l('aguayo_tools', 'http://drupal.org/project/aguayo_tools'))) . "</div>",
    );
  }
}

/**
 * Implements hook_THEME().
 *
 * Return a themed breadcrumb trail.
 *
 * @param (array) $vars
 *   An array containing the breadcrumb links.
 *
 * @return string
 *   A string containing the breadcrumb output.
 */
function aguayo_breadcrumb($vars) {
  $breadcrumb = $vars['breadcrumb'];
  // Determine if we are to display the breadcrumb.
  $show_breadcrumb = theme_get_setting('breadcrumb_display');
  if ($show_breadcrumb == 'yes') {

    // Optionally get rid of the homepage link.
    $show_breadcrumb_home = theme_get_setting('breadcrumb_home');
    if (!$show_breadcrumb_home) {
      array_shift($breadcrumb);
    }

    // Return the breadcrumb with separators.
    if (!empty($breadcrumb)) {
      $separator = filter_xss(theme_get_setting('breadcrumb_separator'));
      $trailing_separator = $title = '';

      // Add the title and trailing separator.
      if (theme_get_setting('breadcrumb_title')) {
        if ($title = drupal_get_title()) {
          $trailing_separator = $separator;
        }
      }
      // Just add the trailing separator.
      elseif (theme_get_setting('breadcrumb_trailing')) {
        $trailing_separator = $separator;
      }

      // Assemble the breadcrumb.
      return implode($separator, $breadcrumb) . $trailing_separator . $title;
    }
  }
  // Otherwise, return an empty string.
  return '';
}

/**
 * Implements hook_preprocess_HOOK().
 *
 * Save variables to be printed/used in blocks instead of in page.tpl.php
 */
function aguayo_preprocess_page(&$vars) {
  $aguayo = &drupal_static('aguayo');
  if (isset($aguayo['messages_as_block']) && $aguayo['messages_as_block']) {
    $aguayo['show_messages'] = $vars['show_messages'];
    // If no messages yet, save as FALSE so that template_process_page does invoke
    // theme('status_messages')
    if (!isset($vars['messages'])) {
      $vars['messages'] = FALSE;
    }
  }
  unset($aguayo['messages_as_block']);
}

/**
 * Implements hook_process_HOOK().
 *
 * Save variables to be printed/used in blocks instead of in page.tpl.php
 */
function aguayo_process_page(&$vars) {
  $aguayo = &drupal_static('aguayo');
  $aguayo['title_prefix'] = $vars['title_prefix'];
  $aguayo['title_suffix'] = $vars['title_suffix'];
  $aguayo['title'] = $vars['title'];
}

/**
 * Process blocks created with aguayo_tools:
 *  - aguayo_messages
 *  - aguayo_messages
 *  - aguayo_title
 */
function aguayo_process_block(&$vars) {
  if ($vars['block']->module == 'aguayo_tools') {
    $aguayo = &drupal_static('aguayo');
    switch ($vars['block']->delta) {
      case 'aguayo_breadcrumb':
        $vars['content'] = '<div id="breadcrumb">' . theme('breadcrumb', array('breadcrumb' => drupal_get_breadcrumb())) . '</div>';
        break;

      case 'aguayo_messages':
        if ($aguayo['show_messages']) {
          $vars['content'] = theme('status_messages');
          unset($aguayo['show_messages']);
        }
        break;

      case 'aguayo_title':
        $vars['content'] = render($aguayo['title_prefix']);
        if ($aguayo['title']) {
          $vars['content'] .= '<h1 class="title" id="page-title">' . $aguayo['title'] . '</h1>';
        }
        $vars['content'] .= render($aguayo['title_suffix']);
        unset($aguayo['title_prefix']);
        unset($aguayo['title']);
        unset($aguayo['title_suffix']);
        break;
    }
  }
}

/**
 * Implements hook_preprocess_HOOK().
 * Size set using CSS
 */
function aguayo_preprocess_textfield(&$vars) {
  $vars['element']['#size'] = NULL;
}

/**
 * Implements hook_preprocess_HOOK().
 * Size set using CSS
 */
function aguayo_preprocess_password(&$vars) {
  $vars['element']['#size'] = NULL;
}

/**
 * Implements hook_preprocess_HOOK().
 * Size set using CSS
 */
function aguayo_preprocess_file(&$vars) {
  $vars['element']['#size'] = NULL;
}

/**
 * Implements hook_preprocess_HOOK().
 */
function aguayo_preprocess_block(&$vars, $hook) {
  $vars['classes_array'][] = drupal_html_class('block-' . $vars['block']->bid);
}

/**
 * Implements hook_process_HOOK().
 */
function aguayo_process_pager(&$vars) {
  $original = isset($vars['tags']) ? $vars['tags'] : array();

  $tags = array(
    0 => FALSE,
    1 => FALSE,
    3 => FALSE,
    4 => FALSE,
  );

  if (theme_get_setting('pager_first')) {
    $tags[0] = isset($original[0]) ? $original[0] : t('« first');
  }
  if (theme_get_setting('pager_next')) {
    $tags[1] = isset($original[1]) ? $original[1] : t('‹ previous');
  }
  if (theme_get_setting('pager_previous')) {
    $tags[3] = isset($original[3]) ? $original[3] : t('next ›');
  }
  if (theme_get_setting('pager_last')) {
    $tags[4] = isset($original[4]) ? $original[4] : t('last »');
  }

  $vars['tags'] = $tags;
}

/**
 * Implements hook_THEME().
 */
function aguayo_pager($vars) {
  $tags = $vars['tags'];
  $element = $vars['element'];
  $parameters = $vars['parameters'];
  $quantity = $vars['quantity'];
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

  // CHANGED: Print pager links that have a tag to use.
  // Others are ignored.
  $li_first = FALSE;
  if ($tags[0]) {
    $args = array(
      'text' => $tags[0],
      'element' => $element,
      'parameters' => $parameters,
    );
    $li_first = theme('pager_first', $args);
  }

  $li_previous = FALSE;
  if ($tags[1]) {
    $args = array(
      'text' => $tags[1],
      'element' => $element,
      'interval' => 1,
      'parameters' => $parameters,
    );
    $li_previous = theme('pager_previous', $args);
  }

  $li_next = FALSE;
  if ($tags[3]) {
    $args = array(
      'text' => $tags[3],
      'element' => $element,
      'interval' => 1,
      'parameters' => $parameters,
    );
    $li_next = theme('pager_next', $args);
  }

  $li_last = FALSE;
  if ($tags[4]) {
    $args = array(
      'text' => $tags[4],
      'element' => $element,
      'parameters' => $parameters,
    );
    $li_last = theme('pager_last', $args);
  }

  if ($pager_total[$element] > 1) {
    if ($li_first) {
      $items[] = array(
        'class' => array('pager-first'), 
        'data' => $li_first,
      );
    }
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
          'data' => '…',
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
            'class' => array('pager-current'), 
            'data' => $i,
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
          'class' => array('pager-ellipsis'), 
          'data' => '…',
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
    if ($li_last) {
      $items[] = array(
        'class' => array('pager-last'), 
        'data' => $li_last,
      );
    }

    return '<h2 class="element-invisible">' . t('Pages') . '</h2>' . theme('item_list', array(
      'items' => $items,
      'attributes' => array('class' => array('pager', 'clearfix')),
    ));
  }
}

/**
 * Implements hook_THEME().
 */
function aguayo_pager_link($vars) {
  $text = $vars['text'];
  $page_new = $vars['page_new'];
  $element = $vars['element'];
  $parameters = $vars['parameters'];
  $attributes = $vars['attributes'];

  $page = isset($_GET['page']) ? $_GET['page'] : '';
  if ($new_page = implode(',', pager_load_array($page_new[$element], $element, explode(',', $page)))) {
    $parameters['page'] = $new_page;
  }

  $query = array();
  if (count($parameters)) {
    $query = drupal_get_query_parameters($parameters, array());
  }
  if ($query_pager = pager_get_query_parameters()) {
    $query = array_merge($query, $query_pager);
  }

  // Set each pager link title
  if (!isset($attributes['title'])) {
    static $titles = NULL;
    if (!isset($titles)) {
      $titles = array(
        t('« first') => t('Go to first page'), 
        t('‹ previous') => t('Go to previous page'), 
        t('next ›') => t('Go to next page'), 
        t('last »') => t('Go to last page'),
      );
    }
    if (isset($titles[$text])) {
      $attributes['title'] = $titles[$text];
    }
    elseif (is_numeric($text)) {
      $attributes['title'] = t('Go to page @number', array('@number' => $text));
    }
  }

  // @todo l() cannot be used here, since it adds an 'active' class based on the
  // path only (which is always the current path for pager links). Apparently,
  // none of the pager links is active at any time - but it should still be
  // possible to use l() here.
  // @see http://drupal.org/node/1410574
  $attributes['href'] = url($_GET['q'], array('query' => $query));
  // CHANGED: allow html in link text.
  return '<a' . drupal_attributes($attributes) . '>' . $text . '</a>';
}

/**
 * Implements theme_HOOK().
 */
function aguayo_select($vars) {
  $original = theme_select($vars);
  $classes = 'aguayo-select-wrapper';
  $classes .= isset($vars['element']['#disabled']) && $vars['element']['#disabled'] ? ' aguayo-select-disabled' : '';
  return '<span class="' . $classes . '">' . $original . '</span>';
}

/**
 * Implements theme_HOOK().
 */
function aguayo_file($vars) {
  $original = theme_file($vars);

  return '<span class="aguayo-file-wrapper">' . $original . '</span>';
}

/**
 * Implements hook_preprocess_theme().
 */
function aguayo_preprocess_node(&$vars) {
  // This allows us to put fields on a group "pre_title", which will print
  // things, well, before the title.
  // Easier than creating the title as a field.
  if (isset($vars['content']['group_pre_title'])) {
    $vars['group_pre_title'] = $vars['content']['group_pre_title'];
    hide($vars['content']['group_pre_title']);
  }
  // Avoid strict warnings.
  elseif (!isset($vars['group_pre_title'])) {
    $vars['group_pre_title'] = NULL;
  }

  // Mark the node that is being printed as main content.
  if (node_is_page($vars['node'])) {
    $vars['classes_array'][] = 'aguayo-node-is-page';
  }

  // Better variable for title printing!
  if (isset($vars['node']->hide_title) && $vars['node']->hide_title) {
    $vars['print_title'] = FALSE;
  }
  $vars['print_title'] = isset($vars['print_title']) ? $vars['print_title'] : !$vars['page'];
}

/**
 * Implements hook_preprocess_file_entity().
 */
function aguayo_preprocess_field(&$vars) {

  // $field 
  $field = field_info_field($vars['element']['#field_name']);
  if ($field['cardinality'] === 1 || $field['cardinality'] === '1') {
    $vars['field-wrapper'] = FALSE;
  }

  $vars['field-wrapper'] = isset($vars['field-wrapper']) ? $vars['field-wrapper'] : TRUE;
  $vars['field-top-level'] = isset($vars['field-top-level']) ? $vars['field-top-level'] : TRUE;
  $vars['field-tag'] = isset($vars['field-tag']) ? $vars['field-tag'] : 'div';
}

/**
 * Implements hook_THEME().
 *
 * This is so much better markup than original field!
 * Try to remove as much as we can without breaking things.
 */
function aguayo_field($vars) {
  $output = '';

  // Render the label, if it's not hidden.
  if (!$vars['label_hidden']) {
    $output .= '<div class="field-label"' . $vars['title_attributes'] . '>' . $vars['label'] . ':&nbsp;</div>';
  }

  // Render the items.
  if ($vars['field-wrapper'] || !$vars['label_hidden']) {
    $output .= '<div class="field-items"' . $vars['content_attributes'] . '>';
  }
  foreach ($vars['items'] as $delta => $item) {
    $classes = 'field-item ' . ($delta % 2 ? 'odd' : 'even');
    if ($vars['field-wrapper']) {
      $output .= '<div class="' . $classes . '"' . $vars['item_attributes'][$delta] . '>' . drupal_render($item) . '</div>';
    }
    else {
      $output .= drupal_render($item);
    }
  }
  if ($vars['field-wrapper'] || !$vars['label_hidden']) {
    $output .= '</div>';
  }

  // Render the top-level DIV.
  if ($vars['field-top-level']) {
    $output = '<' . $vars['field-tag'] . ' class="' . $vars['classes'] . '"' . $vars['attributes'] . '>' . $output . '</' . $vars['field-tag'] . '>';
  }
  else {
    $output = $output;
  }

  return $output;
}

function aguayo_preprocess_region(&$vars) {
  $vars['classes_array'][] = 'clearfix';
}

/**
 * Check if a group has regions to be printed, regions should be named
 * GROUP_whatever
 * Call from within page.tpl.php
 *
 * @param string $name
 *   the group name to check against (SECTION)
 * @param array $page
 *   $page variable from page.tpl.php
 *
 * @return boolean
 *   TRUE if group has regions to be printed, FALSE otherwise
 */
function aguayo_print_group($name, $page) {
  foreach (system_region_list('aguayo') as $region_key => $region) {
    if (strpos($region_key, $name) === 0 && isset($page[$region_key]) && $page[$region_key]) {
      return TRUE;
    }
  }
  return FALSE;
}
