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
           
        </div>
        <!-- Page header end -->
        
        <div class="main-container">
        <?php 
        if(isset($_POST['add']))
        {
            $name = trim($_POST['name']);
            $stmt = $db->db->prepare("insert into order_to(`name`) VALUES(?)");
            $add = $stmt->execute([$name]);
            if($add)
            {
                ?>
                <div class="alert alert-success">تم الاضافة بنجاح</div>
                <?php
            }
        }

        if(isset($_POST['update']))
        {
            $id = intval($_GET['id']);
            $name = trim($_POST['name']);
            $stmt = $db->db->prepare("update order_to set name = ? where id = ?");
            if($stmt->execute([$name,$id]))
            {
                ?>
                <div class="alert alert-success">تم تغير البيانات بنجاح</div>
                <?php
            }
        }

        ?>
<!-- Row start -->
<div class="row gutters">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="text-right mb-3">
            <!-- Button trigger modal -->
            <?php 
              if(isset($_GET['action']))
              {
                ?>
                <a href="direct_to.php" class="btn btn-dark">للخلف</a>
                <?php
              }
              else 
              {
                ?>
                <a href="?action=insert" class="btn btn-primary">اضافة</a>
                <?php
              }
            ?>

        </div>
    </div>
</div>
<!-- Row end -->

<!-- Row start -->
<div class="row gutters">
   <?php 
    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'insert')
        {
            ?>
              <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
                <h2 class="my-4">ادخال بيانات جديدة</h2>
               <div class="form-group">
               <label for="">الاسم</label>
                <input type="text" name="name" require class="form-control">
               </div>
               <button type="submit" name="add" class="btn btn-success">انشاء</button>
             </form>
            <?php 
        }
        if($_GET['action'] == 'delete')
        {
            $id = intval($_GET['id']);
            $stmt = $db->db->prepare("delete from order_to where id = ?");
            $delete = $stmt->execute([$id]);
            if($delete)
            {
                ?>
                <div class="col-12">
                <div class="alert alert-success">تمت عملية الحذف بنجاح</div>
                </div>
                <?php
            }
        }
        if($_GET['action'] == 'edit')
        {
            $id = intval($_GET['id']);
            $stmt = $db->db->prepare("select * from order_to where id = ?");
            $stmt->execute([$id]);
            $fetch = $stmt->fetch();
            ?>
            <form  action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
                <div class="form-group">
                    <label for="">الاسم</label>
                    <input type="text" name="name" value="<?=$fetch['name']?>" class="form-control">
                </div>
                <input type="submit" name="update" value="تعديل" class="btn btn-success"> 
            </form>
            <?php 
        }
    }
    else 
    {
        ?>

            <?php 
            $stmt = $db->db->prepare("select * from order_to");
            $stmt->execute();
            if($stmt->rowCount()){
                ?>
            <table class="table">
            <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>الحالة</th>
            </tr>
            <?php 
            foreach($stmt->fetchAll() as $row)
            {
                ?>
                <tr>
                    <td>1</td>
                    <td><?=$row['name']?></td>
                    <td>
                        <a onclick="return confirm('سوف تقوم بعملية الحذف ؟ ');"  href="?action=delete&id=<?=$row['id']?>" class="btn btn-danger">حذف</a>
                        <a href="?action=edit&id=<?=$row['id']?>" class="btn btn-success">تعديل</a>
                    </td>
                </tr>
                <?php
            }
            ?>
           
        </table>
                <?php 
            }else{
                ?>
                <div class="col-12">
                <div class="alert alert-info">لا يوجد بيانات</div>
                </div>
                <?php
            }
            ?>
           

  
        <?php 
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