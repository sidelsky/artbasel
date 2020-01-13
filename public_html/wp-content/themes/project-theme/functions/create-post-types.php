<?php

    use App\Helper\CustomPostType;


    /**
     * Custom Post Yypes
     */
    add_action('init', 'createPostTypes');
    function createPostTypes()
    {
        //CustomPostType::createPostType('FAQ', 'FAQs');

        $instance = new CustomPostType();

        $instance->createPostType('Works', 'Works', $args=[
            'hierarchical' => TRUE,
            'has_archive' => TRUE,
            'menu_icon' => 'dashicons-format-image',
            'supports' => [
                'revisions' => TRUE
            ],
            'rewrite' => [
                'slug' => FALSE,
                'with_front' => TRUE
            ]
        ]);

    }

    //Static functions, by definition, cannot and do not depend on any instance properties of the class. That is, they do not require an instance of the class to execute (and so can be executed. In some sense, this means that the function doesn't (and will never need to) depend on members or methods (public or private) of the class.

    // class Example {

    //     // property declaration
    //     public $value = "The text in the property";
    
    //     // method declaration
    //     public function displayValue() {
    //         echo $this->value;
    //     }
    
    //     static function displayText() {
    //         echo "The text from the static function";
    //     }
    // }

    // $instance = new Example();
    // $instance->displayValue();
    
    // $instance->displayText();
    
    // // Example::displayValue();         // Direct call to a non static function not allowed
    
    // Example::displayText();

