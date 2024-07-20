<?php
$videoID = trim($_GET['id']);

if($videoID == ''){
	exit('Error: videoID not found.');
}

$languageCodes = [ 
"hi"=> "Hindi", "bn"=> "Bengali", "es"=> "Spanish", "ru"=> "Russian", "de"=> "German","fr"=> "French", "ja"=> "Japanese",	 "pt"=> "Portuguese","tr"=> "Turkish", "it"=> "Italian","fa"=> "Persian-Farsi","nl"=> "Dutch",
"pl"=> "Polish","zh-CN"=> "Chinese", "vi"=> "Vietnamese","id"=> "Indonesian","cs"=> "Czech","ko"=> "Korean", 
"uk"=> "Ukrainian","ar"=> "Arabic","el"=> "Greek","iw"=> "Hebrew", "sv"=> "Swedish","ro"=> "Romanian",
"hu"=> "Hungarian","th"=> "Thai", "da"=> "Danish","sk"=> "Slovak","fi"=> "Finnish","sr"=> "Serbian", 
"no"=> "Norwegian","bg"=> "Bulgarian","lt"=> "Lithuanian","sl"=> "Slovenian", "ca"=> "Catalan","et"=> "Estonian",
"lv"=> "Latvian","hr"=> "Croatian", "as" => "Assamese","gu" => "Gujarati","ml" => "Malayalam", "ne" => "Nepali", 
"mr" => "Marathi","pa" => "Punjabi","ta" => "Tamil","ka" => "Kannada", "te" => "Telugu","or" => "Oriya",
"ur" => "Urdu","ms" => "malay","en" => "English",];

foreach($languageCodes as $languageCode => $languageName){
	//echo $languageName;
	$filename = "../content/posts/videos/$languageName/$videoID.html";
	
	if (unlink($filename) === false) {
		throw new Exception("Failed to delete file: " . $file);
	}
}