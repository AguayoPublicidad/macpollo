<?php
/**
 * Format callback.
 *
 * @see CALLBACK_addressfield_format_callback()
 */
$plugin = array(
  'title' => t('MacPollo Address form Colombia'),
  'format callback' => 'macpollo_stores_format_address_generate',
  'type' => 'address_macpollo',
  'weight' => -100,
);

function macpollo_stores_format_address_generate(&$format, $address, $context = array()) {
  // We start with a reasonable default: a simple block format suitable
  // for international shipping. We extend it with country-specific heuristics
  // below.
  if ($address['country'] == 'CO' && $context['mode'] != 'render') {
    module_load_include('inc', 'macpollo_stores', 'macpollo_stores_cities');
    module_load_include('inc', 'macpollo_stores', 'macpollo_stores_zones');

    $departament = macpollo_stores_cities();

    $dept = array_merge(array('' => t('Department')), array_keys($departament));
    
    $ciud = array('' => t('City'));
    
    if(!empty($address['administrative_area'])) {
      $ciud = array_merge($ciud, $departament[$address['administrative_area']]);
    }

    $zones = array('' => t('Zone'));
    $zons = macpollo_stores_cities_zones();
    if(!empty($address['locality'])) {
      $zones = array_merge($zones, $zons[$address['locality']]);
    }
    
    $format['locality_block']['locality'] = array(
      '#title' => t('City'),
      '#size' => 30,
      '#required' => TRUE,
      '#prefix' => ' ',
      '#attributes' => array('class' => array('locality')),
      '#options' => array_combine($ciud, $ciud),
      '#ajax' => array(
        'callback' => 'addressfield_standard_widget_refresh',
        'wrapper' => $format['#wrapper_id'],
        'method' => 'replace',
      ),

    );
    
    $format['locality_block']['administrative_area'] = array(
      '#title' => t('Department'),
      '#size' => 10,
      '#required' => TRUE,
      '#prefix' => ' ',
      '#attributes' => array('class' => array('state')),
      '#options' => array_combine($dept, $dept),
      '#weight' => -500,
      '#ajax' => array(
        'callback' => 'addressfield_standard_widget_refresh',
        'wrapper' => $format['#wrapper_id'],
        'method' => 'replace',
      ),
    );

    $format['locality_block']['sub_administrative_area'] = array(
      '#title' => t('Zone'),
      '#size' => 10,
      '#required' => FALSE,
      '#prefix' => ' ',
      '#attributes' => array('class' => array('zone')),
      '#options' => array_combine($zones, $zones),
      // '#ajax' => array(
      //   'callback' => 'addressfield_standard_widget_refresh',
      //   'wrapper' => $format['#wrapper_id'],
      //   'method' => 'replace',
      // ),
    );
  }
}
