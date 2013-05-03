<?php
/**
 * @file
 * Boton para reportar ordenes manualmente a AX.
 */

/**
 * Field handler to provide simple renderer
 */
class macpollo_commerce_handler_field_receiving_order extends views_handler_field {

  function init(&$view, &$options) {
    parent::init($view, $options);
  }

  function option_definition() {
    $options = parent::option_definition();
    return $options;
  }

  function query() {}

  function render($values) {
    $order = $values->_field_data['order_id']['entity'];
    $current_line_id = $values->commerce_line_item_field_data_commerce_line_items_line_item_;
    $lines = field_get_items('commerce_order', $order, 'commerce_line_items');
    $end_line = end($lines);
    if ($current_line_id == $end_line['line_item_id']) {
      if (isset($values->field_field_receiving_order[0]['raw']['value'])) {
        if ($values->field_field_receiving_order[0]['raw']['value']) {
          $response = '<div class="message-order-received">' . t('Received satisfaction') . '</div>';
        }
        else {
          $response = '<div class="message-order-no-received">' . t('Received no satisfaction') . '</div>';
        }
        return $response;
      }
      else {
        return drupal_get_form('macpollo_commerce_receiving_order_form', $order->order_id);
      }
    }
  }
}
