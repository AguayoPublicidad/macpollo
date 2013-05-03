<?php
/**
 * @file
 * Contains theme override functions and preprocess functions for the Aguayo sub-theme.
 */
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
function subtheme_breadcrumb($vars) {
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
      $separator = '<span class="breadcrumb-separator">' . filter_xss(theme_get_setting('breadcrumb_separator')) . '</span>';
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
      // Assemble the breadcrumb Harold.
      $separator = '<span class="breadcrumb-separator">' . $separator . '</span>';
      $number_of_breadcrumbs = count($breadcrumb);
      $count = 0;
      if ($number_of_breadcrumbs >= 1) {
        foreach ($breadcrumb as $key => $value) {
          if ($count == 0) {
            if ($count == $number_of_breadcrumbs - 1) {
              $breadcrumb[$count] = '<h3 class="breadcrumb-home bread-show">' . $breadcrumb[$count] . '</h3>';
            }
            else {
              $breadcrumb[$count] = '<h3 class="bread-hide breadcrumb-home">' . $breadcrumb[$count] . '</h3>';
            }
          }
          elseif ($count == $number_of_breadcrumbs - 1) {
            $breadcrumb[$count] = '<h3 class="bread-show breadcrumb-last">' . $breadcrumb[$count] . '</h3>';
          }
          else {
            $breadcrumb[$count] = '<h3 class="hide breadcrumb-normal">' . $breadcrumb[$count] . '</h3>';
          }
          $count++;
        }
      }
      return implode($separator, $breadcrumb) . $trailing_separator . '<h3 class="breadcroumb-title">' . $title . '</h3>';
    }
  }
  // Otherwise, return an empty string.
  return '';
}

