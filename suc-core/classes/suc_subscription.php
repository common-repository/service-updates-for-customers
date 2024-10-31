<?php

// Subscraction class 
// this class will be used for collecting, displaying and filitering members for the services.


class suc_subscription {


		function  __construct(){
		
		        add_action('plugins_loaded',array( $this, 'init' ),8);
				
				register_sidebar_widget('SUC Subscribe', array($this, 'widget_suc_subscribe'));
	            register_widget_control('SUC Subscribe', array($this, 'widget_suc_subscribe_control'));
				
				add_action('admin_menu', array(&$this, 'suc_subscriberlist'));
				add_action('comment_post', array(&$this, 'sucCommentNotification'));
		
		
		        

		
		}

		
		
		function init(){
								//Load default options
						
								if(!get_option("suc_subscribe_chkd")){
								
									    update_option("suc_subscribe_button", true);
									    update_option("suc_subscribe_label", "ok");
									    update_option("suc_subscribe_chkd", "Thanks for subscribing");
									
								}
				
				
				}	 // init() End 	
						
	
	
	/**
	 * Adds "subscriber list" link to admin Options menu
	 * @access public 
	 */
	function suc_subscriberlist() {
		add_options_page('Service Updates Via E-mail', 'Service Updates Via E-mail', 'manage_options', $this->cbnetmcn_path, array(&$this, 'sucOptionsPg'));
	}
	
	function quick_subscribe_register($source){

	require_once( ABSPATH . WPINC . '/registration.php');

	
	$user_email = apply_filters( 'user_registration_email', $source );
	$user_login = sanitize_user( str_replace('@','', $source) );

	// Check the e-mail address
	if ($user_email == '') {
		$errors = __('<div style="color:#FF0000;"><strong>ERROR</strong>: Please type your e-mail address.</div>');
	} elseif ( !is_email( $user_email ) ) {
		$errors = __('<div style="color:#FF0000;"><strong>ERROR</strong>: The email address isn&#8217;t correct.</div>');
		$user_email = '';
	} elseif ( email_exists( $user_email ) )
		$errors = __('<div style="color:#FF0000;"><strong>ERROR</strong>: This email is already registered, please choose another one.</div>');

	//do_action('register_post');

	$errors = apply_filters( 'registration_errors', $errors );
	$message = $errors;

	if ( empty( $errors ) ) {
		$user_pass = substr( md5( uniqid( microtime() ) ), 0, 7);

		$user_id = wp_create_user( $user_login, $user_pass, $user_email );
		
		$user = new WP_User($user_id);
		$user->set_role('subscriber');
		
		
		$message = "ok";
		
		
		if ( !$user_id )
			$errors['registerfail'] = sprintf(__('<strong>ERROR</strong>: Couldn&#8217;t register you... please contact the <a href="mailto:%s">webmaster</a> !'), get_option('admin_email'));
		
	}
	return $message;


}  // quick_subscribe_register End





					
     function widget_suc_subscribe($args) {
		  
		    extract($args);
			if ( $_POST['QS_user_email_widget'] ) {
				$message = $this->quick_subscribe_register($_POST['QS_user_email_widget']);
			}
			
			$options = get_option('quick_subscribe_title');
			
			$message = ($message=="ok") ? $options['message'] : $message;
			
			$caixa = 'E-mail'; 
			
			echo $before_widget;
			
			echo $before_title . $options['title'] . $after_title;
			?>
			
			<div id='quick_subscribe_messages'>
			
			<?= $message ?>
			
			</div>
		<?php // "?" . $_SERVER['QUERY_STRING'] . ?>
			<form name='quick_subscribe_form' id='quick_subscribe_form' method='POST' action='<?= "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']."?" . $_SERVER['QUERY_STRING']   ?>' >
			
				<input type='text' name='QS_user_email_widget' id='QS_user_email_widget' value='<?= $caixa ?>' onFocus='if(this.value=="<?= $caixa ?>") this.value=""' style="border:1px solid #E5E5E5; margin-bottom:10px;">

				<? if ($options['button']==1){ ?>
					
	<input type='submit' value='<? if($options['label']=='') echo 'Subscribe'; else echo $options['label']; ?>' class="button1" style="margin-left:20px;">
					
				<?php } ?>
			
