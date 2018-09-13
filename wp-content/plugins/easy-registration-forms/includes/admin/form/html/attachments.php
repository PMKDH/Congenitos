<?php
        require_once('class-attachment-cards.php');
        $cards = new ERForms_Attachment_Cards;
        $cards->prepare_items();
?>
<div id="erforms-overview" class="wrap erforms-admin-wrap erf-wrapper-bg">
    <div class="erforms-admin-content">
                <div class="erf-card-wrap erf-attachment-card-wrap">
                    <?php $cards->views(); ?>
                    <?php $cards->display(); ?>
                </div>

    </div>
    
</div>