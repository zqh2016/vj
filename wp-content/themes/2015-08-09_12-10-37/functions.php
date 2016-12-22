<?php 
include_once('inc/options.php');
/* -------------------------------------------------- *
 * WordPress 后台禁用Google Open Sans字体，加速网站
/* ------------------------------------------------- */
// Remove Open Sans that WP adds from frontend
if (!function_exists('remove_wp_open_sans')) :
function remove_wp_open_sans() {
wp_deregister_style( 'open-sans' );
wp_register_style( 'open-sans', false );
}
// 前台删除Google字体CSS
add_action('wp_enqueue_scripts', 'remove_wp_open_sans');
// 后台删除Google字体CSS
add_action('admin_enqueue_scripts', 'remove_wp_open_sans');
endif;
//Gravatar头像缓存
function mytheme_get_avatar( $avatar ){
$avatar = preg_replace( "/http:\/\/(www|\d).gravatar.com/","http://0.bsdev.cn/",$avatar );
return $avatar;
}
add_filter( 'get_avatar', 'mytheme_get_avatar' );

/** 
 * 增强编辑器开始
 */
function add_editor_buttons($buttons) {
$buttons[] = 'fontselect';
$buttons[] = 'fontsizeselect';
$buttons[] = 'cleanup';
$buttons[] = 'backcolor';
$buttons[] = 'underline';
$buttons[] = 'hr';
$buttons[] = 'del';
$buttons[] = 'sub';
$buttons[] = 'sup';
$buttons[] = 'cut';
$buttons[] = 'undo';
$buttons[] = 'image';
$buttons[] = 'copy';
$buttons[] = 'anchor';
$buttons[] = 'paste';
$buttons[] = 'cleanup';
$buttons[] = 'wp_page';
$buttons[] = 'newdocument';
return $buttons;
}
add_filter("mce_buttons_3", "add_editor_buttons");
//增强编辑器结束

/**
 * 阻止站内文章Pingback 
 */ 
function tin_noself_ping( &$links ) {
  $home = get_option( 'home' );
  foreach ( $links as $l => $link )
  if ( 0 === strpos( $link, $home ) )
  unset($links[$l]);
}
add_action('pre_ping','tin_noself_ping');

/** 
 * 移除版本号 
 */
function themepark_remove_cssjs_ver( $src ) {
    if( strpos( $src, 'ver='. get_bloginfo( 'version' ) ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
add_filter( 'style_loader_src', 'themepark_remove_cssjs_ver', 999 );
add_filter( 'script_loader_src', 'themepark_remove_cssjs_ver', 999 );

/** 
 * 去除头部冗余代码 
 */
function remove_open_sans(){
	wp_deregister_style( 'open-sans' );
	wp_register_style( 'open-sans', false );
	wp_enqueue_style('open-sans','');
}
//去除自带js
wp_deregister_script( 'l10n' ); 
add_action( 'init', 'remove_open_sans' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'start_post_rel_link',10, 0 );
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'index_rel_link' );
remove_action( 'wp_head', 'adjacent_posts_rel_link' );
remove_action( 'wp_head', 'rel_canonical' ); 
remove_action( 'pre_post_update', 'wp_save_post_revision' );

/** 
 * 首页排除某些分类文章显视 
 */
function exclude_category_home($query) {
    if ($query->is_home) {
        $query->set('cat', 'ID'); // 注意根据自己的需要，修改分类ID，比如你想排除分类-4 和 -23，
        $query->set('ignore_sticky_posts', '1'); // 如果你不希望在顶部显示置顶文章 
        $query->set('orderby', 'date'); // 老文章在上面 
        $query->set('order', 'DESC'); // 新文章跑到最下面 
    }
    return $query;
} // 最简单的方法就是通过 pre_get_posts  钩子来改变主查询 
add_filter('pre_get_posts', 'exclude_category_home');

/** 
 * 过滤掉菜单样式 
 */
add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);
add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);
function my_css_attributes_filter($var) {
// 保留选择器
return is_array($var) ? array_intersect($var, array('current-menu-item')) : '';
}

