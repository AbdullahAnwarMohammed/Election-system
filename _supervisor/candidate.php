<?php
include("include/template/_header.php");
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
    <li class="breadcrumb-item">المرشحون</li>
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
if($_GET['action'] == 'delete')
{
    $db->deleteSuperVisor('infocandidate','idCandidate',$_GET['id']) ;
    $db->deleteSuperVisor('frontend','idUser',$_GET['id']) ;
    $db->deleteSuperVisor('frontend','parent',$_GET['id']) ;
    $data_m_c = $db->getAll('musharifin_candidate','idCandidate' , $_GET['id'],'yes');
    $r_m = $db->getAll('relationship_mandob','id_supervisor' , $_GET['id'],'yes');
    
    if($r_m)
    {
      foreach($r_m as $row){
        $db->deleteSuperVisor('supervisor','id',$row['id_mandob']);
      }
    }

    if($data_m_c){
    foreach($data_m_c as $row){
        $db->deleteSuperVisor('daman','id',$row['idMusharifin']);
        $Daman->deleteSuperVisor('daman','idSuperviosr',$row['idMusharifin']);
    }
  }

  if($db->deleteSuperVisor('supervisor','id',$_GET['id']))
  {
      $db->deleteSuperVisor('musharifin_candidate','idCandidate',$_GET['id']);
      
    header("location:candidate.php");
  }
}
if ($_GET['action'] == 'show') {
  require_once "include/template/forms/candidate/showCandidate.php";
}
if ($_GET['action'] == 'add') {
  require_once "include/template/forms/candidate/addCandidate.php";
}
if ($_GET['action'] == 'edit') {
  require_once "include/template/forms/candidate/editCandidate.php";
}
} else {


?>
<!-- Row start -->
<div class="row gutters">
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
  <div class="text-right mb-3">
    <!-- Button trigger modal -->
    <a href="?action=add" class="btn btn-primary">اضافة مرشح</a>
  
  </div>
</div>
</div>
<!-- Row end -->

<!-- Row start -->
<div class="row gutters">
<?php 
      $all = $db->getAll('supervisor','rank', 2, 'yes');

      if ($all) {
        foreach ($db->getAll('supervisor','rank', 2, 'yes') as $row) {
          $rowFrontend = $db->getSingleInfo('frontend','idUser',$row['id']);
       
            // $infocandidate = $db->getSingleInfo('infocandidate','idCandidate',$row['id']);

            
      if($_SERVER['HTTP_HOST'] == 'localhost')
      {
        $link = $_SERVER['HTTP_HOST'].'/electionSite/index.php?username='.$rowFrontend['nameEnglish'].'&id='.$rowFrontend['id'];

      }else{
        $link = 'https://'.$_SERVER['HTTP_HOST'].'/'.'index.php?username='.$rowFrontend['nameEnglish'].'&id='.$rowFrontend['id'];;
      }
      ?>
      <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
          <figure class="user-card">
            <figcaption>
              <a href="?action=edit&id=<?= $row['id'] ?>" class="edit-card" >
                <i class="icon-mode_edit"></i>
              </a>
              <!-- حذف --> 
              <a href="#" data-id="<?=$row['id']?>" class="delete-card deleteMorsah">
                  <i class="icon-delete"></i>
              </a>
              <a href="#" class="copylink"   data-link="<?=$link?>">
                  <i class="icon-copy"></i>
              </a>
              <?php 
              if(!empty($row['image']))
              {
                
                ?>
                  <img src="<?=$row['image']?>" alt="Wafi Admin" class="profile">

                <?php
              }else{
                ?>
                    <img src="assets/img/morsah.png" alt="Wafi Admin" class="profile">

                <?php 
              }
              ?>
              <h5><?= $row['username'] ?></h5>
              <ul class="list-group">
                <li class="list-group-item"><?= $row['email'] ?></li>
                <li class="list-group-item"><?= $row['phone'] ?></li>
              </ul>
            </figcaption>
          </figure>
        </div>
      <?php 
    }
  }
      ?>



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
$(function(){
  $(".deleteMorsah").on("click",function(){
    let con = confirm("هل تود الحذف");
    if(con)
    {
      window.location.href = `?action=delete&id=`+$(this).data("id");
    } 
  })
})
</script>