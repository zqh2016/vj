<?php 
/*
 * Template Name: 相关
*/
?>
<div class="related">
  <div class="stit"><strong><i class="fa fa-heart"></i>可能感兴趣</strong></div>
  <ul>
  <?php
  // Reference : http://codex.wordpress.org/Function_Reference/wp_get_post_tags
  // we are using this function to get an array of tags assigned to current post
  $tags = wp_get_post_tags($post->ID);
  if ($tags) {
	$tag_ids = array();
	foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
		$args=array(
			'tag__in' => $tag_ids,
			'post__not_in' => array($post->ID),
			'showposts' => 8, // these are the number of related posts we want to display
			'ignore_sticky_posts' => 1 // to exclude the sticky post
		);
	// WP_Query takes the same arguments as query_posts
	$related_query = new WP_Query($args);
	if ($related_query->have_posts()) {
    while ($related_query->have_posts()) : $related_query->the_post(); 
    ?>
    <li>
      <a target="_blank" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
      	<img src="<?php echo get_bloginfo("template_url") ?>/timthumb.php?src=<?php post_thumbnail_src(); ?>&h=205&w=147&q=90&zc=1&ct=1&a=t"/>
      </a>
      <span><a target="_blank" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span>
    </li>
    <?php endwhile;
    }}; wp_reset_query(); ?>
  </ul>
</div>