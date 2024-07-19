<?php
$id = $_GET['id'];
// URL of the MP4 file
$url = "https://s1.xbay.me/x/$id.mp4";

// Command to run ffprobe and get duration
$command = "ffprobe -i " . escapeshellarg($url) . " -show_entries format=duration -v quiet -of csv=\"p=0\"";

// Execute the command and capture output
$output = shell_exec($command);

// Convert duration to seconds
$duration_seconds = (int)round($output);

// Convert seconds to h:m:s format without leading zeros
$formatted_duration = gmdate('G:i:s', $duration_seconds);

// Remove leading '0' from hours if present
$formatted_duration = ltrim($formatted_duration, '0');
$formatted_duration = ltrim($formatted_duration, ':');

// Output the formatted duration
//echo "Duration: $formatted_duration\n";
echo $formatted_duration;
?>