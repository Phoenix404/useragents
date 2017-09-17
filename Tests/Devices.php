<?php 


require_once __DIR__."/../vendor/autoload.php";

use Useragent\UserAgent;
$ua 	= new UserAgent();

$browsers = ["acer", "amazonKindle", "GoogleNexus", "HP", "HTC", "LG", "Motorola", "Nokia", "Samsung", "Sony", "Tablets"];

echo "<center><table style='margin-top:50px;' border='2'><tr><th>Name</th><th>Useragent</th></tr>";
foreach ($browsers as $key => $value) {
	echo "<tr style='padding:10px;'>";
	echo  "<td>".strtoupper($value)."</td>";
	echo  "<td>".$ua->getRandomUserAgent($value)."</td>";
	echo "</tr>";
}
echo "</table></center>";
