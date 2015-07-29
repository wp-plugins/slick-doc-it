<?php
/************************************************
 	Template file for Doc It Archives
************************************************/
get_header();
	//Post Info 
	$postid = $post->ID;
	$DI_core = new Doc_It\Doc_It_Core();
	$tax_parent = $DI_core->di_post_main_tax(); 
?>

<div id="docit-primary" class="docit-content-area">
  <div id="docit-content" class="docit-site-content" role="main">
    <?php //Doc It sidebar menu
		echo do_shortcode('[docit id='.$tax_parent.']'); ?>
    <div id="doc-it-content-wrap">
    
     <article id="docit-post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="docit-entry-header">
    <h1 class="header-text-docit"><?php single_cat_title(''); ?>
          </h1>
           <?php $DI_core->doc_it_breadcrumb(); ?>
        </header>
        <!-- .docit-entry-header -->
      <?php 
	  global $query_string;
		query_posts( $query_string . '&posts_per_page=-1' );
	  if ( have_posts() ) : ?>
      <?php while ( have_posts() ) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="doc-it-entry-header">
          <h1 class="doc-it-entry-title"> <a href="<?php the_permalink(); ?>" rel="bookmark">
            <?php the_title(); ?>
            </a></h1>
          <div class="doc-it-entry-meta">
            <?php edit_post_link('<i class="icon-edit"></i>', '<span class="edit-link">', '</span>' ); ?>
          </div>
          <!-- .doc-it-entry-meta --> 
        </header>
        <!-- .doc-it-entry-header -->
        
        <div class="doc-it-entry-summary">
          <?php the_excerpt(); ?>
        </div>
        <!-- .doc-it-entry-summary --> 
        
      </article>
      <!-- #doc-it-post -->
      
      <?php endwhile; ?>
      <?php endif; ?>
    </div>
    <!-- #doc-it-archive-wrap -->
    
    <div class="clear"></div>
  </div>
  <!-- #doc-it-content --> 
</div>
<!-- #doc-it-primary -->

<div class="clear"></div>
<?php get_footer(); ?>