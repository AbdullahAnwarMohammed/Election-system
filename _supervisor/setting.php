<?php 
include("include/template/_header.php");
if($_SESSION['rankSuperVisor'] != 1)
{
header("location:index.php");
exit;

}


if(isset($_POST['insertAdmin']))
{
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $data = array(
        'username' => $username,
        'email' => $email,
        'password' => password_hash($password,PASSWORD_DEFAULT), 
        'rank' => '1'
    );


    if($db->checkEmail($email) === false)
    {
    $message = '<div class="alert alert-danger">هذا الايميل موجود من قبل</div>';
    }else{
        $insert = $db->insertSuperVisor('supervisor',$data);

        $message = "<div class='alert alert-success'>تم الاضافة بنجاح</div>";
        $succuess = 1;
        header("Refresh:1;url=setting.php");
    }

}

if(isset($_POST['editAdmin']))
{
    $supervisor = $db->getSingleInfo('supervisor','id',$_POST['getID']);


$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
if($_SESSION['superVisor'] == $supervisor['username'])
{
    $_SESSION['superVisor'] = $username;

}
$data = array(
    'username' => $username,
    'email' => $email,
    );

if(!empty($password))
{
    $data['password'] =  password_hash($password,PASSWORD_DEFAULT);
}

if($db->checkEmail($email,'and id != '.$_POST['getID']) === false)
{
    $message = '<div class="alert alert-danger">البريد متسخدم من قبل</div>';
}else{

    $Update = $db->update('supervisor',$data,array(
        'id'=> $_POST['getID'],
        ));
        if($Update)
        {
        
        $message = "<div class='alert alert-success'>تم تعديل البيانات بنجاح</div>";
    
        }

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
<li class="breadcrumb-item">الاعدادت</li>
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
    if ($_GET['action'] == 'add') 
    {
        require_once "include/template/forms/supervisor/addSuperVisor.php";
    }
    if ($_GET['action'] == 'edit')
    {
        require_once "include/template/forms/supervisor/editSuperVisor.php";
    }
}else{
?>


<!-- Row start -->

<!-- Row start -->
<div class="row gutters">
<button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#addNewContact">اضافة مشرف جديد</button>

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

<div class="modal fade" id="addNewContact" tabindex="-1" role="dialog" aria-labelledby="addNewContactLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">

                        <h5 class="modal-title" id="addNewContactLabel">اضافة مشرف جديد</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    <form action="setting.php" class="needs-validation"  method="POST">
            <div class="row mb-3">
                <div class="col-sm-12">
                <input type="text" minlength="5" required name="username" placeholder="الاسم" class="form-control">
                <div class="invalid-feedback">
                هذا الحقل اجباري ولا يقل عن خمسة حروف
                </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-12">
                <input type="email" required name="email" placeholder="البريد" class="form-control">
                <div class="invalid-feedback">
                هذا الحقل اجباري من نوع ايميل
                </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-12">
                <input type="password" minlength="10" required name="password" placeholder="كلمة السر" class="form-control">
                <div class="invalid-feedback">
                هذا الحقل اجباري ولا يقل عن عشرة احروف
                </div>
                </div>
            </div>
            

            

            <!-- <div class="row mb-3">
                <div class="col-sm-12">
                <button type="submit" name="add" class="btn btn-primary">اضافة <i class="ri-file-add-line"></i></button>
                </div>
            </div> -->
            <div class="modal-footer custom">
                        <div class="right-side">
                            <button  type="submit" name="insertAdmin" class="btn btn-link success btn-block">اضافة</button>
                        </div>
                        <div class="divider"></div>

                        <div class="left-side">
                            <a href="#"  class="btn btn-link danger btn-block" data-dismiss="modal">أغلاق</a>
                            <!-- <button type="submit" class="btn btn-link danger btn-block" data-dismiss="modal">Cancel</button> -->
                        </div>
                        
                    </div>
            </form><!-- End General Form Elements -->
                    </div>
                
                </div>
            </div>
        </div>


    <!-- Edit Contact Modal -->
    <div class="modal fade" id="editContact" tabindex="-1" role="dialog" aria-labelledby="editContactLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editContactLabel">Edit Contact</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                <form action="setting.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="getID" class="getID">
            <div class="row mb-3">
                <div class="col-sm-12">
                <input type="text" required  name="username"placeholder="الاسم" class="getUsername form-control">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-12">
                <input type="text" required name="email" placeholder="البريد" class="getEmail form-control">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-12">
                <input type="password" name="password" placeholder="كلمة السر الجديدة" class="form-control">
                </div>
            </div>
            


            

            <!-- <div class="row mb-3">
                <div class="col-sm-12">
                <button type="submit" name="update" class="btn btn-success">تعديل <i class="ri-check-double-line"></i> </button>
                </div>
            </div> -->

            <div class="modal-footer custom">
                
                    <div class="right-side">
                        <button type="submit" name="editAdmin" class="btn btn-link success btn-block">تعديل</button>
                    </div>
                    <div class="divider"></div>
                    <div class="left-side">
                        <a href="#"  class="btn btn-link danger btn-block" data-dismiss="modal">أغلاق</a>
                        <!-- <button type="submit" class="btn btn-link danger btn-block" data-dismiss="modal">Cancel</button> -->
                    </div>
                </div>

            </form><!-- End General Form Elements -->
                </div>
                
            </div>
        </div>
    </div>
</div>
<?php 
            $x = 0;
                
            foreach($db->getAll('supervisor','rank',1,true) as $row)
            {
                
            $x++; 
                ?>


<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
    <figure class="user-card">
        <figcaption>
            <a href="#" class="edit-card" data-email="<?=$row['email']?>" data-username="<?=$row['username']?>" data-id="<?=$row['id']?>" data-toggle="modal" data-target="#editContact">
                <i class="icon-mode_edit"></i>
            </a>
            <a href="#" data-id="<?=$row['id']?>" class="delete-card deleteSuperVisor">
                <i class="icon-delete"></i>
            </a>

            <img src="assets/img/avatar_admin.png" alt="Wafi Admin" class="profile">
            <h5><?= $row['username'] ?></h5>
            <ul class="list-group">
                <li class="list-group-item"><?= $row['email'] ?></li>
           
            </ul>
        </figcaption>
    </figure>
</div>
<?php 
            }
            ?>


</div>
<!-- Row end -->


<div class="row gutters">

<div class="col-12">
<?php 
$Setting = $db->db->prepare("SELECT * FROM settings");
$Setting->execute();
$fetch = $Setting->fetch();
?>
                                
<?php 
if($_SERVER['REQUEST_METHOD'] == 'POST')
{

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$phone = trim($_POST['mobile']);
$fb = trim($_POST['fb']);
$twitter = trim($_POST['twitter']);
$siteDesc = trim($_POST['siteDesc']);
$status = $_POST['status'];
$versions = trim($_POST['versions']);
if(!empty($_FILES['icon']['tmp_name']))
{
$upload = new upload('icon','uploads/icon/',array('jpg','png'),'600000');
if($upload->checkIsUpload())
{

if(file_exists($fetch['icon']))
{
unlink($fetch['icon']);
}
$upload->upload();
$icon = $upload->getDirection();
}

}else{
$icon = $fetch['icon'];
}


$updatSQL = $db->db->prepare("UPDATE settings SET 
siteName = ?,
siteDesc = ?,
siteEmail = ?,
phone = ?,
fb = ?,
twitter = ?,
statusVAL = ?,
icon = ?,
ver = ?
");
$updatSQL->execute([$name,$siteDesc,$email,$phone,$fb,$twitter,$status,$icon,$versions]);
header("location:setting.php");

}
?>

            <div class="card m-0">
            
                <form action="<?=$_SERVER['PHP_SELF']?>" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row gutters">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <input type="text" class="form-control" value="<?=$fetch['siteName']?>" 
                                    id="name" name="name" placeholder="Name" required="">
                            </div>
                        </div>
                        
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <input type="email" class="form-control" value="<?=$fetch['siteEmail']?>"
                                    id="email" name="email" placeholder="Email" required="">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row gutters">
                        <div class="col-12">
                            <div class="form-group">
                                <input type="number" class="form-control" id="mobile" 
                                    value="<?=$fetch['phone']?>"  name="mobile" placeholder="Mobile Number" required="">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <input type="text" class="form-control" id="subject"
                                    value="<?=$fetch['fb']?>"    name="fb" placeholder="Subject" required="">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                <input type="text" class="form-control" id="subject"
                                    value="<?=$fetch['twitter']?>"    name="twitter" placeholder="Subject" required="">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row gutters">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <textarea class="form-control" 
                                id="message" placeholder="Message" maxlength="140" name="siteDesc" rows="3"><?=$fetch['siteDesc']?></textarea>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <select name="status" class="form-control" name="active" id="">
                                <?php 
                                if($fetch['statusVAL'] == 1)
                                {
                                    ?>
                                    <option selected value="1">مفعل</option>
                                    <option value="0">غير مفعل</option>
                                    <?php 
                                }else
                                {
                                    ?>
                                    <option value="1">مفعل</option>
                                    <option selected value="0">غير مفعل</option>
                                    <?php 
                                }
                                ?>
                                
                                </select>
                            </div>

                            <div class="form-group">
                                <input type="file" class="form-control" name="icon">
                                <img src="<?=$fetch['icon']?>" width="80" class="img-thumbnail" alt="">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" value="<?=$fetch['ver']?>" placeholder="رقد الاصدار" name="versions">
                            </div>
                        </div>

                    </div>
                    
                    <button type="submit" id="submit" name="submit" class="btn btn-primary float-right">تعديل</button>

                </div>

                </form>
                
            </div>
    
</div>

</div>
<!-- Row end -->
<?php 
}
?>

</div>

</div>
<!-- Page content end -->
<?php 
include("include/template/_footer.php");
?>
<script>
$(function(){
    $(".edit-card").on("click",function(){
        let email = $(this).data("email");
        let username = $(this).data("username");
        let id = $(this).data("id");
        $(".getEmail").val(email);
        $(".getUsername").val(username);
        $(".getID").val(id);
        
    })
})
</script>