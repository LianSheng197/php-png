<?php
Header("Content-type: image/png");
date_default_timezone_set("Asia/Taipei");

$ttf = $_SERVER["DOCUMENT_ROOT"] . "/ttf/WenQuanYiZenHeiMono-02.ttf";
$demo_string = "
這是一張實際上不存在的圖片。||
只需要將文字打在網址末端即可產生塞有對應文字的圖片，如需換行則打上豎線 (\|)|
舉個栗子：https://php-png.herokuapp.com/Line 1\|Line 2\|Line 3||
＊使用字體：文泉驛等寬正黑|
＊這是怎麽做的？|
  參考原始碼：在任何狀況時，於網址末端打上 \"/code\" 即可自動導向。|
  Ex: https://php-png.herokuapp.com/....../code
";

$text = isset($_GET['s']) ? $_GET['s'] : $demo_string;
$item_text = preg_split("/(?<!\\\\)\|/", $text);
$size = 14;
$bc = isset($_GET['bc']) ? $_GET['bc'] : "333";
$bgcolor = explode(",", hex2rgb($bc));
$fc = isset($_GET['fc']) ? $_GET['fc'] : "CCC";
$fontcolor = explode(",", hex2rgb($fc));

/* -------- Special Parameter -------- */
$height_chack = true;
// 顯示 IP，格式："ip=<pos_x>,<pos_y>,[size],[fontcolor]"
$show_ip = isset($_GET['ip']) ? $_GET['ip'] : 0;

$angle = 0;
$pos_x = 6;
$pos_y = 6 + $size;

// calc height by word lines.
$height = ($size + 1) * count($item_text) * 2 + 6;

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
    if ($height_chack && $pos_y >= $height - $size) {
        break;
    }
    $line = utf8str(str_replace("\|", "|", $item_text[$i]));

    # 插入文字
    ImageTTFText($image, $size, $angle, $pos_x, $pos_y, $color, $ttf, $line);

    $pos_y += $size * 2;
}

ImagePng($image);
ImageDestroy($image);

function utf8str($str)
{
    # 偵測引入字串的編碼
    $text_encoding = mb_detect_encoding($str, 'UTF-8, ISO-8859-1');
    # 確保最後結果是以 UTF-8 編碼
    if ($text_encoding != 'UTF-8') {
        $str = mb_convert_encoding($str, 'UTF-8', $text_encoding);
    }
    # HTML Entity number to HEX (&#[dec];)
    $str = mb_encode_numericentity($str,
        array(0x0, 0xffff, 0, 0xffff), 'UTF-8');

    return $str;
}

function hex2rgb($hex)
{
    $hex = str_replace("#", "", $hex);

    switch (strlen($hex)) {
        case 1:
            $hex = $hex . $hex;
        case 2:
            $r = hexdec($hex);
            $g = hexdec($hex);
            $b = hexdec($hex);
            break;
        case 3:
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
            break;
        default:
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
            break;
    }

    $rgb = array($r, $g, $b);
    return implode(",", $rgb);
}