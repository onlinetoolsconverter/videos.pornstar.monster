<?php 

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

$text = "rewrite this porn video title: Maddy May and Jamie Jett loves anal sex, also write a meta descripttion for this video within 150 chars, also write a description about this video, you can use these keywords to write taitle, meta description and description 'All Anal, anal, anal creampie, Ass, ass eating, big tits, Blonde, blowjob, brown hair, Brunette, Fake boobs, gaping, Natural boobs, piercings, pornstar, tattoo, toys' and place all these in a json file.";
$response = sendPostRequest($text, $model, $session_id, $referralUrl);

$response = preg_replace('/\[model: .+\]/', '', $response);
$response = trim($response);
echo $response;