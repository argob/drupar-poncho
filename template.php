<?php

/**
 * @file
 * template.php
 */

function poncho_preprocess_html(&$variables) {
  drupal_add_css('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css', array('type' => 'external'));
  drupal_add_css('https://fonts.googleapis.com/css?family=Roboto:400,300italic,300,100italic,100,400italic,500,500italic,700,700italic,900,900italic', array('type' => 'external'));
  drupal_add_css('https://fonts.googleapis.com/css?family=Droid+Serif:400,400italic,700,700italic', array('type' => 'external'));
}
/**
 * Implements hook_preprocess_page().
 */
function poncho_preprocess_page(&$variables){
  $variables['logo'] = base_path() . drupal_get_path('theme', 'poncho') . '/logo.svg';
}
/**
 * Implements hook_wysiwyg_editor_settings_alter().
 */
function poncho_wysiwyg_editor_settings_alter(&$settings, $context) {
  if ($context['profile']->editor == 'ckeditor') {
      $path = drupal_get_path('theme', 'poncho');
      $settings['stylesSet'] = "drupal:/$path/ckeditor.styles.js";
  }
}

/**
 * Agrega clases para el título del bloque
 */
function poncho_preprocess_block(&$variables) {
  $variables['title_attributes_array']['class'][] = 'h3 section-title';
}
function poncho_preprocess_breadcrumb(&$variables) {
  if(arg(0) == 'node' && is_numeric(arg(1)) && arg(2) != '' || arg(0) == 'node' && is_numeric(arg(1))) {
    $breadcrumb = &$variables['breadcrumb'];
    $breadcrumb = crearBreadcrumb(node_load(arg(1)));
  }
  if (isset($path) && $path == NULL) {
    $path = $_GET['q'];
  }
  if (isset($path) && $path == 'noticias'){
    $breadcrumb = &$variables['breadcrumb'];
    $breadcrumb = crearBreadcrumb(node_load(arg(1)));
  }
}

/**
 * Crea el breadcrumb
 */
function crearBreadcrumb($nodo) {
  $breadcrumb[] = l(t('Inicio'), '<front>');
  foreach (obtenerGrupos($nodo) as $grupo) {
    $breadcrumb[] = $grupo;
  }
  if(arg(0) == 'node' && is_numeric(arg(1)) && arg(2) != '') {
    $breadcrumb[] = l($nodo->title, 'node/' . $nodo->nid);
  } if(esPaginaDeNoticias()){
    $breadcrumb[] = array(
    'data' => 'Noticias',
    'class' => array('active'),
  );
  }
  if (isset($path) && $path == NULL) {
    $path = $_GET['q'];
  }
  if (isset($path) && $path == 'noticias'){
    $breadcrumb[] = array(
    'data' => 'Noticias',
    'class' => array('active'),
  );
  }
  $breadcrumb[] = array(
    'data' => drupal_get_title(),
    'class' => array('active'),
  );
  return $breadcrumb;
}

function esPaginaDeNoticias(){
  return arg(2) == 'noticias';
}

/**
 * Recorre la jerarquía de Organic Groups de un nodo y retorna un array con esto.
 */
function obtenerGrupos($nodo){
  $organicGroupBreadcrumb = array();
  while (tieneGrupo($nodo)) {
    $group = node_load(obtenerGrupo($nodo));
    $organicGroupBreadcrumb[] = l($group->title, 'node/' . $group->nid);
    $nodo = $group;
  }
  return array_reverse($organicGroupBreadcrumb);
}

/**
 * Chequea si un nodo tiene grupo
 */
function tieneGrupo($node){
  return isset($node->og_group_ref) && !empty($node->og_group_ref) ? TRUE : FALSE;
}

/**
 * Devuelve el grupo de un nodo
 */
function obtenerGrupo($node){
  return field_get_items('node', $node, 'og_group_ref')[0]['target_id'];
}

/**
 * Implementa theme_breadcrumb()
 */
function poncho_breadcrumb($variables) {
  return theme('item_list', array(
    'attributes' => array(
      'class' => array(
        'breadcrumb',
        'pull-left'
      ),
    ),
    'items' => $variables['breadcrumb'],
    'type' => 'ol',
    )
  );
}

