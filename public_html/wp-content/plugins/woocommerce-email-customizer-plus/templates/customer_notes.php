<?php
foreach ($order_notes as $order_note) {
    ?>
    <div class="note_content">
        <p>
            <?php echo $order_note->comment_content; ?>
        </p>
    </div>
    <?php
}