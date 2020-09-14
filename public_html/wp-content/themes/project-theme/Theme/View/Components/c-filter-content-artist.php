<?php
      $postContent = $data['works_content']; 

      // Shuffle content random
      sort($postContent);

      foreach( $postContent as $content ) :

            $title = get_the_title($content->ID);
            $surname = get_field('surname', $content->ID);
            $fullName = get_field('full_name', $content->ID);

            echo '<li data-filter=".' . $surname . '" class="c-filter__item">' . $title . '</li>';
      endforeach;