function poncho_facetapi_link_inactive($variables) {
  // Builds accessible markup.
  // @see http://drupal.org/node/1316580
  $accessible_vars = array(
    'text' => $variables['text'],
    'active' => FALSE,
  );
  $accessible_markup = theme('facetapi_accessible_markup', $accessible_vars);

  // Sanitizes the link text if necessary.
  $sanitize = empty($variables['options']['html']);
  $variables['text'] = ($sanitize) ? check_plain($variables['text']) : $variables['text'];

  // Adds count to link if one was passed.
  if (isset($variables['count'])) {
    $variables['text'] .= ' ' . theme('facetapi_count', $variables);
  }

  // Resets link text, sets to options to HTML since we already sanitized the
  // link text and are providing additional markup for accessibility.
  $variables['text'] .= $accessible_markup;
  $variables['options']['html'] = TRUE;
  return theme_link($variables);
}

function poncho_facetapi_link_active($variables) {
   // Sanitizes the link text if necessary.
  $sanitize = empty($variables['options']['html']);
  $link_text = ($sanitize) ? check_plain($variables['text']) : $variables['text'];

  // Theme function variables fro accessible markup.
  // @see http://drupal.org/node/1316580
  $accessible_vars = array(
    'text' => $variables['text'],
    'active' => TRUE,
  );

  // Builds link, passes through t() which gives us the ability to change the
  // position of the widget on a per-language basis.
  $replacements = array(
    '!facetapi_deactivate_widget' => theme('facetapi_deactivate_widget', $variables),
    '!facetapi_accessible_markup' => theme('facetapi_accessible_markup', $accessible_vars),
  );
  $variables['text'] = t('!facetapi_deactivate_widget !facetapi_accessible_markup', $replacements). $link_text;
  $variables['options']['html'] = TRUE;

  return theme_link($variables);
}


function poncho_facetapi_count($variables) {
  return '<span class="badge pull-right">' . (int) $variables['count'] . '</span>';
}

function poncho_menu_link__main_menu($variables) {
  $element = $variables['element'];
  $element['#localized_options']['html'] = TRUE;

  $output = l($element['#title'], $element['#href'], $element['#localized_options']);

  //Alguien colocó un return acá con una variable que no esta definida la saco y dejo comentado por si tenía alguna finalidad
  //return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";

  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . "</li>\n";
}

function poncho_menu_tree__primary($variables) {
  return '' . $variables['tree'] . '';
}

function poncho_menu_tree__main_menu($variables) {
  return '<ul class="nav navbar-nav">' . $variables['tree'] . '</ul>';
}

function poncho_menu_tree__og_menu_single(&$variables) {
  return '<ul class="list-inline pull-right">' . $variables['tree'] . '</ul>';
}

