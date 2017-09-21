<?php
header("Access-Control-Allow-Origin: *");
include("../config.php");
include("../shopify_api.php");
$string = '<script src="//'.DOMAIN_NAME.'/thankyou.php?shop={{shop.domain}}"  oid="{{ checkout.order_id }}" type="text/javascript" id="ty_related_script"></script>';
?>    
<html>
<head>
<?php include 'header.php'; ?>
<script type="text/javascript">
            ShopifyApp.init({
                apiKey: '<?= SHOPIFY_API_KEY ?>',
                shopOrigin: 'https://<?= $shop ?>'
            });
        </script>
<title><?php echo APP_NAME; ?></title>
</head>
<body>
<div class="section">
<div class="section-content">   
<?php if ($install_status == '0') { ?>
    <div class="section-row install_status">
        <div class="section-cell" style="box-shadow: none;text-align: center;">
            <label>Please wait... the app is completing its setup.</label>
        </div>
    </div>
<?php } ?>
<div class="section-row">

    <div class="section-listing">
        <div class="section-options">
            <?php include 'menu.php'; ?>
            <div class="section-content tab-content" >
            <label style="font-size:17px;">Instruction</label> 
            <div id="tab_1">
                <div class="section-row">   
                    <div class="section-cell"  style="box-shadow: none;">
                        <div class="cell-container" style="border-bottom: 1px solid #ebeef0;">
                            <div class="cell-column">
                                <label style="font-size:17px;">Add this code to your cart page :</label> 
                            </div>
                        </div>
                        <div class="cell-column">
                            <input class="code" type="text" readonly="readonly" value="{% include 'cart_upsell_ad' %} " >   
                        </div>
                        <div class="cell-column" style="display:none">
                            <img src="images/image1.png" style="width:100%" style="display:none" />   
                        </div>
                           
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<style>
    .code{text-align: center;background-color: #ccc;font-size: 15px;height: 45px;}
</style>
<script>
    $(document).ready(function(){
        ShopifyApp.Bar.loadingOff();
    });
</script>
</body>
</html>               