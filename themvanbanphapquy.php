<?php
require_once('header.php');
$vanbanphapquy = new VanBanPhapQuy();
$linhvuc = new LinhVuc();$donvi = new DonVi();
$linhvuc_list = $linhvuc->get_all_list();
$donvi_list = $donvi->get_all_list();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$act = isset($_GET['act']) ? $_GET['act'] : '';
if($id && $act == 'del'){
	$vanbanphapquy->id = $id; $tt = $vanbanphapquy->get_one();
    if($tt['dinhkem']){
        foreach ($tt['dinhkem'] as $h) {
            if(file_exists($target_files_public.$h['aliasname'])){
                @unlink($target_files_public.$h['aliasname']);
            }
        }
    }
    if($vanbanphapquy->delete()) transfers_to('vanbanphapquy.php');
    else $msg = 'Không thể xóa';
}
$id_linhvuc = '';$id_coquanbanhanh='';$loaivanban='';
if(isset($_POST['submit'])){
	$id = isset($_POST['id']) ? $_POST['id'] : '';
	$act = isset($_POST['act']) ? $_POST['act'] : '';
	$id_linhvuc = isset($_POST['id_linhvuc']) ? $_POST['id_linhvuc'] : '';
	$loaivanban = isset($_POST['loaivanban']) ? $_POST['loaivanban'] : '';
	$id_coquanbanhanh = isset($_POST['id_coquanbanhanh']) ? $_POST['id_coquanbanhanh'] : '';
	$sovanban = isset($_POST['sovanban']) ? $_POST['sovanban'] : '';
	$trichyeu = isset($_POST['trichyeu']) ? $_POST['trichyeu'] : '';
	$ngaybanhanh = isset($_POST['ngaybanhanh']) ? $_POST['ngaybanhanh'] : '';

	$arr_dinhkem = array();
    $hinhanh_aliasname = isset($_POST['hinhanh_aliasname']) ? $_POST['hinhanh_aliasname']  : '';
    $hinhanh_filename = isset($_POST['hinhanh_filename']) ? $_POST['hinhanh_filename']  : '';
    if($hinhanh_aliasname){
        foreach ($hinhanh_aliasname as $key => $value) {
            array_push($arr_dinhkem, array('aliasname' => $value, 'filename' => $hinhanh_filename[$key]));
        }
    }
    $id_user = $users->get_userid();
    $date_post = new MongoDate();

    $vanbanphapquy->id_linhvuc =  $id_linhvuc;
    $vanbanphapquy->loaivanban = $loaivanban;
    $vanbanphapquy->id_coquanbanhanh = $id_coquanbanhanh;
    $vanbanphapquy->sovanban = $sovanban;
    $vanbanphapquy->trichyeu = $trichyeu;
    $vanbanphapquy->ngaybanhanh = $ngaybanhanh ? new MongoDate(convert_date_dd_mm_yyyy($ngaybanhanh)) : '';
    $vanbanphapquy->dinhkem = $arr_dinhkem;
    $vanbanphapquy->id_user = $id_user;
    $vanbanphapquy->date_post = $date_post;
    if($id && $act == 'edit'){
    	$vanbanphapquy->id = $id;
    	if($vanbanphapquy->edit()){
	    	transfers_to('vanbanphapquy.php');
	    }
    } else {
	    if($vanbanphapquy->insert()){
	    	transfers_to('vanbanphapquy.php');
	    }
	}
}
if($id && $act == 'edit'){
	$vanbanphapquy->id = $id; $vb = $vanbanphapquy->get_one();
	$id_linhvuc = $vb['id_linhvuc'];
	$loaivanban = $vb['loaivanban'];
	$id_coquanbanhanh = $vb['id_coquanbanhanh'];
	$sovanban = $vb['sovanban'];
	$trichyeu = $vb['trichyeu'];
	$ngaybanhanh = isset($vb['ngaybanhanh']) ? date("d/m/Y", $vb['ngaybanhanh']->sec) :'';
	$dinhkem = $vb['dinhkem'];
}
?>
<link rel="stylesheet" type="text/css" href="css/style.css">
<script type="text/javascript" src="js/select2.min.js"></script>
<script type="text/javascript" src="js/jquery.inputmask.js"></script>
<script type="text/javascript" src="js/themtintuc.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".select2").select2();
		$(".ngaythangnam").inputmask();
		 upload_hinhanh();delete_file();
	});