function poncho_pager($variables) {
  $output = "";
  $items = array();
  $tags = $variables['tags'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  $quantity = $variables['quantity'];

  global $pager_page_array, $pager_total;

  // Calculate various markers within this pager piece:
  // Middle is used to "center" pages around the current page.
  $pager_middle = ceil($quantity / 2);
  // Current is the page we are currently paged to.
  $pager_current = $pager_page_array[$element] + 1;
  // First is the first page listed by this pager piece (re quantity).
  $pager_first = $pager_current - $pager_middle + 1;
  // Last is the last page listed by this pager piece (re quantity).
  $pager_last = $pager_current + $quantity - $pager_middle;
  // Max is the maximum page number.
  $pager_max = $pager_total[$element];

  // Prepare for generation loop.
  $i = $pager_first;
  if ($pager_last > $pager_max) {
    // Adjust "center" if at end of query.
    $i = $i + ($pager_max - $pager_last);
    $pager_last = $pager_max;
  }
  if ($i <= 0) {
    // Adjust "center" if at start of query.
    $pager_last = $pager_last + (1 - $i);
    $i = 1;
  }

  // End of generation loop preparation.
  $li_first = theme('pager_first', array(
    'text' => (isset($tags[0]) ? $tags[0] : t('Primera')),
    'element' => $element,
    'parameters' => $parameters,
  ));
  $li_previous = theme('pager_previous', array(
    'text' => (isset($tags[1]) ? $tags[1] : t('Anterior')),
    'element' => $element,
    'interval' => 1,
    'parameters' => $parameters,
  ));
  $li_next = theme('pager_next', array(
    'text' => (isset($tags[3]) ? $tags[3] : t('Siguiente')),
    'element' => $element,
    'interval' => 1,
    'parameters' => $parameters,
  ));
  $li_last = theme('pager_last', array(
    'text' => (isset($tags[4]) ? $tags[4] : t('Última')),
    'element' => $element,
    'parameters' => $parameters,
  ));
  if ($pager_total[$element] > 1) {

    // Only show "first" link if set on components' theme setting
    if ($li_first && bootstrap_setting('pager_first_and_last')) {
      $items[] = array(
        'class' => array('pager-first'),
        'data' => $li_first,
      );
    }
    if ($li_previous) {
      $items[] = array(
        'class' => array('prev'),
        'data' => $li_previous,
      );
    }
    // When there is more than one page, create the pager list.
    if ($i != $pager_max) {
      if ($i > 1) {
        $items[] = array(
          'class' => array('pager-ellipsis', 'disabled'),
          'data' => '<span>…</span>',
        );
      }
      // Now generate the actual pager piece.
      for (; $i <= $pager_last && $i <= $pager_max; $i++) {
        if ($i < $pager_current) {
          $items[] = array(
            // 'class' => array('pager-item'),
            'data' => theme('pager_previous', array(
              'text' => $i,
              'element' => $element,
              'interval' => ($pager_current - $i),
              'parameters' => $parameters,
            )),
          );
        }
        if ($i == $pager_current) {
          $items[] = array(
            // Add the active class.
            'class' => array('active'),
            'data' => "<span>$i</span>",
          );
        }
        if ($i > $pager_current) {
          $items[] = array(
            'data' => theme('pager_next', array(
              'text' => $i,
              'element' => $element,
              'interval' => ($i - $pager_current),
              'parameters' => $parameters,
            )),
          );
        }
      }
      if ($i < $pager_max) {
        $items[] = array(
          'class' => array('pager-ellipsis', 'disabled'),
          'data' => '<span>…</span>',
        );
      }
    }
    // End generation.
    if ($li_next) {
      $items[] = array(
        'class' => array('next'),
        'data' => $li_next,
      );
    }
    // // Only show "last" link if set on components' theme setting
    if ($li_last && bootstrap_setting('pager_first_and_last')) {
      $items[] = array(
       'class' => array('pager-last'),
       'data' => $li_last,
      );
    }

    $build = array(
      '#theme_wrappers' => array('container__pager'),
      '#attributes' => array(
        'class' => array(
          'text-center',
        ),
      ),
      'pager' => array(
        '#theme' => 'item_list',
        '#items' => $items,
        '#attributes' => array(
          'class' => array('pagination'),
        ),
      ),
    );
    return drupal_render($build);
  }
  return $output;
}


/**
 * Default theme function for spelling suggestions.
 */
function poncho_apachesolr_search_suggestions($variables) {

  //Por ahora se comentan las sugerencias de solr porque no son precisas y no están en el modelo

  /*$output = '<div class="spelling-suggestions">';
  $output .= '<dl class="form-item"><dt><strong>' . t('Did you mean') . '</strong></dt>';
  foreach ((array) $variables['links'] as $link) {
    $output .= '<dd>' . $link . '</dd>';
  }
  $output .= '</dl></div>';*/

  $output = '';
  return $output;
}




/**
 * Brief message to display when no results match the query.
 *
 * @see search_help()
 */
function poncho_apachesolr_search_noresults() {

  /*drupal_add_js('jQuery(document).ready(function () {
       jQuery("#search-form").prepend("<h1 class=text-center>¿Qué estás buscando?</h1>");
       console.log("Busqueda fallida");
    });', 'inline');

  return t('<ul class="col-md-8 col-md-offset-2 busqueda_vacia">
  <li>Por favor verificá que las palabras estén bien escritas.</li>
  <li>Si querés buscar una frase la podés buscar "entre comillas".</li>
  <li>Si escribís "OR" entre dos palabras, te busca resultados para una u otra.</li>
  </ul>');*/
  return theme('buscar_no_result',array('buscar'=>urldecode(arg(1))));

}

/**
 * Preprocess function for theme_apachesolr_search_snippets().
 * Altero los snippets para mostrar el summary en caso de existir de lo contrario
 * Utiliza el comportamiento natural de mostrar los lugares donde lo encontró
 */
function poncho_preprocess_apachesolr_search_snippets(&$vars) {
  // Flatten the multidimensional array of snippets into a one-dimensional,
  // ordered array.
  $vars['flattened_snippets'] = array();
  $snippets = $vars['snippets'];

  if (is_array($snippets)) {

    if(!empty($vars['doc']->ss_body_summary)){

      $vars['flattened_snippets'] = array_merge($vars['flattened_snippets'],array($vars['doc']->ss_body_summary));

    }else{

      // Add any remaining snippets from the array. Each snippet can either be a
      // string or an array itself; see apachesolr_search_process_response().
      foreach ($snippets as $snippet) {
        $vars['flattened_snippets'] = array_merge($vars['flattened_snippets'], is_array($snippet) ? $snippet : array($snippet));
      }

    }

  }

  // Ensure unique search snippets.
  $vars['flattened_snippets'] = array_unique($vars['flattened_snippets']);
}

/**
 * Implements hook_form_alter().
 */


function poncho_html_head_alter(&$head_elements) {
  unset($head_elements['metatag_generator_0']);
}

function poncho_form_alter(&$form, &$form_state, $form_id){

  if ($form_id == 'user_login_block' || $form_id == 'user_login') {
    $form['#attributes']['autocomplete'] = 'off';
  }

  // Define para todos los forms el tipo de captcha "reCAPTCHA"
  $form['captcha']['#captcha_type'] = 'recaptcha/reCAPTCHA';
  // Además definir la Site Key y la Secret Key en settings.php,
  // agregando las siguientes lineas:
  // $conf['recaptcha_site_key'] = '';
  // $conf['recaptcha_secret_key'] = '';

}

function poncho_custom_page_search_form_alter($form, &$form_state, $search_page, $keys = ''){
    // Loads the core Search CSS file, use the core search module's classes.
  drupal_add_css(drupal_get_path('module', 'search') . '/search.css');

  $form = array();
  $form['#id'] = 'search-form';
  $form['#attributes']['class'][] = 'search-form';
  $form['#search_page'] = $search_page;
  $form['basic'] = array(
    '#type' => 'container',
    '#attributes' => array('class' => array('container-inline DALE QUE VA')),
  );
  $form['basic']['keys'] = array(
    '#type' => 'textfield',
    '#title' => t('Enter terms'),
    '#default_value' => $keys,
    '#size' => 20,
    '#maxlength' => 255,
  );
  $form['basic']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Search'),
  );

  $form['basic']['get'] = array(
    '#type' => 'hidden',
    '#default_value' => json_encode(array_diff_key($_GET, array('q' => 1, 'page' => 1, 'solrsort' => 1, 'retain-filters' => 1))),
  );

  $fq = NULL;

  if (apachesolr_has_searched($search_page['env_id'])) {
    $query = apachesolr_current_query($search_page['env_id']);
    // We use the presence of filter query params as a flag for the retain filters checkbox.
    $fq = $query->getParam('fq');
  }

  if ($fq || isset($form_state['input']['retain-filters'])) {
    $form['basic']['retain-filters'] = array(
      '#type' => 'checkbox',
      '#title' => t('Retain current filters'),
      '#default_value' => (int) !empty($_GET['retain-filters']),
    );
  }

  return $form;
}

