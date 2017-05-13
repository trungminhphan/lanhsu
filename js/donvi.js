function themdonvi(){
	$(".themdonvi").click(function(){
		var dialog = $("#dialog_themdonvi").data('dialog');
		dialog.open();
		$.get($(this).attr("href"), function(data){
			$("#content_dialog_themdonvi").html(data);
			$("#update_themdonvi").click(function(){
				if($("#tendonvi").val()==''){
					alert('Hãy nhập tên Đơn vị');
				} else {
					$.ajax({
			        	type: "POST",
			           	url: "post.suadonvi.php",
			           	data: $("#themdonviform").serialize(),
			           	success: function(data) {
			           		var d = $.parseJSON(data);
			           		window.location = "?id_root=" + d.id_root + "&update=" + d.update + "&id2=" + d.id2 + "&id3="+d.id3;
			           	}
			        }).fail(function() {
						$.Notify({type: 'alert', caption: 'Thông báo', content: "Cập nhật thất bại"});
				    });
					dialog.close(); return false;
				}
			});
		});
	});
}

function suadonvi(){
	$(".suadonvi").click(function(){
		var dialog = $("#dialog_suadonvi").data('dialog');
		dialog.open();
		$.get($(this).attr("href"), function(data){
			$("#content_dialog").html(data); //$(".donvi").select2();
			$("#update_donvi").click(function(){
				if($("#tendonvi").val() == ''){
					alert('Nhập tên đơn vị.');
				} else {
					$.ajax({
			        	type: "POST",
			           	url: "post.suadonvi.php",
			           	data: $("#suadonviform").serialize(),
			           	success: function(data) {
			           		var d = $.parseJSON(data);
			           		//$.Notify({type: 'alert', caption: 'Thông báo', content: d.text });
			           		window.location = "?id_root=" + d.id_root + "&update=" + d.update + "&id2=" + d.id2 + "&id3="+d.id3+"&id4="+d.id4;
			           	}
			        }).fail(function() {
						$.Notify({type: 'alert', caption: 'Thông báo', content: "Cập nhật thất bại"});
				    });
					dialog.close();
					return false;
				}
			});
			$("#delete_donvi").click(function(){
				var delete_dialog = $("#dialog_delete").data('dialog');
				delete_dialog.open();
				$("#delete_no").click(function(){
					delete_dialog.close();
				});
				$("#delete_ok").click(function(){
					delete_dialog.close(); dialog.close();
					$.ajax({
						type: "POST",
						url: "post.suadonvi.php",
						data: $("#xoadonviform").serialize(),
						success: function(data){
							var d = $.parseJSON(data);
							if(d.update == 'no'){
								$.Notify({type: 'alert', caption: 'Thông báo', content: 'Không thể xoá.' });
							} else {
								window.location = "?id_root=" + d.id_root + "&update=" + d.update + "&id2=" + d.id2 + "&id3="+d.id3+"&id4="+d.id4;	
							}
							//$.Notify({type: 'alert', caption: 'Thông báo', content: data });
							//window.location.href += "?mypara";
		           			//location.reload();
						}
					}).fail(function(){
						$.Notify({type: 'alert', caption: 'Thông báo', content: "Xoá thất bại"});
					});
				});
			});
		});
	});
}

function timdonvi(){
    $("#timdonvi").select2({
      ajax: {
        selectOnClose: true,
        url: "getJSON.donvi.php",
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
        if (repo.loading) return repo.tendonvi;
        var markup = '';
        markup += '<p>';
        markup += repo.tendonvi;
        markup += '</p>';
        return markup;
    }

    function formatRepoSelection (repo) {
        return repo.text;
    }
}