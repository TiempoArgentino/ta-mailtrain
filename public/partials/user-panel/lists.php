<?php do_action('before_newletter_page') ?>
<div class="content-panel" id="newsletter">
    <div class="row">
        <div class="col-12">
            <h3 class="text-center"><?php echo __('Newsletter','user-panel')?></h3>
        </div>
    </div>
    <div class="row">
        <?php do_action('newletter_extra_content') ?>
        <h2><?php echo __('Your lists','ser-panel')?></h2>
        <?php 
        foreach(json_decode(mailtrain_api()->get_lists_user(wp_get_current_user()->user_email)) as $ll){
            echo $ll->{'name'};
            }
        ?>
    </div>
</div>  