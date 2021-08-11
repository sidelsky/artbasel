<?php

if (!function_exists('dzs_savemeta')) {

  function dzs_savemeta($id, $arg2, $arg3 = '') {

    if ($arg3 == 'html') {
      update_post_meta($id, $arg2, htmlentities($_POST[$arg2]));
      return;
    }


    if (isset($_POST[$arg2]))
      update_post_meta($id, $arg2, esc_attr(strip_tags($_POST[$arg2])));
    else
      if ($arg3 == 'checkbox')
        update_post_meta($id, $arg2, "off");
  }

}


if (!function_exists('dzs_get_query_arg')) {
  /**
   * @param string $url
   * @param string $key
   * @return string
   */
  function dzs_get_query_arg($url, $key) {
    if (strpos($url, $key) !== false) {
      $pattern = '/[?&]' . $key . '=(.+?)(?=&|$)/';
      preg_match($pattern, $url, $matches);

      if ($matches && $matches[1]) {
        return $matches[1];
      }
    }
    return '';
  }
}


if (!function_exists('dzs_checked')) {

  function dzs_checked($arg1, $arg2, $arg3 = 'checked', $echo = true) {
    $func_output = '';

    if (isset($arg1) && $arg1 == $arg2) {
      $func_output = $arg3;
    }
    if ($echo == true)
      echo $func_output;
    else
      return $func_output;
  }

}

if (!function_exists('dzs_find_string')) {

  function dzs_find_string($arg, $arg2) {
    $pos = strpos($arg, $arg2);

    if ($pos === false)
      return false;

    return true;
  }

}
if (!function_exists('dzs_sanitize_for_js_double_quote')) {
  function dzs_sanitize_for_js_double_quote($arg) {
    $arg = str_replace('"', '&#8221;', $arg);
    return $arg;
  }
}


if (!function_exists('dzs_add_query_arg')) {
  function dzs_add_query_arg($url, $key, $value) {

    $key = urlencode($key);
    $value = urlencode($value);

    $s = $url;
    $str_pair = $key . "=" . $value;

    $s = preg_replace('/(&|\\?)' . $str_pair . '/', '$1' . $str_pair, $url);


    if (strpos($s, $key . '=') !== false) {
    } else {
      if (strpos($s, '?') !== false) {
        $s .= '&' . $str_pair;
      } else {
        $s .= '?' . $str_pair;
      }
    }
    if ($value == 'NaN') {

      $s = preg_replace('/(&|\\?)' . $key . '=[^\&]*/', '', $s);
      if (strpos($s, '?') === false && strpos($s, '&') !== false) {
        $s = preg_replace('/&/', '?', $s);
      }
    }

    return $s;
  }
}

if (!function_exists('dzs_remove_query_arg')) {
  function dzs_remove_query_arg($url, $key) {
    return dzs_add_query_arg($url, $key, 'NaN');
  }
}


