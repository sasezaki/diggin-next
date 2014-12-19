<?php
// Assuming you installed from Composer:
require "vendor/autoload.php";
use Masterminds\HTML5;


// An example HTML document:
$html = <<< 'HERE'
  <html>
  <head>
    <title>TEST</title>
  </head>
  <body id='foo'>
    <h1>Hello World</h1><foo></foo>
    <p>This is a test of the HTML5 parser.</p>
<video>
<source src='sample.ogv' type='video/ogg; codecs="theora, vorbis"'>
<source src='sample.mp4' type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
<p>動画を再生するには、videoタグをサポートしたブラウザが&必要&amp;で>す。</p>
</video>
  </body>
  </html>
HERE;

// $d = new DOMDocument('1.0', 'UTF-8');
// $d->loadHTML($html);
// var_dump($d->saveHTML($d));

$html5 = new HTML5();
$dom = $html5->loadHTML($html);
var_dump($dom->saveHTML($dom));