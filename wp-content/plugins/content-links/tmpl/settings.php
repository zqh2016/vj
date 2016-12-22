<?php
    defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
?>
<div style="display: none;" class="message-notice">
    <div class="message-notice-close" style="display: none;">[X]</div>
    <div class="message-notice-status"></div>
    <div class="message-notice-display"></div>
    
</div>
<div id="info-details" style="display: none;">
    <div class="title-info-details">
        <?php lang::get("Link anchors explanation"); ?> 
    </div>
    <hr>
    <div class="content-info-details">
        <?php lang::get("Words and phrases, which will be used for an automatic linking between texts in corresponding category.");?>
        <br />
        <?php lang::get("Also, you can use a roots of the words, that you want to use for linking. The plugin will find the word with corresponding roots automatically and will use their words for link creating.");?>
    </div>
    <div class="button-close">
        <input type="button" value="<?php lang::get('Close');?>" class="lp-button-close" onclick="jQuery('#info-details').arcticmodal('close');">
    </div>
</div>
<div id="content-link-support" style="display: none;">
    <form action="<?php echo admin_url("admin-post.php?action=cl_support");?>" method="post">
        <div class="title"><?php lang::get("Sending of suggestions");?></div>
        <div class="body">
            <table>
                <tr id="loading-field" style="display: none;">
                    <td colspan="3">
                        <img src="<?php echo plugins_url('/assets/img/wpadmloader.gif', dirname(__FILE__));?>" alt="loading" >
                    </td>
                </tr>
                <tr id="message-field">
                    <th style="vertical-align: top;"><?php lang::get('Suggestion');?></th>
                    <td colspan="2"><textarea id="message" name="message"></textarea></td>
                </tr>
                <tr id="message-result" style="display: none;">
                    <td colspan="2" style="font-size:16px; vertical-align: middle; height: 120px;"></td>
                </tr>
                <tr id="button-sent">
                    <td width="0"></td>
                    <td style="padding-top: 20px; text-align: left;">
                        <span style="margin-left:10px">
                            <input type="button" class="button button-primary" value="<?php lang::get("Submit request");?>" onclick="sendMessageSupport(this)">
                        </span>
                    </td>
                    <td style="padding-top: 20px; text-align: right;">
                        <span style="float: right;margin-right: 10px;line-height: 33px;">
                            <a href="javascript:void(0)" onclick="closeSupport();"><?php lang::get("Cancel and go back")?></a>
                        </span>
                    </td>
                </tr>
                <tr id="button-ok" style="display: none;">
                    <td colspan="2" style="text-align: center;">
                        <a href="javascript:void(0)" onclick="closeSupport();"><?php lang::get("Go back")?></a>
                    </td>
                </tr>
            </table>
        </div>
    </form>
</div>

