<?php
 /*

 Plugin Name: newsplug
 Description: this plugin is for newsletters compaigns.
 Version: 1.0
 Author: Wali Systems Inc.,Muhammad Bilal
 Tags: Newsletter, Newsletter Plugin, Wordpress Newsletter, Newsletter Comapigns
  Author URI: http://www.walisystemsinc.com 
 */


 function newsplug_style() {
    wp_enqueue_style( 'news_boostrap',  plugin_dir_url( __FILE__ ) .'css/bootstrap.min.css' );
    
    // wp_enqueue_style( 'news_boostrap',  plugins_url( 'css/bootstrap.min.css',__FILE__ ));
    // wp_enqueue_script( 'updateform', plugins_url( 'js/updateform.js', __FILE__ ));
}
add_action( 'admin_init', 'newsplug_style' ); 
 function newsplug_dragstyle() {
    wp_enqueue_style( 'news_drag',  plugin_dir_url( __FILE__ ) .'css/drag.css' );
    
    // wp_enqueue_style( 'news_boostrap',  plugins_url( 'css/bootstrap.min.css',__FILE__ ));
    // wp_enqueue_script( 'updateform', plugins_url( 'js/updateform.js', __FILE__ ));
}
add_action( 'admin_init', 'newsplug_dragstyle' ); 

 function newsplug_getedit($id,$subj){?>
			<form action="" method="post">
		<label>subject *:</label><br>
		<input type="text" name="upd_subj" required="required" value = "<?php echo $subj;?>" size="30"/>
				<br>
		<label>body *:</label><br><br>
		<input type="hidden" name="updid" value = "<?php echo $id;?>">


		<?php global $wpdb;
		$myrows = $wpdb->get_results( "SELECT mail_area FROM wp_storemail WHERE id='".$id."'");
		$des = null;
		foreach ( $myrows as $myrow )
		{
			$des .= stripcslashes($myrow->mail_area);
		}
		wp_editor($des, 'upd_body', 'settings_wpeditor' ); ?>
		<input type="submit" value= "update template" name="update_temp" size="20">
		<br>
		</form>


		<?php
		if(isset($_POST['upd_subj'])&&isset($_POST['upd_body'])&&isset($_POST['updid'])){ 
                             $subject= sanitize_text_field( $_POST['upd_subj'] );
                              $updid = intval($_POST['updid']);
                                if ( ! $updid ) {
                                  $updid = '';
                                }	

                  global $wpdb;
			$table_name = $wpdb->prefix . 'storemail';

			$args = array(

				'subj'=>$subject,
				'mail_area'=>$_POST['upd_body']
				);
				$cond=array( 'id' => $updid);

			$return = $wpdb->update('wp_storemail', $args, $cond);
			echo $return;
			}
		}

		//forms comment

		function newsplug_form_init(){
					global $wpdb;
				$edit_id='';
       if(isset($_GET['create']) && $_GET['create']!==''){
	$id = $_GET['create'];
}
       if(isset($_GET['edit']) && $_GET['edit']!==''){
	$edit_id = $_GET['edit'];
}
if($edit_id!=''){
	  echo "<center><h1>Edit Form!</h1><center>";

			?>


			<?php $form_name= $wpdb->get_results("SELECT form_name FROM wp_form where id = $edit_id;");
			$form_name = json_decode(json_encode($form_name), true);

			?>
			<form method="post" action="">

			<center>Form Name: <input type="text" value="<?php echo $form_name[0]['form_name'] ?>" class="form_name" name="form_nam"><center><br>

			<div id="div1" class='droppable'>
			<?php
			$class_name='';
			$last_order=0;
			$created_elements = $wpdb->get_results("SELECT wp_created_form.form_id, wp_created_form.field_id,wp_created_form.value,wp_created_form.element_order,wp_created_form.name,wp_form_elements.id,wp_form_elements.tag_name,wp_form_elements.tag_end,wp_form_elements.type
FROM wp_created_form
INNER JOIN wp_form_elements
ON wp_created_form.field_id=wp_form_elements.id
and wp_created_form.form_id=$edit_id
order by element_order ASC;");
			

			foreach($created_elements as $element){
		//i am here now
		if ($element->field_id=='2')
			$class_name='text_class';
		    //$order=
		elseif($element->field_id=='3')
		$class_name='email_class';
		elseif($element->field_id=='1')
		$class_name='submit_class';
			if($element->type!='submit'){
			echo "<div style='position: relative; z-index: 3; width: 269px; right: auto; height: 29px; bottom: auto; top: 20px; left: 5px; float: left;' ><$element->tag_name class='$class_name' type='$element->type' value='$element->value' unique-data='$element->id' name='$element->type[]' order='$element->element_order' ></div><br>";

			$last_order=$element->element_order;
			}
			$class_name='';
			}

			?>
			<input class="submit_class" style="visibility:hidden" type="submit" unique-data="1" name="submit[]" value="Submit" order='999'>
			</div>

			<div>
			<input type="button" last_order='<?php echo $last_order ?>' form-id="<?php echo $edit_id ?>" class="update_button"  value="Save">
			<?php
			$cancel_url= parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
			echo "<input type='button' name='cancel' value='cancel' onclick=location.href='http://$_SERVER[SERVER_NAME]$cancel_url?page=third'>" ?>
			</div>
			</form>

			<div class="dragg-element" style="margin-left:900px;    margin-top: -308px;">


			<?php

			$elements = $wpdb->get_results("SELECT * FROM wp_form_elements;");

echo "<span> <h3>Draggable Elements </h3></span><br>";
foreach($elements as $element){
	if($element->type!='submit')
echo " $element->type<div class='draggable'><$element->tag_name   type='$element->type' unique-data='$element->id' name='$element->type[]' readonly ></div><br>";
//echo "<button>$element->type</button><br>";
}
	//wp_enqueue_script( 'updateform', plugins_url( 'js/updateform.js', __FILE__ ));
 wp_enqueue_script( 'updateform',  plugin_dir_url( __FILE__ ) .'js/updateform.js' );
?>

		</div>
		<?php
}
	  else if($id==1){



		    echo "<center><h1>New Form!</h1><center>";
			
			$myrows = $wpdb->get_results( "SELECT id,name FROM wp_lists" );
			
		
		echo '<h4>SELECT LIST </h4> <select name="lff" class="list_class" required>';
		//echo '<option value="0" >"select"</option>';
		foreach ( $myrows as $myrow ){
			echo '<option value="'.$myrow->id.'">'.$myrow->name.'</option>';
		}
				echo '</select>';
		$mythem = $wpdb->get_results( "SELECT id,subj FROM wp_storemail" );
	

		?>

		<!--	<style>
			.draggable {
  width: auto;
  height: auto;

 border: 5px solid transparent
}
.droppable {
  width: 450px;
  height: 250px;
  background: lightgrey;
  margin: 5px;
  padding: 5px;
 display:inline-block;
 padding: 10px;border: 1px solid #aaaaaa;

}
			</style> -->

			<form method="post" action="">
			<center>Form Name: <input type="text"  class="form_name" name="form_nam"required="required"><center><br>

			<div id="div1" class='droppable'> <input class="submit_class" style="visibility:hidden" type="submit" unique-data="1" name="submit[]" value="Submit" order='999'></div>

			<div>
			<input type="button" class="save_button btn btn-primary"  value="Save">
			<?php
			$cancel_url= parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
			echo "<input type='button' name='cancel' value='cancel' onclick=location.href='http://$_SERVER[SERVER_NAME]$cancel_url?page=third' class='btn btn-primary'>" ?>
			</div>
			</form>

			<div class="dragg-element" style="margin-left:900px;    margin-top: -308px;">


			<?php

			$elements = $wpdb->get_results("SELECT * FROM wp_form_elements;");
				//print_r($elements);
echo "<span> <h3>Draggable Elements </h3></span><br>";
foreach($elements as $element){
	if($element->type!='submit')
echo " $element->type<div class='draggable'><$element->tag_name   type='$element->type' unique-data='$element->id' name='$element->type[]' readonly ></div><br>";
//echo "<button>$element->type</button><br>";
}



/*function Zumper_widget_enqueue_script()
{   
    wp_enqueue_script( 'my_custom_script', plugin_dir_url( __FILE__ ) . 'js/jquery.repeatable.js' );
}
add_action('admin_enqueue_scripts', 'Zumper_widget_enqueue_script');*/
//wp_enqueue_script( 'saveform', plugins_url( 'js/saveform.js', __FILE__ ));
 wp_enqueue_script( 'saveform',  plugin_dir_url( __FILE__ ) .'js/saveform.js' );
//plugins_url( 'js/saveform.js', __FILE__ )
			?>


		</div>
		<?php

	   }
	   else{

		//wp_enqueue_script( 'deleteform', plugins_url( 'js/deleteform.js', __FILE__ ));
                    wp_enqueue_script( 'deleteform',  plugin_dir_url( __FILE__ ) .'js/deleteform.js' );
	   echo "<center><h1>Forms!</h1><center>";
	   $myrows = $wpdb->get_results( "SELECT id,name FROM wp_lists" );
	   //$mythem = $wpdb->get_results( "SELECT id,subj FROM wp_storemail" );
	  
	  //echo plugins_url( 'js/deleteform.js', __FILE__ );
	   if (count($myrows)> 0)
		   {
			   //
			   echo $myrows->id;
		echo "<Button onclick=location.href='http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]&create=1' style='margin-left: 900px;'class='btn btn-primary'>Add New Form</Button>";}
		   else if(count($myrows)< 0){
			   echo '<h3> You can not add new form add list first </h3>'; 
		   }
		   
	   echo "<center><table border=1 style='width:100%;'>
        <tr>
        <th class='text-center'>Name</th>
        <th class='text-center'>Created </th>
		<th class='text-center'>Short Code </th>
        <th class='text-center'>Actions</th>

        </tr>";
	   global $wpdb;
         $result = $wpdb->get_results ( "SELECT * FROM wp_form" );
	//echo plugin_dir_url( __FILE__ );	   
//echo plugins_url( 'js/saveform.js', __FILE__ );		
		foreach ( $result as $print )   {
    ?>
    <tr>
    <td class="text-center"><?php echo $print->form_name;?></td>
	<td class="text-center"><?php echo $print->created_date;?></td>
	<td class="text-center"><?php echo $print->short_code;?></td>
	<td class="text-center"> <?php echo "<Button onclick=location.href='http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]&edit=$print->id' class='btn btn-warning'>Edit</Button>" ?>
	<button id="<?php echo $print->id ?>" class="del_form btn btn-danger" value="1" >Delete</button></td>
    </tr>
	
	<?php
		   }
		   
	  echo ' <a href='.get_admin_url().'admin.php?page=fourth&act=edit class="btn btn-default"> Add NEW LIST </a>'; 
	
	   }
	?>
	  
	  	
	
	<br>   
	  <br>
<?php 
}

		//forms comment
		function newsplug_create_form(){
					global $wpdb;


	$version = get_option( 'newsplug_version', '1.0' );
	$table_name = $wpdb->prefix . 'created_form';

	$sql = "CREATE TABLE $table_name (
		id int NOT NULL AUTO_INCREMENT,
		form_id int NOT NULL,
		field_id int NOT NULL,
		value TEXT,
		element_order int,
		name varchar(10),
		PRIMARY KEY (id)
	)ENGINE = InnoDB;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	if ( version_compare( $version, '2.0' ) < 0 ) {
		$sql = "CREATE TABLE $table_name (
		id int NOT NULL AUTO_INCREMENT,
		form_id int NOT NULL,
		field_id int NOT NULL,
		value TEXT,
		element_order int,
		name varchar(10),
		PRIMARY KEY (id)
	)ENGINE = InnoDB;";
		}

		}

		//db form
		function newsplug_form(){
					global $wpdb;

$version = get_option( 'newsplug_version', '1.0' );
	$table_name = $wpdb->prefix . 'form';

	$sql = "CREATE TABLE $table_name (
		id int NOT NULL AUTO_INCREMENT,
		form_name text,
		created_date datetime,
		template int,
		short_code text
		)ENGINE = InnoDB;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	if ( version_compare( $version, '2.0' ) < 0 ) {
	$sql = "CREATE TABLE $table_name (
		id int NOT NULL AUTO_INCREMENT,
		form_name text,
		created_date datetime,
		template int,
		short_code text
		)ENGINE = InnoDB;";
		}

		}

		// form element 
		function newsplug_form_elements(){
					global $wpdb;


	$version = get_option( 'newsplug_version', '1.0' );
	$table_name = $wpdb->prefix . 'form_elements';

	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id int NOT NULL AUTO_INCREMENT,
		tag_name varchar(20),
		tag_end varchar(20),
		type varchar(20),
		PRIMARY KEY (id)
	)ENGINE = InnoDB;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	if ( version_compare( $version, '2.0' ) < 0 ) {
	$sql = "CREATE TABLE $table_name (
		id int NOT NULL AUTO_INCREMENT,
		tag_name varchar(20),
		tag_end varchar(20),
		type varchar(20),
		PRIMARY KEY (id)
	)ENGINE = InnoDB;";
		}
