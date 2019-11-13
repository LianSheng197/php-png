<?php
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
