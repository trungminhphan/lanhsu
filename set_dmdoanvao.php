<?php
require_once('header.php');
$doanvao = new DoanVao();
$doanvao_list = $doanvao->get_all_list();

?>
<?php
if($doanvao_list){
    foreach($doanvao_list as $dv){
      $doanvao->id = $dv['_id'];
      if($dv['id_dmdoanvao']){
        $id_dmdoan = strval($dv['id_dmdoanvao']);
        $arr = array($id_dmdoan);
        $doanvao->set_id_dmdoanvao($arr);
      }
    }
}
?>
<?php
require_once('footer.php');
?>
