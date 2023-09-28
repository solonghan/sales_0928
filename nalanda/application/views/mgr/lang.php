<!DOCTYPE html>
<html>

<head>
    <? include("header.php"); ?>

    <link href="vendors/clockface/css/clockface.css" rel="stylesheet" type="text/css"/>
    <link href="vendors/colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet" type="text/css"/>
    <link href="vendors/datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css"/>
    <link href="vendors/bootstrap-touchspin/css/jquery.bootstrap-touchspin.css" rel="stylesheet" type="text/css"/>
    <link href="vendors/bootstrap-multiselect/css/bootstrap-multiselect.css" rel="stylesheet" type="text/css"/>
    <link href="vendors/clockpicker/css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css"/>
    <link href="vendors/bootstrap-switch/css/bootstrap-switch.css" rel="stylesheet" type="text/css"/>
    

    <link rel="stylesheet" type="text/css" href="css/pickers.css">


    <link rel="stylesheet" type="text/css" href="vendors/datatables/css/dataTables.bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="vendors/datatablesmark.js/css/datatables.mark.min.css"/>

    <link href="vendors/toastr/css/toastr.min.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="css/custom_css/toastr_notificatons.css">

    <!--end of page level css-->

</head>
<? include("nav+menu.php"); ?>
    <aside class="right-side">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                多國語系管理
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?=base_url() ?>mgr/">
                        <i class="fa fa-fw ti-home"></i> 主控板
                    </a>
                </li>
                <li class="active">多國語系管理
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
                                <i class="ti-view-list"></i> 新增欄位
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="m-t-10">
                                <table class="table horizontal_table table-striped">
                                    <thead>
                                    <tr>
                                        <th style="width: auto;">Key(eng)</th>
                                        <th style="width: 14%;">中文</th>
                                        <th style="width: 14%;">英文</th>
                                        <th style="width: 14%;">泰國</th>
                                        <th style="width: 14%;">越南</th>
                                        <th style="width: 14%;">印尼</th>
                                        <th style="width: 8%;">備註</th>
                                        <th style="width:30px;"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <td><input type="text" id="new_name" style="width: 100%;" value=""></td>
                                        <td><textarea id="new_zh" rows="2" style="width: 100%;"></textarea></td>
                                        <td><textarea id="new_en" rows="2" style="width: 100%;"></textarea></td>
                                        <td><textarea id="new_thb" rows="2" style="width: 100%;"></textarea></td>
                                        <td><textarea id="new_vnd" rows="2" style="width: 100%;"></textarea></td>
                                        <td><textarea id="new_idr" rows="2" style="width: 100%;"></textarea></td>
                                        <td><textarea id="new_remark" rows="2" style="width: 100%;"></textarea></td>
                                        <td>
                                            <button type="button" class="btn btn-xs btn-danger" id="add_row">新增</button>
                                        </td>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-xs-12">
                    <div class="panel filterable">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="ti-view-list"></i> 多國語系管理
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="m-t-10">
                                <table class="table horizontal_table table-striped" id="showtable">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="width: auto;">Key</th>
                                        <th style="width: 14%;">中文</th>
                                        <th style="width: 14%;">英文</th>
                                        <th style="width: 14%;">泰國</th>
                                        <th style="width: 14%;">越南</th>
                                        <th style="width: 14%;">印尼</th>
                                        <th style="width: 8%;">備註</th>
                                        <th style="width:30px;">更新<br>時間</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <? for($i=0;$i<count($list);$i++){ $item = $list[$i]; ?>
                                    <tr>
                                        <td><?=$item['id'] ?></td>
                                        <td><input type="text" id="name_<?=$item['id'] ?>" style="width: 100%;" value="<?=$item['name'] ?>"></td>
                                        <td><textarea id="zh_<?=$item['id'] ?>" rows="2" style="width: 100%;"><?=$item['zh'] ?></textarea></td>
                                        <td><textarea id="en_<?=$item['id'] ?>" rows="2" style="width: 100%;"><?=$item['en'] ?></textarea></td>
                                        <td><textarea id="thb_<?=$item['id'] ?>" rows="2" style="width: 100%;"><?=$item['thb'] ?></textarea></td>
                                        <td><textarea id="vnd_<?=$item['id'] ?>" rows="2" style="width: 100%;"><?=$item['vnd'] ?></textarea></td>
                                        <td><textarea id="idr_<?=$item['id'] ?>" rows="2" style="width: 100%;"><?=$item['idr'] ?></textarea></td>
                                        <td><textarea id="remark_<?=$item['id'] ?>" rows="2" style="width: 100%;"><?=$item['remark'] ?></textarea></td>
                                        <td><span id="update_<?=$item['id'] ?>"><?=str_replace(" ", "<br>", date("m/d H:i", strtotime($item['update_date']))) ?></span></td>
                                    </tr>
                                    <? } ?>
                                    </tbody>
                                </table>
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
<script type="text/javascript" src="vendors/datatables/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="vendors/datatables/js/dataTables.bootstrap.js"></script>
<script src="vendors/mark.js/jquery.mark.js" charset="UTF-8"></script>
<script src="vendors/datatablesmark.js/js/datatables.mark.min.js" charset="UTF-8"></script>

