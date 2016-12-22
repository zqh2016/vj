<?php get_header(); ?>
<?php $options = (ClassicOptions::getOptions()); ?>
<div id="main" class="wp">
	<div class="firstPart">
		<div class="slide w670 fl">
			<ul class="pic">					
				<li style=" display: list-item;"><a href="<?php echo($options['jane_slide1_link']); ?>" target="_blank" title="<?php echo($options['jane_slide1_title']); ?>"><img src="<?php echo($options['jane_slide1']); ?>"></a></li>					
				<li><a href="<?php echo($options['jane_slide2_link']); ?>" target="_blank" title="<?php echo($options['jane_slide2_title']); ?>"><img src="<?php echo($options['jane_slide2']); ?>"></a></li>	
								
				<li><a href="<?php echo($options['jane_slide3_link']); ?>" target="_blank" title="<?php echo($options['jane_slide3_title']); ?>"><img src="<?php echo($options['jane_slide3']); ?>"></a></li>					
			</ul>			
			<div class="txt-bg"></div>
			<div class="txt">				
				<ul>					
					<li><a href="<?php echo($options['jane_slide1_link']); ?>" title="<?php echo($options['jane_slide1_title']); ?>"><?php echo($options['jane_slide1_title']); ?></a></li>	
									
					<li><a href="<?php echo($options['jane_slide2_link']); ?>" title="<?php echo($options['jane_slide2_title']); ?>"><?php echo($options['jane_slide2_title']); ?></a></li>	
									
					<li><a href="<?php echo($options['jane_slide3_link']); ?>" title="<?php echo($options['jane_slide3_title']); ?>"><?php echo($options['jane_slide3_title']); ?></a></li>					
				</ul>	
			</div>		
			<ul class="num">				
				<li class="on"><a>1</a><span></span></li>				
				<li class=""><a>2</a><span></span></li>				
				<li class=""><a>3</a><span></span></li>				
			</ul>	
		</div>
		<!--广告-->
		<div class="side w300 fr"><?php echo($options['jane_ad1']); ?></div>
	</div>
	<div class="model1">
		<div class="w670 fl">
			<div class="hpic w310 fl">
				<div class="stit">
					<h3>推荐专题</h3>
				</div>
				<a title="<?php echo($options['jane_jin_title']); ?>" href="<?php echo($options['jane_jin_link']); ?>">
					<img src="<?php echo get_bloginfo("template_url") ?>/timthumb.php?src=<?php echo($options['jane_jin']); ?>&h=465&w=310&q=90&zc=1&ct=1&a=t"/>
					<span class="hpic-tit"><?php echo($options['jane_jin_title']); ?></span>
				</a>
			</div>
			<?php include 'inc/sticky.php'; ?>
		</div>
		<div class="w300 fr">
			<div class="std">
				<div class="stit">
					<h3>随机文章</h3>
				</div>
				<?php
					global $post;
					$postid = $post->ID;
					$args = array( 'orderby' => 'rand', 'post__not_in' => array($post->ID), 'showposts' => 3);
					$query_posts = new WP_Query();
					$query_posts->query($args);
					while ($query_posts->have_posts()) : $query_posts->the_post();
					?>
				<div class="std-item">
					<div class="std-news fl">
						<h4><a target="_blank" title="<?php the_title(); ?>" href="<?php the_permalink(); ?>"><?php the_title();?></a></h4>
						<p><?php echo mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, 58,"..."); ?></p>
					</div>
					<div class="std-img fr">
						<a target="_blank" title="<?php the_title(); ?>" href="<?php the_permalink(); ?>"><img src="<?php echo get_bloginfo("template_url") ?>/timthumb.php?src=<?php post_thumbnail_src(); ?>&h=180&w=180&q=100&zc=1&ct=1&a=t"/></a>
					</div>
				</div>
				<?php endwhile; ?>
				<!--广告-->
				<div style="height: 160px;"><?php echo($options['jane_ad2']); ?></div>
			</div>
		</div>
	</div>
	<div class="model1">
		<?php get_template_part( 'content', get_post_format() );  ?>
		<?php get_sidebar(); ?>
	</div>
</div>
<?php get_footer(); ?>