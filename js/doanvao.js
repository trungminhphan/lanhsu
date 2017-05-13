function nhaplieu(){
    $(".select2").select2();
    $("#id_donvi_xinphep_1").select2({ placeholder: "Chọn đơn vị", allowClear: true });
    $("#id_donvi_xinphep_2").select2({ placeholder: "Chọn đơn vị", allowClear: true });
    $("#id_donvi_xinphep_3").select2({ placeholder: "Chọn đơn vị", allowClear: true });
    $("#id_donvi_xinphep_4").select2({ placeholder: "Chọn đơn vị", allowClear: true });
    $("select").on("select2:select", function (evt) {
        var element = evt.params.data.element; var $element = $(element);
        $element.detach(); $(this).append($element); $(this).trigger("change");
    });
    $(".ngaythangnam").inputmask();
}
/*function upload_congvanxinphep(){
    $(".dinhkem_filecongvanxinphep").change(function() {
      var formData = new FormData($("#themdoanvaoform")[0]);
       $.ajax({
        url: "post.upload.congvanxinphep.php", type: "POST",
        data: formData, async: false,
        success: function(datas) {
            if(datas=='Failed'){
                $.Notify({type: 'alert', caption: 'Thông báo', content: "Không thể Thêm học tập"});
            } else {
                $(".info").remove();
                $("#files_congvanxinphep").prepend(datas); delete_file();
            }
        },
        cache: false, contentType: false, processData: false
        }).fail(function() {
            $.Notify({type: 'alert', caption: 'Thông báo', content: "Không thể Upload tập tin"});
        });
    });
}*/
function upload_congvanxinphep(){
    $(".dinhkem_filecongvanxinphep").change(function() {
      var formData = new FormData($("#themdoanvaoform")[0]);
       $.ajax({
        url: "post.upload.congvanxinphep.php", type: "POST",
        data: formData, async: false,
        success: function(datas) {
            if(datas=='Failed'){
                $.Notify({type: 'alert', caption: 'Thông báo', content: "Không thể Thêm học tập"});
            } else {
                $(".info").remove();
                $("#files_congvanxinphep").prepend(datas); delete_file();
            }
        },
        cache: false, contentType: false, processData: false
        }).fail(function() {
            $.Notify({type: 'alert', caption: 'Thông báo', content: "Không thể Upload tập tin"});
        });
    });
}
function upload_quyetdinhchophep(){
    $(".dinhkem_quyetdinhchophep").change(function() {
      var formData = new FormData($("#themdoanvaoform")[0]);
       $.ajax({
        url: "post.upload.quyetdinhchophep.php", type: "POST",
        data: formData, async: false,
        success: function(datas) {
            if(datas=='Failed'){
                $.Notify({type: 'alert', caption: 'Thông báo', content: "Không thể Upload tập tin"});
            } else {
                $(".info").remove();
                $("#files_quyetdinhchophep").prepend(datas); delete_file();
            }
        },
        cache: false, contentType: false, processData: false
        }).fail(function() {
            $.Notify({type: 'alert', caption: 'Thông báo', content: "Không thể Upload tập tin"});
        });
    });
}
function upload_quyetdinhchophep_2(){
    $(".dinhkem_quyetdinhchophep_2").change(function() {
      var formData = new FormData($("#themdoanvaoform")[0]);
       $.ajax({
        url: "post.upload.quyetdinhchophep_2.php", type: "POST",
        data: formData, async: false,
        success: function(datas) {
            if(datas=='Failed'){
                $.Notify({type: 'alert', caption: 'Thông báo', content: "Không thể Upload tập tin"});
            } else {
                $(".info").remove();
                $("#files_quyetdinhchophep_2").prepend(datas); delete_file();
            }
        },
        cache: false, contentType: false, processData: false
        }).fail(function() {
            $.Notify({type: 'alert', caption: 'Thông báo', content: "Không thể Upload tập tin"});
        });
    });
}
function hide_donvi_xinphep(){
    $("#id_donvi_xinphep2").hide();
    $("#id_donvi_xinphep3").hide();
    $("#id_donvi_xinphep4").hide();
}
function delete_file(){
    var link_delete; var _this;
    $(".delete_file").click(function(){
        link_delete = $(this).attr("href"); _this = $(this);
        $.ajax({
            url: link_delete,
            type: "GET",
            success: function(datas) {
                $.Notify({type: 'alert', caption: 'Thông báo', content: datas});
                _this.parents("div.row").fadeOut("slow", function(){
                    $(this).remove();
                });
            }
        }).fail(function() {
            $.Notify({type: 'alert', caption: 'Thông báo', content: "Không thể xoá."});
        });
    });
}
function id_donvi_xinphep_action_chitiet(){
    $("#id_donvi_xinphep_1").change(function(){
        $.getJSON("get.donvi.php?id1=" + $("#id_donvi_xinphep_1").val() + "&level=2", function(d){
            $("#id_donvi_xinphep_2").html(d.str_donvi_2);
            $("#id_donvi_xinphep2").show();
            $("#id_donvi_xinphep_3").html(d.str_donvi_3);
            $("#id_donvi_xinphep_4").html(d.str_donvi_4);
            $("#id_donvi_xinphep_2").select2({ placeholder : "Chọn đơn vị", allowClear: true });
            $("#id_donvi_xinphep_3").select2({ placeholder : "Chọn đơn vị", allowClear: true });
            $("#id_donvi_xinphep_4").select2({ placeholder : "Chọn đơn vị", allowClear: true });
             
        });
    });
    $("#id_donvi_xinphep_2").change(function(){
        $.getJSON("get.donvi.php?id1=" + $("#id_donvi_xinphep_1").val()+ "&id2="+$("#id_donvi_xinphep_2").val()+"&level=3", function(d){
            $("#id_donvi_xinphep_3").html(d.str_donvi_3); 
            $("#id_donvi_xinphep3").show();
            $("#id_donvi_xinphep_4").html(d.str_donvi_4); 
            $("#id_donvi_xinphep_3").select2({ placeholder : "Chọn đơn vị", allowClear: true });
            $("#id_donvi_xinphep_4").select2({ placeholder : "Chọn đơn vị", allowClear: true });
        });
    });
    $("#id_donvi_xinphep_3").change(function(){
        $.getJSON("get.donvi.php?id1=" + $("#id_donvi_xinphep_1").val()+ "&id2="+$("#id_donvi_xinphep_2").val()+"&id3="+$("#id_donvi_xinphep_3").val()+"&level=4", function(d){
            $("#id_donvi_xinphep_4").html(d.str_donvi_4);
            $("#id_donvi_xinphep4").show();
            $("#id_donvi_xinphep_4").select2({ placeholder : "Chọn đơn vị", allowClear: true });
        });
    });
}

