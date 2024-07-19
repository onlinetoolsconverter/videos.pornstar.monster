<?php 
//$html_content = $_POST['html_content'];
$html_content = file_get_contents('New Text Document.txt');
//$title = $_POST['title'];

$dom = new DOMDocument();
@$dom->loadHTML($html_content);

// Find the search block
$xpath = new DOMXPath($dom);
$blocks = $xpath->query("//div[@id='search']//div[@jsname]");

// Initialize image URLs array
$image_urls = [];

// Loop through script tags
$scripts = $dom->getElementsByTagName('script');
foreach ($scripts as $script) {
    $script_content = $script->textContent;  // Access script content

    if ($script_content) {
        // Match the script with image URLs
        $ldi_match = $xpath->query("//text()[contains(., 'google.ldi=')]");
        if ($ldi_match->length > 0) {
            $ldi_text = $ldi_match->item(0)->textContent;
            $matches = explode(';', $ldi_text);  // Split at semicolon
            if (isset($matches[1])) {
                $json_string = '{' . str_replace("'", '"', trim($matches[1])) . '}';  // Clean and wrap in curly braces
                $urls = json_decode($json_string, true);
                $image_urls = array_merge($image_urls, $urls);
            }
        }

        // Match the script with base64 images
        $base64_match = $xpath->query("//text()[contains(., 's=\\''data:image/')]");
        if ($base64_match->length > 0) {
            $base64_text = $base64_match->item(0)->textContent;
            $matches = explode(');', $base64_text);  // Split at closing parenthesis
            if (isset($matches[0])) {
                $parts = explode("'", $matches[0]);  // Split at single quotes
                if (isset($parts[1]) && isset($parts[3])) {
                    $image_data = $parts[1];
                    // Step 1: Clean the Data
                    $image_data = str_replace("\\x3d", "=", $image_data);
                    $image_id = $parts[3];
                    $image_urls[$image_id] = $image_data;
                }
            }
        }
    }
}

// Use $image_urls as needed
file_put_contents('t.json', json_encode($image_urls));
?>