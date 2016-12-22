<?php

    class lgp_core {
        private static $mail_support = 'support@wpadm.com';
        private static $table_prefix = 'lgp_';
        private static $table_name_linking = 'linking';
        private static $table_name_post = 'posts';
        private static $default_setting = array('notice' => array('stars5' => true, 'pro' => true ));
        private static $default_count_link = 3;
        private static $links_in_one_category = 1;
        private static $black_words = array( 
        'or', 'he', 'after', 'as', 'at', 'by', 'in', 'on', 'of', 'off', 'per', 'pro', 
        'to', 'able', 'about', 'again', 'all', 'almost',
        'already', 'also', 'although', 'and', 'another', 'any', 'are', 'around',
        'based', 'because', 'been', 'before', 'being', 'between', 'both', 'bring',
        'but', 'came', 'can', 'com', 'come', 'comes', 'could', 'did', 'does',
        'doing', 'done', 'each', 'eight', 'else', 'etc', 'even', 'every', 'five',
        'for', 'four', 'from', 'get', 'gets', 'getting', 'going', 'got', 'had',
        'has', 'have', 'her', 'here', 'him', 'himself', 'his', 'how', 'however',
        'href', 'http', 'including', 'into', 'its', 'ing', 'just', 'know', 'like',
        'looks', 'mailto', 'make', 'making', 'many', 'may', 'means', 'might',
        'more', 'more', 'most', 'move', 'much', 'must', 'need', 'needs', 'never',
        'nice', 'nine', 'not', 'now', 'often', 'one', 'only', 'org', 'other',
        'our', 'out', 'over', 'own', 'piece', 'rather', 'really', 'said', 'same',
        'say', 'says', 'see', 'seven', 'several', 'she', 'should', 'since',
        'single', 'six', 'some', 'something', 'still', 'stuff', 'such', 'take',
        'ten', 'than', 'that', 'the', 'their', 'them', 'them', 'then', 'there',
        'there', 'these', 'they', 'thing', 'things', 'this', 'those',
        'three', 'through', 'too', 'took', 'two', 'under', 'use', 'used', 'using',
        'usual', 'very', 'via', 'want', 'was', 'way', 'well', 'were', 'what',
        'when', 'where', 'whether', 'which', 'while', 'whilst', 'who', 'why',
        'will', 'with', 'within', 'would', 'yes', 'yet', 'you', 'your');

        static function debug($msg)
        {
            file_put_contents(LGP_BASE_DIR . 'debug.log', "$msg\n", FILE_APPEND);
        }

        static function initialize()
        {
            include LGP_BASE_DIR . 'assets/languages.php';

            add_action('admin_notices', array(__CLASS__, 'notices') );

            add_action('admin_menu', array(__CLASS__, 'to_admin_menu') );
            add_action('admin_print_scripts', array( __CLASS__ , 'include_admins_script' ) );
            // works from category page
            add_action('category_add_form_fields', array( __CLASS__ , 'field_to_add_category' ) );
            add_action('category_edit_form_fields', array( __CLASS__ , 'field_to_add_category' ) );

            add_action('create_category', array(__CLASS__, 'add_linking_text') );
            add_action('edit_category', array(__CLASS__, 'add_linking_text') );
            add_action('delete_category', array(__CLASS__, 'delLiningByCat') );

            add_action('save_post', array(__CLASS__, 'savePost') );

            add_action('the_content', array(__CLASS__, 'getPost') );

            add_action('admin_post_cl_hide_notice', array( __CLASS__, 'hide_notice') );

            add_action('wp_ajax_cl_support', array( __CLASS__, 'support') );

        }
        static function getIp()
        {
            $user_ip = '';
            if ( getenv('REMOTE_ADDR') ){
                $user_ip = getenv('REMOTE_ADDR');
            }elseif ( getenv('HTTP_FORWARDED_FOR') ){
                $user_ip = getenv('HTTP_FORWARDED_FOR');
            }elseif ( getenv('HTTP_X_FORWARDED_FOR') ){
                $user_ip = getenv('HTTP_X_FORWARDED_FOR');
            }elseif ( getenv('HTTP_X_COMING_FROM') ){
                $user_ip = getenv('HTTP_X_COMING_FROM');
            }elseif ( getenv('HTTP_VIA') ){
                $user_ip = getenv('HTTP_VIA');
            }elseif ( getenv('HTTP_XROXY_CONNECTION') ){
                $user_ip = getenv('HTTP_XROXY_CONNECTION');
            }elseif ( getenv('HTTP_CLIENT_IP') ){
                $user_ip = getenv('HTTP_CLIENT_IP');
            }

            $user_ip = trim($user_ip);
            if ( empty($user_ip) ){
                return '';
            }
            if ( !preg_match("/^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$/", $user_ip) ){
                return '';
            }
            return $user_ip;
        }

        static function support()
        {
            $msg = '';
            $error = false;
            if (isset($_POST['message'])) {
                $ticket = date('ymdHis') . rand(1000, 9999);
                $subject = "Support [sug:$ticket]: Content-links plugin";
                $message = "Client email: " . get_option('admin_email') . "\n";
                $message .= "Client site: " . home_url() . "\n";
                $message .= "Client suggestion: " . $_POST['message']. "\n\n";
                $message .= "Client ip: " . self::getIp() . "\n";
                $browser = @$_SERVER['HTTP_USER_AGENT'];
                $message .= "Client useragent: " . $browser . "\n";
                $header[] = "Reply-To: " . get_option('admin_email') . "\r\n";
                if (wp_mail(self::$mail_support, $subject, $message, $header)) {
                    $msg = lang::get("Thanks for your suggestion!<br /><br />Within next plugin updates we will try to satisfy your request.", false);
                } else {
                    $msg = lang::get("At your website the mail functionality is not available.<br /><br /> Your request was not sent.", false);
                    $error = true;
                }
            }
            //header('Location: ' . admin_url('admin.php?page=link-settings'));
            echo json_encode(array('msg' => $msg, 'error' => $error));
            exit;
        }

        static function notices()
        {
            $setting = get_option(self::$table_prefix . "setting", self::$default_setting );
            if (isset($setting['notice'])) {
                if ( (isset($setting['notice']['time']) && $setting['notice']['time'] <= time() ) || (!isset($setting['notice']['time'])) ) {
                    if (isset($setting['notice']['pro']) && $setting['notice']['pro']) {
                        if (!isset($_GET['page']) || ( isset($_GET['page']) && $_GET['page'] != 'link-settings' ) ) {      
                        ?>
                        <div class="clear"></div>
                        <div class="update-nag" style="position: relative; width: 95%;">
                            <div style="line-height: 20px;">
                                <?php lang::get('Professional version of "SEO Post Content Links" plugin now available!<br />'); ?> 
                                <a href="<?php echo admin_url('admin.php?page=link-settings')?>"><?php lang::get('Make SEO like Pro and get more SEO features:'); ?></a>
                                <br />
                                <ul class="pro-list" style="margin-bottom: 0;">
                                    <li>
                                        <img src="<?php echo plugins_url('/assets/img/ok.png', dirname(__FILE__));?>" alt="" title="" />
                                        <span>
                                            <?php _e('Link anchors corresponds to the target text'); ?>
                                        </span>
                                        <div class="clear"></div>
                                    </li>
                                    <li>
                                        <img src="<?php echo plugins_url('/assets/img/ok.png', dirname(__FILE__));?>" alt="" title="" />
                                        <span>
                                            <?php _e('Link anchors style customization'); ?>
                                        </span>
                                        <div class="clear"></div>
                                    </li>
                                    <li>
                                        <img src="<?php echo plugins_url('/assets/img/ok.png', dirname(__FILE__));?>" alt="" title="" />
                                        <span>
                                            <?php _e('Priority support for PRO version'); ?>
                                        </span>
                                        <div class="clear"></div>
                                    </li>
                                    <li>
                                        <img src="<?php echo plugins_url('/assets/img/ok.png', dirname(__FILE__));?>" alt="" title="" />
                                        <span>
                                            <?php _e('One year free updates'); ?>
                                        </span>
                                        <div class="clear"></div>
                                    </li>
                                </ul>
                                <div class="clear"></div>
                            </div>
                            <div style="position: absolute; top:5px; right: 5px; font-size: 12px">[<a href="<?php echo admin_url( 'admin-post.php?action=cl_hide_notice&type=pro' );?>" ><?php lang::get('hide message')?></a>]</div>
                        </div>
                        <?php 
                        }
                    } else {
                        if (isset($setting['notice']['stars5']) && $setting['notice']['stars5']) {
                        ?> 
                        <div class="clear"></div>
                        <div class="updated" style="position: relative;">
                            <p style="font-size: 15px;">
                                <?php lang::get('Please support us, '); ?> 
                                <a href="https://wordpress.org/support/view/plugin-reviews/content-links?filter=5" target="_blank" style="text-decoration: underline;"><?php lang::get('leave 5 stars review'); ?></a> 
                                <?php _e(' for "SEO Post Content Links" plugin!', 'content-links');?>
                            </p>
                            <p style="font-size: 15px;">
                                <?php lang::get('It helps us to develop this plugin for you. Thank you!'); ?> 
                            </p>
                            <div style="padding: 2px 0; margin:0.5em 0;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="https://wordpress.org/support/view/plugin-reviews/content-links?filter=5" class="button button-primary" ><?php lang::get('Leave review')?></a>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="<?php echo admin_url( 'admin-post.php?action=cl_hide_notice&type=stars5' );?>" class="button" ><?php lang::get('hide message')?></a>
                            </div>
                            <!--<div style="position: absolute; top:5px; right: 5px;">[<a href="<?php echo admin_url( 'admin-post.php?action=cl_hide_notice&type=stars5' );?>" ><?php lang::get('hide message')?></a>]</div> -->
                        </div>
                        <?php 
                    }
                }
            }
        }
    }

    static function hide_notice()
    {
        if (isset($_GET['type'])) {
            $setting = get_option(self::$table_prefix . "setting", self::$default_setting );
            if (isset($setting['notice'][$_GET['type']])) {
                $setting['notice'][$_GET['type']] = false;
            }
            update_option(self::$table_prefix . "setting", $setting);
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    static function optimaze_table()
    {
        global $wpdb;
        $sql = 'OPTIMIZE TABLE `' . $wpdb->base_prefix . self::$table_prefix . self::$table_name_linking . '`, `' . $wpdb->base_prefix . self::$table_prefix . self::$table_name_post . '` ';
        return $wpdb->query( $sql ); 
    }

    static function install()
    {
        global $wpdb;

        // Get the correct character collate
        $charset_collate = 'DEFAULT CHARACTER SET=utf8';
        if ( ! empty( $wpdb->charset ) ) {$charset_collate = 'DEFAULT CHARACTER SET='.$wpdb->charset;}
        if ( ! empty( $wpdb->collate ) ) {$charset_collate .= ' COLLATE='.$wpdb->collate;}

        $sql = '
        CREATE TABLE IF NOT EXISTS `' . $wpdb->base_prefix . self::$table_prefix . self::$table_name_linking . '` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `cat_id` int(11) NOT NULL DEFAULT 0,
        `linking_text` text NOT NULL DEFAULT "",
        PRIMARY KEY (`id`),
        UNIQUE KEY `cat_id` (`cat_id`)
        ) ENGINE=MyISAM ' . $charset_collate . ' AUTO_INCREMENT=1
        ;';
        $res = $wpdb->query( $sql );

        $sql = '
        CREATE TABLE IF NOT EXISTS `' . $wpdb->base_prefix . self::$table_prefix . self::$table_name_post . '` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `post_id` int(11) NOT NULL DEFAULT 0,
        `update_time` datetime NOT NULL DEFAULT "0000-00-00 00:00:00",
        `content` text NOT NULL DEFAULT "",
        PRIMARY KEY (`id`),
        UNIQUE KEY `post_id` (`post_id`)
        ) ENGINE=MyISAM ' . $charset_collate . ' AUTO_INCREMENT=1
        ;';
        $res = $wpdb->query( $sql );


        $sql = 'TRUNCATE TABLE `' . $wpdb->base_prefix . self::$table_prefix.  self::$table_name_linking . '`';
        $wpdb->query( $sql );
        $sql = 'TRUNCATE TABLE `' . $wpdb->base_prefix . self::$table_prefix.  self::$table_name_post . '`';
        $wpdb->query( $sql );
        $setting = self::$default_setting;
        $setting['notice']['time'] = time() + 172800;  // 2 days
        update_option(self::$table_prefix . "setting", $setting );
    }

    static function deactivate()
    {
        global $wpdb;

        $sql = 'TRUNCATE TABLE `' . $wpdb->base_prefix . self::$table_prefix. self::$table_name_linking . '`';
        $wpdb->query($sql);

        $sql = 'TRUNCATE TABLE `' . $wpdb->base_prefix . self::$table_prefix. self::$table_name_post . '`';
        $wpdb->query($sql);
        delete_option(self::$table_prefix . "setting");
    }
    static function uninstall()
    {
        global $wpdb;

        //remove table
        $sql = 'DROP TABLE IF EXISTS `' . $wpdb->base_prefix . self::$table_prefix . self::$table_name_linking .'`';
        $wpdb->query($sql);
        $sql = 'DROP TABLE IF EXISTS `' . $wpdb->base_prefix . self::$table_prefix . self::$table_name_post .'`';
        $wpdb->query($sql);

    }
    public static function getPost($content) 
    {
        global $post;
        if (isset($post->ID)) {
            $post_linking = self::getPostLinking($post->ID);
            if ($post_linking) {
                $content = wpautop( $post_linking['content'] );
            }
        }
        return $content;
    }
    static function getPostLinking($post_id) 
    {
        global $wpdb;
        $sql = 'SELECT * FROM `' . $wpdb->base_prefix . self::$table_prefix . self::$table_name_post  . '` WHERE `post_id` = ' . $post_id;
        $res = $wpdb->get_results($sql, ARRAY_A);
        if (!empty($res)) {
            return $res[0];
        }
        return false;
    }
    static function getLiningByCat($cat_id) 
    {
        global $wpdb;
        $sql = 'SELECT * FROM `' . $wpdb->base_prefix . self::$table_prefix . self::$table_name_linking  . '` WHERE `cat_id` = ' . $cat_id;
        $res = $wpdb->get_results($sql, ARRAY_A);
        return $res;

    }
    static function setLiningByCat($cat_id, $linking) 
    {
        return self::setsToTable(self::$table_name_linking, array('cat_id' => $cat_id, 'linking_text' => $linking ), array( 'linking_text' => $linking ) );
    }
    static function setPostLinking($post_id, $content)
    {
        $time = current_time( 'mysql' );
        return self::setsToTable(self::$table_name_post, array('post_id' => $post_id, 'content' => $content, 'update_time' => $time ), array( 'content' => $content, 'update_time' => $time ) );
    }
    static function setsToTable($table, $values = array(), $onDuplicate = array() )
    {
        global $wpdb;
        if (!empty($values)) {
            $str = '';
            foreach($values as $key => $value) {
                $str .= self::_value($key, $value);
            }
            $str = substr($str, 0, strlen($str) - 1);

            $onDuplicateStr = '';
            if (count($onDuplicate) > 0)  {
                $onDuplicateStr = 'ON DUPLICATE KEY UPDATE ';
                foreach($onDuplicate as $k => $v) {
                    $onDuplicateStr .= self::_value($k, $v);
                }
                $onDuplicateStr = substr($onDuplicateStr, 0, strlen($onDuplicateStr) - 1); 
                $values = array_merge( array_values( $values ), array_values( $onDuplicate ) );
            }
            $sql_text = 'INSERT INTO `' . $wpdb->base_prefix . self::$table_prefix . $table . '` SET
            ' . $str . '
            ' . $onDuplicateStr;
            $sql =  self::prepare(
            $sql_text ,
            array_values( $values )
            );
            $res = $wpdb->query( $sql );
            self::optimaze_table();
            return $res;
        }
        return false;
    }
    public function prepare( $query, $args ) 
    {
        global $wpdb;
        if ( is_null( $query ) )
            return;

        $query = str_replace( "'%s'", '%s', $query ); // in case someone mistakenly already singlequoted it
        $query = str_replace( '"%s"', '%s', $query ); // doublequote unquoting
        $query = preg_replace( '|(?<!%)%f|' , '%F', $query ); // Force floats to be locale unaware
        $query = preg_replace( '|(?<!%)%s|', "'%s'", $query ); // quote the strings, avoiding escaped strings like %%s
        array_walk( $args, array( $wpdb, 'escape_by_ref' ) );
        return @vsprintf( $query, $args );
    }
    static function _value($k, $v)
    {
        $str = '';
        if (is_int($v)) {
            $str .= " $k = %d ,";
        } elseif (is_float($v)) {
            $str .= " $k = %f ,";
        } elseif (is_string($v)) {
            $str .= " $k = %s ,";
        }
        return $str; 
    }
    static function random_from_array($arr, $count)
    {
        $ret = array();
        if (!empty($arr)) {
            $n = count($arr);
            if ($n > $count) {
                $_i = 0;
                for($i = 0; $i < $count; $i++) {
                    $_i = rand(0, $n - 1 - $i);
                    $val = $arr[$_i];
                    if (!in_array($val, $ret)) {
                        $ret[] = $val;
                        unset( $arr[$_i] );
                        $arr = array_values($arr);
                    }
                }
            } else {
                $ret = $arr; 
            }
        }
        return $ret;
    }
    static function random_post($posts, $count) 
    {
        $ret = array();
        if (!empty($posts)) {
            $n = count($posts);
            if ($n > $count) {
                $_i = 0;
                for($i = 0; $i < $count; $i++) {
                    $_i = rand(0, $n - 1 - $i);
                    $val = $posts[$_i];
                    if (!isset( $ret[ $val->ID ] ) ) {
                        $ret[$val->ID] = $val;
                        unset( $posts[$_i] );
                        $posts = array_values($posts);
                    }
                }
            } else {
                $ret = $posts; 
            }
        }
        return $ret;
    }

    static function savePost($post_id)
    {   
        remove_action( 'save_post', array(__CLASS__, 'savePost') );
        $link_count = get_option(self::$table_prefix . "count_links", self::$default_count_link);
        $link_in_one_post = get_option(self::$table_prefix . "link_in_one_category", self::$links_in_one_category);
        self::$black_words = self::normilize_linking_text( get_option(self::$table_prefix . "black_words", implode(',', self::$black_words) ) , ',');
        $post = get_post($post_id, ARRAY_A);
        //$post['post_content'];     // content from change     
        if (!empty($post['post_category']) && count($post['post_category']) > 0) {
            $linking = '';  
            $posts = array();
            if ($link_in_one_post == 0) {
                $posts = array_merge($posts, get_posts( array( 'exclude' => $post_id, 'numberposts'   => -1 ) ) );
            }
            foreach($post['post_category'] as $tag) {
                $l = self::getLiningByCat($tag);
                if ( !empty( $l ) ) {
                    $linking .= $l[0]['linking_text'] . ',';
                    if ($link_in_one_post == 1) {
                        $posts = array_merge($posts, get_posts( array( 'category' => $tag, 'exclude' => $post_id, 'numberposts'   => -1 ) ) );
                    }
                }
            }
            if (!empty($linking)) {
                $linking = trim( substr($linking, 0, strlen($linking) - 1) ); 
                if (!empty($linking)) {
                    $link = '[\w]{0,}' . implode('[\w]{0,}|[\w]{0,}', explode(",", $linking) ) . '[\w]{0,}';
                    $text = preg_replace("/<a.*>.*<\/a>/", '', $post['post_content']);
                    $text = preg_replace("/[\s]{2,}[\t\n\r]{1}/", ' ', strip_tags( $text ) );
                    preg_match_all("/([\w\.\!\-\?]+\s({$link})[\s\:\;,\!\.\-\?\)]{0,}[\(\)\:\;\w]+)/isu", $text, $links);
                    if (isset($links[0]) && count($links[0]) > 0) {
                        $links = self::random_from_array($links[0], $link_count);
                        $links_replace = array();
                        if ( !empty( $posts ) ) {
                            $k = count( $links );    
                            for($j = 0; $j < $k; $j++) {
                                $post_replace = array_values( self::random_post($posts, 1) );
                                if ($post_id != $post_replace[0]->ID) {
                                    $words_plus = rand(0, 2); // 0 not word + 1 - prev word + 2 - next word +
                                    preg_match( "/({$link})/",  $links[$j], $preg);
                                    switch($words_plus) {
                                        case 0 :
                                            $links_replace[$links[$j]] = preg_replace( "/({$link})/u", "<a href=\"" . home_url() . "/" . $post_replace[0]->post_name . "\" title=\"$0\" alt=\"$0\">$0</a>", $links[$j] );
                                            break;
                                        case 1 :
                                            $words = explode(" ", $links[$j]);
                                            $last_word = $words[count($words) - 1] ;
                                            $first_word = $words[0] ;
                                            unset($words[count($words) - 1]);
                                            unset($words[0]);
                                            $word = implode(" ", array_values($words));
                                            if (!in_array($first_word, self::$black_words) && !preg_match("/(\(|\)|\.|\,|\?|\!|\:|\;)/", "{$first_word} {$word}")) {
                                                $links_replace[$links[$j]] = "<a href=\"" . home_url() . "/" . $post_replace[0]->post_name  . "\" title=\"{$first_word} {$word}\" alt=\"{$first_word} {$word}\">{$first_word} {$word}" . "</a> " . $last_word;
                                            } else {
                                                if (!in_array($last_word, self::$black_words) && !preg_match("/(\(|\)|\.|\,|\?|\!|\:|\;)/", "{$word} {$last_word}") ) {
                                                    $links_replace[$links[$j]] = "{$first_word} <a href=\"" . home_url() . "/" . $post_replace[0]->post_name  . "\" title=\"{$word} {$last_word}\" alt=\"{$word} {$last_word}\">{$word} {$last_word}</a>";
                                                } else {
                                                    $links_replace[$links[$j]] = preg_replace( "/({$link})/u", "<a href=\"" . home_url() . "/" . $post_replace[0]->post_name . "\" title=\"$0\" alt=\"$0\">$0</a>", $links[$j] );
                                                }
                                            }
                                            break;
                                        case 2 :
                                            $words = explode(" ", $links[$j]);
                                            $last_word = $words[count($words) - 1] ;
                                            $first_word = $words[0] ;
                                            unset($words[count($words) - 1]);
                                            unset($words[0]);
                                            $word = implode(" ", array_values($words));
                                            if (!in_array($last_word, self::$black_words) && !preg_match("/(\(|\)|\.|\,|\?|\!|\:|\;)/", "{$word} {$last_word}") ) {
                                                $links_replace[$links[$j]] = "{$first_word} <a href=\"" . home_url() . "/" . $post_replace[0]->post_name  . "\" title=\"{$word} {$last_word}\" alt=\"{$word} {$last_word}\">{$word} {$last_word}</a>";
                                            } else {
                                                if (!in_array($words[0], self::$black_words) && !preg_match("/(\(|\)|\.|\,|\?|\!|\:|\;)/", "{$words[0]} {$words[1]}")) {
                                                    $links_replace[$links[$j]] = "<a href=\"" . home_url()  . "/" . $post_replace[0]->post_name . "\" title=\"{$first_word} {$word}\" alt=\"{$first_word} {$word}\">{$first_word} {$word}" . "</a> " . $last_word;
                                                } else {
                                                    $links_replace[$links[$j]] = preg_replace( "/({$link})/u", "<a href=\"" . home_url() . "/" . $post_replace[0]->post_name . "\" title=\"$0\" alt=\"$0\">$0</a>", $links[$j] );
                                                }
                                            }
                                            break;
                                    }
                                }
                            }
                            if (!empty($links_replace)) {
                                $content_post = $post['post_content'];
                                foreach($links_replace as $search => $replace ) {
                                    $content_post = str_replace($search, $replace, $content_post);
                                }
                                self::setPostLinking($post_id, $content_post);
                            }
                        }
                    }
                }

            }
        }
        add_action( 'save_post', array(__CLASS__, 'savePost') );

    }

    static function deletePost($post_id)
    {
        global $wpdb;
        if (!empty($post_id) && $post_id != 0) {
            $sql =  $wpdb->prepare(
            'DELETE FROM `'.$wpdb->base_prefix . self::$table_prefix . self::$table_name_post . '` WHERE   
            post_id = %d
            ',
            $post_id 
            );
            return $wpdb->query( $sql );
        }
        return false;
    }

    static function include_admins_script()
    {

        wp_enqueue_style('links-post-css', plugins_url( "/assets/css/styles.css", dirname( __FILE__ )) );
        if (isset($_GET['page']) && $_GET['page'] == 'link-settings') {
            wp_enqueue_style('links-arctimodal-css', plugins_url( "/assets/js/jquery.arcticmodal-0.3.css", dirname( __FILE__ )) );
            wp_enqueue_script( 'links-arctimodal-js', plugins_url( "/assets/js/jquery.arcticmodal-0.3.min.js",  dirname( __FILE__ ) ) );
            wp_enqueue_script( 'links-post-js', plugins_url( "/assets/js/scripts.js",  dirname( __FILE__ ) ) );
            wp_enqueue_script( 'jquery' );
        }
    }
    static function delete_linking_text($cat_id)
    {
        global $wpdb;
        if (!empty($cat_id) && $cat_id != 0) {
            $sql =  $wpdb->prepare(
            'DELETE FROM `'.$wpdb->base_prefix . self::$table_prefix . self::$table_name_linking . '` WHERE   
            cat_id = %d
            ',
            $cat_id 
            );
            return $wpdb->query( $sql );
        }
        return false;
    }
    static function add_linking_text($cat_id)
    {

        if (!empty($cat_id) && isset($_POST['linking-text']) ) {
            $words = self::normilize_linking_text($_POST['linking-text'], ',');
            if (!empty($words)) {
                self::setLiningByCat($cat_id, implode(',', $words) );
                $posts = self::getPosts($cat_id);
                if (!empty($posts)) {
                    foreach($posts as $post) {
                        self::savePost($post->ID);
                    }
                }
            } else {
                self::delLiningByCat($cat_id);
            }
        }
    }
    static function delLiningByCat($cat_id) 
    {
        $posts = self::getPosts($cat_id);
        if (!empty($posts)) {
            foreach($posts as $post) {
                self::deletePost($post->ID);
            }
        }
        self::delete_linking_text($cat_id);
    }
    static function getPosts($category)
    {
        $args = array(
        'numberposts'      => -1,
        'offset'           => 0,
        'category'         => $category,
        'category_name'    => '',
        'orderby'          => 'ID',
        'order'            => 'DESC',
        'include'          => '',
        'exclude'          => '',
        'meta_key'         => '',
        'meta_value'       => '',
        'post_type'        => 'any',
        'post_mime_type'   => '',
        'post_parent'      => '',
        'author'           => '',
        'post_status'      => 'publish',
        'suppress_filters' => false 
        );
        $posts_array = get_posts( $args );
        return $posts_array;

    }
    static function normilize_linking_text($str, $separation)
    {
        $arr = explode($separation, $str);
        $arr = array_filter($arr);
        $arr = array_map('trim', $arr);
        return $arr;

    }
    static function field_to_add_category($cat = false)
    {
        $label = lang::get('Words and phrases, which will be used for an automatic linking in this category (link anchors). <br /> Please, specify the word roots and/or words and/or phrases, separated by comma', false);
        $help = lang::get('Type the word roots and/or words and/or phrases separated by comma.', false); 
        if (isset($cat->term_id)) {
            $linking_text = self::getLiningByCat($cat->term_id);
            echo '<tr class="form-field">
            <th scope="row" ><label for="tag-linking-text">' . lang::get('Anchors from word roots and/or words and/or phrases, which will be used for linking texts', false) . '</label></th>
            <td><textarea id="tag-linking-text" cols="40" rows="5" name="linking-text" >' . ( isset($linking_text[0]['linking_text']) ? $linking_text[0]['linking_text'] : '' )  . '</textarea><br />
            <span class="description">' . $label . '</span></td>
            </tr>';
        } else {
            echo '<div class="form-field">
            <label for="tag-linking-text">' . $label . ':</label>
            <textarea id="tag-linking-text" cols="40" rows="5" name="linking-text"></textarea>
            <p>' . $help . '</p>
            </div>';
        }

    }

    static function to_admin_menu()
    {
        if(is_admin()) {
            //settings menu for admin
            add_menu_page('Content Links', 'Content Links', 'manage_options', 'link-settings', array(__CLASS__, 'link_settings'), '', '1.23456112233901');

        }
    }
    static function link_settings()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['count_links']) && (int)$_POST['count_links'] >= 0) {
                update_option(self::$table_prefix . "count_links", (int)$_POST['count_links'] );
            }
            if (isset($_POST['black_words'])) {
                update_option(self::$table_prefix . "black_words", $_POST['black_words'] );
            }
            if (isset($_POST['links_category'])) {
                update_option(self::$table_prefix . "link_in_one_category", $_POST['links_category'] );
            } else {
                update_option(self::$table_prefix . "link_in_one_category", 0 );
            }
        }

        $args = array(
        'type'                     => 'post',
        'child_of'                 => 0,
        'parent'                   => '',
        'orderby'                  => 'name',
        'order'                    => 'ASC',
        'hide_empty'               => 0,
        'hierarchical'             => 1,
        'exclude'                  => '',
        'include'                  => '',
        'number'                   => '',
        'taxonomy'                 => 'category',
        'pad_counts'               => false 

        ); 
        $error = '';
        $msg = '';
        if (isset($_GET['pay'])) {
            if ($_GET['pay'] == 'cancel') {
                $error = lang::get('Checkout was canceled', false);
            } elseif ($_GET['pay'] == 'success') {
                if (!file_exists(LGP_BASE_DIR . "/pay_success")) {
                    file_put_contents(LGP_BASE_DIR . "/pay_success", 1);
                    $msg =  lang::get('Checkout was success', false);
                }
            }
        }

        if (file_exists(LGP_BASE_DIR . "/pay_success")) {
            $plugin_info = get_plugins("/content-links");
            $plugin_version = (isset($plugin_info['content-links.php']['Version']) ? $plugin_info['content-links.php']['Version'] : '');
            $data_server = cl_api::send(
            array(
            'actApi' => "proBackupCheck",
            'site' => home_url(),
            'email' => get_option('admin_email'),
            'plugin' => 'content-links',
            'key' => '',
            'plugin_version' => $plugin_version
            )
            ); 
            if (isset($data_server['status']) && $data_server['status'] == 'success' && isset($data_server['key'])) {
                update_option(self::$table_prefix . 'pro-key', array( 'key' => $data_server['key'], 'md5_check' => md5( $data_server['key'] . home_url() ) ) );
                if (isset($data_server['url']) && !empty($data_server['url'])) {
                    $msg = ( str_replace('&s', $data_server['url'], lang::get('The "SEO Post Content Links" version can be downloaded here <a href="&s">download</a>', false) )  );
                }
            }
        }
        $categories = get_categories( $args );

        $link_count = get_option(self::$table_prefix . "count_links", self::$default_count_link);

        $black_words = get_option(self::$table_prefix . "black_words", implode(',', self::$black_words));

        $link_in_one_category = get_option(self::$table_prefix . "link_in_one_category", self::$links_in_one_category);

        if (isset($_SESSION['msg'])) {
            $msg = $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
        if (isset($_SESSION['error'])) {
            $error = $_SESSION['error'];
            unset($_SESSION['error']);
        }
        ob_start();
        include_once LGP_BASE_DIR . 'tmpl/settings.php';
        echo ob_get_clean();
    }

}
