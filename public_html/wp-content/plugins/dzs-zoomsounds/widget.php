<?php



class DZSAP_Tags_Widget extends WP_Widget {

  /**
   * Sets up the widgets name etc
   */
  public function __construct() {
    $widget_ops = array(
      'classname' => 'dzsap_tags_widget',
      'description' => 'ZoomSounds '.esc_html__('Song tags','dzsap'),
    );
    parent::__construct( 'dzsap_tags_widget', 'ZoomSounds '.esc_html__('Song tags','dzsap'), $widget_ops );
  }

  static function register_this_widget(){
    register_widget(__CLASS__);
  }

  /**
   * Outputs the content of the widget
   *
   * @param array $args
   * @param array $instance
   */
  public function widget( $args, $instance ) {






    echo $args['before_widget'];
    if ( ! empty( $instance['title'] ) ) {
      echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
    }








    $taxonomy = 'dzsap_tags';
    $term_list = get_terms( $taxonomy, array(
      'hide_empty' => false,
    ) );


    $fout = '';
    if(is_array($term_list) && count($term_list)>0){

      $fout.='<ul>';


      foreach ($term_list as $lab=>$term){

        $fout.='<li>';


        $cach_tag = $term;
        $fout.='<a class="dzsap-tag" href="';


        $fout.=add_query_arg(array(
          'query_song_tag'=>$cach_tag->slug
        ),dzs_curr_url());

        $fout.='">#';
        $fout.=$cach_tag->name;

        $fout.='</a>';
        $fout.='</li>';


      }

      $fout.='</ul>';

    }
    echo $fout;
    echo $args['after_widget'];
  }

  /**
   * Outputs the options form on admin
   *
   * @param array $instance The widget options
   */
  public function form( $instance ) {
    // outputs the options form on admin




    $margs = array(
      'title'=>'',
      'shortcode'=>'',
    );




    if(is_array($instance)){
      $margs = array_merge($margs, $instance);
    }



    ?>
    <div>
    <h5 for="<?php echo $this->get_field_id('title'); ?>"><?php echo esc_html__("Title", 'dzsap'); ?></h5>
    <input type="text" name="<?php echo $this->get_field_name('title') ?>" id="<?php echo $this->get_field_id('title') ?> " value="<?php echo $margs['title'] ?>" size="20"/>
    </div>


    <?php
  }

}