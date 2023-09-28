
    <meta charset="UTF-8">
    <base href="<?=base_url() ?>backend/"></base>
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> -->

    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <meta name="description" content="溫度部落 後台管理系統">
    <title>起笑 KIHSIAO 後台管理系統</title>
    <!-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> -->
    <!-- Favicons-->
    <link rel="apple-touch-icon" sizes="57x57" href="../assets/img/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="../assets/img/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="../assets/img/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="../assets/img/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="../assets/img/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="../assets/img/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="../assets/img/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/img/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="../assets/img/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../assets/img/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/img/favicon/favicon-16x16.png">
    <!-- <link rel="manifest" href="../assets/img/favicon/manifest.json"> -->
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="../assets/img/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!-- global css -->
    <link type="text/css" href="css/app.css?v=<?=date('ymdH') ?>" rel="stylesheet"/>
    
    <!-- <link rel="stylesheet" type="text/css" href="css/theme_normal.css?v=<?=date('ymdH') ?>"> -->
    <link rel="stylesheet" type="text/css" href="css/theme_gold.css?v=<?=date('ymdH') ?>">
    <!-- <link rel="stylesheet" type="text/css" href="css/theme_grey.css?v=<?=date('ymdH') ?>"> -->
    <!-- <link rel="stylesheet" type="text/css" href="css/theme_blue.css?v=<?=date('ymdH') ?>"> -->
    
    <link rel="stylesheet" type="text/css" href="css/jqbtk.min.css">
    <link href="vendors/datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="css/anypicker-font.css" />
    <link rel="stylesheet" type="text/css" href="css/anypicker.css" />

    <link rel="stylesheet" type="text/css" href="css/anypicker-ios.css" />
    <link rel="stylesheet" type="text/css" href="css/anypicker-android.css" />
    <link rel="stylesheet" type="text/css" href="css/anypicker-windows.css" />

    <!-- <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> -->
    <!-- end of global css -->
    <!--page level css -->
    <style>
    #toggle_exhibit li.dropdown-toggle a:hover{
        background-color: #2c7dac;
    }

    #toggle_exhibit .dropdown-toggle span{
        font-size: 16px;
        color: #EEE;
    }
    #toggle_exhibit .dropdown-menu{
        overflow-y: scroll; 
        max-height: 360px;
        /*max-width: 120px;*/
    }
    #toggle_exhibit .dropdown-menu::-webkit-scrollbar {
        display: none;
    }
    #toggle_exhibit .dropdown-menu li a{
        padding: 12px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 30px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 34px !important;
    }
    .select2-container--default .select2-selection--single {
        height: 34px !important;
    }

    </style>
