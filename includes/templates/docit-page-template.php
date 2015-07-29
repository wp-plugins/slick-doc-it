<?php
/************************************************
 	Template file for Doc It Pages
************************************************/
get_header(); 

?>
<div id="docit-primary" class="doc-it-content-area">
  <div id="doc-it-content" class="doc-it-site-content">
    <?php /* The loop */ ?>
    <?php while ( have_posts() ) : the_post(); ?>
    <?php /* This containce the shortcode to display menu.*/ ?>
    
    <!-- #docit-post -->
    <?php the_content(); ?>
    <?php endwhile; ?>
    <div id="doc-it-content-wrap">
      <?php	
	global	$docit_att;
	$di_intro = $docit_att['intro'];
	$di_id = $docit_att['id'];
	
$posts = new WP_Query('post_type=docit_intro&p='.$di_intro);									

   //loop through posts
	while ($posts->have_posts()) {
		//get the post
	  $posts->the_post();
		 ?>
      <article id="docit-post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="docit-entry-header">
          <h1 class="header-text-docit header-text-docit-page">
            <?php the_title(); ?>
          </h1>
        </header>
        <!-- .docit-entry-header -->
        
        <div class="docit-entry-content">
          <article id="docit-post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php echo the_content(); ?> </article>
          <!-- #docit-post -->
          
          <?php }//end while ?>
        </div>
        <!-- .docit-entry-content --> 
        
      </article>
    </div>
    <!-- #docit-archive-wrap --> 
    
  </div>
  <!-- #docit-content -->
  
  <div class="clear"></div>
</div>
<!-- #docit-primary -->

<div class="clear"></div>
<?php get_footer(); ?>