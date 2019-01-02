<?php

/**
 * Define the folder(note the ending slash) containing the mediawiki's files.
 *  
 * YOU SHOULD PLACE THEM OUTSIDE THE DOCUMENT ROOT OF YOUR WEB SERVER!!!
 */

define("MEDIAWIKI_PATH", "mediawiki-1.15.5/");

/* Include our helper class */
require_once "mediawiki-zhconverter.inc.php";

/* Convert it, valid variants such as zh, zh-cn, zh-tw, zh-sg & zh-hk */
echo '雪糕:';echo MediaWikiZhConverter::convert("雪糕", "zh-tw") , ",";
echo '記憶體:';echo MediaWikiZhConverter::convert("記憶體", "zh-cn"), ",";
echo '大卫·贝克汉姆:';echo MediaWikiZhConverter::convert("大卫·贝克汉姆", "zh-hk");

?>