<div class="wrap">
    <?php if (!empty($error)) {
            echo '<div class="error" style="text-align: center; color: red; font-weight:bold;">
            <p style="font-size: 16px;">
            ' . $error . '
            </p></div>'; 
    }?>
    <?php if (!empty($msg)) {
            echo '<div class="updated" style="text-align: center; font-weight:bold;">
            <p style="font-size: 16px;">
            ' . $msg . '
            </p></div>'; 
    }?>
    <div class="block-pro-stars">
        <div class="main-block-pro-stars">
            <div class="block-pro">
                <div class="pro-title">
                    <?php lang::get('Use Professional version of <strong>"SEO Post Content Links"</strong> plugin and get:') ; ?>  
                </div>
                <ul class="pro-list">
                    <li>
                        <img src="<?php echo plugins_url('/assets/img/ok.png', dirname(__FILE__));?>" alt="" title="" />
                        <span>
                            <?php lang::get('Link anchors corresponds to the target text'); ?>
                        </span>
                        <div class="clear"></div>
                    </li>
                    <li>
                        <img src="<?php echo plugins_url('/assets/img/ok.png', dirname(__FILE__));?>" alt="" title="" />
                        <span>
                            <?php lang::get('Link anchors style customization'); ?>
                        </span>
                        <div class="clear"></div>
                    </li>
                    <li>
                        <img src="<?php echo plugins_url('/assets/img/ok.png', dirname(__FILE__));?>" alt="" title="" />
                        <span>
                            <?php lang::get('Priority support for PRO version'); ?>
                        </span>
                        <div class="clear"></div>
                    </li>
                    <li>
                        <img src="<?php echo plugins_url('/assets/img/ok.png', dirname(__FILE__));?>" alt="" title="" />
                        <span>
                            <?php lang::get('One year free updates'); ?>
                        </span>
                        <div class="clear"></div>
                    </li>
                </ul>
                <form id="content_links_pro_form" name="content_links_pro_form" method="post" action="<?php echo cl_api::$url_secure; ?>/api/">
                    <input type="hidden" name="site" value="<?php echo home_url(); ?>">
                    <input type="hidden" name="actApi" value="<?php echo 'proBackupPay'; ?>">
                    <input type="hidden" name="email" value="<?php echo get_option('admin_email');?>">
                    <input type="hidden" name="plugin" value="<?php echo 'content-links'; ?>">
                    <input type="hidden" name="success_url" value="<?php echo admin_url("admin.php?page=link-settings&pay=success"); ?>">
                    <input type="hidden" name="cancel_url" value="<?php echo admin_url("admin.php?page=link-settings&pay=cancel"); ?>">
                    <input class="button button-primary button-hero" type="submit" value="<?php lang::get('Get PRO'); ?>">
                </form>
            </div>
            <div class="block-stars" style="">
                <div class="block-stars-click" style="cursor: pointer;" onclick="window.open('https://wordpress.org/support/view/plugin-reviews/content-links?filter=5')">
                    <div class="stars"><?php lang::get('Leave us '); ?> <a><?php lang::get('5 stars'); ?></a></div>
                    <div class="stars"><img src="<?php echo plugins_url('/assets/img/stars-5.png', dirname(__FILE__));?>" alt="<?php lang::get('5 stars'); ?>" title="<?php lang::get('5 stars'); ?>" /></div>
                    <div class="stars" style="font-size: 16px"><?php lang::get('It will help us to develop this plugin for you'); ?></div>
                </div>
                <div class="block-support">
                    <?php lang::get('If you have any suggestions or wishes')?>
                    <input type="button" onclick="showModal('content-link-support')" class="button button-primary" value="<?php lang::get('Contact us')?>">
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <form action="" method="post">
        <div class="main-form-setting">
            <div class="title-setting" onclick="shows_form('.setting-linking', '#icon-title');">
                <?php lang::get('Settings')?>
                <span id="icon-title" class="dashicons dashicons-arrow-down"></span>
            </div>
            <div class="setting-linking" style="display: none;">
                <table class="form-table">
                    <tr>
                        <th>
                            <label for="count-links"><?php lang::get('Count of links in text')?></label>
                            <br />
                            <span style="font-size: 12px; font-weight: 300;">(<?php lang::get('Links won\'t be created if the word roots or words or phrases wasn\'t found' )?>)</span>
                        </th>
                        <td><input type="text" name="count_links" id="count-links" value="<?php echo $link_count; ?>"></td>
                    </tr>
                    <tr>
                        <th>
                            <label for="black-words"><?php lang::get('Black Words')?></label> 
                            <br />
                            <span style="font-size: 12px; font-weight: 300;">
                                (<?php lang::get('Stop words')?>)
                            </span>
                        </th>
                        <td> <textarea cols="70" style="resize:none;" name="black_words" id="black-words"><?php echo $black_words; ?></textarea> </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <input type="radio" name="links_category" id="links-category_1" value="1" <?php echo ( ($link_in_one_category == 1) ? 'checked="checked"' : '' );?> > 
                            <label for="links-category_1"><?php lang::get('Linking posts among themselves within one category')?></label>
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <input type="radio" name="links_category" id="links-category_0" value="0" <?php echo ( ($link_in_one_category == 0) ? 'checked="checked"' : '' );?> >
                            <label for="links-category_0"><?php lang::get('Linking posts among themselves between all categories')?></label>
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td><input type="submit" class="button button-primary" value="<?php lang::get("Save"); ?>" /> </td>
                    </tr>
                </table>
            </div>
        </div>
        <table class="wp-list-table widefat fixed tags cl-table" style="border:1px solid #aaa;">
            <thead>
                <tr>
                    <th align="center" width="150" class="title-table"><?php lang::get("Name"); ?></th>
                    <th align="center" width="300" class="title-table"><?php lang::get("Description"); ?></th>
                    <th align="center" width="150" class="title-table"><?php lang::get("Label"); ?></th>
                    <th width="90" align="center" class="title-table"><?php lang::get("Post Count"); ?></th>
                    <th align="center" class="title-table"><?php lang::get("Link anchors"); ?><br />(<a style="font-size: 12px;" href="javascript:void(0)" onclick="showModal('info-details')"><?php lang::get("explanation"); ?></a>)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($categories as $category) {
                        $linking_text = self::getLiningByCat($category->cat_ID);
                    ?> 
                    <tr>
                        <td >
                            <strong>
                                <a href="edit-tags.php?action=edit&taxonomy=category&tag_ID=<?php echo $category->cat_ID; ?>&post_type=post" target="_blank"><?php echo $category->name?></a>
                            </strong>
                        </td>
                        <td ><?php echo $category->description; ?></td>
                        <td ><?php echo urldecode( $category->slug ); ?></td>
                        <td ><a href="edit.php?category_name=<?php echo $category->slug;?>"><?php echo $category->count; ?></a></td>
                        <td ><?php echo isset($linking_text[0]['linking_text']) ? $linking_text[0]['linking_text'] : ''; ?></td>
                    </tr>
                    <?php
                }?>
            </tbody>
        </table>
    </form>
</div>