$xyz=$wpdb->get_results ( "SELECT `type` FROM `wp_form_elements` WHERE `id`=1");
if($xyz[0]->type!='submit'){
$args = array(
				'id' => NULL,
				'tag_name' =>'input',
				'tag_end' => 'input',
				'type' => 'submit'
				);
				$wpdb->insert('wp_form_elements', $args);
}
//name
$xyz=$wpdb->get_results ( "SELECT `type` FROM `wp_form_elements` WHERE `id`=2");
if($xyz[0]->type!='text'){
$args = array(
				'id' => NULL,
				'tag_name' =>'input',
				'tag_end' => 'input',
				'type' => 'text'
				);
				$wpdb->insert('wp_form_elements', $args);
}
//email
$xyz=$wpdb->get_results ( "SELECT `type` FROM `wp_form_elements` WHERE `id`=3");
if($xyz[0]->type!='email'){
$args = array(
				'id' => NULL,
				'tag_name' =>'input',
				'tag_end' => 'input',
				'type' => 'email'
				);
				$wpdb->insert('wp_form_elements', $args);
				
				}
		}
//Email Template Store
 function newsplug_dummy(){
    global $wpdb;


	$version = get_option( 'newsplug_version', '1.0' );
	$table_name = $wpdb->prefix . 'storemail';

	$sql = "CREATE TABLE $table_name (
		id int NOT NULL AUTO_INCREMENT,
		created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
		subj varchar(25) NOT NULL,
		mail_area TEXT NOT NULL,
		UNIQUE KEY id (id)
	)ENGINE = MYISAM;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	if ( version_compare( $version, '2.0' ) < 0 ) {
	$sql = "CREATE TABLE $table_name (
		id int NOT NULL AUTO_INCREMENT,
		created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
		subj varchar(25) NOT NULL,
		mail_area TEXT NOT NULL,
		UNIQUE KEY id (id)
	)ENGINE = MYISAM;";
		}
	}
	//COMPAIGN table
	function newsplug_campaign_table(){
	global $wpdb;
  	$version = get_option( 'newsplug_version', '1.0' );
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix .'campaign';

	$sql = "CREATE TABLE $table_name (
		id int(9) NOT NULL AUTO_INCREMENT,
		name varchar(25) NOT NULL,
		add_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
		statu char(1) NOT NULL,
		start_date DATE,
		start_time TIME,
		theme_id int,
		list_id int,
		FOREIGN KEY (list_id) REFERENCES wp_lists(id),
		
		PRIMARY KEY id (id)
	) $charset_collate;";
