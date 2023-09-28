<!DOCTYPE html>
<html>

<head>
    <? include("header.php"); ?>
    <link href="vendors/select2/css/select2.min.css" rel="stylesheet" type="text/css">
    <link href="vendors/select2/css/select2-bootstrap.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.css" />
    
    <link href="css/native-toast.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/custom_css/toastr_notificatons.css">

    <link href="vendors/bootstrap-switch/css/bootstrap-switch.css" rel="stylesheet" type="text/css"/>
    <link href="vendors/daterangepicker/css/daterangepicker.css" rel="stylesheet" type="text/css"/>
    <link href="vendors/datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
    <style type="text/css">
        table#data_table thead tr th {
            vertical-align: middle !important;
            text-align: center ;
        }
        table #content tr td {
            vertical-align: middle !important;
            text-align: center ;
        }
        table#data_table thead tr td.td_left {
            text-align: left !important;
        }
    </style>
</head>
<? include("nav+menu.php"); ?>
    <aside class="right-side">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?=$title ?></h1>
            <? foreach ($tool_btns as $item): ?>
                <button class="pull-right btn btn-md <?=$item[2] ?>" style="margin-top: -20px; margin-right: 25px;" onclick="location.href='<?=$item[1] ?>';">&nbsp;<?=$item[0] ?>&nbsp;</button>
            <? endforeach; ?>
            <? if(isset($action_btns)): foreach ($action_btns as $item): ?>
            <button class="action_btns pull-right btn btn-md <?=$item[2] ?>" style="margin-top: -20px; margin-right: 25px;" onclick="<?=$item[1] ?>"><?=$item[0] ?></button>
            <? endforeach; endif; ?>
            <input type="file" id="importexcel" style="display: none;" accept=".csv">
            <ol class="breadcrumb">
                <li>
                    <a href="<?=base_url() ?>mgr/">
                        <i class="fa fa-fw ti-home"></i>Home
                    </a>
                </li>
                <? if (isset($parent) && $parent != ""): ?>
                <li ><a href="<?=$parent_link ?>">
                        <?=$parent ?>
                    </a>
                </li>
                <? endif; ?>
                
                <li class="active"><?=$title ?>
                </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-lg-12 col-xs-12">
                    <div class="panel filterable">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-cogs"></i> <?=$title ?> 
                                <small class="sub_title"></small>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <?php
                            if (isset($status_btn)) {
                            ?>
                            <input type="button" class="btn btn-xs btn-default btn-status btn-primary" value="全部" style="width: 180px;margin-bottom: 8px;" data-val="ALL">
                            <?php }?>
                            <?php
                                foreach ($status_btn as $s) {
                            ?>
                            <input type="button" class="btn btn-xs btn-default btn-status" value="<?=$s[1] ?>" style="width: 180px;margin-bottom: 8px;" data-val="<?=$s[0] ?>"> 
                            <?
                                }
                            ?>
                            <div class="m-t-10">
                                <div class="col-lg-3 col-md-4 col-sm-12 pull-right">
                                    <div class="input-group form-inline">
                                        <input type="text" class="form-control search" placeholder="關鍵字..">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default search_action" type="button">搜尋</button>
                                            <button class="btn btn-default search_clear" type="button">清空</button>
                                            </span>
                                    </div>
                                </div>
                                <table class="table horizontal_table table-striped" id="data_table">
                                    <thead>
                                    <tr>
                                        <?  
                                            $index = 0;
                                            foreach ($th_title as $t) {
                                                echo '<th';
                                                if ($th_width[$index] != "") {
                                                    echo ' style="width:'.$th_width[$index].'";';
                                                }
                                                echo '>&nbsp;&nbsp;&nbsp;'.$t.'</th>';
                                                $index++;
                                            }
                                        ?>
                                    </tr>
                                    </thead>
                                    <tbody id="content"></tbody>
                                </table>
                                <ul class="pagination page pull-right"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="background-overlay"></div>
        </section>
        <!-- /.content -->
    </aside>
</div>

<!-- global js -->
<script src="js/app.js" type="text/javascript"></script>
<!-- end of global js -->

