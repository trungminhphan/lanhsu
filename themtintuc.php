<?php require_once('header.php');
$tintuc = new TinTuc(); $id_user = $users->get_userid();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$act = isset($_GET['act']) ? $_GET['act'] : '';
if($id && $act == 'del'){
	$tintuc->id = $id; $tt = $tintuc->get_one();
    if($tt['hinhanh']){
        foreach ($tt['hinhanh'] as $h) {
            if(file_exists($target_files_public.$h['aliasname'])){
                @unlink($target_files_public.$h['aliasname']);
            }
        }
    }
    if($tintuc->delete()) transfers_to('tintuc.php');
    else $msg = 'Không thể xóa';
}
if(isset($_POST['submit'])){
	$id = isset($_POST['id']) ? $_POST['id'] : '';
	$act = isset($_POST['act']) ? $_POST['act'] : '';
	$tieude = isset($_POST['tieude']) ? $_POST['tieude'] : '';
	$mota = isset($_POST['mota']) ? $_POST['mota'] : '';
	$noidung = isset($_POST['noidung']) ? $_POST['noidung'] : '';
	$arr_hinhanh = array();
    $hinhanh_aliasname = isset($_POST['hinhanh_aliasname']) ? $_POST['hinhanh_aliasname']  : '';
    $hinhanh_filename = isset($_POST['hinhanh_filename']) ? $_POST['hinhanh_filename']  : '';
    if($hinhanh_aliasname){
        foreach ($hinhanh_aliasname as $key => $value) {
            array_push($arr_hinhanh, array('aliasname' => $value, 'filename' => $hinhanh_filename[$key]));
        }
    }

    $tintuc->tieude = $tieude;
    $tintuc->mota = $mota;
    $tintuc->noidung = $noidung;
    $tintuc->hinhanh = $arr_hinhanh;
    $tintuc->date_post = new MongoDate();
    $tintuc->id_user = $id_user;

	if($id && $act == 'edit'){
		$tintuc->id = $id;
		if($tintuc->edit()) transfers_to('tintuc.php');
	} else {
	    if($tintuc->insert()){
	    	transfers_to('tintuc.php');
	    }
	}
}

if($id && $act == 'edit'){
	$tintuc->id = $id;$tt = $tintuc->get_one();
	$tieude = $tt['tieude'];
	$mota = $tt['mota'];
	$noidung = $tt['noidung'];
	$hinhanh = $tt['hinhanh'];
}
?>
<script type="text/javascript" src="js/themtintuc.js"></script>
<script src="inc/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        upload_hinhanh();delete_file();CKEDITOR.replace('noidung', {
    toolbar: [
    { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
    { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
    { name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },'/',
    { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
    { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
    { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
    { name: 'others', items: [ '-' ] },
    ]
});
    });
</script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Thêm tin lãnh sự</h1>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" accept-charset="utf-8" id="themtintuc" data-role="validator" data-show-required-state="false" enctype="multipart/form-data">
<input type="hidden" name="id" id="id" value="<?php echo isset($id) ? $id : ''; ?>">
<input type="hidden" name="act" id="act" value="<?php echo isset($act) ? $act : ''; ?>">
<div class="grid">
    <div class="row cells12">
    	<div class="cell colspan2 padding-top-10">Tiêu đề</div>
        <div class="cell colspan10 input-control text">
            <input type="text" name="tieude" id="tieude" placeholder="Tiêu đề" value="<?php echo isset($tieude) ? $tieude : ''; ?>" data-validate-func="required"/>
            <span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
        </div>
    </div>
    <div class="row cells12">
        <div class="cell colspan2 padding-top-10">Mô tả</div>
        <div class="cell colspan10 input-control textarea">
            <textarea name="mota" id="mota"><?php echo isset($mota) ? $mota: ''; ?></textarea>
        </div>
    </div>
    <div class="row cells12">
        <div class="cell colspan2 padding-top-10">Nội dung</div>
        <div class="cell colspan10 input-control textarea">
            <textarea name="noidung" id="noidung" class="summernote"><?php echo isset($noidung) ? $noidung: ''; ?></textarea>
        </div>
    </div>
    <div class="row cells12">
        <div class="cell colspan2 padding-top-10">Hình ảnh</div>
        <div class="cell colspan6">
            <div class="input-control file" data-role="input">
                <input type="file" name="dinhkem[]" class="dinhkem" multiple accept="image/*" placeholder="Chọn hình ảnh" />
                <button class="button"><span class="mif-folder"></span></button>
            </div>
        </div>
    </div>
    <div id="list_hinhanh">
        <?php
        if(isset($hinhanh) && $hinhanh){
            foreach ($hinhanh as $h) {
                echo '<div class="row cells12">
                        <div class="cell colspan2"></div>
                        <div class="cell colspan10 input-control text">
                            <input type="hidden" name="hinhanh_aliasname[]" value="'.$h['aliasname'].'" readonly/>
                            <input type="text" name="hinhanh_filename[]" value="'.$h['filename'].'"/>
                            <div class="button-group">
                                <a href="get.xoahinhanh.php?filename='.$h['aliasname'].'" onclick="return false;" class="delete_file button"><span class="mif-bin"></span></a>
                            </div>
                        </div>
                    </div>';
            }
        }
        ?>
    </div>
    <div class="row cells12">
        <div class="cell colspan12 align-center">
            <a href="tintuc.php" class="button"><span class="mif-keyboard-return"></span> Trở về</a>
            <button name="submit" id="submit" class="button primary"><span class="mif-checkmark"></span> Cập nhật</button>
        </div>
    </div>
</div>
</form>
<?php require_once('footer.php'); ?>