//FOREIGN KEY (theme_id) REFERENCES wp_storemail(id),
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	
	if ( version_compare( $version, '2.0' ) < 0 ) {
	$sql = "CREATE TABLE $table_name (
		id int(9) NOT NULL AUTO_INCREMENT,
		name varchar(25) NOT NULL,
		add_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
		statu char(1) NOT NULL,
		start_date DATE,
		start_time TIME,
		theme_id int,
		list_id int,
		FOREIGN KEY (list_id) REFERENCES wp_lists(id),

		PRIMARY KEY id (id)
	) $charset_collate;";
	}
}
//List TABLE
function newsplug_list_table(){
	global $wpdb;
  		$version = get_option( 'newsplug_version', '1.0' );
	//$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'lists';

	$sql = "CREATE TABLE $table_name (
		id int(9) NOT NULL AUTO_INCREMENT,
		created TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
		name varchar(25) NOT NULL,
		wsubj varchar(25) NOT NULL,
		wbody TEXT NOT NULL,
		PRIMARY KEY (id)
		)ENGINE = InnoDB;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	
	if ( version_compare( $version, '2.0' ) < 0 ) {
		$sql = "CREATE TABLE $table_name (
		id int(9) NOT NULL AUTO_INCREMENT,
		created TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
		name varchar(25) NOT NULL,
		wsubj varchar(25) NOT NULL,
		wbody TEXT NOT NULL,
		PRIMARY KEY (id)
		)ENGINE = InnoDB;";

		}
	}
	
function newsplug_chepy(){
   	global $wpdb;
$sql='ALTER TABLE `wp_subscribers`
ADD FOREIGN KEY (`store_id`)
REFERENCES wp_storemail(id)';
$wpdb->query($sql);

}
	function newsplug_form_databas() {
   	global $wpdb;
  	 $your_db_name=$wpdb->prefix . 'form';
     $your_db_name2=$wpdb->prefix . 'form_elements';
	// create the ECPT metabox database table
	if($wpdb->get_var("show tables like '$your_db_name'") != $your_db_name)
	{
		$sql = "CREATE TABLE " . $your_db_name . " (
		`id` mediumint(9) NOT NULL AUTO_INCREMENT,
		`form_name` mediumtext NOT NULL,
		`created_date` DATETIME  NOT NULL,
		`short_code` tinytext NOT NULL,
		 UNIQUE KEY id (id)
		);";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

}
//subscribers
function newsplug_subscribers_table(){
	global $wpdb;
  		$version = get_option( 'newsplug_version', '1.0' );
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'subscribers';
	//echo 'subscribers table inside';
	$sql = "CREATE TABLE $table_name (
		id int(9) NOT NULL AUTO_INCREMENT,
		created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		name varchar(25) NOT NULL,
		email varchar(25) NOT NULL,
		stat char(1) NOT NULL,
		sub_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		store_id int,
		list_id int,
		FOREIGN KEY (`list_id`) REFERENCES wp_lists(id),
		PRIMARY KEY (id)
	)$charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	//echo 'dbDelta execute';
	if ( version_compare( $version, '2.0' ) < 0 ) {
	$sql = "CREATE TABLE $table_name (
		id int(9) NOT NULL AUTO_INCREMENT,
		created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		name varchar(25) NOT NULL,
		email varchar(25) NOT NULL,
		stat char(1) NOT NULL,
		sub_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		store_id int DEFAULT 20,
				list_id int,
						FOREIGN KEY (`store_id`) REFERENCES wp_storemail(id),
		FOREIGN KEY (`list_id`) REFERENCES wp_lists(id),
	PRIMARY KEY (id)
	)$charset_collate;";		}
	}

//CRUD
wp_enqueue_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js');
wp_enqueue_script( 'jquery-ui', 'https://code.jquery.com/ui/1.11.4/jquery-ui.js');

//wp_enqueue_script( 'custom', plugins_url( 'custom.css', __FILE__ ));

add_action('wp_ajax_nopriv_saveform_request','newsplug_saveform_request');
add_action('wp_ajax_saveform_request','newsplug_saveform_request');

function newsplug_saveform_request()
{
global $wpdb;

$form_name = sanitize_text_field( $_POST["form_name"] );
   //$form_name= $_POST["form_name"];
$list_id = intval( $_POST['list_id'] );
if ( ! $list_id ) {
  $list_id = '';
}  
//$list_id=$_POST['list_id'];



$date = date("Y-m-d H:i:s");
$table_name = $wpdb->prefix . "form";
$wpdb->insert( $table_name, array(
    'form_name' => $form_name,
    'created_date' => $date,

) );
 $lastid = $wpdb->insert_id;

$wpdb->update(
    $table_name,
    array(
         'short_code' => "[myforms formid='$lastid' listid='$list_id']"  // string

    ),
    array( 'id' => $lastid  )
);

 //echo $lastid;
 $table_name = $wpdb->prefix . "created_form";
//$data = sanitize_text_field( $_POST['data'] );	
 //$data = $_POST['data'];
	
	if(isset($_POST['data']))
	{
 //           $data = sanitize_text_field( $_POST['data'] );
		//echo $table_name;

		foreach($data as $key=>$val)
		{
			$aarah = explode("_", $val);

	$wpdb->insert( $table_name, array(
    'form_id' => $lastid,
    'field_id' => $aarah[2],
	'value' => $aarah[1],
	'element_order' => $aarah[3],
//	'name' => $aarah[4]
) );

		}
	}

 wp_die();
   /*
   $data=json_decode($_POST['data']);
   //echo $data;
   print_r (explode(" ", $data));

   ;*/
}


add_action('wp_ajax_nopriv_updateform_request','newsplug_updateform_request');
add_action('wp_ajax_updateform_request','newsplug_updateform_request');

function newsplug_updateform_request()
{
global $wpdb;


   //$form_name= $_POST["form_name"];
   //$form_id= $_POST["form_id"];
$form_name = sanitize_text_field( $_POST["form_name"] );
$form_id = intval( $_POST["form_id"] );
if ( ! $form_id ) {
  $form_id = '';
}  
$date = date("Y-m-d H:i:s");
$table_name = $wpdb->prefix . "form";
$wpdb->update(
    'wp_form',
    array(
        'form_name' => $form_name,  // string

    ),
    array( 'id' =>  $form_id )
);
 //$lastid = $wpdb->insert_id;
 //echo $lastid;

 $wpdb->query(
              'DELETE  FROM '.$wpdb->prefix.'created_form
               WHERE form_id = "'.$form_id.'"'
);

 $table_name = $wpdb->prefix . "created_form";
	//$data = $_POST['data'];
        $data = sanitize_text_field( $_POST['data'] );	
	if(isset($_POST['data']))
	{
            $data = sanitize_text_field( $_POST['data'] );	
		foreach($data as $key=>$val)
		{
			$aarah = explode("_", $val);
			//echo $aarah[3];
			//print_r($aarah);
	$wpdb->insert( $table_name, array(
    'form_id' => $form_id,
    'field_id' => $aarah[2],
	'value' => $aarah[1],
	'element_order' => $aarah[3],

) );

		}
	}

 wp_die();
   /*
   $data=json_decode($_POST['data']);
   //echo $data;
   print_r (explode(" ", $data));

   ;*/
}
add_action('wp_ajax_nopriv_delete_form','newsplug_delete_form');
add_action('wp_ajax_delete_form','newsplug_delete_form');

