<?php
      $postContent = $data['works_content']; 

      // Shuffle content random
		sort($postContent);

      //$medium = get_field_object('field_5f59ebf6d4758');
      //print_r( $medium );


      
      foreach( $postContent as $content ) :

            //$medium = get_field('medium', $content->ID);

            //$valueLabel = $medium['label'];
            //$valueValue = $medium['value'];

            // foreach($medium as $value) :
            //    $valueValue = $value['value'];
            //    $valueLabel = $value['label'];
            // endforeach;
            
            //echo '<li data-filter=".' . $valueValue . '" class="c-filter__item item">' . $valueLabel . '</li>';
            
      endforeach;


      ?>


<?php
//$field = get_field_object('medium'); 
$field = get_field_object('field_5f59ebf6d4758')['choices'][1];
//$colors = get_field('medium');

print_r($field );

// foreach( $field as $value ) {
//    echo $value['choices'];
// }

// foreach($colors as $key => $val) {
//     $label = $colors[$key];
//     echo 'This is your label: '. $field['choices'][$label]; 
// }