if (!function_exists('dzs_get_excerpt')) {
  function dzs_get_excerpt($pid = 0, $pargs = array()) {

    global $post;
    $fout = '';
    $excerpt = '';
    if ($pid == 0 && isset($post->ID)) {
      $pid = $post->ID;
    }

    $po = null;
    if (function_exists('get_post') && intval($pid) && intval($pid) > 0) {
      $po = (get_post($pid));
    }


    $args = array(
      'maxlen' => 400
    , 'striptags' => false
    , 'stripshortcodes' => false
    , 'forceexcerpt' => false // -- if set to true will ignore the manual post excerpt
    , 'readmore' => 'auto'
    , 'readmore_markup' => ''
    , 'content' => ''
    );
    $args = array_merge($args, $pargs);

    if ($args['content'] != '') {
      $args['readmore'] = 'off';
      $args['forceexcerpt'] = true;
    }


    if (isset($po->post_excerpt) && $po->post_excerpt != '' && $args['forceexcerpt'] == false) {
      $fout = $po->post_excerpt;


      if ($args['readmore_markup'] != '') {
        $fout = str_replace('{readmore}', $args['readmore_markup'], $fout);
      } else {
        if (function_exists('continue_reading_link')) {
          $fout = str_replace('{readmore}', continue_reading_link($pid), $fout);
        } else {
          $fout = str_replace('{readmore}', '<div class="readmore-con"><a href="' . get_permalink($pid) . '">' . esc_html__('Read More') . '</a></div>', $fout);
        }
      }

      return $fout;
    }

    $content = '';
    if ($args['content'] != '') {
      $content = $args['content'];
    } else {
      if ($args['striptags'] != 'on') {
        $content = $po->post_content;
      } else {
        $content = strip_tags($po->post_content);;
      }
    }


    $maxlen = intval($args['maxlen']);

    if (strlen($content) > $maxlen) {

      $excerpt .= substr($content, 0, $maxlen);

      if ($args['striptags'] == true) {
        $excerpt = strip_tags($excerpt);
      }

      if ($args['stripshortcodes'] == false && function_exists('do_shortcode')) {
        $excerpt = do_shortcode(stripslashes($excerpt));
      } else {
        $excerpt = stripslashes($excerpt);
        if (function_exists('strip_shortcodes')) {
          $excerpt = strip_shortcodes($excerpt);
        }
        $excerpt = str_replace('[/one_half]', '', $excerpt);
        $excerpt = str_replace("\n", " ", $excerpt);
        $excerpt = str_replace("\r", " ", $excerpt);
        $excerpt = str_replace("\t", " ", $excerpt);
      }

      $fout .= $excerpt;
      if ($args['readmore'] == 'auto') {
        $fout .= '{readmore}';
      }
    } else {

      $fout .= $content;
      if ($args['readmore'] == 'on') {
        $fout .= '{readmore}';
      }
    }


    if ($args['readmore_markup'] != '') {
      $fout = str_replace('{readmore}', $args['readmore_markup'], $fout);
    } else {
      if (function_exists('continue_reading_link')) {
        $fout = str_replace('{readmore}', continue_reading_link($pid), $fout);
      } else {
        if (function_exists('get_permalink')) {
          $fout = str_replace('{readmore}', '<div class="readmore-con"><a href="' . get_permalink($pid) . '">' . esc_html__('read more') . ' &raquo;</a></div>', $fout);
        }

      }
    }

    return $fout;
  }

}


if (!function_exists('dzs_print_menu')) {

  function dzs_print_menu() {
    $args = array('menu' => 'mainnav', 'menu_class' => 'menu sf-menu', 'container' => false, 'theme_location' => 'primary', 'echo' => '0');
    $aux = wp_nav_menu($args);
    $aux = preg_replace('/<ul>/', '<ul class="sf-menu">', $aux, 1);
    if (preg_match('/<div class="sf-menu">/', $aux)) {
      $aux = preg_replace('/<div class="sf-menu">/', '', $aux, 1);
      $aux = $rest = substr($aux, 0, -7);
    }


    print_r($aux);
  }

}
if (!function_exists('dzs_post_date')) {

  function dzs_post_date($pid) {
    $po = get_post($pid);

    if ($po) {
      echo mysql2date('l M jS, Y', $po->post_date);
    }
  }

}


