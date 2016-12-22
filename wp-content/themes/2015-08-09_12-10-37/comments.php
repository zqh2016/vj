<?php
/**
 * The template for displaying Comments
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>

	<h3 class="comments-title">
		这里有<?php printf(comments_number( '没有讨论', '1 个讨论', '% 个讨论' )); ?>，你怎么看？
		
	</h3>
	<ol class="comment-list">
		<?php
			wp_list_comments( array(
				'type' => comment,
				'callback' => mytheme_comment,
				'style'      => 'ol',
				'short_ping' => true,
				'avatar_size'=> 48, // avatar size
			) );
		?>
	</ol><!-- .comment-list -->

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	<div id="comment-nav-below" class="navigation comment-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'janestyle' ); ?></h1>
		<div class="commentnav"><?php previous_comments_link( __( '&larr; Older Comments', 'janestyle' ) ); ?></div>
		<div class="commentnav"><?php next_comments_link( __( 'Newer Comments &rarr;', 'janestyle' ) ); ?></div>
	</div><!-- #comment-nav-below -->
	<?php endif; // Check for comment navigation. ?>

	<?php if ( ! comments_open() ) : ?>
	<p class="no-comments"><?php _e( 'Comments are closed.', 'janestyle' ); ?></p>
	<?php endif; ?>

	<?php endif; // have_comments() ?>

	<?php comment_form(	array('comment_notes_after' => '')); ?>

</div><!-- #comments -->
