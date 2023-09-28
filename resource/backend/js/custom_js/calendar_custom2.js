"use strict";
$(document).ready(function() {

    $(".datetime").datetimepicker({
        format: 'YYYY-MM-DD',
        // timepicker: false
    });

    $(".select2").select2({
        theme: "bootstrap",
        buttonWidth: '260px',
        placeholder: '查無資料'
    });
    // $('.timepicker').datetimepicker({
    //     datepicker: false,
    //     lang: 'en',
    //     step: 30,
    //     format: 'H:i'
    // });

    $(".colorpicker").colorpicker({
        format: 'rgba'
    });
    $("input").iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
    });

    function adddays(noofdays) {
        return (noofdays * 24 * 60 * 60 * 1000);
    }

    var eventarr = [];
    var today = moment().format("YYYY-MM-DD");
    (function ($) {
        "use strict";
        
        var options = {
            events_source: './mgr/season/all_seasons/'+s,
            view: 'month',
            tmpl_path: 'vendors/bootstrap-calendar/tmpls/',
            tmpl_cache: false,
            language: 'zh-TW',
            day: today,
            onAfterEventsLoad: function (events) {
                if (!events) {
                    return;
                }
                eventarr = events;
            },
            onAfterViewLoad: function (view) {
                $('.page-header h3').text(this.getTitle());
                $('.btn-group button').removeClass('active');
                $('button[data-calendar-view="' + view + '"]').addClass('active');
            },
            classes: {
                months: {
                    general: 'label'
                }
            }
        };

        var calendar = $('#calendar').calendar(options);

        $("#active").find(".badge1").text(eventarr.length);
        $("#add_new_event").on("click", function () {
            var values = $(this).closest(".modal-content");
            if (values.find("#new_event_name").val() == "") {
                alert("餐期名稱不可為空");
            } else {
                var cycle_enable = "0";
                if($('#cycle_enable').is(':checked')) cycle_enable = "1";

                var cycle_type = "d";
                if($('#cycle_type_1').is(':checked')) cycle_type = "d";
                if($('#cycle_type_2').is(':checked')) cycle_type = "w";
                // if($('#cycle_type_3').is(':checked')) cycle_type = "m";

                var cycle_without_holiday = "n";
                if($('#cycle_without_holiday_1').is(':checked')) cycle_without_holiday = "y";
                if($('#cycle_without_holiday_2').is(':checked')) cycle_without_holiday = "n";

                var color = "#DCDCDC";
                for (var i = 1; i <= 6; i++) {
                    if($('#new_event_color_'+i).is(':checked')){
                        if (i==6) {
                            if ($("#custom_color").val() == "") {
                                alert("自定顏色尚未選擇");
                                return;
                            }
                            color = $("#custom_color").val();
                        }else{
                            color = $('#new_event_color_'+i).val();
                        }
                    }
                }

                if (cycle_enable == "1") {
                    if(new Date($("#cycle_end_date").val()).getTime() < new Date($("#new_event_start_day").val()).getTime()){
                        alert("結束日期不可早於開始日期");
                        return;
                    }
                }else{
                    cycle_type = "n";
                }

                var startH = $("#new_event_start_time_1").val();
                var startM = $("#new_event_start_time_2").val();
                var endH = $("#new_event_end_time_1").val();
                var endM = $("#new_event_end_time_2").val();

                if (startH==-1 || startM==-1 || endH==-1 || endM==-1) {
                    alert("請選擇時間");
                    return;
                }

                if (endH < startH || (startH == endH && startM < endM)) {
                    alert("開始時間不可晚於結束時間");
                    return;   
                }

                var starttime = (startM==0)?startH+":0"+startM:startH+":"+startM;
                var endtime = (endM==0)?endH+":0"+endM:endH+":"+endM;
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: BASE_URL+"mgr/season/add_event/"+s,
                    data: {
                        title: $("#new_event_name").val(),
                        total_seat: $("#new_event_total_seat").val(),
                        open_seat: $("#new_event_open_seat").val(),
                        start_day: $("#new_event_start_day").val(),
                        start_time: starttime,
                        end_time: endtime,
                        color: color,//$("#new_event_color").val(),
                        cycle_enable: cycle_enable,
                        cycle_type: cycle_type,
                        cycle_without_holiday: cycle_without_holiday,
                        end_day: $("#cycle_end_date").val()
                    },
                    beforeSend: function() {
                        loading_start();
                    },
                    success: function (data){
                        if (!data.hasOwnProperty("id")) {
                            alert(data);
                            return;
                        }
                        var id = data['id'];
                        var title = data['title'];
                        var url = data['url'];
                        var color = data['color'];

                        for (var i = 0; i < data['cycle'].length; i++) {
                            var newevent = {
                                id: id,
                                title: title+"("+$("#new_event_total_seat").val()+"/0)",
                                short: title,
                                url: url,
                                seat: "("+$("#new_event_total_seat").val()+"/0)",
                                color: color,
                                start: new Date(data['cycle'][i]).getTime(),
                                end: new Date(data['cycle'][i]).getTime()
                            };
                            eventarr.push(newevent);
                        };

                        $(document.createElement('li'))
                        .html('<a href="'+BASE_URL+'mgr/season/'+id+'" style="color:'+color+'">'+title+'<span class="pull-right"><i class="fa ti-tag showbtns" aria-hidden="true"></i></span></a>')
                        .appendTo($("#eventlist"));
                    },
                    error: function (e){
                        alert("建立餐期發生錯誤，請聯繫管理員");
                    },
                    complete: function() {
                        loading_stop();
                        $("#addModal").modal("hide");
                        
                        calendar.setOptions({events_source: eventarr});
                        // $("#active").find(".badge1").text(eventarr.length);
                        calendar.view();
                    }
                });
            }
        });
       
        
        $("#new_event_total_seat").on("blur", function () {
            if(!isNaN($("#new_event_total_seat").val())){
                if ($("#new_event_total_seat").val()!="" && $("#new_event_open_seat").val()!="" && (parseInt($("#new_event_total_seat").val()) < parseInt($("#new_event_open_seat").val()) )) {
                    $("#seat_error").html("『座位總數』需大於等於『開放座位數』");
                    $("#new_event_open_seat").parent("div").addClass("has-error");
                }else{
                    $("#seat_error").html("");
                    $("#new_event_open_seat").parent("div").removeClass("has-error");
                }
            }else{
                alert("請輸入數字");
                $("#new_event_total_seat").val("");
                $("#seat_error").html("");
            }
        });

        $("#new_event_open_seat").on("blur", function () {
            if(!isNaN($("#new_event_open_seat").val())){
                if ($("#new_event_total_seat").val()!="" && $("#new_event_open_seat").val()!="" && (parseInt($("#new_event_total_seat").val()) < parseInt($("#new_event_open_seat").val()) )) {
                    $("#seat_error").html("『座位總數』需大於等於『開放座位數』");
                    $("#new_event_open_seat").parent("div").addClass("has-error");
                }else{
                    $("#seat_error").html("");
                    $("#new_event_open_seat").parent("div").removeClass("has-error");
                }
            }else{
                alert("請輸入數字");
                $("#new_event_open_seat").val("");
                $("#seat_error").html("");
            }
        });


        $("#new_event_start_time").on("blur", function () {
            if ($("#new_event_start_time").val()!="" && $("#new_event_end_time").val()!="") {
                var s = $("#new_event_start_time").val().split(":");
                var n = $("#new_event_end_time").val().split(":");

                s = parseInt(s[0])*60 + parseInt(s[1]);
                n = parseInt(n[0])*60 + parseInt(n[1]);

                if (s <= n) {
                    $("#time_error").html("結束時間需要大於開始時間");
                    $("#new_event_start_time").parent("div").addClass("has-error");
                    $("#new_event_end_time").parent("div").addClass("has-error");
                }else{
                    $("#time_error").html("");
                    $("#new_event_start_time").parent("div").removeClass("has-error");
                    $("#new_event_end_time").parent("div").removeClass("has-error");
                }
            }
        });

        $("#new_event_end_time").on("blur", function () {
            if ($("#new_event_start_time").val()!="" && $("#new_event_end_time").val()!="") {
                var s = $("#new_event_start_time").val().split(":");
                var e = $("#new_event_end_time").val().split(":");

                s = parseInt(s[0])*60 + parseInt(s[1]);
                e = parseInt(e[0])*60 + parseInt(e[1]);

                if (e <= s) {
                    $("#time_error").html("結束時間需要大於開始時間");
                    $("#new_event_start_time").parent("div").addClass("has-error");
                    $("#new_event_end_time").parent("div").addClass("has-error");
                }else{
                    $("#time_error").html("");
                    $("#new_event_start_time").parent("div").removeClass("has-error");
                    $("#new_event_end_time").parent("div").removeClass("has-error");
                }
            }
        });
        
        $("body").on("hide.bs.modal", function () {
            $("#addForm").find("[type='reset']").click();
            
            $('#cycle_area').addClass("hidden");
            $(".icheckbox_square-blue").removeClass("checked");
        });

        $('#cycle_enable').on("ifChanged", function () {
            var val = $(this).is(':checked') ? true : false;
            if (val) {
                $('#cycle_area').removeClass("hidden");
            }else{
                $('#cycle_area').addClass("hidden");
            }
        });

        $('#new_event_color_6').on("ifChanged", function () {
            var val = $(this).is(':checked') ? true : false;
            if (val) {
                $('#custom_color').show();
            }else{
                $('#custom_color').hide();
            }
        });

        $('.btn-group button[data-calendar-nav]').each(function () {
            var $this = $(this);
            $this.on('click', function () {
                calendar.navigate($this.data('calendar-nav'));
            });
        });

        $('.btn-group button[data-calendar-view]').each(function () {
            var $this = $(this);
            $this.on('click', function () {
                calendar.view($this.data('calendar-view'));
            });
        });

        $('#first_day').on('change', function () {
            var value = $(this).val();
            value = value.length ? parseInt(value) : null;
            calendar.setOptions({first_day: value});
            calendar.view();
        });

    }(jQuery));

    $('#eventlist').slimScroll({
        color: '#A9B6BC',
        height: '540px',
        size: '5px'
    });
});