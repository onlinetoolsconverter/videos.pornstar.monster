<?php
$videoID = trim($_GET['id']);

if($videoID == ''){
	exit('Error: videoID not found.');
}

//cheack if the files exists and validate all the jsons 
$videoInfo = validateAndReturnJson("xbay-data/$videoID/videoInfo.json"); 
$thumbnails = validateAndReturnJson("xbay-data/$videoID/thumbnails.json");
$translations = validateAndReturnJson("xbay-data/$videoID/translations.json");

//validate duration exists or not
$duration = trim($videoInfo['durationInput']);
if($duration == ''){
	exit('Error: Duration not found.');
}


//validate images json, if image urls are valid url or data:image
$imageValidation = validateJsonForUrlOrDataImage($thumbnails);

if (isset($imageValidation['error'])) {
    exit('Error: ' . $result['error']);
}



//add original thumbnails from xbay.me
$thumbnails['thumb_1'] = "https://xbay.me//t/$videoID.jpg";
$thumbnails['thumb_2'] = "https://xbay.me//t/$videoID.jpg?n";



$languageCodes = [ 
"hi"=> "Hindi", "bn"=> "Bengali", "es"=> "Spanish", "ru"=> "Russian", "de"=> "German","fr"=> "French", "ja"=> "Japanese",	 "pt"=> "Portuguese","tr"=> "Turkish", "it"=> "Italian","fa"=> "Persian-Farsi","nl"=> "Dutch",
"pl"=> "Polish","zh-CN"=> "Chinese", "vi"=> "Vietnamese","id"=> "Indonesian","cs"=> "Czech","ko"=> "Korean", 
"uk"=> "Ukrainian","ar"=> "Arabic","el"=> "Greek","iw"=> "Hebrew", "sv"=> "Swedish","ro"=> "Romanian",
"hu"=> "Hungarian","th"=> "Thai", "da"=> "Danish","sk"=> "Slovak","fi"=> "Finnish","sr"=> "Serbian", 
"no"=> "Norwegian","bg"=> "Bulgarian","lt"=> "Lithuanian","sl"=> "Slovenian", "ca"=> "Catalan","et"=> "Estonian",
"lv"=> "Latvian","hr"=> "Croatian", "as" => "Assamese","gu" => "Gujarati","ml" => "Malayalam", "ne" => "Nepali", 
"mr" => "Marathi","pa" => "Punjabi","ta" => "Tamil","ka" => "Kannada", "te" => "Telugu","or" => "Oriya",
"ur" => "Urdu","ms" => "malay","en" => "English",];


//current time stamp
$now = new DateTime('now', new DateTimeZone('UTC'));
// Format the datetime as a string
$timeStamp = $now->format('Y-m-d\TH:i:s\+00:00');


//video mp4 url
$videoUrl = "https://s1.xbay.me/x/$videoID.mp4";



// Access data for each language from $translations
foreach ($translations as $languageCode => $languageData) {
    //echo "Language Code=> $languageCode\n";
	$title = trim($languageData['title']);
	$metaDescription = trim($languageData['metaDescription']);
	$description = trim($languageData['description']);
	$tags = trim($languageData['tags']);
	
	$languageName = $languageCodes[$languageCode];
	
	if($title != '' && $metaDescription != '' && $description != '' && $tags != '' && $languageName != ''){
		
		$title = $title . ' Video';
		
		$truncatedText = truncateString($title, 100);
		$slug = slugify($truncatedText);
	
	    //$url = "$languageName/$slug";
		
		//get random thumbnails
		$randomKey = array_rand($thumbnails, 1);
        $randomThumbnail = $thumbnails[$randomKey];
		
		//excludeFromHomePage: true in front matter for all non english languages
		//$excludeFromHomePage = 'true';
		//if($languageName == "English"){ $excludeFromHomePage = 'false';}
		
		
		
		$post_template = <<<EOF
---
title: "$title"
slug: "$slug"
description: "$metaDescription"
date: "$timeStamp"
tags: [$tags]
image: "$randomThumbnail"
video: 
 url: "$videoUrl" 
 duration: "$duration" 
 image: "$randomThumbnail" 
---

<p>$description</p>
EOF;

//when creating post, for $languageCode zh-CN is changed to zh (hugo don't recognise zh-CN)
if($languageCode == 'zh-CN'){ $languageCode = 'zh'; }

//create posts
$filename = "../content/posts/videos/$videoID/$languageName.$languageCode.html";


$dir = dirname($filename);

if (!is_dir($dir)) {
	mkdir($dir, 0755, true); // Create directory with permissions and recursive
}

if (file_put_contents($filename, $post_template) === false) {
        throw new Exception("Failed to create file: " . $filename);
    }
	
	
	}
	

    //echo "Title=> " . $title . "\n";
    //echo "Tags=> " . $tags . "\n\n";
}

