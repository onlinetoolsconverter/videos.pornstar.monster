<?php

$title = 'சிவப்பு தலை லாரன் ஹில் bbw நிர்வாணமாகவும் நிக்கோல் கொலினாவும் பெரிய மார்பகங்கள் லெஸ்பியன் ஆபாச XXX [பார்ன்மெகாலோட்] Video';
$truncatedText = truncateString1($title, 100);
echo $truncatedText;
//chatgpt
function truncateString($text, $limit = 100, $encoding = 'UTF-8') {
  $words = preg_split('/\s+/', $text); // Split on one or more whitespace characters
  $output = '';
  $i = 0;

  while (isset($words[$i]) && (mb_strlen($output, $encoding) + mb_strlen($words[$i], $encoding) + ($i > 0 ? 1 : 0)) <= $limit) {
    $output .= ($i > 0 ? ' ' : '') . $words[$i];
    $i++;
  }

  return $output;
}


//gimini
function truncateString1($text, $limit = 100, $encoding = 'UTF-8') {
  $words = preg_split('/\s+/', $text); // Split on one or more whitespace characters
  $output = '';
  $i = 0;

  while (isset($words[$i]) && mb_strlen($output, $encoding) + mb_strlen($words[$i], $encoding) + 1 <= $limit) {
    $output .= $words[$i] . ' ';
    $i++;
  }

  return trim($output, ' ');
}
