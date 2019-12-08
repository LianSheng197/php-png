<?php
// Special-03: 800x600, c-haner points line graph
require $_SERVER["DOCUMENT_ROOT"] . "/func/main.php";
require $_SERVER["DOCUMENT_ROOT"] . "/func/special.php";

header("Content-type: image/png");
$arrval = array(12, 123, 21, 32, 77, 85, 166, 176, 163, 121);
$height = 600;
$width = 800;
$im = imagecreate($width, $height);
$white = imagecolorallocate($im, 255, 255, 255);
$gray = imagecolorallocate($im, 200, 200, 200);
$black = imagecolorallocate($im, 33, 33, 33);
$red = imagecolorallocate($im, 200, 50, 50);

$padding_x = 40;
$padding_y = 40;


$x = 21;
$y = 11;
$num = 0;
while ($x <= $width && $y <= $height) {
    $prcnt = ((($height - 50) - ($y - 1)) / ($height - 60)) * 100;
    imageline($im, 21, $y, $width - 10, $y, $gray);
    imageline($im, $x, 11, $x, $height - 50, $gray);

    // imagestring($im, 2, 1, $y - 10, $prcnt . '%', $red);
    imagestring($im, 2, $x - 3, $height - 40, $num, $red);
    $x += 30;
    $y += 20;
    $num++;
}
$tx = 20;
$ty = 210;
foreach ($arrval as $values) {
    $cx = $tx + 30;
    $cy = 200 - $values;
    imageline($im, $tx, $ty, $cx, $cy, $red);
    imagestring($im, 5, $cx - 3, $cy - 13, '.', $red);
    $ty = $cy;
    $tx = $cx;
}

// x-axis line
imageline($im, 20, $height - 49, $width - 10, $height - 49, $black);
// y-axis line
imageline($im, $padding_x, 11, $padding_x, $height - 50, $black);

imagestring($im, 30, 100, $height - 20, 'Line Graph by: Roseindia Technologies', $red);
imagepng($im);

ImageDestroy($im);