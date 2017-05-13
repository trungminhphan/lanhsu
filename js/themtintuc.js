function upload_hinhanh(){
	$(".dinhkem").change(function() {
        var formData = new FormData($("#themtintuc")[0]);
        $.ajax({
            url: "post.upload.hinhanh.php", type: "POST",
            data: formData, async: false,
            success: function(datas) {
                if(datas=='Failed'){
                    alert('Lỗi không thể Upload tập tin.');
                } else {
                    $("#list_hinhanh").prepend(datas);
                    delete_file();
                }
            },
            cache: false, contentType: false, processData: false
        }).fail(function() {
            alert('Lỗi không thể Upload tập tin.');
        });
    });
}
function delete_file(){
	var link_delete; var _this;
	$(".delete_file").click(function(){
		link_delete = $(this).attr("href");	_this = $(this);
		$.ajax({
            url: link_delete,
            type: "GET",
            success: function(datas) {
            	_this.parents("div.row").fadeOut("slow", function(){
            		$(this).remove();
            	});
          	}
	    }).fail(function() {
	        alert('Không thể xoá.');
	    });
	});
}