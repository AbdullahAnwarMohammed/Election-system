<?php 
include("include/template/_header.php");
?>

<!-- Sidebar wrapper start -->
<?php  include("include/template/_sidebar.php"); ?>

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
        <li class="breadcrumb-item">دليل المستخدم</li>
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

<div class="d-flex align-items-center justify-content-between mb-4">

<h1 class="h3 mb-0 text-gray-800">دليل المستخدم</h1>

</div>

<div class="row">
            <?php 
            $msg = '';
            if($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                $candidate = trim($_POST['candidate']);
                $contractor = trim($_POST['contractor']);
                $guarantor = trim($_POST['guarantor']);
                $representative = trim($_POST['representative']);
                $Update = $db->db->prepare(
                    "
                    UPDATE user_guide 
                    SET 
                    candidate = ?,
                    contractor = ?,
                    guarantor = ?,
                    representative = ?
                    "
                );
                $Update->execute([$candidate,$contractor,$guarantor,$representative]);
                if($Update)
                {
                    $msg = 'تم التعديل بنجاح';
                }
            }
            ?>
            <form action="user_guide.php" method="POST" style="width: 100%;">
            <?php 
            $fetch = $db->db->prepare("SELECT * FROM user_guide");
            $fetch->execute();
            $data = $fetch->fetch();
            if(!empty($msg))
            {
                ?>
                <div class='alert alert-success'><?=$msg?></div>
                <?php 
            }
            ?>
                <div class="form-group">

            
                    <label for="exampleInputEmail1">دليل المرشح</label>
                    <input type="url" value="<?=$data['candidate']?>" name="candidate" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" >
                </div>
                <div class="form-group">
                <label for="exampleInputEmail1">دليل المُتعد</label>
                    <input type="url"  value="<?=$data['contractor']?>"   name="contractor" class="form-control" id="exampleInputPassword1" >
                </div>
                <div class="form-group">
                <label for="exampleInputEmail1">دليل الضامن</label>
                    <input type="url"  value="<?=$data['guarantor']?>"  name="guarantor" class="form-control" id="exampleInputPassword1" >
                </div>
                <div class="form-group">
                <label for="exampleInputEmail1">دليل المندوب</label>
                    <input type="url"  value="<?=$data['representative']?>"   name="representative" class="form-control" id="exampleInputPassword1" >
                </div>
                
                <button type="submit" class="btn  btn-primary btn-success">تعديل</button>
            </form>
                
            </div>
            

</div>
<!-- Main container end -->

</div>
<!-- Page content end -->
<?php 
include("include/template/_footer.php");
?>