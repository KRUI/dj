<?php
//Dynamically returns RSS feed for podcasts
require_once ('../../lib/db.php'); //Connect to database
	$db = new Database();
	$db->connect();
$url = mysql_fetch_assoc(mysql_query("SELECT root_url FROM global_settings LIMIT 1"));
$url['rss'] = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$show_id = $_GET['channel'];
//Fetch podcast channel
$channelParam = "SELECT title,url,description FROM podcast_shows WHERE show_id ='".mysql_real_escape_string($show_id)."'";
$channelQuery = mysql_query($channelParam);
$channel = mysql_fetch_assoc($channelQuery);
//Fetch podcast items
$itemParam = "SELECT item_title,item_link,item_desc,pubDate FROM podcast_items WHERE show_id ='".mysql_real_escape_string($show_id)."' ORDER BY pubDate DESC";
$itemQuery = mysql_query($itemParam);
$result = 0;
while ($itemResult = mysql_fetch_array($itemQuery)) {
	$item['title'][$result] = $itemResult['item_title'];
	$item['link'][$result] = $itemResult['item_link'];
	$item['desc'][$result] = $itemResult['item_desc'];
	$item['pubDate'][$result] = $itemResult['pubDate'];
	$result++;
}
///Return podcast feed (Atom)
echo ('<?xml version="1.0" encoding="ISO-8859-1"?>
<?xml-stylesheet type="text/css" href="http://www.rsspect.com/css/generic.css"?>
<rss xmlns:atom="http://www.w3.org/2005/Atom" xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#" version="2.0">
<channel>');
//Podcast Channel Information
echo stripslashes ('<title>'.$channel['title'].'</title>');
echo stripslashes ('<link>'.$channel['url'].'</link>');
echo stripslashes ('<description>'.$channel['description'].'</description>');
echo ('<language>en-us</language>');
echo stripslashes ('<atom:link href="'.$url['rss'].'" rel="self" type="application/rss+xml" />');
//Items
for ($i = 0; $i < $result; $i++) {
echo('<item>');
	echo stripslashes('<title>'.$item['title'][$i].'</title>');
	echo stripslashes('<link>'.$url['root_url'].$item['link'][$i].'</link>');
	echo stripslashes('<description>'.$item['desc'][$i].'</description>');
	echo stripslashes('<pubDate>'.date('r', $item['pubDate'][$i]).'</pubDate>');
	echo stripslashes('<guid isPermaLink="true">'.$url['root_url'].'assets/users/'.$item['link'][$i].'</guid>');
echo('</item>');
}
echo ('</channel></rss>');
?>