<script src="vendors/bootstrap-multiselect/js/bootstrap-multiselect.js" type="text/javascript"></script>
<script src="vendors/select2/js/select2.js" type="text/javascript"></script>
<script src="vendors/toastr/js/toastr.min.js"></script>
<script>
    // var data = JSON.parse('<?=str_replace(" ", "<s>", str_replace("\n", "<br>", str_replace("'", "\'", json_encode($list)))) ?>');
    // var dataindex = JSON.parse('<?=json_encode($listindex) ?>');
    var dtInstance;
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

    $(document).ready(function () {
        window.onload = function () {
            $(function () {
                dtInstance = $("#showtable").DataTable({
                    "responsive": true,
                    bLengthChange: false,
                    "pageLength": -1,
                    // "ordering": false,
                    "order": [[0, "desc"]],
                    columnDefs: [
                        { targets: [1,2,3,4,5,6,7], orderable: false},
                        { targets: [1], type: "html-input"}
                    ]
                });
            });
        }
        var current_edit_input = "";
        $("#showtable input").on("focus", function(event){
            if (current_edit_input != event.target.id) {
                if (confirm("Key值確定要變更嗎?\n可能會造成系統錯誤，請謹慎修改")) {
                    current_edit_input = event.target.id;
                }else{
                    current_edit_input = "";
                    $(this).trigger('blur');
                }
            }
        });

        $("#showtable textarea, #showtable input").on('blur', function(event) {
            var id = (event.target.id).split("_");
            var value = $(this).val();
            
            // if (data[dataindex[id[1]]][id[0]] != pvalue) {
                $.ajax({
                    url: "<?=base_url() ?>mgr/lang/update",
                    data: {
                        id: id[1],
                        key: id[0],
                        value: value
                    },
                    type:"POST",
                    dataType:'json',
                    success: function(d){
                        if (d.status == "100") {
                            // data[dataindex[id[1]]][id[0]] = value;
                            $("#update_"+id[1]).html(d.update_date);
                        }else{
                            toastr["error"](d.msg, "發生問題");
                        }
                    },
                    error:function(xhr, ajaxOptions, thrownError){ 
                        toastr["error"]("請檢查網路狀態再重試一次", "網路發生問題");
                    }
                });
            // }
        });

        $("#apitable textarea").on('blur', function(event) {
            var id = (event.target.id).split("_");
            var value = $(this).val();
            var key = $("#akey_"+id[1]).html();
            
            // if (data[dataindex[id[1]]][id[0]] != pvalue) {
                $.ajax({
                    url: "<?=base_url() ?>mgr/lang/update_api_msg",
                    data: {
                        index: id[0],
                        key: key,
                        value: value
                    },
                    type:"POST",
                    dataType:'json',
                    success: function(d){
                        if (d.status == "100") {
                            // data[dataindex[id[1]]][id[0]] = value;
                            $("#update_"+id[1]).html(d.update_date);
                        }else{
                            toastr["error"](d.msg, "發生問題");
                        }
                    },
                    error:function(xhr, ajaxOptions, thrownError){ 
                        toastr["error"]("請檢查網路狀態再重試一次", "網路發生問題");
                    }
                });
            // }
        });

        $("#add_row").on('click', function(event) {
            if ($("#new_name").val() == "") {
                toastr["error"]("Key值不可為空", "發生問題");
                return;
            }
            $.ajax({
                url: "<?=base_url() ?>mgr/lang/addrow",
                data: {
                    name: $("#new_name").val(),
                    zh: $("#new_zh").val(),
                    en: $("#new_en").val(),
                    thb: $("#new_thb").val(),
                    vnd: $("#new_vnd").val(),
                    idr: $("#new_idr").val(),
                    remark: $("#new_remark").val()
                },
                type:"POST",
                dataType:'json',
                success: function(d){
                    if (d.status == "100") {
                        dtInstance.row.add(
                            [
                                d.id,
                                '<input type="text" id="name_" style="width: 100%;" value="'+$("#new_name").val()+'">',
                                '<textarea id="zh_'+d.id+'" rows="2" style="width: 100%;">'+$("#new_zh").val()+'</textarea>',
                                '<textarea id="en_'+d.id+'" rows="2" style="width: 100%;">'+$("#new_en").val()+'</textarea>',
                                '<textarea id="thb_'+d.id+'" rows="2" style="width: 100%;">'+$("#new_thb").val()+'</textarea>',
                                '<textarea id="vnd_'+d.id+'" rows="2" style="width: 100%;">'+$("#new_vnd").val()+'</textarea>',
                                '<textarea id="idr_'+d.id+'" rows="2" style="width: 100%;">'+$("#new_idr").val()+'</textarea>',
                                '<textarea id="remark_'+d.id+'" rows="2" style="width: 100%;">'+$("#new_remark").val()+'</textarea>',
                                '<span id="update_'+d.id+'">'+d.update_date+'</span>'
                            ]
                        ).draw();

                        $("#new_name").val("");
                        $("#new_zh").val("");
                        $("#new_en").val("");
                        $("#new_thb").val("");
                        $("#new_vnd").val("");
                        $("#new_idr").val("");
                        $("#new_remark").val("");
                    }else{
                        toastr["error"](d.msg, "發生問題");
                    }
                },
                error:function(xhr, ajaxOptions, thrownError){ 
                    toastr["error"]("請檢查網路狀態再重試一次", "網路發生問題");
                }
            });
        });
    });    
</script>
</body>

</html>
