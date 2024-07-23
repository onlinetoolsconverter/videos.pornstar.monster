<?php 
$text = $_POST['text'];

$model="unfiltered_x";
$session_id = "X4u3dkQwP1KmgBJoQQ3RKlGPxGXcRLpeR3v2";
//$session_id = generateRandomString(); //if the above stop working
$referralUrl = "https://toolbaz.com/writer/paragraph-generator";

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
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification

    // Grab our result
    $result = curl_exec($ch);

    // Close cURL resource after use
    curl_close($ch);

    return $result;
}


$response = sendPostRequest($text, $model, $session_id, $referralUrl);

$response = preg_replace('/\[model: .+\]/', '', $response);
$response = trim($response);
echo $response;



function generateRandomString($length = 36) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}