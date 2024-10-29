<?php /*
Plugin Name: Awesome Employee List
Author: Raihanul Islam
Version: 1.0 
Description: Standard and Easy to Use Employee List Plugin
*/



class Employee {
	public function __construct(){
		add_action('init', array($this, 'employee_default'));
		add_action('add_meta_boxes', array($this, 'employee_metabox'));
		add_action('save_post', array($this, 'employee_metabox_save'));
		add_action('admin_enqueue_scripts', array($this, 'jquery_ui') );
		add_shortcode('employee-list', array($this, 'employee_list_callback'));
		add_action('wp_enqueue_scripts',array($this,'script_emplloyee_area'));



	}
	
	function script_emplloyee_area () {
		wp_enqueue_script('bootstrp-js', PLUGINS_URL('js/bootstrap.min.js', __FILE__), array('jquery') );
		wp_enqueue_style('fontend', PLUGINS_URL('css/fontend.css', __FILE__));
		wp_enqueue_style('bootstrap-custom', PLUGINS_URL('css/bootstrap.min.css', __FILE__));
	}

	public function jquery_ui(){
		wp_enqueue_script('jquery-ui-tabs');
		wp_enqueue_script('employee_bootstrp', PLUGINS_URL('js/custom.js', __FILE__), array('jquery') );
		wp_enqueue_style('employee-custom', PLUGINS_URL('css/custom.css', __FILE__));
		
        
		
	}

