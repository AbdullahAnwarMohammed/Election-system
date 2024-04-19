<?php 
    $message;
    $succuess = 0;



    
    if(isset($_POST['update']))
    {
       $idCandidate = $_POST['idCandidate'];

      $username = $_POST['username'];
      $email = $_POST['email'];
      $password = $_POST['password'];
      $data = array(
        'username' => $username,
        'email' => $email,
    );

    if(!empty($password))
    {
      $data['password'] =  password_hash($password,PASSWORD_DEFAULT);
      $passwords->update('passwords',array(
        'password' => $password,
      ),array(
        'id_user' => $_GET['id']
      ));

    }

    $nameEvent = $candidate->getSingleInfo('idCandidate',$_POST['idCandidate']);
    if($Supervisor->check('username',$username,'هذا الاسم موجود من قبل','AND id != '.$_GET['id'].'') === false)
    {
     if($Supervisor->check('email',$email,'هذا الايميل موجود من قبل','AND id != '.$_GET['id'].'') === false){

    $frontend->update('frontend',array(
      'parent' => $nameEvent['idCandidate'],
      'username' => $username,
      'event' => $nameEvent['nameEvent']
    ),array(
      'idUser' => $_GET['id']
    ));
    $musharifin_candidate->update('musharifin_candidate',array(
      'idCandidate' => $idCandidate
    ),array(
      'idMusharifin' => $_GET['id']
    ));
  
    $create_daman = isset($_POST['create_daman']) ? $_POST['create_daman'] : 0;
    $select_daman = isset($_POST['select_daman']) ? $_POST['select_daman'] : 0;
    $cancel_madmen = isset($_POST['cancel_madmen']) ? $_POST['cancel_madmen'] : 0;
    $prepare_madmen = isset($_POST['prepare_madmen']) ? $_POST['prepare_madmen'] : 0;
    $create_list = isset($_POST['create_list']) ? $_POST['create_list'] : 0;

    $powers->update('powers',array(
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

    $Supervisor->update('supervisor',$data,array(
     'id'=> $_GET['id'],
    )) ? header("location:musharifin.php?action=edit&id=".$_GET['id']) : false;

      }else{
        $message = $Supervisor->_errors;
      }
    }else{
      $message = $Supervisor->_errors;

    }
  }

    ?>
<?= !empty($message) ? '<div class="alert alert-'.($succuess === 0 ? 'warning' : 'success').'">'.$message.'</div>' : null ?>
<?= !empty($upload->_errors) ? '<div class="alert alert-warning">'.$upload->_errors.'</div>' : null ?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"> المُتعهدون (تعديل) </h1>

  <a href="musharifin.php" class="btn btn-md btn-dark "><i class="fas fa-chevron-circle-left"></i></a>
</div>


<div class="col-lg-12">
<?php 
                     $single = $Supervisor->getSingleInfo('id',$_GET['id']);
                  ?>
          <div class="card">
            <div class="card-body py-3">
              <form action="<?=$_SERVER['PHP_SELF']?>?action=edit&id=<?=$single['id']?>" method="POST" enctype="multipart/form-data">
              <div class="row mb-3">
              <div class="col-sm-12">
                    <label for="حدث">اسم المربح التابه له</label>
         

                    <select name="idCandidate" required class="form-control">
                        <?php

                        foreach($Supervisor->getAll('rank',2,'yes') as $row)
                        {
                            
                            ?>
                            <option value="<?=$row['id']?>"><?=$row['username']?></option>
                            <?php
                        }
                        ?>
                    </select>
                  </div>  
              </div> 
              <div class="row mb-3">
                  <div class="col-sm-12">
                    <input type="text" name="username" value="<?=$single['username']?>" placeholder="الاسم" class="form-control">
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-12">
                    <input type="text" name="email" value="<?=$single['email']?>" placeholder="البريد" class="form-control">
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-12">
                    <input type="password" name="password" placeholder="كلمة السر" class="form-control">
                  </div>
                </div>
            
                
                <div class="form-check form-check-inline">
                <?php 
                $power = $powers->getSingleInfo('idUser',$_GET['id']);
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
                <label class="form-check-label" for="inlineCheckbox1">انشاء ضامن</label>
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
                  $power = $powers->getSingleInfo('idUser',$_GET['id']);

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
                  <div class="col-sm-12">
                    <button type="submit" name="update" class="btn btn-primary">تعديل <i class="ri-check-double-line"></i></button>
                  </div>
                </div>

              </form><!-- End General Form Elements -->

            </div>
          </div>

        </div>