if (!function_exists('dzs_pagination')) {

  function dzs_pagination($maxNumPages = 3, $range = 2, $pargs = array()) {
    global $paged;


    $margs = array(

      'container_class' => 'dzs-pagination qc-pagination',
      'include_raquo' => true,
      'include_prev_next' => false,
      'include_dots' => false,
      'style' => 'div',
      'link_style' => 'default',
      'paged' => '',
      'extraattr' => '',
      'a_class' => 'pagination-link',
      'wrap_before_text' => '<span class="the-pagination-number--inner">',
      'wrap_after_text' => '</span>',
      'make_main_div_regardles_of_nr_pages' => 'off',
    );


    if ($pargs) {
      $margs = array_merge($margs, $pargs);
    }


    $fout = '';
    $showitems = ($range * 2) + 1;

    if (empty($paged))
      $paged = 1;


    if ($margs['paged']) {
      $paged = $margs['paged'];
    }


    if ($maxNumPages == '') {
      global $wp_query;
      $maxNumPages = $wp_query->max_num_pages;
      if (!$maxNumPages) {
        $maxNumPages = 1;
      }
    }

    if (1 != $maxNumPages || $margs['make_main_div_regardles_of_nr_pages'] == 'on') {

      if ($margs['style'] == 'div') {

        $fout .= "<div class='" . $margs['container_class'] . "'";
      }
      if ($margs['style'] == 'ul') {

        $fout .= "<ul class='" . $margs['container_class'] . "'>";
      }

      $fout .= $margs['extraattr'];

      $fout .= ">";


      if ($margs['include_raquo']) {

        if ($paged > 2 && $paged > $range + 1 && $showitems < $maxNumPages) {
          $fout .= "<a href='" . get_pagenum_link(1) . "'>&laquo;</a>";
        }
        if ($paged > 1 && $showitems < $maxNumPages) {
          $fout .= "<a href='" . get_pagenum_link($paged - 1) . "'>&lsaquo;</a>";
        }
      }

      $needs_dots = true;


      $li_class = '';

      if ($margs['include_prev_next']) {


        if ($margs['style'] == 'div') {
          $link = DZSHelpers::add_query_arg(dzs_curr_url(), $margs['link_style'], (1));
          $fout .= "<a href='" . $link . "' class='" . $margs['a_class'] . ' prev-next-pagination-btn prev-pagination-btn' . "" . ' ' . " ' >";
          $fout .= esc_html__("Prev", 'dzswtl');
        }


      }


      $items_outputed = 0;

      for ($i = 1; $i <= $maxNumPages; $i++) {
        if (1 != $maxNumPages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $maxNumPages <= $showitems)) {


          if ($margs['link_style'] == 'default') {

            $link = get_pagenum_link($i);
          } else {

            $link = DZSHelpers::add_query_arg(dzs_curr_url(), $margs['link_style'], $i);
          }


          $li_class = '';

          if ($paged == $i) {

            $link = '#';


            if ($margs['style'] == 'div') {
              $li_class .= ' current';
            }
            if ($margs['style'] == 'ul') {
              $li_class .= ' active';
            }
          }


          if ($margs['style'] == 'div') {
            $fout .= "<a href='" . $link . "' class='pagination-number " . $margs['a_class'] . "" . $li_class . " inactive' >";
          }


          if ($margs['style'] == 'ul') {

            $fout .= '<li class="' . $li_class . '"><a class="' . $margs['a_class'] . '" href="' . $link . '">';
          }


          $fout .= $margs['wrap_before_text'];

          $fout .= $i;
          $fout .= $margs['wrap_after_text'];


          if ($margs['style'] == 'div') {
            $fout .= "</a>";
          }


          if ($margs['style'] == 'ul') {

            $fout .= '</a></li>';
          }

          $items_outputed++;
        }
      }


      if ($items_outputed < $maxNumPages) {

        if ($margs['include_dots']) {


          $fout .= '<span class="the-pagination-dots">...</span>';

          $i = $maxNumPages;
          $link = DZSHelpers::add_query_arg(dzs_curr_url(), $margs['link_style'], ($i));
          if ($margs['style'] == 'div') {
            $fout .= "<a href='" . $link . "' class=' pagination-number " . $margs['a_class'] . "" . $li_class . " inactive' >";
            $fout .= $margs['wrap_before_text'];

            $fout .= $i;
            $fout .= $margs['wrap_after_text'];

            if ($margs['style'] == 'div') {
              $fout .= "</a>";
            }

          }

        }
      }


      if ($margs['include_raquo']) {
        if ($paged < $maxNumPages && $showitems < $maxNumPages) $fout .= "<a href='" . get_pagenum_link($paged + 1) . "'>&rsaquo;</a>";


        if ($paged < $maxNumPages - 1 && $paged + $range - 1 < $maxNumPages && $showitems < $maxNumPages) $fout .= "<a href='" . get_pagenum_link($maxNumPages) . "'>&raquo;</a>";


      }
      if ($margs['include_prev_next']) {


        if ($margs['style'] == 'div') {
          $link = DZSHelpers::add_query_arg(dzs_curr_url(), $margs['link_style'], ($paged + 1));
          $fout .= "<a href='" . $link . "' class='" . $margs['a_class'] . ' prev-next-pagination-btn next-pagination-btn' . "" . ' ' . " ' >";
          $fout .= esc_html__("Next", 'dzswtl');
        }


      }


      if ($margs['style'] == 'div') {
        $fout .= '<div class="clearfix"></div>';
        $fout .= "</div>";
      }
      if ($margs['style'] == 'ul') {
        $fout .= '</ul>';
      }
    }
    return $fout;
  }


}


if (!function_exists('replace_in_matrix')) {

  function replace_in_matrix($arg1, $arg2, &$argarray) {
    foreach ($argarray as &$newi) {

      if (is_array($newi)) {
        foreach ($newi as &$newj) {
          if (is_array($newj)) {
            foreach ($newj as &$newk) {
              if (!is_array($newk)) {
                $newk = str_replace($arg1, $arg2, $newk);
              }
            }
          } else {
            $newj = str_replace($arg1, $arg2, $newj);
          }
        }
      } else {
        $newi = str_replace($arg1, $arg2, $newi);
      }
    }
  }

}