<!-- begining of page level js -->
<script src="vendors/mark.js/jquery.mark.js" charset="UTF-8"></script>
<script src="vendors/datatablesmark.js/js/datatables.mark.min.js" charset="UTF-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.js"></script>
<script src="js/native-toast.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="vendors/bootstrap-switch/js/bootstrap-switch.js" type="text/javascript"></script>

<script src="vendors/moment/js/moment.min.js" type="text/javascript"></script>
<script src="vendors/colorpicker/js/bootstrap-colorpicker.min.js" type="text/javascript"></script>
<script src="vendors/clockpicker/js/bootstrap-clockpicker.min.js" type="text/javascript"></script>
<script src="vendors/datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>

<script src="vendors/daterangepicker/js/daterangepicker.js" type="text/javascript"></script>
<!-- end of page level js -->

<!-- Wish333的 -->
<script>
    
    var page = 1;
    var search = "";
    var latern = 1;
    var search_datetime1 = "<?=(isset($default_date)?$default_date:"") ?>";
    var search_datetime2 = "<?=(isset($default_date)?$default_date:"") ?>";
    // var date_search_type = "created_at";
    
    var can_order_column_index = '<?=json_encode($can_order_fields) ?>';
    var default_order_column = '<?=(isset($default_order_column)?$default_order_column:"") ?>';
    var order_direction = '<?=(isset($default_order_direction)?$default_order_direction:"") ?>';
    var status = '<?=(isset($default_status)?$default_status:"") ?>';
    var del_alert_string = <?=(isset($del_alert_string))?"'$del_alert_string'":"'確定刪除此筆資料嗎?'"?>;
    var year = '<?=(isset($year)?$year:"") ?>';

    $(document).ready(function () {
        $(document).on('click', '.btn-review-reject', function(event) {
            $("#reviewRejectModal").modal("show");
        });

        $(document).on('click', ".privilege-assign-member", function(event) {
            $("#privilegeAssignModal").modal("show"); 
        });

        $("#reservationtime").daterangepicker({
            timePicker: false,
            autoUpdateInput: false,
            timePickerIncrement: 30,
            locale: {
                applyLabel: '確認',
                cancelLabel: '清空'
            },
            drops: "down",
            ranges: {
               '明日': [moment().add(1, 'days'), moment().add(1, 'days')],
               '今日': [moment(), moment()],
               '昨日': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               '過去7日': [moment().subtract(6, 'days'), moment()],
               '過去30日': [moment().subtract(29, 'days'), moment()],
               '本月': [moment().startOf('month'), moment().endOf('month')],
               '上個月': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            "locale": {
                "format": "YYYY-MM-DD",
                "separator": " ~ ",
                "applyLabel": "確認",
                "cancelLabel": "清空",
                "fromLabel": "From",
                "toLabel": "To",
                "customRangeLabel": "自選區間",
                "weekLabel": "W",
                "daysOfWeek": [
                    "日",
                    "一",
                    "二",
                    "三",
                    "四",
                    "五",
                    "六"
                ],
                "monthNames": [
                    "一月",
                    "二月",
                    "三月",
                    "四月",
                    "五月",
                    "六月",
                    "七月",
                    "八月",
                    "九月",
                    "十月",
                    "十一月",
                    "十二月"
                ],
                "firstDay": 0
            }
        }).on('apply.daterangepicker', function (ev, picker) {
            if (picker.startDate.format('YYYY-MM-DD') == picker.endDate.format('YYYY-MM-DD')) {
                $(this).val(picker.startDate.format('YYYY-MM-DD'));
            }else{
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' ~ ' + picker.endDate.format('YYYY-MM-DD'));
            }
            search_datetime1 = picker.startDate.format('YYYY-MM-DD');
            search_datetime2 = picker.endDate.format('YYYY-MM-DD');
            load_data(1);
        }).on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
            search_datetime1 = "";
            search_datetime2 = "";
            load_data(1);
        });

        $(".date_search_type").on('click', function(event) {
            $("#date_search_type_txt").html($(this).html());
            date_search_type = $(this).attr("data-val");
            
            load_data(page);
        });
        // $(".bill_status").on('change', function(event) {
        //     status = $(this).val(); 
        //     load_data(page);
        // });

        // $(".bill_project").on('change', function(event) {
        //     project = $(this).val(); 
        //     if (project == 3) {
        //         $(".latern_select").show();
        //     }else{
        //         $(".latern_select").hide();
        //     }
        //     load_data(page);
        // });

        // $(".bill_latern").on('change', function(event) {
        //     latern = $(this).val(); 
        //     load_data(page);  
        // });

        $(".select2").select2();
        //Delete Action
        $(document).on('click', ".del-btn", function(event) {
            if (!confirm(del_alert_string)) return;
            var id = $(this).closest('tr').attr("data-id");

            $.ajax({
                url: "<?=(isset($custom_del_url))?$custom_del_url:$action.'edit/del' ?>",
                data: {
                    del_id: id
                },
                type: "POST",
                dataType: "json",
                success: function(msg){
                    if (msg.status) {
                        $("tr[data-id="+id+"]").fadeTo('fast', 0.5, function() {
                            $(this).remove();
                        });
                    }
                },
                error: function(e){
                    console.log(e);
                }
            });
        });
        
        //Edit Action
        $(document).on('click', ".edit-btn", function(event) {
            var id = $(this).closest('tr').attr("data-id");
            location.href = '<?=$action ?>edit/'+id;
        });

        generate_order();
        load_data(page);

        $(".search").on('keypress', function(event) {
            if( event.which == 13 ){
                search = $(".search").val();
                load_data(1);
            }
        });

        $(".search_action").on('click', function(event) {
            search = $(".search").val();
            load_data(1);
        });

        $(".search_clear").on('click', function(event) {
            search = "";
            $(".search").val("");
            load_data(1);   
        });

        $(document).on('keypress', '.curpage', function(event) {
             if( event.which == 13 && $.isNumeric($(this).val())){
                load_data(parseInt($(this).val()));
            }
        });

        $(document).on('click', '.sort_up', function(event) {
            var id = $(this).parent().attr("id").split("_")[1];
            var sort = parseInt($("#sort_"+id).val());
            sort = sort - 1;
            sort = (sort <= 0)?1:sort;
            sort_action(id, sort);
        });

        $(document).on('click', '.sort_down', function(event) {
            var id = $(this).parent().attr("id").split("_")[1];  
            var sort = parseInt($("#sort_"+id).val());
            sort = sort + 1;
            sort_action(id, sort);
        });

        $(document).on('click', '.order_btn', function(event) {
            var selected_index = $(this).attr("data-index");
            if (selected_index == default_order_column) {
                if (order_direction == "DESC") {
                    order_direction = "ASC";
                }else{
                    order_direction = "DESC";
                }
            }else{
                default_order_column = selected_index;
                order_direction = "DESC";
            }
            $("#data_table").find('th').each(function(index, el) {
                if (can_order_column_index.indexOf(index) != -1) {
                    if (index == default_order_column) {
                        if (order_direction == "DESC") {
                            $(this).find("button").html('<span class="glyphicon glyphicon-sort-by-attributes-alt"></span>');
                        }else{
                            $(this).find("button").html('<span class="glyphicon glyphicon-sort-by-attributes"></span>');
                        }
                    }else{
                        $(this).find("button").html('<span class="glyphicon glyphicon-sort"></span>');
                    }
                } 
            });
            load_data(page);
        });

        $(document).on('click', '.ajax_update', function(event) {
            var id = $(this).attr("id").split("_")[1];
            var action = $(this).attr("data-action");

            var formData = new FormData();
            $("#tr_"+id).find('.edit_field').each(function(index, el) {
                formData.append($(this).attr("name"), $(this).val());
            }); 
            $.ajax({
                type: "POST",
                url: action,
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data){
                    if (data.status) {
                        nativeToast({
                            message: '更新成功',
                            type: 'success',
                            position: 'top',
                            square: true,
                            edge: false,
                            debug: false
                        });
                    }else{
                        nativeToast({
                            message: data.msg,
                            type: 'danger',
                            position: 'top',
                            square: true,
                            edge: false,
                            debug: false
                        });
                    }
                },
                failure: function(errMsg) {}
            }); 
        });

        <? if (isset($controller) && $controller == "bill"): ?>
        $("#importexcel").on('change', function(event) {
            var formData = new FormData();
            formData.append('import_file', $(this)[0].files[0]);

            $.ajax({
                   url : '<?=base_url() ?>mgr/bill/import_newebpay_bill',
                   type : 'POST',
                   data : formData,
                   processData: false,  // tell jQuery not to process the data
                   contentType: false,  // tell jQuery not to set contentType
                   dataType: "json",
                   success : function(data) {
                       if (data.status) {
                            msg = "已成功更新 "+data.res.success+" 筆資訊";
                            if (data.res.fail > 0) msg += "\n失敗 "+data.res.fail+" 筆";
                            alert(msg);
                            load_data(page);
                       }
                       $("#importexcel").val('');
                   }
            });
        });

        $(document).on('click', '.bill-recheck-btn', function(event) {
            var id = $(this).closest('tr').attr('data-id');
            $.ajax({
            type: "POST",
            url: "<?=$action ?>recheck",
            data: {
                id: id
            },
            dataType: "json",
            success: function(data){
                if (data.status) {
                    load_data(page);
                }
            },
            failure: function(errMsg) {}
        }); 
        });
        <? endif; ?>

        $(document).on('click', '.btn-latern-info', function(event) {
            var id = $(this).closest('tr').attr("data-id"); 
            
            $.ajax({
                type: "POST",
                url: "<?=$action ?>get_data",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(data){
                    if (data.status) {
                        var latern_info = data.data.latern_info;
                        $.each(latern_info, function(lid, item) {
                            $(".latern_position_"+lid).val(item.position);
                            if (item['receipt'] != "") {
                                var img_url = '<?=base_url() ?>'+item.receipt;
                                $(".latern_receipt_"+lid).attr("href", img_url);
                                $(".latern_receipt_"+lid+" img").attr("src", img_url);
                            }
                        });
                        
                        $("#laternAssignModal").modal("show");
                        $("#latern_wish_id").val(id);
                        $("#latern_area").val($("select.bill_latern").val());
                        $("#laternAssignModal .latern_info").html($("tr[data-id="+id+"] span.client_name").html());

                        $('.latern_op').hide();
                        $('.latern_op_'+$("select.bill_latern").val()).show();
                    }
                },
                failure: function(errMsg) {}
            }); 
        });

        $("#laternAssignSave").on('click', function(event) {
            var form = $('#latern_form')[0];
            var formData = new FormData(form);
            // formData.append('latern_receipt', $("input[name=latern_receipt")[0].files[0]);

             $.ajax({
                type: "POST",
                processData: false,
                contentType: false,
                url: "<?=$action ?>latern_info",
                data: formData,
                dataType: "json",
                success: function(data){
                    if (data.status) {
                        $("#laternAssignModal").modal("hide");
                        $("#latern_wish_id").val("");
                        load_data(page);
                        $('#latern_form').reset();
                    }
                },
                failure: function(errMsg) {}
            }); 
        });
    });

    function import_newebpay_bill(){
        $("#importexcel").click();
    }

    function sort_action(id, sort){
        $.ajax({
            type: "POST",
            url: "<?=$action ?>sort",
            data: {
                id: id,
                sort: sort
            },
            dataType: "json",
            success: function(data){
                if (data.status) {
                    load_data(page);
                }
            },
            failure: function(errMsg) {}
        }); 
    }    

    function load_data(goto_page){
        loading_start();
        page = goto_page;
        $.ajax({
            type: "POST",
            url: "<?=(isset($custom_data_url))?$custom_data_url:$action.'data' ?>",
            data: {
                page: page,
                search: search,
                order: default_order_column,
                direction: order_direction,
                status: status,
                year: year
            },
            dataType: "json",
            success: function(data){
                if (data.status) {
                    if (data.html == "") {
                        var col_cnt = $("#content").closest('table').find("thead tr th").length;
                        $("#content").html("<tr><td colspan='"+col_cnt+"' class='text-center'>查無資料</td></tr>");
                    }else{
                        $("#content").html(data.html);    
                    }
                    page = parseInt(data.page);
                    generate_page(data.total_page);
                    $(".curpage").val(page);

                    if (data.sub_title) $(".sub_title").html(data.sub_title);
                }
                // $(".select2").select2().on('change.select2', function(event) {
                //     var id = $(this).parent().attr("id").split("_")[1];
                //     var sort = $(this).val();
                //     sort_action(id, sort);
                // });
                $('.status_switcher').bootstrapSwitch({
                    onText: "開啟",
                    offText: "關閉",
                    onSwitchChange: function(e, state) {
                        var id = $(this).closest('tr').attr("data-id");
                        var status = (state)?1:0;
                        $.ajax({
                            type: "POST",
                            url: "<?=(isset($custom_data_url))?$custom_data_url:$action.'switch_toggle' ?>",
                            data: {
                                id: id,
                                status: status
                            },
                            dataType: "json",
                            success: function(data){
                                
                            }
                        });
                    }
                });
                $('[data-toggle="tooltip"]').tooltip();
                loading_stop();
            },
            failure: function(errMsg) {loading_stop();}
        }); 
    }

    var page_range = 10;
    function generate_page(total_page){
        page = parseInt(page);
        var html = "";
        var first = Math.floor((page-1)/page_range) * page_range + 1;
        if (page == 1) {
            html = '<li class="paginate_button previous disabled"><a href="javascript:;">Previous</a></li>';
        }else{
            html ='<li class="paginate_button previous"><a href="javascript:load_data('+(page-1)+');">Previous</a></li>';
        }

        for (var i = first; i < first + page_range && i <= total_page ; i++) {
            html += '<li class="paginate_button ';
            if(i == page) html += ' active';
            html += '"><a href="javascript:load_data('+i+');">'+i+'</a></li>';
        }

        if (page == total_page) {
            html += '<li class="paginate_button next disabled"><a href="javascript:;">Next</a></li>';
        }else{
            html += '<li class="paginate_button next"><a href="javascript:load_data('+(page+1)+');">Next</a></li>';
        }

        if (page != total_page) {
            html += '<li class="paginate_button last"><a href="javascript:load_data('+(total_page)+');">Last('+total_page+')</a></li>';
        }

        html += '<li class="paginate_button last"><a href="javascript:;" style="padding:0;"><input type="text" class="form-control curpage" style="width:56px; height:30px; border:0; text-align:center;" value="1"></a></li>';

        $(".page").html(html);
    }

    function generate_order(){
        $("#data_table").find('th').each(function(index, el) {
            if (can_order_column_index.indexOf(index) != -1) {
                if (index == default_order_column) {
                    $(this).append(
                        $("<button/>").addClass('btn order_btn btn-sm pull-left').css({
                            width: '16px',
                            height: '16px',
                            padding: 0
                        })
                        .html('<span class="glyphicon glyphicon-sort-by-attributes'+((order_direction == "DESC")?'-alt':'')+'"></span>')
                        .attr("data-index", index)
                    );
                }else{
                    $(this).append(
                        $("<button/>").addClass('btn order_btn btn-sm pull-left').css({
                            width: '16px',
                            height: '16px',
                            padding: 0
                        }).html('<span class="glyphicon glyphicon-sort"></span>')
                        .attr("data-index", index)
                    );
                }
            } 
        });
    }

    $(".btn-status").on('click', function(event) {
        // dtInstance.DataTable({mark: false});
        $(document).find('.btn-status').each(function(index, el) {
            $(this).removeClass("btn-primary");
        });
        $(this).addClass("btn-primary");

        var val = $(this).attr("data-val");
        status = val;
        load_data(page);
    });

    function export_action(){
        loading_start();
        $.ajax({
            type: "POST",
            url: "<?=(isset($custom_data_url))?$custom_data_url:$action.'data/export' ?>",
            data: {
                page: 'all',
                search: search,
                order: default_order_column,
                direction: order_direction,
                start_date: search_datetime1,
                end_date: search_datetime2,
                date_search_type: date_search_type,
                status: status,
                project: project,
                latern: latern
            },
            dataType: "json",
            success: function(data){
                if (data.status) {
                    window.open(data.url);
                }
                loading_stop();
            },
            failure: function(errMsg) {loading_stop();}
        }); 
    }
