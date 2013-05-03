<?php
/**
 * @file
 * User aux funtions
 */

/**
 * User form login
 */
function _macpollo_user_form_login_alter(&$form, $form_state) {
  $form['name']['#title'] = t('User');
  $form['name']['#description'] = '';
  $form['pass']['#description'] = '';
  $form['link_pass'] = array(
    '#prefix' => '<div class="user-form-login-pass">',
    '#suffix' => '</div>',
    '#markup' => l(t('Forgot your password?'), 'user/password')
  );
}


/**
 * User form register
 */
function _macpollo_user_form_register_alter(&$form, $form_state) {
  $path = current_path();
  $attrs = array('attributes' => array('target' => '_blank'));

  if ($path == 'person/register') {
    $form['profile_company']['#access'] = FALSE;
    $form['path'] = array('#type' => 'hidden', '#value' => $path);
    $label_per = t($form['profile_person']['field_terms_and_conditions']['und']['#title']);
    $form['profile_person']['field_terms_and_conditions']['und']['#title'] = l($label_per, 'node/' . NID_TERMS_AND_CONDS, $attrs);
    $form['profile_company']['field_terms_and_condition']['und']['#default_value'] = NULL;
    // Shipping.
    $form['profile_person']['field_shipping_location'][LANGUAGE_NONE]['#access'] = FALSE;
    $form['profile_person']['field_shipping_address'][LANGUAGE_NONE]['#access'] = FALSE;
  }
  elseif ($path == 'company/register') {
    $form['profile_person']['#access'] = FALSE;
    $form['path'] = array('#type' => 'hidden', '#value' => $path);
    $label_com = t($form['profile_company']['field_terms_and_conditions']['und']['#title']);
    $form['profile_company']['field_terms_and_conditions']['und']['#title'] = l($label_com, 'node/' . NID_TERMS_AND_CONDS, $attrs);
    $form['profile_person']['field_terms_and_condition']['und']['#default_value'] = NULL;
    // Shipping.
    $form['profile_company']['field_shipping_location'][LANGUAGE_NONE]['#access'] = FALSE;
    $form['profile_company']['field_shipping_address'][LANGUAGE_NONE]['#access'] = FALSE;
  }

  $form['profile_person']['#title'] = '';
  $form['profile_company']['#title'] = '';
  // Descripción.
  $form['account']['name']['#description'] = '';
  $form['account']['mail']['#description'] = '';
  $form['#validate'][] = 'macpollo_user_form_register_alter_validate';
}

/**
 * User form login
 */
function macpollo_user_form_register_alter_validate(&$form, &$form_state) {
  unset($form_state['values']['profile_company']);
  if ($form['path']['#value'] == 'person/register') {
    unset($form['profiles']['company']);
    if ($form_state['values']['profile_person']['field_terms_and_conditions']['und'][0]['value'] == 0) {
      form_set_error('field_terms_and_conditions', t('You must accept terms and conditions'));
    }
  }
  if ($form['path']['#value'] == 'company/register') {
    unset($form['profiles']['person']);
    if ($form_state['values']['profile_company']['field_terms_and_conditions']['und'][0]['value'] == 0) {
      form_set_error('field_terms_and_conditions', t('You must accept terms and conditions'));
    }
  }
}

/**
 * User form login
 */
