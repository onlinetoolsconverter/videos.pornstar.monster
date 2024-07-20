<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST['data'];
    $id = $filename = $_POST['filename'];
	
	
	//$json_data = json_decode($data, true);
	//$data = $json_string = json_encode($json_data, JSON_PRETTY_PRINT);

    if (!empty($data) && !empty($filename)) {
		
		$filePath = "xbay-data/$id/translations.json";

		$dir = dirname($filePath);

		if (!is_dir($dir)) {
			mkdir($dir, 0755, true); // Create directory with permissions and recursive
		}
		
        
        file_put_contents($filePath, $data);
        echo "File saved successfully!";
    } else {
        echo "Data or filename is missing!";
    }
} else {
    echo "Invalid request method!";
}