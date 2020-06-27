<?php
// Completed by Hannah Carl

// Create fake user agent
$context = stream_context_create(
  array(
      "http" => array(
          "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
      )
  )
);

// Pull contents from API
$file = file_get_contents("https://api.github.com/orgs/boomtownroi", false, $context);
$file = str_replace('"', '', $file);
$separator = array(': ', ',');
$file = str_replace($separator, '-', $file);
$array = explode("-",$file);

// Find url values that include "api.github.com/orgs/BoomTownROI"
foreach ($array as $value) {
  if (strpos($value, 'api.github.com/orgs/BoomTownROI') !== false) { 
    $results[] = $value; 
    echo $value . "\n";
    print_r(get_headers($value, 1, $context));
  }
}
//if( empty($results) ) { echo 'No matches found.'; }
//else { echo "'api.github.com/orgs/BoomTownROI' was found in: " . implode('; ', $results); }

?>