</script>

<!-- CTFA的 -->
<!-- <script>
    var page = 1;
    var search = "";
    
    var can_order_column_index = <?=json_encode($can_order_fields) ?>;
    var default_order_column = <?=$default_order_column ?>;
    var order_direction = '<?=$default_order_direction ?>';
    var status = '<?=$default_status ?>';
    var del_alert_string = <?=(isset($del_alert_string))?"'$del_alert_string'":"'確定刪除此筆資料嗎?'"?>;
    var year = <?=$year?>;
    
    $(document).ready(function () {
        $(document).on('click', ".del-btn", function(event) {
            if (!confirm(del_alert_string)) return;
            var id = $(this).attr("id").split("_")[1];
            
            $.ajax({
                url: "<?=(isset($custom_del_url))?$custom_del_url:$action.'del' ?>",
                data: {
                    id:id
                },
                type: "POST",
                dataType: "json",
                success: function(msg){
                    if (msg.status) {
                        $("#tr_"+id).fadeTo('fast', 0.5, function() {
                            $(this).remove();
                        });
                    }
                    
                    console.log(msg);
                },
                error: function(e){
                    console.log(e);
                }
            });
        });

        generate_order();
        load_data(page);

        $(".search").on('keypress', function(event) {
            if( event.which == 13 ){
                search = $(".search").val();
                load_data(1);
            }
        });

        $(".search_action").on('click', function(event) {
            search = $(".search").val();
            load_data(1);
        });

        $(".search_clear").on('click', function(event) {
            search = "";
            $(".search").val("");
            load_data(1);   
        });

        $(document).on('keypress', '.curpage', function(event) {
             if( event.which == 13 && $.isNumeric($(this).val())){
                load_data(parseInt($(this).val()));
            }
        });

        $(document).on('click', '.sort_up', function(event) {
            var id = $(this).parent().attr("id").split("_")[1];
            var sort = parseInt($("#sort_"+id).val());
            sort = sort - 1;
            sort = (sort <= 0)?1:sort;
            sort_action(id, sort);
        });

        $(document).on('click', '.sort_down', function(event) {
            var id = $(this).parent().attr("id").split("_")[1];  
            var sort = parseInt($("#sort_"+id).val());
            sort = sort + 1;
            sort_action(id, sort);
        });

        $(document).on('click', '.order_btn', function(event) {
            var selected_index = $(this).attr("data-index");
            if (selected_index == default_order_column) {
                if (order_direction == "DESC") {
                    order_direction = "ASC";
                }else{
                    order_direction = "DESC";
                }
            }else{
                default_order_column = selected_index;
                order_direction = "DESC";
            }
            $("#data_table").find('th').each(function(index, el) {
                if (can_order_column_index.indexOf(index) != -1) {
                    if (index == default_order_column) {
                        if (order_direction == "DESC") {
                            $(this).find("button").html('<span class="glyphicon glyphicon-sort-by-attributes-alt"></span>');
                        }else{
                            $(this).find("button").html('<span class="glyphicon glyphicon-sort-by-attributes"></span>');
                        }
                    }else{
                        $(this).find("button").html('<span class="glyphicon glyphicon-sort"></span>');
                    }
                } 
            });
            load_data(page);
        });

        $(document).on('click', '.ajax_update', function(event) {
            var id = $(this).attr("id").split("_")[1];
            var action = $(this).attr("data-action");

            var formData = new FormData();
            $("#tr_"+id).find('.edit_field').each(function(index, el) {
                formData.append($(this).attr("name"), $(this).val());
            }); 
            $.ajax({
                type: "POST",
                url: action,
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data){
                    if (data.status) {
                        nativeToast({
                            message: '更新成功',
                            type: 'success',
                            position: 'top',
                            square: true,
                            edge: false,
                            debug: false
                        });
                    }else{
                        nativeToast({
                            message: data.msg,
                            type: 'danger',
                            position: 'top',
                            square: true,
                            edge: false,
                            debug: false
                        });
                    }
                },
                failure: function(errMsg) {}
            }); 
        });
    });

    function sort_action(id, sort){
        $.ajax({
            type: "POST",
            url: "<?=$action ?>sort",
            data: {
                id: id,
                sort: sort
            },
            dataType: "json",
            success: function(data){
                if (data.status) {
                    load_data(page);
                }
            },
            error: function(e){
                console.log(e);
            },
            failure: function(errMsg) {}
        }); 
    }    

    function load_data(goto_page){
        page = goto_page;
        $.ajax({
            type: "POST",
            url: "<?=(isset($custom_data_url))?$custom_data_url:$action.'data' ?>",
            data: {
                page: page,
                search: search,
                order: default_order_column,
                direction: order_direction,
                status: status,
                year: year
            },
            dataType: "json",
            success: function(data){
                if (data.status) {
                    if (data.html == "") {
                        var col_cnt = $("#content").closest('table').find("thead tr th").length;
                        $("#content").html("<tr><td colspan='"+col_cnt+"' class='text-center'>查無資料</td></tr>");
                    }else{
                        $("#content").html(data.html);    
                    }
                    page = parseInt(data.page);
                    generate_page(data.total_page);
                    $(".curpage").val(page);

                    if (data.sub_title) $(".sub_title").html(data.sub_title);
                }
                // $(".select2").select2().on('change.select2', function(event) {
                //     var id = $(this).parent().attr("id").split("_")[1];
                //     var sort = $(this).val();
                //     sort_action(id, sort);
                // });
                $('.status_switcher').bootstrapSwitch({
                    onText: "開啟",
                    offText: "關閉",
                    onSwitchChange: function(e, state) {
                        var id = $(this).closest('tr').attr("data-id");
                        var status = (state)?1:0;
                        $.ajax({
                            type: "POST",
                            url: "<?=(isset($custom_data_url))?$custom_data_url:$action.'switch_toggle' ?>",
                            data: {
                                id: id,
                                status: status
                            },
                            dataType: "json",
                            success: function(data){
                                
                            }
                        });
                    }
                });
                $('[data-toggle="tooltip"]').tooltip();
                loading_stop();
            },
            error: function(errMsg) {
                console.log(errMsg.responseText);
            },
            failure: function(errMsg) {
                //console.log(errMsg);
            }
        }); 
    }

    var page_range = 10;
    function generate_page(total_page){
        page = parseInt(page);
        var html = "";
        var first = Math.floor((page-1)/page_range) * page_range + 1;
        if (page == 1) {
            html = '<li class="paginate_button previous disabled"><a href="javascript:;">Previous</a></li>';
        }else{
            html ='<li class="paginate_button previous"><a href="javascript:load_data('+(page-1)+');">Previous</a></li>';
        }

        for (var i = first; i < first + page_range && i <= total_page ; i++) {
            html += '<li class="paginate_button ';
            if(i == page) html += ' active';
            html += '"><a href="javascript:load_data('+i+');">'+i+'</a></li>';
        }

        if (page == total_page) {
            html += '<li class="paginate_button next disabled"><a href="javascript:;">Next</a></li>';
        }else{
            html += '<li class="paginate_button next"><a href="javascript:load_data('+(page+1)+');">Next</a></li>';
        }

        if (page != total_page) {
            html += '<li class="paginate_button last"><a href="javascript:load_data('+(total_page)+');">Last('+total_page+')</a></li>';
        }

        html += '<li class="paginate_button last"><a href="javascript:;" style="padding:0;"><input type="text" class="form-control curpage" style="width:56px; height:30px; border:0; text-align:center;" value="1"></a></li>';

        $(".page").html(html);
    }

    function generate_order(){
        $("#data_table").find('th').each(function(index, el) {
            if (can_order_column_index.indexOf(index) != -1) {
                if (index == default_order_column) {
                    $(this).append(
                        $("<button/>").addClass('btn order_btn btn-sm pull-left').css({
                            width: '16px',
                            height: '16px',
                            padding: 0
                        })
                        .html('<span class="glyphicon glyphicon-sort-by-attributes'+((order_direction == "DESC")?'-alt':'')+'"></span>')
                        .attr("data-index", index)
                    );
                }else{
                    $(this).append(
                        $("<button/>").addClass('btn order_btn btn-sm pull-left').css({
                            width: '16px',
                            height: '16px',
                            padding: 0
                        }).html('<span class="glyphicon glyphicon-sort"></span>')
                        .attr("data-index", index)
                    );
                }
            } 
        });
    }

    $(".btn-status").on('click', function(event) {
        // dtInstance.DataTable({mark: false});
        $(document).find('.btn-status').each(function(index, el) {
            $(this).removeClass("btn-primary");
        });
        $(this).addClass("btn-primary");

        var val = $(this).attr("data-val");
        status = val;
        load_data(page);
    });
</script> -->
</body>
</html>
