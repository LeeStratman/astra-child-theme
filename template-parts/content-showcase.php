<?php
/**
 * Template for Blog Layout Showcase Grid.
 *
 * @package Astra Child
 */

?>

<?php astra_entry_before(); ?>

<article 
	<?php
		echo astra_attr(
			'article-blog',
			array(
				'id'    => 'post-' . get_the_id(),
				'class' => join( ' ', get_post_class() ),
			)
		);
		?>
>

	<?php astra_entry_top(); ?>

	<div <?php astra_blog_layout_class( 'astra-child__showcase-archive__entry' ); ?>>

		<div class="post-content">

			<?php get_template_part( 'template-parts/showcase/content-single' ); ?>

			<div class="entry-content clear"
			>
				<?php
					wp_link_pages(
						array(
							'before'      => '<div class="page-links">' . esc_html( astra_default_strings( 'string-blog-page-links-before', false ) ),
							'after'       => '</div>',
							'link_before' => '<span class="page-link">',
							'link_after'  => '</span>',
						)
					);
					?>
			</div><!-- .entry-content .clear -->
		</div><!-- .post-content -->

	</div> <!-- .blog-layout-1 -->

	<?php astra_entry_bottom(); ?>

</article><!-- #post-## -->

<?php astra_entry_after(); ?>
