<!DOCTYPE html>
<html>

<head>
    <? include("header.php"); ?>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- styles -->
    <!--page level css -->
    <link href="css/lockscreen.css" rel="stylesheet">
    <!--end page level css-->
</head>
<body>
<div class="preloader">
    <div class="loader_img"><img src="img/loader.gif" alt="loading..." height="64" width="64"></div>
</div>
<div class="top">
    <div class="colors"></div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1">
            <div class="lockscreen-container">
                <div id="output"></div>
                <img src="img/logo_white.png" alt="Logo" style="height: 50px;">
                <div class="form-box">
                    <div class="avatar" style="background: url(<?=$this->session->avatar ?>); background-color: #FFF; background-size: contain;"></div>
                    <form action="<?=base_url() ?>mgr/unlock" method="post">
                        <div class="form">
                            <div class="row">
                                <h4 class="user-name hidden-sm hidden-md hidden-lg"><?=$this->session->name ?></h4>
                                <div class="col-sm-6">
                                    <input type="text" class="hidden-xs" value="<?=$this->session->name ?>" readonly>
                                </div>
                                <div class="col-sm-6">
                                    <input type="password" name="password" class="form-control" placeholder="請輸入密碼">
                                </div>
                            </div>
                            <button class="btn login" id="index" type="submit">
                                <img src="img/pages/arrow-right.png" alt="Go" width="30" height="30">
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- global js -->
<script src="js/jquery.min.js" type="text/javascript"></script>
<script src="js/bootstrap.min.js" type="text/javascript"></script>
<!-- end of global js -->
<!-- page css -->
<script src="js/lockscreen.js"></script>
<!-- end of page css -->
</body>

</html>