function newsplug_delete_form()
{
global $wpdb;


   //$form_id= $_POST["form_id"];

$form_id = intval( $_POST["form_id"] );
if ( ! $form_id ) {
  $form_id = '';
}


$table_name = $wpdb->prefix . "form";
 $wpdb->query(
              'DELETE  FROM '.$wpdb->prefix.'form
               WHERE id = "'.$form_id.'"'
);


 wp_die();
   /*
   $data=json_decode($_POST['data']);
   //echo $data;
   print_r (explode(" ", $data));

   ;*/
}

//END

//subscribers
function newsplug_subscribers_func(){
global $wpdb;
//ob_start();		
if(isset($_GET['umi'])&& isset($_GET['emt'])){
$emt = intval( $_GET['emt'] );
if ( ! $emt ) {
  $emt = '';
}
$umi = intval( $_GET['umi'] );
if ( ! $umi ) {
  $umi = '';
}
    global $wpdb;
			$table_name = $wpdb->prefix . 'subscribers';
			
	$myrows = $wpdb->get_results( "SELECT `subj`,`mail_area` FROM `wp_storemail` WHERE id=".$emt );

		$to = $umi;
    $subject = null;
    	$des = null;
		foreach ( $myrows as $myrow )
		{
			$subject=$myrow->subj;
			$des .= stripcslashes($myrow->mail_area);
		}
		$body = $des;
			echo $subject;
			echo '<br></br>';
			echo $to;
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

	if(mail($to, $subject, $body,$headers))
    {
        echo ("<p>Email send successfully </p>");
 	}
    else {
        echo ("<p>email sending fail</p>");
		}
	
}
//delete spam user
if(isset($_GET['deluser'])){
$deluser = intval( $_GET['deluser'] );
if ( ! $deluser ) {
  $deluser = '';
}
    global $wpdb;
			$table_name = $wpdb->prefix . 'subscribers';
			$args = array(
			 'id' => $deluser,
				);
			$wpdb->delete($table_name,$args);
}
//
if(isset($_GET['n'])&& isset($_GET['un']))
		{
                        $sid = intval( $_GET['n'] );
                            if ( ! $sid ) {
                          $sid = '';
                        }
                        
                          $con = intval( $_GET['un'] );
                            if ( ! $con ) {
                          $con = '';
                        }
			//echo get_admin_url();
		global $wpdb;
			

			$args = array(

				'store_id'=>$sid,
				
				);
				$cond=array( 'id' => $con );

			$return = $wpdb->update('wp_subscribers', $args, $cond);
	//		$_GET['m']=null;
//			newsplug_subscribers_func();
			
		}

//
if(isset($_GET['m'])){
	//$alph=$_GET['m'];
                        $alph = intval( $_GET['m'] );
                            if ( ! $alph ) {
                          $alph = '';
                        }
	$alp = $wpdb->get_results( "SELECT subj,id FROM wp_storemail" );
		foreach ( $alp as $dod ){
		//echo '<td class="text-center"><a href='.get_admin_url().'admin.php?page=users&n='.$dod->id.' class="button">'.$dod->subj.'</td>';
			//echo '<form action='.get_admin_url().'admin.php?page=users  method="GET"><input type="hidden" value='.$dod->id.' name="n"/><input type="hidden" value='.$_GET['m'].' name="m"/><input type="submit" value='.$dod->subj.'></form>';
			//echo '<button onclick='.cli($dod->id).'>'.$dod->subj.'</button>';
			echo '<a href='.get_admin_url().'admin.php?page=users&n='.$dod->id.'&un='.$alph.' class="button">'.$dod->subj.'</a>';
			echo '<br>';
			}

//for loop end

		}
		
		else{
	$i=1;
?>
	<!---bootstap --->
	
<?php


//plugins_url( 'custom.css', __FILE__ )
//echo plugin_dir_url(__FILE__).'css/bootstrap.min.css';//.'newsplug/css';
	echo '<div style="overflow-x:auto;">';
	echo '<table class="table table-striped">
	<tr>
    <th class="text-center">Serial</th>
	<th class="text-center">Subscriber Name</th>
	<th class="text-center">Subscriber Email</th>
	<th class="text-center"> Delete user </th>
	</tr>';
//<th class="text-center">current template</th><th class="text-center"> Email template </th><th class="text-center"> MAIL </th>	
$abc = $wpdb->get_results( "SELECT subj FROM wp_storemail" );
	$sbut = $wpdb->get_results( "SELECT `id`,`store_id`,`name`, `email` FROM `wp_subscribers` WHERE `stat`='1'" );
	
	foreach ( $sbut as $myrow )   {
			echo '<tr> <td class="text-center">'.$i.'</td>';// echo '&nbsp';
			echo '<td class="text-center">'.$myrow->name.'</td>';
			echo '<td class="text-center">'.$myrow->email.'</td>';
			/*echo '<td class="text-center">'.$myrow->store_id.'</td>';
			echo '<td class="text-center"><a href='.get_admin_url().'admin.php?page=users&m='.$myrow->id.' class="button">choose template</td>';
			echo '<td class="text-center"><a href='.get_admin_url().'admin.php?page=users&umi='.$myrow->email.'&emt='.$myrow->store_id.' class="button">Send Mail</a></td>';
*/
			echo '<td class="text-center"> <a href='.get_admin_url().'admin.php?page=users&deluser='.$myrow->id.' class="btn btn-danger">DELETE
          </a> <td class="text-center">';
			$i=$i+1;
	}

		echo '</table>';
	echo '</div>';
		}

		

}
//

//cron job
//diable cron job wp_config
add_filter( 'cron_schedules', 'newsplug_isa_add_every_three_minutes' );
function newsplug_isa_add_every_three_minutes( $schedules ) {
 //echo 'yes 3 minute work properly';
    $schedules['every_three_minutes'] = array(
            'interval'  => 10,
            'display'   => __( 'Every 3 Minutes', 'textdomain' )
    );
    
    return $schedules;
}


 /*function activat() {

  $args = array( false );
  if(!wp_next_scheduled('abc', $args )){
  	//$aloq=wp_schedule_event( time(), 'every_three_minutes', 'my_hourly_event' );
	$aloq=wp_schedule_event( time(), "hourly", "abc" );
		
  }
 } */
 
/* function cronjov(){
	// print_r('abchfkdw'.'dfijjjjjjjjjjjjjjx');
	// exit;
 }
 //add_action('abc','cronjov');
add_action( 'my_hourly_event',  'update_db_hourly' );
//add_action( 'my_hourly_event',  'http://wsiserver.website/news/cb_hourly.php' );
//add_action( 'init', 'update_db_hourly' ); */

 /*function activate() {

  $args = array( false );
  if(!wp_next_scheduled('my_hourly_event', $args )){
  	//$aloq=wp_schedule_event( time(), 'every_three_minutes', 'my_hourly_event' );
	$aloq=wp_schedule_event( time(), "daily", "my_hourly_event" );
		
  }
  else {
	  echo 'in else condition';
	  exit;
  }
 //wp_schedule_event( time(), 'hourly', 'my_hourly_event' );
   }
/*

*/ 

