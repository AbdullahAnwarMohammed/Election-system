

<?php 
  $message;
  $succuess = 0;
  $frontendData = $db->getSingleInfo('frontend','idUser',$_GET['id']);

  if(isset($_POST['update']))
  {
  

    $username = trim($_POST['username']);
    $nameEnglish = trim($_POST['nameEnglish']);
    $phone = $_POST['phone'];
    $active = $_POST['active'];
    $data = array(
      'username' => $username,    
      'phone' => $phone,
      'gender' => $_POST['gender'],
      'active' => $active 
   );

  if($db->checkUsername($username,'and id != '.$_GET['id']) === false)        
  {
    $message = 'الاسم متسخدم من قبل';
  }else if(!preg_match('/^[a-zA-Z.0-9]+$/',$nameEnglish))
  {
    $message = ' يرجي استخدام الاحرف الانجليزية  وعلامة  نقطة فقط(.)';
  }
  else if($db->checkNameEnglish($nameEnglish,'and idUser !='.$_GET['id']) === false)
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

    $db->update('powers',array(
      'create_daman' => $create_daman,
      'select_daman' =>  $select_daman ,
      'cancel_madmen' => $cancel_madmen,
      'prepare_madmen' =>  $prepare_madmen,
      'create_list' =>  $create_list
    ),
    array(
      'idUser' => $_GET['id']
    )
    );
    $message = "تم التعديل بنجاح";
    $succuess = 1;
    header("Refresh:3; url=?action=edit&master=".$_GET['master']."&id=".$_GET['id']);

  }
  }
  ?>

   
<?php 

?>
<?= !empty($upload->_errors) ? '<div class="alert alert-warning">'.$upload->_errors.'</div>' : null ?>
<div class="row">


<?php 

$damanInfo = $db->getSingleInfo('daman','id',$_GET['id']);

?>
<div class="col-12">
<div class="d-flex align-items-center justify-content-between mb-4">
      
      <h1 class="h3 mb-0 text-gray-800">تعديل مُتعهد </h1>
      <a href="show_damans.php?master=<?=$_GET['master']?>"  >
        <img src="assets/img/back.png" />
      </a>
  </div>
</div>
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
                    <!-- <div class="col-sm-12">
                       
                    <select class="form-control" name="idMusharif">

                    
                        $query = "
                        SELECT * FROM musharifin_candidate WHERE idCandidate = ?
                        ";
                        $stmt = $db->db->prepare($query);
                        $stmt->execute([$_GET['master']]);
                       foreach($stmt->fetchAll() as $row)
                        {
                            $supervisor = $Supervisor->getSingleInfo('id',$row['idMusharifin']);
                         
                            if($damanInfo['idMusharif'] == $row['idMusharifin']){
                            
                                <option selected value="$supervisor['id']">$supervisor['username']</option>
                            

                            }else{
                               
                                <option  value="$supervisor['id']?>">$supervisor['username']?></option>
                               
                            }
                         
                        }
                        ?>
                    </select>
                    </div> -->
                </div>
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
            
               
                <!-- <div class="row mb-3">
                  <div class="col-sm-12">
                    <input type="email" required name="email"   value="<$damanInfo['email']?>"  placeholder="البريد" class="form-control">
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-12">
                    <input type="phone" name="phone"  value="<$damanInfo['phone']?>"   placeholder="رقم الهاتف" class="form-control">
                  </div>
                </div> -->
               
                <?php 
                $power = $db->getSingleInfo('powers','idUser',$_GET['id']);
                ?>

                <div class="form-check form-check-inline">
                <?php 
                 if($power['select_daman'] == 1)
                 {
                   ?>
                <input class="form-check-input" type="checkbox" checked name="select_daman" id="inlineCheckbox2" value="1">
 
                   <?php 
                 }else{
                   ?>
                <input class="form-check-input" type="checkbox" name="select_daman" id="inlineCheckbox2" value="1">
 
                   <?php 
                 }
                ?>
                <label class="form-check-label" for="inlineCheckbox2">تحديد مضامين</label>
                </div>
                <div class="form-check form-check-inline">
                <?php 
                 if($power['cancel_madmen'] == 1)
                 {
                   ?>
                <input class="form-check-input" type="checkbox" checked name="cancel_madmen" id="inlineCheckbox3" value="1" >
 
                   <?php 
                 }else{
                   ?>
                <input class="form-check-input" type="checkbox" name="cancel_madmen" id="inlineCheckbox3" value="1" >
 
                   <?php 
                 }
                ?>
                <label class="form-check-label" for="inlineCheckbox3">الغاء مضامين</label>
                </div>
                <div class="form-check form-check-inline">
                <?php 
                 if($power['prepare_madmen'] == 1)
                 {
                   ?>
                <input class="form-check-input" type="checkbox" checked name="prepare_madmen" id="inlineCheckbox3" value="1" >
 
                   <?php 
                 }else{
                   ?>
                <input class="form-check-input" type="checkbox" name="prepare_madmen" id="inlineCheckbox3" value="1" >
 
                   <?php 
                 }
                ?>
                <label class="form-check-label" for="inlineCheckbox3">تحضير</label>
                </div>
                <div class="form-check form-check-inline">
                <?php 
                 if($power['create_list'] == 1)
                 {
                   ?>
                <input class="form-check-input" type="checkbox" checked name="create_list" id="inlineCheckbox3" value="1" >
 
                   <?php 
                 }else{
                   ?>
                <input class="form-check-input" type="checkbox" name="create_list" id="inlineCheckbox3" value="1" >
 
                   <?php 
                 }
                ?>
                <label class="form-check-label" for="inlineCheckbox3">انشاء قوائم</label>
                </div>

                
                <div class="row mb-3">
                 <select name="active"  class="form-control">
                  <?php 
                  if($damanInfo['active'] == 1)
                  {
                    ?>
                         <option value="1" selected>مفعل</option>
                         <option value="0">غير مفعل</option>
                    <?php 
                  }
                  else 
                  {
                    ?>
                    <option value="1">مفعل</option>
                    <option value="0" selected>غير مفعل</option>
               <?php 
                  }
                  ?>
            
                 </select>
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
