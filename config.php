<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING);

$server = "localhost";
$db_user = "shopiapp_upsell";
$db_pwd = "upsell121*1";
$db_name = "shopiapp_cartupsell";

$db_obj = mysql_connect($server,$db_user, $db_pwd);
mysql_select_db($db_name);
mysql_set_charset('utf-8');

$sql_details = array(
    'user' => $db_user,
    'pass' => $db_pwd,
    'db' => $db_name,
    'host' => $server
);

define('DOMAIN_NAME', 'shopiapps.io/cartupsell');
define('SITE_URL', 'https://shopiapps.io/cartupsell');

session_start();
  
if (!$db_obj) {
	echo "Failed to connect to MySQL: " . mysql_error();
}

define('SHOPIFY_API_KEY', '9927bc08b380ddc359ba23bdc6046834');
define('SHOPIFY_SECRET', '49242dcaeeb49ece25bb8e1d597b1ac8');
define('SHOPIFY_SCOPE', 'write_products,write_content,write_themes,write_orders');
define('PLAN', 'paid'); /* free/paid */
define('PLAN_PRICE', 19); /* 2.99 */
define('PLAN_TRIAL', 7); /* 7 */
define('PLAN_MODE', true); /* true/false */

define('APP_NAME',"Cart Upsell");
function loopAndFind($array, $index, $search){
	$returnArray = array();
	foreach($array as $k=>$v){
		if($v[$index] == $search){   
			$returnArray[] = $v;
		} 
	}
	return $returnArray;
}
?>