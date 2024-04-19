<?php 
    $message;
    $succuess = 0;


    if(isset($_POST['add']))
    {
     
       $nameEvent = $db->getSingleInfo('infocandidate','idCandidate',$_SESSION['idSuperVisor'])['nameEvent'];
      $username = trim($_POST['username']);
      $nameEnglish = trim($_POST['nameEnglish']);
      $phone = $_POST['phone'];
         $password = isset($_POST['password']) ? $_POST['password'] : "NULL";
      
  
       $data = array(
           'username' => $username,
           'password' => $password,
           'phone' => $phone,
           
           'rank' => 4
       );
    
 
       if($db->checkUsername($username) === false)
       {
         $message = 'الاسم العربي متسخدم من قبل';
       }else if(!preg_match('/^[a-zA-Z.0-9]+$/',$nameEnglish))
       {
         $message = ' يرجي استخدام الاحرف الانجليزية  وعلامة  نقطة فقط(.)';
       }
       else if($db->checkNameEnglish($nameEnglish) === false)
       {
        $message = 'الاسم الانجليزي متسخدم من قبل';
       }
        else{
          
         $insert = $db->insertSuperVisor('supervisor',$data);
        $allajnas = $db->db->prepare("INSERT INTO allajnas(id_user,value_allajna) VALUES(?,?)");
        $allajnas->execute([$insert,$_POST['allajnas']]);


         $db->insertSuperVisor('relationship_mandob',
          array(
            'id_mandob' => $insert,
            'id_supervisor' => $_SESSION['idSuperVisor']
          )
          );

          $db->insertSuperVisor('frontend',array(
            'idUser' => $insert, 
            'parent' => $_SESSION['idSuperVisor'], 
            'username' => $username, 
            'event' => $nameEvent,
            'hashUsername' => password_hash(time().$username,PASSWORD_DEFAULT),
            'hashEvent' => password_hash(time().$nameEvent,PASSWORD_DEFAULT),
            'nameEnglish' => $nameEnglish
          ));

          $db->insertSuperVisor('powermandob',array(
            'id_mandob' => $insert,
             'power' => $_POST['powersMandob']
          ));
          
          $message = "تم الاضافة بنجاح";
          $succuess = 1;
          header("Refresh:1;url=mandub.php");
       }
    
/*
        if($Supervisor->check('username',$username,'هذا الاسم موجود من قبل') === false)
       {
        if($Supervisor->check('email',$email,'هذا الايميل موجود من قبل') === false){
        $insert = $Supervisor->insertSuperVisor($data);
        $passwords->insertSuperVisor(
          array(
            'id_user' => $insert,
            'password' => $password
          )
        );
           $musharifin_candidate->insertSuperVisor(
             array(
               'idMusharifin' => $insert,
               'idCandidate' => $_POST['idCandidate']
             )
           );
          

           $frontend->insertSuperVisor(array(
            'idUser' => $insert, 
            'parent' => $_POST['idCandidate'], 
            'username' => $username, 
            'event' => $nameEvent,
            'hashUsername' => password_hash(time().$username,PASSWORD_DEFAULT),
            'hashEvent' => password_hash(time().$nameEvent,PASSWORD_DEFAULT),
          ));

     
          $powermandob->insertSuperVisor(array(
            'id_mandob' => $insert,
             'power' => $_POST['powersMandob']
          ));

           $message = "تم الاضافة بنجاح";
           $succuess = 1;
           header("Refresh:1;url=musharifin.php");
       }else{
        $message = $Supervisor->_errors;
       }
      }
      
      else{
           $message = $Supervisor->_errors;
       }*/
      }

    ?>
<?= !empty($upload->_errors) ? '<div class="alert alert-warning">'.$upload->_errors.'</div>' : null ?>
<div class="d-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"> محضر (اضافة) </h1>

  <a href="mandub.php">
      <img src="assets/img/back.png" />
  </a>
</div>

<div class="col-12">
  <div class="alert alert-info" style="background-color: #008dd1;">اضافة محضر</div>
