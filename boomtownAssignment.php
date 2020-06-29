<?php
// Completed by Hannah Carl

//Variables
$urlToCompare = 'https://api.github.com/orgs/BoomTownROI';
$urlArray = array();

// Create fake user agent
$context = stream_context_create(
  array(
      "http" => array(
          "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
      )
  )
);

// Get information from API
// if internest access is available use the below line, otherwise use the downloaded jsons
// $file = file_get_contents("https://api.github.com/orgs/boomtownroi", false, $context);
$mainFilePath = 'main.json';
$mainJSON = file_get_contents($mainFilePath);
$mainArray = json_decode($mainJSON, true);
//var_dump($mainArray);

// Find url values that include "api.github.com/orgs/BoomTownROI"
// Parse through any secondary urls and make sure those are included
foreach ($mainArray as $mainValue) {
  if (strpos($mainValue, $urlToCompare) !== false) { 
    // Clean up secondary url location
    if(strpos($mainValue, '{') !== false) { 
      $mainValue = str_replace('}', '', $mainValue);
      $secondaryURL = explode('{',$mainValue);
      array_push($urlArray, $secondaryURL[0], $urlToCompare . $secondaryURL[1]);
    }
    else{
      $urlArray[] = $mainValue; 
    }
  }
};
//var_dump($urlArray);

// Check the status of a request for each URL
//foreach ($urlArray as $urlToVisit) {
  //echo $urlToVisit . "\n";
  /* $headerArray = get_headers($urlToVisit, 1, $context);
  if(strpos($headerArray[0], '200') === false) { 
    echo "Failed request for " . $urlToVisit . $headerArray[0] . "\n";
  }
  else {
    $pageInfo = file_get_contents($urlToVisit, 1, $context);
    $responseArray = explode(":",$pageInfo);
    for($x = 0; $x <= count($responseArray); $x++) {
      if($x < 20){
        //echo $responseArray[$x] . "\n";
      }
      if($responseArray[$x] === 'id'){
        echo "id: " . $responseArray[$x + 1] . "\n";
      }
    }
  } */
//};

$jsonFileDir = array_diff(scandir('sub_json_files/'), array('..', '.'));
foreach($jsonFileDir as $subJSONFile) {
  $all_ids = array();
  $subJSON = file_get_contents('sub_json_files/' . $subJSONFile);
  $subJSONObj = json_decode($subJSON);
  echo "File name: " . $subJSONFile . "\n";
  foreach($subJSONObj as $row) {
    foreach($row as $key => $val) {
      if($key === "id"){
        echo $key . ': ' . $val . "\n";
      }
    }
  }
};

// Function verifies the updated date is later than created from the API
function verifyDate($createdAt, $updatedAt){
  $createdTime = new DateTime($createdAt);
  $updatedTime = new DateTime($updatedAt);

  if($updatedTime > $createdTime){
    echo "Updated time of " . $updatedAt . " is later than the created time of " . $createdAt . "\n";
  }
  else{
    echo "Failure: Updated time was not later than the created time\n";
  }
};

// Function verifies repository count
function verifyReposCount($publicReposCount, $reposURL, $context){
  $pageNumber = 1;
  $urlRepoCount = 0;


  while(($urlRepoCount < $publicReposCount) && ($pageNumber < 5)) {
    $reposArray = array();
    $repoURLInfo = file_get_contents($reposURL . "?page=" . $pageNumber, false, $context);
    $urlRepoCount += substr_count($repoURLInfo, "full_name");
    $pageNumber += 1;
  }

  if($urlRepoCount === $publicReposCount){
    echo "Repository Counter: Verified\n";
    echo "Repository Count: " . $urlRepoCount . "\n";
  }
  else{
    echo "Respository Counter: Not Verified\n";
  }
};

//verifyDate("2011-11-22T21:48:43Z","2020-04-21T23:30:09Z");
//verifyReposCount(41,"https://api.github.com/orgs/BoomTownROI/repos", $context);
?>