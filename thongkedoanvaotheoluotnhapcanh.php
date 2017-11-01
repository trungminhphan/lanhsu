<?php
require_once('header.php');
$kinhphi = new KinhPhi();$quocgia = new QuocGia();$canbo = new CanBo();$doanvao=new DoanVao();
$donvi = new DonVi();$linhvuc = new LinhVuc();$mucdich = new MucDich();
$id_donvi ='';$id_quocgia='';$id_kinhphi='';
if(isset($_GET['submit'])){
    $query = array();
    $a = isset($_GET['id_donvi']) ? explode("-", $_GET['id_donvi']) : '';
    $id_donvi = isset($a[0]) ? $a[0] : '';
    $tungay = isset($_GET['tungay']) ? $_GET['tungay'] : '';
    $denngay = isset($_GET['denngay']) ? $_GET['denngay'] : '';
    $id_mucdich = isset($_GET['id_mucdich']) ? $_GET['id_mucdich'] : '';
    $id_linhvuc = isset($_GET['id_linhvuc']) ? $_GET['id_linhvuc'] : '';
    if(convert_date_dd_mm_yyyy($tungay) > convert_date_dd_mm_yyyy($denngay)){
        $msg = 'Chọn ngày sai hoặc chưa chọn Đơn vị thống kê';
    } else {
        $start_date = new MongoDate(convert_date_dd_mm_yyyy($tungay));
        $end_date = new MongoDate(convert_date_dd_mm_yyyy($denngay));
        array_push($query, array('ngayden' => array('$gte' => $start_date)));
        array_push($query, array('ngaydi' => array('$lte' => $end_date)));
        if($id_donvi && count($a) == 1){
            array_push($query, array('$or' => array(array('danhsachdoan.id_donvi.0' => $id_donvi), array('danhsachdoan_2.id_donvi.0' => $id_donvi))));
        } else if($id_donvi && count($a) == 2){
            array_push($query, array('$or' => array(array('danhsachdoan.id_donvi.0' => $id_donvi,'danhsachdoan.id_donvi.1' => $a[1]), array('danhsachdoan_2.id_donvi.0' => $id_donvi, 'danhsachdoan_2.id_donvi.1' => $a[1]))));
        } else if($id_donvi && count($a) == 3){
            array_push($query, array('$or' => array(array('danhsachdoan.id_donvi.0' => $id_donvi,'danhsachdoan.id_donvi.1' => $a[1], 'danhsachdoan.id_donvi.2' => $a[2]), array('danhsachdoan_2.id_donvi.0' => $id_donvi, 'danhsachdoan_2.id_donvi.1' => $a[1], 'danhsachdoan_2.id_donvi.2' => $a[2]))));
        } else if($id_donvi && count($a) == 4){
            array_push($query, array('$or' => array(array('danhsachdoan.id_donvi.0' => $id_donvi,'danhsachdoan.id_donvi.1' => $a[1], 'danhsachdoan.id_donvi.2' => $a[2], 'danhsachdoan.id_donvi.3' => $a[3]), array('danhsachdoan_2.id_donvi.0' => $id_donvi, 'danhsachdoan_2.id_donvi.1' => $a[1], 'danhsachdoan_2.id_donvi.2' => $a[2], 'danhsachdoan_2.id_donvi.3' => $a[3]))));
        } else {
            $donvi_list = $donvi->get_all_list();
        }

        $query_1 =array(
            array('ngayden' => array('$gte' => $start_date)),
            array('ngaydi' => array('$lte' => $end_date)),
            array('$or' => array(array('danhsachdoan.id_donvi.0' => $id_donvi), array('danhsachdoan_2.id_donvi.0' => $id_donvi)))
        );

        if($id_mucdich){
            array_push($query, array('id_mucdich' => new MongoId($id_mucdich)));
            array_push($query_1, array('id_mucdich' => new MongoId($id_mucdich)));
        }
        if($id_linhvuc){
            array_push($query, array('id_linhvuc' => new MongoId($id_linhvuc)));
            array_push($query_1, array('id_linhvuc' => new MongoId($id_linhvuc)));
        }
        $q1 = array('$and' => $query_1);
        $list_1 = $doanvao->get_list_condition($q1);
        $q = array('$and' => $query);
        $union_list = $doanvao->get_list_condition($q);

    }
}
$kinhphi_list = $kinhphi->get_all_list();
$quocgia_list = $quocgia->get_all_list();
?>
<script type="text/javascript" src="js/select2.min.js"></script>
<script type="text/javascript" src="js/jquery.inputmask.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/thongkedoanra.js"></script>
<script type="text/javascript" src="js/date-eu.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $(".select2").select2();timdonvi();
        $(".ngaythangnam").inputmask();
        <?php if(isset($msg) && $msg) : ?>
            $.Notify({type: 'alert', caption: 'Thông báo', content: <?php echo "'".$msg."'"; ?>});
        <?php endif; ?>
        $(".open_window").click(function(){
          window.open($(this).attr("href"), '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=0, left=100, width=1024, height=800');
          return false;
        });
        $('#list_result').dataTable();
    });