function thanhviendoan(){
    $(".thanhviendoan").select2({
      ajax: {
        selectOnClose: true,
        url: "getJSON.canbo.php",
        dataType: 'json',
        data: function (params) {
          return {
            q: params.term, // search term
            page: params.page
          };
        },
        processResults: function (data, params) {
          // parse the results into the format expected by Select2
          // since we are using custom formatting functions we do not need to
          // alter the remote JSON data, except to indicate that infinite
          // scrolling can be used
          params.page = params.page || 1;
          return {
            results: data.items,
            pagination: {
              more: (params.page * 30) < data.total_count
            }
          };
        },
        cache: true
      },
      escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
      minimumInputLength: 1,
      templateResult: formatRepo, // omitted for brevity, see the source of this page
      templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
    });
    function formatRepo (repo) {
        if (repo.loading) return repo.hoten;
        var markup = '';
        markup += '<p>';
        markup += 'ID: '+ repo.code + '  -  Họ tên: <b>' + repo.hoten + '</b><br />';
        markup += 'Đơn vị: '+ repo.donvi + '<br />';
        markup += 'Chức vụ: '+ repo.chucvu + '<br />';
        markup += '</p>';
        return markup;
    }

    function formatRepoSelection (repo) {
        return repo.text;
    }
}
/*function upload_congvanduocphep(){
    $(".dinhkem_filecongvanduocphep").change(function() {
      var formData = new FormData($("#themdoanvaoform")[0]);
       $.ajax({
        url: "post.upload.congvanduocphep.php", type: "POST",
        data: formData, async: false,
        success: function(datas) {
            if(datas=='Failed'){
                $.Notify({type: 'alert', caption: 'Thông báo', content: "Không thể Thêm học tập"});
            } else {
                $(".info").remove();
                $("#files_congvanduocphep").prepend(datas); delete_file();
            }
        },
        cache: false, contentType: false, processData: false
        }).fail(function() {
            $.Notify({type: 'alert', caption: 'Thông báo', content: "Không thể Upload tập tin"});
        });
    });
}

function upload_quyetdinhchophep(){
    $(".dinhkem_quyetdinhchophep").change(function() {
      var formData = new FormData($("#themdoanvaoform")[0]);
       $.ajax({
        url: "post.upload.quyetdinhchophep.php", type: "POST",
        data: formData, async: false,
        success: function(datas) {
            if(datas=='Failed'){
                $.Notify({type: 'alert', caption: 'Thông báo', content: "Không thể Thêm học tập"});
            } else {
                $(".info").remove();
                $("#files_quyetdinhchophep").prepend(datas); delete_file();
            }
        },
        cache: false, contentType: false, processData: false
        }).fail(function() {
            $.Notify({type: 'alert', caption: 'Thông báo', content: "Không thể Upload tập tin"});
        });
    });
}*/