function _macpollo_user_form_profile_alter(&$form, &$form_state) {
  // User contact.
  $form['contact']['#access'] = FALSE;
  // Timeozone.
  $form['timezone']['#access'] = FALSE;
  // Language.
  $form['locale']['#access'] = FALSE;

  // Descripciones.
  $form['account']['mail']['#description'] = '';
  $form['account']['current_pass']['#description'] = '';
  $form['account']['pass']['#description'] = '';

  $profile_types = profile2_get_types();
  foreach ($profile_types as $key => $p_type) {
    if (empty($form_state['profiles'])) {
      $profile = profile2_load_by_user($form['#user'], $p_type->type);
      if (empty($profile)) {
        $profile = profile_create(
          array(
            'type' => $p_type->type,
            'uid' => $form['#user']->uid
          )
        );
      }
      $form_state['profiles'][$profile->type] = $profile;
    }
    profile2_attach_form($form, $form_state);
  }

  // Terms and cond.
  if (isset($form['profile_person']['field_terms_and_conditions']['und'])) {
    $form['profile_person']['field_terms_and_conditions']['und']['#access'] = FALSE;
  }
  if (isset($form['profile_company']['field_terms_and_conditions']['und'])) {
    $form['profile_company']['field_terms_and_conditions']['und']['#access'] = FALSE;
  }

  // Shipping.
  // Person.
  if (isset($form['profile_person'])) {
    $form['profile_person']['field_shipping_address']['#weight'] = 15;
    $form['profile_person']['field_shipping_location']['#weight'] = 17;
    $form['profile_person']['shipping'] = array(
      '#prefix' => '<div class="macpollo-shipping-data">',
      '#suffix' => '</div>',
      '#weight' => 100,
    );
    // Opciones ciudad envio.
    $form['profile_person']['field_shipping_location'][LANGUAGE_NONE]['#options'] = macpollo_user_get_shipping_locations();

    $form['profile_person']['shipping']['lab'] = array(
      '#prefix' => '<div class="macpollo-shipping-data-label"><span class="shipping-label">',
      '#suffix' => '</span></div>',
      '#markup' => t('Delivery addresses'),
    );
    $form['profile_person']['shipping']['address'] = $form['profile_person']['field_shipping_address'];
    $form['profile_person']['shipping']['city'] = $form['profile_person']['field_shipping_location'];
    $form['profile_person']['field_shipping_address']['#access'] = FALSE;
    $form['profile_person']['field_shipping_location']['#access'] = FALSE;
  }

  // Person.
  if (isset($form['profile_company'])) {
    $form['profile_company']['field_shipping_address']['#weight'] = 15;
    $form['profile_company']['field_shipping_location']['#weight'] = 17;
    $form['profile_company']['shipping'] = array(
      '#prefix' => '<div class="macpollo-shipping-data">',
      '#suffix' => '</div>',
      '#weight' => 100,
    );
    // Opciones ciudad envio.
    $form['profile_company']['field_shipping_location'][LANGUAGE_NONE]['#options'] = macpollo_user_get_shipping_locations();

    $form['profile_company']['shipping']['lab'] = array(
      '#prefix' => '<div class="macpollo-shipping-data-label"><span class="shipping-label">',
      '#suffix' => '</span></div>',
      '#markup' => t('Delivery addresses'),
    );
    $form['profile_company']['shipping']['address'] = $form['profile_company']['field_shipping_address'];
    $form['profile_company']['shipping']['city'] = $form['profile_company']['field_shipping_location'];
    $form['profile_company']['field_shipping_address']['#access'] = FALSE;
    $form['profile_company']['field_shipping_location']['#access'] = FALSE;
  }
  $form['#validate'][] = 'macpollo_user_profile_validate';
}

/**
 * Profile validate
 */
function macpollo_user_profile_validate($form, &$form_state) {
  // Person address.
  if (isset($form_state['values']['profile_person']['shipping']['address'][LANGUAGE_NONE][0]['value'])) {
    $address = $form_state['values']['profile_person']['shipping']['address'][LANGUAGE_NONE][0]['value'];
    $form_state['values']['profile_person']['field_shipping_address'][LANGUAGE_NONE][0]['value'] = $address;
  }
  // Person city.
  if (isset($form_state['values']['profile_person']['shipping']['city'][LANGUAGE_NONE][0]['tid'])) {
    $city = $form_state['values']['profile_person']['shipping']['city'][LANGUAGE_NONE][0]['tid'];
    $form_state['values']['profile_person']['field_shipping_location'][LANGUAGE_NONE][0]['tid'] = $city;
  }

  // Company address.
  if (isset($form_state['values']['profile_company']['shipping']['address'][LANGUAGE_NONE][0]['value'])) {
    $address = $form_state['values']['profile_company']['shipping']['address'][LANGUAGE_NONE][0]['value'];
    $form_state['values']['profile_company']['field_shipping_address'][LANGUAGE_NONE][0]['value'] = $address;
  }
  // Company city.
  if (isset($form_state['values']['profile_company']['shipping']['city'][LANGUAGE_NONE][0]['tid'])) {
    $city = $form_state['values']['profile_company']['shipping']['city'][LANGUAGE_NONE][0]['tid'];
    $form_state['values']['profile_company']['field_shipping_location'][LANGUAGE_NONE][0]['tid'] = $city;
  }
}

/**
 * User block content links user type
 */
function _macpollo_user_links_type_register() {
  $content = array();
  $content['container'] = array(
    '#prefix' => '<div class="user-register-links">',
    '#suffix' => '</div>',
  );
  $content['container']['person'] = array(
    '#prefix' => '<div class="user-register-links-person links-register-type">',
    '#suffix' => '</div>',
    '#markup' => l(t('Person'), 'person/register')
  );
  $content['container']['company'] = array(
    '#prefix' => '<div class="user-register-links-company links-register-type">',
    '#suffix' => '</div>',
    '#markup' => l(t('Company'), 'company/register')
  );
  return $content;
}
