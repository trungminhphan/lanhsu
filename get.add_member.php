<?php
    function __autoload($class_name) {
        require_once('cls/class.' . strtolower($class_name) . '.php');
    }
    $session = new SessionManager();
    $users = new Users();
    require_once('inc/functions.inc.php');
    require_once('inc/config.inc.php');
    if(!$users->isLoggedIn()){ transfers_to('./login.php'); }
    $canbo = new CanBo();$canbo_list = $canbo->get_all_list();
    $tinhtrang=0;
?>
<tr class="items">
	<td>
	<div class="input-control select">
		<select name="id_canbo[]" class="thanhviendoan">
		</select>
	</div>
	</td>
	<td>
		<div class="input-control select">
			<select name="tinhtrang[]" class="select2">
				<option value="0">Cấp mới</option>
				<option value="1">Gia hạn</option>
			</select>
		</div>
	</td>
	<td>
		<div class="input-control text" style="width:100px;">
			<input type="text" name="sothe[]" value="" style="width:100px;"/>
		</div>
	</td>
	<td class="align-center">
		<div class="input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<input type="text" name="ngaycap[]" value="<?php echo isset($ngaycap) ? $ngaycap : ''; ?>" placeholder="Ngày cấp." data-inputmask="'alias': 'date'" class="ngaythangnam" style="width:100px;"/>
		</div>
	</td>
	<td class="align-center">
		<div class="input-control text" data-role="datepicker" data-format="dd/mm/yyyy" style="width:100px;">
			<input type="text" name="ngayhethan[]" value="" placeholder="Ngày hết hạn." data-inputmask="'alias': 'date'" class="ngaythangnam" style="width:100px;"/>
		</div>
	</td>
	<td class="align-center"><a href="#" onclick="return false;" class="remove_member"><span class="mif-bin"></span></a></td>
</tr>