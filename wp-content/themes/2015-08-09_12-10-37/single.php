<?php get_header(); ?>
<div id="main" class="wp">
	<?php jane_breadcrumbs(); ?>
	<div class="w670 fl mt">
		<div class="post">
		<?php while ( have_posts() ) : the_post(); update_post_caches($posts); ?>
		<h1><?php the_title();?></h1>
		<div class="note">
			<span><i class="fa fa-pencil-square-o"></i>编辑：<?php the_author(); ?></span>
			<span><i class="fa fa-calendar"></i></i><?php the_time('Y年n月j日'); ?></span> 
			<span><i class="fa fa-comments"></i><?php comments_number('0评论','1评论','%评论'); ?></span> 
			<span><i class="fa fa-eye"></i><?php get_post_views($post -> ID); ?>浏览</span>
		</div>
		<div class="conts">
        <?php the_content(); ?>
        </div>
		<?php endwhile; ?>
		</div>
		<div class="tag"><?php the_tags(('<strong>标签:</strong> '), ' '); ?></div>
	    <?php wp_link_pages( array(
		'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'jane' ) . '</span>',
		'after'       => '</div>',
		'link_before' => '<span>',
		'link_after'  => '</span>',
		) );
		?>
		<!--相关文章-->
		<?php get_template_part( 'inc/related' ); ?>
		<?php
	    // If comments are open or we have at least one comment, load up the comment template
	    if ( comments_open() || '0' != get_comments_number() ) :
		    comments_template();
	    endif;
	   ?>
	</div>
	<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>