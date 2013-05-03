<?php
/**
 * @file
 * Contains the basic ticket field handler.
 */

/**
 * Field handler to provide simple renderer
 */
class macpollo_views_field_handler_field_publish_status extends views_handler_field {

  function init(&$view, &$options) {
    parent::init($view, $options);
  }

  function option_definition() {
    $options = parent::option_definition();
    return $options;
  }

  function query() {
  }

  function render($values) {
    $return = array();
    if (isset($values->node_status) && $values->node_status == 1) {
      $date = format_date($values->node_created, 'custom_short');
      $text = t('Publish on: @date', array('@date' => $date)) . '<span class="recipe-publish"> </span>';
    }
    elseif (!isset($values->node_status) || $values->node_status == 0) {
      $text = t('Pending approval') . '<span class="recipe-unpublish"> </span>';
    }

    $return = array(
      '#prefix' => '<div class="user-recipes-publish-status">',
      '#suffix' => '</div>',
      '#markup' => $text,
    );
    return $return;
  }
}