</script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Thống kê Đoàn vào theo Đơn vị (lượt nhập cảnh)</h1>
<form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="thongkeform" data-role="validator" data-show-required-state="false" enctype="multipart/form-data">
<div class="grid" style="margin-top: 30px;">
    <div class="row cells12">
        <div class="cell colspan4 input-control select">
            <label>Đơn vị</label>
            <select name="id_donvi" id="id_donvi">
            <?php
            if(isset($id_donvi) && $id_donvi){
                $donvi->id = $id_donvi; $dv = $donvi->get_one();
                echo '<option value="'.$dv['_id'].'">'.$dv['ten'].'</option>';
            }
            ?>
            </select>
        </div>
        <div class="cell colspan4 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
            <label>Từ ngày</label>
            <input type="text" name="tungay" id="tungay" value="<?php echo isset($tungay) ? $tungay : '01/01/2006'; ?>" class="ngaythangnam" data-inputmask="'alias': 'date'" data-validate-func="required" placeholder="Chọn ngày" />
            <span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
        </div>
        <div class="cell colspan4 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
            <label>Đến ngày</label>
            <input type="text" name="denngay" id="denngay" value="<?php echo isset($denngay) ? $denngay : date('d/m/Y'); ?>" class="ngaythangnam" data-inputmask="'alias': 'date'" data-validate-func="required" placeholder="Chọn ngày" />
            <span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
        </div>
    </div>
    <div class="row cells12">
        <div class="cell colspan4 input-control select">
            <label>Mục đích</label>
            <select name="id_mucdich" id="id_mucdich" data-placeholder="Chọn mục đích" data-allow-clear="true" class="select2">
                <option value=""></option>
                option
            <?php
            $mucdich_list = $mucdich->get_all_list();
            if($mucdich_list){
                foreach($mucdich_list as $md){
                    echo '<option value="'.$md['_id'].'"'.($md['_id'] == $id_mucdich ? ' selected' : '').'>'.$md['ten'].'</option>';
                }
            }
            ?>
            </select>
        </div>
        <div class="cell colspan4 input-control select">
            <label>Lĩnh vực</label>
            <select name="id_linhvuc" id="id_linhvuc" data-placeholder="Chọn lĩnh vực" data-allow-clear="true" class="select2">
                <option value=""></option>
            <?php
            $linhvuc_list = $linhvuc->get_all_list();
            if($linhvuc_list){
                foreach($linhvuc_list as $lv){
                    echo '<option value="'.$lv['_id'].'"'.($lv['_id'] == $id_linhvuc ? ' selected' : '').'>'.$lv['ten'].'</option>';
                }
            }
            ?>
            </select>
        </div>
    </div>
    <div class="row cells12">
        <div class="cell colspan12 align-center">
            <button name="submit" id="submit" value="OK" class="button primary"><span class="mif-checkmark"></span> Thống kê</button>
            <?php if(isset($_GET['submit'])) : ?>
                <a href="in_thongkedoanvaotheoluot.php?<?php echo $_SERVER['QUERY_STRING']; ?>" class="open_window button"><span class="mif-printer"></span> Print</a>
                <a href="export_thongkedoanvaotheoluot.php?<?php echo $_SERVER['QUERY_STRING']; ?>" class="button success"><span class="mif-file-excel"></span> Excel</a>
            <?php endif; ?>
        </div>
    </div>