function poncho_menu_link__og_menu_single(array $variables) {
  $element = $variables['element'];
  $sub_menu = '';

  if ($element['#below']) {
    // Prevent dropdown functions from being added to management menu so it
    // does not affect the navbar module.
    if (($element['#original_link']['menu_name'] == 'management') && (module_exists('navbar'))) {
      $sub_menu = drupal_render($element['#below']);
    }
    elseif ((!empty($element['#original_link']['depth'])) && ($element['#original_link']['depth'] == 2)) {
      // Add our own wrapper.
      unset($element['#below']['#theme_wrappers']);
      $sub_menu = '<ul class="dropdown-menu dropdown-menu-right">' . drupal_render($element['#below']) . '</ul>';
      // Generate as standard dropdown.
      $element['#title'] .= ' <span class="caret"></span>';
      $element['#attributes']['class'][] = 'dropdown';
      $element['#localized_options']['html'] = TRUE;

      // Set dropdown trigger element to # to prevent inadvertant page loading
      // when a submenu link is clicked.
      $element['#localized_options']['attributes']['data-target'] = '#';
      $element['#localized_options']['attributes']['class'][] = 'dropdown-toggle';
      $element['#localized_options']['attributes']['data-toggle'] = 'dropdown';
    }
  }
  // On primary navigation menu, class 'active' is not set on active menu item.
  // @see https://drupal.org/node/1896674
  if (($element['#href'] == $_GET['q'] || ($element['#href'] == '<front>' && drupal_is_front_page())) && (empty($element['#localized_options']['language']))) {
    $element['#attributes']['class'][] = 'active';
  }
  $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}
