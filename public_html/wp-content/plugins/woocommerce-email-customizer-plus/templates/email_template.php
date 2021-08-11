<?php

use Wecp\App\Controllers\EmailManager;

$manager = new EmailManager();
echo $manager->getRenderedTemplate($args);