			</form>
			
			
			<?
			echo $after_widget;

		  
		  
		  }  // widget_suc_subscribe  End 

       
	   
	   function widget_suc_subscribe_control() {
		
			// Collect our widget's options.
			$optionName = 'quick_subscribe_title';
			$submitName = 'quick_subscribe_submit';
			$options = get_option($optionName);
			
			// This is for handing the control form submission.
			if ( $_POST[$submitName] ) {
				// Clean up control form submission options
				$buttonValue = ($_POST['quick_subscribe_button']==1) ? 1 : 0;
				$newoptions['title'] = $_POST['quick_subscribe_title'];
				$newoptions['message'] = $_POST['quick_subscribe_message'];
				$newoptions['label'] = $_POST['quick_subscribe_label'];
				$newoptions['button'] = $buttonValue;
				
				if ( $options != $newoptions ) {
					$options = $newoptions;
					update_option($optionName, $options);
				
				}
			
			}
			
			$title = $options['title'];
			$message = $options['message'];
			$label = $options['label'];
			$button = $options['button'];
			
			
			
			// The HTML below is the control form for editing options.
			?>
			<div>
			<label for="quick_subscribe_title" style="line-height:35px;display:block;">
			Title: <input type="text" id="quick_subscribe_title" name="quick_subscribe_title" value="<?php echo $title; ?>" /></label>
			
			<label for="quick_subscribe_message" style="line-height:35px;display:block;">
			Thanks Message: <input type="text" id="quick_subscribe_message" name="quick_subscribe_message" value="<?php echo $message; ?>" /></label>
			
			<label for="quick_subscribe_button" style="line-height:35px;display:block;">
			<input type="checkbox" id="quick_subscribe_button" name="quick_subscribe_button" value="1" <?php if ($button==1) echo "checked"; ?> /> Display Submit button</label>
			
			<label for="quick_subscribe_label" style="line-height:35px;display:block;">
			Button label: <input type="text" id="quick_subscribe_label" name="quick_subscribe_label" value="<?php echo $label; ?>" /></label>
			
			<input type="hidden" name="<? echo $submitName ?>" id="<? echo $submitName ?>" value="1" />
			</div>
			<?php
			
		}     // end of widget_suc_subscribe_control func


		
				 
	 function quick_subscribe_get_form($message, $source){
			$caixa = 'E-mail'; 
			$op_button = (get_option("quicksubscribe_button")) ? "checked" : "";
			$op_label = get_option("quicksubscribe_label");
			$op_tks = get_option("quicksubscribe_tks");
			
			$message = ($message=="ok") ? $op_tks : $message;
			
			$output = "<div id='quick_subscribe_messages'>". $message ."</div>";
			
			$output .= "<form name='quick_subscribe_form' id='quick_subscribe_form' method='POST' action='http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'] ."'>";
			$output .= "<input type='text' name='". $source ."' id='". $source ."' value='$caixa' onFocus='if(this.value==\"$caixa\") this.value=\"\"'>";
			if ($op_button) $output .= "<input type='submit' value='$op_label'>";
			$output .= "</form>";
			return $output;
		}
		
		
		
		function quick_subscribe_post_form($content){
			
			
			//TODO Check if there is [quicksubscribe] tag and only do anything if it necessary
			if ( $_POST['QS_user_email_post'] ) {
				$message = $this->quick_subscribe_register($_POST['QS_user_email_post']);
			}
			
			$output = $this->quick_subscribe_get_form($message, "QS_user_email_post");
			
			
			$content = str_replace("[quicksubscribe]", $output, $content);
			return $content;
			
		
			
		}
		
		function quick_subscribe_form(){
		
			if ( $_POST['QS_user_email_tt'] ) {
				$message = $this->quick_subscribe_register($_POST['QS_user_email_tt']);
			}
			
			echo $this->quick_subscribe_get_form($message, "QS_user_email_tt");
			
		}
  
      //  sending email and subscrier list go below 
	  
	  
			  /**
			 * Sends email
			 * @param string $to
			 * @param string $subject
			 * @param string $message
			 * @access public
			 */
			function sucSendMail($to, $subject, $message) {
				$site_name  = str_replace('"', "'", $this->site_name);
				$site_email = str_replace(array('<', '>'), array('', ''), $this->site_email);
				$charset    = get_settings('blog_charset');
				$headers    = "From: \"{$site_name}\" <{$site_email}>\n";
				$headers   .= "MIME-Version: 1.0\n";
				$headers   .= "Content-Type: text/plain; charset=\"{$charset}\"\n";
				$subject    = '['.get_bloginfo('name').'] '.$subject;
				//return wp_mail($to, $subject, $message, $headers);
				$test = wp_mail($to, $subject, $message, $headers);
				 if ($test != true) { 
				 var_dump($test);
				 }
			}
			
			
			
			/**
	 * Sends notification emails when new comment is added to the post
	 * @param integer $comment_id
	 * @access public
	 */
	function sucCommentNotification($comment_id = 0) {
		global $wpdb;
		if ( is_user_logged_in() && $this->cbnetmcn_options['not_to_logged_users'] == 1 ) {
			return $comment_id;
		}
		if ( intval($comment_id) > 0 ) {
			$query = "SELECT t1.comment_post_ID,t1.comment_date,t1.comment_author,t1.comment_author_email,t1.comment_author_url,t1.comment_content,t1.comment_approved,
					  t2.ID,t2.post_title,t2.post_author FROM $wpdb->comments t1 INNER JOIN $wpdb->posts t2 ON t1.comment_post_ID=t2.ID 
					  WHERE comment_ID=$comment_id";
			$row = $wpdb->get_row($query, ARRAY_A);

			$pauth_fname = get_usermeta($row['post_author'],'first_name');
			$pauth_lname = get_usermeta($row['post_author'],'last_name');
			$pauth_name  = $pauth_fname.' '.$pauth_lname;
			$auth        = get_userdata($row['post_author']);
			$pauth_email = $auth->user_email;
		
		
			if ( trim($pauth_name) == '' ) {
				$pauth_name = $auth->user_nicename;
			}
			
			$subject  = "New Comment On: ".$row['post_title'];
			$message  = '';
			if ( $row['comment_approved'] == 'spam' ) {
				return $comment_id;
			} else if ( $row['comment_approved'] == 0 ) {
				$message .= "Note: This comment is under moderation\n\n";
			}
			$message .= "Updates On Service : \"".$row['post_title']."\n";
			$message .= "(Updates Posted By : ".$pauth_name.")\n\n";
			$message .= "Name: ".$row['comment_author']."\n";
			$message .= "Email: ".$row['comment_author_email']."\n";
			$message .= "URL: ".get_permalink($row['comment_post_ID'])."\n\n";
			$message .= "Updates Detail:\n\n".stripslashes($row['comment_content'])."\n\n\n";
			
/*			if ( $row['comment_approved'] != 1 ) {
				$message .= "Approve it: ".$this->cbnetmcn_siteurl."/wp-admin/comment.php?action=mac&c=".$comment_id."\n";
			}
*/			
			//  get the email list *** By Irsh ******* 
			$sql_email_list = "SELECT user_email FROM " . $dbtable_praefix . "$wpdb->users ";	
            $cbnetmcn_data = $wpdb->get_results($sql_email_list, ARRAY_A);
			
			
				if ( count($cbnetmcn_data) > 0 ) {
					foreach ( $cbnetmcn_data as $cbnetmcn_email ) {
					
				 	$to = trim($cbnetmcn_email[ user_email ]) ;
			       
				    $this->sucSendMail($to, $subject, $message);
					} 
				}
		}
	
	}    // sucCommentNotification End 
	
	
	/**
	 * Plugin's Options page
	 * Carries out all the operations in Options page
	 * @access public 
	 */
	function sucOptionsPg() {
		global $wpdb;
		$msg = '';

			$this->cbnetmcn_request = $_REQUEST['cbnetmcn'];
			if ( $this->cbnetmcn_request['save'] ) {
				$this->cbnetmcn_options['not_to_logged_users'] = $this->cbnetmcn_request['not_to_logged_users'];
				$this->cbnetmcn_options['disabled'] = $this->cbnetmcn_request['disabled'];
				foreach ( (array)$this->cbnetmcn_request['emails'] as $email ) {
					$cbnetmcn_emails .= ','.$email;
				}
				$cbnetmcn_emails = trim($cbnetmcn_emails,',');
				$this->cbnetmcn_options['emails'] = $cbnetmcn_emails;
				$this->cbnetmcn_options['additional_emails'] = trim($this->cbnetmcn_request['additional_emails'],',');
				update_option("multi_comment_notifications", $this->cbnetmcn_options);
				$msg = 'Options saved';
			}
			if ( trim($msg) !== '' ) {
				echo '<div id="message" class="updated fade"><p><strong>'.$msg.'</strong></p></div>';
			}
			$logged_users_chk = '';
			$disable_chk      = '';
			if ( $this->cbnetmcn_options['not_to_logged_users'] == 1 ) {
				$logged_users_chk = ' checked ';
			}
			if ( $this->cbnetmcn_options['disabled'] == 1 ) {
				$disable_chk = ' checked ';
			}
			$user_array = array();
			$query  = "SELECT ID FROM $wpdb->users";
			$result = $wpdb->get_results($query, ARRAY_A);
			foreach ( (array)$result as $key=>$row ) {
				$data = get_userdata($row['ID']);
				$data->wp_user_level = intval($data->wp_user_level);
				$userid   = $data->ID;
				$username = $data->user_login;
				$fullname = $data->first_name.' '.$data->last_name;
				$email    = $data->user_email;
				$level    = @key($data->wp_capabilities);
				$user_array[$data->wp_user_level][] = array($userid, $username, $fullname, $email, $level);
			}
			ksort($user_array, SORT_DESC);
			reset($user_array);
			$user_array = array_reverse($user_array, TRUE);
			?>
			<div class="wrap">
			 
			 <form method="post">
			 <p>
			 <h3><?php _e('Send service updates to the following users', 'cbnetmcn'); ?>:</h3>
			 <table border="0" width="80%" cellpadding="3" cellspacing="1">
			 <?php 
			 $last_user_level = '';
			 foreach ( (array)$user_array as $user_level=>$user_arr ) { 
			 ?>
				<tr><td colspan="4"><strong><?php echo ucfirst($user_arr[0][4]);?></strong></td></tr>
				<tr bgcolor="#dddddd">
				 <td width="3%"></td>
				 <td><strong><?php _e('Username', 'cbnetmcn'); ?></strong></td>
				 <td><strong><?php _e('Name', 'cbnetmcn'); ?></strong></td>
				 <td><strong><?php _e('E-mail', 'cbnetmcn'); ?></strong></td>
				</tr>
			 <?php
				foreach ( (array)$user_arr as $user_detail ) { 
					$user_chk = '';
					if ( isset( $user_detail[3] ) && strpos($this->cbnetmcn_options['emails'],$user_detail[3]) !== false ) {
						$user_chk = ' checked ';
					}
			 ?>
					<tr class="alternate">
					 <td><input type="checkbox" name="cbnetmcn[emails][]" value="<?php echo $user_detail[3];?>" <?php echo $user_chk;?> /></td>
					 <td><?php echo $user_detail[1];?></td>
					 <td><?php echo $user_detail[2];?></td>
					 <td><?php echo $user_detail[3];?></td>
					</tr>
			 <?php	
				}
			 } 
			 ?>
			 </table>
			 </p>
			 <p><br /><?php _e('Send new comment notification to the following E-mails as well: (separate multiple E-mails with comma)', 'cbnetmcn'); ?><br />
			 <input type="text" name="cbnetmcn[additional_emails]" value="<?php echo $this->cbnetmcn_options['additional_emails'];?>" size="85"></p>
			 <p><input type="checkbox" name="cbnetmcn[not_to_logged_users]" value="1" <?php echo $logged_users_chk;?> /> <?php _e('Don\'t send comment notification if registered user (admin, author, editor etc...) posts a comment', 'cbnetmcn'); ?></p>
			 <p><input type="checkbox" name="cbnetmcn[disabled]" value="1" <?php echo $disable_chk;?> /> <?php _e('Disable comment notification', 'cbnetmcn'); ?></p>
			 <p><input type="submit" name="cbnetmcn[save]" value="<?php _e('Save', 'cbnetmcn'); ?>" class="button" /></p>
			 </form>
			
			</div>
			<?php
		
		}  // sucOptionsPg   End 
			

}  // class End 


$suc_subscription = new suc_subscription();

?>