/** 
 * 注册菜单 
 */
if (function_exists('register_nav_menus')) {
    register_nav_menus(array(
        'primary' => __('导航菜单', 'janestyle') ,
    ));
};

/**
 * 文章浏览次数 
 */
function get_post_views($post_id){
    $count_key = 'views';   
    $count = get_post_meta($post_id, $count_key, true);    
    if ($count == '') {   
        delete_post_meta($post_id, $count_key);   
        add_post_meta($post_id, $count_key, '0');   
        $count = '0';   
    }    
    echo number_format_i18n($count);     
}    
function set_post_views () {     
    global $post;     
    $post_id = $post -> ID;   
    $count_key = 'views';   
    $count = get_post_meta($post_id, $count_key, true);     
    if (is_single() || is_page()) {     
        if ($count == '') {   
            delete_post_meta($post_id, $count_key);   
            add_post_meta($post_id, $count_key, '0');   
        } else {   
            update_post_meta($post_id, $count_key, $count + 1);   
        }     
    }    
}   
add_action('get_header', 'set_post_views');

/**
 * 热门点击文章
 * 一个月内”文章点击排名:<?php get_mostviewed($limit = 6,30);?>
 * 文章点击排名:<?php get_mostviewed($limit = 25,0);?>
 */
function get_mostviewed($limit = 5, $limitdays = 30, $before = '<li>', $after = '</li>') {
    global $wpdb;
    if ($limitdays == 0 || $limitdays == "") $where = "";
    else $where.= " AND post_date > '" . date('Y-m-d', strtotime("-$limitdays days")) . "'";
    $most_viewed = $wpdb->get_results("SELECT DISTINCT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '" . current_time('mysql') . "' " . $where . " AND post_type <> 'page' AND post_status = 'publish' AND meta_key = 'views' AND post_password = '' ORDER BY views DESC LIMIT $limit");
    if ($most_viewed) {
        foreach ($most_viewed as $post) {
            global $post;
            $post_ID = $post->ID;
            $views = (int)get_post_meta($post_ID, 'views', true);
            //$post_views = (int)get_post_meta($post_ID, 'views', true);
            $post_title = htmlspecialchars(stripslashes($post->post_title));
            $permalink = get_permalink($post->ID);
            echo $before . "<a href=\"$permalink\">$post_title </a>" . $after;
        }
    }
}

/** 
 * WordPress 调用上周的文章 
 */
function last_week_posts() { 
	$thisweek = date('W'); //获取本周数
	if ($thisweek != 1) { //如果本周不是第一周，减 1 就是上周
		$lastweek = $thisweek - 1; 
	}else{ // 如果本周是第一周，上周就是去年的 52 周
		$lastweek = 52;
	}
	$year = date('Y'); // 获取当前年份
	if ($lastweek != 52) { // 如果本周不是一年最后一周（52周），年份就是今年
		$year = date('Y');
	}else{ // 如果是最后一周，年份减 1 就是去年
		$year = date('Y') -1; 
	}
	$the_query = new WP_Query( 'year=' . $year . '&w=' . $lastweek );
	if ( $the_query->have_posts() ) : 
		while ( $the_query->have_posts() ) : $the_query->the_post(); 
	echo '<li><a href="'; echo the_permalink(). '">';echo the_title().'</a></li>';
    endwhile; 
    wp_reset_postdata(); 
    else:  
	echo '<p>'. _e( '抱歉，没有找到符合条件的文章' ) .'</p>';
	endif;
}
add_shortcode('lastweek', 'last_week_posts'); // 添加简码

/** 
 * WordPress自动裁图功能 
 */
