<?php
$videID = $_GET['id'];

$now = new DateTime('now', new DateTimeZone('UTC'));
// Format the datetime as a string
$timeStamp = $now->format('Y-m-d\TH:i:s\+00:00');



@$post_template = <<<EOF
---
title: "$title"
slug: "$sluz"
url: "$url"
description: "$metaDescriptin"
date: "$timeStamp"
tags: [$tags]
image: "$thumbnail"
images: []
video: 
 url: "$videoUrl" 
 duration: "$duration" 
 image: "$thumbnail" 
---

<p>$description</p>


EOF;
