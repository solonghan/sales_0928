<!DOCTYPE html>
<html>

<head>
    <? include("header.php"); ?>
    <link rel="stylesheet" href="vendors/datetime/css/jquery.datetimepicker.css">
    <link href="vendors/airdatepicker/css/datepicker.min.css" rel="stylesheet" type="text/css">
    <link href="vendors/select2/css/select2.min.css" rel="stylesheet" type="text/css">
    <link href="vendors/select2/css/select2-bootstrap.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.css" />
    <link href="vendors/iCheck/css/all.css" rel="stylesheet">
    <style>
        .del-btn{
            position:absolute; 
            top:0; 
            left:14px; 
            margin:3%; 
            height:25px; 
            width:25px; 
            line-height:25px; 
            padding:0;
        }

        .pics.row {
          display: -webkit-box;
          display: -webkit-flex;
          display: -ms-flexbox;
          display:         flex;
          flex-wrap: wrap;
        }
        .pics.row > [class*='col-'] {
          display: flex;
          flex-direction: column;
        }

    </style>
</head>
<? include("nav+menu.php"); ?>
    <aside class="right-side">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <?=$title ?>
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?=base_url() ?>mgr/">
                        <i class="fa fa-fw ti-home"></i> 首頁
                    </a>
                </li>
                
                <? if ($parent != ""): ?>
                <li ><a href="<?=$parent_link ?>">
                        <i class="fa fa-fw ti-folder"></i> <?=$parent ?>
                    </a>
                </li>
                <? endif; ?>
                <li class="active"><?=$title ?></li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-lg-12 col-xs-12">
                    <div class="panel filterable">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="ti-view-list"></i> <?=$title ?>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" action="<?=$action ?>" id="new_form" enctype='multipart/form-data'>
                                <?
                                    
                                    foreach ($param as $item):
                                ?>
                                <? if ($item[2] == "header"): ?>
                                <h4><b><?=$item[0] ?></b></h4>
                                <? else: ?>
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">
                                        <?
                                            echo $item[0]; 
                                            if ($item[4]) echo "<span class='text text-danger'>*</span>";
                                        ?>
                                    </label>
                                    <div class="col-sm-10">
                                        <? if ($item[2] == "text"): ?>
                                        <input type="text" class="form-control" name="<?=$item[1] ?>" value="<?=$item[3] ?>"<?=(isset($can_edit)&&!$can_edit)?" disabled":"" ?>>
                                        <? elseif ($item[2] == "number"): ?>
                                        <input type="number" class="form-control" name="<?=$item[1] ?>" value="<?=$item[3] ?>"<?=(isset($can_edit)&&!$can_edit)?" disabled":"" ?>>
                                        <? elseif ($item[2] == "plain"): ?>
                                        <div style="line-height: 40px; border-bottom: 1px solid #EEE;" data-name="<?=$item[1] ?>"><?=$item[3] ?></div>
                                        <? elseif($item[2] == "textarea"): ?>
                                        <textarea class="ckeditor" id="<?=$item[1] ?>" name="<?=$item[1] ?>"<?=(isset($can_edit)&&!$can_edit)?" disabled":"" ?>><?=$item[3] ?></textarea>
                                        <? elseif($item[2] == "textarea_plain"): ?>
                                        <textarea class="form-control" id="<?=$item[1] ?>" name="<?=$item[1] ?>" style="height: 200px;"<?=(isset($can_edit)&&!$can_edit)?" disabled":"" ?>><?=$item[3] ?></textarea>
                                        <? elseif($item[2] == "checkbox"): ?>
                                        <input type="checkbox" class="form-control" name="<?=$item[1] ?>"<?=($item[3]!="" && $item[3]==1)?' checked':'' ?><?=(isset($can_edit)&&!$can_edit)?" disabled":"" ?>>
                                        <? elseif($item[2] == "checkbox_multi"): ?>
                                            <div style="display: inline-flex; line-height: 34px; flex-wrap: wrap;">
                                            <? foreach ($checkbox[$item[1]] as $c): ?>
                                                <label>
                                                    <input style="margin-top: -5px;" type="checkbox" value="<?=$c[2] ?>" name="<?=$c[0] ?>[]"<?=($c[3]==1)?' checked':'' ?><?=(isset($can_edit)&&!$can_edit)?" disabled":"" ?>> <?=$c[1] ?>
                                                </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <? endforeach; ?>
                                            </div>
                                        <? elseif($item[2] == "day"): ?>
                                        <input type="text" class="form-control daypicker" name="<?=$item[1] ?>" value="<?=$item[3] ?>" autocomplete="off"<?=(isset($can_edit)&&!$can_edit)?" disabled":"" ?>>
                                        <? elseif($item[2] == "datetime"): ?>
                                        <input type="text" class="form-control datetimepicker" name="<?=$item[1] ?>" value="<?=$item[3] ?>" autocomplete="off"<?=(isset($can_edit)&&!$can_edit)?" disabled":"" ?>>
                                        <? elseif($item[2] == "city"): ?>
                                        <select class="form-control select2" name="city" style="width: 49%;">
                                            <? 
                                                for ($i=0; $i < count($city); $i++) { 
                                                    echo '<option value="'.$i.'"';
                                                    if ($i == $item[3][0]) echo ' selected';
                                                    echo '>'.$city[$i]['name'].'</option>';
                                                }
                                            ?>
                                        </select>
                                        <select class="form-control select2" name="dist" style="width: 49%;">
                                            <?
                                                for ($i=0; $i < count($city[$item[3][0]]['dist']); $i++) { 
                                                    $dist = $city[$item[3][0]]['dist'][$i];
                                                    echo '<option value="'.$dist['c3'].'"';
                                                    if ($dist['c3'] == $item[3][1]) echo ' selected';
                                                    echo '>'.$dist['c3']." ".$dist['name'].'</option>';
                                                }
                                            ?>
                                        </select>
                                        <? elseif($item[2] == "select"): ?>
                                        <select class="form-control select2" name="<?=$item[1] ?>"<?=(isset($can_edit)&&!$can_edit)?" disabled":"" ?>>
                                            <? 
                                                if (isset($item[6])) {
                                                    foreach ($select[$item[1]] as $option){
                                                        echo '<option value="'.$option[$item[6][0]].'"';
                                                        if ($item[3] == $option[$item[6][0]]) echo ' selected';
                                                        echo '>'.$option[$item[6][1]].'</option>';
                                                    }     
                                                }else{
                                                    foreach ($select[$item[1]] as $option){
                                                        echo '<option value="'.$option.'"';
                                                        if ($item[3] == $option) echo ' selected';
                                                        echo '>'.$option.'</option>';
                                                    } 
                                                }
                                                
                                            ?>
                                        </select>
                                        <? elseif($item[2] == "select_multi"): ?>
                                        <select class="form-control select2" name="<?=$item[1] ?>[]" multiple="multiple"<?=(isset($can_edit)&&!$can_edit)?" disabled":"" ?>>
                                            <? 
                                                foreach ($select[$item[1]] as $option){
                                                    echo '<option value="'.$option[$item[6][0]].'"';
                                                    if (is_array($item[3]) && in_array($option[$item[6][0]], $item[3])) echo ' selected';
                                                    echo '>'.$option[$item[6][1]].'</option>';
                                                } 
                                            ?>
                                        </select>
                                        <? elseif(substr($item[2], 0, 4) == "file"): ?>
                                            <input data-multi="<?=($item[2]=="file_multi")?'true':'false' ?>" data-related="<?=$item[1] ?>" class="file_upload" type="file" id="fileupload_<?=$item[1] ?>" style="display: none;"<?=($item[2]=="file_multi")?' multiple':'' ?>>
                                            <? if(isset($can_edit)&&!$can_edit): ?><? else: ?>
                                            <button type="button" class="btn btn-sm btn-info" onclick="fileupload_<?=$item[1] ?>.click();" style="background-color: #65aadd;">選擇檔案</button>
                                            <? endif; ?>
                                            <input type="hidden" name="<?=$item[1] ?>" id="<?=$item[1] ?>" value="<?=$item[3] ?>">
                                            <input type="hidden" name="<?=$item[1] ?>_deleted" id="<?=$item[1] ?>_deleted" value="">
                                            <div class="row" id="files_<?=$item[1] ?>">
                                                <? if (isset($files) && isset($files[$item[1]])): ?>
                                                    <? foreach ($files[$item[1]] as $f): //Array: id, name ?>
                                                    <div id="file_<?=$f['id'] ?>" class="col-xs-12" style="margin: 10px 0;">
                                                        <? if(isset($can_edit)&&!$can_edit): ?><? else: ?>
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="delete_file('<?=$item[1] ?>','<?=$f['id'] ?>');"><span class="fa fa-fw ti-trash"></span></button>
                                                        <? endif; ?>
                                                        &nbsp;&nbsp;<a href="<?=base_url()."file/".$f['id'] ?>" target="_blank"><?=$f['name'] ?></a>
                                                    </div>
                                                    <? endforeach; ?>
                                                <? endif; ?>
                                            </div>
                                        <? elseif($item[2] == "img"): ?>
                                            <input data-multi="false" data-ratio="<?=$item[8] ?>" data-related="<?=$item[1] ?>" class="img_upload" type="file" id="imgupload_<?=$item[1] ?>" style="display: none;" accept="image/*">
                                            <? if(isset($can_edit)&&!$can_edit): ?><? else: ?>
                                            <button type="button" class="btn btn-sm btn-info" onclick="imgupload_<?=$item[1] ?>.click();">選擇照片</button>
                                            <button id="delphoto_<?=$item[1] ?>" type="button" class="btn btn-sm btn-danger" onclick="delete_photo('<?=$item[1] ?>');"<?=($item[3] == "")?' style="display:none;"':"" ?>>刪除照片</button>
                                            <? endif; ?>
                                            <input type="hidden" name="<?=$item[1] ?>" id="<?=$item[1] ?>" value="<?=$item[3] ?>">
                                            <div id="img_<?=$item[1] ?>" style="width: 256px; margin-top: 6px; background-color: #FFF; border:1px solid #DDD; padding: 2px; border-radius: 2px;<?=($item[3] == "")?' display:none;':"" ?>">
                                                <img src="<?=($item[3] != "")?base_url().$item[3]:"" ?>" style="width: 250px;">
                                            </div>
                                        <? elseif($item[2] == "img_multi"): ?>
                                            <input data-multi="true" data-ratio="<?=$item[6] ?>" data-related="<?=$item[1] ?>" class="img_upload" type="file" id="imgupload_<?=$item[1] ?>" style="display: none;" accept="image/*">
                                            <? if(isset($can_edit)&&!$can_edit): ?><? else: ?>
                                            <button type="button" class="btn btn-sm btn-info" onclick="imgupload_<?=$item[1] ?>.click();">選擇照片</button>
                                            <? endif; ?>
                                            <!-- xxx.jpg;abc.jpb; -->
                                            <input type="hidden" name="<?=$item[1] ?>" id="<?=$item[1] ?>" value="<?=$item[3] ?>">
                                            <input type="hidden" name="picdeleted_<?=$item[1] ?>" id="picdeleted_<?=$item[1] ?>" value="">
                                            <div class="row pics" id="pics_<?=$item[1] ?>">
                                                <?
                                                    if ($item[3] != "") {
                                                        $pics = explode(",", $item[3]);
                                                        foreach ($pics as $c) {
                                                ?>
                                                <div class="<?=($type=="edit")?'col-lg-3 col-md-4 col-sm-4 col-xs-6':'col-lg-2 col-md-3 col-sm-4 col-xs-6' ?>">
                                                    <a data-fancybox="gallery_<?=$item[1] ?>" href="<?=base_url().$c ?>"><img src="<?=base_url().$c ?>" class="thumbnail" style="width:100%;"></a>
                                                    <? if(isset($can_edit)&&!$can_edit): ?><? else: ?>
                                                    <button type="button" class="btn btn-sm btn-danger del-btn" onclick="del_multi_img(this, '<?=$c ?>', '<?=$item[1] ?>');">
                                                        <span class="fa fa-fw ti-trash"></span>
                                                    </button>
                                                    <? endif; ?>
                                                </div>
                                                <?
                                                        }
                                                    }
                                                ?>
                                            </div>
                                        <? elseif($item[2] == "img_multi_without_crop"): ?>
                                            <input data-multi="true" data-related="<?=$item[1] ?>" class="multiple_img_upload" type="file" id="imgupload_<?=$item[1] ?>" style="display: none;" accept="image/*" multiple>
                                            
                                            <button type="button" class="btn btn-sm btn-info" onclick="imgupload_<?=$item[1] ?>.click();">選擇照片</button>
                                            
                                            <input type="hidden" name="<?=$item[1] ?>" id="<?=$item[1] ?>" value="<?=$item[3] ?>">
                                            <input type="hidden" name="picdeleted_<?=$item[1] ?>" id="picdeleted_<?=$item[1] ?>" value="">
                                            <div class="row pics" id="pics_<?=$item[1] ?>">
                                                <?
                                                    if (isset($pics) && is_array($pics)) {
                                                        foreach ($pics as $pic) {
                                                            $c = $pic['url'];
                                                ?>
                                                <div class="<?=($type=="edit")?'col-lg-3 col-md-4 col-sm-4 col-xs-6':'col-lg-2 col-md-3 col-sm-4 col-xs-6' ?>">
                                                    <a data-fancybox="gallery_<?=$item[1] ?>" href="<?=base_url().$c ?>"><img src="<?=base_url().$c ?>" class="thumbnail" style="width:100%;"></a>
                                                    <? if(isset($can_edit)&&!$can_edit): ?><? else: ?>
                                                    <button type="button" class="btn btn-sm btn-danger del-btn" onclick="del_multi_img(this, '<?=$c ?>', '<?=$item[1] ?>');">
                                                        <span class="fa fa-fw ti-trash"></span>
                                                    </button>
                                                    <? endif; ?>
                                                </div>
                                                <?
                                                        }
                                                    }
                                                ?>
                                            </div>
                                        <? endif; ?>
                                        <? if ($item[5] != ""): ?>
                                        <small class="text text-danger"><?=$item[5] ?></small>
                                        <? endif; ?>
                                    </div>
                                </div>
                                <? endif; ?>
                                <? endforeach; ?>
                                <? if ($submit_txt != ""): ?>
                                <div class="form-group">
                                    <div class="col-sm-10 col-sm-offset-2">
                                        <button type="button" class="btn btn-md btn-primary submit_btn"><?=$submit_txt ?></button>
                                    </div>
                                </div>
                                <? endif; ?>
                            </form>
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

