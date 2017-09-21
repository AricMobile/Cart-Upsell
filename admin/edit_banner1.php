<?php
header("Access-Control-Allow-Origin: *");
include("../config.php");
include("../shopify_api.php");
include("shopify_function.php");

#echo "<pre>"; print_r(getAllProuctData($shop, $token));
#echo "<pre>"; print_r(getCollection($shop, $token));
$product_list = array();
/*$All_product_array = getAllProuctData($shop, $token);
if(count($All_product_array["products"]) > 0 && $All_product_array["status"] == "1"){
    $product_list = $All_product_array["products"];
}*/

$collection_list = array();
$All_coll_array = getCollection($shop, $token);
if(count($All_coll_array["result"]) > 0 && $All_coll_array["status"] == "1"){
    $collection_list = $All_coll_array["result"];
    $collection_list = array_values($collection_list);
}

if(count($product_list) > 0 || count($collection_list) > 0){
  #echo "<pre>"; print_r($product_list);
  #echo "<pre>"; print_r($collection_list);
}

$b_id_val = "";
if(isset($_REQUEST['id']) && $_REQUEST['id'] != "") {
    $b_id_val = $_REQUEST["id"];
    $b_id_val = trim($b_id_val);
    $b_id_val = base64_decode($b_id_val);
}

$sel_sql = "select * from banner_mst where shop = '".$shop."' and bid= '".$b_id_val."'";
$result=mysql_query($sel_sql);
if(mysql_num_rows($result) == 0){
    header('Location: banner.php?shop='.$shop);
}

$banner_data = mysql_fetch_assoc($result);
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
                                              <a style="display:none;" class="btn primary" href="edit_banner1.php?shop=<?=$shop?>">Edit for test</a>                                              
                                              <a class="btn primary" href="banner.php?shop=<?= $shop; ?>"><< Back to list</a><br /><br />
                                              <label style="font-size:17px;">Edit Banner</label>
                                          </div>
                                      </div>

                                      <div class="cell-container">
                                          <div class="cell-column text_right">
                                              <label>Banner Title * :</label>
                                          </div>
                                          <div class="cell-column ">
                                              <input type="text" class="demo input_field" id="bTitle" placeholder="Banner Title" value="<?php echo $banner_data['b_title']; ?>" /> 
                                          </div>
                                      </div>

                                      <div class="cell-container">
                                          <div class="cell-column text_right">
                                              <label>Select product(s) to upsell *:</label>
                                          </div>
                                          <div class="cell-column ">
                                              <input type="text" class="demo input_field" id="b_product_list" placeholder="Select Products" value="<?php echo $banner_data['b_product_list']; ?>" readonly="true" /> 
                                              <input type="hidden" class="input_field" id="hd_b_product_list" value="<?php echo $banner_data['b_product_list']; ?>" /> 
                                              <a href="#upsell_pro_view" id="btnSelect_upsell_pro">Select Products</a>
                                          </div>
                                      </div>

                                      <div class="cell-container">
                                          <div class="cell-column text_right">
                                              <label>Select Banner Trigger *:</label>
                                          </div>
                                          <div class="cell-column ">
                                              <input type="text" class="demo input_field" id="b_target_products" placeholder="Select Products" value="<?php echo $banner_data['b_target_list']; ?>" readonly="true" /> 
                                              <input type="hidden" class="input_field" id="hd_b_target_products" value="<?php echo $banner_data['b_target_list']; ?>" /> 
                                              <a href="#target_pro_view" id="btnSelect_target_pro">Select Products</a>
                                          </div>
                                      </div>

                                      <div class="cell-container">
                                          <div class="cell-column text_right">
                                              <label></label>
                                          </div>
                                          <div class="cell-column ">
                                              <br />
                                              <input class="btn primary btnsave" value="Save" type="button" id="edit_banner" /> 
                                              <input type="hidden" id="hd_id" value="<?php echo base64_encode($banner_data['bid']); ?>" />
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
  <div class="product_selection_box">
      <div class="product_selection_option">
        <h2>Search Products:</h2>
      <div class="select_opt_row">
          <input type="radio" class="rd_all_product rd_ps_opt" target_div="all_pro_list_box" name="rd_ps_opt" checked="true" value="all_pro"> 
          <div class="op_head">All Products</div>
          <input type="text" class="txt_search_products" target_div="all_pro_list_box" placeholder="Search by product name" value="" />
      </div>
      <div class="select_opt_row">    
          <input type="radio" class="rd_all_collection rd_ps_opt" target_div="all_coll_list_box" name="rd_ps_opt" value="all_coll">
          <div class="op_head">From Collection</div>          
      </div>     
      <br />    
            <input type="button" class="btn primary btnSearchProducts" value="Search Products" />
            <span class="error_search_opt"></span>
        </div>
        <div class="product_selection_data">
            <div class="pro_list_box">
                <div class="all_pro_list_box product_data_list"> </div>                                       
              <div class="all_coll_list_box product_data_list" style="display:none;">
          <?php
          if(count($collection_list) > 0){
            for($i=0;$i<count($collection_list);$i++){
          ?>
            <div class="p_box" id="<?=$collection_list[$i]["id"]?>">
              <span class="p_name"><?=$collection_list[$i]["title"]?></span>  
              <input type="button" class="btn primary p_add" value="Add" p_id="<?=$collection_list[$i]["id"]?>" max-limit="1" />
            </div>
          <?php
            }
          }
          ?>
              </div>
            </div>
            <div class="pro_target_list"></div>
        </div>    
        <div class="footer_data">
          <div class="pagignation_data"> </div>   
          <div class="product_selection_buttonbox">
              <input type="button" class="btn primary btnContProducts" value="Continute with Selected Products"  data_for="b_product_list" max-limit="1" />
              <span class="error_cont_btn"></span>
          </div>
        </div>
    </div>    
