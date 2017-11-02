<?php
function __autoload($class_name) {
    require_once('cls/class.' . strtolower($class_name) . '.php');
}
$session = new SessionManager();
$users = new Users();
require_once('inc/functions.inc.php');
require_once('inc/config.inc.php');
if(!$users->isLoggedIn()){ transfers_to('./login.php'); }
$doanra_regis = new DoanRa_Regis();
$doanvao_regis = new DoanVao_Regis();
$abtc_regis = new ABTC_Regis();
$count_doanra = $doanra_regis->count_status_0();
$count_doanvao = $doanvao_regis->count_status_0();
$count_abtc = $abtc_regis->count_status_0();
$total_count=$count_doanra+$count_doanvao+$count_abtc;
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Phần mềm quản lý Lãnh sự , Sở Ngoại vụ.">
    <meta name="keywords" content="Phần mêm, Lãnh sự, Sở NGoại vụ An Giang">
    <meta name="author" content="Trung tâm Tin học Trường Đai học An Giang, 18 Ung Văn Khiêm, Tp Long Xuyên, An Giang">
    <link rel='shortcut icon' type='image/x-icon' href="images/favicon.ico" />
    <title>Phần mềm Quản lý Lãnh sự - Sở Ngoại vụ An Giang</title>
    <link href="css/metro.css" rel="stylesheet">
    <link href="css/metro-icons.css" rel="stylesheet">
    <link href="css/metro-responsive.css" rel="stylesheet">
    <link href="css/metro-schemes.css" rel="stylesheet">
    <script src="js/jquery-2.1.3.min.js"></script>
    <script src="js/metro.js"></script>
