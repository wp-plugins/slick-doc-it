<?php namespace Doc_It;
	class DI_Settings_page {
	function __construct() {
		//Add Settings Page
		add_action('admin_menu', array($this,'Doc_It_menu_settings_page'));
		//Settings Page CSS
		add_action( 'wp_enqueue_style', 'doc_it_admin_css' );
	}
	//**************************************************
	// Add Settings Page to Menu
	//**************************************************
	function Doc_It_menu_settings_page() {
		//settings Info Page
		add_submenu_page(
			'edit.php?post_type=docit'
			, 'Doc It Settings'
			, 'Settings'
			, 'manage_options'
			, 'doc-it-settings-page'
			, array($this,'doc_it_settings_page')
		);
	}
	//**************************************************
	//Settings Page
	//**************************************************

//Main setting page function
function doc_it_settings_page() {
	
add_action( 'wp_enqueue_style', 'doc_it_admin_css' );

?>
<style type="text/css">
.final-shortcode-textarea, .shortcode-generator-form, .final-instagram-user-id-textarea, instagram-shortcode-form {
	display: none;
}
</style>
   
    <div class="doc-it-admin-wrap">
	        <h1>Doc It Settings</h1>
<a class="buy-extensions-btn" href="http://www.slickremix.com/downloads/doc-it-premium-extension/" target="_blank">Get Extension Here!</a> <?php
		
		
		$options = get_option('doc_it_menu_labelz');
		if (!empty($options))	{
			foreach($options as $key => $link) 
				{ 
					if($link == '') 
					{ 
						unset($options[$key]); 
					} 
				} 
			$options = array_values(array_filter($options));
		}//end if $options empty
	?>
  
  
  
   <form method="post" id="doc-it-settings-taxes" name="createMenu" action="options.php">
	            <?php settings_fields('doc-it-settings-taxes'); ?>
             
	            <div class="doc-it-settings-admin-input-wrap company-info-style">
	                <div class="use-of-plugin">
	                    1. Input the Main Name for what you are going to document and save it. (ie. My Project, My Book, My Plugin, etc...) This creates an admin menu item. (on the left of this page under "DocIt" tab) This admin menu item is where you will go to make the categories for the item you are documenting.
	       
	                </div>
	       	<?php  
				if (is_plugin_active('doc-it-premium/doc-it-premium.php')) {
					$doc_it_array = count($options);
					$final_count = $doc_it_array == '1' ? 1 : $doc_it_array + 1;
			
								  echo '<input name="doc_it_menu_labelz['.$final_count.']" id="doc-it-create-id" placeholder="Letters and numbers only" class="doc-it-settings-admin-input doc-it-custom-name" type="text"  />';
				      echo'<br/>';
				    
					  if (!empty($options))	{
						  foreach ($options as $key => $value) {
								  $final_key = preg_replace("/[^A-Za-z0-9 '-]/","",$options[$key]);
								 echo '<div class="doc_it_menu_labels_wrap'.$key.'"><a class="delete_option doc_it_menu_labels'.$key.'" href="javascript:;">X</a>';
								  echo '<input id="doc_it_menu_labels'.$key.'" readonly="readonly" name="doc_it_menu_labelz['.$key.']" class="doc-it-settings-admin-input no_input_backg" type="text" value="'.$final_key.'" /><br/>';
								 echo '</div>';
						  }
				}//end if $options empty
			} else { ?>       
	        <?php if (!empty($options)) {
				echo '<h3 class="need-premium">Please Purchase the <a href="http://www.slickremix.com/downloads/doc-it-premium-extension/" target="_blank">DocIt Premium Extension</a> to create as many Document Items as you want!</h3>';
				echo'<input id="doc-it-create-id" readonly="readonly" placeholder="Please upgrade to create more" class="doc-it-settings-admin-input doc-it-custom-name need-premium-input" type="text"  value="" />';
				echo'<br/>';
				}
				else {
					echo'<input name="doc_it_menu_labelz[1]" id="doc-it-create-id" placeholder="Letters and numbers only" class="doc-it-settings-admin-input doc-it-custom-name" type="text"/>';
					echo'<br/>';
				}
				if (!empty($options)) {
					foreach ($options as $key => $value) {
						$final_key = preg_replace("/[^A-Za-z0-9 '-]/", "", $options[$key]);
						echo '<div class="doc_it_menu_labels_wrap1"><a class="delete_option doc_it_menu_labels1" href="javascript:;">X</a>';
						echo '<input id="doc_it_menu_labels1" readonly="readonly" name="doc_it_menu_labelz[1]" class="doc-it-settings-admin-input no_input_backg" type="text" value="'.$final_key.'" /><br/>';
						echo '</div>';
					}
				}//not empty check
			 } //end NOT Premium ?>
			<input type="submit" class="doc-it-admin-submit-btn" value="<?php _e('Save Changes') ?>">
	         
	        </form>
  
      <div class="clear"></div>
    </div>
    <!--/doc-it-settings-admin-input-wrap-->
  
 
  <script>
  jQuery(document).ready(function() {
		<?php foreach ($options as $key => $value) {?>
		  jQuery(".doc_it_menu_labels<?php print $key?>").click(function(){
				 jQuery('#doc_it_menu_labels<?php print $key?>').val('');
				 jQuery('.doc_it_menu_labels_wrap<?php print $key?>').addClass('di_disappear');
				 jQuery('#doc_it_menu_labels<?php print $key?>').remove();
		  });
		 <?php }?>
   });
</script> 

 <div class="doc-it-settings-admin-input-wrap company-info-style">  
  <div class="use-of-plugin-2">2. Now select which Doc It Category you would like to generate a shortcode for using the select option below.</div>
  <div class="doc-it-icon-wrap"> <a href="https://www.facebook.com/SlickRemix" target="_blank" class="facebook-icon"></a> </div>
  <form class="doc-it-admin-form">
    <select id="shortcode-form-selector">
      <option value="">Please Select a Category</option>
      <?php 
global $wp_taxonomies;
if ($wp_taxonomies){
foreach($wp_taxonomies as $taxonomy){
 if($taxonomy->object_type[0] == 'Doc It' && !empty($taxonomy->labels->label)){
 $taxonomy_menu_name = $taxonomy->labels->label; 
 $taxonomy_name = $taxonomy->query_var;
 ?>		 
          <option value="<?php print $taxonomy_name?>-shortcode-form"><?php print $taxonomy_menu_name?></option>
 <?php } 
 } 
?>
      </select>  
  </form>
  <!--/doc-it-admin-form-->
<?php  
  
?>  
  
  <?php
foreach($wp_taxonomies as $main_taxonomy) {
	if ($main_taxonomy->object_type[0] == 'Doc It' && !empty($main_taxonomy->labels->label))	{
		 $main_taxonomy_menu_name = $main_taxonomy->labels->label; 
		$main_taxonomy_name = $main_taxonomy->query_var; 
?>
  <div class="docit-<?php print $main_taxonomy_name?>-shortcode-form">
    <form class="doc-it-admin-form shortcode-generator-form <?php print $main_taxonomy_name?>-shortcode-form">
      <h2><?php print $main_taxonomy_menu_name?> Shortcode Generator</h2>
      <div class="instructional-text">You must create an <a href="edit.php?post_type=docit_intro" target="_blank">Introduction Post</a> then you can select one below. This is the content that will appear on the page you decide to paste your shortcode on. (Basically, the post will be the "Front Page" of the Document Item you select it for.)</div>
      <div class="doc-it-admin-input-wrap <?php print $main_taxonomy_name?>_id">
        <div class="doc-it-admin-input-label">Introduction Post (required)</div>
        <input type="hidden" id="<?php print $main_taxonomy_name?>_id" class="doc-it-admin-input" value="<?php print $main_taxonomy_name?>" />
        
         <select id="<?php print $main_taxonomy_name?>_intro">
      <option value="">Please Select an Introduction Post</option>
      <?php 
		$pre_sub_posts = new \WP_Query("post_type=docit_intro");
		if ($pre_sub_posts-> have_posts()) {		
			 //loop through posts
			while ($pre_sub_posts-> have_posts()) {
				$pre_sub_posts->the_post();?>
				<option value="<?php print the_ID();?>"><?php print the_title();?></option>
                
      <?php
			}//end while
		}//end if
 ?>
      </select>  
        <div class="clear"></div>
      </div>
      <!--/doc-it-admin-input-wrap-->
      
      <input type="button" class="doc-it-admin-submit-btn" value="Generate Doc It Shortcode" onclick="updateTextArea_<?php print $main_taxonomy_name?>();" tabindex="4" style="margin-right:1em;" />
      <div class="doc-it-admin-input-wrap final-shortcode-textarea">
        <div class="instructional-text">Now copy and paste the shortcode below to a <a href="edit.php?post_type=page" target="_blank">Page that you should make</a>. Name it relative to your Doc It Menu you created above. The shortcode will not work on posts, it must be a page.</div>
        <input class="copyme <?php print $main_taxonomy_name?>-final-shortcode doc-it-admin-input" value="" />
        <div class="clear"></div>
      </div>
      <!--/doc-it-admin-input-wrap-->
      
    </form>
  </div>
  <!--/docit-facebook_group-shortcode-form--> 
  
  <script>
//START Script for shortcode creation//
function updateTextArea_<?php print $main_taxonomy_name?>() {
    //hidden Id Input
	var <?php print $main_taxonomy_name?>_id = ' id=' + jQuery("input#<?php print $main_taxonomy_name?>_id").val(); 
	
	if (<?php print $main_taxonomy_name?>_id == " id=") {
	  	 jQuery(".<?php print $main_taxonomy_name;?>_id").addClass('docit-empty-error');  
      	 jQuery("input#<?php print $main_taxonomy_name?>_id").focus();
		 return false;
	}
	if (<?php print $main_taxonomy_name?>_id != " id=") {
	  	 jQuery(".<?php print $main_taxonomy_name?>_id").removeClass('docit-empty-error');  
	}
	
	//hidden Instructions select
	var <?php print $main_taxonomy_name?>_intro = ' intro=' + jQuery( "select#<?php print $main_taxonomy_name?>_intro" ).val();
	
	if (<?php print $main_taxonomy_name?>_intro == " intro=") {
	  	 jQuery("select#<?php print $main_taxonomy_name?>_intro").addClass('docit-empty-error');  
      	 jQuery("select#<?php print $main_taxonomy_name?>_intro").focus();
		 return false;
	}
	if (<?php print $main_taxonomy_name?>_intro != " intro=") {
	  	 jQuery("select#<?php print $main_taxonomy_name?>_intro").removeClass('docit-empty-error');  
	}
	
		var final_<?php print $main_taxonomy_name?>_shorcode = '[docit' + <?php print $main_taxonomy_name?>_id + <?php print $main_taxonomy_name?>_intro +']'

jQuery('.<?php print $main_taxonomy_name?>-final-shortcode').val(final_<?php print $main_taxonomy_name?>_shorcode);
	
	jQuery('.<?php print $main_taxonomy_name?>-shortcode-form .final-shortcode-textarea').slideDown();
	
}
//END Script for shortcode creation//
</script>
  <?php }//endif
}//endforeach
}
?>
  <a class="doc-it-admin-slick-logo" href="http://www.slickremix.com" target="_blank"></a> 
  </div><!--doc-it-settings-admin-input-wrap-->
  
 <div class="doc-it-settings-admin-input-wrap company-info-style">  
  <div class="use-of-plugin-2"><h1>Additional Options</h1> 
   If you would like to change the colors of the menu please<?php 
if(is_plugin_active('doc-it-premium/doc-it-premium.php')) {
   echo' <a href="edit.php?post_type=docit&page=doc-it-color-options-settings-page">click here</a>.';
}
else {
  		echo' <a href="http://www.slickremix.com/product/doc-it-premium-extension/" target="_blank">click here</a> to upgrade.';	
	}
  ?>
  

  
  
  </div>
  <!-- custom option for padding -->
  <form method="post" class="doc-it-color-settings-admin-form" action="options.php">
    <?php settings_fields('doc-it-settings-options'); ?>
    <div class="doc-it-color-settings-admin-input-wrap company-info-style doc-it-color-options-turn-on-custom-colors">
      <div class="doc-it-color-settings-admin-input-label docit-wp-header-custom"><p>Check the box to turn ON the custom padding option for the Doc It Plugin. This will make it so the menu and content fits nicely within your website. Simply define the numbers to suite your desired spacing. Here is how it works. 25px(top padding) 20px(right padding) 25px(bottom padding) 30px(left padding).</p><p>The same idea applies to the margin option. However, if you set a Max-Width for the Main Doc It Wrapper too we can add auto to the left and right margin so the frame will be positioned in the middle of the screen. Give it a try!</p></div>
    <p>
        <input name="doc-it-color-options-settings-custom-css-main-wrapper-padding" class="doc-it-color-settings-admin-input" type="checkbox"  id="doc-it-color-options-settings-custom-css-main-wrapper-padding" value="1" <?php echo checked( '1', get_option( 'doc-it-color-options-settings-custom-css-main-wrapper-padding' ) ); ?>/>
        <?php  
                        if (get_option( 'doc-it-color-options-settings-custom-css-main-wrapper-padding' ) == '1') {
                           echo "<strong>Checked:</strong> Custom style options being used now.";
                        }
                        else	{
                          echo "<strong>Not Checked:</strong> You are using the default styles.";
                        }
                           ?>
       </p>  <p>
        <label>Padding:</label>
        <input name="doc-it-color-options-main-wrapper-padding-input" class="doc-it-color-settings-admin-input" type="text"  id="doc-it-color-options-main-wrapper-padding-input" placeholder="25px 20px 25px 30px " value="<?php echo get_option('doc-it-color-options-main-wrapper-padding-input'); ?>" title="Only Numbers and px are allowed"/>
      </p>
     <p>
        <label>Max-Width:</label>
        <input name="doc-it-color-options-main-wrapper-width-input" class="doc-it-color-settings-admin-input" type="text"  id="doc-it-color-options-main-wrapper-width-input" placeholder="970px" value="<?php echo get_option('doc-it-color-options-main-wrapper-width-input'); ?>" title="Only Numbers and px are allowed"/>
      </p>
      <p>
        <label>Margin:</label>
        <input name="doc-it-color-options-main-wrapper-margin-input" class="doc-it-color-settings-admin-input" type="text"  id="doc-it-color-options-main-wrapper-margin-input" placeholder="20px auto 25px auto" value="<?php echo get_option('doc-it-color-options-main-wrapper-margin-input'); ?>" title="Only Numbers and px are allowed"/>
      </p>
      <p>
        <label>Menu:</label>
        <select name="doc-it-color-options-menu-position" class="doc-it-color-settings-admin-input" id="doc-it-color-options-menu-position" value="<?php echo get_option('doc-it-color-options-menu-position'); ?>"/>
        <option value="1">Left Side by default</option>
        <option value="2" <?php if ( get_option('doc-it-color-options-menu-position') == 2 ) echo 'selected="selected"' ?>>Right Side</option>
        </select>
        
      </p>
      <br/>
       <p>
        <input name="doc-it-color-options-settings-custom-css" class="doc-it-color-settings-admin-input" type="checkbox"  id="doc-it-color-options-settings-custom-css" value="1" <?php echo checked( '1', get_option( 'doc-it-color-options-settings-custom-css' ) ); ?>/>
        <?php  
                        if (get_option( 'doc-it-color-options-settings-custom-css' ) == '1') {
                           echo "<strong>Checked:</strong> Custom CSS option is being used now.";
                        }
                        else	{
                          echo "<strong>Not Checked:</strong> You are using the default CSS.";
                        }
                           ?>
       </p>
       <p>
         <label class="toggle-custom-textarea-show"><span>Show</span><span class="toggle-custom-textarea-hide">Hide</span> custom CSS</label>
       <div class="docit-custom-css-text">Thanks for using our plugin :) Add your custom CSS additions or overrides below.</div>
      <textarea name="doc-it-color-options-main-wrapper-css-input" class="doc-it-color-settings-admin-input" id="doc-it-color-options-main-wrapper-css-input"><?php echo get_option('doc-it-color-options-main-wrapper-css-input'); ?></textarea>
      </p>
      <div class="clear"></div>
      <div class="doc-it-color-settings-admin-input-label docit-wp-header-custom"><div class="styled-wrap-options">Check the box to turn ON the option to display code in your content and it be colored and spaced professionally. <a href="http://www.slickremix.com/2013/11/07/rainbow-color-options/" target="_blank">Click here</a> to see example usage and all the Supported Languages, this could not be easier. Thanks to <a href="http://craig.is/making/rainbows/" target="_blank">Rainbows</a>. </div>
      <input name="doc-it-color-coded" class="doc-it-color-settings-admin-input" type="checkbox"  id="doc-it-color-coded" value="1" <?php echo checked( '1', get_option( 'doc-it-color-coded' ) ); ?>/>
        		   <?php  
                        if (get_option( 'doc-it-color-coded' ) == '1') {
                           echo "<strong>Checked:</strong> You are NOW using Rainbows to color your code.";
                        }
                        else	{
                          echo "<strong>Not Checked:</strong> You are not using Rainbows to color you code.";
                        }
                     ?>
                           
      <div class="clear"></div>
      </div><!--styled-wrap-options-->
           
           <!-- Custom Option to have submenus closed on page load -->
           <div class="doc-it-color-settings-admin-input-label docit-wp-header-custom"><div class="styled-wrap-options">Check the box to make the menu items close when landing on a page.</div>
      <input name="doc-it-display-menu-closed" class="doc-it-color-settings-admin-input" type="checkbox"  id="doc-it-display-menu-closed" value="1" <?php echo checked( '1', get_option( 'doc-it-display-menu-closed' ) ); ?>/>
        		   <?php  
                        if (get_option( 'doc-it-display-menu-closed' ) == '1') {
                           echo "<strong>Checked:</strong> The menu will now be closed when the page loads.";
                        }
                        else	{
                          echo "<strong>Not Checked:</strong> The menu will be open when the page loads.";
                        }
                     ?>
                                
      <div class="clear"></div>
           </div><!--styled-wrap-options-->     
                           
    </div>
    <!--/doc-it-color-settings-admin-input-wrap-->
   
    <input type="submit" class="doc-it-admin-submit-btn" value="<?php _e('Save Changes') ?>" />
  </form>
  <!-- close custom option for padding --> 
  
</div>

<script type="text/javascript">
//only allow uppercase, lowercase, spaces and 1-9
 jQuery("#doc-it-create-id").keypress(function(event){
        var ew = event.which;
        if(ew == 32)
            return true;
        if(48 <= ew && ew <= 57)
            return true;
        if(65 <= ew && ew <= 90)
            return true;
        if(97 <= ew && ew <= 122)
            return true;
        return false;
		alert('error');
    });
	
jQuery(function() {    
    jQuery('#shortcode-form-selector').change(function(){
        jQuery('.shortcode-generator-form').hide();
        jQuery('.' + jQuery(this).val()).fadeIn('fast');
    });
});
//select all 
jQuery(".copyme").focus(function() {
    var jQuerythis = jQuery(this);
    jQuerythis.select();

    // Work around Chrome's little problem
    jQuerythis.mouseup(function() {
        // Prevent further mouseup intervention
        jQuerythis.unbind("mouseup");
        return false;
    });
});

jQuery( document ).ready(function() {
  jQuery( ".toggle-custom-textarea-show" ).click(function() {  
		 jQuery('textarea#doc-it-color-options-main-wrapper-css-input').slideToggle();
		  jQuery('.toggle-custom-textarea-show span').toggle();
		  jQuery('.docit-custom-css-text').toggle();
		  
}); 
  });
</script><?php
	}
}//End Class 
new DI_Settings_page();
?>