if (!function_exists('dzs_curr_url')) {

  function dzs_curr_url($pargs = array()) {

    $margs = array(

      'get_page_url_too' => true,
      'get_script_name' => false,
    );


    if ($pargs) {
      $margs = array_merge($margs, $pargs);
    }



    $page_url = '';
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
      $page_url .= "https://";
    } else {
      $page_url = 'http://';
    }


    $request_uri = $_SERVER["REQUEST_URI"];

    if ($margs['get_script_name']) {

      if ($_SERVER['SCRIPT_NAME']) {
        $request_uri = $_SERVER['SCRIPT_NAME'];
      }
    }



    if ($_SERVER["SERVER_PORT"] != "80") {
      $page_url .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $request_uri;
    } else {
      $page_url .= $_SERVER["SERVER_NAME"] . $request_uri;
    }


    if ($margs['get_page_url_too'] === false) {
      $aux_arr = explode('/', $page_url);



      $page_url = '';
      for ($i = 0; $i < count($aux_arr) - 1; $i++) {
        $page_url .= $aux_arr[$i] . '/';
      }
    }


    return $page_url;
  }

}


if (!function_exists('dzs_addAttr')) {

  function dzs_addAttr($arg1, $arg2) {
    $fout = '';

    if (isset($arg2) && $arg2 != "undefined" && $arg2 != '')
      $fout .= ' ' . $arg1 . "='" . $arg2 . "' ";
    return $fout;
  }

}


if (!function_exists('dzs_addSwfAttr')) {
  function dzs_addSwfAttr($arg1, $arg2, $first = false) {
    $fout = '';


    //sanitaze for object input
    $lb = array('"', "\r\n", "\n", "\r", "&", "`", '???', "'");
    $arg2 = str_replace(' ', '%20', $arg2);

    $arg2 = str_replace($lb, '', $arg2);

    if (isset ($arg2) && $arg2 != "undefined" && $arg2 != '') {
      if ($first == false) {
        $fout .= '&amp;';
      }
      $fout .= $arg1 . "=" . $arg2 . "";
    }
    return $fout;
  }
}


if (!function_exists('dzs_clean')) {

  function dzs_clean($var) {
    if (!function_exists('sanitize_text_field')) {
      return $var;
    } else {
      return sanitize_text_field($var);
    }
  }

}

if (!function_exists('dzs_read_from_file_ob')) {
  function dzs_read_from_file_ob($filepath) {
    // -- @filepath - relative to dzs_functions
    ob_start();
    include($filepath);
    return ob_get_clean();
  }

}
if (!function_exists('dzs_is_logged_in')) {
  function dzs_is_logged_in() {
    return is_user_logged_in();
  }
}

if (!function_exists('dzs_is_option_for_this')) {
  function dzs_is_option_for_this($oim, $seekedTag) {

    if (isset($oim['it_is_for']) && $oim['it_is_for']) {
      if (is_array($oim['it_is_for'])) {
        if (in_array($seekedTag, $oim['it_is_for'])) {
          return true;
        }
        return false;
      } else {
        if ($oim['it_is_for'] == $seekedTag) {
          return true;
        }
        return false;
      }
    }
    return true;
  }
}


if (!function_exists('dzs_get_current_screen')) {
  function dzs_get_current_screen() {
    global $current_screen;
    $responseArray = array(
      'base' => null,
      'action' => null,
    );

    if (isset($current_screen)) {
      if (isset($current_screen->base)) {
        $responseArray['base'] = $current_screen->base;
      }
      if (isset($current_screen->action)) {
        $responseArray['action'] = $current_screen->action;
      }
    }

    return $responseArray;
  }
}
if (!function_exists('dzs_assertIfPageCanHaveGutenbergBlocks')) {
  function dzs_assertIfPageCanHaveGutenbergBlocks() {
    global $post;

    $current_screen = dzs_get_current_screen();


    if (isset($post) &&
      (
        ($current_screen['base'] == 'post' && (isset($post->post_content) && strpos($post->post_content, 'wp:') !== false)) ||
        ($current_screen['base'] == 'post' && ($current_screen['action'] == 'new' || $current_screen['action'] == 'add'))
        || ($current_screen['base'] == 'post' && $current_screen['action'] != 'new' && (function_exists('has_blocks') && has_blocks($post->ID)) || (isset($post->post_content) && $post->post_content === ''))
      )
    ) {

      return true;
    }

    return false;
  }

}