//finally echo ok
echo "ok";
//print_r($translations);





            



function validateAndReturnJson($filePath) {
    if (!file_exists($filePath)) {
        return ['error' => 'File not found'];
    }

    $fileContents = file_get_contents($filePath);
    $data = json_decode($fileContents, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['error' => 'Invalid JSON: ' . json_last_error_msg()];
    }

    return $data;
}

function validateJsonForUrlOrDataImage($data){
	foreach ($data as $key => $value) {//echo $value ."\n\n";
        if (!is_string($value)) {
            return ['error' => 'Values must be strings'];
        }
        if (!filter_var($value, FILTER_VALIDATE_URL) && !preg_match('/^data:image/', $value)) {
            return ['error' => 'Invalid value: ' . $value];
        }
    }

    return $data;
}


function slugify($string) {
    // Array of characters to be replaced with an empty string
    $unwanted_chars = [';', ',', '.', '!', '?', '@', '#', '$', '%', '^', '&', '*', '(', ')', '_', '=', '+', '[', ']', '{', '}', '\\', '|', ':', '"', "'", '<', '>', '/', '`', '~'];

    // Replace each unwanted character with an empty string
    foreach ($unwanted_chars as $char) {
        $string = str_replace($char, '', $string);
    }

    // Replace spaces with hyphens
    $string = str_replace(' ', '-', $string);

    // Convert to lowercase (handles multibyte characters properly)
    //$string = mb_strtolower($string, 'UTF-8');

    // Replace multiple hyphens with a single hyphen
    $string = preg_replace('/-+/', '-', $string);

    // Trim hyphens from both ends
    $string = trim($string, '-');

    return $string;
}


//need to truncate the title for slugify, cloudflare don not support more than 100 chars long name
function truncateString($text, $limit = 100, $encoding = 'UTF-8') {
  $words = preg_split('/\s+/', $text); // Split on one or more whitespace characters
  $output = '';
  $i = 0;

  while (isset($words[$i]) && mb_strlen($output, $encoding) + mb_strlen($words[$i], $encoding) + 1 <= $limit) {
    $output .= $words[$i] . ' ';
    $i++;
  }

  return trim($output, ' ');
}

//not using it for now
function validateDuration($duration) {
    // Regular expressions for h:m:s and m:s formats
    $pattern1 = '/^\d{1,2}:\d{2}:\d{2}$/';
    $pattern2 = '/^\d{1,2}:\d{2}$/';

    // Check if the duration matches either pattern
    if (preg_match($pattern1, $duration) || preg_match($pattern2, $duration)) {
        // Basic validation passed, but further checks can be added
        // For example, check if hours are less than 24, minutes and seconds less than 60
        $parts = explode(':', $duration);
        if (count($parts) == 3) {
            // h:m:s format
            list($hours, $minutes, $seconds) = $parts;
            if ($hours >= 24 || $minutes >= 60 || $seconds >= 60) {
                return false;
            }
        } elseif (count($parts) == 2) {
            // m:s format
            list($minutes, $seconds) = $parts;
            if ($minutes >= 60 || $seconds >= 60) {
                return false;
            }
        }
        return true;
    } else {
        return false;
    }
}
