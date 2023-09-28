<html>
<base href="<?php echo base_url() ?>resource/backend_new/"></base>

<head>
    <meta charset="UTF-8">
    <title>Sales Excel 上傳預覽頁面</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- <link rel="shortcut icon" href="img/favicon.ico"/> -->
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!-- global css -->
    <link type="text/css" rel="stylesheet" href="css/app.css"/>
    <!-- end of global css -->
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="vendors/datatables/css/dataTables.bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="css/custom.css">
    <link rel="stylesheet" href="css/custom_css/skins/skin-default.css" type="text/css" id="skin"/>
    <link rel="stylesheet" type="text/css" href="css/custom_css/datatables_custom.css">

    <link href="vendors/iCheck/css/all.css" rel="stylesheet"/>
    <link href="vendors/bootstrap-fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css"/>
    <!-- <link rel="stylesheet" type="text/css" href="css/custom.css"> -->
    <link rel="stylesheet" type="text/css" href="css/formelements.css">
    <!--end of page level css-->
    <style>
        div.dataTables_wrapper {
            margin: 0 auto;
        }
    </style>
</head>

<body class="skin-default">
<div class="preloader">
    <div class="loader_img"><img src="img/loader.gif" alt="loading..." height="64" width="64"></div>
</div>
<br>
<section class="content">   
    <div class="row">
        <div class="col-lg-12">
            <div class="panel ">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="ti-layout-grid3"></i>上傳預覽介面
                        <button class="pull-right btn btn-md btn-primary" id='upload_file' style="margin-top: -8px;">&nbsp;上傳檔案&nbsp;</button>
                    </h3>
                </div>
                <div class="panel-body">
                    <form role="form" method="POST" action="<?=base_url()?>Api/import_excel_for_view_excel/<?=$token?>" id="form" enctype="multipart/form-data" style="display:inline">
                        <input type="file" onchange="upload_img();" name="img" id="img" >
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- wrapper-->
<!-- global js -->
<script src="js/app.js" type="text/javascript"></script>
<!-- end of global js -->
<!-- begining of page level js -->
<script type="text/javascript" src="vendors/datatables/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="vendors/datatables/js/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="js/custom_js/datatables_custom.js"></script>

<script src="vendors/iCheck/js/icheck.js"></script>
<script src="vendors/bootstrap-fileinput/js/fileinput.min.js" type="text/javascript"></script>
<script src="js/custom_js/form_elements.js"></script>
<!-- end of page level js -->

<script>
    var formData;
    var file_name;
    $(document).ready(function () {
        $('#img').hide();

        $('#upload_file').click(function() {
            $('#img').trigger("click");
        });
    });

    function upload_img() {
        console.log($('#img')[0].files[0]);
        formData = new FormData(); 
        formData.append("dir_name", 'uploads/customer_manage');
        let file = $('#img')[0].files[0];
        formData.append('img', file);
        console.log(formData);
        $.ajax({
            type: "POST",
            cache: false,
            processData: false,
            contentType: false,
            headers : {
                'Authorization': "Sales <?=$token?>",
            },
            url: "<?=base_url()?>Api/upload_img",
            data: formData,
            dataType: "json",
            success: function(data) {
                if (data.status) {
                    console.log(data.img_src);
                    file_name = data.img_src;
                    post_uploads_excel(data.img_src);
                }
            },
            error: function(e) {
                console.log(e);
            }
        });
    }

    function post_uploads_excel(file_name) {
        formData = new FormData(); 
        formData.append("file_name", file_name);
        $.ajax({
            type: "POST",
            cache: false,
            processData: false,
            contentType: false,
            headers : {
                'Authorization': "Sales <?=$token?>",
            },
            url: "<?=base_url()?>Api/view_excel/<?=$token?>",
            data: formData,
            dataType: "json",
            success: function(data) {
                if (data.status) {
                    console.log(data);
                    alert(data.msg);
                    location.href=data.url;
                }
            },
            error: function(e) {
                console.log(e);
            }
        });
    }
</script>
</body>

</html>