<?php
Header("Content-type: image/png");
date_default_timezone_set("Asia/Taipei");

$ttf = $_SERVER["DOCUMENT_ROOT"] . "/ttf/WenQuanYiZenHeiMono-02.ttf";
$demo_string = "
這是一張實際上不存在的圖片。|
它由 php 函數 ImagePng() 產生，|
再用 ImageTTFText() 插入文字的。|
|
下列是目前支援的參數設定：|
     s: 圖片嵌入文字，以豎線 (Vertical bar) 作爲換行符號|
        （一般豎線是放在 ENTER 鍵附近）|
     w: 圖片寬度|
     h: 圖片高度|
  size: 字體大小|
    bc: 背景顏色|
    fc: 字體顏色|
|
＊使用字體：文泉驛等寬正黑|
（謎之音：但是看起來有些地方不夠等寬= =\"?）
";

$text = isset($_GET['s']) ? $_GET['s'] : $demo_string;
$item_text = explode("|", $text);
$size = isset($_GET['size']) ? $_GET['size'] : 14;
$width = isset($_GET['w']) ? $_GET['w'] : 600;
$height = isset($_GET['h']) ? $_GET['h'] : 430;
$bc = isset($_GET['bc']) ? $_GET['bc'] : "333";
$bgcolor = explode(",", hex2rgb($bc));
$fc = isset($_GET['fc']) ? $_GET['fc'] : "CCC";
$fontcolor = explode(",", hex2rgb($fc));

/* -------- Special Parameter -------- */
// 不檢查高度（設定爲 true，未設定爲 false）
isset($_GET['nohck']) ? $height_chack = false : $height_chack = true; 
// 顯示 IP，格式："ip=<pos_x>,<pos_y>,[size],[fontcolor]"
$show_ip = isset($_GET['ip']) ? $_GET['ip'] : 0; 

$angle = 0;
$pos_x = 6;
$pos_y = 6 + $size;
$image = imagecreate($width, $height);
$bgcolor = ImageColorAllocate($image, $bgcolor[0], $bgcolor[1], $bgcolor[2]);
$color = ImageColorAllocate($image, $fontcolor[0], $fontcolor[1], $fontcolor[2]);

// 主要：插入文字
for ($i = 0; $i < sizeof($item_text); $i++) {
    if ($height_chack && $pos_y >= $height - $size) {
        break;
    }
    $line = utf8str($item_text[$i]);

    # 插入文字
    ImageTTFText($image, $size, $angle, $pos_x, $pos_y, $color, $ttf, $line);

    $pos_y += $size * 2;
}

// 特殊：顯示訪客的 IP
if ($show_ip != 0) {
    $pos = explode(",", $show_ip);
    $px = $pos[0];
    $py = $pos[1];
    $ip_size = isset($pos[2])? $pos[2] : $size;
    $ip_color_pre = isset($pos[3])? explode(",", hex2rgb($pos[3])) : explode(",", hex2rgb("CCC"));
    $ip_color = ImageColorAllocate($image, $ip_color_pre[0], $ip_color_pre[1], $ip_color_pre[2]);
    $ip = getIP();

    utf8str($ip);
    ImageTTFText($image, $ip_size, $angle, $px, $py, $ip_color, $ttf, $ip);
}

ImagePng($image);
ImageDestroy($image);

function utf8str($str) {
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

function getIP()
{
    if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    } else {
        $ip = $_SERVER["REMOTE_ADDR"];
    }

    return ($ip == "::1") ? "127.0.0.1" : $ip;
}

// debug
function debug(...$data)
{
    error_log("[" . date("Y-m-d H:i:s") . "]\n" . json_encode($data) . "\n", 3, "./debug.log");
}
