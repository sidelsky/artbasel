<?php
/**
 * Template name: Contact
 */
use App\Helper\Render;
use Theme\Model\Contact;
use Theme\Model\Enquiries;

$render = new Render;
$contact = new Contact();
$enquiries = new Enquiries();

include("header.php"); ?>

<section class="u-section u-bg-color--bw-white">
    <div class="u-l-container--center u-l-container" data-in-viewport>
        <div class="u-l-container u-l-container--row u-l-horizontal-padding u-l-vertical-padding--small">

            <div class="tabs">

                <?php 
                    //Gravity Forms title
                    $forms = RGFormsModel::get_forms(1);
                    $arrayCount = sizeof($forms);

                    for ($i = 1; $i <= $arrayCount ; $i++) { 
                        
                        $labels = $forms[$i-1]->title;
                        $id = $forms[$i-1]->id;

                        ?>

                        <input name="tabs" type="radio" id="tab-<?= $id ?>" <?= $isChecked = $i == 1 ? 'checked' : '' ; ?> class="input <?= $id ?>"/>
                        <label for="tab-<?= $id ?>" class="label"><?= $labels ?></label>
                        <div class="panel">
                            <!-- Tab Content -->
                            <?php
                                //Contact Form
                                $formData = [
                                    'id' => $id,
                                    'title' => 'false',
                                    'description' => 'true',
                                    'ajax' => 'false'
                                ];

                                $template = 'c-contact-form';
                                echo $render->view('Components/' . $template, $formData);
                            ?>
                        </div>
                    
                <?php } ?>

        </div>
    </div>
</section>
    
    <?php

        //Contact Details
        $template = 'c-contact';
        $data = $contact->getData();
        echo $render->view('Components/' . $template, $data);

        //Enquiries
        $template = 'c-enquiries';
        $data = $enquiries->getData();
        echo $render->view('Components/' . $template, $data);

    ?>


<?php include("footer.php"); ?>
