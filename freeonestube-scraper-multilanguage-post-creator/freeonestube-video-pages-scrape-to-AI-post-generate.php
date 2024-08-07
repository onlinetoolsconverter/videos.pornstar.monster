<!DOCTYPE html>
<html>
<head>
    <title>AI Freeonestube video post Genrerator</title>    
</head>

<body>
    <div class="input-row">
	 <form>
        <input type="text" placeholder="Enter Freeonestube video URL" value="<?php echo @$url; ?>" name="url">
        <input type="submit" value="Submit">
	 </form>
    </div>
	
	<div id="output">
	<pre id="jsonOutput"></pre>
<?php

if(isset($_GET['url'])){ $url = $_GET['url']; }
else{ die("Must provide a url."); }

$html = file_get_contents($url);


$videoInfo = parseVideoInfo($html);

$name = $videoInfo['name'];
$tags = $videoInfo['tags'];
$tags = implode(',', $tags);

$duration = $videoInfo['duration'];
$thumbnailUrl = $videoInfo['thumbnailUrl'];
$contentURL = $videoInfo['contentURL'];

$videoBaseUrl = dirname($contentURL) . '/';

$sources_data = extract_video_sources($html);

if ($sources_data) {
  // Process the extracted sources
  $sources = "sources: " . json_encode($sources_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
  $video_sources = $sources_data;
} else {
  echo "Error extracting sources";
  $sources = "";
}



$model="unfiltered_x";
//$session_id = "X4u3dkQwP1KmgBJoQQ3RKlGPxGXcRLpeR3v2";
$session_id = generateRandomString(); //if the above stop working
$referralUrl = "https://toolbaz.com/";

$text = "Generate SEO-friendly and human-readable engaging and attractive title, meta description, slug, and  description for a porn/adult video based on the following information:
Title: $name
Actor/Actress/Tags: $tags

Do not use the 'encounter' word while generating texts, instead use 'porn, scean, clip, video, xxx, adult video, sexy video, sex video, fucking, erotic video, nsfw video' etc or something similar.
title should be a different from the provided actual title, to generate the title use the provided keywords,
meta descripttion should be 130 to 140 chars, 
use variety and all the provided keywords in description,
slug should be 4 to 5 words and do not contain any stop words and dash separeted, 
and place all these in a json file.
Remember keys of the json must be 'title', 'metaDescription', 'description', 'slug'.
";

$response = sendPostRequest($text, $model, $session_id, $referralUrl);

// Replace backticks with an empty string or any other character if necessary
$response = str_replace('\\`', "'", $response);
$response = str_replace('`', "'", $response);

$response = preg_replace('/\[model: .+\]/', '', $response);
$response = trim($response);

echo $response;

$json_data = extract_json($response);

if ($json_data !== false) {
  //print_r($json_data);
  $title = $json_data['title'];
  $meta_description = $json_data['metaDescription'];
  $description = $json_data['description'];
  $slug = $json_data['slug'];
  
  //insert original video data
  //insert Tags
  $json_data['tags'] = $tags;
  
  $json_data['originalTitle'] =  $name;
  $json_data['duration'] =  $duration;
  $json_data['thumbnailUrl'] = $thumbnailUrl;
  $json_data['contentURL'] = $contentURL;

  $json_data['videoBaseUrl'] = $videoBaseUrl;
  
  $json_data['videoSources'] = $video_sources;

  //put json data to a file
  file_put_contents("json_data/$slug.json", json_encode($json_data));
  
  //create a translate page link
  echo "<center><a href='transtale-video-info.php?slug=$slug' target='_blank'>Translate</a></center>";
  
  // Convert title to lowercase for case-insensitive comparison
  $lowerTitle = strtolower($title);

  // Check if the title ends with "video"
  if (substr($lowerTitle, -5) !== 'video') {
    $title .= ' Video';
  }
  
} else {
  exit( "No valid JSON found.");
}

/*
//current time stamp
$now = new DateTime('now', new DateTimeZone('UTC'));
// Format the datetime as a string
$timeStamp = $now->format('Y-m-d\TH:i:s\+00:00');

createPost();

function createPost(){
	global $title, $slug, $meta_description, $timeStamp, $tags, $thumbnailUrl, $contentURL, $videoBaseUrl, $duration, $sources, $description;
	
	$post_template = <<<EOF
---
title: "$title"
slug: "$slug"
url: "video/$slug"
description: "$meta_description"
date: "$timeStamp"
tags: [$tags]
image: "$thumbnailUrl"
video: 
 url: "$contentURL"
 baseURL: "$videoBaseUrl"
 duration: "$duration"
 $sources 
 image: "$thumbnailUrl" 
---

<p>$description</p>
EOF;

//$filename = "../content/posts/videos/$slug.html";
$filename = "videos/$slug.html";


$dir = dirname($filename);

if (!is_dir($dir)) {
	mkdir($dir, 0755, true); // Create directory with permissions and recursive
}

$filename = getUniqueFilename($filename);

if (file_put_contents($filename, $post_template) === false) {
        throw new Exception("Failed to create file: " . $filename);
    }
	
}
*/

function sendPostRequest($text, $model, $session_id, $referralUrl) {
    // Create a new cURL resource
    $ch = curl_init();

    // Set URL and other appropriate options
    curl_setopt($ch, CURLOPT_URL, "https://data.toolbaz.com/writer.php"); // Replace with your actual endpoint
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
        'text' => $text,
        'model' => $model,
        'session_id' => $session_id
    )));
    curl_setopt($ch, CURLOPT_REFERER, $referralUrl);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification

    // Grab our result
    $result = curl_exec($ch);

    // Close cURL resource after use
    curl_close($ch);

    return $result;
}


