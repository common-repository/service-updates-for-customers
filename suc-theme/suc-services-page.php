<?php 
$args = array( 'post_type' => $this->post_type, 'posts_per_page' => 10 );
$loop = new WP_Query( $args );
global $post;
$i = 0 ;
?>
<div class="suc-title" id="service-heading"> Service </div> 
		  <div class="suc-description" id="service-heading">Detail link</div> 
		  <div class="status" id="service-heading"> Status</div>
<?php while ( $loop->have_posts() ) : $loop->the_post();  ?>
    <div class="services"> 
		  <div class="suc-title"> <?php the_title();  ?> </div> 
		  <div class="suc-description"> <?php //the_content(); ?> 
		  <a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
		  For Detail Click Here </a></div> 
		  <div class="status"> <?php echo $this->statuses[$i][name]; ?>  </div>
   </div> 
<?php
	$i++;
	endwhile;

?>


