<?php 


require_once __DIR__."/../vendor/autoload.php";

use Useragent\UserAgent;
$ua 	= new UserAgent();

$browsers = ["mozilla", "windows", "firefox", "linux", "Kindle", "LG", "ie", "mac", "safari", "Playstation", "Wii", "PSP", "SuperBot", 
				"Wget", "ELinks", "NetBSD", "Lynx", "IEMobile", "Baiduspider", "iPhone", "Puffin", "opera", 
				"Yahoo","Galeon","mac","Symbian","Apple","Android","maxthon","Googlebot-Mobile"];

echo "<center><table style='margin-top:50px;' border='2'><tr><th>Name</th><th>Useragent</th></tr>";
foreach ($browsers as $key => $value) {
	echo "<tr style='padding:10px;'>";
	echo  "<td>".strtoupper($value)."</td>";
	echo  "<td>".$ua->findUserAgents($value, "useragent")."</td>";
	echo "</tr>";
}
echo "</table></center>";