if (!class_exists('DZSHelpers')) {

  class DZSHelpers {

    static function get_contents($url, $pargs = array()) {
      $margs = array(
        'force_file_get_contents' => 'off',
      );
      $margs = array_merge($margs, $pargs);
      if (function_exists('curl_init') && $margs['force_file_get_contents'] == 'off') { // if cURL is available, use it...
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $cache = curl_exec($ch);
        curl_close($ch);
      } else {
        $cache = @file_get_contents($url); // ...if not, use the common file_get_contents()
      }
      return $cache;
    }


    static function wp_savemeta($id, $arg2, $arg3 = '') {

      if ($arg3 == 'html') {
        update_post_meta($id, $arg2, htmlentities($_POST[$arg2]));
        return;
      }


      if (isset($_POST[$arg2]))
        update_post_meta($id, $arg2, esc_attr(strip_tags($_POST[$arg2])));
      else
        if ($arg3 == 'checkbox')
          update_post_meta($id, $arg2, "off");
    }

    static function get_query_arg($url, $key) {
      if (strpos($url, $key) !== false) {
        $pattern = '/[?&]' . $key . '=(.+?)(?=&|$)/';
        preg_match($pattern, $url, $matches);

        if ($matches && $matches[1]) {
          return $matches[1];
        }
      }
    }


    static function safe_add_query_arg() {

      $args = func_get_args();
      $total_args = count($args);
      $uri = $_SERVER['REQUEST_URI'];

      if (3 <= $total_args) {
        $uri = add_query_arg($args[0], $args[1], $args[2]);
      } elseif (2 == $total_args) {
        $uri = add_query_arg($args[0], $args[1]);
      } elseif (1 == $total_args) {
        $uri = add_query_arg($args[0]);
      }

      if (function_exists('esc_url')) {

        return esc_url($uri);
      } else {
        return $uri;
      }
    }

    static function add_query_arg($url, $key, $value) {
      $a = parse_url($url);

      $query = '';
      if (isset($a['query'])) {

        $query = $a['query'] ? $a['query'] : '';
        parse_str($query, $params);
        $params[$key] = $value;
        $query = http_build_query($params);
      }
      $result = '';
      if ($a['scheme']) {
        $result .= $a['scheme'] . ':';
      }
      if ($a['host']) {
        $result .= '//' . $a['host'];
      }
      if ($a['path']) {
        $result .= $a['path'];
      }
      if ($query) {
        $result .= '?' . $query;
      }
      return $result;
    }

    static function remove_query_arg($key, $query = false) {
      if (is_array($key)) { // removing multiple keys
        foreach ($key as $k)
          $query = DZSHelpers::add_query_arg($k, false, $query);
        return $query;
      }
      return DZSHelpers::add_query_arg($key, false, $query);
    }


    static function stripslashes_deep($value) {
      if (is_array($value)) {
        $value = array_map('stripslashes_deep', $value);
      } elseif (is_object($value)) {
        $vars = get_object_vars($value);
        foreach ($vars as $key => $data) {
          $value->{$key} = DZSHelpers::stripslashes_deep($data);
        }
      } elseif (is_string($value)) {
        $value = stripslashes($value);
      }

      return $value;
    }

    static function wp_parse_str($string, &$array) {
      parse_str($string, $array);
      if (function_exists('stripslashes_deep')) {
        if (get_magic_quotes_gpc()) {
          $array = DZSHelpers::stripslashes_deep($array);
        }
      }


      /**
       * Filter the array of variables derived from a parsed string.
       *
       * @param array $array The array populated with variables.
       * @since 2.3.0
       *
       */
      $array = DZSHelpers::apply_filters('wp_parse_str', $array);
    }


    static function apply_filters($tag, $value) {
      global $wp_filter, $merged_filters, $wp_current_filter;

      $args = array();

      if (isset($wp_filter['all'])) {
        $wp_current_filter[] = $tag;
        $args = func_get_args();
      }

      if (!isset($wp_filter[$tag])) {
        if (isset($wp_filter['all']))
          array_pop($wp_current_filter);
        return $value;
      }

      if (!isset($wp_filter['all']))
        $wp_current_filter[] = $tag;

      // Sort
      if (!isset($merged_filters[$tag])) {
        ksort($wp_filter[$tag]);
        $merged_filters[$tag] = true;
      }

      reset($wp_filter[$tag]);

      if (empty($args))
        $args = func_get_args();

      do {
        foreach ((array)current($wp_filter[$tag]) as $the_)
          if (!is_null($the_['function'])) {
            $args[1] = $value;
            $value = call_user_func_array($the_['function'], array_slice($args, 1, (int)$the_['accepted_args']));
          }

      } while (next($wp_filter[$tag]) !== false);

      array_pop($wp_current_filter);

      return $value;
    }

    /**
     * Navigates through an array and encodes the values to be used in a URL.
     *
     *
     * @param array|string $value The array or string to be encoded.
     * @return array|string $value The encoded array (or string from the callback).
     * @since 2.2.0
     *
     */
    static function urlencode_deep($value) {
      $value = is_array($value) ? array_map('DZSHelpers::urlencode_deep', $value) : urlencode($value);
      return $value;
    }

    static function build_query($data) {
      return DZSHelpers::_http_build_query($data, null, '&', '', false);
    }

    static function _http_build_query($data, $prefix = null, $sep = null, $key = '', $urlencode = true) {
      $ret = array();

      foreach ((array)$data as $k => $v) {
        if ($urlencode)
          $k = urlencode($k);
        if (is_int($k) && $prefix != null)
          $k = $prefix . $k;
        if (!empty($key))
          $k = $key . '%5B' . $k . '%5D';
        if ($v === null)
          continue;
        elseif ($v === FALSE)
          $v = '0';

        if (is_array($v) || is_object($v))
          array_push($ret, DZSHelpers::_http_build_query($v, '', $sep, $k, $urlencode));
        elseif ($urlencode)
          array_push($ret, $k . '=' . urlencode($v));
        else
          array_push($ret, $k . '=' . $v);
      }

      if (null === $sep)
        $sep = ini_get('arg_separator.output');

      return implode($sep, $ret);
    }


    static function transform_to_str_size($arg) {
      //-- DZSHelpers::transform_to_str_size(400%);
      $fout = $arg;
      if (strpos($arg, 'auto') !== false || strpos($arg, '%') !== false) {

      } else {
        $fout .= 'px';
      }
      return $fout;
    }



    static function generate_input_text($argname, $otherargs = array()) {
      $fout = '';

      $margs = array(
        'class' => '',
        'val' => '', // --- default value
        'seekval' => '', // --- the value to be seeked
        'type' => '',
        'extraattr' => '',
        'slider_min' => '10',
        'slider_max' => '80',
        'input_type' => 'text',
      );
      $margs = array_merge($margs, $otherargs);

      $fout .= '<input type="' . $margs['input_type'] . '"';
      $fout .= ' name="' . $argname . '"';


      if ($margs['type'] == 'colorpicker') {
        $margs['class'] .= ' with_colorpicker';
      }

      $val = '';


      if ($margs['class'] != '') {
        $fout .= ' class="' . $margs['class'] . '"';
      }
      if (isset($margs['seekval']) && $margs['seekval'] != '') {

        $fout .= ' value="' . $margs['seekval'] . '"';
        $val = $margs['seekval'];
      } else {
        $fout .= ' value="' . $margs['val'] . '"';
        $val = $margs['val'];
      }

      if ($margs['type'] == 'slider') {
        $fout .= ' ';
      }

      if ($margs['extraattr'] != '') {
        $fout .= '' . $margs['extraattr'] . '';
      }

      $fout .= '/>';


      if ($margs['type'] == 'slider') {

        $tempval = $val;

        if ($tempval == '' || intval($tempval) == false) {
          $tempval = 0;
        }

        $fout .= '<div id="' . $argname . '_slider" style="width:200px;"></div>';
      }
      if ($margs['type'] == 'colorpicker') {
        $fout .= '<div class="picker-con"><div class="the-icon"></div><div class="picker"></div></div>';
      }

      return $fout;
    }

    static function generate_input_checkbox($inputName, $argOptions) {
      $fout = '';
      $inputType = 'checkbox';

      if (isset($argOptions['type'])) {
        if ($argOptions['type'] == 'radio') {
          $inputType = 'radio';
        }
      }
      $fout .= '<input type="' . $inputType . '"';
      $fout .= ' name="' . $inputName . '"';
      if (isset($argOptions['class'])) {
        $fout .= ' class="' . $argOptions['class'] . '"';
      }

      if (isset($argOptions['id'])) {
        $fout .= ' id="' . $argOptions['id'] . '"';
      }
      $theval = 'on';
      if (isset($argOptions['val'])) {
        $fout .= ' value="' . $argOptions['val'] . '"';
        $theval = $argOptions['val'];
      } else {
        $fout .= ' value="on"';
      }

      if (isset($argOptions['seekval'])) {
        $isChecked = false;
        if (is_array($argOptions['seekval'])) {
          if (isset($argOptions['seekval'][$inputName]) && $argOptions['seekval'][$inputName] === $argOptions['val']) {
            $isChecked = true;
          }else{

            foreach ($argOptions['seekval'] as $argOption) {
              if ($argOption == $argOptions['val']) {
                $isChecked = true;
              }
            }
          }

        } else {
          if ($argOptions['seekval'] == $theval) {
            $isChecked = true;
          }
        }
        if ($isChecked) {
          $fout .= ' checked="checked"';
        }
      }
      $fout .= '/>';
      return $fout;
    }

    static function generate_input_textarea($argname, $otherargs = array()) {
      $fout = '';
      $fout .= '<textarea';
      $fout .= ' name="' . $argname . '"';

      $margs = array(
        'class' => '',
        'val' => '', // === default value
        'seekval' => '', // ===the value to be seeked
        'type' => '',
        'extraattr' => '',
      );
      $margs = array_merge($margs, $otherargs);


      if ($margs['class'] != '') {
        $fout .= ' class="' . $margs['class'] . '"';
      }
      if ($margs['extraattr'] != '') {
        $fout .= '' . $margs['extraattr'] . '';
      }
      $fout .= '>';
      if (isset($margs['seekval']) && $margs['seekval'] != '') {
        $fout .= '' . $margs['seekval'] . '';
      } else {
        $fout .= '' . $margs['val'] . '';
      }
      $fout .= '</textarea>';

      return $fout;
    }

    static function generate_select($argname, $pargopts) {

      $fout = '';
      $auxtype = 'select';

      if ($pargopts == false) {
        $pargopts = array();
      }

      $margs = array(
        'options' => array(),
        'class' => '',
        'seekval' => '',
        'extraattr' => '',
      );

      $margs = array_merge($margs, $pargopts);

      $fout .= '<select';
      $fout .= ' name="' . $argname . '"';
      if (isset($margs['class'])) {
        $fout .= ' class="' . $margs['class'] . '"';
      }
      if ($margs['extraattr'] != '') {
        $fout .= '' . $margs['extraattr'] . '';
      }

      $fout .= '>';


      if (is_array($margs) && isset($margs['options'])) {

        foreach ($margs['options'] as $opt) {
          $val = '';
          $lab = '';


          if (is_array($opt) && isset($opt['lab']) && isset($opt['val'])) {
            $val = $opt['val'];
            $lab = $opt['lab'];
          } else {
            if (is_array($opt) && isset($opt['label']) && isset($opt['value'])) {

              $val = $opt['value'];
              $lab = $opt['label'];
            } else {
              $val = $opt;
              $lab = $opt;
            }

          }

          if (is_array($val)) {
            $val = '';
          }
          if (is_array($lab)) {
            $lab = '';
          }

          $fout .= '<option value="' . $val . '"';
          if ($margs['seekval'] != '' && $margs['seekval'] == $val) {
            $fout .= ' selected';
          }

          $fout .= '>' . $lab . '</option>';
        }
      }
      $fout .= '</select>';
      return $fout;
    }


  }

}