<script src="vendors/datetime/js/jquery.datetimepicker.full.min.js" type="text/javascript"></script>
<script src="vendors/airdatepicker/js/datepicker.min.js" type="text/javascript"></script>
<script src="vendors/airdatepicker/js/datepicker.en.js" type="text/javascript"></script>
<script src="ckeditor/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.js"></script>
<script type="text/javascript" src="vendors/iCheck/js/icheck.js"></script>

<script>
    <? if (isset($city)) : ?>
    var city = JSON.parse('<?=json_encode($city) ?>');
    <? endif ?>
    var language = {
        daysMin: ['日', '一', '二', '三', '四', '五', '六'],
        months: ['一月','二月','三月','四月','五月','六月', '七月','八月','九月','十月','十一月','十二月'],
        monthsShort: ['一月','二月','三月','四月','五月','六月', '七月','八月','九月','十月','十一月','十二月'],
        dateFormat: 'yyyy-mm-dd',
        timeFormat: 'hh:ii:00',
        firstDay: 0
    };
    $(document).ready(function () {
        $(".select2").select2();
        $('.daypicker').datepicker({
            language: language,
            minDate: new Date('<?=date('Y-m-d') ?>'),
            position: "bottom left",
            autoClose: true
        });
        $('.datetimepicker').datepicker({
            language: language,
            minDate: new Date('<?=date('Y-m-d') ?>'),
            position: "bottom left",
            autoClose: true,
            timepicker: true
        });
        $(".submit_btn").on('click', function(event) {
            $("#new_form").submit();
        });
        $(document).on('input', 'input[type=number]', function(event) {
            if ($(this).val() != "" && parseInt($(this).val()) < 0) 
                $(this).val(0);
        });
        $(".form-action").on('click', function(event) {
            event.preventDefault();
            return false;
        });
        $("input").iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '80%'
        });

        $("select[name=city]").on('change', function(e) {
            // var data = e.params.data;
            var selected_id = $(this).val();
            var new_data = new Array();
            for (var i = 0; i < city[selected_id]['dist'].length; i++) {
                new_data.push(
                    {
                        "id": city[selected_id]['dist'][i]['c3'],
                        "text": city[selected_id]['dist'][i]['c3']+" "+city[selected_id]['dist'][i]['name']
                    }
                )
            }
            $("select[name=dist]").empty().select2({
                data: new_data
            })
        });
        $(document).on('click', '.num_minus', function(event) {
            var id = $(this).parent().attr("id").split("_")[1];
            var num = parseInt($("#select_"+id).val());
            num = num - 1;
            num = (num < 0)?0:num;
            $("#select_"+id).val(num);
        });

        $(document).on('click', '.num_plus', function(event) {
            var id = $(this).parent().attr("id").split("_")[1];  
            var num = parseInt($("#select_"+id).val());
            var total = parseInt($(this).closest('.pattern').attr("data-max"));
            num = num + 1;
            num = (num >= total)?total:num;
            $("#select_"+id).val(num);
        });

        $(".multiple_img_upload").on('change', function(event) {
            var related_id = $(this).attr("data-related");
            var formData = new FormData();
            
            $.each($(this)[0].files, function(i, file) {
                formData.append('pics['+i+']', file);    
            });
            $.ajax({
                url: "<?=base_url() ?>mgr/dashboard/multiple_img_upload",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                type:"POST",
                dataType:'json',
                success: function(data){
                    if (data.status) {
                        jQuery.each(data.data, function(index, pic) {
                            $("#pics_"+related_id).append(
                                $("<div/>").addClass('col-lg-2 col-md-3 col-sm-4 col-xs-6').append(
                                    $("<a/>").attr({"href":"<?=base_url() ?>"+pic, "data-fancybox":"gallery_"+related_id}).append(
                                        $("<img/>").addClass('thumbnail').css({'width':'100%'}).attr("src", "<?=base_url() ?>"+pic)
                                    )
                                ).append(
                                    $("<button/>").attr("type", "button").addClass('btn btn-sm btn-danger del-btn').on('click', function(event) {
                                        del_multi_img($(this), pic, related_id);  
                                    }).append(
                                        $('<span/>').addClass('fa fa-fw ti-trash')
                                    )
                                )
                            );
                            $("#"+related_id).val($("#"+related_id).val()+pic+";");
                        });
                        
                    }
                },
                error:function(xhr, ajaxOptions, thrownError){ 
                    alert("照片上傳發生錯誤"); 
                }
            });
        });

        $(".file_upload").on('change', function(event) {
            var multiple = $(this).attr("data-multi");
            var related_id = $(this).attr("data-related");
            var formData = new FormData();
            // formData.append('relation_id', <?=0//$id ?>);
            
            if (multiple == "true") {
                $.each($(this)[0].files, function(i, file) {
                    formData.append('files['+i+']', file);    
                });    
            }else{
                formData.append('files', $(this)[0].files[0]);
            }
            
            $.ajax({
                url: "<?=base_url() ?>mgr/dashboard/file_upload",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                type:"POST",
                dataType:'json',
                success: function(data){
                    if (data.status) {
                        if (multiple == "true") {
                            jQuery.each(data.data, function(index, file) {
                                $("#files_"+related_id).append(
                                    $("<div/>").attr("id", "file_"+file.id).addClass('col-xs-12').css({'margin-top':'10px'}).append(
                                        $("<button/>").attr("type", "button").addClass('btn btn-sm btn-danger').on('click', function(event) {
                                            delete_file(related_id, file.id);  
                                        }).append(
                                            $('<span/>').addClass('fa fa-fw ti-trash')
                                        )
                                    ).append('&nbsp;&nbsp;').append(
                                        $("<a/>").attr({"href":"<?=base_url()."file/" ?>"+file.id}).append(
                                            file.realname
                                        )
                                    )
                                );
                                $("#"+related_id).val($("#"+related_id).val()+file.id+";");
                            });    
                        }else{
                            var file = data.data[0];
                            $("#files_"+related_id).append(
                                $("<div/>").attr("id", "file_"+file.id).addClass('col-xs-12').css({'margin-top':'10px'}).append(
                                    $("<button/>").attr("type", "button").addClass('btn btn-sm btn-danger').on('click', function(event) {
                                        delete_file(related_id, file.id);  
                                    }).append(
                                        $('<span/>').addClass('fa fa-fw ti-trash')
                                    )
                                ).append('&nbsp;&nbsp;').append(
                                    $("<a/>").attr({"href":"<?=base_url()."file/" ?>"+file.id}).append(
                                        file.realname
                                    )
                                )
                            );
                            if ($("#"+related_id).val() != "") {
                                var old_id = $("#"+related_id).val().replace(";", "");
                                delete_file(related_id, old_id, false);    
                            }
                            $("#"+related_id).val(file.id+";");
                        }
                    }
                },
                error:function(xhr, ajaxOptions, thrownError){ 
                    // alert("檔案上傳發生錯誤"); 
                }
            });
        });
    });

    function delete_file(related_id, id, alarm = true){
        if (alarm && !confirm("確定刪除此檔案?"+"\n"+"注意! 儲存後此動作才會正式生效")) return;
        $("#file_"+id).fadeTo('fast', 0, function() {
            $(this).remove(); 
        });
        $("#"+related_id+"_deleted").val($("#"+related_id+"_deleted").val()+id+",");
    }

    function delete_photo(id){
        $("#delphoto_"+id).hide();
        $("#"+id).val("");
        $("#img_"+id+" img").attr("src", "");
        $("#img_"+id).hide();
    }

    function del_multi_img(obj, pic, id){
        if (!confirm("確定刪除此照片嗎?刪除後請按下方儲存鈕，才會真正刪除。")) return;
        $(obj).parent("div").fadeOut();
        $("#picdeleted_"+id).val($("#picdeleted_"+id).val()+pic+",");
    }
    function form_action(url){
        $("#new_form").attr("action", url).submit();
    }
</script>
<? include("crop.php"); ?>
</body>

</html>
