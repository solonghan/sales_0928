<!DOCTYPE html>
<html>

<head>
    <? include("header.php"); ?>
    <link href="vendors/select2/css/select2.min.css" rel="stylesheet" type="text/css">
    <link href="vendors/select2/css/select2-bootstrap.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.css" />

    <link href="css/native-toast.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/custom_css/toastr_notificatons.css">

    <link href="vendors/bootstrap-switch/css/bootstrap-switch.css" rel="stylesheet" type="text/css" />
    <link href="vendors/daterangepicker/css/daterangepicker.css" rel="stylesheet" type="text/css" />
    <link href="vendors/datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />

</head>
<? include("nav+menu.php"); ?>
<aside class="right-side">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= $title ?>
        </h1>
        <? foreach ($tool_btns as $item) : ?>
            <button class="pull-right btn btn-md <?= $item[2] ?>" style="margin-top: -20px; margin-right: 25px;" onclick="location.href='<?= $item[1] ?>';"><?= $item[0] ?></button>
        <? endforeach; ?>
        <ol class="breadcrumb">
            <li>
                <a href="<?= base_url() ?>mgr/">
                    <i class="fa fa-fw ti-home"></i> 首頁
                </a>
            </li>
            <? if (isset($parent) && $parent != "") : ?>
                <li><a href="<?= $parent_link ?>">
                        <?= $parent ?>
                    </a>
                </li>
            <? endif; ?>

            <li class="active"><?= $title ?>
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
                            <!-- <i class="ti-view-list"></i> --><i class="fa fa-cogs"></i> <?= $title ?>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="m-t-10">
                            <div class="pull-left" style="font-size:18px;color:blue">
                                <? if (isset($msg) != "") : ?>
                                    <?= $msg ?>
                                <? endif; ?>
                            </div>
                            <div class="row">
                                <?
                                $enable_controller = ["meeting", "operate"];
                                if (isset($controller) && in_array($controller, $enable_controller)) : ?>
                                    <div class="col-lg-4">
                                        <div class="input-group" style="width: 100%;">
                                            <div class="input-group-addon">
                                                <i class="fa fa-fw ti-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control" id="reservationtime" placeholder="篩選時間區間" autocomplete="off" />
                                        </div>
                                    </div>
                                <? endif; ?>
                                <div class="col-lg-3 col-md-4 col-sm-12 pull-right">
                                    <div class="input-group form-inline">
                                        <input type="text" class="form-control search">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default search_action" type="button">搜尋</button>
                                            <button class="btn btn-default search_clear" type="button">清空</button>
                                        </span>
                                    </div>
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
                                                echo ' style="width:' . $th_width[$index] . '";';
                                            }
                                            echo '>' . $t . '</th>';
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