if (!function_exists('vc_dzs_add_media_att')) {
  function vc_dzs_add_media_att($settings, $value) {

    error_log('vc_dzs_add_media_att()');

    $dependency = '';
    if (function_exists('vc_generate_dependencies_attributes')) {
      $dependency = vc_generate_dependencies_attributes($settings);
    }

    $settings = array_merge(array(
      'library_type' => ''
    ), $settings);


    $fout = '<div class="setting setting-medium setting-three-floats">';


    if (strpos($settings['class'], 'try-preview') !== false) {
      $fout .= '<div class="preview-media-con-left"></div>';
    }


    if (strpos($settings['class'], 'with-colorpicker') !== false) {
      $fout .= '<div class="colorpicker-con">';
      $fout .= '<i class="divimage color-spectrum"></i>';
      $fout .= '<div class="colorpicker--inner">';

      $fout .= '<div class="farb"></div>';
      $fout .= '</div>';


      $fout .= '</div>';
    }

    $fout .= '<div class="setting-input type-input overflow-it">
<input style="" name="' . $settings['param_name']
      . '" class="wpb_vc_param_value wpb-textinput setting-field dzs-preview-changer '
      . $settings['param_name'] . ' ' . $settings['type'] . '_field" type="text" value="'
      . $value . '" ' . $dependency . '/>
</div>

';


    $fout .= '<div class="change-media-con">
    <button class="button-secondary dzs-btn-add-media-att';


    if (strpos($settings['class'], 'button-setting-input-url') !== false) {
      $fout .= ' button-setting-input-url';
    }

    $fout .= '" data-library_type="' . $settings['library_type'] . '"><i class="fa fa-external-link"></i> ' . esc_html__("Add Media", DZSAP_ID) . '</button>
</div>';


    $fout .= '</div>';

    return $fout;
  }


}