</head>
<body>
<div class="app-bar" data-role="appbar">
    <a href="index.php" class="app-bar-element branding"><span class="mif-home mif-2x"></span></a>
    <ul class="app-bar-menu small-dropdown">
        <?php if($users->is_admin() || $users->is_student()) : ?>
        <li><a href="#" class="dropdown-toggle"><span class="mif-apps mif-2x"></span>&nbsp;&nbsp;Danh mục</a>
            <ul class="d-menu" data-role="dropdown">
                <?php if($users->is_admin()) : ?>
                <li><a href="quocgia.php"><span class="mif-earth"></span>&nbsp;&nbsp;Quốc gia/Quốc tịch</a></li>
                <li class="divider"></li>
                <li><a href="donvi.php"><span class="mif-library"></span>&nbsp;&nbsp;Đơn vị</a></li>
                <li class="divider"></li>
                <li><a href="chucvu.php"><span class="mif-organization"></span>&nbsp;&nbsp;Chức vụ</a></li>
                <li class="divider"></li>
                <li><a href="mucdich.php"><span class="mif-paw"></span>&nbsp;&nbsp;Mục đích</a></li>
                <li class="divider"></li>
                <li><a href="kinhphi.php"><span class="mif-dollars"></span>&nbsp;&nbsp;Kinh phí</a></li>
                <li class="divider"></li>
                <?php endif; ?>
                <?php if($users->is_admin() || $users->is_student()) : ?>
                <li><a href="canbo.php"><span class="mif-user-plus"></span>&nbsp;&nbsp;Quản lý Cá nhân</a></li>
                <li class="divider"></li>
                <?php endif; ?>
                <?php if($users->is_admin()) : ?>
                <li><a href="danhmucdoanvao.php"><span class="mif-shrink"></span>&nbsp;&nbsp;Danh mục Đoàn vào</a></li>
                <li class="divider"></li>
                <li><a href="ham.php"><span class="mif-medal"></span>&nbsp;&nbsp;Hàm</a></li>
                <li class="divider"></li>
                <li><a href="nghenghiep.php"><span class="mif-profile"></span>&nbsp;&nbsp;Nghề nghiệp</a></li>
                <li class="divider"></li>
                <li><a href="dantoc.php"><span class="mif-flag"></span>&nbsp;&nbsp;Dân Tộc</a></li>
                <li class="divider"></li>
                <li><a href="phanloaidonvi.php"><span class="mif-bookmark"></span>&nbsp;&nbsp;Phân loại đơn vị</a></li>
                <li class="divider"></li>
                <li><a href="linhvuc.php"><span class="mif-tag"></span>&nbsp;&nbsp;Lĩnh vực</a></li>
                <?php endif; ?>
            </ul>
        </li>
        <?php endif; ?>
        <?php if(!$users->is_student()): ?>
        <li>
            <a href="#" class="dropdown-toggle"><span class="mif-insert-template mif-2x"></span>&nbsp;&nbsp;Quản lý</a>
            <ul class="d-menu" data-role="dropdown">
                <li><a href="doanra.php"><span class="mif-enlarge"></span>&nbsp;&nbsp;Đoàn ra</a></li>
                <li class="divider"></li>
                <li><a href="doanvao.php"><span class="mif-shrink"></span>&nbsp;&nbsp;Đoàn vào</a></li>
                <li class="divider"></li>
                <li><a href="abtc.php"><span class="mif-credit-card"></span>&nbsp;&nbsp;Cấp thẻ ABTC</a></li>
                <li class="divider"></li>
                <li><a href="tintuc.php"><span class="mif-language"></span>&nbsp;&nbsp;Tin tức</a></li>
                <li class="divider"></li>
                <li><a href="vanbanphapquy.php"><span class="mif-folder-special"></span>&nbsp;&nbsp;Văn bản pháp quy</a></li>
            </ul>
        </li>
        <li>
            <a href="#" class="dropdown-toggle"><span class="mif-earth mif-2x"></span>&nbsp;&nbsp;Đăng ký trực tuyến <?php echo $total_count > 0 ? '<sup><span class="tag alert">'.$total_count.'</span></sup>' : ''; ?></a>
            <ul class="d-menu" data-role="dropdown">
                <li><a href="doanra_regis.php"><span class="mif-enlarge"></span>&nbsp;&nbsp;Đoàn ra <?php echo $count_doanra > 0 ? '<span class="tag alert">'.$count_doanra.'</span>' : '' ;?></a></li>
                <li class="divider"></li>
                <li><a href="doanvao_regis.php"><span class="mif-shrink"></span>&nbsp;&nbsp;Đoàn vào <?php echo $count_doanvao > 0 ? '<span class="tag alert">'.$count_doanvao.'</span>' : ''; ?></a></li>
                <li class="divider"></li>
                <li><a href="abtc_regis.php"><span class="mif-credit-card"></span>&nbsp;&nbsp;ABTC <?php echo $count_abtc > 0 ? '<span class="tag alert">'.$count_abtc.'</span>' : ''; ?></a></li>
                <li class="divider"></li>
            </ul>
        </li>
        <li>
            <a href="#" class="dropdown-toggle"><span class="mif-chart-line mif-2x"></span>&nbsp;&nbsp;Thống kê</a>
            <ul class="d-menu" data-role="dropdown">
                <li class="dropdown-toggle"><a href="#"><span class="mif-enlarge"></span>&nbsp;&nbsp;Đoàn ra</a>
                    <ul class="d-menu" data-role="dropdown">
                        <li><a href="thongkedoanratheocanhan.php">Thống kê theo Cá nhân</a></li>
                        <li class="divider"></li>
                        <li><a href="thongkedoanratheodonvi.php">Thống kê theo Đơn vị (xin phép)</a></li>
                        <li class="divider"></li>
                        <li><a href="thongkedoanratheoluotxuatcanh.php">Thống kê theo Đơn vị (lượt xuất cảnh)</a></li>
                        <li class="divider"></li>
                        <li><a href="thongkedoanratheoquocgia.php">Thống kê theo Quốc gia</a></li>
                        <li class="divider"></li>
                        <li><a href="thongkedoanratheokinhphi.php">Thống kê theo Kinh phí</a></li>
                        <li class="divider"></li>
                        <li><a href="thongkedoanratheomucdich.php">Thống kê theo Mục đích</a></li>
                    </ul>
                </li>
                <li class="divider"></li>
                <li class="dropdown-toggle"><a href="#"><span class="mif-shrink"></span>&nbsp;&nbsp;Đoàn vào</a>
                    <ul class="d-menu" data-role="dropdown">
                        <li><a href="thongkedoanvaotheocanhan.php">Thống kê theo Cá nhân</a></li>
                        <li class="divider"></li>
                        <li><a href="thongkedoanvaotheodonvi.php">Thống kê theo Đơn vị tiếp</a></li>
                        <li class="divider"></li>
                        <li><a href="thongkedoanvaotheoluotnhapcanh.php">Thống kê theo Tổ chức nước ngoài</a></li>
                        <li class="divider"></li>
                        <li><a href="thongkedoanvaotheomucdich.php">Thống kê theo Mục đích</a></li>
                        <li class="divider"></li>
                        <li><a href="thongkedoanvaotheolinhvuc.php">Thống kê theo Lĩnh vực</a></li>
                        <li class="divider"></li>
                        <li><a href="thongkedoanvao.php">Thống kê theo Đoàn vào</a></li>
                    </ul>
                </li>
                <li class="divider"></li>
                <li><a href="thongkeabtc.php"><span class="mif-credit-card"></span>&nbsp;&nbsp;Cấp thẻ ABTC</a></li>
                <li class="divider"></li>
                <li><a href="thongkephanloai.php"><span class="mif-tags"></span>&nbsp;&nbsp;Thống kê Phân loại</a></li>
                <li class="divider"></li>
                <li><a href="thongketuoihuu.php"><span class="mif-user-plus"></span>&nbsp;&nbsp;Thống kê Cán bộ sắp nghỉ hưu</a></li>
            </ul>
        </li>
        <li><a href="timkiemnangcao.php"><span class="mif-search mif-2x"></span> Tìm kiếm</a></li>
        <?php endif; ?>

        <li><a href="#" class="dropdown-toggle"><span class="mif-users mif-2x"></span>&nbsp;&nbsp;Tài khoản</a>
            <ul class="d-menu" data-role="dropdown">
                <?php if(!$users->is_student()): ?>
                    <?php if($users->is_admin()): ?>
                        <li><a href="users.php"><span class="mif-user"></span>&nbsp;&nbsp;Quản lý tài khoản</a></li>
                        <li class="divider"></li>
                        <li><a href="users_regis.php"><span class="mif-user"></span>&nbsp;&nbsp;Tài khoản đăng ký Trực tuyến</a></li>
                        <li class="divider"></li>
                    <?php endif; ?>
                    <li><a href="thongkenhaplieu.php"><span class="mif-user-check"></span>&nbsp;&nbsp;Thống kê nhập liệu</a></li>
                    <li class="divider"></li>
                <?php endif; ?>
                <li><a href="logout.php"><span class="mif-exit"></span>&nbsp;&nbsp;Đăng xuất</a></li>
                <li class="divider"></li>
            </ul>
        </li>
    </ul>
</div>
<div class="container page-content">
