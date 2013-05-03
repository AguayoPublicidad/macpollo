<?php
/**
 * @file
 * User information file
 */

/**
 * User block information
 */
function _macpollo_user_information() {
  global $user;
  $account = menu_get_object('user');
  $account = ($account) ? $account : $user;
  // Load user fields.
  // Person.
  if (isset($account->roles[PERSON_ROLE])) {
    $type = 'person';
  }
  elseif (isset($account->roles[COMPANY_ROLE])) {
    $type = 'company';
  }

  if (isset($type) && $type) {
    $profile = profile2_load_by_user($account, $type);
    $name = field_get_items('profile2', $profile, 'field_user_name');
    $gender = field_get_items('profile2', $profile, 'field_user_gender');
  }
  if (isset($gender[0]['value'])) {
    $img_name = $gender[0]['value'];
  }
  else {
    $img_name = 'male';
  }

  $content = array();
  $content['container'] = array(
    '#prefix' => '<div class="user-information">',
    '#suffix' => '</div>',
  );
  // Links.
  $content['container']['links'] = array(
    '#prefix' => '<div class="user-information-links">',
    '#suffix' => '</div>',
  );
  $content['container']['links']['info'] = array(
    '#prefix' => '<div class="user-information-link p-info">',
    '#suffix' => '</div>',
    '#markup' => l(t('My data'), 'user/' . $account->uid . '/edit')
  );
  $content['container']['links']['orders'] = array(
    '#prefix' => '<div class="user-information-link p-orders">',
    '#suffix' => '</div>',
    '#markup' => l(t('My orders'), 'user/' . $account->uid . '/orders')
  );
  $content['container']['links']['recipes'] = array(
    '#prefix' => '<div class="user-information-link p-recipes">',
    '#suffix' => '</div>',
    '#markup' => l(t('My recipes'), 'user/' . $account->uid . '/recipes')
  );

  // Data.
  $content['container']['data'] = array(
    '#prefix' => '<div class="user-information-data">',
    '#suffix' => '</div>',
  );
  // Image.
  $content['container']['data']['img'] = array(
    '#prefix' => '<div class="user-data p-img">',
    '#suffix' => '</div>',
    '#markup' => theme('image', array('path' => drupal_get_path('module', 'macpollo_user') . '/images/img-' . $img_name . '.png', 'alt' => 'Imagen-perfil'))
  );

  // Data info.
  $content['container']['data']['info'] = array(
    '#prefix' => '<div class="user-information-data-info">',
    '#suffix' => '</div>',
  );
  // Nombre.
  $content['container']['data']['info']['name'] = array(
    '#prefix' => '<h2 class="user-infor-name">',
    '#suffix' => '</h2>',
    '#markup' => isset($name[0]['value']) ? $name[0]['value'] : '',
  );
  // Bienvenida.
  $content['container']['data']['info']['welco'] = array(
    '#prefix' => '<h3 class="user-infor-welco">',
    '#suffix' => '</h3>',
    '#markup' => t('Welcome to MacPollo'),
  );
  // Desde.
  $day = date('n', $account->created);
  $mont = t(date('F', $account->created));
  $year = date('Y', $account->created);
  $content['container']['data']['info']['regis'] = array(
    '#prefix' => '<p class="user-infor-register">',
    '#suffix' => '</p>',
    '#markup' => t('You are macpollo community member since @day from @mont from @year', array('@day' => $day, '@mont' => $mont, '@year' => $year)),
  );

  // User contents.
  $content['container']['data']['info']['contents'] = array(
    '#prefix' => '<div class="user-information-contents">',
    '#suffix' => '</div>',
  );
  $num_oders = '<span class="text-red">' . 0 . '</span>';
  $content['container']['data']['info']['contents']['orders'] = array(
    '#prefix' => '<div class="user-contents user-orders">',
    '#suffix' => '</div>',
    '#markup' => t('Ordes:!n_orders', array('!n_orders' => $num_oders))
  );
  $user_info = '<span class="text-red">' . 0 . '</span>';
  $content['container']['data']['info']['contents']['user_info'] = array(
    '#prefix' => '<div class="user-contents user-infom">',
    '#suffix' => '</div>',
    '#markup' => t('Information: !user_info %', array('!user_info' => $user_info))
  );
  $recipes = '<span class="text-red">' . _macpollo_user_num_contents($account->uid, 'recipe') . '</span>';
  $content['container']['data']['info']['contents']['recipes'] = array(
    '#prefix' => '<div class="user-contents user-recipes">',
    '#suffix' => '</div>',
    '#markup' => t('Recipes: !recipes', array('!recipes' => $recipes))
  );
  // User links.
  $content['container']['data']['info']['u_links'] = array(
    '#prefix' => '<div class="user-contents-links">',
    '#suffix' => '</div>',
  );
  $content['container']['data']['info']['u_links']['order'] = array(
    '#prefix' => '<div class="user-link-order btn-red">',
    '#suffix' => '</div>',
    '#markup' => l(t('Make order'), '')
  );
  $content['container']['data']['info']['u_links']['a_info'] = array(
    '#prefix' => '<div class="user-link-ainfo btn-red">',
    '#suffix' => '</div>',
    '#markup' => l(t('Update Information'), 'user/' . $account->uid . '/edit')
  );
  $content['container']['data']['info']['u_links']['p_recipe'] = array(
    '#prefix' => '<div class="user-link-precipe btn-red">',
    '#suffix' => '</div>',
    '#markup' => l(t('Publish recipe'), 'node/add/recipe')
  );

  return $content;
}
