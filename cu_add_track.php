<?php
header('Access-Control-Allow-Origin: *');
include("config.php");

$current_date = date("Y-m-d H:i:s");

$shop = "";
if(isset($_REQUEST['shop']) && $_REQUEST['shop'] != "") {
    $shop = $_REQUEST["shop"];
    $shop = trim($shop);
}

$type = "";
if(isset($_REQUEST['type']) && $_REQUEST['type'] != "") {
    $type = $_REQUEST["type"];
    $type = trim($type);
}

if($type == 'cu_ac_p' && $shop != "") {
    $pid = $_REQUEST["pid"];
    $cart_tocked = $_REQUEST["cu_t"];
    $bid = $_REQUEST["bn_id"];
	$cu_id_str = $_REQUEST["cu_id_str"];
	

    $total_row = 0;
    $checl_pro_sql = "SELECT * FROM banner_transacrion WHERE pid='".$pid."' and cart_id='".$cart_tocked."'";
    $res_pro = mysql_query($checl_pro_sql);
    $total_row = mysql_num_rows($res_pro);
    #echo $total_row.">>";
    if($total_row == 0) { 
        $sql_insert = "Insert into banner_transacrion(`shop`,bid,pid,add_to_cart,cart_id,created_date,updated_date) ".
                "VALUES('".mysql_real_escape_string($shop)."','".$bid."','".$pid."||||".$cu_id_str."','1','".$cart_tocked."','".$current_date."','".$current_date."')";
        #echo $sql_insert;
        $result_sql = mysql_query($sql_insert);
        if (!$result_sql) {
            echo 'Invalid query: '.mysql_error();
        } else {
            echo "success";
        }
    } else {
        echo "error";
    }
} else {
    echo "error";
}
?>