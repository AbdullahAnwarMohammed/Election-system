<?php
include("include/template/_header.php");
$frontend = $db->getSingleInfo('frontend','idUser',$_SESSION['idSuperVisor']);

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
        <li class="breadcrumb-item"><?=$frontend['event']?></li>
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
    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'showData')
        {
            $sql = "
            SELECT * FROM voters INNER JOIN vote WHERE
            vote.idSupervisor = ?
            AND 
            ( vote.level = ? OR  vote.level = ?)
            AND 
            vote.idUser = ?
            AND 
            vote.idUser = voters.id
            ";
            $get = $db->db->prepare($sql);

            $get->execute([$_SESSION['idSuperVisor'],'1','2',$_GET['id']]);
                $get->rowCount();
                $fetch = $get->fetch();
        ?>
        <div class="row gutters">
        <div class="col-8">
            <h5><?= $fetch['username']?></h5>
        </div>
        <div class="col-4 justify-content-end d-flex">
            <a class="btn btn-dark" href="madmen.php">للخلف</a>
        </div>
        <div class="col-12">
            <table class="table">
                <tr>
                <td>الاسم : <?= $fetch['username']?></td>
                </tr>
                <tr>
                <td>الهاتف : <?= $fetch['phone']?></td>
                </tr>
                <tr>
                <td>الجنس : <?= $fetch['gender'] == 1 ? 'ذكر':'انثى' ?></td>
                </tr>
                <tr>
                <td>المنطقة : <?= $fetch['areaName']?></td>
                </tr>
                <tr>
                <td>رقم القيد : <?= $fetch['raqmAlqayd']?></td>
                </tr>
                <tr>
                <td>اللجنة : <?= $fetch['allajna']?></td>
                </tr>
                <tr>
                <td>المدرسة : <?= $fetch['areaName']?></td>
                </tr>
                <tr>
                <td>القائمة : 
                <?php 
                $Item = $db->db->prepare("SELECT * FROM listcontent WHERE idUser = ? AND  idSupervisor = ?");
                $Item->execute([$fetch['idUser'],$_SESSION['idSuperVisor']]);
                if($Item->rowCount() > 0)
                {
                    foreach($Item->fetchAll() as $row)
                    {
                        $ListName = $db->db->prepare("SELECT * FROM list WHERE id = ?");
                        $ListName->execute([$row['idList']]);
                        echo '<span class="btn btn-dark btn-sm mx-1">'.$ListName->fetch()['name'].'</span>';

                    }
                }
                else 
                {
                    echo 'لا يوجد قوائم';
                }
                ?>
                </td>
                </tr>
                
            </table>
        </div>
        </div>
        <?php 
        }
    }else{
    ?>
    <div class="row gutters">
        <?php 
        $sql = "
        SELECT * FROM voters INNER JOIN vote WHERE
        vote.idSupervisor = ?
        AND 
        ( vote.level = ?
        OR 
        vote.level = ?)
        AND 
        vote.idUser = voters.id
        ";
        $get = $db->db->prepare($sql);
        $get->execute([$_SESSION['idSuperVisor'],'1','2']);
        $fetchAll = $get->fetchAll();
        $temp = array_unique(array_column($fetchAll, 'username'));
        $fetchAll = array_intersect_key($fetchAll, $temp);

        $male = 0;
        $female = 0;
        $attend = 0;
        $no_attend = 0;
        $all = count($fetchAll);
        foreach($fetchAll as $row)
        {
            if($row['gender'] == 1)
            {
                $male++;
            }else{
                $female++;
            }
            if($row['level'] == 1)
            {
                $no_attend++;

            }else{
                $attend++;
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
                            <div class="col-md-6 bg-success text-white  py-4 text-center">
                                <h4>حاضر : <?=$attend?></h4>
                            </div>
                            <div class="col-md-6  bg-danger text-white py-4  text-center">
                            <h4>غير حاضر : <?=$no_attend?></h4>

                            </div>


        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

            <div class="table-container">
                <div class="table-container">
                    <div class="t-header d-flex  justify-content-between align-items-center text-white bg-info" >
                        المضامين
                    </div>
                    <div class="table-responsive">
                    <table id="copy-print-csv" class="table custom-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم الناخب</th>
                            <th>العائلة</th>
                            <th>الحضور</th>
                            <th>الضامنين</th>
                            <!-- <th>الجنس</th>
                            <th>المنطقة</th> -->
                            <th>اللجنة</th>
                            <th>الهاتف</th>
                            
                        </tr>
                        </thead>
                        <?php 
                        
                        

                        ?>
                        <tbody>
                            <div class="data">
                                <?php 

                            if($get->rowCount() > 0)
        {
            $data = '';
        foreach($fetchAll as $row)
        {
        ?>
    
            <?php 
            $gender = $row['gender'] == 1 ? 'ذكر' : 'انثى';
            $attendOrNo = $row['level'] == 1 ? '<span class="btn btn-danger "><span style="visibility:hidden">0</span></span>':'<span  class="btn btn-success"><span style="visibility:hidden">1</span></span>';
            $changeColor = $row['gender'] == 1 ? "#062bb1" : "#c51334";

            $sql = "SELECT * FROM vote WHERE idUser = ? AND idSupervisor = ? AND ( level = ? OR level = ?)";
            $counts = $db->db->prepare($sql);
            $counts->execute([$row['idUser'],$_SESSION['idSuperVisor'],'1','2']);
            $badge = $counts->rowCount() > 1 ? "bg-info text-white" : "bg-success text-white";


        
            
            $data .='<tr>
            <td></td>
            <td  style="cursor: pointer;"><a href="?action=showData&id='.$row['idUser'].'">
            <span  style=color:'.$changeColor.'>'.$row['username'].'</span>
            </a>
            
            <span  style="visibility:hidden">'.$row['areaName'].'</span>
            <span  style="visibility:hidden">'.$gender.'</span>
            
            </td>
            <td>'.$row['familyName'].'</td>

            <td class="text-center">'.$attendOrNo.'</td>
            <td class="text-center '.$badge.'" style="cursor:pointer">
            ';
            
            
            
                
                $data .='
                <span  style="font-size:20px" data-username="'.$row['username'].'" data-id="'.$row['idUser'].'"  class="getModalMadmen badge <?=$badge?>" data-toggle="modal" data-target="#basicModal">'.$counts->rowCount().'</span>
            
                </td>

            <td>'.$row['allajna'].'</td>
            <td>'.$row['phone'].'</td>
            ';
            $data .='
            
            </tr>  
                ';
        }
        echo $data;  
        

    }
    ?>
                            </div>
                        </tbody>
                    </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php 
    }
    ?>
</div>


    <div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModalLabel" aria-hidden="true" style="display: none;">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="basicModalLabel">المضامين</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body getShowMadmen">
                                                ...
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


<!-- Page content end -->
<?php
include("include/template/_footer.php");
?>
<script>
$(function(){
    
    $(".getModalMadmen").on("click",function(){
        let idUser = $(this).data("id");
        let username = $(this).data("username")
        $.ajax({
    url:"ajax_backend.php",  
    method:"POST",  
    data:{
        action:'showMadmen',
        idUser:idUser
        
        
    },
    success:function(data){ 
        $(".getShowMadmen").html(data);
        $(".modal-title").html(username);
    }
    })
    })
    // $(".data").hide();

    
        


    
})
</script>