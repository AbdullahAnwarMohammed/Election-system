<?php 

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
   $fullName = $_POST['fullName'];
   $gender = $_POST['gender'];
   $raqmAlqayd = $_POST['raqmAlqayd'];
   $nationalityNumber = $_POST['nationalityNumber'];
   $familyName = $_POST['familyName'];
   $areaName = $_POST['areaName'];
   $raqmAlsunduq = filter_var((int)$_POST['raqmAlsunduq'],FILTER_SANITIZE_NUMBER_INT);
   $phone = $_POST['phone'];
   $allajna = $_POST['allajna'];
    try{
           $stmt = $db->db->prepare("insert into voters(`idEvent`, `fullName`, `gender`, `raqmAlqayd`, `nationalityNumber`, `familyName`, `areaName`, `raqmAlsunduq`, `phone`, `allajna`)
    values(?,?,?,?,?,?,?,?,?,?)");
   $stmt->execute([$_GET['id'],$fullName,$gender,$raqmAlqayd,$nationalityNumber,$familyName,$areaName,$raqmAlsunduq,$phone,$allajna]);
   $message = 'تم اضافة البيانات بنجاح';

    }catch(PDOException $e)
    {
        echo $e->getMessage();
    }
}
$info = $db->getSingleInfo('voters','id',$_GET['id']);

?>
<?php 
if(!empty($message))
{
    echo '<div class="alert alert-success">'.$message.'</div>';
}
?>
<form action="<?=$_SERVER['PHP_SELF']?>?action=add&id=<?=$_GET['id']?>" method="POST">
    <div class="form-group">
        <label for="">الاسم</label>
        <input type="text" required name="fullName" class="form-control">
    </div>
    <div class="form-group">
        <label for="">الجنس</label>
        <select name="gender" id="" class="form-control">
         
            <option value="1">ذكر</option>
            <option value="2" >انتى</option>
        </select>
    </div>

    <div class="form-group">
        <label for="">رقم القيد</label>
        <input type="number" name="raqmAlqayd" class="form-control">
    </div>
 

    <div class="form-group">
        <label for="">رقم الجنسية</label>

        <input type="text" name="nationalityNumber" class="form-control">
    </div>
    <div class="form-group">
        <label for="">العائلة</label>
        <input type="text" class="form-control" name="familyName" >
      

    </div>
    <div class="form-group">
        <label for="">المنطقة</label>
        <?php 
        $familes = $db->db->prepare("select DISTINCT(areaName) from voters where areaName is not null");
        $familes->execute();
     
        ?>
        
        <select name="areaName" class="form-control" id="">
        <?php 
           foreach($familes->fetchAll() as $row)
           {
               ?>
          <option value="<?=$row['areaName']?>"><?=$row['areaName']?></option>

               <?php
           }
        ?>
        </select>
    </div>
    <div class="form-group">
         <label for="">رقم الصندوق</label>

        <input type="text" name="raqmAlsunduq" class="form-control">
    </div>
    <div class="form-group">
         <label for="">رقم الهاتف</label>
        <input type="text" name="phone" class="form-control">
    </div>
    <div class="form-group">
         <label for="">اللجنة</label>
         <?php 
        $familes = $db->db->prepare("select DISTINCT(allajna) from voters where allajna is not null");
        $familes->execute();
     
        ?>
            <select name="allajna" class="form-control" id="">
        <?php 
           foreach($familes->fetchAll() as $row)
           {
               ?>
          <option value="<?=$row['allajna']?>" ><?=$row['allajna']?></option>

               <?php
           }
        ?>
        </select>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-info" value="حفظ">
        <a href="voters.php?id=<?=$_GET['id']?>" class="btn btn-info">الناخبين</a>
    </div>
</form>