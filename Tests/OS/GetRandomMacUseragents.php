<?php 

require_once __DIR__."/../../vendor/autoload.php";

use Useragent\UserAgent;
$ua 	= new UserAgent();
echo "Random Mac UserAgents : ".$ua->getRandomUserAgent("os mac");
