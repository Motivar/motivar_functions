<?php
if ( ! defined( 'ABSPATH' ) ) exit;
add_menu_page( 'Admin Tools', 'Admin Tools', 'manage_options', 'wp_admin_tools', 'motivar_admin_functions_options');
function motivar_admin_functions_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

global $current_user;
$msg=$cls='';
$vars=array(array('debug',0,'Enable Debug Mode'),array('admin_only',0,'Users only Can See the page'),array('admin_only_frontend',0,'Super User only Frontend'),array('map_key',2,'Google Maps Key'),array('google',1,'Google Analytics'),array('hotjar',1,'Hotjar Analytics'),array('mcp_key',2,'Mailchimp Api Key'),array('mcp_list_id',2,'Mailchimp List Id'), array('mcp_related_post',2,'Post Type Related to Mailchimp'), array('post_slugify',2,'Post Types to Slugify (separate with comma)'), array('tax_slugify',2,'taxonomies to Slugify (separate with comma)'),array('motivar_login_bcg',2,'Url of Image for Login Page'),array('motivar_login_color_url',2,'Font Color for urls'),array('motivar_login_color',2,'Background Color of Login Page'),array('motivar_login_url',2,'Url For Login Image'),array('motivar_login_alt',2,'Alt For Login Image'),array('_recaptcha',2,'Enable Recaptcha in these pages (separate with comma)'),array('recaptcha_position',3,'Recaptcha Position'),array('recaptcha_distance_from_bottom',2,'Set distance from bottom'));


if (isset($_POST['post_g_options']) && wp_verify_nonce($_POST['post_g_options'], 'motivar_functions_user_'.$current_user->ID))
	{
		foreach ($_POST as $key => $value)
		{
			if (strpos($key,'motivar_functions_')===0)
			{
			foreach ($vars as $var)
				{
					switch ($var[1]) {
						case 1:
						if (strpos($key,$var[0]))
							{
							$value=str_replace("\n",' ',$value);
							$value=stripslashes(trim($value));
							$value=base64_encode($value);
							}
						break;
						default:
							break;
					}
					update_option($key,$value);
				if (!isset($_POST['motivar_functions_'.$var[0]]))
					{
					delete_option('motivar_functions_'.$var[0]);
					}
				}
			}

		}
	$msg.= '<div class="updated" style="padding:10px;" >Settings Updated</div>';
	}
else if (isset($_POST['tags_import']) && wp_verify_nonce($_POST['tags_import'], 'motivar_functions_import_'.$current_user->ID))
	{
	$import_file = $_FILES['import_file']['name'];
	if (empty($_POST['tax']))
	{
		$msg.='<div class="error" style="padding:10px;font-weight:bold;" >Select Taxonomy!</div>';
	}
	else
	{
		if( empty($import_file))
			{
			$msg.='<div class="error" style="padding:10px;font-weight:bold;" >No File Found</div>';
			}
		else
			{
			$extension =  explode( '.', $import_file );
			$extension=end($extension);
			if( $extension != 'json' )
				{
				$msg.='<div class="error" style="padding:10px;font-weight:bold;" >Only json files allowed</div>';
				}
			else
				{
				$data =file_get_contents($_FILES['import_file']['tmp_name']);
				$data=json_decode($data);
				if (empty($data))
					{
					$msg.='<div class="error" style="padding:10px;font-weight:bold;" >Empty Data</div>';
					}
				else
					{
					global $wpdb;
					$parents=array();
					/*bring parents up*/
					usort($data,function ($a, $b){return strcmp($a->parent, $b->parent);});
					if (isset($_POST['del_all']))
						{
						$taxonomies = get_terms(array($_POST['tax']),array('hide_empty'=>false,'fields'=>'ids'));
						if (!empty($taxonomies))
						{
						foreach ($taxonomies as $t)
						{
							wp_delete_term($t,$_POST['tax']);
						}

						}
							}
						$k=0;
					foreach ($data as $d)
						{
							$term = term_exists($d->name, $_POST['tax']);
							if ($term !== 0 && $term !== null) {
							 if(isset($_POST['rep_all']))
							 {
							 	wp_update_term($term['term_id'], $_POST['tax'], array(
										'description'=> $d->description,
									    'slug' => $d->slug,
									    'name'=>$d->name
										));
							 $k++;
							 }
							}
							else
							{
							if(empty($d->parent))
								{
								$a=wp_insert_term($d->name,$_POST['tax'],array(
								    'description'=> $d->description,
								    'slug' => $d->slug
								  ));
								$parent[$d->name]=$a['term_id'];
								 }
							else
								{
								wp_insert_term($d->name,$_POST['tax'],array(
								    'description'=> $d->description,
								    'slug' => $d->slug,
								    'parent'=>$parent[$d->parent]
								  ));
								}
							$k++;
							}
						}
					$msg.= '<div class="updated" style="padding:10px;" >'.$k.' Terms Imported!</div>';
					}
				}
			}
		}
	}

