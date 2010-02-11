<?php 
/*
Plugin Name: Rename roles plugin
Plugin URI: http://tpoxa.com/2010/02/09/roles-rename/
Description: That plugin provides easy renaming of user roles.
Version: 0.1
Author: Maksim Trofimenko 
Author URI: http://tpoxa.com
*/

add_action('admin_menu', 'rename_roles_menu');

function rename_roles_menu() {
  add_options_page('User roles rename', 'User roles rename', 'administrator', 'roles-rename-settings', 'roles_rename_settings');
}

function roles_rename_settings() 
{
	 echo '<div class="wrap">';  
	 echo '<div id="icon-users" class="icon32"><br></div>';
	 echo '<h2>User roles</h2>';
	
	 $save_that_array=array();
	 
	 global $wp_roles;
	 $all_roles = $wp_roles->roles; 
     
	 $editable_roles = apply_filters('editable_roles', $all_roles);
	  
	 echo '<form method="post" action=""><table class="form-table"><tbody>';
	 
	 foreach ($editable_roles as $role_id=>$role):
	 
	    $role['name']=(isset($_POST[$role_id])) ? esc_attr($_POST[$role_id]) : $role['name'];	    

	    $save_that_array[$role_id]=$role['name'];
	    	 	
		echo '<tr >
					<th scope="row">
						<label for="'.$role_id.'"><span style="text-transform:capitalize">'.$role_id.'</span></label>
					</th>
					<td>
						<input type="text" size="32" value="'.$role['name'].'"  name="'.$role_id.'">
						
					</td>
				</tr>';
	   endforeach;

	   echo '</tbody></table>';
	  
		  if(isset($_POST['save'])) 
		  {
		  	if(update_option('rename_user_roles_titles',(array)$save_that_array))
		  	{
		  		echo '<div class="updated">Roles are updated</div>';
		  	}			
	   	  }
	   	   
	   echo' <p class="submit">
	                <input name="save" class="button-primary" value="Save" type="submit">
	        </p> 
	        </form>';
	  echo '</div>';
  
}

function edit_roles_func ($args)
{
	$new_names = get_option('rename_user_roles_titles',array());	
	foreach($args as $role_id=>&$role)
	{
		if(isset($new_names[$role_id]) &&  trim($new_names[$role_id]) !="")
		$role['name']=$new_names[$role_id];
	}	
	return $args; 
}

add_filter('editable_roles','edit_roles_func');

?>