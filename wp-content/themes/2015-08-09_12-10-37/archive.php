<?php get_header(); ?>
<div id="mian" class="wp">
	<?php jane_breadcrumbs(); ?>
	<div class="model1">
		<?php get_template_part( 'content', get_post_format() );  ?>
		<?php get_sidebar(); ?>
	</div>
</div>
<?php get_footer(); ?>