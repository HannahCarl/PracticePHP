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
// $file = file_get_contents("https://api.github.com/orgs/boomtownroi", false, $context);
$apiContents = 
  '"login": "BoomTownROI",
  "id": 1214096,
  "node_id": "MDEyOk9yZ2FuaXphdGlvbjEyMTQwOTY=",
  "url": "https://api.github.com/orgs/BoomTownROI",
  "repos_url": "https://api.github.com/orgs/BoomTownROI/repos",
  "events_url": "https://api.github.com/orgs/BoomTownROI/events",
  "hooks_url": "https://api.github.com/orgs/BoomTownROI/hooks",
  "issues_url": "https://api.github.com/orgs/BoomTownROI/issues",
  "members_url": "https://api.github.com/orgs/BoomTownROI/members{/member}",
  "public_members_url": "https://api.github.com/orgs/BoomTownROI/public_members{/member}",
  "avatar_url": "https://avatars3.githubusercontent.com/u/1214096?v=4",
  "description": "",
  "name": "BoomTownROI",
  "company": null,
  "blog": "boomtownroi.com",
  "location": null,
  "email": null,
  "twitter_username": null,
  "is_verified": false,
  "has_organization_projects": true,
  "has_repository_projects": true,
  "public_repos": 41,
  "public_gists": 0,
  "followers": 0,
  "following": 0,
  "html_url": "https://github.com/BoomTownROI",
  "created_at": "2011-11-22T21:48:43Z",
  "updated_at": "2020-04-21T23:30:09Z",
  "type": "Organization"';
$apiContents = str_replace('"', '', $apiContents);
$separator = array(': ', ',');
$apiContents = str_replace($separator, '-', $apiContents);
$apiArray = explode("-",$apiContents);

// Find url values that include "api.github.com/orgs/BoomTownROI"
// Parse through any secondary urls and make sure those are included
foreach ($apiArray as $apiValue) {
  if (strpos($apiValue, $urlToCompare) !== false) { 
    // Clean up secondary url location
    if(strpos($apiValue, '{') !== false) { 
      $apiValue = str_replace('}', '', $apiValue);
      $secondaryURL = explode('{',$apiValue);
      array_push($urlArray, $secondaryURL[0], $urlToCompare . $secondaryURL[1]);
    }
    else{
      $urlArray[] = $apiValue; 
    }
  }
};

// Check the status of a request for each URL
foreach ($urlArray as $urlToVisit) {
  echo $urlToVisit . "\n";
  //$headerArray = get_headers($urlToVisit, 1, $context);
    //if(strpos($headerArray[0], '200') === false) { 
    //  echo "Failed request for " . $urlToVisit . $headerArray[0] . "\n";
   // }
    //else {
      // Fill in logic to parse ID values
    //}
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

verifyDate("2011-11-22T21:48:43Z","2020-04-21T23:30:09Z");
verifyReposCount(41,"https://api.github.com/orgs/BoomTownROI/repos", $context);
?>