//CRON CALL BACK PLACE HERE
/*	function update_db_hourly() {	
	
	global $wpdb;
	echo 'update db hourly';
	$sbut = $wpdb->get_results( "SELECT `email`,`store_id` FROM `wp_subscribers` WHERE `stat`='1'" );
	
	foreach ( $sbut as $myrow )   {
		$to=$myrow->email;
		$subject=null;
		$des = null;	
		$hsy=$wpdb->get_results( "SELECT `subj`,`mail_area` FROM `wp_storemail` WHERE `id`='26'" );
	
		foreach ($hsy as $va)
		{
						$subject=$va->subj;
						$des .= stripcslashes($va->mail_area);
		}
		$body = $des;
		$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	
	if(mail($to, $subject, $body,$headers)){
			echo 'mail from cron';
		}
		else {
			echo 'cron is not woring \ !!!!!!!';
		}
	}
	

}*/
//
	function newsplug_install()
		{
								newsplug_create_form();
								newsplug_form();
								newsplug_form_elements();
								newsplug_dummy();
								newsplug_subscribers_table();
									//newsplug_chepy();
									newsplug_list_table();
									newsplug_campaign_table();
									
									newsplug_activate_cron();
									
								newsplug_form_databas();
								
		}

	register_activation_hook(__FILE__, 'newsplug_install' );
	add_action('admin_menu', 'newsplug_pages');

	function newsplug_pages(){

	add_menu_page('newsplug', 'newsplug', 'manage_options', 'etem', 'newsplug_seconndry',"dashicons-megaphone",3);
	//"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAAvklEQVRoge2USxHCQBAFR0IkIAEJSEACEiIBJ0hZKUhYCXDJaagUm7zZZALdVXPamk/vzwwAPK+dAwEEsgn0BoHuBbfuh4DYT37kPy8Q/U0ikE1g6ToCCGwtEA0CcoIIAmrBwz1iBBAQBaJBQC14uCuEAAKiQDQIyAkiCHieLuEUMeXKfme3VlsKPlxSsb4St5l+l5lZvnK1z2PLEvfWXSkJhvVRzGxoFRiSSay+xuOUXHcYuk69R1uw8wD/xhuouG6cDWOwHAAAAABJRU5ErkJggg=="
	add_submenu_page('etem', 'newsplug_seconndry', 'add new Theme', 'manage_options','newsplug_seconndry','newsplug_dum' );
	//plugin_dir_url(__FILE__).'images/pen.png'
	add_submenu_page('etem','newsplug_seconndry','forms','manage_options','third','newsplug_form_init');
	add_submenu_page('etem','newsplug_seconndry','Register Subscribers','manage_options','users','newsplug_subscribers_func');
	add_submenu_page('etem','newsplug_seconndry','List','manage_options','fourth','newsplug_list_record');
		add_submenu_page('etem','newsplug_seconndry','Compain','manage_options','fifth','newsplug_comapin_page');
	
	
	}
