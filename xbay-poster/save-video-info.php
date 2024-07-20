<?php

// Access the POST data (using php://input for flexibility)
$rawData = file_get_contents('php://input');
$data = json_decode($rawData, true);

if (!$data) {
  echo json_encode(['error' => 'Invalid data received']);
  exit;
}


$id = $data['videoID'];

$filePath = "xbay-data/$id/videoInfo.json";

$dir = dirname($filePath);

if (!is_dir($dir)) {
    mkdir($dir, 0755, true); // Create directory with permissions and recursive
}

file_put_contents($filePath, $rawData);
echo 'ok';