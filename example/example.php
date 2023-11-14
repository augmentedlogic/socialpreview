<?php

require '../src/SocialPreviewClient.php';
require '../src/Preview.php';

use com\augmentedlogic\socialpreview\SocialPreviewClient;
use com\augmentedlogic\socialpreview\Preview;

// Some URLs for testing

//$url = "https://www.threads.net/@mistakenotmy/post/CzgPEULrOZV";
//$url = "https://universeodon.com/@mistakenotmy/111391564599914065";
//$url = "https://stackoverflow.com/questions/1148928/disable-warnings-when-loading-non-well-formed-html-by-domdocument-php";
//$url = "https://www.lawfaremedia.org/article/the-fight-for-democracy-in-poland-has-just-begun";
//$url = "https://twitter.com/StephenKing/status/1615742233134653442";
//$url = "https://www.instagram.com/p/B_f6_O9jIU7/?hl=en";
$url = "https://medium.com/@wolfhf/teslas-cybertruck-is-dystopia-on-wheels-and-it-s-political-ffc929e2644";


$spc = new SocialPreviewClient();
  $spc->setUserAgent("curl/9.75"); // set a user specific agent
  $spc->setTimeout(10);            // set a timeout in seconds
  $spc->setConnectTimeout(10);     // set connect timeout in seconds
  $spc->allowFallbackData(true);   // allow core data (title, description) to be replaced if no opengraph data is available, default: true
  $spc->allowFallbackImage(true);  // allow use of a fallback image (the first image in the page)
                                   // if no opengraph or twitter card image is available, default: false
  $spc->addHeader("x-myheader: whatever"); // add custom headers if required

// get the preview object
$preview = $spc->getPreview($url);

print $preview->getSiteName()."\n";
print $preview->getImage()."\n";
print $preview->getDomain()."\n";
print $preview->getTitle()."\n";
print $preview->getDescription()."\n";
print $preview->getStatusCode()."\n";

// dump all the available data as an array
print_r($preview->getAsArray());