if (!function_exists('print_rr')) {
  function print_rr($arg = array(), $pargs = array()) {
    $margs = array(
      'echo' => true,
      'encode_html' => false,
      'background_color' => '',
    );

    $pre_start = '<pre class="from-dzsap" style="';
    if ($margs['background_color']) {
      $pre_start .= 'background-color: ' . $margs['background_color'];
    }
    $pre_start .= '">';
    $pre_end = '</pre>';

    if ($pargs && $pargs === true && is_array($pargs) == false) {

      $pargs = array(
        'echo' => false
      );

    }
    if ($pargs) {
      $margs = array_merge($margs, $pargs);
    }


    echo $pre_start;
    if ($margs['echo'] == false && $margs['encode_html'] == false) {
      return $pre_start . print_r($arg, true) . $pre_end;
    }
    if ($margs['echo'] == false) {
      ob_start();
    }


    if ($margs['encode_html']) {
      echo htmlentities(print_r($arg, true));
    } else {
      print_r($arg);
    }

    echo $pre_end;

    if ($margs['echo'] == false) {
      $fout = ob_get_clean();

      return $fout;
    }


  }


}
if (!function_exists('print_rrr')) {


  function print_rrr($arg = array()) {


    return print_rr($arg, array('echo' => false));

  }


}