	public function employee_default(){
		$labels = array(
			'name'               => _x( 'Employee', 'Employee Admin Menu Name', 'your-plugin-textdomain' ),
			'singular_name'      => _x( 'Employee', 'Employee Admin Menu singular name', 'your-plugin-textdomain' ),
			'menu_name'          => _x( 'Employee', 'admin menu', 'your-plugin-textdomain' ),
			'name_admin_bar'     => _x( 'Employee', 'add new on admin bar', 'your-plugin-textdomain' ),
			'add_new'            => _x( 'Add New', 'Employee', 'your-plugin-textdomain' ),
			'add_new_item'       => __( 'Add New Employee', 'your-plugin-textdomain' ),
			'new_item'           => __( 'New Employee', 'your-plugin-textdomain' ),
			'edit_item'          => __( 'Edit Employee', 'your-plugin-textdomain' ),
			'view_item'          => __( 'View Employee', 'your-plugin-textdomain' ),
			'all_items'          => __( 'All Employee', 'your-plugin-textdomain' ),
			'search_items'       => __( 'Search Employee', 'your-plugin-textdomain' ),
			'parent_item_colon'  => __( 'Parent Employee:', 'your-plugin-textdomain' ),
			'not_found'          => __( 'No Employee found.', 'your-plugin-textdomain' ),
			'not_found_in_trash' => __( 'No Employee found in Trash.', 'your-plugin-textdomain' )
		);

		$args = array(
			'labels'             => $labels,
	                'description'        => __( 'Employee list.', 'your-plugin-textdomain' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'employee' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon' 		 => 'dashicons-groups',
			'supports'           => array( 'title', 'editor', 'thumbnail' )
		);

		register_post_type( 'employee_list', $args );

		// employee types 

		$labels = array(
			'name'              => _x( 'Employee Types', 'taxonomy general name' ),
			'singular_name'     => _x( 'Employee Type', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Employee Types' ),
			'all_items'         => __( 'All Employee Types' ),
			'parent_item'       => __( 'Parent Employee Type' ),
			'parent_item_colon' => __( 'Parent Employee Type:' ),
			'edit_item'         => __( 'Edit Employee Type' ),
			'update_item'       => __( 'Update Employee Type' ),
			'add_new_item'      => __( 'Add New Employee Type' ),
			'new_item_name'     => __( 'New Employee Type Name' ),
			'menu_name'         => __( 'Employee Type' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'type' ),
		);

		register_taxonomy( 'employee_type', array( 'employee_list' ), $args );


	}

	public function employee_metabox(){
		// metabox for employees 
		
		add_meta_box('employee-info', 'Employee Information', array($this, 'employee_information'), 'employee_list', 'normal', 'high');
	}
	public function employee_information(){

		$value = get_post_meta(get_the_id(), 'employee-info', true);

		?>

		
		<?php 
			$father = get_post_meta(get_the_id(), 'employee_father', true);
			$mother = get_post_meta(get_the_id(), 'employee_mother', true);
			$gender = get_post_meta(get_the_id(), 'employee_gender', true);
			$designation = get_post_meta(get_the_id(), 'employee_designation', true);
			
			$skills = get_post_meta(get_the_id(), 'employee_skills', true);
		?>


		<div id="tabs">
		  <ul>
		    <li><a href="#personal">Personal Information</a></li>
		    <li><a href="#official">Official Information</a></li>
		    <li><a href="#experience">Experience</a></li>
		  </ul>
		  <div id="personal">
		    <p><label for="father">Father's Name</label></p>
		    <p><input type="text" name="father" value="<?php echo $father; ?>" id="father"></p>
		    <p><label for="mother">Mother's Name</label></p>
		    <p><input type="text" name="mother" value="<?php echo $mother; ?>" id="mother"></p>

		    <p>
		    	<input type="radio" name="gender" value="male" id="male" <?php if($gender == 'male'){echo "checked";} ?>>
		    	<label for="male"> Male</label> <br>
		    	<input type="radio" name="gender" value="female" id="female" <?php if($gender == 'female'){echo "checked";} ?>>
		    	<label for="female"> Female</label>
		    </p>
		  </div>


		  <div id="official">
		    <p><label for="designation">Designation</label></p>
		    <p><input type="text" name="designation" value="<?php echo $designation; ?>" id="designation"></p>
		  </div>


		 


		  <div id="experience">
		    <p><label for="skills">Skills:</label></p>
		    <p><input type="text" name="skills" value="<?php echo $skills; ?>" id="skills"></p>
		  </div>

		</div>
		<?php 
	}

	public function employee_metabox_save($post_id){

		$father = $_POST['father'];
		$mother = $_POST['mother'];
		$gender = $_POST['gender'];
		$designation = $_POST['designation'];
		
		$skills = $_POST['skills'];

		update_post_meta(get_the_id(), 'employee_father', $father);

		update_post_meta(get_the_id(), 'employee_mother', $mother);

		update_post_meta(get_the_id(), 'employee_gender', $gender);

		update_post_meta(get_the_id(), 'employee_designation', $designation);

		update_post_meta(get_the_id(), 'employee_skills', $skills);

	}


	public function employee_list_callback($attr, $content){
		ob_start();

		$atts = shortcode_atts(array(
			'count' => -1
		), $attr);

		extract($atts);
		?>
			
			
				<?php 

				if( get_query_var('paged') ){
					$current_page = get_query_var('paged');
				}else {
					$current_page = 1;
				}

					$employee = new WP_Query(array(
						'post_type' => 'employee_list',
						'posts_per_page' => $count,
						'paged' => $current_page
					));

				while($employee->have_posts()) : $employee->the_post();
				?>
				
				
						
				
				
				
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 sample">
    <div class="hovereffect">
        <?php the_post_thumbnail();?>
        <div class="overlay">
           <h2><?php the_title();?></h2>
		   <div class="employee-details">
						
						<p><strong>Designation: </strong><?php echo get_post_meta( get_the_id(), 'employee_designation', true ); ?></p>

						<p><strong>About The Employee: </strong><?php the_content(); ?></p>

						<p><strong>Father's Name: </strong><?php echo get_post_meta( get_the_id(), 'employee_father', true ); ?></p>

						<p><strong>Mother's Name: </strong><?php echo get_post_meta( get_the_id(), 'employee_mother', true ); ?></p>

						<p><strong>Gender: </strong><?php echo get_post_meta( get_the_id(), 'employee_gender', true ); ?></p>

					

						<p><strong>Skills: </strong><?php echo get_post_meta( get_the_id(), 'employee_skills', true ); ?></p>
			</div
        </div>
    </div>
</div>
</div>
				
				
				
				
				
				
				
				
				
				
				
				
				<?php endwhile; ?>

				<?php 

				

					echo paginate_links(array(
						'current' => $current_page,
						'total' => $employee->max_num_pages,
						'prev_text' => 'Previous Page',
						'next_text' => 'Next Page',
						'show_all' => true
					));

					
				?>
			

		<?php return ob_get_clean();
	}


	public function visual_composer_support(){
		if(function_exists('vc_map')){
			vc_map(array(
				'name' => 'Employee List',
				'base' => 'employee-list',
				'id' => 'employee-list',
				'params' => array(
					array(
						'heading' => 'How Many Employee to show',
						'param_name' => 'count',
						'type' => 'textfield',
					)
				)
			));
		}
	}


}


$employee = new Employee();


$employee->visual_composer_support();