function parseVideoInfo($html) {
  $dom = new DOMDocument();
  @$dom->loadHTML($html); // Suppress potential errors from invalid HTML

  $videoData = [];

  // Get video info from meta tags
  $xpath = new DOMXPath($dom);
  $nameNode = $xpath->query("//meta[@itemprop='name']")->item(0);
  $durationNode = $xpath->query("//meta[@itemprop='duration']")->item(0);
  $thumbnailUrlNode = $xpath->query("//meta[@itemprop='thumbnailUrl']")->item(0);
  $contentUrlNode = $xpath->query("//meta[@itemprop='contentURL']")->item(0);

  if ($nameNode) {
    $videoData['name'] = $nameNode->getAttribute('content');
  }

  if ($durationNode) {
    $duration = $durationNode->getAttribute('content');
    $videoData['durationP'] = $duration;
    $videoData['duration'] = convertDurationToHMS($duration);
  }

  if ($thumbnailUrlNode) {
    $videoData['thumbnailUrl'] = $thumbnailUrlNode->getAttribute('content');
  }

  if ($contentUrlNode) {
    $videoData['contentURL'] = $contentUrlNode->getAttribute('content');
  }

  // Get links from video-content-row div
  $contentRow = $dom->getElementById('video-about');
  if ($contentRow) {
    $links = $contentRow->getElementsByTagName('a');
    $videoData['tags'] = [];
    for ($i = 1; $i < $links->length - 1; $i++) { // Exclude first and last link
      $linkText = $links->item($i)->textContent;
      $videoData['tags'][] = $linkText;
    }
  }

  return $videoData;
}

function convertDurationToHMS($duration) {
  $matches = [];
  preg_match('/P(?:(\d+)D)?T(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?/', $duration, $matches);
  $days = isset($matches[1]) ? $matches[1] : 0;
  $hours = isset($matches[2]) ? $matches[2] : 0;
  $minutes = isset($matches[3]) ? $matches[3] : 0;
  $seconds = isset($matches[4]) ? $matches[4] : 0;

  $h_m_s = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
  $h_m_s = ltrim($h_m_s, '0');
  $h_m_s = ltrim($h_m_s, ':');
  //$h_m_s = ltrim($h_m_s, '0');
  return $h_m_s;
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


function extract_json($string) {
  $start_pos = strpos($string, '{');
  $end_pos = strrpos($string, '}');

  if ($start_pos !== false && $end_pos !== false) {
    $json_string = substr($string, $start_pos, $end_pos - $start_pos + 1);
    return json_decode($json_string, true); // Decode as associative array
  } else {
    return false; // No JSON found
  }
}


function extract_video_sources($html) {
  // Regular expression to match the JSON array (adjust if needed)
  $pattern = '/sources: \[([^\]]+?)\]/';
  $pattern = '/sources: (\[.*?\]),/';

  preg_match($pattern, $html, $matches);

  if (empty($matches)) {
    return false; // No sources found
  }

  $json_string = $matches[1];

  // Decode the JSON string
  $json_data = json_decode($json_string, true);

  if (json_last_error() !== JSON_ERROR_NONE) {
    return false; // JSON decoding error
  }

  return $json_data;
}



function generateRandomString($length = 36) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
	
    return $randomString;
}

//use this, if files overwritting
function getUniqueFilename($filename) {
  $newFilename = $filename;
  $counter = 1;

  while (file_exists($newFilename)) {
    $newFilename = pathinfo($filename, PATHINFO_DIRNAME) . '/' . pathinfo($filename, PATHINFO_FILENAME) . '-' . $counter . '.' . pathinfo($filename, PATHINFO_EXTENSION);
    $counter++;
  }

  return $newFilename;
}

?>

<style>
        body {
            font-family: Arial, sans-serif;
            text-align: left;
            background-color: #f0f0f0;
        }

        .input-row {
            display: flex;
            justify-content: left;
            align-items: left;
            margin: 20px;
        }

        input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 200px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
		
		pre {
		  font-family: monospace;
		  font-size: 18px;
		  color: #2f5bb7;
		  background-color: #f5f5f5;
		  padding: 10px;
		  overflow-x: auto; /* For long lines */
		  white-space: pre-wrap;
		}

    </style>


<script>
    const jsonString = '<?php echo addslashes(str_replace(["\r", "\n"], '', $response)); ?>'; // Replace with your JSON string

    try {
      const jsonObj = JSON.parse(jsonString);
      const formattedJson = JSON.stringify(jsonObj, null, 2);
      document.getElementById("jsonOutput").textContent = formattedJson;
    } catch (error) {
      console.error("Error parsing JSON:", error);
      document.getElementById("jsonOutput").textContent = "Invalid JSON";
    }
  </script>
  
  </div>
</body>
</html>
