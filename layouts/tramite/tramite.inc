<?php

// Plugin definition
$plugin = array(
  'title' => t('Trámite'),
  'category' => t('Drupar'),
  'icon' => 'tramite.png',
  'theme' => 'tramite',
  'css' => '../../css/bootstrap.css',
  'regions' => array(
  	'header_full' => t('Encabezado'), 
    'content'  => t('Content'),
    'content_bottom'  => t('Content bottom'),
    'footer'  => t('Pie'),
    'footer_2'  => t('Pie 2'),
  ),
);