</div>

<div class="col-lg-12">

          <div class="card">
            <div class="card-body py-3">
              
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

              <form class="needs-validation"  action="<?=$_SERVER['PHP_SELF']?>?action=add" method="POST" enctype="multipart/form-data">
              <!-- <div class="row mb-3">
              <div class="col-sm-12">
                    <label for="حدث">اسم المرشح التابع له</label>
                  
                     $single = $Supervisor->getSingleInfo('id',$_SESSION['idSuperVisor']);
            
                    <select name="idCandidate" required class="form-control">
                       
                       
                        if($_SESSION['rankSuperVisor'] != 1)
                        {
                          
                          <option value="<$single['id']?>"><$single['username']?></option>
                          
                        }else{
                        foreach($Supervisor->getAll('rank',2,'yes') as $row)
                        {
                          
                            <option value="?$row['id']">$row['username']></option>
                            
                        }
                      }
                        
                    </select>
                  </div>  
              </div>  -->
              <div class="row mb-3">
                  <div class="col-sm-12">
                    <input type="text" required name="username" placeholder="الاسم بالعربي (مطلوب)" class="form-control">
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-12">
                    <ul>
                      <li class="text-danger"> في الاسم الانجليزي غير مسموح بالرموز و المسافات ماعدا (.)</li>
                    </ul>
                    <input type="text" required name="nameEnglish" placeholder="الاسم بالانجليزي (مطلوب)"  class="form-control">
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-sm-12">
                    <input type="number" required name="phone" placeholder="رقم الهاتف" class="form-control">
                  </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-12">
                      <select name="gender"  class="form-control">
                      <option value="0">ذكر</option>
                      <option value="1">انثى</option>
                      </select>
                    </div>
                </div>

                <label for=""> تحضير لجان : </label>

                <div class="form-check form-check-inline">
                <input class="form-check-input" checked type="radio" name="powersMandob" id="inlineCheckbox1" value="male">
                <label class="form-check-label" for="inlineCheckbox1">ذكور</label>
                <input class="form-check-input" type="radio" name="powersMandob" id="inlineCheckbox1" value="female">
                <label class="form-check-label" for="inlineCheckbox1">آناث</label>
                </div>


         
                <div class="row mb-3 my-4">
                  <label for="">رقم اللجنة</label>
                  <?php 
                  $sql = $db->db->prepare("SELECT DISTINCT(allajna) FROM voters WHERE  allajna != ''");
                  $sql->execute();
                  
                  ?>
                  <select class="form-control" name="allajnas">
                  <option value="0" selected >الكل</option> 

                    <?php 
                    foreach($sql->fetchAll() as $row)
                    {
                      ?>
                       <option value="<?=$row['allajna']?>"><?=$row['allajna']?></option> 
                      <?php
                    }
                    ?>
                  </select>
                </div>


            

                
                <!-- <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="create_daman" id="inlineCheckbox1" value="1">
                <label class="form-check-label" for="inlineCheckbox1">انشاء ضامن</label>
                </div>
                <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="select_daman" id="inlineCheckbox2" value="1">
                <label class="form-check-label" for="inlineCheckbox2">تحديد مضامين</label>
                </div>
                <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="cancel_madmen" id="inlineCheckbox3" value="1" >
                <label class="form-check-label" for="inlineCheckbox3">الغاء مضامين</label>
                </div>
                <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="prepare_madmen" id="inlineCheckbox3" value="1" >
                <label class="form-check-label" for="inlineCheckbox3">تحضير</label>
                </div>
                <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="create_list" id="inlineCheckbox3" value="1" >
                <label class="form-check-label" for="inlineCheckbox3">انشاء قوائم</label>
                </div> -->
                 

                

                <div class="row mb-3">
                  <div class="col-sm-12">
                    <button type="submit" name="add" class="btn btn-lg btn-success">اضافة <i class="ri-file-add-line"></i></button>
                  </div>
                </div>

              </form><!-- End General Form Elements -->

            </div>
          </div>

        </div>