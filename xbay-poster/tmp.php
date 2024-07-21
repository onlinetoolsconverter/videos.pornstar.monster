<?php 

echo $title = 'சிவப்பு தலை லாரன் ஹில் bbw நிர்வாணமாகவும் நிக்கோல் கொலினாவும் பெரிய மார்பகங்கள் லெஸ்பியன் ஆபாச XXX [பார்ன்மெகாலோட்] Video';
$title = 'We need to use functions that handle multi-byte characters correctly. PHP provides the mb_strlen() function for this purpose.';
echo '<br>';
$truncatedText = truncateString($title, 100);
		echo $slug = slugify($truncatedText);

echo '<br>';
echo mb_strlen($slug);	
function slugify($string) {
    // Array of characters to be replaced with an empty string
    $unwanted_chars = [';', ',', '.', '!', '?', '@', '#', '$', '%', '^', '&', '*', '(', ')', '_', '=', '+', '[', ']', '{', '}', '\\', '|', ':', '"', "'", '<', '>', '/', '`', '~'];

    // Replace each unwanted character with an empty string
    foreach ($unwanted_chars as $char) {
        $string = str_replace($char, '', $string);
    }

    // Replace spaces with hyphens
    $string = str_replace(' ', '-', $string);

    // Convert to lowercase (handles multibyte characters properly)
    //$string = mb_strtolower($string, 'UTF-8');

    // Replace multiple hyphens with a single hyphen
    $string = preg_replace('/-+/', '-', $string);

    // Trim hyphens from both ends
    $string = trim($string, '-');

    return $string;
}
function truncateString($text, $limit = 100, $encoding = 'UTF-8') {
  $words = preg_split('/\s+/', $text); // Split on one or more whitespace characters
  $output = '';
  $i = 0;

  while (isset($words[$i]) && mb_strlen($output, $encoding) + mb_strlen($words[$i], $encoding) + 1 <= $limit) {
    $output .= $words[$i] . ' ';
    $i++;
  }

  return trim($output, ' ');
}
