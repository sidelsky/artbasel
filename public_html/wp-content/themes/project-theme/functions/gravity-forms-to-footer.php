<?php

    add_filter("gform_init_scripts_footer", "init_scripts");

    function init_scripts() {
        return true;
    }