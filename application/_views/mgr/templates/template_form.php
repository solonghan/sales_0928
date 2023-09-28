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
        .del-btn {
            position: absolute;
            top: 0;
            left: 14px;
            margin: 3%;
            height: 25px;
            width: 25px;
            line-height: 25px;
            padding: 0;
        }

        .pics.row {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            flex-wrap: wrap;
        }

        .pics.row>[class*='col-'] {
            display: flex;
            flex-direction: column;
        }

        .select2-selection {
            height: 34px;
        }
    </style>
</head>
<? include("nav+menu.php"); ?>
<aside class="right-side">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= $title ?>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?= base_url() ?>mgr/">
                    <i class="fa fa-fw ti-home"></i> 首頁
                </a>
            </li>

            <? if ($parent != ""): ?>
            <li><a href="<?= $parent_link ?>">
                    <i class="fa fa-fw ti-folder"></i> <?= $parent ?>
                </a>
            </li>
            <? endif; ?>
            <li class="active"><?= $title ?></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-xs-12">
                <div class="panel filterable">
                    <div class="panel-heading clearfix">
                        <h3 class="panel-title pull-left m-t-6">
                            <i class="ti-view-list"></i> <?= $title ?>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="<?= $action ?>" id="new_form" enctype='multipart/form-data'>
                            <?
                                    
                                    foreach ($field as $item):
                                ?>
                            <? if ($item[2] == "header"): ?>
                            <h4><b><?= $item[0] ?></b></h4>
                            <? else: ?>
                            <div class="form-group">
                                <?php if ($item[0] !== FALSE):?>
                                <label for="input-text" class="col-sm-2 control-label">
                                    <?
                                    echo $item[0]; 
                                    if ($item[4])
                                        echo "<span class='text text-danger'>*</span>";
                                    ?>
                                </label>
                                <?php endif; ?>
                                <div class="col-sm-10">
                                    <? if ($item[2] == "text"): ?>
                                    <input type="text" class="form-control" name="<?= $item[1] ?>" value="<?= $item[3] ?>" <?= (isset($can_edit) && !$can_edit) ? " disabled" : "" ?>>
                                    <? elseif ($item[2] == "number"): ?>
                                    <input type="number" class="form-control" name="<?= $item[1] ?>" value="<?= $item[3] ?>" <?= (isset($can_edit) && !$can_edit) ? " disabled" : "" ?>>
                                    <? elseif ($item[2] == "plain"): ?>
                                    <div style="line-height: 40px; border-bottom: 1px solid #EEE;" data-name="<?= $item[1] ?>"><?= $item[3] ?></div>
                                    <? elseif($item[2] == "textarea"): ?>
                                    <textarea class="ckeditor" id="<?= $item[1] ?>" name="<?= $item[1] ?>" <?= (isset($can_edit) && !$can_edit) ? " disabled" : "" ?>><?= $item[3] ?></textarea>
                                    <? elseif($item[2] == "textarea_plain"): ?>
                                    <textarea class="form-control" id="<?= $item[1] ?>" name="<?= $item[1] ?>" style="height: 200px;" <?= (isset($can_edit) && !$can_edit) ? " disabled" : "" ?>><?= $item[3] ?></textarea>
                                    <? elseif($item[2] == "checkbox"): ?>
                                    <input type="checkbox" class="form-control" name="<?= $item[1] ?>" <?= ($item[3] != "" && $item[3] == 1) ? ' checked' : '' ?><?= (isset($can_edit) && !$can_edit) ? " disabled" : "" ?>>
                                    <? elseif($item[2] == "checkbox_multi"): ?>
                                    <div style="display: inline-flex; line-height: 34px; flex-wrap: wrap;">
                                        <? foreach ($checkbox[$item[1]] as $c): ?>
                                        <label>
                                            <input style="margin-top: -5px;" type="checkbox" value="<?= $c[2] ?>" name="<?= $c[0] ?>[]" <?= ($c[3] == 1) ? ' checked' : '' ?><?= (isset($can_edit) && !$can_edit) ? " disabled" : "" ?>> <?= $c[1] ?>
                                        </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <? endforeach; ?>
                                    </div>
                                    <? elseif($item[2] == "day"): ?>
                                    <input type="text" class="form-control daypicker" name="<?= $item[1] ?>" value="<?= $item[3] ?>" autocomplete="off" <?= (isset($can_edit) && !$can_edit) ? " disabled" : "" ?>>
                                    <? elseif($item[2] == "day_pre"): ?>
                                    <input type="text" class="form-control daypicker_pre" name="<?= $item[1] ?>" value="<?= $item[3] ?>" autocomplete="off" <?= (isset($can_edit) && !$can_edit) ? " disabled" : "" ?>>
                                    <? elseif($item[2] == "datetime"): ?>
                                    <input type="text" class="form-control datetimepicker" name="<?= $item[1] ?>" value="<?= $item[3] ?>" autocomplete="off" <?= (isset($can_edit) && !$can_edit) ? " disabled" : "" ?>>
                                    <? elseif($item[2] == "datetime_pre"): ?>
                                    <input type="text" class="form-control datetimepicker_pre" name="<?= $item[1] ?>" value="<?= $item[3] ?>" autocomplete="off" <?= (isset($can_edit) && !$can_edit) ? " disabled" : "" ?>>
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
                                    <? elseif($item[2] == "p_select"): ?>
                                    <select class="form-control select2 p_select" name="<?= $item[1] ?>" <?= (isset($can_edit) && !$can_edit) ? " disabled" : "" ?> what_ctrl="<?= $item[7] ?>">
                                        <? 
                                            if (isset($item[6])) {
                                                foreach ($select[$item[1]] as $option){
                                                    echo '<option value="'.$option[$item[6][0]].'"';
                                                    if ($item[3] == $option[$item[6][0]]) echo ' selected';
                                                    echo '>'.$option[$item[6][1]].'</option>';
                                                }     
                                            }else{
                                                foreach ($select[$item[1]] as $option){
                                                    echo '<option value="'.$option['value'].'"';
                                                    if ($item[3] == $option['value']) echo ' selected';
                                                    echo '>'.$option['string'].'</option>';
                                                } 
                                            }
                                            
                                        ?>
                                    </select>
                                    <? elseif($item[2] == "c_select"): ?>
                                    <select class="form-control select2 c_select" name="<?= $item[1] ?>" <?= (isset($can_edit) && !$can_edit) ? " disabled" : "" ?>>
                                        <? 
                                            if (isset($item[6])) {
                                                foreach ($select[$item[1]] as $option){
                                                    echo '<option value="'.$option[$item[6][0]].'"';
                                                    if ($item[3] == $option[$item[6][0]]) echo ' selected';
                                                    echo '>'.$option[$item[6][1]].'</option>';
                                                }     
                                            }else{
                                                foreach ($select[$item[1]] as $option){
                                                    echo '<option value="'.$option['value'].'"';
                                                    if ($item[3] == $option['value']) echo ' selected';
                                                    echo '>'.$option['string'].'</option>';
                                                } 
                                            }
                                            
                                        ?>
                                    </select>
                                    <? elseif($item[2] == "hid_btn"): ?>
                                    <input type="hidden" name="<?= $item[1] ?>" id="<?= $item[1] ?>" value="<?= $item[3] ?>">
                                    <? elseif($item[2] == "select"): ?>
                                    <select class="form-control select2" name="<?= $item[1] ?>" <?= (isset($can_edit) && !$can_edit) ? " disabled" : "" ?>>
                                        <? 
                                            if (isset($item[6])) {
                                                foreach ($select[$item[1]] as $option){
                                                    echo '<option value="'.$option[$item[6][0]].'"';
                                                    if ($item[3] == $option[$item[6][0]]) echo ' selected';
                                                    echo '>'.$option[$item[6][1]].'</option>';
                                                }     
                                            }else{
                                                foreach ($select[$item[1]] as $option){
                                                    echo '<option value="'.$option['value'].'"';
                                                    if ($item[3] == $option['value']) echo ' selected';
                                                    echo '>'.$option['string'].'</option>';
                                                } 
                                            }
                                            
                                        ?>
                                    </select>
                                    <? elseif($item[2] == "select_multi"): ?>
                                    <select class="form-control select2" name="<?= $item[1] ?>[]" multiple="multiple" <?= (isset($can_edit) && !$can_edit) ? " disabled" : "" ?>>
                                        <? 
                                                foreach ($select[$item[1]] as $option){
                                                    echo '<option value="'.$option[$item[6][0]].'"';
                                                    if (is_array($item[3]) && in_array($option[$item[6][0]], $item[3])) echo ' selected';
                                                    echo '>'.$option[$item[6][1]].'</option>';
                                                } 
                                            ?>
                                    </select>
                                    <? elseif(substr($item[2], 0, 4) == "file"): ?>
                                    <input data-multi="<?= ($item[2] == "file_multi") ? 'true' : 'false' ?>" data-related="<?= $item[1] ?>" class="file_upload" type="file" id="fileupload_<?= $item[1] ?>" style="display: none;" <?= ($item[2] == "file_multi") ? ' multiple' : '' ?>>
                                    <? if(isset($can_edit)&&!$can_edit): ?>
                                    <? else: ?>
                                    <button type="button" class="btn btn-sm btn-info" onclick="fileupload_<?= $item[1] ?>.click();" style="background-color: #65aadd;">選擇檔案</button>
                                    <? endif; ?>
                                    <input type="hidden" name="<?= $item[1] ?>" id="<?= $item[1] ?>" value="<?= $item[3] ?>">
                                    <input type="hidden" name="<?= $item[1] ?>_deleted" id="<?= $item[1] ?>_deleted" value="">
                                    <div class="row" id="files_<?= $item[1] ?>">
                                        <? if (isset($files) && isset($files[$item[1]])): ?>
                                        <? foreach ($files[$item[1]] as $f): //Array: id, name ?>
                                        <div id="file_<?= $f['id'] ?>" class="col-xs-12" style="margin: 10px 0;">
                                            <? if(isset($can_edit)&&!$can_edit): ?>
                                            <? else: ?>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="delete_file('<?= $item[1] ?>','<?= $f['id'] ?>');"><span class="fa fa-fw ti-trash"></span></button>
                                            <? endif; ?>
                                            &nbsp;&nbsp;<a href="<?= base_url() . "file/" . $f['id'] ?>" target="_blank"><?= $f['name'] ?></a>
                                        </div>
                                        <? endforeach; ?>
                                        <? endif; ?>
                                    </div>
                                    <? elseif($item[2] == "img"): ?>
                                    <input data-multi="false" data-ratio="<?= $item[6] ?>" data-related="<?= $item[1] ?>" data-folder="<?=$item[7] ?>" class="img_upload" type="file" id="imgupload_<?= $item[1] ?>" style="display: none;" accept="image/*">
                                    <? if(isset($can_edit)&&!$can_edit): ?>
                                    <? else: ?>
                                    <button type="button" class="btn btn-sm btn-info" onclick="imgupload_<?= $item[1] ?>.click();">選擇照片</button>
                                    <button id="delphoto_<?= $item[1] ?>" type="button" class="btn btn-sm btn-danger" onclick="delete_photo('<?= $item[1] ?>');" <?= ($item[3] == "") ? ' style="display:none;"' : "" ?>>刪除照片</button>
                                    <? endif; ?>
                                    <input type="hidden" name="<?= $item[1] ?>" id="<?= $item[1] ?>" value="<?= $item[3] ?>">
                                    <div id="img_<?= $item[1] ?>" style="width: 256px; margin-top: 6px; background-color: #FFF; border:1px solid #DDD; padding: 2px; border-radius: 2px;<?= ($item[3] == "") ? ' display:none;' : "" ?>">
                                        <img src="<?= ($item[3] != "") ? ((strpos($item[3], "http") !== FALSE) ? $item[3] : base_url() . $item[3]) : "" ?>" style="width: 250px;">
                                    </div>
                                    <? elseif($item[2] == "img_multi"): ?>
                                    <input data-multi="true" data-ratio="<?= $item[6] ?>" data-related="<?= $item[1] ?>" class="img_upload" type="file" id="imgupload_<?= $item[1] ?>" style="display: none;" accept="image/*">
                                    <? if(isset($can_edit)&&!$can_edit): ?>
                                    <? else: ?>
                                    <button type="button" class="btn btn-sm btn-info" onclick="imgupload_<?= $item[1] ?>.click();">選擇照片</button>
                                    <? endif; ?>
                                    <!-- xxx.jpg;abc.jpb; -->
                                    <input type="hidden" name="<?= $item[1] ?>" id="<?= $item[1] ?>" value="<?= $item[3] ?>">
                                    <input type="hidden" name="picdeleted_<?= $item[1] ?>" id="picdeleted_<?= $item[1] ?>" value="">
                                    <div class="row pics" id="pics_<?= $item[1] ?>">
                                        <?
                                                    if ($item[3] != "") {
                                                        $pics = explode(",", $item[3]);
                                                        foreach ($pics as $c) {
                                                ?>
                                        <div class="<?= ($type == "edit") ? 'col-lg-3 col-md-4 col-sm-4 col-xs-6' : 'col-lg-2 col-md-3 col-sm-4 col-xs-6' ?>">
                                            <a data-fancybox="gallery_<?= $item[1] ?>" href="<?= base_url() . $c ?>"><img src="<?= base_url() . $c ?>" class="thumbnail" style="width:100%;"></a>
                                            <? if(isset($can_edit)&&!$can_edit): ?>
                                            <? else: ?>
                                            <button type="button" class="btn btn-sm btn-danger del-btn" onclick="del_multi_img(this, '<?= $c ?>', '<?= $item[1] ?>');">
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
                                    <input data-multi="true" data-related="<?= $item[1] ?>" class="multiple_img_upload" type="file" id="imgupload_<?= $item[1] ?>" style="display: none;" accept="image/*" multiple>

                                    <button type="button" class="btn btn-sm btn-info" onclick="imgupload_<?= $item[1] ?>.click();">選擇照片</button>

                                    <input type="hidden" name="<?= $item[1] ?>" id="<?= $item[1] ?>" value="<?= $item[3] ?>">
                                    <input type="hidden" name="picdeleted_<?= $item[1] ?>" id="picdeleted_<?= $item[1] ?>" value="">
                                    <div class="row pics" id="pics_<?= $item[1] ?>">
                                        <?
                                                    if (isset($pics) && is_array($pics)) {
                                                        foreach ($pics as $pic) {
                                                            $c = $pic['url'];
                                                ?>
                                        <div class="<?= ($type == "edit") ? 'col-lg-3 col-md-4 col-sm-4 col-xs-6' : 'col-lg-2 col-md-3 col-sm-4 col-xs-6' ?>">
                                            <a data-fancybox="gallery_<?= $item[1] ?>" href="<?= base_url() . $c ?>"><img src="<?= base_url() . $c ?>" class="thumbnail" style="width:100%;"></a>
                                            <? if(isset($can_edit)&&!$can_edit): ?>
                                            <? else: ?>
                                            <button type="button" class="btn btn-sm btn-danger del-btn" onclick="del_multi_img(this, '<?= $c ?>', '<?= $item[1] ?>');">
                                                <span class="fa fa-fw ti-trash"></span>
                                            </button>
                                            <? endif; ?>
                                        </div>
                                        <?
                                                        }
                                                    }
                                                ?>
                                    </div>
                                    <!-- for Wedding project(in our bride) - Start -->
                                    <? elseif($item[2] == "btn_active"): ?>
                                    <select class="form-control select2 btn_active_select" name="<?= $item[1] ?>" <?= (isset($can_edit) && !$can_edit) ? " disabled" : "" ?> what_ctrl="<?= $item[7] ?>">
                                        <?php
                                        if (isset($item[6]))
                                        {
                                            foreach ($select[$item[1]] as $option)
                                            {
                                                echo '<option value="'.$option[$item[6][0]].'">'.$option[$item[6][1]].'</option>';
                                            }
                                        }
                                        else
                                        {
                                            foreach ($select[$item[1]] as $option)
                                            {
                                                echo '<option value="'.$option['value'].'">'.$option['string'].'</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                    <button type="button" class="btn btn-sm btn-info btn_active" >請求新增</button>
                                    <hr>
                                    <!-- for Wedding project(in our bride) - End -->
                                    <? endif; ?>
                                    <? if ($item[5] != ""): ?>
                                    <small class="text text-danger"><?= $item[5] ?></small>
                                    <? endif; ?>
                                </div>
                            </div>
                            <? endif; ?>
                            <? endforeach; ?>
                            <!-- for dynamic insert btn -->
                            <div class="dy_btn">
                                <?php echo (isset($btn_html) ? $btn_html : '')?>
                            </div>
                            <? if ($submit_txt != ""): ?>
                            <hr>
                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-2">
                                    <button type="button" class="btn btn-md btn-primary submit_btn"><?= $submit_txt ?></button>
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
<?php include("template_form_js.php"); ?>

<!-- For Wedding project -->
<script>
    var dynamic_row = <?php echo (isset($max_row) ? $max_row : 0)?>;
    var max_row = <?php echo (isset($max_row) ? $max_row : 0)?>;
    $(document).ready(function() {
        $('.btn_active').on('click', function(e) {
            var content_type = $('.btn_active_select').val();
            dynamic_row += 1;
            var what_ctrl = $('.btn_active_select').attr('what_ctrl');
            var row_num = $('#row_num').val();
            $.ajax({
                url: "<?= base_url() ?>mgr/"+ what_ctrl +"/get_btn_active/",
                data: {
                    content_type: content_type,
                    dynamic_row: dynamic_row
                },
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    $('.dy_btn').append(data.btn_html);
                    $('#row_num').val(parseInt(row_num, 10) + 1);
                    max_row += 1;
                    $('#row_types').val($('#row_types').val() + ',' + content_type);
                },
            });
        });
    });

    // for our bride
    function del_grp(id)
    {
        $('#' + id).remove();
        $('#row_num').val(parseInt($('#row_num').val(), 10) - 1);
        console.log(id.split('_')[2]);
        $('#row_del').val($('#row_del').val() + ',' + id.split('_')[2]);
    }
</script>

<? include("crop.php"); ?>
</body>

</html>