</script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Thông tin văn bản pháp quy</h1>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" accept-charset="utf-8" id="themtintuc" data-role="validator" data-show-required-state="false" enctype="multipart/form-data">
<input type="hidden" name="id" id="id" value="<?php echo isset($id) ? $id : ''; ?>">
<input type="hidden" name="act" id="act" value="<?php echo isset($act) ? $act : ''; ?>">
<div class="grid">
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Lĩnh vực</div>
		<div class="cell colspan4 input-control select">
			<select name="id_linhvuc" id="id_linhvuc" class="select2">
			<?php
			if($linhvuc_list){
				foreach ($linhvuc_list as $lv) {
					echo '<option value="'.$lv['_id'].'"'.($lv['_id'] == $id_linhvuc ? ' selected' : '').'>'.$lv['ten'].'</option>';
				}
			}
			?>
			</select>
		</div>
		<div class="cell colspan2 padding-top-10">Loại văn bản</div>
		<div class="cell colspan4 input-control select">
			<select name="loaivanban" id="loaivanban" class="select2">
			<?php
			foreach ($arr_loaivanban as $lvb) {
				echo '<option value="'.$lvb.'"'.($lvb == $loaivanban ? ' selected' : '').'>'.$lvb.'</option>';
			}
			?>
			</select>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Cơ quan ban hành</div>
		<div class="cell colspan4 input-control select">
			<select name="id_coquanbanhanh" id="id_coquanbanhanh" class="select2">
			<?php
			if($donvi_list){
				foreach ($donvi_list as $dv) {
					echo '<option value="'.$dv['_id'].'"'.($dv['_id'] == $id_coquanbanhanh ? ' selected' : '').'>'.$dv['ten'].'</option>';
				}
			}
			?>
			</select>
		</div>
		<div class="cell colspan2 padding-top-10">Số văn bản</div>
		<div class="cell colspan4 input-control text">
			<input type="text" name="sovanban" id="sovanban" value="<?php echo isset($sovanban) ? $sovanban: ''; ?>" data-validate-func="required">
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Trích yếu</div>
		<div class="cell colspan10 input-control text">
			<input type="text" name="trichyeu" id="trichyeu" value="<?php echo isset($trichyeu) ? $trichyeu: ''; ?>" data-validate-func="required">
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Ngày ban hành</div>
		<div class="cell colspan4 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<input type="text" name="ngaybanhanh" id="ngaybanhanh" value="<?php echo isset($ngaybanhanh) ? $ngaybanhanh : ''; ?>" placeholder="- ngày ban hành - " data-inputmask="'alias': 'date'" class="ngaythangnam"/>
		</div>
		<div class="cell colspan2 padding-top-10">Đính kèm</div>
        <div class="cell colspan4">
            <div class="input-control file" data-role="input">
                <input type="file" name="dinhkem[]" class="dinhkem" multiple placeholder="Chọn tập tin" />
                <button class="button"><span class="mif-folder"></span></button>
            </div>
        </div>
	</div>
	<div id="list_hinhanh">
        <?php
        if(isset($dinhkem) && $dinhkem){
            foreach ($dinhkem as $h) {
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
            <a href="vanbanphapquy.php" class="button"><span class="mif-keyboard-return"></span> Trở về</a>
            <button name="submit" id="submit" class="button primary"><span class="mif-checkmark"></span> Cập nhật</button>
        </div>
    </div>
</div>
</form>
<?php require_once('footer.php'); ?>