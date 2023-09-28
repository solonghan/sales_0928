<!DOCTYPE html>
<html>

<head>
    <? include("header.php"); ?>
    <!-- <link rel="stylesheet" href="vendors/swiper/css/swiper.min.css"> -->
    <!-- <link href="vendors/nvd3/css/nv.d3.min.css" rel="stylesheet" type="text/css"> -->
    <!-- <link rel="stylesheet" href="vendors/lcswitch/css/lc_switch.css"> -->

    <link href="css/custom_css/flot_charts.css" rel="stylesheet" type="text/css">

    <link href="css/custom_css/dashboard1.css" rel="stylesheet" type="text/css" />
    <!-- <link href="css/custom_css/dashboard1_timeline.css" rel="stylesheet"/> -->
    <link href="vendors/toastr/css/toastr.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="css/custom_css/toastr_notificatons.css">


    <link href="vendors/circliful/css/jquery.circliful.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="vendors/animate/animate.min.css" />

    <link rel="stylesheet" type="text/css" href="css/custom_css/widgets1.css">
    <!--end of page level css-->
</head>
<? include("nav+menu.php"); ?>
<aside class="right-side">

    <section class="content-header">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-5 col-xs-8">
                <div class="header-element">
                    <h3>主控台
                    </h3>
                </div>
            </div>
            <div class="col-lg-4 col-lg-offset-2 col-md-6 col-sm-7 col-xs-4">
                <div class="header-object">
                    <!-- <span class="option-search pull-right hidden-xs">
                            <span class="search-wrapper">
                                <input type="text" placeholder="Search here"><i class="ti-search"></i>
                            </span>
                        </span> -->
                </div>
            </div>
        </div>
    </section>

    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="panel ">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <i class="ti-bar-chart-alt"></i> 近30天流量
                        </h4>
                        <!-- <span class="pull-right">
                                    <i class="fa fa-fw ti-angle-up clickable"></i>
                                    <i class="fa fa-fw ti-close removepanel clickable"></i>
                            </span> -->
                    </div>
                    <div class="panel-body">
                        <div id="basicFlotLegend" class="flotLegend"></div>
                        <div id="line-chart" class="flotChart1"></div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-sm-6 col-md-6 col-lg-3">
                <div class="widget-bg-color-icon card-box">
                    <div class="bg-icon bg-warning pull-left">
                        <i class="ti-eye text-white"></i>
                    </div>
                    <div class="text-right">
                        <h3 class="text-dark"><b id="widget_count1">2652</b></h3>
                        <p>Visitors</p>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-3">
                <div class="widget-bg-color-icon card-box border_success">
                    <div class="bg-icon pull-left">
                        <i class="ti-pie-chart text-success"></i>
                    </div>
                    <div class="text-right">
                        <h3 class="text-success"><b id="widget_count3">3251</b></h3>
                        <p>Sales status</p>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="col-sm-6 col-md-6 col-lg-3">
                <div class="widget-bg-color-icon card-box">
                    <div class="bg-icon bg-icon-info pull-left">
                        <i class="ti-cup text-primary"></i>
                    </div>
                    <div class="text-right">
                        <h3 class="text-dark"><b id="widget_count2">7698</b></h3>
                        <p class="text-primary">Income status</p>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-3">
                <div class="widget-bg-color-icon card-box border_danger">
                    <div class="text-center">
                        <h3 class="text-danger"><b id="widget_count4">4358</b></h3>
                        <p>Total sales:<span class="text-success"> 3251</span><span class="pull-right"><i class="ti-angle-double-down text-danger m-r-5"></i>7.85%</span></p>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>

        </div>



    </section>

</aside>
<!-- /.right-side -->
</div>
<!-- ./wrapper -->
<!-- global js -->
<div id="qn"></div>
<script src="js/app.js" type="text/javascript"></script>
<!-- end of global js -->
<script type="text/javascript" src="vendors/moment/js/moment.min.js"></script>
<script type="text/javascript" src="vendors/advanced_newsTicker/js/newsTicker.js"></script>
<!-- <script type="text/javascript" src="js/dashboard1.js"></script> -->

<script type="text/javascript" src="js/custom_js/sparkline/jquery.flot.spline.js"></script>

<script type="text/javascript" src="vendors/flip/js/jquery.flip.min.js"></script>
<script type="text/javascript" src="vendors/lcswitch/js/lc_switch.min.js"></script>


<script src="vendors/flotchart/js/jquery.flot.js" type="text/javascript"></script>
<script src="vendors/flotchart/js/jquery.flot.resize.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript" src="vendors/flotchart/js/jquery.flot.stack.js"></script>
<script language="javascript" type="text/javascript" src="vendors/flotchart/js/jquery.flot.time.js"></script>
<script src="vendors/flotspline/js/jquery.flot.spline.min.js" type="text/javascript"></script>
<script src="vendors/flotchart/js/jquery.flot.categories.js" type="text/javascript"></script>
<script src="vendors/flotchart/js/jquery.flot.pie.js" type="text/javascript"></script>
<script src="vendors/flot.tooltip/js/jquery.flot.tooltip.js" type="text/javascript"></script>


