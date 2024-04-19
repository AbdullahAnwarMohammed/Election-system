<?php
include("include/template/_header.php");
$frontendDATA = $db->getSingleInfo('frontend','idUser',$_SESSION['idSuperVisor']);

?>

<!-- Sidebar wrapper start -->
<?php include("include/template/_sidebar.php"); ?>

<!-- Sidebar wrapper end -->

<!-- Page content start  -->
<div class="page-content">

<!-- Header start -->
<?php
include("include/template/_navbar.php");
?>
<!-- Header end -->

<!-- Page header start -->
<div class="page-header">
<ol class="breadcrumb">
  <li class="breadcrumb-item"><?=$frontendDATA['event']?></li>
  <!-- <li class="breadcrumb-item active">Admin Dashboard</li> -->
</ol>

<ul class="app-actions">
  <li>
    <a href="#" id="reportrange">
      <span class="range-text"></span>
      <i class="icon-chevron-down"></i>
    </a>
  </li>
  <li>
    <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print">
      <i class="icon-print"></i>
    </a>
  </li>
  <li>
    <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Download CSV">
      <i class="icon-cloud_download"></i>
    </a>
  </li>
</ul>
</div>
<!-- Page header end -->

<!-- Main container start -->
<div class="main-container">
<?php 
if (isset($_GET['action'])) {

    if ($_GET['action'] == 'delete') {
      $db->deleteSuperVisor('infocandidate','idCandidate', $_GET['id']);
      $db->deleteSuperVisor('frontend','idUser', $_GET['id']);
      $db->deleteSuperVisor('frontend','parent', $_GET['id']);
      $data_m_c = $db->getAll('musharifin_candidate','idCandidate', $_GET['master'], 'yes');
      if ($data_m_c) {
        $db->deleteSuperVisor('supervisor','id', $_GET['id']);
  
        foreach ($data_m_c as $row) {
          $db->deleteSuperVisor('daman','idMusharif', $row['idMusharifin']);
        }
  
        $db->deleteSuperVisor('musharifin_candidate','idMusharifin', $_GET['id']);
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
          <strong>بنجاح</strong> تح الحذف
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        header("location:show_condidate.php?master=" . $_SESSION['idSuperVisor']);
      }
    }
    if ($_GET['action'] == 'add') {
  
  ?>
      <div class="row">
        <?php require_once("include/template/forms/controls_candidate/add_condidate.php") ?>
      </div>
    <?php
    }
    if ($_GET['action'] == 'edit') {
    ?>
      <div class="row">
        <?php require_once("include/template/forms/controls_candidate/edit_condidate.php") ?>
      </div>
    <?php
    }
    if ($_GET['action'] == 'show') {
    ?>
      <div class="row">
        <?php require_once("include/template/forms/controls_candidate/show_condidate.php") ?>
      </div>
    <?php
    }
  } else {

    ?>
<div class="row gutters">
<?php 
  $musharifins = $db->getAll('musharifin_candidate','idCandidate', $_GET['master'], 'yes');
  $male = 0;
  $female = 0;
if(!empty($musharifins)){
  $all = count($musharifins);
  foreach($musharifins as $row)
  {
      $sql2 = $db->db->prepare("SELECT * FROM supervisor WHERE id = ?");
      $sql2->execute([$row['idMusharifin']]);
    $fetch = $sql2->fetch();
    if($fetch['gender'] == 0)
    {
        $male++;
    }else{
        $female++;
    }
      
  }
}
?>
<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
              <div class="info-tiles bg-dark text-white">
                <div class="info-icon">
                  <i class="icon-account_circle"></i>
                </div>
                <div class="stats-detail">
                  <h3><?=$all?></h3>
                  <p>العدد الكلي</p>
                </div>
              </div>
            </div>

                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
              <div class="info-tiles bg-info text-white">
                <div class="info-icon">
                  <i class="icon-account_circle"></i>
                </div>
                <div class="stats-detail">
                  <h3><?=$male?></h3>
                  <p>الذكور</p>
                </div>
              </div>
            </div>

                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
              <div class="info-tiles  text-white" style="background-color: #850b0b;">
                <div class="info-icon">
                  <i class="icon-account_circle"></i>
                </div>
                <div class="stats-detail">
                  <h3><?=$female?></h3>
                  <p>الاناث</p>
                </div>
              </div>
            </div>

  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
  <div class="table-container">
            <div class="t-header d-flex  justify-content-between align-items-center text-white" style="background-color: #892222;">
                                المفاتيح
                                <a href="?action=add&master=<?= $_SESSION['idSuperVisor'] ?>" class="btn btn-success">اضافة</a>
                            
                            </div>
            <div class="table-responsive">
              <table id="basicExample" class="table custom-table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>الاسم</th>
                     <th>رقم الهاتف</th>
   <th>البريد</th>
   <th>كلمة السر</th>
 <th>اعدادت</th>
                    <th>المُتعهدون</th>
                    <th>المضامين</th>

                 
                   
                  </tr>
                </thead>
                <tbody>
                                        <?php 
                                              if ($musharifins !== false) {
                                              foreach ($musharifins as $row) :
                                                
                                                $countSDaman  = $db->db->prepare("SELECT * FROM daman WHERE idMusharif = ?");
                                                $countSDaman->execute([$row['idMusharifin']]);

                                                $countSMadmen  = $db->db->prepare("SELECT * FROM vote WHERE idParent = ?");
                                                $countSMadmen->execute([$row['idMusharifin']]);

                                                $row = $db->getSingleInfo('supervisor','id', $row['idMusharifin']);
                                                $rowFrontend = $db->getSingleInfo('frontend','idUser', $row['id']);
                                                if ($_SERVER['HTTP_HOST'] == 'localhost') {
                                                  $link = 'https://'.$_SERVER['HTTP_HOST'] . '/electionSite/index.php?username=' . $rowFrontend['nameEnglish'] . '&id=' . $rowFrontend['id'];
                                                } else {
                                                  $link =  'https://'.  $_SERVER['HTTP_HOST'] . '/' . 'index.php?username=' . $rowFrontend['nameEnglish'] . '&id=' . $rowFrontend['id'];;
                                                }
                                                if ($row['rank'] != '4') {
                                        
                                                }
                                                $changeColor = $row['gender'] == 0 ? "#062bb1" : "#c51334";
            $password = $db->db->prepare("select * from passwords where id_user = ?");
            $password->execute([$row['id']]);
            $password = $password->fetch()['password'];
                                                ?>
                  <tr>
                    <td></td>
                    <td><a target="_blank" href="<?=$link?>" style="color:<?=$changeColor?>"><?=$row['username']?></a></td>
                     <td>
                        
                        <?=$row['phone']?>
                        <a href="tel:+965<?=$row['phone']?>"><img   width='20'src="assets/img/telephone.png" /></a></a>
                        
                    <a href="https://wa.me/+965<?=$row['phone']?>"><img width='20' src="assets/img/whatsapp.png" /></a>
                        </td>
                    
                         <td><?=$row['email']?></td>
                         <td><?=$password?></td>
                         <td>
                                <a href="?action=edit&master=<?=$_SESSION['idSuperVisor']?>&id=<?=$row['id']?>" class="edit-card" >
                                            <i class="icon-mode_edit"></i>
                                            </a>


                                            <a href="#" class="deleteMofta7 delete-card " data-master="<?=$_SESSION['idSuperVisor']?>" data-id="<?=$row['id'] ?>">
                                                <i class="icon-delete"></i>
                                            </a>
                                            <a href="#" class="copylink"   data-link="<?=$link?>">
                                                <i class="icon-copy"></i>
                                            </a>
                                        
                         </td>

                    <td><?=$countSDaman->rowCount()?></td>
                      <td><?=$countSMadmen->rowCount()?></td>

                   
                   
                                         
              
                  </tr>
                                                <?php 
                                                endforeach;
                                            }
                                                ?>
                                        
                
                </tbody>
              </table>
            </div>
          </div>

  </div>
</div>
<?php 
  } 
  ?>
</div>
<!-- Main container end -->

<!-- Page content end -->
<?php
include("include/template/_footer.php");
?>

<script>
$(function(){

$(".deleteMofta7").on("click",function(){
  let con = confirm("هل تود الحذف");
  if(con)
  {
    window.location.href = `?action=delete&master=`+$(this).data("master")+`&id=`+$(this).data("id");
  } 
})
})
</script>