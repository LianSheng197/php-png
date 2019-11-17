<?php
function getDNT()
{
    return (isset($_SERVER['HTTP_DNT']) && $_SERVER['HTTP_DNT'] == 1);
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

function getRef()
{
    if (isset($_SERVER["HTTP_REFERER"])) {
        $result = $_SERVER["HTTP_REFERER"];
    } else {
        $result = false;
    }

    return $result;
}

function getTitle(string $url)
{
    $str = file_get_contents($url);
    if (strlen($str) > 0) {
        $str = trim(preg_replace('/\s+/', ' ', $str));
        // ignore case
        preg_match("/\<title\>(.*)\<\/title\>/i", $str, $title);

        return $title[1];
    }
}