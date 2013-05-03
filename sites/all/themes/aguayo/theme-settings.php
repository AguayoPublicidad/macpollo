<?php
function aguayo_form_system_theme_settings_alter(&$form, &$form_state) {

  /**
   * Breadcrumb settings
   * Copied from Zen
   */
  $form['breadcrumb'] = array(
   '#type' => 'fieldset',
   '#title' => t('Breadcrumb'),
  );
  $form['breadcrumb']['breadcrumb_display'] = array(
   '#type' => 'select',
   '#title' => t('Display breadcrumb'),
   '#default_value' => theme_get_setting('breadcrumb_display'),
   '#options' => array(
     'yes' => t('Yes'),
     'no' => t('No'),
   ),
  );
  $form['breadcrumb']['breadcrumb_separator'] = array(
   '#type'  => 'textfield',
   '#title' => t('Breadcrumb separator'),
   '#description' => t('Text only. Dont forget to include spaces.'),
   '#default_value' => theme_get_setting('breadcrumb_separator'),
   '#size' => 8,
   '#maxlength' => 10,
  );
  $form['breadcrumb']['breadcrumb_home'] = array(
   '#type' => 'checkbox',
   '#title' => t('Show the homepage link in breadcrumbs'),
   '#default_value' => theme_get_setting('breadcrumb_home'),
  );
  $form['breadcrumb']['breadcrumb_trailing'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Append a separator to the end of the breadcrumb'),
    '#default_value' => theme_get_setting('breadcrumb_trailing'),
    '#description'   => t('Useful when the breadcrumb is placed just before the title.'),
  );
  $form['breadcrumb']['breadcrumb_title'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Append the content title to the end of the breadcrumb'),
    '#default_value' => theme_get_setting('breadcrumb_title'),
    '#description'   => t('Useful when the breadcrumb is not placed just before the title.'),
  );

  $form['pager'] = array(
    '#type'  => 'fieldset',
    '#title' => t('Pager display settings'),
  );
  $form['pager']['pager_first'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Pager First Link'),
    '#default_value' => theme_get_setting('pager_first'),
    '#description'   => t('If unchecked, pager first link will be disabled for all forms.')
  );
  $form['pager']['pager_next'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Pager Next Link'),
    '#default_value' => theme_get_setting('pager_next'),
    '#description'   => t('If unchecked, pager next link will be disabled for all forms.')
  );
  $form['pager']['pager_previous'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Pager Previous Link'),
    '#default_value' => theme_get_setting('pager_previous'),
    '#description'   => t('If unchecked, pager previoius link will be disabled for all forms.')
  );
  $form['pager']['pager_last'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Pager Last Link'),
    '#default_value' => theme_get_setting('pager_last'),
    '#description'   => t('If unchecked, pager last link will be disabled for all forms.')
  );

  $form['libraries'] = array(
    '#type'  => 'fieldset',
    '#title' => t('Additional Libraries'),
  );
  $form['libraries']['html5shim'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('html5 Shim'),
    '#default_value' => theme_get_setting('html5shim'),
    '#description'   => t('Adds the html5 shim, a library that enables non-modern browsers to recognize HTML5 elements, to the header.')
  );
  $form['libraries']['css3_mediaqueries'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('css3 MediaQueries JS'),
    '#default_value' => theme_get_setting('css3_mediaqueries'),
    '#description'   => t('css3-mediaqueries.js: make CSS3 Media Queries work in all browsers (JavaScript library).')
  );
  $form['libraries']['normalize_css'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('normalize.css'),
    '#default_value' => theme_get_setting('normalize_css'),
    '#description'   => t('A modern, HTML5-ready alternative to CSS resets.')
  );

}