<!-- template add Modal -->
<div class="modal fade" id="privilegeAssignModal" role="dialog" aria-labelledby="modalLabel" tabindex="-1">
    <div class="modal-dialog" role="document" style="width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">指派人員 群組名稱：<span class="pri_name">最高權限</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12" style="display: inline-flex; line-height: 28px;">
                        選擇人員：
                        <select class="form-control select2" style="width: 320px;">
                            <option value="">王大明(展覽處-五組 02-22222222#222)</option>
                            <option value="">江金沙(展覽處-三組 01-11111111#222)</option>
                            <option value="">劉奕如(展覽處-一組 02-33333333#222)</option>
                        </select>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <button class="btn btn-info btn-xs" id="" style="height: 28px; line-height: 18px;">加入</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <table id="coin_log_table" class="table table-striped dataTable no-footer">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>姓名</th>
                                    <th>帳號</th>
                                    <th>職稱</th>
                                    <th>單位/資訊</th>
                                    <th>加入時間</th>
                                    <td>動作</td>
                                </tr>
                            </thead>
                            <tbody id="">
                                <tr>
                                    <td>1</td>
                                    <td>王小明</td>
                                    <td>Wangmin</td>
                                    <td>專員</td>
                                    <td>主計處</td>
                                    <td>2020-09-01<br>15:34:21</td>
                                    <td><button class="btn btn-danger btn-xs del-btn"><span class="fa fa-fw fa-minus-square-o"></span></button></td>
                                </tr>

                                <tr>
                                    <td>1</td>
                                    <td>蕭英山</td>
                                    <td>eishan</td>
                                    <td>專員</td>
                                    <td>展覽處<br>(02)2245-3563</td>
                                    <td>2020-09-04<br>12:43:11</td>
                                    <td><button class="btn btn-danger btn-xs del-btn"><span class="fa fa-fw fa-minus-square-o"></span></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">關閉</button>
                <!-- <button id="template_save" type="button" class="btn btn-primary" data-dismiss="modal">儲存</button> -->
            </div>
        </div>
    </div>
</div>
<!-- template add Modal -->

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
<script>
    var page = 1;
    var search = "";

    var search_datetime1 = "";
    var search_datetime2 = "";

    var can_order_column_indedx = <?= json_encode($can_order_fields) ?>;
    var default_order_column = <?= $default_order_column ?>;
    var order_direction = '<?= $default_order_direction ?>';
    $(document).ready(function() {
        $(document).on('click', ".privilege-assign-member", function(event) {
            $("#privilegeAssignModal").modal("show");
        });

        $("#reservationtime").daterangepicker({
            timePicker: false,
            autoUpdateInput: false,
            // timePickerIncrement: 30,
            locale: {
                applyLabel: '確認',
                cancelLabel: '清空'
            },
            drops: "down"
        }).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' ~ ' + picker.endDate.format('YYYY-MM-DD'));
            search_datetime1 = picker.startDate.format('YYYY-MM-DD');
            search_datetime2 = picker.endDate.format('YYYY-MM-DD');
        }).on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            search_datetime1 = "";
            search_datetime2 = "";
        });


        $(".select2").select2();
        //Delete Action
        $(document).on('click', ".del-btn", function(event) {
            if (!confirm("確定刪除此筆資料嗎?")) return;
            var id = $(this).closest('tr').attr("data-id");

            $.ajax({
                url: '<?= $action ?>del',
                data: {
                    id: id
                },
                type: "POST",
                dataType: "json",
                success: function(msg) {
                    if (msg.status) {
                        $("tr[data-id=" + id + "]").fadeTo('fast', 0.5, function() {
                            $(this).remove();
                        });
                    }
                }
            });
        });

        //Edit Action
        $(document).on('click', ".edit-btn", function(event) {
            var id = $(this).closest('tr').attr("data-id");
            // location.href = '<?= $action ?>edit/'+id;
            location.href = '<?= $action ?>add/';
        });

        generate_order();
        load_data(page);

        $(".search").on('keypress', function(event) {
            if (event.which == 13) {
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
            if (event.which == 13 && $.isNumeric($(this).val())) {
                load_data(parseInt($(this).val()));
            }
        });

        $(document).on('click', '.sort_up', function(event) {
            var id = $(this).parent().attr("id").split("_")[1];
            var sort = parseInt($("#sort_" + id).val());
            sort = sort - 1;
            sort = (sort <= 0) ? 1 : sort;
            sort_action(id, sort);
        });

        $(document).on('click', '.sort_down', function(event) {
            var id = $(this).parent().attr("id").split("_")[1];
            var sort = parseInt($("#sort_" + id).val());
            sort = sort + 1;
            sort_action(id, sort);
        });

        $(document).on('click', '.order_btn', function(event) {
            var selected_index = $(this).attr("data-index");
            if (selected_index == default_order_column) {
                if (order_direction == "DESC") {
                    order_direction = "ASC";
                } else {
                    order_direction = "DESC";
                }
            } else {
                default_order_column = selected_index;
                order_direction = "DESC";
            }
            $("#data_table").find('th').each(function(index, el) {
                if (can_order_column_indedx.indexOf(index) != -1) {
                    if (index == default_order_column) {
                        if (order_direction == "DESC") {
                            $(this).find("button").html('<span class="glyphicon glyphicon-sort-by-attributes-alt"></span>');
                        } else {
                            $(this).find("button").html('<span class="glyphicon glyphicon-sort-by-attributes"></span>');
                        }
                    } else {
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
            $("#tr_" + id).find('.edit_field').each(function(index, el) {
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
                success: function(data) {
                    if (data.status) {
                        nativeToast({
                            message: '更新成功',
                            type: 'success',
                            position: 'top',
                            square: true,
                            edge: false,
                            debug: false
                        });
                    } else {
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

    function sort_action(id, sort) {
        $.ajax({
            type: "POST",
            url: "<?= $action ?>sort",
            data: {
                id: id,
                sort: sort
            },
            dataType: "json",
            success: function(data) {
                if (data.status) {
                    load_data(page);
                }
            },
            failure: function(errMsg) {}
        });
    }

    function load_data(goto_page) {
        page = goto_page;
        $.ajax({
            type: "POST",
            url: "<?= (isset($custom_data_url)) ? $custom_data_url : $action . 'data' ?>",
            data: {
                page: page,
                search: search,
                order: default_order_column,
                direction: order_direction
            },
            dataType: "json",
            success: function(data) {
                if (data.status) {
                    $("#content").html(data.html);
                    page = parseInt(data.page);
                    generate_page(data.total_page);
                    $(".curpage").val(page);
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
                        var status = (state) ? 1 : 0;
                        $.ajax({
                            type: "POST",
                            url: "<?= (isset($custom_switch_url)) ? $custom_switch_url : $action . 'switch_toggle' ?>",
                            data: {
                                id: id,
                                status: status
                            },
                            dataType: "json",
                            success: function(data) {

                            }
                        });
                    }
                });
                $('[data-toggle="tooltip"]').tooltip();
            },
            failure: function(errMsg) {}
        });
    }

    var page_range = 10;

    function generate_page(total_page) {
        page = parseInt(page);
        var html = "";
        var first = Math.floor((page - 1) / page_range) * page_range + 1;
        if (page == 1) {
            html = '<li class="paginate_button previous disabled"><a href="javascript:;">Previous</a></li>';
        } else {
            html = '<li class="paginate_button previous"><a href="javascript:load_data(' + (page - 1) + ');">Previous</a></li>';
        }

        for (var i = first; i < first + page_range && i <= total_page; i++) {
            html += '<li class="paginate_button ';
            if (i == page) html += ' active';
            html += '"><a href="javascript:load_data(' + i + ');">' + i + '</a></li>';
        }

        if (page == total_page) {
            html += '<li class="paginate_button next disabled"><a href="javascript:;">Next</a></li>';
        } else {
            html += '<li class="paginate_button next"><a href="javascript:load_data(' + (page + 1) + ');">Next</a></li>';
        }

        if (page != total_page) {
            html += '<li class="paginate_button last"><a href="javascript:load_data(' + (total_page) + ');">Last(' + total_page + ')</a></li>';
        }

        html += '<li class="paginate_button last"><a href="javascript:;" style="padding:0;"><input type="text" class="form-control curpage" style="width:56px; height:30px; border:0; text-align:center;" value="1"></a></li>';

        $(".page").html(html);
    }

    function generate_order() {
        $("#data_table").find('th').each(function(index, el) {
            if (can_order_column_indedx.indexOf(index) != -1) {
                if (index == default_order_column) {
                    $(this).append(
                        $("<button/>").addClass('btn order_btn btn-sm pull-right').css({
                            width: '16px',
                            height: '16px',
                            padding: 0
                        })
                        .html('<span class="glyphicon glyphicon-sort-by-attributes' + ((order_direction == "DESC") ? '-alt' : '') + '"></span>')
                        .attr("data-index", index)
                    );
                } else {
                    $(this).append(
                        $("<button/>").addClass('btn order_btn btn-sm pull-right').css({
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
</script>
</body>

</html>