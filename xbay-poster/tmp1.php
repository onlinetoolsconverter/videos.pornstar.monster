<?php

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

$text= "Write+a+meta+description+in+this+topic%3A+Lauren+Hill+bbw+nude+And+Nicole+Colina+huge+tits+Lesbian";
$model="unfiltered_x";
$session_id = "X4u3dkQwP1KmgBJoQQ3RKlGPxGXcRLpeR3v2";
$referralUrl = "https://toolbaz.com/writer/meta-description-generator";

echo sendPostRequest($text, $model, $session_id, $referralUrl);



/*

function gRS(length) {
    const c = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let result = '';
    const cLength = c.length;
    for (let i = 0; i < length; i++) {
        result += c.charAt(Math.floor(Math.random() * cLength));
    }
    return result;
}

function setC(name, value, days) {
    const d = new Date();
    d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
    const expires = "expires=" + d.toUTCString();
    document.cookie = name + "=" + (value || "") + ";" + expires + ";path=/";
}

function updateC() {
    const rS = gRS(36);
    setC('SessionID', rS, 1); 
}


updateC();

setInterval(updateC, 120000);
*/