<script src="vendors/toastr/js/toastr.min.js"></script>
<script src="vendors/datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>



<script type="text/javascript" src="vendors/countUp.js/js/countUp.js"></script>
<!--Sparkline Chart-->
<script type="text/javascript" src="vendors/jquery-knob/js/jquery.knob.js"></script>
<!-- flip --->
<script src="vendors/flip/js/jquery.flip.min.js" type="text/javascript"></script>
<!-- circliful -->
<script src="vendors/circliful/js/jquery.circliful.min.js" type="text/javascript"></script>
<!-- sparkline charts -->
<script src="js/custom_js/sparkline/jquery.flot.spline.js"></script>

<script type="text/javascript" src="js/custom_js/widgets1.js"></script>
<!-- end of page level js -->
<script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-bottom-full-width",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "1000",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "swing",
        "showMethod": "show"
    };
    $(document).ready(function($) {
        $("#subscribers-chart").sparkline([3, 4, 6, 3, 5], {
            type: 'pie',
            width: '55',
            height: '55',
            sliceColors: ['#6699cc', '#66cc99', '#f0ad4e', '#66ccff', '#66cc99']
        });

        var d1, d2, data, Options;

        <?
        $d1 = array();
        $d2 = array();
        $axis = array();

        $today = date("Y-m-d");
        $max_value = 0;

        for ($i = 0; $i < 30; $i++) {
            $d = date('Y-m-d', strtotime("-" . $i . " day", strtotime($today)));
            array_push($d1, array(strtotime($d) * 1000, 0));
            array_push($d2, array(strtotime($d) * 1000, 0));
            array_push($axis, array($i, $d));
        }

        for ($i = 0; $i < count($statistic); $i++) {
            for ($j = 0; $j < count($d1); $j++) {
                if (strtotime(date($statistic[$i]['date'])) * 1000 == $d1[$j][0]) {
                    $d1[$j][1] = $statistic[$i]['value'];
                }
            }
            if ($statistic[$i]['value'] > $max_value) $max_value = $statistic[$i]['value'];
            // array_push($d1, array( strtotime(date($statistic[$i]['date']))*1000, $statistic[$i]['value'] ));
            // array_push($axis, array($i, date("Y-m-d", strtotime($statistic[$i]['date']))));
        }

        for ($i = 0; $i < count($statistic_independent); $i++) {
            for ($j = 0; $j < count($d2); $j++) {
                if (strtotime(date($statistic_independent[$i]['date'])) * 1000 == $d2[$j][0]) {
                    $d2[$j][1] = $statistic_independent[$i]['value'];
                }
            }
            if ($statistic_independent[$i]['value'] > $max_value) $max_value = $statistic_independent[$i]['value'];
            // array_push($d1, array( strtotime(date($statistic[$i]['date']))*1000, $statistic[$i]['value'] ));
            // array_push($axis, array($i, date("Y-m-d", strtotime($statistic[$i]['date']))));
        }

        $y_step = $max_value / 100 * 10;
        ?>
        d1 = <?= json_encode($d1) ?>;
        d2 = <?= json_encode($d2) ?>;


        data = [{
                label: "流量",
                data: d1,
                color: "#66cc99"
            },
            {
                label: "不重複訪客",
                data: d2,
                color: "#f0ad4e"
            }
        ];

        Options = {
            xaxis: {
                <?
                // $min = (count($axis) <= 0) ? 0 :explode("-", $axis[0][1]);
                // $max = (count($axis) <= 0) ? 0 :explode("-", $axis[count($axis)-1][1]);

                $today = date("Y-m-d");
                $min = date('Y-m-d', strtotime("-30 day", strtotime($today)));
                $max = strtotime($today) * 1000;
                ?>
                // min: (new Date(<?= $min[0] ?>, <?= $min[1] ?>, <?= $min[2] ?>)).getTime(),
                // max: (new Date(<?= $max[0] ?>, <?= $max[1] ?>, <?= $max[2] ?>)).getTime(),
                mode: "time",
                tickSize: [1, "day"],
                // monthNames: ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"],
                // tickLength: 0

                tickLength: 5,
                timezone: "browser",
                timeformat: "%b<br>%d"
            },
            yaxis: {
                tickSize: <?= $y_step ?>
            },
            series: {
                lines: {
                    show: true,
                    fill: false,
                    lineWidth: 2
                },
                points: {
                    show: true,
                    radius: 4.5,
                    fill: true,
                    fillColor: "#ffffff",
                    lineWidth: 2
                }
            },
            grid: {
                hoverable: true,
                clickable: false,
                borderWidth: 0
            },
            legend: {
                container: '#basicFlotLegend',
                show: true
            },

            tooltip: true,
            tooltipOpts: {
                content: '%s: %y'
            }

        };


        var holder = $('#line-chart');

        if (holder.length) {
            $.plot(holder, data, Options);
        }
    });
</script>
</body>

</html>