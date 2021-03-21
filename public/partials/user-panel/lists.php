<?php do_action('before_newletter_page') ?>
<div class="content-panel content-active" id="newsletter">
    <div class="row">
        <div class="col-12">
            <h3 class="text-center"><?php echo __('Newsletter','user-panel')?></h3>
        </div>
    </div>
    <div class="row">
        <?php do_action('newletter_extra_content') ?>
    </div>
</div>