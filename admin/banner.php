<?php
header("Access-Control-Allow-Origin: *");
include("../config.php");
include("../shopify_api.php");

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
<script src="//cdn.datatables.net/1.10.3/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="//cdn.datatables.net/1.10.3/css/jquery.dataTables.css" />
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
                        <div class="banner_list section-row">
                            <div class="section-cell"  style="box-shadow: none;">
                                <div class="cell-container" style="border-bottom: 1px solid #ebeef0;">
                                    <div class="cell-column">
                                        <a class="btn primary" href="add_banner.php?shop=<?= $shop; ?>">Add Banner</a><br /><br />
                                        <label style="font-size:17px;">Banner List</label>                                        
                                    </div>
                                </div>

                                <div class="cell-container">
                                    <table id="cust_data" class="display" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th width="1%" style="text-align:left;display:none;">Id</th>
                                                <th width="49%" style="text-align:left">Title</th>
                                                <th width="15%" style="text-align:left">Preview</th>
                                                <th width="15%" style="text-align:left">Status</th>
                                                <th width="10%" style="text-align:left">Edit</th>
                                                <th width="10%" style="text-align:left">Delete</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="preview" style="display:none">
                <div id="preview_popup" style="height:250px;">
                <img class="loader_popup" src="assets/css/ajax-loader.gif">
                </div>
                <a class="fancy-close" style="text-decoration:none;cursor: pointer;;">Close</a>
            </div>
        </div>
    </div>
