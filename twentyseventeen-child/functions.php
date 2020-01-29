<?php

class customRegistrationSystem{
			/**
	 		* class constructor holding all actions
	 		*/
		public function __construct(){
				add_action( 'wp_enqueue_scripts', array( $this,'childtheme_enqueue_styles') );
				add_action( 'wp_enqueue_scripts', array( $this,'custom_enqueue_style_script') );
				add_shortcode('user_register_form',array( $this,'user_register_form') );
				add_action( "wp_ajax_user_registration_ajax_func", array( $this,"user_registration_ajax_func") );
				add_action( "wp_ajax_nopriv_user_registration_ajax_func",array( $this, "user_registration_ajax_func") );
		}
			/**
	 		* Enqueue parent scripts
	 		*/
		public function childtheme_enqueue_styles() {
		    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
		    wp_enqueue_style( 'child-style',
		        get_stylesheet_directory_uri() . '/style.css',
		        array('parent-style')
		    );
		}
			/**
	 		* Enqueue custom scripts
	 		*/

		public function custom_enqueue_style_script(){
			wp_enqueue_style( 'bootstrap-style', get_stylesheet_directory_uri() . '/assets/css/bootstrap.min.css');
		    wp_enqueue_script( 'bootstrap-script',
		        get_stylesheet_directory_uri() . '/assets/js/bootstrap.min.js',array(),'1.0.0',true);
		    wp_enqueue_script( 'jquery-validate-js',
		        get_stylesheet_directory_uri() . '/assets/js/jquery.validate.js',array(),'1.0.0',true);
		    wp_enqueue_script( 'additional-methods-min-js',
		        get_stylesheet_directory_uri() . '/assets/js/additional-methods.min.js',array(),'',true);
    		wp_register_script( 
			'ajax_object', 
			get_stylesheet_directory_uri() . '/assets/js/custom-js.js', 
			array('jquery'), 
			'', 
			true 
			);
	    	wp_enqueue_script( 'ajax_object' );
			wp_localize_script( 
			'ajax_object', 
			'ajax_object', 
				array( 
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'ajax_nonce' => wp_create_nonce('create_user_reg')
					
				)
			);
			wp_enqueue_style( 'custom-style', get_stylesheet_directory_uri() . '/assets/css/custom-css.css');

		}
			/**
	 		* Shortcode for User Registration Form
	 		*/
		public function user_register_form(){
			ob_start();
			if (is_user_logged_in()) : 
			    ?>
			    <a role="button" href="You are already logged in <?php echo wp_logout_url('/'); ?>">Log Out</a>

			<?php 
				else : 
			?>		<div class="custom-reg_loader_wrp">
						<img class="custom-reg_loader" style="display:none;" src="<?php echo  get_stylesheet_directory_uri() .'/assets/images/spinningwheel.gif'?>">
					</div>
					<div class="custom_popup_alert">
					    <div class="custom-dailog">
					      <div class="custom-dailog-content">
					        <div class="custom-dailog-header">
					          <h4 class="custom-dailog-title"></h4>
					        </div>
					        <div class="custom-dailog-body">
					          
					        </div>
					      </div>
					    </div>
					</div>
					<form id="user_reg_form" class="text-center border border-light p-5 user_reg_form" method="post" action="JavaScript:Void(0)">
						
						<p class="h4 mb-4">Sign in</p>
					    <input type="text"  class="form-control user_name_class" name="user_name" placeholder="User Name">
					    <input type="email" class="form-control user_email_class" name="user_email"  placeholder="E-mail">
					    <input type="password" id="confirm_pass"  class="form-control user_password_class" name="user_password" placeholder="Password">
						<input type="password"  class="form-control user_password_confirm_class" name="user_password_confirm" placeholder="Confirm Password">
					    <button class="btn btn-info btn-block user_registration_submit" type="submit">Submit</button>

					</form>
						<?php 
				endif;
					return ob_get_clean();
		}
			/**
	 		* Ajax Function 
	 		*/
		public function user_registration_ajax_func(){
			/**
			* we check the WordPress AJAX nonce
			*/
			check_ajax_referer( 'create_user_reg', 'security' );

			$user_name 		= sanitize_text_field($_POST['user_name']);
			$user_password 	= sanitize_text_field($_POST['user_password']);
			$user_email 	= sanitize_email($_POST['user_email']);
			$user_pass 		= wp_generate_password(12, false);
			if(email_exists( $user_email )){
				echo "user email already in use please try something new";
			}
			else{
				$user_id = wp_insert_user(
					array(
						'user_email' => $user_email,
						'user_login' => $user_name,
						'user_pass'  => $user_pass,
						'first_name' => $user_name
					)
				);
				if ($user_id !== '') {
						$to = $user_email;
						$subject = "Hi " . $user_name . ", welcome to our site!";
						$body = '
						<h1>Dear ' . $user_name . ',</h1></br>
						<p>Thank you for joining our site. Your account is now active.</p>
						<p>Username:- '.$user_email.'</p><br>
						<p>Password:- '.$user_pass.'</p><br>
						<p>Please go ahead and navigate around your account.</p>
						<p>Login Url:- '.wp_login_url().'</p>
						<p>Let me know if you have further questions, I am here to help.</p>
						';
						$headers = array('Content-Type: text/html; charset=UTF-8');
						wp_mail($to, $subject, $body, $headers);
						echo 'You have successfully Register';

					}
			}
			wp_die();
		}

}

$customRegistrationSystem = new customRegistrationSystem();



