<?php
/**
 * @file
 * Login page
 */

/**
 * Login page callback
 */
function macpollo_user_login_page() {
  $query = $_GET;
  unset($query['q']);
  $content = array();
  $content['form_login'] = array(
    '#prefix' => '<div class="macpollo-login-user-form-login">',
    '#suffix' => '</div>',
  );
  $content['form_login']['msg_form'] = array(
    '#prefix' => '<div class="macpollo-login-user wrapper-brown">',
    '#suffix' => '</div>',
    '#markup' => t('I am a customer'),
  );
  $content['form_login']['msg_form_msg'] = array(
    '#prefix' => '<div class="macpollo-login-user-msg">',
    '#suffix' => '</div>',
    '#markup' => t('Enter the following details:'),
  );
  $content['form_login']['form'] = array(
    '#prefix' => '<div class="macpollo-login-user-form">',
    '#suffix' => '</div>',
    '#markup' => drupal_render(drupal_get_form('user_login')),
  );

  // Registro.
  $content['register_login'] = array(
    '#prefix' => '<div class="macpollo-register-user">',
    '#suffix' => '</div>',
  );
  $content['register_login']['msg_form'] = array(
    '#prefix' => '<div class="macpollo-register-user wrapper-brown">',
    '#suffix' => '</div>',
    '#markup' => t('I am a new customer'),
  );
  $content['register_login']['msg_register'] = array(
    '#prefix' => '<div class="macpollo-register-msg">',
    '#suffix' => '</div>',
    '#markup' => t('If you are not registered'),
  );
  $content['register_login']['msg_register_link'] = array(
    '#prefix' => '<div class="macpollo-register-link">',
    '#suffix' => '</div>',
    '#markup' => l(t('Register here'), 'person/register', array(
      'query' => $query
    )),
  );

  return $content;
}
