<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <base href="<?=base_url() ?>backend/"></base>
    <title>起笑 KIHSIAO</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" href="img/favicon.ico"/>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!-- global css -->
    <link type="text/css" href="css/app.css" rel="stylesheet"/>
    <!-- end of bootstrap -->
    <!--page level css -->
    <link type="text/css" href="vendors/themify/css/themify-icons.css" rel="stylesheet"/>
    <link href="vendors/iCheck/css/all.css" rel="stylesheet">
    <link href="vendors/bootstrapvalidator/css/bootstrapValidator.min.css" rel="stylesheet"/>
    <link href="css/login.css" rel="stylesheet">
    <!--end page level css-->
</head>

<body id="sign-in">
<div class="preloader">
    <div class="loader_img"><img src="img/loader.gif" alt="loading..." height="64" width="64"></div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 login-form">
            <div class="panel-header">
                <h3 class="text-center">
                    起笑 KIHSIAO 後台管理系統
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12">
                        <?
                            if($error!=""){
                        ?>
                        <div class="alert alert-danger">
                            <a href="#" class="alert-link"><?=$error ?></a>
                        </div>
                        <?
                            }
                        ?><form action="<?=base_url() ?>mgr/login" id="authentication" method="post" class="login_validator">
                            <div class="form-group">
                                <label for="email" class="sr-only"> 帳號</label>
                                <input type="text" class="form-control  form-control-lg" id="email" name="email"
                                       placeholder="E-mail">
                            </div>
                            <div class="form-group">
                                <label for="password" class="sr-only">密碼</label>
                                <input type="password" class="form-control form-control-lg" id="password"
                                       name="password" placeholder="密碼">
                            </div>
                            <!-- <div class="form-group checkbox">
                                <label for="remember">
                                    <input type="checkbox" name="remember" id="remember" checked>&nbsp; 記住我
                                </label>
                            </div> -->
                            <div class="form-group">
                                <input type="submit" value="登入" class="btn btn-primary btn-block"/>
                            </div>
                            <!-- <a href="forgot_password.html" id="forgot" class="forgot"> Forgot Password ? </a>

                            <span class="pull-right sign-up">New ? <a href="register.html">Sign Up</a></span> -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- global js -->
<script src="js/jquery.min.js" type="text/javascript"></script>
<script src="js/bootstrap.min.js" type="text/javascript"></script>
<!-- end of global js -->
<!-- page level js -->
<script type="text/javascript" src="vendors/iCheck/js/icheck.js"></script>
<script src="vendors/bootstrapvalidator/js/bootstrapValidator.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/custom_js/login.js"></script>
<!-- end of page level js -->
</body>

</html>
