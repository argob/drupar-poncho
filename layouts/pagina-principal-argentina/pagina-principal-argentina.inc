<?php

// Plugin definition
$plugin = array(
  'title' => t('Página Principal'),
  'category' => t('Drupar'),
  'icon' => 'pagina-principal-argentina.png',
  'theme' => 'pagina-principal-argentina',
  'css' => '../../css/bootstrap.css',
  'regions' => array(
    'top' => t('Top'),
    'middle1' => t('Middle1'),
    'middle2' => t('Middle2'),
    'middle3' => t('Middle3'),
    'bottom' => t('Bottom')
  ),
);