</div>
</form>
<hr />
<?php if(isset($union_list) && $union_list->count() > 0 && $id_donvi) : ?>
<?php
if(isset($id_donvi) && $id_donvi){
    $c1 = 0;
    foreach ($list_1 as $u) {
        if($u['danhsachdoan']){
            foreach ($u['danhsachdoan'] as $ds) {
                if($ds['id_donvi'][0] == $id_donvi) $c1++;
            }
        }
        if($u['danhsachdoan_2']){
            foreach ($u['danhsachdoan_2'] as $ds2) {
                if($ds2['id_donvi'][0] == $id_donvi) $c1++;
            }
        }
    }
    echo '<h4><a href=""><span class="mif-arrow-left"></span></a> ' . $dv['ten'] .': <span class="fg-blue"><a href="thongkedoanvaotheoluotnhapcanh.php?id_donvi='.$id_donvi.'&tungay='.$tungay.'&denngay='.$denngay.'&id_quocgia='.$id_quocgia.'&id_kinhphi='.$id_kinhphi.'&submit=OK">'.$c1.'</a> lượt xuất cảnh (từ ngày: '.$tungay.'  đến ngày: '.$denngay.')</span></h4>';
    if(isset($dv['level2']) && $dv['level2']){
        foreach ($dv['level2'] as $a2) {
            $c2 = 0;
            foreach ($union_list as $u) {
                if($u['danhsachdoan']){
                    foreach ($u['danhsachdoan'] as $ds) {
                        if($ds['id_donvi'][0] == $id_donvi && $ds['id_donvi'][1]==strval($a2['_id'])) $c2++;
                    }
                }
                if($u['danhsachdoan_2']){
                    foreach ($u['danhsachdoan_2'] as $ds2) {
                        if($ds2['id_donvi'][0] == $id_donvi && $ds2['id_donvi'][1] == strval($a2['_id'])) $c2++;
                    }
                }
            }
            if($c2) echo '<h5><span class="mif-arrow-right"></span> '.$a2['ten'].': <a href="thongkedoanvaotheoluotnhapcanh.php?id_donvi='.$id_donvi.'-'.$a2['_id'].'&tungay='.$tungay.'&denngay='.$denngay.'&id_quocgia='.$id_quocgia.'&id_kinhphi='.$id_kinhphi.'&submit=OK">'.$c2.'</a></h5>';
            if(isset($a2['level3']) && $a2['level3']){
                echo '<ul>';
                foreach ($a2['level3'] as $a3) {
                    $c3 = 0;
                    foreach ($union_list as $u) {
                        if($u['danhsachdoan']){
                            foreach ($u['danhsachdoan'] as $ds) {
                                if($ds['id_donvi'][0] == $id_donvi && $ds['id_donvi'][1]==$a2['_id'] && $ds['id_donvi'][2]==$a3['_id']) $c3++;
                            }
                        }
                        if($u['danhsachdoan_2']){
                            foreach ($u['danhsachdoan_2'] as $ds2) {
                                if($ds2['id_donvi'][0] == $id_donvi && $ds2['id_donvi'][1] == $a2['_id'] && $ds2['id_donvi'][2] == $a3['_id']) $c3++;
                            }
                        }
                    }
                    if($c3) echo '<li>'.$a3['ten'].': <a href="thongkedoanvaotheoluotnhapcanh.php?id_donvi='.$id_donvi.'-'.$a2['_id'] . '-' .$a3['_id'].'&tungay='.$tungay.'&denngay='.$denngay.'&id_quocgia='.$id_quocgia.'&id_kinhphi='.$id_kinhphi.'&submit=OK">'.$c3.'</a></li>';
                    if(isset($a3['level4']) && $a3['level4']){
                        echo '<ul>';
                        foreach ($a3['level4'] as $a4) {
                            $c4 = 0;
                            foreach ($union_list as $u) {
                                if($u['danhsachdoan']){
                                    foreach ($u['danhsachdoan'] as $ds) {
                                        if($ds['id_donvi'][0] == $id_donvi && $ds['id_donvi'][1]==$a2['_id'] && $ds['id_donvi'][2]==$a3['_id'] && $ds['id_donvi'][3]==$a4['_id']) $c4++;
                                    }
                                }
                                if($u['danhsachdoan_2']){
                                    foreach ($u['danhsachdoan_2'] as $ds2) {
                                        if($ds2['id_donvi'][0] == $id_donvi && $ds2['id_donvi'][1] == $a2['_id'] && $ds2['id_donvi'][2] == $a3['_id'] && $ds2['id_donvi'][3] == $a4['_id']) $c4++;
                                    }
                                }
                            }
                            if($c4) echo '<li>'.$a4['ten'].': <a href="thongkedoanvaotheoluotnhapcanh.php?id_donvi='.$id_donvi.'-'.$a2['_id'] . '-' .$a3['_id'].'-'.$a4['_id'].'&tungay='.$tungay.'&denngay='.$denngay.'&id_quocgia='.$id_quocgia.'&id_kinhphi='.$id_kinhphi.'&submit=OK">'.$c4.'</a></li>';
                        }
                        echo '</ul>';

                    }
                }
                echo '</ul>';
            }

        }
    }
}
?>
<table class="table border bordered striped dataTable" id="list_result" >
    <thead>
        <tr>
            <th>STT</th>
            <th>Họ tên</th>
            <th>Văn bản xin phép</th>
            <th>Văn bản cho phép</th>
            <th>Ngày đến</th>
            <th>Ngày đi</th>
            <th>Lĩnh vực</th>
            <th>Nội dung</th>
            <th>Chi tiết</th>
        </tr>
    </thead>
    <tbody>
    <?php
        $i = 1;
        $arr_union_list = iterator_to_array($union_list);
        $arr_union_list = sort_array_and_key($arr_union_list, 'ngaydi', SORT_DESC);
        foreach ($arr_union_list as $u) {
            $cvxinphep = $u['congvanxinphep']['ten'];
            $file_xinphep = isset($u['congvanxinphep']['attachments'][0]['alias_name']) ? $u['congvanxinphep']['attachments'][0]['alias_name'] : '';;
            $soquyetdinh = isset($u['quyetdinhchophep']['ten']) ? $u['quyetdinhchophep']['ten'] : '';;
            $file_chophep = isset($u['quyetdinhchophep']['attachments'][0]['alias_name']) ? $u['quyetdinhchophep']['attachments'][0]['alias_name'] : '';
            $ngaydi = $u['ngayden'] ? date("d/m/Y", $u['ngayden']->sec) : '';
            $ngayve = $u['ngaydi'] ? date("d/m/Y", $u['ngaydi']->sec) : '';
            if(isset($u['id_linhvuc']) && $u['id_linhvuc']){
                $linhvuc->id = $u['id_linhvuc']; $lv = $linhvuc->get_one();
                $tenlinhvuc = $lv['ten'];
            } else { $tenlinhvuc = ''; }
            if($u['danhsachdoan']){
                foreach ($u['danhsachdoan'] as $ds) {
                    if($ds['id_donvi'][0] == $id_donvi){
                        $canbo->id = $ds['id_canbo']; $cb = $canbo->get_one();
                        echo '<tr>
                            <td>'.$i.'</td>
                            <td>'.$cb['hoten'].'</td>
                            <td><a href="'.$target_files.$file_xinphep.'" target="_blank">'.$cvxinphep.'</a></td>
                            <td><a href="'.$target_files.$file_chophep.'" target="_blank">'.$soquyetdinh.'</a></td>
                            <td>'.$ngaydi.'</td>
                            <td>'.$ngayve.'</td>
                            <td>'.$tenlinhvuc.'</td>
                            <td>'.$u['noidung'].'</td>
                            <td><a href="chitietdoanvao.php?id='.$u['_id'].'" target="_blank"><span class="mif-list-numbered"></span></a></td>
                        </tr>';$i++;
                    }
                }
            }
            if($u['danhsachdoan_2']){
                foreach ($u['danhsachdoan_2'] as $ds2) {
                    if($ds2['id_donvi'][0] == $id_donvi){
                        $canbo->id = $ds2['id_canbo']; $cb = $canbo->get_one();
                        echo '<tr>
                            <td>'.$i.'</td>
                            <td>'.$cb['hoten'].'</td>
                            <td><a href="'.$target_files.$file_xinphep.'" target="_blank">'.$cvxinphep.'</a></td>
                            <td><a href="'.$target_files.$file_chophep.'" target="_blank">'.$soquyetdinh.'</a></td>
                            <td>'.$ngaydi.'</td>
                            <td>'.$ngayve.'</td>
                            <td>'.$tenkinhphi.'</td>
                            <td>'.$u['noidung'].'</td>
                            <td><a href="chitietdoanvao.php?id='.$u['_id'].'" target="_blank"><span class="mif-list-numbered"></span></a></td>
                        </tr>';$i++;
                    }
                }
            }
        }
    ?>
    </tbody>
