<?php

/**
 * Display form erros alert
 *
 * @param array $errors
 * @return void
 */
function form_errors($errors = array())
{
  $output = "";
  if (!empty($errors)) {
    $output .= "<div class=\"error\">";
    $output .= "Please fix the following errors:";
    $output .= "<ul>";
    foreach ($errors as $key => $error) {
      $output .= "<li>";
      $output .= htmlentities($error);
      $output .= "</li>";
    }
    $output .= "</ul>";
    $output .= "</div>";
  }
  return $output;
}

/**
 * Upload file to server
 *
 * @param [type] $target_dir
 * @param [type] $field
 * @return void
 */
function form_upload($target_dir, $field)
{
  if (!file_exists($target_dir)) {
    $target_file = $target_dir . basename($_FILES[$field]["name"]);
    return $target_file;
  }

  return "";
}

/**
 * Get Form Checkbox Check Status On POST BACK
 * @example <input type="checkbox" <?php echo get_form_field_checked('user_name'); ?> />
 * @return  string
 */
function check_form_field_checked($srcdata,$value){
  if(!empty($srcdata)){
    $arr=explode(",",$srcdata);
    if(in_array($value,$arr)){
      return "checked";
    }
  }
  return null;
  }