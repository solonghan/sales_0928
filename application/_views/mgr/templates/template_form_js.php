<script>
    var city = JSON.parse('<?= json_encode($city) ?>');

    var language = {
        daysMin: ['日', '一', '二', '三', '四', '五', '六'],
        months: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
        monthsShort: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
        dateFormat: 'yyyy-mm-dd',
        timeFormat: 'hh:ii:00',
        firstDay: 0,
    };
    $(document).ready(function() {

        $(".select2").select2();
        $('.daypicker').datepicker({
            language: language,
            minDate: new Date('<?= date('Y-m-d') ?>'),
            position: "bottom left",
            autoClose: true
        });
        $('.daypicker_pre').datepicker({
            language: language,
            maxDate: new Date('<?= date('Y-m-d') ?>'),
            position: "bottom left",
            autoClose: true
        });
        $('.datetimepicker').datepicker({
            language: language,
            minDate: new Date('<?= date('Y-m-d') ?>'),
            position: "bottom left",
            autoClose: true,
            timepicker: true
        });
        $('.datetimepicker_pre').datepicker({
            language: language,
            maxDate: new Date('<?= date('Y-m-d') ?>'),
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
                new_data.push({
                    "id": city[selected_id]['dist'][i]['c3'],
                    "text": city[selected_id]['dist'][i]['c3'] + " " + city[selected_id]['dist'][i]['name']
                })
            }
            $("select[name=dist]").empty().select2({
                data: new_data
            })
        });
        $(".p_select").on('change', function(e) {
            $(".c_select option").removeAttr('selected');
            var select_value = $(this).val();
            var what_ctrl = $(this).attr('what_ctrl');
            $.ajax({
                url: "<?= base_url() ?>mgr/"+ what_ctrl +"/get_c_select_value/",
                data: {
                    p_value: select_value
                },
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    if (data.status) {
                        $(".c_select option[value="+data.value+"]").attr('selected', 'TRUE');
                        $('.c_select').siblings('span.select2').find('.select2-selection__rendered').attr('title', data.string).text(data.string);
                        $('.c_select').val('').selectpicker('refresh');
                        $('.c_select').change(data.value);
                    }
                },
            });
        });
        $(document).on('click', '.num_minus', function(event) {
            var id = $(this).parent().attr("id").split("_")[1];
            var num = parseInt($("#select_" + id).val());
            num = num - 1;
            num = (num < 0) ? 0 : num;
            $("#select_" + id).val(num);
        });

        $(document).on('click', '.num_plus', function(event) {
            var id = $(this).parent().attr("id").split("_")[1];
            var num = parseInt($("#select_" + id).val());
            var total = parseInt($(this).closest('.pattern').attr("data-max"));
            num = num + 1;
            num = (num >= total) ? total : num;
            $("#select_" + id).val(num);
        });

        $(".multiple_img_upload").on('change', function(event) {
            var related_id = $(this).attr("data-related");
            var formData = new FormData();

            $.each($(this)[0].files, function(i, file) {
                formData.append('pics[' + i + ']', file);
            });
            $.ajax({
                url: "<?= base_url() ?>mgr/dashboard/multiple_img_upload",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    if (data.status) {
                        jQuery.each(data.data, function(index, pic) {
                            $("#pics_" + related_id).append(
                                $("<div/>").addClass('col-lg-2 col-md-3 col-sm-4 col-xs-6').append(
                                    $("<a/>").attr({
                                        "href": "<?= base_url() ?>" + pic,
                                        "data-fancybox": "gallery_" + related_id
                                    }).append(
                                        $("<img/>").addClass('thumbnail').css({
                                            'width': '100%'
                                        }).attr("src", "<?= base_url() ?>" + pic)
                                    )
                                ).append(
                                    $("<button/>").attr("type", "button").addClass('btn btn-sm btn-danger del-btn').on('click', function(event) {
                                        del_multi_img($(this), pic, related_id);
                                    }).append(
                                        $('<span/>').addClass('fa fa-fw ti-trash')
                                    )
                                )
                            );
                            $("#" + related_id).val($("#" + related_id).val() + pic + ";");
                        });

                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert("照片上傳發生錯誤");
                }
            });
        });

        $(".file_upload").on('change', function(event) {
            var multiple = $(this).attr("data-multi");
            var related_id = $(this).attr("data-related");
            var formData = new FormData();

            if (multiple == "true") {
                $.each($(this)[0].files, function(i, file) {
                    formData.append('files[' + i + ']', file);
                });
            } else {
                formData.append('files', $(this)[0].files[0]);
            }

            $.ajax({
                url: "<?= base_url() ?>mgr/dashboard/file_upload",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    if (data.status) {
                        if (multiple == "true") {
                            jQuery.each(data.data, function(index, file) {
                                $("#files_" + related_id).append(
                                    $("<div/>").attr("id", "file_" + file.id).addClass('col-xs-12').css({
                                        'margin-top': '10px'
                                    }).append(
                                        $("<button/>").attr("type", "button").addClass('btn btn-sm btn-danger').on('click', function(event) {
                                            delete_file(related_id, file.id);
                                        }).append(
                                            $('<span/>').addClass('fa fa-fw ti-trash')
                                        )
                                    ).append('&nbsp;&nbsp;').append(
                                        $("<a/>").attr({
                                            "href": "<?= base_url() . "file/" ?>" + file.id
                                        }).append(
                                            file.realname
                                        )
                                    )
                                );
                                $("#" + related_id).val($("#" + related_id).val() + file.id + ";");
                            });
                        } else {
                            var file = data.data[0];
                            $("#files_" + related_id).append(
                                $("<div/>").attr("id", "file_" + file.id).addClass('col-xs-12').css({
                                    'margin-top': '10px'
                                }).append(
                                    $("<button/>").attr("type", "button").addClass('btn btn-sm btn-danger').on('click', function(event) {
                                        delete_file(related_id, file.id);
                                    }).append(
                                        $('<span/>').addClass('fa fa-fw ti-trash')
                                    )
                                ).append('&nbsp;&nbsp;').append(
                                    $("<a/>").attr({
                                        "href": "<?= base_url() . "file/" ?>" + file.id
                                    }).append(
                                        file.realname
                                    )
                                )
                            );
                            if ($("#" + related_id).val() != "") {
                                var old_id = $("#" + related_id).val().replace(";", "");
                                delete_file(related_id, old_id, false);
                            }
                            $("#" + related_id).val(file.id + ";");
                        }
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    // alert("檔案上傳發生錯誤"); 
                }
            });
        });
    });

    function delete_file(related_id, id, alarm = true) {
        if (alarm && !confirm("確定刪除此檔案?" + "\n" + "注意! 儲存後此動作才會正式生效")) return;
        $("#file_" + id).fadeTo('fast', 0, function() {
            $(this).remove();
        });
        $("#" + related_id + "_deleted").val($("#" + related_id + "_deleted").val() + id + ",");
    }

    function delete_photo(id) {
        $("#delphoto_" + id).hide();
        $("#" + id).val("");
        $("#img_" + id + " img").attr("src", "");
        $("#img_" + id).hide();
    }

    function del_multi_img(obj, pic, id) {
        if (!confirm("確定刪除此照片嗎?刪除後請按下方儲存鈕，才會真正刪除。")) return;
        $(obj).parent("div").fadeOut();
        $("#picdeleted_" + id).val($("#picdeleted_" + id).val() + pic + ",");
    }

    function form_action(url) {
        $("#new_form").attr("action", url).submit();
    }
</script>