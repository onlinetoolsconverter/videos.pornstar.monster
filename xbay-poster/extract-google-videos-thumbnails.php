<?php



// Access the POST data (using php://input for flexibility)
$rawData = file_get_contents('php://input');
$data = json_decode($rawData, true);

if (!$data) {
  echo json_encode(['error' => 'Invalid data received']);
  exit;
}

$html_content = $data['googleVideoHtml'];
$title = $data['titleInput'];
$id = $data['videoID'];
//exit;


//$html_content = file_get_contents('New Text Document.txt');  // Replace with your actual HTML content

// Create a DOMDocument object
$dom = new DOMDocument();
@$dom->loadHTML($html_content);

// Create an XPath object
$xpath = new DOMXPath($dom);

// Find the blocks
$blocks = $xpath->query("//*[@id='search']//div[@jsname]");

// Extract URLs from the script tags
$scripts = $xpath->query("//script");
$image_urls = [];

foreach ($scripts as $script) {
    $script_content = $script->nodeValue;
    if ($script_content) {
        // Match the script with image URLs
        if (preg_match("/google\.ldi={(.*?)};/", $script_content, $match_ldi)) {
            $urls = json_decode('{' . str_replace("'", "\"", $match_ldi[1]) . '}', true);
            $image_urls = array_merge($image_urls, $urls);
        }

        // Match the script with base64 images
        if (preg_match("/s='(data:image\/.*?)';var ii=\['(.*?)'\];.*?_setImagesSrc\(ii,s\)/", $script_content, $match_base64)) {
            $image_data = $match_base64[1];
            // Step 1: Clean the Data
            $image_data = str_replace("\\x3d", "=", $image_data);
            $image_id = $match_base64[2];
            $image_urls[$image_id] = $image_data;
            
        }
    }
}

// Print the image URLs
//print_r($image_urls);
// Use $image_urls as needed

file_put_contents("xbay-data/$id-thumbnails.json", json_encode($image_urls));
echo 'ok';
?>
