<div class="column-three">
    <div class="card">
        <div class="settings-container">
            <?php
            if (isset($tab_content)) {
                echo $tab_content;
            } else {
                NULL;
            }
            ?>
        </div>
    </div>
    <script>
        jQuery(document).ready(function ($) {
            wp.codeEditor.initialize($('#wecp_custom_css'), cm_settings);
        });
    </script>
</div>