if (function_exists('dzs_sanitize_for_post_terms') == false) {
  function dzs_sanitize_for_post_terms($arg) {

    // -- sanitize the term for set_post_terms


    $fout = '';


    if (is_array($arg) || is_object($arg)) {


      if (count($arg) == 1) {

        if (isset($arg->term_id)) {
          return $arg->term_id;
        } else {
          return $arg;
        }
      }

      if (count($arg) > 1) {

        foreach ($arg as $it) {

          if ($fout) {
            $fout .= ',';
          }

          if (isset($it->term_id)) {

            $fout .= $it->term_id;
          } else {
            return $arg;
          }
        }
      }

    } else {
      return $arg;
    }

    return $fout;
  }
}


if (function_exists('sanitize_youtube_url_to_id') == false) {
  function sanitize_youtube_url_to_id($arg) {

    if (strpos($arg, 'youtube.com/embed') !== false) {
      $auxa = explode('/', 'youtube.com/embed/');

      if ($auxa[1]) {

        return $auxa[1];
      }
    }


    if (strpos($arg, 'youtube.com') !== false || strpos($arg, 'youtu.be') !== false) {


      if (DZSHelpers::get_query_arg($arg, 'v')) {
        return DZSHelpers::get_query_arg($arg, 'v');
      }

      if (strpos($arg, 'youtu.be') !== false) {
        $auxa = explode('/', 'youtube.com/embed/');

        $arg = $auxa[count($auxa) - 1];
      }
    }


    return $arg;
  }
}


if (!function_exists('dzs_esc__')) {
  function dzs_esc__($format, $args = array(), $isLax = false) {
    $allowedTags = array(
      'p' => array(
        'class' => array(),
        'style' => array(),
      ),
      'strong' => array(),
      'em' => array(),
      'br' => array(),
      'a' => array(
        'href' => array(),
        'target' => array(),
        'style' => array(),
        'class' => array(),
      ),
      'span' => array(
        'style' => array(),
        'class' => array(),
      ),
      'i' => array(
        'style' => array(),
        'class' => array(),
      ),
    );

    if ($isLax) {
      $allowedTags = array(
        'p' => array(
          'class' => array(),
          'style' => array(),
        ),
        'strong' => array(),
        'div' => array(
          'class' => true,
          'data-playerid' => true,
          'data-*' => true,
          'onclick' => array(),
          'style' => array(),
        ),
        'em' => array(),
        'br' => array(),
        'a' => array(
          'href' => array(),
          'data-*' => true,
          'target' => array(),
          'style' => array(),
          'onclick' => array(),
          'class' => array(),
        ),
        'span' => array(
          'style' => array(),
          'class' => array(),
        ),
        'i' => array(
          'style' => array(),
          'class' => array(),
        ),
      );

    }

    if (!is_array($args)) {
      $args = array();
    }
    $argumentsForSprintf = array_merge(array($format), $args);

    $string = $format;

    if (count($args)) {
      $string = call_user_func_array('sprintf',
        $argumentsForSprintf
      );
    }

    return wp_kses($string, $allowedTags);

  }
}
if (!function_exists('dzs_wp_getPostBySlug')) {
  function dzs_wp_getPostBySlug($slug, $postType) {

    $args = [
      'post_type' => $postType,
      'posts_per_page' => 1,
      'post_name__in' => [$slug]
    ];
    $q = get_posts($args);

    if (isset($q[0])) {
      return $q[0];
    }

    return null;
  }
}