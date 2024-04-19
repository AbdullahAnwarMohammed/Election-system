<?php 
include("include/template/_header.php");
$message = '';
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    
    $info = $db->getSingleInfo('supervisor','id',$_SESSION['idSuperVisor']);
    $oldPassword = trim($_POST['oldPassword']);
    $newPassword = trim($_POST['newPassword']);
    $repeatPassword = trim($_POST['repeatPassword']);
    if(!password_verify($oldPassword,$info['password']))
    {
        $message = "<div class='alert alert-danger'>
        كلمة المرور خطأ
        </div>";
    }else if($newPassword != $repeatPassword)
    {
        $message = "<div class='alert alert-danger'>
        كلمة المرور غير متسأوية
        </div>"; 
    }else{
        if($_SESSION['rankSuperVisor'] == 3)
        {
            $db->update('supervisor',array(
                'password'=>password_hash($newPassword,PASSWORD_DEFAULT)
            )
            ,array(
                'id' =>$_SESSION['idSuperVisor']
            )
            );

            $db->update('passwords',array(
                'password'=>$newPassword
            )
            ,array(
                'id_user' =>$_SESSION['idSuperVisor']
            )
            );
        
        }else{
            $db->update('supervisor',array(
                'password'=>password_hash($newPassword,PASSWORD_DEFAULT)
            )
            ,array(
                'id' =>$_SESSION['idSuperVisor']
            )
            );

            $db->update('passwords',array(
                'password'=>$newPassword
            )
            ,array(
                'id_user' =>$_SESSION['idSuperVisor']
            )
            );
            
            
        }
        $message = "<div class='alert alert-success'>
        تم تغير كلمة السر  
        </div>";
    }
}

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
                <li class="breadcrumb-item">الرئيسية</li>
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
        <h1 class="h3 mb-0 text-gray-800">تغير كلمة السر</h1>

        <a href="index.php" class="btn btn-md btn-dark shadow-sm">  الرئيسية</a>

        
            </div>
    <div class="row">

    <div class="col-12">
    <?php
        if(!empty($message))
        {
            ?>
            <div class="col-12"><?=$message?></div>

            <?php
        }
        ?>

    <div class="card">
            <div class="card-body py-3">
              <form action="<?=$_SERVER['PHP_SELF']?>" method="POST" >
                  
                <div class="row mb-3">
                  <div class="col-sm-12">
                    <input type="password" required name="oldPassword" placeholder="كلمة السر القديمة" class="form-control">
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-12">
                    <input type="password" required name="newPassword" placeholder="كلمة السر الجديدة" class="form-control">
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-12">
                    <input type="password" required name="repeatPassword" placeholder="تكرار كلمة السر" class="form-control">
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-12">
                    <button type="submit" name="update" class="btn btn-primary">تعديل <i class="ri-check-double-line"></i></button>
                  </div>
                </div>

              </form><!-- End General Form Elements -->

            </div>
          </div>
    </div>
    </div>

        </div>
        <!-- Main container end -->

    </div>
    <!-- Page content end -->
    <?php 
        include("include/template/_footer.php");
        ?>