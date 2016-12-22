<?php
/*
Plugin Name: zqh lib
Plugin URI: 
Description: zqh lib test.
Version: 3.3.0
Author: zhouqinghai
Author URI: 
Text Domain: 
Domain Path: 
License: GPL2
 */

/* 注册激活插件时要调用的函数 */ 
register_activation_hook( __FILE__, 'display_zqh_install');   

/* 注册停用插件时要调用的函数 */ 
register_deactivation_hook( __FILE__, 'display_zqh_remove' );  

function display_zqh_install() {  
    /* 在数据库的 wp_options 表中添加一条记录，第二个参数为默认值 */ 
    //add_option("display_copyright_text", "<p style='color:red'>本站点所有文章均为原创，转载请注明出处！</p>", '', 'yes');  
}

function display_zqh_remove() {  
    /* 删除 wp_options 表中的对应记录 */ 
    //delete_option('display_copyright_text');  
}

if( is_admin() ) {
    /*  利用 admin_menu 钩子，添加菜单 */
    add_action('admin_menu', 'display_zqh_menu');
}

function display_zqh_menu() {
    /* add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);  */
    /* 页名称，菜单名称，访问级别，菜单别名，点击该菜单时的回调函数（用以显示设置页面） */
    add_options_page('Set Zqh', 'Zqh Lib', 'administrator','display_zqh', 'display_zqh_html_page');
}

function display_zqh_html_page() {
    ?>
    <div>  
        <h2>Set Zqh</h2>  
        <form method="post" action="options.php">  
            <?php /* 下面这行代码用来保存表单中内容到数据库 */ ?>  
            <?php wp_nonce_field('update-options'); ?>  
 
            <p>  
                <textarea  
                    name="display_zqh_text" 
                    id="display_zqh_text" 
                    cols="100" 
                    rows="20"><?php echo get_option('display_zqh_text'); ?></textarea>  
            </p>  
 
            <p>  
                <input type="hidden" name="action" value="update" />  
                <input type="hidden" name="page_options" value="display_zqh_text" />  
 
                <input type="submit" value="Save" class="button-primary" />  
            </p>  
        </form>  
    </div>  
<?php  
}  

add_filter( 'the_content',  'display_zqh' );  
 
/*  */ 
$set_nums = 0;
$match_nums = 0;
$key_index = 0;
$key_link = "";
$key_link_used = array();
function display_zqh( $content ) {
	//echo "[in]";var_dump($set_nums,$match_nums,$key_index,$key_link,$key_link_used);
	global $set_nums, $match_nums, $key_index, $key_link, $key_link_used;
    //if( is_home() )  
    //    $content = $content . get_option('display_copyright_text'); 
	//if($_REQUEST['zqh_test'] == 1){
		if(is_single()){
			$str = get_option('display_zqh_text');
			$str = explode("\n", $str);
			if(isset($str[0])){
				$line1_arr = explode(",", $str[0]);
				$catsy = get_the_category();
				$myCat = $catsy[0]->cat_ID;
				if(!in_array($myCat, $line1_arr))
					return $content;
				$set_nums = 0;
				$match_nums = 0;
				$key_index = 0;
				$key_link = "";
				$key_link_used = array();
				for($i=1;$i<count($str);$i++){
					$tmp_arr = explode("|", $str[$i]);
					if(count($tmp_arr) != 2)
						continue;
					$keyword_arr = explode(",", $tmp_arr[0]);
					foreach($keyword_arr as $v1){
						if(!empty($v1)) $keyword_link[trim($v1)] = $tmp_arr[1];
					}
					
					foreach($keyword_link as $k2 => $v2){
						if($set_nums > 2) 
							break;
						$key_index = 1;
						$key_link = $v2;
						preg_match_all("/\b".$k2."\b/i", $content, $out_match);//print_r($out_match);exit;
						$match_nums = count($out_match[0]);
						if($match_nums <= 0)
							continue;
						//$encode = mb_detect_encoding($content, array('GB2312','GBK','UTF-8'));var_dump($encode);exit;
						//$content = '';
						//$out = str_ireplace("vintage necklace", "999", $content, $count_nums);var_dump($out);exit;
						$content = preg_replace_callback(
							"/\b".$k2."\b/i",
							function ($matches) {
								global $set_nums, $match_nums, $key_index, $key_link, $key_link_used;
								$resp = $matches[0];
								$key_place = ceil($match_nums/2);
								if($key_index == $key_place && !in_array($key_link, $key_link_used)){
									$resp = '<a href="'.$key_link.'">'.$resp.'</a>';//echo $resp;
									$key_link_used[] = $key_link;
									$set_nums++;
								}else{
									
								}
								$key_index++;
								return $resp;
							},
							$content
						);
						//var_dump($content);exit;
					}
					//var_dump($content);
				}
			}
		}
	//}
	//var_dump($content);
 //echo "[out]";var_dump($set_nums,$match_nums,$key_index,$key_link,$key_link_used);
    return $content;  
}  
?> 