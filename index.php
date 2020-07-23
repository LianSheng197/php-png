<?php
require($_SERVER["DOCUMENT_ROOT"] . "/func/main.php");

Header("Content-type: image/png");

$demo_string = "
這是一張實際上不存在的圖片。||
只需要將文字打在網址末端即可產生塞有對應文字的圖片，如需換行則打上豎線 \"\|\"|
舉個栗子：https://php-png.herokuapp.com/Line 1\|Line 2\|Line 3||
＊使用字體：文泉驛等寬正黑|
＊這是怎麽做的？|
  參考原始碼：在任何狀況時，於網址末端打上 \"/code\" 即可自動導向。|
  Ex: https://php-png.herokuapp.com/xxx.../code
";

$text = isset($_GET['s']) ? $_GET['s'] : $demo_string;
$item_text = preg_split("/(?<!\\\\)\|/", $text);
$size = 14;
$bc = isset($_GET['bc']) ? $_GET['bc'] : "333";
$bgcolor = explode(",", hex2rgb($bc));
$fc = isset($_GET['fc']) ? $_GET['fc'] : "CCC";
$fontcolor = explode(",", hex2rgb($fc));

$angle = 0;
$pos_x = 6;
$pos_y = 6 + $size;

// calc height by word lines.
$height = $size * count($item_text) * 2 + 6;

// calc width by the longest lines. (have some difference, may caused by fonts.)
$longest = ($size + 1) * 2;
foreach ($item_text as $line) {
    $length = mb_strwidth($line, "UTF-8") * $size * 0.75 + 6;
    if ($length > $longest) {
        $longest = $length;
    }
}
$width = $longest;

$image = imagecreate($width, $height);
$bgcolor = ImageColorAllocate($image, $bgcolor[0], $bgcolor[1], $bgcolor[2]);
$color = ImageColorAllocate($image, $fontcolor[0], $fontcolor[1], $fontcolor[2]);

// 主要：插入文字
for ($i = 0; $i < sizeof($item_text); $i++) {
    $line = utf8str(str_replace("\|", "|", $item_text[$i]));

    # 插入文字
    ImageTTFText($image, $size, $angle, $pos_x, $pos_y, $color, $ttf, $line);

    $pos_y += $size * 2;
}

ImagePng($image);
ImageDestroy($image);