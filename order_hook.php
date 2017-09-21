<?php

require_once("config.php");

/*require 'shopifyclient.php';
require 'vendor/autoload.php';
use sandeepshetty\shopify_api;

$req_header = apache_request_headers();
$req_header = json_encode($req_header);
$req_arr = json_decode($req_header, true);
$shop_domain = $req_arr['X-Shopify-Shop-Domain'];

$select_sql = "SELECT `id`,`token` FROM `app` WHERE `shop` = '".$shop_domain."' ORDER BY `id` DESC LIMIT 1";
$res = mysql_query($select_sql);                                                                             
if (mysql_num_rows($res) > 0) {                                                                              
    $res_arr = mysql_fetch_assoc($res);                                                                     
    $token = $res_arr['token'];                                                                             
}

$sc = new ShopifyClient($shop_domain, $token, SHOPIFY_API_KEY, SHOPIFY_SECRET);*/

$to      = 'harakhani@gmail.com';
$subject = 'Order Create Hook : Webbook Called';
$message = 'Order Create Hook : Webbook Called';
$headers = 'From: spipl001@gmail.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();  

$current_date = date("Y-m-d H:i:s");
$entityBody = file_get_contents('php://input');
$arr = json_decode($entityBody);

$cart_token = $arr->cart_token;
$select_sql = "SELECT * FROM banner_transacrion WHERE cart_id='".$cart_token."'";
$res = mysql_query($select_sql);
if(mysql_num_rows($res) > 0) { 
    foreach ($arr->line_items as $product) {
        $cu_upsell_type = "";
        $pro_id = $product->product_id;

        $checl_pro_sql = "SELECT tid FROM banner_transacrion WHERE cart_id='".$cart_token."' and pid='".$pro_id."||||".$product->variant_id."'";
        $res_pro = mysql_query($checl_pro_sql);
        if(mysql_num_rows($res_pro) > 0) { 
            $update_sql = "update banner_transacrion set is_purchased='1', order_hook_resp='".mysql_real_escape_string($entityBody)."', updated_date='".$current_date."' WHERE cart_id='".$cart_token."' and pid='".$pro_id."||||".$product->variant_id."'";
            $res = mysql_query($update_sql);

            /*$product_meta_list = $sc->call('GET', "/admin/products/".$pro_id."/metafields.json"); 
            #$pro_meta_arr = loopandfind($product_meta_list, 'key', 'p_upsell_target');

            //$pro_meta_arr = loopandfind($product_meta_list, 'key', 'cu_upsell_type');    
            //if ($pro_meta_arr[0]['value'] != "") {  
                //$cu_upsell_type = $pro_meta_arr[0]['value']; 
            //}

            $upsell_meta = loopAndFind($product_meta_list, 'namespace', 'cu_upsell_type');
            foreach ($upsell_meta as $k => $v) {
                if ($v['key'] == 'cu_upsell_type') {
                    $cu_upsell_type = $v['value'];
                }
            }*/
        }
    }
}
/*mail($to, $subject, $entityBody, $headers);*/
exit;
?>