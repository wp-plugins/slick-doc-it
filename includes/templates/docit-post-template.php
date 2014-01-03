<?php
/************************************************
 	Template file for Doc It Posts
************************************************/
get_header(); 
?>
<?php
	//Post Info 
	$postid = $post->ID;
	$tax_parent = di_post_main_tax(); 
?>

<div id="docit-primary" class="docit-content-area">
  <div id="docit-content" class="docit-site-content" role="main">
    <?php //Doc It sidebar menu
		echo do_shortcode('[docit id='.$tax_parent.']'); ?>
         
	
    <div id="doc-it-content-wrap">
      <?php /* The loop */ ?>
      <?php while ( have_posts() ) : the_post();
	   ?>
      
      <article id="docit-post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="docit-entry-header">
          <h1 class="header-text-docit">
            <?php the_title(); ?>
          </h1>
           <?php doc_it_breadcrumb(); ?>
        </header>
        <!-- .docit-entry-header -->
        
        <div class="docit-entry-content">
          <article id="docit-post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="docit-entry-header">
              <div class="docit-entry-meta">
                <?php edit_post_link('<span class="edit-link">', '</span>' ); ?>
              </div>
              <!-- .docit-entry-meta --> 
            </header>
            <!-- .docit-entry-header --> 
            <?php the_content(); ?>
          </article>
          <!-- #docit-post --> 
        </div>
        <!-- .docit-entry-content --> 
        
      
      </article>
      <!-- #docit-post -->
      <?php endwhile; ?>
      
      <div class="doc-it-next-prev-wrap"><?php di_next_previous_post($postid, $tax_parent);?></div> 
    </div>
    <!-- #docit-archive-wrap --> 
    
	

  </div>
  <!-- #docit-content -->
  
  <div class="clear"></div>
</div>
<!-- #docit-primary -->



<div class="clear"></div>
<?php get_footer(); ?>
