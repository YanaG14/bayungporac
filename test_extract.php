<?php
require 'file_extractor.php';

$file = 'uploads/1775548871_sample code docs.docx'; // change to your file path

$text = extractText($file);

echo "<pre>";
echo substr($text, 0, 1000); // show first 1000 chars