</div>
<div id="loader" style="display:none"></div> 
<link rel="stylesheet" href="assets/css/jquery.minicolors.css">
<script src="assets/js/jquery.fancybox.js"></script>
<script src="assets/js/jquery.fancybox.pack.js"></script>
<style>
.banner_list{
    width:100%;border-top: 1px solid #ebeef0;clear: both;
}
.alert-error {
    background: #FFCDC9;
    color: red;
}
#cust_data_wrapper{width:100%;}
#preview_popup h1{font-size: 25px;color:#479ccf;}
#preview .loader_popup{ padding:125px 275px;}
ul.pr_images{padding:0;margin:0;list-style-type:none;display:inline-block;}
ul.pr_images li.up_pr_img{float:left;width:140px;height: auto;padding: 14px 7px;}
ul.pr_images li.up_pr_img img{max-height: 100%; max-width:135px;}
#cust_data tr td:nth-child(1){padding-left:19px;}
#cust_data tr td:nth-child(2){padding-left:30px;}
#cust_data tr td:nth-child(3){padding-left:20px;}
#cust_data tr td:nth-child(4){padding-left:21px;}
#cust_data tr td:nth-child(5){padding-left:28px;}
</style>
<script src="assets/js/jquery.minicolors.min.js"></script>
<script>
    $(document).ready(function () {
        ShopifyApp.Bar.loadingOff();
        var shop = "<?php echo $shop; ?>";

         oTable = load_data(shop);
         $("#loader").hide();
    });

    $(document).on('click', '.preview_banner', function () {
        var curr_obj = $(this);
        var id_str = $.trim($(curr_obj).attr("id"));
        var hr_str = $.trim($(curr_obj).attr("href"));

        $(".loader_popup").show();
        $.ajax({
            type: "GET",
            url: "ajax.php",
            data: {
                type: "get_preview", 
                shop: "<?= $shop ?>", 
                id: id_str
            },
            success: function (data) {
                $("#preview_popup").html(data);
                $(".loader_popup").hide();
            }
        });
        
        $.fancybox({
            'autoSize':false,
            'closeBtn': false,
            'width':625,
            'height':290,
            'href': hr_str
        });
        
    });

    $(document).on('click', '.fancy-close', function () { 
        $("#preview_popup").html("<img class='loader_popup' src='assets/css/ajax-loader.gif'>");
        $.fancybox.close();
    });

    /*$(document).on('click', '.b_status_onoff', function () {     
        $(".alert-error").hide();          
        $(".alert-error").html("");       
        var curr_obj = $(this);
        var id_str = $.trim($(curr_obj).attr("id"));
        var val_str = $.trim($(curr_obj).attr("b_status_change"));

        var change_linktext_sucess = 'Make it On';
        var change_val_after_sucess = 1;
        if(val_str == 1){
            change_val_after_sucess = 0;
            change_linktext_sucess = 'Make it Off';
        }

        $("#loader").show();
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {
                type: "update_status", 
                shop: "<?= $shop ?>", 
                id: id_str, 
                val: val_str
            },
            success: function (data) {
                $("#loader").hide();
                if(data == "success"){          
                    $(curr_obj).text(change_linktext_sucess);
                    $(curr_obj).attr("b_status_change", change_val_after_sucess);
                    
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
        return false;
    });*/

    $(document).on('click', '.b_delete', function () {     
        $(".alert-error").hide();          
        $(".alert-error").html("");       
        var curr_obj = $(this);
        var id_str = $.trim($(curr_obj).attr("id"));
        var b_pro_list_str = $.trim($(curr_obj).attr("b_pro_list"));

        $("#loader").show();
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {
                type: "b_delete", 
                shop: "<?= $shop ?>", 
                id: id_str,
                b_pro_list_old: b_pro_list_str
            },
            success: function (data) {
                $("#loader").hide();
                if(data == "success"){          
                    $(curr_obj).parents("tr").hide();
                } else {
                    $(".alert-error").html(data); 
                    $(".alert-error").show();
                }
            }
        }); 
        return false;
    });

    function load_data(shop_str) {
        $("#loader").show();
        var table = $('#cust_data').DataTable({
            responsive: true,
            "processing": true,
            "serverSide": true,
            "order": [[1, "desc"]],
            "columns": [                
                {"data": "bid", "visible": false},
                {"data": "b_title_internal"},
                {"data": "bid", "bSortable": false, "targets": 'no-sort', "render": function (data, type, row) {
                        var bid_str_temp = base64_encode(row['bid']);
                        var image_str = '<a href="#preview" id="'+bid_str_temp+'" class="preview_banner"><img src="images/preview.png" alt="" style="max-width: 20px;" /></a>';
                        var b_product_list = row['b_product_list'];
                        var b_target_list = row['b_target_list'];
                        return image_str;
                    }
                },
                {"data": "b_status", "render": function (data, type, row) {
                        var b_status_str = 'Inactive';
                        if (data == '1') {
                            b_status_str = 'Active';
                        }
                        return b_status_str;
                    }
                },
                {"data": "bid", "bSortable": false, "targets": 'no-sort', "render": function (data, type, row) {
                        var bid_str_temp = base64_encode(data);                        
                        var b_action_str = '<a href="edit_banner.php?id='+bid_str_temp+'&shop='+shop_str+'" class="b_edit"><img src="images/edit.png" alt="" style="max-width: 20px;" /></a>';
                        return b_action_str;
                    }
                },
                {"data": "bid", "bSortable": false, "targets": 'no-sort', "render": function (data, type, row) {
                        var bid_str_temp = base64_encode(data);
                        var b_product_list = row['b_product_list'];
                        /*var b_onoff_str = '<a href="javascript:void(0);" class="b_status_onoff" id="'+bid_str_temp+'" b_status_change="1">Make it On</a>';
                        if(row['b_status'] == "1"){
                            b_onoff_str = '<a href="javascript:void(0);" class="b_status_onoff" id="'+bid_str_temp+'" b_status_change="0">Make it Off</a>';
                        }
                        return b_onoff_str;*/
                        var b_action_str = '<a href="javascript:void(0)" id="'+bid_str_temp+'" b_pro_list="'+b_product_list+'" class="b_delete"><img src="images/delete.png" alt="" style="max-width: 20px;" /></a>';
                        return b_action_str;
                    }
                }
            ],
            "ajax": {
                "url": "ajax.php?shop="+shop_str+"&type=get_banner_list",
                "type": "POST"
            }
        });
        return table;
    }

    function base64_encode(data) {
          var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
          var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
            ac = 0,
            enc = '',
            tmp_arr = [];

          if (!data) {
            return data;
          }

          data = unescape(encodeURIComponent(data));

          do {
            // pack three octets into four hexets
            o1 = data.charCodeAt(i++);
            o2 = data.charCodeAt(i++);
            o3 = data.charCodeAt(i++);

            bits = o1 << 16 | o2 << 8 | o3;

            h1 = bits >> 18 & 0x3f;
            h2 = bits >> 12 & 0x3f;
            h3 = bits >> 6 & 0x3f;
            h4 = bits & 0x3f;

            // use hexets to index into b64, and append result to encoded string
            tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
          } while (i < data.length);

          enc = tmp_arr.join('');

          var r = data.length % 3;

          return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
    }
</script>
</body>
</html>               