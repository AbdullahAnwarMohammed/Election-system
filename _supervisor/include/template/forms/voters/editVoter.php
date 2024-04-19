<?php 

if(isset($_POST['editVote']))
{
   $fullName = $_POST['fullName'];
   $gender = $_POST['gender'];
   $raqmAlqayd = $_POST['raqmAlqayd'];
   $nationalityNumber = $_POST['nationalityNumber'];
   $familyName = $_POST['familyName'];
   $areaName = $_POST['areaName'];
   $raqmAlsunduq = $_POST['raqmAlsunduq'];
   $phone = $_POST['phone'];
   $allajna = $_POST['allajna'];
   $stmt = $db->db->prepare("update voters set
   fullName = ?,
   gender = ? ,
   raqmAlqayd = ? ,
   nationalityNumber = ? ,
   familyName = ? ,
   areaName = ? ,
   raqmAlsunduq = ? ,
   phone = ? ,
   allajna = ? 
where 
    id = ?
   ");
   $stmt->execute([$fullName,$gender,$raqmAlqayd,$nationalityNumber,$familyName,$areaName,$raqmAlsunduq,$phone,$allajna,$_GET['id']]);
    $message = 'تم تعديل البيانات بنجاح';
   // header("location:?action=edit&id=".$_GET['id']."&idEvent=".$_GET['idEvent']);
}
$info = $db->getSingleInfo('voters','id',$_GET['id']);
?>
<?php 
if(!empty($message))
{
    echo '<div class="alert alert-success">'.$message.'</div>';
}
?>
<form action="?action=edit&id=<?=$_GET['id']?>&idEvent=<?=$_GET['idEvent']?>" method="POST">
    <div class="form-group">
        <label for="">الاسم</label>
        <input type="text" value="<?=$info['fullName']?>" required name="fullName" class="form-control">
    </div>
    <div class="form-group">
        <label for="">الجنس</label>
        <select name="gender" id="" class="form-control">
         
            <option value="1" <?=$info['gender'] == 1 ? "selected" : ""?>>ذكر</option>
            <option value="2" <?=$info['gender'] == 2 ? "selected" : ""?>>انثى</option>
        </select>
    </div>
    <div class="form-group">
        <label for="">رقم القيد</label>
        <input type="text" value="<?=$info['raqmAlqayd']?>" name="raqmAlqayd" class="form-control">
    </div>
    <div class="form-group">
        <label for="">رقم الجنسية</label>

        <input type="text" value="<?=$info['nationalityNumber']?>"  name="nationalityNumber" class="form-control">
    </div>
    <div class="form-group">
        <label for="">العائلة</label>
        <input type="text" class="form-control" name="familyName" value="<?=$info['familyName']?>">
      
    </div>
    <div class="form-group">
        <label for="">المنطقة</label>
        <?php 
        $familes = $db->db->prepare("select DISTINCT(areaName) from voters where areaName is not null and idEvent = ?");
        $familes->execute([$_GET['idEvent']]);
     
        ?>
        
        <select name="areaName" class="form-control" id="">
        <?php 
           foreach($familes->fetchAll() as $row)
           {
               ?>
          <option value="<?=$row['areaName']?>" <?=$row['areaName'] == $info['areaName'] ? "selected" : ""?>><?=$row['areaName']?></option>

               <?php
           }
        ?>
        </select>

    </div>
    <div class="form-group">
         <label for="">رقم الصندوق</label>

        <input type="text" value="<?=$info['raqmAlsunduq']?>" name="raqmAlsunduq" class="form-control">
    </div>
    <div class="form-group">
         <label for="">رقم الهاتف</label>
        <input type="text"  value="<?=$info['phone']?>" name="phone" class="form-control">
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
          <option value="<?=$row['allajna']?>" <?=$row['allajna'] == $info['allajna'] ? "selected" : ""?>><?=$row['allajna']?></option>

               <?php
           }
        ?>
        </select>
    </div>
    <div class="form-group">
        <input type="submit" name="editVote" class="btn btn-info" value="حفظ">
        <a href="voters.php?id=<?=$_GET['idEvent']?>" class="btn btn-info">الناخبين</a>
    </div>
</form>