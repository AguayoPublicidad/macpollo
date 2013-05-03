<?php
/**
 * @file
 * Create a field for views.
 */

/**
 * Implements hook_views_data_alter()
 */
function macpollo_views_field_views_data_alter(&$data) {
  $data['node']['publish_status'] = array(
    'group' => t('CustomFields'),
    'title' => t('Custom publish status'),
    'help' => t('Node publish status.'),
    'field' => array(
      'handler' => 'macpollo_views_field_handler_field_publish_status',
    ),
  );

  // Area
  // Link publicar receta.
  $data['views']['mp_add_recipe'] = array(
    'group' => t('CustomFields'),
    'title' => t('Link add recipe'),
    'help' => t('Create recipe content.'),
    'area' => array(
      'handler' => 'macpollo_views_field_handler_area_add_recipe',
    ),
  );

}
