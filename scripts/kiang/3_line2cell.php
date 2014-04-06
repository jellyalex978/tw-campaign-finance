<?php

/*
 * As we have had cross points, use this script to convert them to unique cells
 * 
 * Then we could deal with each cell indenpently.
 * 
 * Each cell should has following fields:
 * id -> concat(image-id, x number, y number)
 * x -> x of begin point
 * y -> y of begin point
 * width -> width of the cell
 * height -> height of the cell
 */

$path = dirname(dirname(__DIR__));

if (!file_exists("{$path}/pdf/cells")) {
    mkdir("{$path}/pdf/cells", 0777, true);
}

$oh = fopen($path . '/pdf/pdf2jpg.csv', 'r');
fgetcsv($oh, 512); //skip first line
while ($oFile = fgetcsv($oh, 512)) {
    /*
     * $oFile -> id,檔名,頁數,網址,圖寬,圖高
     */
    $oJsonFile = "{$path}/pdf/lines/{$oFile[0]}.json";
    if (!file_exists($oJsonFile)) {
        file_put_contents($oJsonFile, json_encode(array(
            'width' => $oFile[4],
            'height' => $oFile[5],
            'horizons' => array(),
            'verticles' => array(),
            'cross_points' => array(),
        )));
    }
    $oJson = json_decode(file_get_contents($oJsonFile));
    $imageObj = new stdClass();
    $imageObj->image_id = $oFile[0];
    $imageObj->document = $oFile[1];
    $imageObj->page_no = $oFile[2];
    $imageObj->url = $oFile[3];
    $imageObj->width = $oFile[4];
    $imageObj->height = $oFile[5];
    $imageObj->cells = array();
    $imageObj->cellCount = 0;
    $previousLine = array();
    $numberX = 0;
    if (!empty($oJson->cross_points)) {
        foreach ($oJson->cross_points AS $line) {
            if (empty($previousLine)) {
                $previousLine = $line;
                continue;
            }
            ++$numberX;
            $numberY = 0;
            $firstPointSkipped = false;
            foreach ($line AS $key => $point) {
                if (false === $firstPointSkipped) {
                    $firstPointSkipped = true;
                    continue;
                }
                ++$numberY;
                ++$imageObj->cellCount;
                if (!isset($imageObj->cells[$numberX])) {
                    $imageObj->cells[$numberX] = array();
                }
                $imageObj->cells[$numberX][$numberY] = array(
                    'id' => "{$oFile[0]}-{$numberY}-{$numberX}",
                    'x' => $previousLine[$key - 1][0],
                    'y' => $previousLine[$key - 1][1],
                    'width' => ($line[$key][0] - $previousLine[$key - 1][0]),
                    'height' => ($line[$key][1] - $previousLine[$key - 1][1]),
                );
            }
            $previousLine = $line;
        }
    }
    file_put_contents("{$path}/pdf/cells/{$oFile[0]}.json", json_encode($imageObj));
    continue;
    /*
     * to test cropped image
     */
    $img = imagecreatefromjpeg($imageObj->url);
    if (false !== $img) {
        foreach ($imageObj->cells AS $x => $line) {
            foreach ($line AS $y => $cell) {
                $croppedImg = imagecrop($img, $cell);
                if (false !== $croppedImg) {
                    imagepng($croppedImg, "{$path}/pdf/cells/{$cell['id']}.png");
                    unset($croppedImg);
                }
            }
        }
    }
}