</div>
<div id="target_pro_view" style="display:none">    
    <div class="product_selection_box">
      <div class="product_selection_option">
        <h2>Search Products:</h2>
      <div class="select_opt_row">
          <input type="radio" class="rd_all_product rd_ps_opt" target_div="all_pro_list_box" name="rd_ps_opt" checked="true" value="all_pro"> 
          <div class="op_head">All Products</div>
          <input type="text" class="txt_search_products" target_div="all_pro_list_box" placeholder="Search by product name" value="" />
      </div>
      <div class="select_opt_row">    
          <input type="radio" class="rd_all_collection rd_ps_opt" target_div="all_pro_list_box" name="rd_ps_opt" value="all_coll">
          <div class="op_head">From Collection</div>
          <select class="store_col_list">
            <option value="-1">Select</option>
          <?php
                    if(count($collection_list) > 0){
                      for($i=0;$i<count($collection_list);$i++){
                ?>
                        <option value="<?=$collection_list[$i]["id"]?>"><?=$collection_list[$i]["title"]?></option>
                <?php
                        }
                  }
              ?>
          </select>
      </div>          
            <input type="button" class="btn primary btnSearchProducts" value="Search Products" />
            <span class="error_search_opt"></span>
        </div>
        <div class="product_selection_data">
          <div class="pro_list_box">
              <div class="all_pro_list_box product_data_list"> </div>                           
          </div>
          <div class="pro_target_list"></div>
        </div>    
        <div class="footer_data">
          <div class="pagignation_data"> </div>   
          <div class="product_selection_buttonbox">
              <input type="button" class="btn primary btnContProducts" value="Continute with Selected Products"  data_for="b_target_products" max-limit="4" />
              <span class="error_cont_btn"></span>
          </div>
        </div>
    </div>
</div>

