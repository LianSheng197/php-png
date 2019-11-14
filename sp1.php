<?php
// Special-01: 600x125, sign. get visitor's referer url and title.
require $_SERVER["DOCUMENT_ROOT"] . "/func/main.php";
require $_SERVER["DOCUMENT_ROOT"] . "/func/special.php";

Header("Content-type: image/png");
date_default_timezone_set("Asia/Taipei");

$ttf = $_SERVER["DOCUMENT_ROOT"] . "/ttf/WenQuanYiZenHeiMono-02.ttf";

$dnt_open = getDNT();
$ref = getRef();

if ($dnt_open) {
    $text = "你的瀏覽器告訴我...|噢不！你似乎開啓了「不要追蹤我」的選項。|那只好尊重你的選擇了。|（放心，這張圖不會存入任何資料。）";
} else {
    if ($ref != "Error_No_Referer") {
        $title = getTitle($ref);
        $text = "你似乎正在瀏覽「${title}」...？|($ref)";
    } else {
        $text = "你似乎開了新分頁看著這張圖...？|或者你的瀏覽器不支援此功能。";
    }
}

$item_text = preg_split("/(?<!\\\\)\|/", $text);
$size = 14;
$bc = "333";
$bgcolor = explode(",", hex2rgb($bc));
$fc = "CCC";
$fontcolor = explode(",", hex2rgb($fc));

$angle = 0;
$pos_x = 6;
$pos_y = 6 + $size;

$width = 600;
$height = 125;

$image = imagecreate($width, $height);
$bgcolor = ImageColorAllocate($image, $bgcolor[0], $bgcolor[1], $bgcolor[2]);
$color = ImageColorAllocate($image, $fontcolor[0], $fontcolor[1], $fontcolor[2]);

for ($i = 0; $i < sizeof($item_text); $i++) {
    $line = utf8str(str_replace("\|", "|", $item_text[$i]));
    ImageTTFText($image, $size, $angle, $pos_x, $pos_y, $color, $ttf, $line);
    $pos_y += $size * 2;
}

ImagePng($image);
ImageDestroy($image);
