<div class="post w670 fl">
	<div class="stit">
		<strong><i class="fa fa-flag"></i>最新文章</strong>
		<span>Recent Posts</span>
	</div>
	<?php if ( have_posts() ) : while (have_posts()) : the_post(); ?>
	<div class="post-list">
		<div class="post-img fl">
			<a target="_blank" title="<?php the_title(); ?>" href="<?php the_permalink(); ?>"><img src="<?php echo get_bloginfo("template_url") ?>/timthumb.php?src=<?php post_thumbnail_src(); ?>&h=160&w=200&q=100&zc=1&ct=1&a=t"/></a>
		</div>
		<div class="post-news fr">
			<h2>
				<?php 
					$category = get_the_category(); 
					if( $category[0] ){
						echo '<span>[<a href="'.get_category_link($category[0]->term_id ).'" title="'.$category[0]->cat_name.'">'.$category[0]->cat_name.'</a>]</span>';
					};
					?>
				<a href="<?php the_permalink(); ?>" target="_blank" title="<?php the_title(); ?>"><?php the_title(); ?></a>
				</h2>
			<p><?php echo mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, 165,"..."); ?></p>
			<div class="data fr">
				<span><i class="fa fa-calendar"></i><?php the_time('Y-n-j'); ?></span>
				<span><i class="fa fa-eye"></i><?php get_post_views($post->ID); ?>浏览</span>
				<span><i class="fa fa-comment"></i><?php comments_number('0评论','1评论','%评论'); ?></span>
			</div>
		</div>
	</div>
	<?php endwhile; endif; ?>
	<?php jane_paging_nav(6);?>
</div>