if (function_exists('add_theme_support')) add_theme_support('post-thumbnails'); //添加特色缩略图支持
function post_thumbnail( $width = 100,$height = 80 ){
    global $post;
    if( has_post_thumbnail() ){    //如果有缩略图，则显示缩略图
        $timthumb_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
        $post_timthumb = '<img src="'.get_bloginfo("template_url").'/timthumb.php?src='.$timthumb_src[0].'&amp;h='.$height.'&amp;w='.$width.'&amp;q=90&amp;zc=1&amp;ct=1&amp;a=t" alt="'.$post->post_title.'" class="thumb" />';
        echo $post_timthumb;
    } else {
        $post_timthumb = '';
        ob_start();
        ob_end_clean();
        //获取日志中第一张图片
        $output = preg_match('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $index_matches);    
        $first_img_src = $index_matches [1];    //获取该图片 src
        if( !empty($first_img_src) ){    //如果日志中有图片
            $path_parts = pathinfo($first_img_src);    //获取图片 src 信息
            $first_img_name = $path_parts["basename"];    //获取图片名
            $first_img_pic = get_bloginfo('template_url'). '/cache/'.$first_img_name;    //文件所在地址
            $first_img_file = ABSPATH. 'cache/'.$first_img_name;    //保存地址
            $expired = 604800;    //过期时间
            if ( !is_file($first_img_file) || (time() - filemtime($first_img_file)) > $expired ){
                copy($first_img_src, $first_img_file);    //远程获取图片保存于本地
                //保存时用原图显示
                $post_timthumb = '<img src="'.$first_img_src.'" alt="'.$post->post_title.'" class="thumb" />';    
            }
            $post_timthumb = '<img src="'.get_bloginfo("template_url").'/timthumb.php?src='.$first_img_pic.'&amp;h='.$height.'&amp;w='.$width.'&amp;q=90&amp;zc=1&amp;ct=1&amp;a=t" alt="'.$post->post_title.'" class="thumb" />';
        } else {    //如果日志中没有图片，则显示默认
            $post_timthumb = '<img src="'.get_bloginfo("template_url").'/images/default_thumb.gif" alt="'.$post->post_title.'" class="thumb" />';
        }
        echo $post_timthumb;
    }
}
// 列表图片地址
function post_thumbnail_src(){
    global $post;
  if( $values = get_post_custom_values("thumb") ) { //输出自定义域图片地址
    $values = get_post_custom_values("thumb");
    $post_thumbnail_src = $values [0];
  } elseif( has_post_thumbnail() ){    //如果有特色缩略图，则输出缩略图地址
        $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
    $post_thumbnail_src = $thumbnail_src [0];
    } else {
    $post_thumbnail_src = '';
    ob_start();
    ob_end_clean();
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
    $post_thumbnail_src = $matches [1] [0];   //获取该图片 src
    if(empty($post_thumbnail_src)){ //如果日志中没有图片，则显示随机图片
      $random = mt_rand(1, 10);
      echo get_bloginfo('template_url');
      echo '/images/pic/'.$random.'.jpg';
      //如果日志中没有图片，则显示默认图片
      //echo '/images/default_thumb.jpg';
    }
  };
  echo $post_thumbnail_src;
}

/**
 * 获取WordPress所有分类名字和ID
 */
function show_category(){
    global $wpdb;
    $request = "SELECT $wpdb->terms.term_id, name FROM $wpdb->terms ";
    $request .= " LEFT JOIN $wpdb->term_taxonomy ON $wpdb->term_taxonomy.term_id = $wpdb->terms.term_id ";
    $request .= " WHERE $wpdb->term_taxonomy.taxonomy = 'category' ";
    $request .= " ORDER BY term_id asc";
    $categorys = $wpdb->get_results($request);
    foreach ($categorys as $category) { //调用菜单
       /* $output = '<li><a href="'. get_category_link( $category->term_id ).'">'. $category->name."(<em>".$category->term_id.'</em>)</a></li>'; */
        $output = '<li><a href="'. get_category_link( $category->term_id ).'">'. $category->name.'</a></li>';
        echo $output;
    }
}

/**
 * 页码
 */
if ( ! function_exists( 'jane_paging_nav' ) ) :
function jane_paging_nav($range = 6){
	global $paged, $wp_query;
	echo "<div class='pagination'>";
	if ( !$max_page ) {$max_page = $wp_query->max_num_pages;}
	if($max_page > 1){if(!$paged){$paged = 1;}
	if($paged != 1){echo "<a href='" . get_pagenum_link(1) . "' class='extend' title='跳转到首页'>首页</a>";}
	if($paged>1) echo '<a href="' . get_pagenum_link($paged-1) .'" class="prev-page" title="上一页">&laquo;</a>';
    if($max_page > $range){
		if($paged < $range){for($i = 1; $i <= ($range + 1); $i++){echo "<a href='" . get_pagenum_link($i) ."'";
		if($i==$paged)echo " class='current'";echo ">$i</a>";}}
    elseif($paged >= ($max_page - ceil(($range/2)))){
		for($i = $max_page - $range; $i <= $max_page; $i++){echo "<a href='" . get_pagenum_link($i) ."'";
		if($i==$paged)echo " class='current'";echo ">$i</a>";}}
	elseif($paged >= $range && $paged < ($max_page - ceil(($range/2)))){
		for($i = ($paged - ceil($range/2)); $i <= ($paged + ceil(($range/2))); $i++){echo "<a href='" . get_pagenum_link($i) ."'";if($i==$paged) echo " class='current'";echo ">$i</a>";}}}
    else{for($i = 1; $i <= $max_page; $i++){echo "<a href='" . get_pagenum_link($i) ."'";
    if($i==$paged)echo " class='current'";echo ">$i</a>";}}
	if($paged<$max_page) 
	echo '<a href="' . get_pagenum_link($paged+1) .'" class="next-page" title="下一页">&raquo;</a>';
    if($paged != $max_page){echo "<a href='" . get_pagenum_link($max_page) . "' class='extend' title='跳转到最后一页'>尾页</a>";
	}
	}
	echo "</div>";
}; endif;

/**
 * 面包导航
 */
function jane_breadcrumbs() {
    $delimiter = '&raquo;'; // 分隔符
    $before = '<span class="current">'; // 在当前链接前插入
    $after = '</span>'; // 在当前链接后插入
    if (!is_home() && !is_front_page() || is_paged()) {
        echo '<div class="postion">' . __('<i class="fa fa-home"></i>', 'jane');
        global $post;
        $homeLink = home_url();
        echo ' <a itemprop="breadcrumb" href="' . $homeLink . '">' . __('首页', 'jane') . '</a> ' . $delimiter . ' ';
        if (is_category()) { // 分类 存档
            global $wp_query;
            $cat_obj = $wp_query->get_queried_object();
            $thisCat = $cat_obj->term_id;
            $thisCat = get_category($thisCat);
            $parentCat = get_category($thisCat->parent);
            if ($thisCat->parent != 0) {
                $cat_code = get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' ');
                echo $cat_code = str_replace('<a', '<a itemprop="breadcrumb"', $cat_code);
            }
            echo $before . '' . single_cat_title('', false) . '' . $after;
        } elseif (is_day()) { // 天 存档
            echo '<a itemprop="breadcrumb" href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo '<a itemprop="breadcrumb"  href="' . get_month_link(get_the_time('Y') , get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
            echo $before . get_the_time('d') . $after;
        } elseif (is_month()) { // 月 存档
            echo '<a itemprop="breadcrumb" href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo $before . get_the_time('F') . $after;
        } elseif (is_year()) { // 年 存档
            echo $before . get_the_time('Y') . $after;
        } elseif (is_single() && !is_attachment()) { // 文章
            if (get_post_type() != 'post') { // 自定义文章类型
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                echo '<a itemprop="breadcrumb" href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
                echo $before . '正文' . $after;
            } else { // 文章 post
                $cat = get_the_category();
                $cat = $cat[0];
                $cat_code = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
                echo $cat_code = str_replace('<a', '<a itemprop="breadcrumb"', $cat_code);
                echo $before . '正文' . $after;
            }
        } elseif (!is_single() && !is_page() && get_post_type() != 'post') {
            $post_type = get_post_type_object(get_post_type());
            echo $before . $post_type->labels->singular_name . $after;
        } elseif (is_attachment()) { // 附件
            $parent = get_post($post->post_parent);
            $cat = get_the_category($parent->ID);
            $cat = $cat[0];
            echo '<a itemprop="breadcrumb" href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
            echo $before . '正文' . $after;
        } elseif (is_page() && !$post->post_parent) { // 页面
            echo $before . '正文' . $after;
        } elseif (is_page() && $post->post_parent) { // 父级页面
            $parent_id = $post->post_parent;
            $breadcrumbs = array();
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = '<a itemprop="breadcrumb" href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                $parent_id = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
            echo $before . '正文' . $after;
        } elseif (is_search()) { // 搜索结果
            echo $before;
            printf(__('搜索: %s', 'jane') , get_search_query());
            echo $after;
        } elseif (is_tag()) { //标签 存档
            echo $before;
            printf(__('标签： %s', 'jane') , single_tag_title('', false));
            echo $after;
        } elseif (is_author()) { // 作者存档
            global $author;
            $userdata = get_userdata($author);
            echo $before;
            printf(__('作者: %s', 'jane') , $userdata->display_name);
            echo $after;
        } elseif (is_404()) { // 404 页面
            echo $before;
            _e('Not Found', 'jane');
            echo $after;
        }
        if (get_query_var('paged')) { // 分页
            //if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() )
            //echo sprintf( __( '( Page %s )', 'jane' ), get_query_var('paged') );
        }
        echo '</div>';
    }
}