$msg.='<div class="wrap"><h2>Basic Options</h2><form method="POST" action="">
<br><table class="form-table">';

foreach ($vars as $var)
	{
	$msg.='<tr valign="top"><th scope="row"><label for="motivar_functions_'.$var[0].'">'.ucfirst($var[2]).'</label></th><td>';
	$option=get_option('motivar_functions_'.$var[0]);
	switch ($var[1]) {
		case 0:
			$cls='';
			if ($option==1)
			{
			$cls="checked";
			}

		 $msg.='<input type="checkbox" name="motivar_functions_'.$var[0].'" id="motivar_functions_'.$var[0].'" value="1" '.$cls.'>';
			break;
		case 1:
		$msg.='<textarea name="motivar_functions_'.$var[0].'" id="motivar_functions_'.$var[0].'" rows="8" cols="50">'.base64_decode($option).'</textarea>';
		break;
		case 2:
		$msg.='<input type="text" name="motivar_functions_'.$var[0].'" id="motivar_functions_'.$var[0].'" value="'.$option.'" />';
		break;
		case 3:
		$select2='<select name="motivar_functions_'.$var[0].'" id="motivar_functions_'.$var[0].'">
							  <option value="">Choose Recaptcha Position</option>
									<option value="bottom_left"';
									if ($option=='bottom_left') {
											$select2.='selected>Bottom Left</option>';
									}
									else{
										$select2.='>Bottom Left</option>';
									}
									$select2.='<option value="bottom_right"';
									if ($option=='bottom_right') {
											$select2.='selected>Bottom Right</option></select>';
									}
									else{
										$select2.='>Bottom Right</option></select>';
									}
		$msg.=$select2;
		break;
		default:
			# code...
			break;
	}
	$msg.='</td></tr>';
	}
$msg.=wp_nonce_field( 'motivar_functions_user_'.$current_user->ID,'post_g_options').'</table><p><input type="submit" value="Save" class="button-primary"/></p><br><hr></form>';

$select='<select name="tax" id="tax"><option value="">Choose Taxonomy</option>';
$taxonomies = get_taxonomies(array(),'object');
foreach ( $taxonomies as $taxonomy ) {
   $select.= '<option value="'.$taxonomy->query_var.'">'.$taxonomy->labels->name.'</option>';
}
$select.='</select>';
$msg.='<h2>Insert Tags</h2>';
$msg.='<div class="metabox-holder">
				<div class="postbox">
						<div class="inside">
							<form method="post" enctype="multipart/form-data">
							<p>'.$select
							.wp_nonce_field( 'motivar_functions_import_'.$current_user->ID,'tags_import').'</p><p><input type="file" name="import_file"/></p>';
$checks=array(array('del_all','Delete Previous Terms'),array('rep_all','Replace same terms'));
foreach ($checks as $check)
	{
	$msg.='<p><label for="'.$check[0].'"><input type="checkbox" name="'.$check[0].'" id="'.$check[0].'" class="onlyone">'.$check[1].'</label></p>';
	}
$msg.='	<p><input type="submit" value="Insert" class="button-primary"/></p></form></div></div></div>';
$msg.='</div>';
echo $msg;
	}



