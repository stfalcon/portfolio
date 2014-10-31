<?php
if (!isset($_GET['pack'])) {
    echo 'error data';
    die;
}

function setData($data, $name)
{
    $aData = array(
        'data' => trim($data),
        'time' => time() + 3600,
    );

    file_put_contents('./cache/' . $name, json_encode($aData));
}

$file = md5($_GET['pack']).'.cache';
if (is_file($file)) {
    $fileDataRaw = file_get_contents('./cache/' . $file);
    $fileData = json_decode($fileDataRaw);
    if ($fileData->time > time()) {
        die($fileData->data . '');
    }
}

$url = 'https://play.google.com/store/apps/details?id=' . $_GET['pack']; //com.stfalcon.nizam.android'

$page = file_get_contents($url);
$page = str_replace(["\n", "\r"], [''], $page);

$matches = false;
preg_match_all('/\"softwareVersion\">([\d\s\.]+)<\/div>/Ui', $page, $matches);

if (isset($matches[1][0])) {
    echo trim($matches[1][0]);
    setData($matches[1][0], $file);
} else {
    echo 'null';
    setData('null', $file);
}
