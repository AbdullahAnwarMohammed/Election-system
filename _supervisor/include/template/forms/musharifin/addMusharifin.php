<?php 
    $message;
    $succuess = 0;


    if(isset($_POST['add']))
    {
     
      $idCandidate = $_POST['idCandidate'];
       $nameEvent = $candidate->getSingleInfo('idCandidate',$idCandidate)['nameEvent'];
       $username = $_POST['username'];
       $email = $_POST['email'];
       $password = $_POST['password'];
      
  
       $data = array(
           'username' => $username,
           'email' => $email,
           'password' => password_hash($password,PASSWORD_DEFAULT), 
           'rank' => 3
       );
    
 
     
    
       if($Supervisor->checkUsername($username) === false)
       {
        $message = 'الاسم متسخدم من قبل';

       }else if($Supervisor->checkEmail($email) === false){
        $message = ' البريد  مستخدم من قبل';
       }
       else{
        
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

          $create_daman = isset($_POST['create_daman']) ? $_POST['create_daman'] : 0;
          $select_daman = isset($_POST['select_daman']) ? $_POST['select_daman'] : 0;
          $cancel_madmen = isset($_POST['cancel_madmen']) ? $_POST['cancel_madmen'] : 0;
          $prepare_madmen = isset($_POST['prepare_madmen']) ? $_POST['prepare_madmen'] : 0;
          $create_list = isset($_POST['create_list']) ? $_POST['create_list'] : 0;
          $powers->insertSuperVisor(array(
            'idUser' => $insert,
            'idParent' => $_POST['idCandidate'],
            'create_daman' => $create_daman,
            'select_daman' =>  $select_daman ,
            'cancel_madmen' => $cancel_madmen,
            'prepare_madmen' =>  $prepare_madmen,
            'create_list' =>  $create_list
          ));


           $message = "تم الاضافة بنجاح";
           $succuess = 1;
           header("Refresh:1;url=musharifin.php");
       
      }
      
      
      }

    ?>
<?= !empty($upload->_errors) ? '<div class="alert alert-warning">'.$upload->_errors.'</div>' : null ?>
<div class="d-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"> المُتعهدون (اضافة) </h1>

  <a href="musharifin.php" class="btn btn-md btn-dark "><i class="fas fa-chevron-circle-left"></i></a>
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

              <form class="needs-validation" novalidate action="<?=$_SERVER['PHP_SELF']?>?action=add" method="POST" enctype="multipart/form-data">
              <div class="row mb-3">
              <div class="col-sm-12">
                    <label for="حدث">اسم المرشح التابع له</label>
                    <?php 
                     $single = $Supervisor->getSingleInfo('id',$_SESSION['idSuperVisor']);
                 ?>
                    <select name="idCandidate" required class="form-control">
                        <?php
                       
                        if($_SESSION['rankSuperVisor'] != 1)
                        {
                          ?>
                          <option value="<?=$single['id']?>"><?=$single['username']?></option>
                          <?php
                        }else{
                        foreach($Supervisor->getAll('rank',2,'yes') as $row)
                        {
                            ?>
                            <option value="<?=$row['id']?>"><?=$row['username']?></option>
                            <?php
                        }
                      }
                        ?>
                    </select>
                  </div>  
              </div> 
              <div class="row mb-3">
                  <div class="col-sm-12">
                    <input type="text" required name="username" placeholder="الاسم" class="form-control">
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-12">
                    <input type="email" required name="email" placeholder="البريد" class="form-control">
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-12">
                    <input type="password" required name="password" placeholder="كلمة السر" class="form-control">
                  </div>
                </div>
            

                
                <div class="form-check form-check-inline">
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
                </div>
                 

                

                <div class="row mb-3">
                  <div class="col-sm-12">
                    <button type="submit" name="add" class="btn btn-primary">اضافة <i class="ri-file-add-line"></i></button>
                  </div>
                </div>

              </form><!-- End General Form Elements -->

            </div>
          </div>

        </div>