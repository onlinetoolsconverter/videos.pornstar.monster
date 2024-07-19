<?php 
$id = $_GET['id'];
echo file_get_contents("https://xbay.me/view.php?x=$id");