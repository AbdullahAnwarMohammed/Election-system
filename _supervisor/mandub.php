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

<div class="main-container">
<?php
if (isset($_GET['action'])) {
    if($_GET['action'] == 'delete')
    {
        // عملية الحذف
        $delOne = $db->db->prepare("DELETE FROM supervisor WHERE id = ?");
        $delOne->execute([$_GET['id']]);

        header("location:mandub.php");

    }
    if ($_GET['action'] == 'add') {
        require_once "include/template/forms/mandub/addMandub.php";
    }
    if ($_GET['action'] == 'show') {
        require_once "include/template/forms/mandub/showMandub.php";
    }
    if ($_GET['action'] == 'edit') {
        require_once "include/template/forms/mandub/editMandub.php";
    }
} else {
?>
    <div class="row gutters">
        <?php 
            $male = 0;
            $female = 0;
        if(!empty($db->getAll('supervisor','rank',4,'yes'))){
            $data = $db->innerJoin('supervisor','supervisor.*,relationship_mandob.id_mandob '
            ,'relationship_mandob',
            'supervisor.id = relationship_mandob.id_mandob AND relationship_mandob.id_supervisor 
            = '.  $_SESSION['idSuperVisor'] .' AND supervisor.rank = 4');
            
            if(!empty($data)){

   
            $all = count($data);
            foreach($data as $row)
            {
                
                if($row['gender'] == 0)
                {
                    $male++;
                }else{
                    $female++;
                }
                
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
                    <div class="t-header d-flex  justify-content-between align-items-center text-white" style="background-color: #008dd1;">
                        المحضرون
                        <a href="?action=add" class="btn btn-success">اضافة</a>
                    </div>

                    <div class="table-responsive">
                        <table id="basicExample" class="table custom-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>اللجنه</th>
                                    <th>رقم الهاتف</th>
                                    <th>اعدادت</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

            if($db->getAll('supervisor','rank',4,'yes')){
                $data = $db->innerJoin('supervisor','supervisor.*,relationship_mandob.id_mandob '
                ,'relationship_mandob',
                'supervisor.id = relationship_mandob.id_mandob AND relationship_mandob.id_supervisor 
                = '.  $_SESSION['idSuperVisor'] .' AND supervisor.rank = 4');

                                    $x = 0;
                                    if(!empty($data)){

                                
                                    foreach ($data as $row) {
                                        // $usernameMosraf = $Supervisor->getSingleInfo('id', $row['idSuperviosr']);
                                        // $idSuperVisor = $frontend->getSingleInfo('idUser', $row['id'])['parent'];
                                        // $SuperVisorData = $Supervisor->getSingleInfo('id', $idSuperVisor);
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
                                            
                                            <td><a target="_blank" href="<?=$link?>" style="color:<?=$changeColor?>"><?= $row['username'] ?></a></td>
                                            
                                            <td>
                                                
                                             <?php 
                                            $stmt = $db->db->prepare("SELECT * FROM allajnas WHERE id_user = ?");
                                            $stmt->execute([$row['id']]);
                                            $fetch = $stmt->fetch();
                                            if($fetch['value_allajna'] == 0)
                                            {
                                                echo 'الكل';
                                            }else{
                                              echo $fetch['value_allajna'];
                                            }
                                             ?>
                                                
                                            </td>
                                            <td>
                                                    <?=$row['phone']?>
                        <a href="tel:+965<?=$row['phone']?>"><img   width='20'src="assets/img/telephone.png" /></a></a>
                        
                    <a href="https://wa.me/+965<?=$row['phone']?>"><img width='20' src="assets/img/whatsapp.png" /></a>
                                                
                                            </td>

                                            <td>
                                <a href="mandub.php?action=edit&id=<?=$row['id']?>" class="edit-card">
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

    <?php
                                }
                            }
    ?>
        </div>
        <!-- Row end -->

    </div>

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