//activater
	function newsplug_activate_cron() {
	
		  $args = array( false );
		  if(!wp_next_scheduled('news', $args )){
			 // echo 'if wp_next_scheduled call back '; //count 30
			//$aloq=wp_schedule_event( time(), 'every_three_minutes', 'newsplugss' );
			$al=wp_schedule_event( time(), "daily", "news" );
				if($al==NULL){
					//echo 'output is null'; //count 14
				}
				else  {echo 'false'; }
		  }
		  else {
			  echo 'nor schedule';
		  }
	}
	add_action('news','newsplug_croncall');	
	// cron for list
	function newsplug_croncall(){
		//add_action( 'my_list_event',  'cron_call' );
		

		global $wpdb;
		$current_date=date("Y-m-d");
		$current_time=date("h:i:s");
		$myrows = $wpdb->get_results( 'SELECT start_date,start_time,theme_id,list_id FROM wp_campaign WHERE statu=1');
		
		foreach($myrows as $absa){
		$list_en=$absa->list_id;
		$temp_en=$absa->theme_id;
		if($current_date==$absa->start_date){
		//	if($current_time==$absa->start_time){
						$myadd = $wpdb->get_results( "SELECT `email` FROM `wp_subscribers` WHERE list_id=".$list_en );	
					$mytemp = $wpdb->get_results( "SELECT `subj`,`mail_area` FROM `wp_storemail` WHERE id=".$temp_en );
							$subject = null;
							$des = null;
							foreach ( $mytemp as $myrow )
					{
			
							$subject=$myrow->subj;
							$des .= stripcslashes($myrow->mail_area);
					}
						$body = $des;
						$headers = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
							foreach($myadd as $tow){
								$to=$tow->email;
						
							if(mail($to, $subject, $body,$headers))
								{
									echo ("<p>Email send successfully </p>");
								}
								else {
									echo ("<p>email sending fail</p>");
									}	
								}
			//}
		}
	}
		//under construction

		
	//$myadd = $wpdb->get_results( "SELECT `email` FROM `wp_subscribers` WHERE list_id=".$list_en );
		//echo ("SELECT `email` FROM `wp_subscribers` WHERE id=".$list_en );
		
	

	
	//echo ("SELECT `subj`,`mail_area` FROM `wp_storemail` WHERE id=".$temp_en );
//		$to = $_GET['umi'];
    
		
	
		
	}
	

	
	//compain page
	function newsplug_comapin_page(){
		global $wpdb;
wp_enqueue_style( 'news_jquery', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css' );
 wp_enqueue_script( 'jquery', 'https://code.jquery.com/jquery-1.12.4.js');
wp_enqueue_script( 'jqueryui', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js');
	
	newsplug_activate_cron();
	
		$current_date=date("Y-m-d");
		$current_time=date("h:i");
		
		?>
		
		<script type="text/javascript">
function newsplug_Dostart(e)
{
    if (confirm('Are you sure ?'))
    {}
	else
	{
		e.preventDefault();
		return false;
	}
}

</script>
<?php
global $wpdb;
//stop compaign

		if(isset($_POST['stopid'])&&isset($_POST['_stopnonce'])){
                 if(wp_verify_nonce($_POST['_stopnonce'],'stop_compaign')){
		//$sid=$_POST['stopid'];
		                        $sid = intval( $_POST['stopid'] );
                            if ( ! $sid ) {
                          $sid = '';
                        }	
	$myrows = $wpdb->get_results( 'SELECT statu FROM wp_campaign WHERE id='.$sid );
			if($myrows[0]->statu=='0'){
				?><script>
				alert('compaign is already not running');</script><?php
					
			}
			else {
						$args = array(

				'statu'=>'0',
				
				);
				$cond=array( 'id' => $sid );

			$return = $wpdb->update('wp_campaign', $args, $cond);
			}
		}
                else {echo 'sorry blocked due to security reason';}
                }
		//start compaign
		if(isset($_POST['compid'])&&isset($_POST['_startnonce'])){
		 if(wp_verify_nonce($_POST['_startnonce'],'start_compaign')){
		//$idd=$_POST['compid'];
			          $idd = intval( $_POST['compid'] );
                            if ( ! $idd ) {
                          $idd = '';
                        }	
	$myrows = $wpdb->get_results( 'SELECT statu FROM wp_campaign WHERE id='.$idd );
			if($myrows[0]->statu=='1'){
				?><script>
				alert('compaign is already running');</script><?php
					
			}
			else {
						$args = array(

				'statu'=>'1',
				
				);
				$cond=array( 'id' => $idd );

			$return = $wpdb->update('wp_campaign', $args, $cond);
			}
                }
                else {echo 'security issues can not start compaign <h1>sorry</h1>';}
                        }
		
		//
		global $wpdb;
		//if(isset($_POST['cn'])&&isset($_POST['dlist'])&&isset($_POST['dtheme'])&&isset($_POST['cdate'])&&isset($_POST['ctime'])){
			if(isset($_POST['cn'])&&isset($_POST['dlist'])&&isset($_POST['dtheme'])&&isset($_POST['cdate'])&&isset($_POST['_nonce'])){
			  //  register_compaign _nonce
                            $dlist = intval( $_POST['dlist'] );
                            if ( ! $dlist ) {
                          $dlist = '';
                        }
                        		    $dtheme = intval( $_POST['dtheme'] );
                            if ( ! $dtheme ) {
                          $dtheme = '';
                        }
                        $cname = sanitize_text_field( $_POST['cn'] );
                        
                            $table_name = $wpdb->prefix . 'campaign';
			if($dlist==0 && $dtheme==0){
				
			}
			else{
				
			
	//		exit;
		$args = array(
				'name' => $cname,
				'theme_id' => $dtheme,
					'list_id' => $dlist,
					'statu' => '0',
					'start_date' => $_POST['cdate']/* ,
					'start_time' => $_POST['ctime'] */
				);
					
if(wp_verify_nonce($_POST['_nonce'],'register_compaign')){
                    $wpdb->insert('wp_campaign', $args);
                }
                else {  
                
                    echo 'some security risk are found';
                }
		
		
			}
		}
if(isset($_GET['anc']) && $_GET['anc']=='nc'){
    
	$mytheme = $wpdb->get_results( "SELECT id,subj FROM wp_storemail" );
	$myrows = $wpdb->get_results( "SELECT id,name FROM wp_lists" );
	
	?>
	
<!--<style>
#aDiv{width: 300px; height: 300px; margin: 0 auto;}
</style>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">-->
<!--<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->	

  <script>
    
  jQuery( function($) {
    $( ".datepicker" ).datepicker({
		minDate: 0,
		dateFormat: 'yy-mm-dd'
	});
	
  } );
  </script>
<!--<p>Date: <input type="text" class="datepicker"></p> -->

<?php
	echo '<center><h1> ADD NEW COMPAIGN </h1></center>';

	echo '<div id="aDiv"> <form action='.get_admin_url().'admin.php?page=fifth method="POST">

		<h4>Comapign Name </h4>
		<input type="text" name="cn" required="required" placeholder="COMPAIGN NAME" maxlength="50" size="30"/>';
		echo '<h4> start date </h4>';
			//	echo '<input type="time" name="timestamp" step="1">';
	//echo "<input required id='datefield' type='date' name='cdate' min='1899-01-01' max='2000-13-13' onkeypress='return false'></input>";
	echo '<input required type="text" name="cdate" class="datepicker" onkeypress="return false">';
	//echo "<h4> Enter Start time </h4>";
	//echo '<input type="time" name="ctime" required>';
?>
<!--<script type="text/javascript">
	//
	var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();
 if(dd<10){
        dd='0'+dd
    } 
    if(mm<10){
        mm='0'+mm
    } 
	

today = yyyy+'-'+mm+'-'+dd;
document.getElementById("datefield").setAttribute("min", today);
maximum='2050'+'-'+'12'+'-'+'12';	
document.getElementById("datefield").setAttribute("max", maximum);	</script>	 -->

<!-- locAL -->
<!--<script type="text/javascript"
     src="http://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js">
    </script> 
    <script type="text/javascript"
     src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js">
    </script>
    <script type="text/javascript"
     src="http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.min.js">
    </script>
    <script type="text/javascript"
     src="http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.pt-BR.js">
    </script>
    <script type="text/javascript">
      $('#datetimepicker').datetimepicker({
        format: 'dd/MM/yyyy hh:mm:ss',
        language: 'pt-BR'
      });
    </script>  -->
<?php
		echo '<h4>SELECT THEME </h4> <select name="dtheme">';
	//	echo '<option value="0">"select"</option>';
		foreach ( $mytheme as $myrow ){
			echo '<option value="'.$myrow->id.'">'.$myrow->subj.'</option>';
		}
				echo '</select>';
		echo '<h4>SELECT LIST </h4> <select name="dlist">';
	//	echo '<option value="0">"select"</option>';
		foreach ( $myrows as $myrow ){
			echo '<option value="'.$myrow->id.'">'.$myrow->name.'</option>';
		}
				echo '</select>';
				echo '<br><br>';?> 
                <input type="hidden" name="_nonce" value="<?php echo wp_create_nonce('register_compaign'); ?>"> 
                               <?php echo '<input type="submit" value= "Save" size="50" class="btn btn-default"><br></form>';
echo '</div>';
}	
else{
	
	echo '<h1> ALL COMPAIN </h1>';

		//	print_r ($_POST['cdate']);
	$i=1;
	$myrows = $wpdb->get_results( "SELECT id,name,list_id,theme_id,start_date,start_time,statu FROM wp_campaign" );
		
		
		echo '<div style="overflow-x:auto;">';
	echo '<table class="table table-bordered">
	<tr>
    <th class="text-center">Serial</th>
	<th class="text-center">COMPAIN</th>
	<th class="text-center">LIST</th>
	<th class="text-center">Email</th>
	<th class="text-center"> Start date </th>
	<th class="text-center"> Start time </th>
	<th class="text-center">action </th>
	
	</tr>';
		echo '<br><br><hr><hr><hr><hr>';
		
		//echo plugin_dir_url(__FILE__).'images/pen.png';
	foreach ( $myrows as $myrow )   {
$sbut=$wpdb->get_results( "SELECT `name` FROM `wp_lists` WHERE `id`='".$myrow->list_id."'" );

$sthe=$wpdb->get_results( "SELECT `subj` FROM `wp_storemail` WHERE `id`='".$myrow->theme_id."'" );

			echo '<tr> <td class="text-center">'.$i.'</td>';// echo '&nbsp';
			echo '<td class="text-center">'.$myrow->name.'</td>';
			echo '<td class="text-center">'.$sbut[0]->name.'</td>';
			echo '<td class="text-center">'.$sthe[0]->subj.'</td>';
			echo '<td class="text-center">'.$myrow->start_date.'</td>';
			echo '<td class="text-center">'.$myrow->start_time.'</td>';	
				if($myrow->statu=='0'){
				echo '<td class="text-center"><form action="" method="post" onSubmit="newsplug_Dostart(event)"><input type="hidden" name="compid" value="'.$myrow->id.'">';?>
                                <input type="hidden" name="_startnonce" value="<?php echo wp_create_nonce('start_compaign'); ?>"><?php echo '<input type="submit" value="START COMPAIGN" class="btn btn-default"></form></td>';}
                                else {echo '<td class="text-center"><form action="" method="post" onSubmit="newsplug_Dostart(event)"><input type="hidden" name="stopid" value="'.$myrow->id.'">';?>
                               <input type="hidden" name="_stopnonce" value="<?php echo wp_create_nonce('stop_compaign'); ?>"><?php echo '<input type="submit" value="STOP  COMPAIGN" class="btn btn-default"></form></td>';}
			 $i=$i+1;
	}
	
	?>
		<br>
		<a href="<?php echo get_admin_url(); ?>admin.php?page=fifth&anc=nc" class="btn btn-primary"> Add NEW COMPAIGN </a>
		<br>
	<?php
}
	}
	//List call back
	
function newsplug_list_record(){
?>
<script type="text/javascript">
function newsplug_DoFunList(e)
{
    if (confirm('Are you realy want to delete this LIST ? it may create problems while compaing'))
    {}
	else
	{
		e.preventDefault();
		return false;
	}
}

</script>
<?php
	global $wpdb;
       //  theme-nonce
	if(isset($_POST['list'])&& isset($_POST['wsub']) && isset($_POST['wbod']) && isset($_POST['_themenonce'])){
            if( wp_verify_nonce($_POST['_themenonce'],'theme-nonce')){
		//echo '<h1>'.$_POST['list'].'</h1>';
            $listname = sanitize_text_field( $_POST['list'] );
            $mailsubj = sanitize_text_field( $_POST['wsub'] );
	$table_name = $wpdb->prefix . 'lists';
			//$mail_d=$_POST['list'];
			$args = array(
				'name' => $listname,
				'wsubj'=>$mailsubj,
				'wbody'=>$_POST['wbod']
				);

		$return = $wpdb->insert('wp_lists', $args);
        }
        else {echo 'blocked blocked blocked'; }
	}


       if(isset($_GET['act'])&&$_GET['act']='edit'){

	//admin.php?page=fourth&act=edit
//print_r($_REQUEST);	
	echo '<h1>ADD NEW List Name </h1>'; 

	echo '<form action='.get_admin_url().'admin.php?page=fourth method="POST">';?>
		<label>LIST NAME*:</label><br>
		<input type="text" name="list" required="required" placeholder="LIST NAME" maxlength="50" size="30"/>
				<br><br>
<label>Welcome Theme*:</label><br>
		<input type="text" name="wsub" required="required" placeholder="Theme Title" maxlength="50" size="30"/>
<br>		<br>
		<label>body*:</label><br>
		<?php wp_editor( '', 'wbod', 'settings_wpeditor' ); ?>
		<br> 
                  <input type="hidden" name="_themenonce" value="<?php echo wp_create_nonce('theme-nonce'); ?>"> 
		<input type="submit" value= "Save" size="20" class="btn btn-primary">
		
		<br>
		</form>
<?php
}
else{	

		$myrows = $wpdb->get_results( "SELECT id,name,wsubj FROM wp_lists" );
		$i=1;
		echo 'use id number in short code as a parameter';
 		echo '<div style="overflow-x:auto;">';
	echo '<table class="table table-bordered">
	<tr>
    <th class="text-center">Serial</th>
    <th class="text-center">name</th>
	<th class="text-center">welcome Template</th>
	<th class="text-center">id number</th>
	<th class="text-center"> Delete List </th>
	</tr>';
	foreach ( $myrows as $myrow )
		{

			echo '<tr> <td class="text-center">'.$i.'</td>';// echo '&nbsp';
			echo '<td class="text-center">'.$myrow->name.'</td>';
			echo '<td class="text-center">'.$myrow->wsubj.'</td>';
			echo '<td class="text-center">'.$myrow->id.'</td>';
			echo '<td class="text-center"><form action="" method="post" onSubmit="newsplug_DoFunList(event)"><input type="hidden" name="listid" value="'.$myrow->id.'"><input type="submit" value="DELETE" class="btn btn-danger"></form></td>';
		
			echo '<br>';
			$i=$i+1;
		}

		echo '</table>';
		echo '</div>'; ?>
		<br>
		<br>
		<hr><hr>
 <a href="<?php echo get_admin_url(); ?>admin.php?page=fourth&act=edit" class="btn btn-info"> Add NEW LIST </a>

<?php 
}
}
/* sub menu */
	function newsplug_seconndry()
		{?>

<script type="text/javascript">
function newsplug_DoFunc(e)
{
    if (confirm('Are you realy want to delete this theme?'))
    {}
	else
	{
		e.preventDefault();
		return false;
	}
}

</script>
<?php
//comment
//delete click
 
		if(isset($_POST['deleteid']) && isset($_POST['_delnonce'])){
                   if(wp_verify_nonce($_POST['_delnonce'],'del-nonce')){
                    $deleteid = intval( $_POST['deleteid'] );
                    if ( ! $deleteid ) {
                      $deleteid = '';
                    }
			global $wpdb;
			$table_name = $wpdb->prefix . 'storemail';
			$args = array(
			 'id' => $deleteid,
				);
			$wpdb->delete($table_name,$args);
                }
                else {echo 'BLOCKED BLOCKED BLOCKED';}
		}
//edit _GET
	if(isset($_GET['editid'])&&isset($_GET['sub']))
		{
			//$subj=$_GET['sub'];
                        $subj = sanitize_text_field( $_GET['sub'] );
			//$id=$_GET['editid'];
		$id = intval( $_GET['editid'] );
                    if ( ! $id ) {
                      $id = '';
                    }	
                        newsplug_getedit($id,$subj);
		}

//comment
else{

			global $wpdb;
		$myrows = $wpdb->get_results( "SELECT id,subj,mail_area FROM wp_storemail" );
		$i=1;
		echo '<div style="overflow-x:auto;">';
	echo '<table class="table table-bordered">
	<tr>
    <th class="text-center">Serial</th>
    <th class="text-center">Theme Title</th>
	<th class="text-center"> Delete Theme </th>
	<th class="text-center"> Edit Theme </th>
	</tr>';
	foreach ( $myrows as $myrow )
		{

			echo '<tr> <td class="text-center">'.$i.'</td>';// echo '&nbsp';
			echo '<td class="text-center">'.stripslashes ($myrow->subj).'</td>';
	//		echo '<td class="text-center">[etshort et=&#39;'.$myrow->id.'&#39;]</td>';
			//echo '<td class="text-center"><div role="button" class="button">DELETE</div></td>';
			echo '<td class="text-center"><form action="" method="post" onSubmit="newsplug_DoFunc(event)"><input type="hidden" name="deleteid" value="'.$myrow->id.'">';?>
                        <input type="hidden" name="_delnonce" value="<?php echo wp_create_nonce('del-nonce'); ?>"> <?php echo '<input type="submit" value="DELETE" class="btn btn-danger"></form></td>';
		echo '<td class="text-center">'; ?><a href="<?php echo get_admin_url(); ?>admin.php?page=etem&editid=<?php echo $myrow->id; ?>&sub=<?php echo $myrow->subj; ?>" class="btn btn-primary">Edit </a><?php echo '</td>';

			echo '<br>';
			$i=$i+1;
		}

		echo '</table>';
		echo '</div>';

}
?>

		 <br>
		 <a href="<?php echo get_admin_url(); ?>admin.php?page=newsplug_seconndry" class="btn btn-info"> Add New Theme </a>
		<?php }

	function newsplug_dum()
		{?>
		
		<br>
		<a href="<?php echo get_admin_url(); ?>admin.php?page=etem" class="btn btn-info"> old templates </a>
<br><br><br>
		<form action="" method="post">
		<label>Theme Title *:</label><br>
		<input type="text" name="mail_subj" required="required" placeholder="Title" maxlength="50" size="30"/>
				<br>
		<br><label>body *:</label><br>
		<!--<input type="text" name="mail_body" size="100%"  required="required"/> --> <br>
		
		<?php wp_editor( '', 'mail_body', 'settings_wpeditor' ); ?>
		<br>
                 <input type="hidden" name="_etempnonce" value="<?php echo wp_create_nonce('etemp-nonce'); ?>">
		<input type="submit" value= "Save template" name="save_temp" size="20" class="btn btn-primary">
		<br>
		</form>
		<?php

		if(isset($_POST['mail_subj'])&&isset($_POST['mail_body'])&&isset($_POST['_etempnonce'])){
			global $wpdb;
			if(wp_verify_nonce($_POST['_etempnonce'],'etemp-nonce')){
                        $table_name = $wpdb->prefix . 'storemail';
                        $mail_subj = sanitize_text_field( $_POST['mail_subj'] );
			$mail_d=$_POST['mail_body'];
			$args = array(
				'subj' => $mail_subj,
				'mail_area' =>$mail_d
				);

		$return = $wpdb->insert('wp_storemail', $args);
		$wpdb->show_errors(true);
                }
                else {echo 'Blocked Blocked Blocked';}
		}
		/*global $wpdb;
		$myrows = $wpdb->get_results( "SELECT mail_area FROM wp_storemail where id=16" );
		foreach ( $myrows as $myrow )
		{

			echo stripslashes ($myrow->mail_area);
		} */
		}
	function newsplug_edit_temp($id,$subj,$mail_area)
		{?>
		<form action="" method="post">
		<label>subject *:</label><br>
		<input type="text" name="mail_subj" required="required" value = "<?php echo $subj;?>" size="30"/>
				<br>
		<label>body *:</label><br><br><br>
		<?php wp_editor(stripcslashes($mail_area), 'mail_body', 'settings_wpeditor' ); ?>
		 <input type="hidden" name="_etempnonce" value="<?php echo wp_create_nonce('etemp-nonce'); ?>">
                <input type="submit" value= "update template" name="update_temp" size="20">
		<br>
		</form>


<?php

		}


function newsplug_tempshort($atts){

			$atts=shortcode_atts( array(
				'et' => '0'
				),$atts,'etshort'

				);
	global $wpdb;
/*		$myname = $wpdb->get_results( "SELECT tag_name,tag_end,type FROM wp_form_elements WHERE id=2" );

	echo '<form action="" method="post">
		<label>name *:</label><br>';
	echo '<'.$myname[0]->tag_name. ' type= '.$myname[0]->type. ' name="username" required="required" maxlength="50" size="30"/>';

		//email;
			$einput = $wpdb->get_results( "SELECT tag_name,tag_end,type FROM wp_form_elements WHERE id=3" );
			echo '<label>Email *:</label>';
	echo '<'.$einput[0]->tag_name. ' type='.$einput[0]->type. ' name="useremail" required="required" maxlength="50" size="30"/>';

	$sbut = $wpdb->get_results( "SELECT tag_name,tag_end,type FROM wp_form_elements WHERE id=1" );

	echo '<'. $sbut[0]->tag_name. ' type='.$sbut[0]->type.' value= "Subscribe" name="submit" size="20" />';
	echo '	</form>';
	if(isset($_POST['username'])&&isset($_POST['useremail'])){
			global $wpdb;
			$table_name = $wpdb->prefix . 'subscribers';
	$myrows = $wpdb->get_results( "SELECT subj,mail_area FROM wp_storemail WHERE id=20" );

		$to = $_POST['useremail'];
    $subject = null;
    	$des = null;
		foreach ( $myrows as $myrow )
		{
			$subject=$myrow->subj;
			$des .= stripcslashes($myrow->mail_area);
		}
		$body = $des;
		$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";														
    if(mail($to, $subject, $body,$headers))
    {
        echo ("<p>Email send successfully </p>");
 	}
    else {
        echo ("<p>email sending fail</p>");
		}

	}

*/


	}
	
		add_shortcode('etshort','newsplug_tempshort');
		
	
function newsplug_form_creation($attr){

 global $wpdb;
    
	if(isset($_GET['stat'])&&isset($_GET['seto'])&&isset($_GET['email'])){
            $sta = intval( $_GET['stat'] );
                if ( ! $sta ) {
                  $sta = '';
                }

        $emial=sanitize_email( $_GET['email'] );

						//echo 'control is here';
				$args = array(
				'stat' => $sta
				);
				$cond=array( 'email' => $emial );
		
			$return = $wpdb->update('wp_subscribers',$args, $cond);
				
			}
			
 
else 
                            
    if(isset($_POST['text'])&&isset($_POST['email'])&&isset($_POST['_formnonce'])){
	if(wp_verify_nonce($_POST['_formnonce'],'form-nonce')){
        global $wpdb;
			//insert user record
			//echo "thanks for subscribing go to your email address for verify your email address";
//echo $_POST['text'];
//echo $_POST['email'];

            $liame=sanitize_email( $_POST['email'] );
            $inputtext = sanitize_text_field( $_POST['text'] );
                    
			$args = array(
				'name' => $inputtext,
				'email' =>$liame,
				'list_id' => $attr[listid],
				'stat' => '1'
				);
  //get_results("SELECT form_name FROM wp_form where id = '$attr[formid]' ;");
		$sbut = $wpdb->get_results( "SELECT `id` FROM `wp_subscribers` WHERE `email`='".$liame."'" );
	
//			echo $sbut;
	if ($sbut[0]->id > 0 ) {
//echo '<h1>'.$attr[listid].'</h1>';
echo "SORRY EMAIL IS ALREADY EXIST";

}
else
{
			//$st = $wpdb->get_results( 'SELECT `template` FROM `wp_lists` WHERE `id`='. );
		
			//$dod=$st[0]->template;
	//echo 'Thanks for Subscribing Please Go to your email address for verify your account';
	//$dod=$sbut[0]->list_id;
	$return = $wpdb->insert('wp_subscribers', $args);
	$table_name = $wpdb->prefix . 'subscribers';
	//for send welcome mail;
	$wmai = $wpdb->get_results( "SELECT `list_id` FROM `wp_subscribers` WHERE `email`='".$liame."'" );
	//print_r("SELECT `list_id` FROM `wp_subscribers` WHERE `email`=".$liame);
     //  echo '<br>';
    //    var_dump($wmai);
	$myrows = $wpdb->get_results( "SELECT `wsubj`,`wbody` FROM `wp_lists` WHERE id=".$wmai[0]->list_id);
/*
.get_admin_url().'admin.php?page=users&stat=1&seto='.$serial.'&email='.$mail.'> Subscribe </a>'
*/
//echo get_page_link();
		$to = $liame;
		$subject = null;
    	$des = null;
		foreach ( $myrows as $myrow )
		{
			$subject=$myrow->wsubj;
			$des .= stripcslashes($myrow->wbody);
		}
		
						$array=$liame;
				$mail=$liame;
				$serial = md5($array);
				
	//	$subs='<b> for subscribe follow </b> <a href='.get_page_link().'?stat=1&seto='.$serial.'&email='.$mail.'> Subscribe </a>';
//	$des.=$subs;
		$body = $des;
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
//ob_start();		
		if(mail($to, $subject, $body, $headers))
				{
		//			echo $subs;
					
					echo ("<p>Email send successfully </p>");
					
				}
				else {
						echo ("<p>email sending fail</p>");
					}

}

    }
else {echo 'form blocked ';}
    
}
 ///
		 $form_name= $wpdb->get_results("SELECT form_name,template FROM wp_form where id = '$attr[formid]' ;");
			$form_name = json_decode(json_encode($form_name), true);
				
			?>
				<div>
			<form method="post" action="">

			<center><b><?php echo $form_name[0]['form_name'] ?> </b><center><br>


			<?php

			$class_name='';
			$last_order=0;
			$created_elements = $wpdb->get_results("SELECT wp_created_form.form_id, wp_created_form.field_id,wp_created_form.value,wp_created_form.element_order,wp_created_form.name,wp_form_elements.id,wp_form_elements.tag_name,wp_form_elements.tag_end,wp_form_elements.type
FROM wp_created_form
INNER JOIN wp_form_elements
ON wp_created_form.field_id=wp_form_elements.id
and wp_created_form.form_id= '$attr[formid]'
order by element_order ASC;");
			

			foreach($created_elements as $element){
				ob_start();
		//i am here now
		if ($element->field_id=='2')
			$class_name='text_class';
		    //$order=
		elseif($element->field_id=='3')
		$class_name='email_class';
		elseif($element->field_id=='1')
		$class_name='submit_class';
			if($element->type!='submit' )
			{ob_start();
		echo "$element->value: <$element->tag_name  type='$element->type' class='$class_name'  required  name='$element->type'/><br>";
			}
		else 
		{ob_start(); ?>
                 <input type="hidden" name="_formnonce" value="<?php echo wp_create_nonce('form-nonce'); ?>">
			<?php echo " <{$element->tag_name} type='{$element->type}' class='{$class_name}'  value='{$element->value}' required   name='{$element->type}'/><br>";

			$class_name='';
			}
			}

			?>

			</form>
			</div>
<?php
		}
		add_shortcode('myforms', 'newsplug_form_creation');
register_deactivation_hook( __FILE__, 'newsplug_uninstall' );
function newsplug_deactivate() {
    wp_clear_scheduled_hook('my_hourly_event');
}
function newsplug_uninstall(){
	newsplug_deactivate();
}
?>