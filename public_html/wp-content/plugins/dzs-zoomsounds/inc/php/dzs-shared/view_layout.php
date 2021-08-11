<?php
if (!class_exists('DzsViewLayoutStyleModes')) {
  class DzsViewLayoutStyleModes {
    public static $modes = array(
      'GRID' => 'grid'
    );
  }
}


if (!function_exists('dzs_view_layoutStyle')) {

  /**
   * @param array $layoutOptions = [
   *      ]
   * @param array $items = [
   *        $anyKey => [
   *          'featuredUri' => '',
   *          'thumbnailImageUrl' => '', // image url
   *          'title' => '',
   *          'subtitle' => '',
   *        ],
   * ]
   */
  function dzs_view_layoutStyle($layoutOptions = array(
    'display_mode' => 'grid',
    'items' => array(),
  ), $items = array(), $baseUrl = '') {

    $layoutOptions = array_merge(
      array(
        'display_mode' => 'grid',
      ), $layoutOptions
    );

    ob_start();

    if ($layoutOptions['display_mode'] == DzsViewLayoutStyleModes::$modes['GRID']) {
      ?>
      <div class="dzs-layout--style-grid"><?php
      foreach ($items as $item) {
        ?>
        <div class="dzs-grid-item">
        <a href="<?php echo $item['featuredUri'] ?>">

          <div class="dzs-grid-item--thumbnail divimage"
               style="background-image: url(<?php echo $item['thumbnailImageUrl'] ?>)"></div>
          <div class="dzs-grid-item--title"><?php echo $item['title'] ?></div><?php
          if (isset($item['subtitle']) && $item['subtitle']) {
            ?>
            <div class="dzs-grid-item--subtitle"><?php echo $item['subtitle'] ?></div><?php
          }
          ?>
        </a>
        </div><?php
      }

      ?></div><?php
      wp_enqueue_style('dzs-view-layout-grid', $baseUrl . 'inc/dzs-shared-assets/style/dzs-layout-' . $layoutOptions['display_mode'] . '.css');
    }


    return ob_get_clean();


  }
}



/**
 * @param array $arr = [
 *     'fields' => [ // Defines the feilds to be shown by scaffolding
 *         $anyKey => [
 *             'name' => 'sale', // Overrides the field name (default is the array key)
 *             'model' => 'customer', // (optional) Overrides the model if the field is a belongsTo associated value.
 *             'width' => '100px', // Defines the width of the field for paginate views. Examples are "100px" or "auto"
 *             'align' => 'center', // Alignment types for paginate views (left, right, center)
 *             'format' => 'nice', // Formatting options for paginate fields. Options include ('currency','nice','niceShort','timeAgoInWords' or a valid Date() format)
 *             'title' => 'Sale', // Changes the field name shown in views.
 *             'desc' => 'A deal another person that results in money', // The description shown in edit/create views.
 *             'readonly' => false, // True prevents users from changing the value in edit/create forms.
 *             'type' => 'password', // Defines the input type used by the Form helper
 *             'options' => ['option1', 'option2'], // Defines a list of string options for drop down lists.
 *             'editor' => false, // If set to True will show a WYSIWYG editor for this field.
 *             'default' => '', // The default value for create forms.
 *         ],
 *     ],
 * ]
 */