<div id="loader" style="display:none"></div> 
<link rel="stylesheet" href="assets/css/jquery.minicolors.css">
<style>
#edit_banner{ 
    line-height: 18px;
    height: 29px;
    padding: 0px 10px;
}
.alert-error {
    background: #FFCDC9;
    color: red;
}
.select_opt_row{ display: block; }
.rd_all_product{
    display: inline-block;
    float: none;
    vertical-align: top;
}
.product_selection_box{ width: 608px; }
.product_selection_option{ padding-bottom: 10px; border-bottom: 1px solid #ddd; }
.product_selection_data{ margin: 10px 0; }
.pro_list_box, .pro_target_list{ display: inline-block; vertical-align: top; padding: 5px 10px 5px 5px; }
.product_data_list .p_box, .pro_target_list .p_box { padding: 5px 0; display: block; block; clear: both; }
.pro_list_box { border: 1px solid #ddd; width: 285px; height: 300px; overflow-y: scroll; overflow-x: hidden; }
.pro_target_list{ border: 1px solid #ddd; width: 285px; height: 300px; }
.product_selection_buttonbox{ text-align: left; }
span.p_name { float: left; }
.p_box .p_add, .p_box .p_remove { float: right; }
.p_box .p_add.marked { background-color: green; color: #fff; border: 1px solid green; }
#b_target_products, #b_product_list{ background: #eee; }
.footer_data .pagignation_data, .footer_data .product_selection_buttonbox{ display: inline-block; vertical-align: top; padding: 5px 10px 5px 5px; width: 47%; }
.pagignation_data .page_no { margin-right: 5px; text-decoration: underline; cursor: pointer; }
.pagignation_data .page_no.active_page { text-decoration: none; cursor: text; }
.select_opt_row .rd_ps_opt, .select_opt_row .op_head, .txt_search_products, .store_col_list{ display: inline-block; vertical-align: top; }
.txt_search_products, .store_col_list{ width: 250px !important }
.op_head{ width: 95px; margin-top: 7px; }
.select_opt_row .rd_ps_opt{ margin-top: 7px !important; }
.product_selection_option h2{ font-size: 19px; }
span.error_search_opt, span.error_cont_btn  { color: red; margin-left: 5px; }
</style>
<script src="assets/js/jquery.minicolors.min.js"></script>
<script src="assets/js/jquery.fancybox.js"></script>
<script src="assets/js/jquery.fancybox.pack.js"></script>
<script>
    $(document).ready(function () {
        ShopifyApp.Bar.loadingOff();
        var shop = "<?php echo $shop; ?>";

        $(document).on('click', '#edit_banner', function () {            
            $(".alert-error").hide();          
            $(".alert-error").html("");
            var bid_val = $.trim($("#hd_id").val());
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
                    type: "b_update", 
                    shop: "<?= $shop ?>", 
                    id: bid_val,
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
                            window.location.href = 'banner.php?shop=<?= $shop ?>';
                        }, 3000);
                    } else {
                        $(".alert-error").html(data);  
                        $(".alert-error").show();
                    }
                }
            });
        });
    });

    $(document).on('click', '.rd_ps_opt', function () {
      var curr_btn_obj = $(this);     
      var main_div_obj = $(curr_btn_obj).parents(".product_selection_box");
      //alert($(main_div_obj).find('.rd_ps_opt:checked').val());
      
      var target_div = $(curr_btn_obj).attr("target_div");
      if(target_div == "all_coll_list_box"){
        $(main_div_obj).find(".btnSearchProducts").hide();
      } else {
        $(main_div_obj).find(".btnSearchProducts").show();
      }
      $(main_div_obj).find(".all_pro_list_box").html("");        
        $(main_div_obj).find(".product_data_list").hide();        
        $(main_div_obj).find(".p_add").removeClass("marked");
        $("."+target_div).show();
    });

    $(document).on('click', '.p_add', function () {     
        var curr_btn_obj = $(this);
        var main_div_obj = $(curr_btn_obj).parents(".product_selection_box");
        var limit_for_add = parseInt($(main_div_obj).find(".btnContProducts").attr("max-limit"));
        var current_added_box = $(main_div_obj).find(".pro_target_list .p_box").length;
        if(limit_for_add == 1){
            if(current_added_box == 1){
                alert("Sorry! You can only add 1 item for Upsell.");
                return false;
            }
        } else {            
            if(current_added_box == 4){
                alert("Sorry! You can add maximum 4 items for banner Ad.");
                return false;
            }
        }

        /*var target_list_arr = Array();
        $(main_div_obj).find(".pro_target_list .p_box .p_remove").each( function(ii){
            var p_id_temp = $(this).attr("p_id");
            target_list_arr.push(p_id_temp);
        });
        console.log(target_list_arr);
        if($.inArray(object_id, target_list_arr) == "-1"){ }
        */
        
      var object_id = $(curr_btn_obj).attr("p_id");
        var copy_html = $(curr_btn_obj).parent(".p_box").html();
        copy_html = copy_html.replace("p_add", "p_remove");
        copy_html = copy_html.replace("Add", "Remove");
        var new_html = "<div class='p_box' id='"+object_id+"'>"+copy_html+"</div>";
        //console.log(new_html);
        $(main_div_obj).find(".pro_target_list").append(new_html);
        $(curr_btn_obj).addClass("marked");
    });

    $(document).on('click', '.p_remove', function () {
        var curr_btn_obj = $(this);   
        var main_div_obj = $(curr_btn_obj).parents(".product_selection_box");     
        var object_id = $(curr_btn_obj).attr("p_id");
        $(main_div_obj).find(".product_data_list").find("#"+object_id).find(".p_add").removeClass("marked");
        $(curr_btn_obj).parent(".p_box").remove();
    });  

    $(document).on('click', '.btnContProducts', function () {        
        var curr_btn_obj = $(this);
        var main_div_obj = $(curr_btn_obj).parents(".product_selection_box");
        var current_added_box = $(main_div_obj).find(".pro_target_list .p_box").length;
        if(current_added_box == 0){
            alert("Please select products");
            return false;
        }

        var data_for = $(curr_btn_obj).attr("data_for");
        var prefix = "";
        if(data_for == "b_product_list"){
            var selected_opt = $(main_div_obj).find('.rd_ps_opt:checked').val();
            if(selected_opt == "all_pro"){
                prefix = "p_";  
            } else {
                prefix = "c_";  
            }
        }

        var final_selected_pro_list = "";
        $(main_div_obj).find(".pro_target_list .p_box .p_remove").each( function(ii){
            var p_id_temp = prefix + $(this).attr("p_id");
            if(final_selected_pro_list == ""){
                final_selected_pro_list = p_id_temp; 
            } else {
                final_selected_pro_list += ","+p_id_temp; 
            }
        });

        //alert(final_selected_pro_list);
        $("#"+data_for).val(final_selected_pro_list);
        $(main_div_obj).find(".pro_target_list").html("");
        $(main_div_obj).find(".p_add").removeClass("marked");
        $.fancybox.close(); 
    });

  $(document).on('click', '.btnSearchProducts', function () {        
        var curr_btn_obj = $(this);
        var main_div_obj = $(curr_btn_obj).parents(".product_selection_box");
        var selected_opt = $(main_div_obj).find('.rd_ps_opt:checked').val();
        var search_text = $(main_div_obj).find(".txt_search_products").val();
        search_text = $.trim(search_text);
        var selected_coll = $(".store_col_list").val();

        if(selected_opt == "all_coll" && selected_coll == "-1"){
          alert("Please select collection");
          return false;
        }

        $("#loader").show();
      $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {
                type: "search_p", 
                shop: "<?= $shop ?>",
                ss: search_text,
                st: selected_opt,
                sc: selected_coll,
                page: 1
            },
            success: function (data) {
                $("#loader").hide();
                if(data == "no_products"){
                $(main_div_obj).find(".all_pro_list_box").html("Sorry! No search found.");
                } else {
                  var data_arr = data.split("|||");
                  var list_str = data_arr[1];
                  $(main_div_obj).find(".pagignation_data").html(data_arr[0]);      
                  $(main_div_obj).find(".all_pro_list_box").html(list_str);                 
                }
            },
            error: function (error) {
              $("#loader").hide();
        console.log('error; ' + eval(error));
        alert("Process fail. Please try again.")
            }
        });
    });

  $(document).on('click', '.page_no', function () {        
        var curr_btn_obj = $(this);
        var main_div_obj = $(curr_btn_obj).parents(".product_selection_box");
        var curr_page_no = $(curr_btn_obj).text();
        var search_text = $(curr_btn_obj).attr("ss");
        search_text = $.trim(search_text);

        $("#loader").show();
      $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {
                type: "search_p", 
                shop: "<?= $shop ?>",
                ss: search_text,
                page: curr_page_no
            },
            success: function (data) {
                $("#loader").hide();
                if(data == "no_products"){
                $(main_div_obj).find(".all_pro_list_box").html("Sorry! No search found.");
                } else {
                  var data_arr = data.split("|||");
                  var list_str = data_arr[1];
                  $(main_div_obj).find(".pagignation_data").html(data_arr[0]);      
                  $(main_div_obj).find(".all_pro_list_box").html(list_str);               
                }
            },
            error: function (error) {
              $("#loader").hide();
        console.log('error; ' + eval(error));
        alert("Process fail. Please try again.")
            }
        });
    }); 

    $("#btnSelect_upsell_pro").fancybox();
    $("#btnSelect_target_pro").fancybox();
</script>
</body>
</html>               