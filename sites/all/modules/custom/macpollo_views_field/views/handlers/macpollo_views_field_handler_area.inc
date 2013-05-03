<?php
/**
 * @file
 * Views handler for filter, files o relations to views
 */

/**
 * A handler to provide an area that is constructed by the administrator using PHP.
 *
 * @ingroup views_area_handlers
 */
class macpollo_views_field_handler_area_add_recipe extends views_handler_area {
  /**
   * Implements views_handler_area#render().
   */
  function render($empty = FALSE) {
    return '<div class="user-link-precipe btn-red">' . l(t('Publish recipe'), 'node/add/recipe') . '</div>';
  }
}
