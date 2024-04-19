<?php 
    $message;
    $succuess = 0;
    $passwordDb = $db->getSingleInfo('passwords','id_user',$_GET['id']);
    $frontendData = $db->getSingleInfo('frontend','idUser',$_GET['id']);



   

    if(isset($_POST['update']))
    { 

       $idCandidate = $_GET['master'];
      $nameEnglish = trim($_POST['nameEnglish']);
      $username = $_POST['username'];
      $email = $_POST['email'];
      $password = $_POST['password'];
      $phone = $_POST['phone'];
      $active = $_POST['active'];
      $data = array(
        'username' => $username,
        'email' => $email,
        'phone' => $phone,
        'gender'=> $_POST['gender'],
        'active' => $active
    );

    if(!empty($password))
    {
      $data['password'] =  password_hash($password,PASSWORD_DEFAULT);
      $db->update('passwords',array(
        'password' => $password,
      ),array(
        'id_user' => $_GET['id']
      ));
    }

    $nameEvent = $db->getSingleInfo('infocandidate','idCandidate',$_GET['master']);
    if($db->checkUsername($username,'','and id != '.$_GET['id']) === false)
    {
      $message = 'الاسم العربي متسخدم من قبل';

    }else if($db->checkEmail($email,'and id != '.$_GET['id']) === false)
    {
      $message = 'البريد متسخدم من قبل';

    }
    else if(!preg_match('/^[a-zA-Z.0-9]+$/',$nameEnglish))
    {
      $message = 'يرجي استخدام الاحرف الانجليزي  في الاسم الانجليزي وعلامة (.)';
    }else if($db->checkNameEnglish($nameEnglish,'and idUser !='.$_GET['id']) === false)
    {
     $message = 'الاسم الانجليزي متسخدم من قبل';
    }
    else{

     
  

   
     
        $db->update('frontend',array(
          'parent' => $nameEvent['idCandidate'],
          'username' => $username,
          'event' => $nameEvent['nameEvent'],
          'nameEnglish' => $nameEnglish
        ),array(
          'idUser' => $_GET['id']
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


      // تعديل التابعين
      // $getFlowers = $db->db->prepare("SELECT * FROM `powers` WHERE idParent = ?");
      // $getFlowers->execute([$_GET['id']]);
      // $all = $getFlowers->fetchAll();
      //   foreach($all as $row)
      //   {
      //         $powers->update('powers',array(
      //         'create_daman' => $create_daman,
      //         'select_daman' =>  $select_daman ,
      //         'cancel_madmen' => $cancel_madmen,
      //         'prepare_madmen' =>  $prepare_madmen,
      //         'create_list' =>  $create_list
      //         ),
      //         array(
      //           'idUser' => $row['idUser']
      //         ));


      //   }


      
     
        $db->update('supervisor',$data,array(
            'id'=> $_GET['id'],
        ));

         $message = 'تم التعديل بنجاح';
         $succuess = 1;
         header("Refresh:1; url=?action=edit&master=".$_GET['master']."&id=".$_GET['id']);
     
      }
 
 
      }

    ?>



<div class="col-lg-12">
<div class="d-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"> تعديل البيانات (مفتاح) </h1>

  <a href="show_condidate.php?master=<?=$_SESSION['idSuperVisor']?>">
      <img src="assets/img/back.png" />
  </a>
</div>
<?php 
                     $single = $db->getSingleInfo('supervisor','id',$_GET['id']);
                  ?>
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

              <form action="<?=$_SERVER['PHP_SELF']?>?action=edit&master=<?=$_GET['master']?>&id=<?=$single['id']?>" method="POST" enctype="multipart/form-data">
           
              <div class="row mb-3">
                  <div class="col-sm-12">
                    <input type="text" name="username" value="<?=$single['username']?>" placeholder="الاسم" class="form-control">
                  </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-12">
                    <input type="text" required name="nameEnglish" value="<?=$frontendData['nameEnglish']?>" placeholder="الاسم بالانجليزي" class="form-control">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-12">
                    <input type="text" required name="phone" value="<?=$single['phone']?>" placeholder="الاسم بالانجليزي" class="form-control">
                    </div>
                </div>


                <div class="row mb-3">
                  <div class="col-sm-12">
                    <input type="text" name="email" value="<?=$single['email']?>" placeholder=" البريد الالكتروني (اسم المستخدم للدخول)" class="form-control">
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-12">
                    <input type="password" name="password" placeholder="كلمة السر" class="form-control">
                    <span class="my-2 d-block">  <?=$passwordDb['password']?> </span>
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
            

                
                <div class="form-check form-check-inline">
                <?php 
                $power = $db->getSingleInfo('powers','idUser',$_GET['id']);
                ?>
                <?php 
                if($power['create_daman'] == 1)
                {
                  ?>
                  <input class="form-check-input" type="checkbox" name="create_daman" checked id="inlineCheckbox1" value="1">

                  <?php 
                }else{
                  ?>
                    <input class="form-check-input" type="checkbox" name="create_daman" id="inlineCheckbox1" value="1">

                  <?php 
                }
                ?>
                <label class="form-check-label" for="inlineCheckbox1">انشاء  مُتعهد</label>
                </div>
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


             
                <div class="row mb-3">
                  <div class="col-sm-12">
                    <button type="submit" name="update" class="btn btn-primary">تعديل <i class="ri-check-double-line"></i></button>
                  </div>
                </div>

              </form><!-- End General Form Elements -->

            </div>
          </div>

        </div>