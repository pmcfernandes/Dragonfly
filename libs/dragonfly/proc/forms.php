<?php

/**
 * Display form erros alert
 *
 * @param array $errors
 * @return void
 */
function form_errors($errors = array()) {
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
function form_upload($target_dir, $field) {
  if (!file_exists($target_dir)) {
      $target_file = $target_dir . basename($_FILES[$field]["name"]);
      return $target_file;
  }

  return "";
}