</table>
<?php endif; ?>

<?php if(isset($donvi_list) && $donvi_list && !$id_donvi): ?>
<table class="table border bordered striped hovered">
    <thead>
        <tr>
            <th>STT</th>
            <th>Đơn vị</th>
            <th>Số đoàn</th>
            <th>Số lượt</th>
            <th>Số người</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $i=1;
    foreach($donvi_list as $dv){
        $query_1 = $query;$query_2 = $query;
        array_push($query_1, array('congvanxinphep.id_donvi.0' => strval($dv['_id'])));
        $q_1 = array('$and' => $query_1);
        array_push($query_2, array('$or' => array(array('danhsachdoan.id_donvi.0' => strval($dv['_id'])), array('danhsachdoan_2.id_donvi.0' => strval($dv['_id'])))));
        $q_2 = array('$and' => $query_2);
        $count_sodoan = $doanvao->count_sodoan($q_1);
        $count_soluot = $doanvao->count_soluot($q_2, $dv['_id']);
        $count_songuoi = $doanvao->count_songuoi($q_2, $dv['_id']);
        if($count_sodoan > 0 || $count_soluot > 0 || $count_songuoi){
            echo '<tr>
                <td>'.$i.'</td>
                <td>'.$dv['ten'].'</td>
                <td class="align-right"><a href="thongkedoanvaotheodonvi.php?id_donvi='.$dv['_id'].'&'.$_SERVER['QUERY_STRING'].'" target="_blank">'.$count_sodoan.'</a></td>
                <td class="align-right"><a href="thongkedoanvaotheoluotnhapcanh.php?id_donvi='.$dv['_id'].'&'.$_SERVER['QUERY_STRING'].'" target="_blank">'.$count_soluot.'</a></td>
                <td class="align-right"><a href="chitietsonguoinhapcanh.php?id_donvi='.$dv['_id'].'&'.$_SERVER['QUERY_STRING'].'" target="_blank">'.$count_songuoi.'</a></td>
            </tr>'; $i++;
        }
    }
    ?>
    </tbody>
</table>
<?php endif; ?>
<?php require_once('footer.php'); ?>
