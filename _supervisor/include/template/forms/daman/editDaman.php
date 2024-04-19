

<?php 
  $message;
  $succuess = 0;
  $frontendData = $db->getSingleInfo('frontend','idUser',$_GET['id']);
  $Power = $db->getSingleInfo('powers','idUser',$_SESSION['idSuperVisor']);
  $PowerUserNow = $db->getSingleInfo('powers','idUser',$_GET['id']);
  if(isset($_POST['update']))
  {
  

    $username = trim($_POST['username']);
    $nameEnglish = trim($_POST['nameEnglish']);
    $phone = $_POST['phone'];

    $data = array(
      'username' => $username,
      'phone' => $phone,
      'gender' => $_POST['gender']
  );
  if($db->checkUsername($username,'and id != '.$_GET['id']) === false)        
  {
    $message = 'الاسم متسخدم من قبل';
  }
  else if(!preg_match('/^[a-zA-Z.0-9]+$/',$nameEnglish))
  {
    $message = ' يرجي استخدام الاحرف الانجليزية  وعلامة  نقطة فقط(.)';
  }else if($db->checkNameEnglish($nameEnglish,'and idUser !='.$_GET['id']) === false)
  {
   $message = 'الاسم الانجليزي متسخدم من قبل';
  }
  else{
    $DamanNameOld = $db->getSingleInfo('daman','id',$_GET['id']);


    $db->update('daman',$data,array(
      'id'=> $_GET['id'],
    ));
      
     $db->update('frontend',array(
      'username' => $username,
      'nameEnglish' => trim($_POST['nameEnglish'])
    ),array(
      'idUser' => $_GET['id'],
      'username' => $DamanNameOld['username']
    ));

    $create_daman = isset($_POST['create_daman']) ? $_POST['create_daman'] : 0;
    $select_daman = isset($_POST['select_daman']) ? $_POST['select_daman'] : 0;
    $cancel_madmen = isset($_POST['cancel_madmen']) ? $_POST['cancel_madmen'] : 0;
    $prepare_madmen = isset($_POST['prepare_madmen']) ? $_POST['prepare_madmen'] : 0;
    $create_list = isset($_POST['create_list']) ? $_POST['create_list'] : 0;

    $powerUpdate = $db->db->prepare("UPDATE `powers`
    SET 
    create_daman = ?,
    select_daman = ?,
    cancel_madmen = ?,
    prepare_madmen = ?,
    create_list = ?
    WHERE 
    idUser = ?
    ");
    $powerUpdate->execute([$create_daman,$select_daman,$cancel_madmen,$prepare_madmen,$create_list,$_GET['id']]);
    
    $message = 'تم التعديل بنجاح';
    $succuess = 1;
    header("Refresh:3; url=?action=edit&master=".$_GET['master'].'&id='.$_GET['id']);

  }

  }
  ?>

<main id="main" class="main">
   
<?php 

?>
<?= !empty($upload->_errors) ? '<div class="alert alert-warning">'.$upload->_errors.'</div>' : null ?>
<div class="row">
<div class="col-12">
<div class="d-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">تعديل بيانات</h1>

  <a href="daman.php?master=<?=$_SESSION['idSuperVisor']?>">
      <img src="assets/img/back.png" />
  </a>
</div>
</div>

<?php 

$damanInfo = $db->getSingleInfo('daman','id',$_GET['id']);

?>
<div class="col-lg-12">

          <div class="card">
            <div class="card-body">

            <?php
              if(!empty($message) && $succuess == 1)
              {
                ?>
                <div class="alert alert-success"><?=$message?></div>
                <?php 
              }
              if(!empty($message) && $succuess == 0)
              {
                ?>
                <div class="alert alert-danger"><?=$message?></div>
                <?php 
              }
              ?>

              <form action="<?=$_SERVER['PHP_SELF']?>?action=edit&master=<?=$_GET['master']?>&id=<?=$damanInfo['id']?>" method="POST" enctype="multipart/form-data">
              <div class="row mb-3">
                  <div class="col-sm-12">
                    <input type="username" required value="<?=$damanInfo['username']?>" name="username" placeholder="الاسم" class="form-control">
                  </div>
                  </div>

                   <div class="row mb-3">
                  <div class="col-sm-12">
                  <ul>
                      <li class="text-danger"> في الاسم الانجليزي غير مسموح بالرموز و المسافات ماعدا (.)</li>
                    </ul>
                    <input type="text" required name="nameEnglish" value="<?=$frontendData['nameEnglish']?>" placeholder="الاسم بالانجليزي" class="form-control">
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-sm-12">
                    <input type="text" readonly  value="رقم المُتعهد : <?=$frontendData['id']?>" class="form-control">
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-sm-12">
                    <input type="number" required value="<?=$damanInfo['phone']?>" name="phone" placeholder="رقم الهاتف" class="form-control">
                  </div>
                  </div>

                  <div class="row mb-3">
                    <div class="col-sm-12">
                      <select name="gender"  class="form-control">
                        <?php 
                        if($damanInfo['gender'] == 0)
                        {
                          ?>
                           <option value="0" selected>ذكر</option>
                      <option value="1">انثى</option>
                          <?php 
                        }
                        else 
                        {
                          ?>
                           <option value="0">ذكر</option>
                      <option value="1" selected>انثى</option>
                          <?php 
                        }
                        ?>
                     
                      </select>
                    </div>
                </div>
            
               
                <div class="row mb-3">
                
               
                 
                   <?php 
                  if($Power['select_daman'] == 1)
                  {
                    ?>
               <div class="form-check form-check-inline">
                      <input class="form-check-input" <?=$PowerUserNow['select_daman'] == 1 ? 'checked':''?> type="checkbox" name="select_daman" id="inlineCheckbox1" value="1">
                      <label class="form-check-label" for="inlineCheckbox1">تحديد مضامين</label>
                      </div>
                    <?php 
                  }else{
                    ?>
<div class="form-check form-check-inline">
                      <input disabled disabled class="form-check-input" type="checkbox" name="select_daman" id="inlineCheckbox1" value="1">
                      <label class="form-check-label" for="inlineCheckbox1">تحديد مضامين</label>
                      </div>
                    <?php 
                  }
                  ?>
                   <?php 
                  if($Power['cancel_madmen'] == 1)
                  {
                    ?>
               <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox"  <?=$PowerUserNow['cancel_madmen'] == 1 ? 'checked':''?>  name="cancel_madmen" id="inlineCheckbox1" value="1">
                      <label class="form-check-label" for="inlineCheckbox1">الغاء مضامين</label>
                      </div>
                    <?php 
                  }else
                  {
                    ?>
          <div class="form-check form-check-inline">
                      <input  disabled class="form-check-input"  type="checkbox" name="cancel_madmen" id="inlineCheckbox1" value="1">
                      <label class="form-check-label" for="inlineCheckbox1">الغاء مضامين</label>
                      </div>
                    <?php 
                  }
                  ?>
                   <?php 
                  if($Power['prepare_madmen'] == 1)
                  {
                    ?>
                <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox"  <?=$PowerUserNow['prepare_madmen'] == 1 ? 'checked':''?> name="prepare_madmen" id="inlineCheckbox1" value="1">
                      
                      <label class="form-check-label" for="inlineCheckbox1">تحضير
                      
                      </label>
                      </div>
                    <?php 
                  }else{
                    ?>
  <div class="form-check form-check-inline">
                      <input disabled class="form-check-input"  type="checkbox" name="prepare_madmen" id="inlineCheckbox1" value="1">
                      <label class="form-check-label" for="inlineCheckbox1">تحضير</label>
                      </div>
                    <?php 
                  }
                  ?>
                   <?php 
                  if($Power['create_list'] == 1)
                  {
                    ?>
                  <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox" <?=$PowerUserNow['create_list'] == 1 ? 'checked':''?> name="create_list" id="inlineCheckbox1" value="1">
                      <label class="form-check-label" for="inlineCheckbox1">انشاء قائمة</label>
                      </div>
                    <?php 
                  }else{

                    ?>
<div class="form-check form-check-inline">
                      <input disabled class="form-check-input"  type="checkbox" name="create_list" id="inlineCheckbox1" value="1">
                      <label class="form-check-label" for="inlineCheckbox1">انشاء قائمة</label>
                      </div>
                    <?php 
                  }
                  ?>

                 </div>
               

                
              
                

                <div class="row mb-3">
                  <div class="col-sm-12">
                    <button type="submit" name="update" class="btn btn-success btn-lg">تعديل</button>
                  </div>
                </div>

              </form><!-- End General Form Elements -->

            </div>
          </div>

        </div>
        </div>
</main>
