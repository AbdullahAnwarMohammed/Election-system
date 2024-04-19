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
      $db->deleteSuperVisor('daman','id', $_GET['id']);
      $db->deleteSuperVisor('frontend','idUser', $_GET['id']);
      $db->deleteSuperVisor('powers','idUser', $_GET['id']);
      header("location:show_damans.php?master=" . $_SESSION['idSuperVisor']);
    }
    if ($_GET['action'] == 'add') {
  ?>
      <?php require_once("include/template/forms/damans_controls/add_daman.php") ?>
    <?php
    }
    if ($_GET['action'] == 'show') {
    ?>
      <?php require_once("include/template/forms/damans_controls/show_damans.php") ?>
    <?php
    }
    if ($_GET['action'] == 'edit') {
    ?>
      <?php require_once("include/template/forms/damans_controls/edit_daman.php") ?>
    <?php
    }
  } else {


    ?>


    <!-- Row start -->
    <div class="row gutters">
    <?php 
                  $damans =  $db->getAll('daman','idSuperviosr', $_GET['master'], 'yes');
                  $male = 0;
                  $female = 0;
                  $all = 0;
                if(!empty($damans)){
                  $all = count($damans);
                  foreach($damans as $row)
                  {
                    
                    if($row['gender'] == 0)
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
          <div class="table-container">
            <div class="t-header d-flex  justify-content-between align-items-center text-white" style="background-color: #267c30;">
              المُتعهدون
              <a href="?action=add&master=<?= $_SESSION['idSuperVisor'] ?>" class="btn btn-success">اضافة</a>
            </div>
            <div class="table-responsive">
              <table id="basicExample" class="table custom-table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>المفتاح</th>
                    <th>رقم الهاتف</th>
                    <th>المضامين</th>
                    <th>اعدادت</th>
                  </tr>
                </thead>
                <tbody>
                  <?php


                  if ($damans !== false) {
                    $x = 0;
                    foreach ($damans as $row) {
                      
                      $countSMadmen  = $db->db->prepare("SELECT * FROM vote WHERE idParent = ?");
                      $countSMadmen->execute([$row['id']]);

                      // $usernameMosraf = $Supervisor->getSingleInfo('id', $row['idSuperviosr']);
                      $idSuperVisor = $db->getSingleInfo('frontend','idUser', $row['id'])['parent'];
                      $SuperVisorData = $db->getSingleInfo('supervisor','id', $idSuperVisor);
                      $rowFrontend = $db->db->prepare(
                        "SELECT * FROM frontend WHERE idUser = ? AND 
                            username = ?"
                      );
                      $rowFrontend->execute([$row['id'], $row['username']]);

                      $rowFrontend = $rowFrontend->fetch();

                      if ($_SERVER['HTTP_HOST'] == 'localhost') {
                        $link = 'https://'.$_SERVER['HTTP_HOST'] . '/electionSite/index.php?username=' . $rowFrontend['nameEnglish'] . '&id=' . $rowFrontend['id'];
                      } else {
                        $link = 'https://'.$_SERVER['HTTP_HOST'] . '/' . 'index.php?username=' . $rowFrontend['nameEnglish'] . '&id=' . $rowFrontend['id'];;
                      }
                      $changeColor = $row['gender'] == 0 ? "#062bb1" : "#c51334";

                  ?>
                      <tr>
                        <td></td>
                        <td><a target="_blank" href="<?=$link?>" style="color:<?=$changeColor?>"><?=$row['username']?></a></td>
                        <td>
                          <?php
                          if ($SuperVisorData['id'] == $_SESSION['idSuperVisor']) {
                            echo "<span class='fw-bold text-primary'>تابع لك</span>";
                          } else {
                            echo $SuperVisorData['username'];
                          }

                          ?>
                        </td>
                        <td>
                        <?=$row['phone']?>
                        <a href="tel:+965<?=$row['phone']?>"><img  width='20'   src="assets/img/telephone.png" /></a></a>
                        
                    <a href="https://wa.me/+965<?=$row['phone']?>"><img width='20'  src="assets/img/whatsapp.png" /></a>
                        </td>
                        <td>
                          <?= $countSMadmen->rowCount() ?>
                        </td>
                        <td>
                          <a href="show_damans.php?action=edit&master=<?= $_SESSION['idSuperVisor'] ?>&id=<?= $row['id'] ?>" class="edit-card">
                            <i class="icon-mode_edit"></i>
                          </a>

                          
                          <a href="#" class="deleteMot3hd delete-card " data-master="<?= $_SESSION['idSuperVisor'] ?>" data-id="<?= $row['id'] ?>">
                            <i class="icon-delete"></i>
                          </a>
                          <a href="#" class="copylink" data-link="<?= $link ?>">
                            <i class="icon-copy"></i>
                          </a>
                        </td>

                      </tr>
                  <?php
                    }
                  }

                  ?>

                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>

    </div>
    <!-- Row end -->

</div>
<!-- Main container end -->
<?php
  }
?>
</div>
<!-- Page content end -->
<?php
include("include/template/_footer.php");
?>

<script>
$(function() {

  $(".deleteMot3hd").on("click", function() {
    let con = confirm("هل تود الحذف");
    if (con) {


      window.location.href = `?action=delete&master=` + $(this).data("master") + `&id=` + $(this).data("id");
    }
  })
})
</script>