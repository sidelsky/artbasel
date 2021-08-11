<?php

if (!function_exists('dzs_query_adjustFilterSearch')) {

  /**
   * @param array $queryArgs mutates
   * @param array $pargs
   */
  function dzs_query_adjustFilterSearch($queryArgs, $pargs = array()) {

    $margs = array(
      'query_order' => 'DESC',
      'query_orderby' => 'date',
    );
    $margs = array_merge($margs, $pargs);


    $queryArgs['orderby'] = $margs['query_orderby'];
    $queryArgs['order'] = $margs['query_order'];
    if ($margs['query_orderby'] == 'meta') {

      $queryArgs['orderby'] = 'meta_value_num';





      $queryArgs['meta_query'] = array(
        'relation' => 'OR',
        array(
          'key' => $margs['query_meta_key'],
          'compare' => 'EXISTS'
        ),
        array(
          'key' => $margs['query_meta_key'],
          'compare' => 'NOT EXISTS'
        )
      );
    }
    if (isset($margs['query_term_slug']) && $margs['query_term_slug']) {



      $queryArgs['tax_query'] = array(
        array(
          'taxonomy' => $margs['query_taxonomy_name'],
          'field' => 'slug',
          'include_children' => false,
          'terms' => ($margs['query_term_slug']),
        )
      );

    }



    return $queryArgs;
  }
}