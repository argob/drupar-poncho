<?php

// Plugin definition
$plugin = array(
  'title' => t('Noticia'),
  'category' => t('Drupar'),
  'icon' => 'noticia.png',
  'theme' => 'noticia',
  'css' => '../../css/bootstrap.css',
  'regions' => array(
  	'header_full' => t('Encabezado'), 
  	'header_left' => t('Header Left'), 
  	'header_right' => t('Header Right'),
    'content'  => t('Content'),
    'footer'  => t('Pie'),
  ),
);

