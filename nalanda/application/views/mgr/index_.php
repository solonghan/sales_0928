<!DOCTYPE html>
<html>
<head>
    <? include("header.php"); ?>
    <!-- <link rel="stylesheet" href="vendors/swiper/css/swiper.min.css"> -->
    <!-- <link href="vendors/nvd3/css/nv.d3.min.css" rel="stylesheet" type="text/css"> -->
    <!-- <link rel="stylesheet" href="vendors/lcswitch/css/lc_switch.css"> -->
    
    <link href="css/custom_css/flot_charts.css" rel="stylesheet" type="text/css">

    <link href="css/custom_css/dashboard1.css" rel="stylesheet" type="text/css"/>
    <!-- <link href="css/custom_css/dashboard1_timeline.css" rel="stylesheet"/> -->

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

        
        
    </aside>
    <!-- /.right-side --> </div>
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

<script src="vendors/datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<!-- end of page level js -->
<script>
    $(document).ready(function($) {
        // $(".flip").flip({
        //     trigger: 'hover'
        // });

        $("#subscribers-chart").sparkline([3, 4, 6, 3, 5], {
            type: 'pie',
            width: '55',
            height: '55',
            sliceColors: ['#6699cc', '#66cc99', '#f0ad4e', '#66ccff', '#66cc99']
        });

        var d1, d2, data, Options;

        <?
            $d1 = array();
            $axis = array();

            $today = date("Y-m-d");
            $max_value = 0;
            $statistic = array(); //tmp
            for ($i=0; $i < 30; $i++) { 
                $d = date('Y-m-d', strtotime("-".$i." day", strtotime($today)));
                array_push($d1, array(strtotime($d)*1000, 0));
                array_push($axis, array($i, $d));
            }

            for ($i=0; $i <count($statistic); $i++) { 
                for($j = 0; $j < count($d1); $j++){
                    if (strtotime(date($statistic[$i]['date']))*1000 == $d1[$j][0]) {
                        $d1[$j][1] = $statistic[$i]['value'];

                    }   
                }
                if ($statistic[$i]['value'] > $max_value) $max_value = $statistic[$i]['value'];
                // array_push($d1, array( strtotime(date($statistic[$i]['date']))*1000, $statistic[$i]['value'] ));
                // array_push($axis, array($i, date("Y-m-d", strtotime($statistic[$i]['date']))));
            }

            $y_step = $max_value / 100 * 10;
        ?>
        d1 = <?=json_encode($d1) ?>;
        

        data = [
            {
                label: "流量",
                data: d1,
                color: "#66cc99"
            }
        ];

        Options = {
            xaxis: {
                <?
                    // $min = (count($axis) <= 0) ? 0 :explode("-", $axis[0][1]);
                    // $max = (count($axis) <= 0) ? 0 :explode("-", $axis[count($axis)-1][1]);
                    
                    $today = date("Y-m-d");
                    $min = date('Y-m-d', strtotime("-30 day", strtotime($today)));
                    $max = $today*1000;
                ?>
                // min: (new Date(<?=$min[0] ?>, <?=$min[1] ?>, <?=$min[2] ?>)).getTime(),
                // max: (new Date(<?=$max[0] ?>, <?=$max[1] ?>, <?=$max[2] ?>)).getTime(),
                mode: "time",
                tickSize: [1, "day"],
                // monthNames: ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"],
                // tickLength: 0

                tickLength: 5,  
                timezone: "browser",
                timeformat: "%b<br>%d"
            },
            yaxis: {
                tickSize: <?=$y_step ?>
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
<!-- guide message -->
<?if($this->encryption->decrypt($this->session->brand_status) == 'final'):?>
<script>
    $(function(){
        $("#guide_content").text("恭喜您完成設定，現在您可以開始使用網站功能！")
        $("#guideModal").modal("show");
        $.ajax({
            url: "<?=base_url()?>mgr/dashboard/finish_guide",
            type: "post",
            success: function(msg){
                console.log(123);
            }
        })
    })
</script>
<?endif;?>
</body>

</html>