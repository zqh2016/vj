<div class="focus w330 fr">
	<div class="stit">
		<strong><i class="fa fa-fire"></i>今日热闻</strong>
		<span>Fashion Daily</span>
	</div>
	<?php 
		$args = array(
		'posts_per_page' => 3,
		'post__in'  => get_option( 'sticky_posts' ),
		'ignore_sticky_posts' => 1,
		);
		$the_query = new WP_Query( $args );
		while ( $the_query->have_posts() ) : $the_query->the_post(); 
		?>	 
	<div class="focus-item">
		<div class="focus-news fl">
			<h2><a href="<?php the_permalink(); ?>" target="_blank" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
			<p><?php echo mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, 72,"..."); ?></p>
			<a href="<?php the_permalink(); ?>" target="_blank">more</a>
		</div>
		<div class="focus-img fr">
			<a href="<?php the_permalink(); ?>" target="_blank">
				<img src="<?php echo get_bloginfo("template_url") ?>/timthumb.php?src=<?php post_thumbnail_src(); ?>&h=180&w=180&q=100&zc=1&ct=1&a=t" alt="<?php the_title(); ?>"/>
			</a>
		</div>
	</div>
	<?php endwhile; wp_reset_postdata(); ?>
</div>