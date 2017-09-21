<?php
header("Access-Control-Allow-Origin: *");
include("../config.php");
include("../shopify_api.php");

$shop=$_SESSION['shop'];
if(isset($_REQUEST['shop']) && $_REQUEST['shop'] != "") {
    $shop = $_REQUEST["shop"];
    $shop = trim($shop);
}

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
                        <div class="alert alert-success" style="display: none;"><b>Success!</b> Your offer has saved successfully.</div>
                        <div class="alert alert-error" style="display: none;"></div>
                        <div id="tab_1">
                            <div class="section-row" style="width:420px">
                                <div class="section-cell"  style="box-shadow: none;">
                                      <div class="cell-container" style="border-bottom: 1px solid #ebeef0;">
                                          <div class="cell-column">
                                              <a style="display:none;" class="btn primary" href="add_banner1.php?shop=<?= $shop; ?>">Add for test</a>
                                              <a class="btn primary" href="banner.php?shop=<?= $shop; ?>"><< Back to list</a><br /><br />
                                              <label style="font-size:17px;">Add New Banner</label>
                                          </div>
                                      </div>

                                      <div class="cell-container">
                                          <div class="cell-column text_right row_head">
                                              <label>Banner Title * :</label>
                                          </div>
                                          <div class="cell-column row_field">
                                              <input type="text" class="demo input_field" id="bTitle" placeholder="Banner Title" value="" /> 
                                          </div>
                                      </div>

                                      <div class="cell-container">
                                          <div class="cell-column text_right row_head">
                                              <label>Select product(s) to upsell *:</label>
                                          </div>
                                          <div class="cell-column row_field">
                                              <input type="text" class="demo input_field" id="b_product_list" placeholder="Select Products" value="" /> 
                                              <input type="hidden" class="input_field" id="hd_b_product_list" value="" /> 
                                              <a href="#upsell_pro_view" id="btnSelect_upsell_pro" style="display:none">Select Products</a>
                                          </div>
                                      </div>

                                      <div class="cell-container">
                                          <div class="cell-column text_right row_head">
                                              <label>Select Banner Trigger *:</label>
                                          </div>
                                          <div class="cell-column row_field">
                                              <input type="text" class="demo input_field" id="b_target_products" placeholder="Select Products" value="" /> 
                                              <input type="hidden" class="input_field" id="hd_b_target_products" value="" /> 
                                              <a href="#target_pro_view" id="btnSelect_target_pro" style="display:none">Select Products</a>
                                          </div>
                                      </div>

                                      <div class="cell-container">
                                          <div class="cell-column text_right row_head">
                                              <label></label>
                                          </div>
                                          <div class="cell-column row_field">
                                              <br />
                                              <input class="btn primary btnsave" value="Save" type="button" id="add_banner" />
                                          </div>
                                      </div>
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>
<div id="upsell_pro_view" style="display:none">
    <img src="images/no_image.png" />
</div>
<div id="target_pro_view" style="display:none">    
    <img src="images/no_image.png" />
</div>

<div id="loader" style="display:none"></div> 
<link rel="stylesheet" href="assets/css/jquery.minicolors.css">
<style>
#add_banner{ 
    line-height: 18px;
    height: 29px;
    padding: 0px 10px;
}
.alert-error {
    background: #FFCDC9;
    color: red;
}
</style>
<script src="assets/js/jquery.minicolors.min.js"></script>
<script src="assets/js/jquery.fancybox.js"></script>
<script src="assets/js/jquery.fancybox.pack.js"></script>
<script>
    $(document).ready(function () {
        ShopifyApp.Bar.loadingOff();
        var shop = "<?php echo $shop; ?>";

        $(document).on('click', '#add_banner', function () {            
            $(".alert-error").hide();
            $(".alert-error").html("");            
            var bTitle_val = $.trim($("#bTitle").val());
            var b_product_list_val = $.trim($("#b_product_list").val());
            var b_target_products_val = $.trim($("#b_target_products").val());

            var has_erro = false;
            if(bTitle_val == "" || b_product_list_val == "" || b_target_products_val == ""){
                var has_erro = true;
            }

            if(has_erro){              
                $(".alert-error").html("Please enter all field value.");
                $(".alert-error").show();
                return false;
            }
            $("#loader").show();

            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {
                    type: "b_add", 
                    shop: "<?= $shop ?>", 
                    bTitle: bTitle_val,
                    b_pro_list: b_product_list_val,
                    b_target_pro: b_target_products_val
                },
                success: function (data) {
                    $("#loader").hide();
                    if(data == "success"){
                        $(".input_field").val("");                      
                        $(".alert-success").show();
                        $('html,body').animate({ scrollTop: $(".tab-content").offset().top}, 1000);
                        setTimeout(function () {
                            $(".alert-success").hide();
                        }, 3000);
                    } else {
                        $(".alert-error").html(data);  
                        $(".alert-error").show();
                    }
                }
            });
        });
    });

    $("#btnSelect_upsell_pro").fancybox();
    $("#btnSelect_target_pro").fancybox();
</script>
</body>
</html>               