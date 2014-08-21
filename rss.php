<?php

if (empty($_GET['url'])) {
    header('HTTP/1.0 404 Not Found');
    exit();
}

require_once 'SimpleXMLElementEx.php';
require_once 'functions.php';

$url = urldecode($_GET['url']);
$cacheFileNameId = md5($url);
$siteCacheFilePath = "cache/$cacheFileNameId.site.txt";

$firstTime = !file_exists($siteCacheFilePath);
$newPage = file_get_contents($url);

if ($firstTime === true) {
    $text = 'This is the first time the page was crawled, changes will now be monitored.';
    $hasChanges = true;
} else {
    $oldPage = file_get_contents($siteCacheFilePath);
    $text = xdiff_string_diff($oldPage, $newPage, 10);
    $hasChanges = !empty($text);
}

if ($hasChanges === true)
    file_put_contents($siteCacheFilePath, $newPage);

$feed = new SimpleXMLElementEx('<rss version="2.0"></rss>');
$feed->addChild('channel');
$feed->channel->addChild('title', 'sitefeed for ' . $url);
$imageItem = $feed->channel->addChild('image');
$imageItem->addChild('url', base_url('img/font-awesome-rss-black.png'));

//Old RSS items
foreach (glob("cache/$cacheFileNameId.rssitem*.txt") as $f) {
    $itemText = file_get_contents($f);
    $itemDate = base64_decode(get_string_between($f, 'rssitem-', '.txt'));

    $item = $feed->channel->addChild('item');
    $item->addChild('title', "sitefeed: Change detected for $url");
    $item->addChild('link', $url);
    $descriptionChild = $item->addChild('description');
    $descriptionChild->addCData($itemText);
    $item->addChild('pubDate', $itemDate);
    $item->addChild('author', 'sitefeed');
}

//New RSS item
if ($hasChanges === true) {
    $date = date(DATE_RSS);
    $text = htmlentities($text);

    $item = $feed->channel->addChild('item');
    $item->addChild('title', "sitefeed: Change detected for $url");
    $item->addChild('link', $url);
    $descriptionChild = $item->addChild('description');
    $descriptionChild->addCData($text);
    $item->addChild('pubDate', $date);
    $item->addChild('author', 'sitefeed');

    $itemCacheFilePath = "cache/$cacheFileNameId.rssitem-" . base64_encode($date) . '.txt';
    file_put_contents($itemCacheFilePath, $text);
}

header('Content-Type: application/rss+xml');
echo $feed->asXML();
