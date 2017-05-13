function hotencanhan(){
    $("#id_canbo").select2({
      ajax: {
        selectOnClose: true,
        url: "getJSON.thongkecanbo.php",
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
        //markup += 'Đơn vị: '+ repo.donvi + '<br />';
       // markup += 'Chức vụ: '+ repo.chucvu + '<br />';
        markup += '</p>';
        return markup;
    }

    function formatRepoSelection (repo) {
        return repo.text;
    }
}

function timdonvi(){
    $("#id_donvi").select2({
      placeholder: "Tìm đơn vị thống kê",
      allowClear: true,
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