var id_donvi_2;var id_donvi_3;var id_donvi_4;
function chucvu(){
	var dialog_themchucvu; var delete_confirm_dialog;
	$("#themchucvu").click(function(){
		dialog_themchucvu = $("#dialog_themchucvu").data('dialog');
		dialog_themchucvu.open();
	});
	$("#themchucvu_no").click(function(){
		dialog_themchucvu.close();
	});
	$("#themchucvu_ok").click(function(){
		$.ajax({
            type: "POST",
            url: "post.themchucvu.php",
            data: $("#themchucvuform").serialize(),
            success: function(datas) {
                if(datas=='Failed'){
                    $.Notify({type: 'alert', caption: 'Thông báo', content: 'Cập nhật thất bại.'});
                } else {
                    //$("#chucvu_list tbody").prepend(datas);
                    //$.Notify({type: 'alert', caption: 'Thông báo', content: datas});
                    location.reload();
                }
           }
        }).fail(function() {
            $.Notify({type: 'alert', caption: 'Thông báo', content: "Cập nhật thất bại."});
        });
		dialog_themchucvu.close();
	});
    $(".suachucvu").click(function(){
        var link; var _this;
        link = $(this).attr("href"); _this = $(this);
        $.getJSON(link, function(d){
            id_donvi_2 = d.id_donvi_2;id_donvi_3 = d.id_donvi_3;id_donvi_4 = d.id_donvi_4;
            $("#id").val(d.id);
            //$("#id_canbo").val(d.id_canbo);
            $("#id_donvi_1").select2("val", d.id_donvi_1);
            $("#id_donvi_2").html(d.str_donvi_2);
            $("#id_donvi_2").select2("val", d.id_donvi_2);
            $("#id_donvi_3").html(d.str_donvi_3);
            $("#id_donvi_3").select2("val", d.id_donvi_3);
            $("#id_donvi_4").html(d.str_donvi_4);
            $("#id_donvi_4").select2("val", d.id_donvi_4);
            $("#id_chucvu").select2("val", d.id_chucvu);
            $("#id_ham").select2("val", d.id_ham);
            $("#ngaynhap").val(d.ngaynhap);
            dialog_themchucvu = $("#dialog_themchucvu").data('dialog');
            dialog_themchucvu.open();
        }); 
    });

    $(".xoachucvu").click(function(){
        var link; var _this;
        link = $(this).attr("href"); _this = $(this);
        delete_confirm_dialog = $("#confirm_delete") .data('dialog');
        delete_confirm_dialog.open();
        $("#delete_ok").click(function(){
            $.ajax({
                type: "GET", url: link,
                success: function(data){
                    if(data == 'Failed'){
                        $.Notify({type: 'alert', caption: 'Thông báo', content: 'Không thể Xoá [Đoàn ra]'});
                        delete_confirm_dialog.close();
                    } else {
                        _this.parents("tr.items").fadeOut();
                        delete_confirm_dialog.close();
                    }
                },      
            }).fail(function() {
                $.Notify({type: 'alert', caption: 'Thông báo', content: "Không thể xoá."});
            });

        });
        $("#delete_no").click(function(){
            delete_confirm_dialog.close();
        });
    });
}

function id_donvi_action_chitiet(){
    $("#id_donvi_1").change(function(){
        $.getJSON("get.donvi.php?id1=" + $("#id_donvi_1").val() + "&level=2", function(d){
            $("#id_donvi_2").html(d.str_donvi_2);
            if(id_donvi_2) $("#id_donvi_2").select2("val", id_donvi_2);$("#id_donvi2").show();
            $("#id_donvi_2").select2({ placeholder : "Chọn đơn vị", allowClear: true });
            $("#id_donvi_3").select2({ placeholder : "Chọn đơn vị", allowClear: true });
            $("#id_donvi_4").select2({ placeholder : "Chọn đơn vị", allowClear: true });
             
        });
    });
    $("#id_donvi_2").change(function(){
        $.getJSON("get.donvi.php?id1=" + $("#id_donvi_1").val()+ "&id2="+$("#id_donvi_2").val()+"&level=3", function(d){
            $("#id_donvi_3").html(d.str_donvi_3); 
            if(id_donvi_3) $("#id_donvi_3").select2("val", id_donvi_3);$("#id_donvi3").show();
            $("#id_donvi_3").select2({ placeholder : "Chọn đơn vị", allowClear: true });
            $("#id_donvi_4").select2({ placeholder : "Chọn đơn vị", allowClear: true });
        });
    });
    $("#id_donvi_3").change(function(){
        $.getJSON("get.donvi.php?id1=" + $("#id_donvi_1").val()+ "&id2="+$("#id_donvi_2").val()+"&id3="+$("#id_donvi_3").val()+"&level=4", function(d){
            $("#id_donvi_4").html(d.str_donvi_4);
            if(id_donvi_4) $("#id_donvi_4").select2("val", id_donvi_4);$("#id_donvi4").show();
            $("#id_donvi_4").select2({ placeholder : "Chọn đơn vị", allowClear: true });
        });
    });
}

function hide_donvi(){
    $("#id_donvi2").hide();$("#id_donvi3").hide();$("#id_donvi4").hide();
}

function radioButton(name){
    var allRadios = document.getElementsByName(name);
    var booRadio;
    var x = 0;
    for(x = 0; x < allRadios.length; x++){

            allRadios[x].onclick = function(){

                if(booRadio == this){
                    this.checked = false;
            booRadio = null;
                }else{
                booRadio = this;
            }
            };

    }
}