<?PHP
/**
 * The header.
 * @package    175750.com
 * @subpackage JaneStyle2
 * @since      JaneStyle2 
 * @version    2.0
 *
 **/
?>
<!DOCTYPE html>
<html>
<head>
<?php $options = (ClassicOptions::getOptions()); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php wp_title( '|', true, 'right' ); bloginfo('name'); ?></title>
<?php
if (is_home() || is_page()) { // 如果是首页或页面页就执行下面的句子
    // 将以下引号中的内容改成你的主页description
    $description = $options['jane_description'];
    // 将以下引号中的内容改成你的主页keywords
    $keywords = $options['jane_keywords'];
} elseif (is_single()) { // 如果是文章页就执行下面的句子
    $description1 = get_post_meta($post->ID, "description", true);
    $description2 = mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)) , 0, 200, "…");
    // 填写自定义字段description时显示自定义字段的内容，否则使用文章内容前200字作为描述
    $description = $description1 ? $description1 : $description2;
    // 填写自定义字段keywords时显示自定义字段的内容，否则使用文章tags作为关键词
    $keywords = get_post_meta($post->ID, "keywords", true);
    if ($keywords == "") {
        $tags = wp_get_post_tags($post->ID);
        foreach ($tags as $tag) {
            $keywords = $keywords . $tag->name . ", ";
        }
        $keywords = rtrim($keywords, ", ");
    }
} elseif (is_category()) {
    $description = category_description();
    $keywords = single_cat_title('', false);
} elseif (is_tag()) {
    $description = tag_description();
    $keywords = single_tag_title('', false);
}
$description = trim(strip_tags($description));
$keywords = trim(strip_tags($keywords));
?>

<meta name="description" content="<?php echo $description; ?>" />
<meta name="keywords" content="<?php echo $keywords; ?>" />
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
<link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/img/favicon.ico" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_url'); ?>/font/css/font-awesome.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'template_url' ); ?>/font/css/font-awesome-ie7.min.css" />
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/SuperSlide.js"></script>
<?php wp_head(); ?>
</head>

<body>
<div class="top">
	<div class="wp">
		<div class="fl"></div>
		<div class="fr">
			<div class="tool fl">
				<a href="<?php bloginfo('url');?>">时尚网</a>
				<a href="javascript:void(0);">收藏本站</a>
				<a href="javascript:void(0);">推荐本站</a>
				<a href="javascript:void(0);">反馈意见</a>
			</div>
			<div class="user fl" <?php if ($options['jane_user'] == '1'){ echo 'style="display: none;"'; }else { echo 'style="display: block;"';} ?>>
			    <?php if(is_user_logged_in()){
					 echo '<a href="javascript:void(0);" style="margin-right: 15px;"><i class="fa fa-user"></i> 已登录</a>';
					 if ( $user_ID ){ ?>
			         <a href="<?php echo wp_logout_url(home_url()); ?>">登出</a>
			         <?php }; 
					 }else{
					?>	 
			    <a href="<?php echo site_url('/wp-login.php'); ?>"  style="margin-right: 15px;"><i class="fa fa-user"></i> 登录</a> 
			    <a href="<?php echo site_url('/wp-login.php?action=register'); ?>">注册</a> 
				<?php }; ?>
			</div>
		</div>
	</div>
</div>
<div id="header" class="wp">
	<div class="banner">
		<div class="logo fl">
			<h1><a title="<?php bloginfo( 'mane' ); ?>" href="<?php bloginfo('url');?>"><?php bloginfo( 'mane' ); ?></a></h1>
		</div>
		<div class="ad fr"><?php echo ($options['jane_banner']); ?></div>
	</div>
	<div class="nav">
		<ul>
		<?php 
			$menuParameters = array(
			'container'	=> false,
			//'echo'	=> false,
			'items_wrap' => '%3$s',
			'theme_locatsion' =>'primary',
			'depth' => 0,
			);
			echo strip_tags(wp_nav_menu( $menuParameters), '<li><a>' );
			?>
		</ul>
	</div>
	<div class="navbar" <?php if ($options['jane_menu'] == '1'){ echo 'style="display: none;"'; }else { echo 'style="display: block;"';} ?>>
		<div class="fl">
			<strong>热门标签：</strong>
			<?php wp_tag_cloud('smallest=14&largest=14&unit=px&number=7&orderby=count&order=DESC'); ?>
		</div>
		<div class="search fr">
			<form id="searchForm" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<input name="s" id="s" type="text" class="text" value="请输入关键字" onblur="if(this.value==''){this.value='请输入关键字';}" onfocus="if(this.value =='请输入关键字') {this.value=''; }"/>
				<button type="submit" value="" id="searchsubmit">搜索</button>
			</form>
		</div>
	</div>
</div>