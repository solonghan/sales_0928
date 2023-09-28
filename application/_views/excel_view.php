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
                        <button class="pull-right btn btn-md btn-primary" id='submit' style="margin-top: -8px;">&nbsp;確定送出&nbsp;</button>
                    </h3>
                </div>
                <div class="panel-body">
                    <form role="form" method="POST" action="<?=base_url()?>Api/import_excel_for_view_excel/<?=$token?>" id="form">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover nowrap display" id="example">
                                <thead>
                                <tr>
                                    <th>選擇</th>
                                    <th>編號</th>
                                    <th>姓名</th>
                                    <th>來源</th>
                                    <th>關係</th>
                                    <th>客戶分級</th>
                                    <th>告知</th>
                                    <th>約訪</th>
                                    <th>拜訪</th>
                                    <th>建議</th>
                                    <th>成交</th>
                                    <th>職業</th>
                                    <th>生日</th>
                                    <th>備註</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php echo $t_body; ?>
                                </tbody>
                            </table>
                            <br>
                        </div>
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
    $(document).ready(function () {
        $('#submit').click(function() {
            $('#form').submit();
        });
        $('#example').dataTable({
            destroy: true,
            "lengthMenu": [[-1], ["All"]],
            "iDisplayLength": -1,
            "scrollX": true
        });
    });
</script>
</body>

</html>