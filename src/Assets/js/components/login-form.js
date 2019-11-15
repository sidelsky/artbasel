/* global require */
/* global window */
/* global site_data */
/* jshint -W097 */
"use-strict";

(function() {
    var user_pass = document.getElementById("user_pass1");
    var submit_button = document.getElementById("wp-submit1");
    var loginform = document.getElementById("loginform1");

    function intit() {
        if (loginform) {
            user_pass.addEventListener("keyup", checkForChange);
        }
    }

    function checkForChange() {
        if (user_pass.value.length > 0) {
            submit_button.removeAttribute("disabled");
        } else {
            submit_button.setAttribute("disabled", "disabled");
        }
    }

    intit();
})();
