<?php 
    $message;
    $succuess = 0;
    $frontendData = $db->getSingleInfo('frontend','idUser',$_GET['id']);

    

    
    if(isset($_POST['update']))
    {

      $username = $_POST['username'];
      $data = array(
        'username' => $username,
        'phone' => $_POST['phone'],
        'active' => $_POST['active'],
        'gender' => $_POST['gender']
      );
      $nameEnglish = trim($_POST['nameEnglish']);


    if($db->checkUsername($username,'','and id != '.$_GET['id']) === false)        
    { 
      $message = 'الاسم   العربي متسخدم من قبل';
    }else if(!preg_match('/^[a-zA-Z.0-9]+$/',$nameEnglish)){
      $message = ' يرجي استخدام الاحرف الانجليزية  وعلامة  نقطة فقط(.)';
    }
    else if($db->checkNameEnglish($nameEnglish,'and idUser !='.$_GET['id']) === false)
    {
     $message = 'الاسم الانجليزي متسخدم من قبل';
    }
    else{

      $allajnas = $db->db->prepare("UPDATE allajnas SET value_allajna = ? WHERE id_user = ?");
      $allajnas->execute([$_POST['allajnas'],$_GET['id']]);

      $db->update('frontend',array(
        'username' => $username,
        'nameEnglish' => trim($_POST['nameEnglish'])

        ),array(
        'idUser' => $_GET['id']
      ));
    
    
      
      $db->update('powermandob',array(
        'power' => $_POST['powersMandob']
      ),array(
        'id_mandob' => $_GET['id']
      ));


      $db->update('supervisor',$data,array(
      'id'=> $_GET['id'],
      ));

      $message = 'تم التعديل بنجاح';
      $succuess = 1;
      header("Refresh:3; url=?action=edit&id=".$_GET['id']);
  

    }

}


    ?>
<?= !empty($upload->_errors) ? '<div class="alert alert-warning">'.$upload->_errors.'</div>' : null ?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"> المحضر (تعديل) </h1>

  <a href="mandub.php">
      <img src="assets/img/back.png" />
  </a>
</div>


<div class="col-lg-12">
<?php 
                     $single = $db->getSingleInfo('supervisor','id',$_GET['id']);
                     $singlePower = $db->getSingleInfo('powermandob','id_mandob',$_GET['id']);
?>
          <div class="card">
            <div class="card-body py-3">
              <form action="<?=$_SERVER['PHP_SELF']?>?action=edit&id=<?=$single['id']?>" method="POST" enctype="multipart/form-data">
              
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
              <!-- <div class="row mb-3"> -->
              <!-- <div class="col-sm-12">
                    <label for="حدث">اسم المربح التابه له</label>
         

                    <select name="idCandidate" required class="form-control">
                   

                        foreach($Supervisor->getAll('rank',2,'yes') as $row)
                        {
                            
                            ?>
                            <option value="<$row['id']?>"><$row['username']?></option>
                
                        }
                        ?>
                    </select>
                  </div>  
              </div>  -->
              <div class="row mb-3">
                  <div class="col-sm-12">
                    <input type="text" name="username" value="<?=$single['username']?>" placeholder="الاسم" class="form-control">
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-12">
                  <ul>
                      <li class="text-danger">  الاسم الانجليزي غير مسموح  بالرموز و المسافات ماعدا (.)</li>
                    </ul>
                    <input type="text" required name="nameEnglish" value="<?=$frontendData['nameEnglish']?>" placeholder="الاسم بالانجليزي" class="form-control">
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-sm-12">
                    <input type="text" readonly  value="رقم المحضر : <?=$frontendData['id']?>" class="form-control">
                  </div>
                </div>
            
                      
                <div class="row mb-3">
                  <div class="col-sm-12">
                    <input type="text" required name="phone" value="<?=$single['phone']?>" placeholder="رقم الهاتف" class="form-control">
                  </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-12">
                      <select name="gender"  class="form-control">
                      <?php 
                      if($single['gender'] == 0)
                      {
                        ?>
                           <option value="0" selected>ذكر</option>
                           <option value="1">انثى</option>

                        <?php 
                      }else{
                        ?>
                           <option value="0" >ذكر</option>
                           <option value="1" selected>انثى</option>

                        <?php 
                      }
                      ?>
                      </select>
                    </div>
                </div>

                <?php 
                if($singlePower['power'] == 'male')
                {
                  ?>
              <div class="form-check form-check-inline">
                                  <label>تحضير لجان : </label>

                <input class="form-check-input" checked type="radio" name="powersMandob" id="inlineCheckbox1" value="male">
                <label class="form-check-label" for="inlineCheckbox1">ذكور</label>
                
                  <input class="form-check-input" type="radio" name="powersMandob" id="inlineCheckbox1" value="female">
                <label class="form-check-label" for="inlineCheckbox1">آناث</label>
                </div>


           
              
                  <?php 
                }else{
                  ?>
 <div class="form-check form-check-inline">
                <label>تحضير لجان : </label>
                <input class="form-check-input"  type="radio" name="powersMandob" id="inlineCheckbox1" value="male">
                <label class="form-check-label" for="inlineCheckbox1">ذكور</label>
                
                 <input class="form-check-input" checked type="radio" name="powersMandob" id="inlineCheckbox1" value="female">
                <label class="form-check-label" for="inlineCheckbox1">آناث</label>
                </div>


                  <?php 
                }
                ?>
               
               
               <div class="row mb-3">
                 <select name="active"  class="form-control">
                  <?php 
                  if($single['active'] == 1)
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

                <div class="row mb-3 my-4">
                  <label for="">رقم اللجنة</label>
                  <?php 
                  $getDaTA = $db->db->prepare("SELECT DISTINCT(value_allajna) FROM allajnas WHERE  id_user = ? ");
                  $getDaTA->execute([$_GET['id']]);
                  $fetch = $getDaTA->fetch();

                  $sql = $db->db->prepare("SELECT DISTINCT(allajna) FROM voters WHERE  allajna != ''");
                  $sql->execute();
                  
                  ?>
                  <select class="form-control" name="allajnas">
                    <?php 
                    if( $fetch['value_allajna'] == 0)
                    {
                      ?>
                      <option value="0" selected>الكل</option> 
                     <?php
                    }else{
                      ?>
                      <option value="0" >الكل</option> 
                     <?php
                    }
                    foreach($sql->fetchAll() as $row)
                    {
                     
                     if($row['allajna'] == $fetch['value_allajna'])
                      {
                        ?>
                         <option value="<?=$row['allajna']?>" selected><?=$row['allajna']?></option> 

                        <?php
                      }else{
                      ?>
                          <option value="<?=$row['allajna']?>" ><?=$row['allajna']?></option> 

                      <?php 
                      }
                    }
                    ?>
                  </select>
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