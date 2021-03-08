<?php get_header() ?>

<div class="container">
    <div class="row">
        <?php do_action('before_lists_page') ?>
        <div class="col-md-8 col-12 mx-auto form">
            <?php do_action('before_lists_form') ?>
            <div id="msg-ok"></div>
            <form method="post" id="mailtrain">
                <div id="user-data">
                    <h3 class="text-center">
                        <?php echo __('Receive updates.', 'mailtrain-api') ?>
                    </h3>

                    <span class="down pb-3 text-center d-block">
                        <?php echo __('We want to meet you, tell us your name and your e-mail:', 'mailtrain-api') ?>
                    </span>
                    <div class="form-group">
                        <input type="text" name="name" id="mailtrain_name" class="form-control" data-merge="FIRST_NAME" placeholder="<?php echo __('Name', 'mailtrain-api') ?>">
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" id="mailtrain_email" class="form-control" data-merge="EMAIL" placeholder="<?php echo __('Email', 'mailtrain-api') ?>">
                    </div>
                    <div class="text-center row">
                        <div class="col-md-4 col-12 mx-auto">
                            <button type="button" class="btn btn-primary btn-block" id="mailtrain-next"><?php echo __('Next', 'mailtrain-api') ?></button>
                        </div>
                    </div>
                    <span class="d-block w-100 text-center pt-3 pb-3">
                        <?php echo __('You can edit your preferences, from your user profile, whenever you want.', 'mailtrain-api') ?>
                    </span>
                </div>
                <div id="lists" class="row">
                    <div class="col-12">
                        <?php do_action('before_lists_form') ?>
                        <h3 class="text-center">
                            <?php echo __('Hi ', 'mailtrain-api') ?> <?php echo is_user_logged_in() ? wp_get_current_user()->user_firstname : '<div id="name-user"></div>'; ?>
                        </h3>

                        <span class="down pb-3 text-center d-block">
                            <?php echo __('You can choose between these alternatives to find out:', 'mailtrain-api') ?>
                        </span>
                    </div>
                    <?php
                    $args = [
                        'post_type' => 'mailtrain_lists'
                    ];

                    $query = new WP_Query($args);
                    if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();
                    ?>
                            <div class="col-12 list-item d-flex mb-1">
                                <div class="list-img">
                                    <img src="<?php echo get_the_post_thumbnail_url(get_the_ID()) ?>" class="img-fluid" />
                                </div>
                                <div class="list-content p-3">
                                    <span class="list-frecuency">
                                        <?php echo get_post_meta(get_the_ID(), '_frecuency', true) ?>
                                    </span>
                                    <h3 class="list-title">
                                        <?php echo get_the_title(get_the_ID()) ?>
                                    </h3>
                                    <span class="list-content-text">
                                        <?php echo get_the_content(get_the_ID()) ?>
                                    </span>
                                    <div class="row">
                                        <div class="content-author col-12 col-md-8">
                                            <?php echo __('By :', 'mailtrain-api') ?> <?php echo get_post_meta(get_the_ID(), '_author_newsletter', true) ?>
                                        </div>
                                        <div class="content-checkbox col-12 col-md-4">
                                            <input type="checkbox" name="list-cid[]" class="list-item-select" value="<?php echo get_post_meta(get_the_ID(), '_list_cid', true) ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        endwhile;
                        ?>
                        <div id="chose" class="p-3">
                            <div id="choose-themes" class="lists-numbers"></div> <?php echo __(' of ', 'mailtrain-api') ?> <div id="count-themes" class="lists-numbers"></div> <?php echo __(' topics chose.', 'mailtrain-api') ?>
                        </div>
                        <div class="col-12 terms-and-conditions text-center">
                            <input type="checkbox" name="terms" id="terms-and-conditions" value=""> <?php echo sprintf(__('I accept %s '), '<a href="' . get_permalink(get_option('mailtrain_terms_page')) . '">' . __('Terms and Conditions') . '</a>') ?>
                        </div>
                        <div class="col-12 button-container d-flex justify-content-between mt-3">
                            <button type="button" id="prev-button" class="btn btn-warning button-mailtrain"><?php echo __('Previus', 'mailerlite-api') ?></button>
                            <button type="button" id="finish-button" class="btn btn-success button-mailtrain"><?php echo __('Next', 'mailerlite-api') ?></button>
                        </div>

                    <?php
                    else :
                        echo '<div class="col-12 text-center"> ' . __('Sorry, no lists there.', 'mailtrain-api') . '</div>';
                    endif;
                    ?>
                </div>
                <div class="row text-center" id="thanks-newsletter">
                    <div class="col-12">
                        <h2><?php echo sprintf(__('Thanks %s', 'mailtrain-api'), '<div id="name-user"></div>'); ?></h2>

                        <?php echo __('Now you will receive information from our site.', 'mailtrain-api') ?>
                        <a href="<?php echo home_url()?>"><?php echo __('Go to home','mailtrain-api')?></a>
                    </div>

                </div>
            </form>
            <?php do_action('after_lists_form') ?>
        </div>
        <?php do_action('after_lists_page') ?>
    </div>
</div>

<?php get_footer() ?>