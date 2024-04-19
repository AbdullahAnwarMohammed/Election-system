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
      header("location:daman.php");
    }
    if ($_GET['action'] == 'add') {

      require_once "include/template/forms/daman/addDaman.php";
    }
    if ($_GET['action'] == 'show') {
      require_once "include/template/forms/daman/showDaman.php";
    }
    if ($_GET['action'] == 'edit') {
      require_once "include/template/forms/daman/editDaman.php";
    }
  } else {

    ?>
    
      <div class="row gutters">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
      <div class="table-container">
      <div class="t-header d-flex  justify-content-between align-items-center">
                المُتعهدون
                <?php
      $Power = $db->getSingleInfo('powers','idUser', $_SESSION['idSuperVisor']);
      if ($_SESSION['rankSuperVisor'] == 3) {
        if ($Power['create_daman'] == 0) {
      ?>
          <a href="#" class="btn btn-dark">غير مؤهل</a>

        <?php
        } else {
        ?>
          <a href="?action=add&master=<?= $_SESSION['idSuperVisor'] ?>" class="btn  btn-success shadow-sm"> <i class="fas fa-plus"></i> اضافة</a>

      <?php
        }
      }

      ?>
      </div>
        <div class="table-responsive">
            <table id="basicExample" class="table custom-table">
            <thead>
            <tr>
              <th>#</th>
              <th scope="col">اسم المُتعهد</th>
              <th>رقم الهاتف</th>
              <th scope="col">الحالة</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if(!empty($db->getAll('daman','idMusharif', $_SESSION['idSuperVisor'], 'yes')))
            {
              foreach ($db->getAll('daman','idMusharif', $_SESSION['idSuperVisor'], 'yes') as $row) {
                $query = "SELECT * FROM frontend WHERE idUser = ? AND parent = ?";
                $stmt = $db->db->prepare($query);

                $stmt->execute([$row['id'], $row['idMusharif']]);
                $rowFrontend = $stmt->fetch();
                $infocandidate = $db->getSingleInfo('infocandidate','idCandidate', $row['id']);
                if ($_SERVER['HTTP_HOST'] == 'localhost') {
                  $link = 'https://'.$_SERVER['HTTP_HOST'] . '/electionSite/index.php?username=' . $rowFrontend['nameEnglish'] . '&id=' . $rowFrontend['id'];
                } else {
            $link = 'https://'.$_SERVER['HTTP_HOST'] . '/' . 'index.php?username=' . $rowFrontend['nameEnglish'] . '&id=' . $rowFrontend['id'];;
                }
                                      $changeColor = $row['gender'] == 0 ? "#062bb1" : "#c51334";

              ?>
                <tr>
                  <td></td>
                  <!-- href="?action=show&master= $_SESSION['idSuperVisor'] ?>&id=$row['id'] ?>" -->
                  <td><a  href="<?=$link?>" target="_blank" style="color:<?=$changeColor?>" ><?= $row['username'] ?> <i class="fas fa-eye"></i> </a></td>
                  <td>
                        <?=$row['phone']?>
                        <a href="tel:+965<?=$row['phone']?>"><img   width='20'src="assets/img/telephone.png" /></a></a>
                        
                    <a href="https://wa.me/+965<?=$row['phone']?>"><img width='20' src="assets/img/whatsapp.png" /></a>
                        </td>
                  </td>
                  <td>
                    <a title="حذف" class="deleteMot" data-id="<?=$row['id']?>" href="#">
                    <i class="icon-delete"></i>
                    </a>

                    <a title="تعديل" href="daman.php?action=edit&master=<?= $_SESSION['idSuperVisor'] ?>&id=<?= $row['id'] ?>">

                    <i class="icon-mode_edit"></i>
                    </a>

                    <a title="نسخ" href="#" class=" copylink" data-link="<?= $link ?>">
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

  <?php 
    }
    ?>


</div>
<!-- Main container end -->

</div>
<!-- Page content end -->
<?php
include("include/template/_footer.php");
?>
<script>

$(function() {

  $(".deleteMot").on("click", function() {
    let con = confirm("هل تود الحذف");
    if (con) {

      window.location.href = `?action=delete&master=<?= $_SESSION['idSuperVisor'] ?>&id=` + $(this).data("id");
    }
  })
})
</script>

