<?php

// Access the POST data (using php://input for flexibility)
$rawData = file_get_contents('php://input');
$data = json_decode($rawData, true);

if (!$data) {
  echo json_encode(['error' => 'Invalid data received']);
  exit;
}


$id = $data['videoID'];

file_put_contents("xbay-data/$id-videoInfo.json", $rawData);
echo 'ok';