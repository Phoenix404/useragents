<?php


require_once __DIR__."/../vendor/autoload.php";

use Useragent\UserAgent;
echo "<pre>";
$ua 	= new UserAgent();

$result = $ua->findFirstInFiles("useragent", "chrome");
print_r($result);