/* 
 * 禁止冒充博主评论
 */
if(!is_user_logged_in()){
    add_filter( 'preprocess_comment', 'admincheck' );
}
function admincheck($incoming_comment) {
    $isSpam = 0;
    $admin_id = trim($incoming_comment['comment_author']);
    $admin_email = trim($incoming_comment['comment_author_email']);    
    // 将以下代码中的 JV 改成博主昵称， 将 inlojv@qq.com 改成博主Email
    if ($admin_id == 'jv' || $admin_id == 'JV' || $admin_id == 'inlojv'|| $admin_id == 'INLOJV' || $admin_email == 'inlojv@qq.com')
        $isSpam = 1;
    if(!$isSpam)
        return $incoming_comment;
    wp_die('李鬼死远点！');
}

/** 
 * 评论列表 
 */
function mytheme_comment($comment, $args, $depth) {
$GLOBALS['comment'] = $comment;
echo ('<li class="comment-body"><div class="comment-author">');
echo get_avatar( $comment, $args['avatar_size'] );
echo ('</div>');
if ($comment->comment_approved == '0') : 
echo ('<em>' . _e('Your comment is awaiting moderation.') . '</em><br/>');
endif;
echo ('<div class="comment-data">');
printf( __('<cite class="fn yh">%s</cite>'), get_comment_author_link());
edit_comment_link(__('(Edit)'),' ','');
echo comment_text(); 
echo ('<div class="reply"><span>');
printf(__('%1$s at %2$s'), get_comment_date(), get_comment_time()); /* translators: 1: date, 2: time */
echo ('</span>');
echo comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth'])));
echo ('</div></div>');
}

/**
 * 评论表单
 */
function jane_fields($fields) {
$fields =  array(
	'author' => '<p class="comment-form-author">' . '<label for="author">' . __( 'Name<span class="required">*</span>' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .
	'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>',
	'email' => '<p class="comment-form-email"><label for="email">' . __( 'Email<span class="required">*</span>' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .
	'<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>',
	'url' => '<p class="comment-form-url"><label for="url">' . __( 'Website' ) . '</label>' .
	'<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30"/></p>',
	);
	return $fields;
}
add_filter('comment_form_default_fields','jane_fields');
function add_my_tips() {
	echo '欢迎踊跃发言！';
}
// 在默认字段（前面说的姓名、邮箱和网址）的下面添加字段
add_filter('comment_form_after_fields', 'add_my_tips');
// 在已登录下面添加字段（因为用户登录后，是没有默认上面三个字段的），所以要使用这个钩子插入内容
add_filter('comment_form_logged_in_after', 'add_my_tips');
?>