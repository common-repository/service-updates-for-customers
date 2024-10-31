<?php
/*
	Plugin Name: Service Updates for customer 
	Description: The plugin sent the services updates to the registered users.
	Plugin URI: http://demo.cybersourcepk.com/?page_id=9
	Author: Irshad 
	Author URI: http://www.cybersourcepk.com/
	License: GPL2
	Version: 1.0
*/

/*  Copyright 2011 Irshad Ahmad (email : pakweb09@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

include_once("suc-core/classes/suc_subscription.php");

class ServiceUpdates_for_customers {
        
		public $slug;
		public $statuses; 
		public $post_type = "suc-service";
	
		const option_prefix  = "service-status-opt-";
		const meta_prefix    = "service-status-meta-";
		
		
	
		function  __construct(){
		
			add_action('plugins_loaded',array( $this, 'init' ),8);
			add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
			add_action( 'admin_init', array( &$this, 'admin_init' ) );
			add_action( 'save_post', array( &$this, 'save_post' ) );
			add_action( 'manage_posts_custom_column', array( &$this, 'custom_column' ) );
			add_action('wp_enqueue_scripts', array( &$this, 'suc_stylesheet' ));
		    add_filter( 'manage_edit-'.$this->post_type.'_columns', array( &$this, 'edit_columns' ) );
			add_shortcode( 'services_updates' , array( &$this, 'show_all_services_updates' ));
			
			
			$this->get_options();
		}
		
		function init(){
		
		   $this->start();
		   $this->includes();
		 
		   
		
		} // init End 
		
		
     private function start(){
			// Set the core file path
			define( 'SUC_FILE_PATH', dirname( __FILE__ ) );
	
			// Define the path to the plugin folder
			define( 'SUC_DIR_NAME',  basename( WPSC_FILE_PATH ) );
	
			// Define the URL to the plugin folder
			define( 'SUC_FOLDER',    dirname( plugin_basename( __FILE__ ) ) );
			define( 'SUC_URL',       plugins_url( '', __FILE__ ) );
		}
	
	
		public function admin_menu() { 
		  add_submenu_page( 'edit.php?post_type=suc-service', 'Service Status Options', 'Service Status', 'manage_options', 'suc-service', array( &$this, 'my_options_page' ));
			}
		
		
		public function admin_init() {
				add_meta_box("service-status-meta", "Status", array( &$this, 'post_status' ), 'suc-service', "side", "low");
		}
			public function my_options_page() {
				
				require_once( SUC_FILE_PATH .'/suc-core/option-page-service-status.php' );
			}
         
		
		
	public function post_status() {
		global $post;
		
		$current_status = get_post_meta($post->ID, ServiceUpdates_for_customers::meta_prefix.'status',true);
		
		?>
		<p><label for="service-status-status">Status:</label><br />
		<select name="service-status-status" id="service-status-status">
			<?php
			foreach ($this->statuses as $key => $status) {
				echo ( $current_status == $key ) ? '<option selected="selected"' : '<option';
				echo ' value="'.$key.'">'.$status['name'].'</option>';
			}
			?>
		</select>
		</p>
		<?php
	}


   public function save_post() {  
		global $post;
		if ( isset( $_POST['service-status-status'] ) ) {
			update_post_meta($post->ID, ServiceUpdates_for_customers::meta_prefix.'status', $_POST['service-status-status']);
		}
	}
		
		public function get_options() {
				
				$this->slug = get_option( ServiceUpdates_for_customers::option_prefix.'slug', 'status' );
				$this->statuses = get_option( ServiceUpdates_for_customers::option_prefix.'statuses', array() );
			
			}
		
		
		public function save_options() {
			
				update_option( ServiceUpdates_for_customers::option_prefix.'slug', $this->slug );
				update_option( ServiceUpdates_for_customers::option_prefix.'statuses', $this->statuses );
			
			}

     public function edit_columns($columns) {
		 
		$columns = array(
			'cb' 		=> '<input type="checkbox" />',
			'title' 	=> 'Title',
			'status'	=> 'Status',
			'date' 		=> 'Date'
			
		);
		return $columns;
	}
	
	public function custom_column($column) { 
		global $post;  
		switch($column) {
			case "date": 
				echo get_the_date('j m y H:i');
				break;
			case "description":
				the_title();
				break;
			case "status":
				$id = get_post_meta($post->ID, ServiceUpdates_for_customers::meta_prefix.'status',true);
				echo $this->statuses[$id]['name'];
				break;
		}
	}
	   
	   //  adding stylesheet for plugin 
	   
	   public function suc_stylesheet(){
	   	
			    $myStyleUrl = plugins_url('suc-css/suc_style.css', __FILE__); // Respects SSL, Style.css is relative to the current file
				$myStyleFile = WP_PLUGIN_DIR . '/service-updates-for-customers/suc-css/suc_style.css';
				if ( file_exists($myStyleFile) ) {
					wp_register_style('myStyleSheets', $myStyleUrl);
					wp_enqueue_style( 'myStyleSheets');
				}
	   
	   
	   }
	   // function for shortcode 
	   public function show_all_services_updates($atts){
	    
	     require_once( SUC_FILE_PATH .'/suc-theme/suc-services-page.php' );
	   }
	 
	   // creating custom post, category , tags etc
	   public function includes(){
	   		require_once( SUC_FILE_PATH .'/suc-core/suc-functions.php' );

	    }

}

$service_updates = new ServiceUpdates_for_customers();

?>