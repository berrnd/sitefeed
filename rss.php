<?php

if (empty($_GET['url'])) {
    header('HTTP/1.0 404 Not Found');
    exit();
}

require_once 'SimpleXMLElementEx.php';
require_once 'functions.php';
require_once 'packages/paulgb/simplediff.php';

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
    $text = htmlDiff($oldPage, $newPage);

    if ($oldPage !== $newPage)
        $hasChanges = true;
}

if ($hasChanges === true) {
    file_put_contents($siteCacheFilePath, $newPage);

    $itemCacheFilePath = "cache/$cacheFileNameId.rssitem-" . base64_encode(date(DATE_RSS)) . '.txt';
    $text .= '<style>.sitefeed-ins { background-color: #aaffaa; } .sitefeed-del { background-color: #ff8888; text-decoration: line-through; }</style>';
    file_put_contents($itemCacheFilePath, $text);
}

$feed = new SimpleXMLElementEx('<rss version="2.0"></rss>');
$feed->addChild('channel');
$feed->channel->addChild('title', 'sitefeed for ' . $url);
$feed->channel->addChild('link', $url);
$imageItem = $feed->channel->addChild('image');
$imageItem->addChild('url', base_url('img/font-awesome-rss-black.png'));

//RSS items
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

header('Content-Type: application/rss+xml');
echo $feed->asXML();
