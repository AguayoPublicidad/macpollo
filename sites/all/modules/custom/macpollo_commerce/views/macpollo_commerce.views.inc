<?php
/**
 * @file
 * Create a field for views.
 */

/**
 * Implements hook_views_data_alter()
 */
function macpollo_commerce_views_data_alter(&$data) {
  // FIELDS.
  $data['commerce_order']['macpollo_commerce_reorder'] = array(
    'group' => t('Macpollo Commerce'),
    'title' => t('Button to reorder.'),
    'help' => t('Macpollo Commerce'),
    'field' => array(
      'handler' => 'macpollo_commerce_handler_field_reorder',
    )
  );
  $data['commerce_order']['macpollo_commerce_receiving_order'] = array(
    'group' => t('Macpollo Commerce'),
    'title' => t('Receiving order.'),
    'help' => t('Macpollo Commerce'),
    'field' => array(
      'handler' => 'macpollo_commerce_handler_field_receiving_order',
    )
  );
  
}
