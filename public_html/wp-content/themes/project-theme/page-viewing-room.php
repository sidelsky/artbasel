<?php

/**
 * Template Name: Viewing room
 */

use App\Helper\Render;
use Theme\Model\Layout;
use Theme\Model\ViewingRoom;

$render = new Render;
$layout = new Layout;
$viewing_room = new ViewingRoom;


$allLayouts = $layout->getLayout();

include("header.php"); ?>


<?php include("footer.php"); ?>
