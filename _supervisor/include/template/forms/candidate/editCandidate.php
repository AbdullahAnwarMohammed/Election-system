<?php 
    $message;
    $succuess = 0;
    $dataSupervisor = $db->getSingleInfo('supervisor','id',$_GET['id']);
    $dataCandidate = $db->getSingleInfo('infocandidate','idCandidate',$_GET['id']);
   
    $passwordDb = $db->getSingleInfo('passwords','id_user',$_GET['id']);


    $frontendData = $db->getSingleInfo('frontend','idUser',$_GET['id']);

   
    if(isset($_POST['update']))

    {
     
        $upload = new upload('image','uploads/supervisor/',array('jpg','png','jpeg'),'10000000');

        $username = trim($_POST['username']);
        $email = $_POST['email'];
        $password = $_POST['password'];
        $nameEvent = $_POST['nameEvent'];
        $nameEnglish = trim($_POST['nameEnglish']);

     
          
        $data = array(
          'username' => $username,
          'email' => $email,
          'phone' => trim($_POST['phone']),
          'active' => $_POST['active'],
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
        if($upload->checkIsUpload())
        {
          $upload->removeImage($dataSupervisor['image']);
          $upload->upload();
          $data['image'] =  $upload->getDirection();          
        }
        $db->update('supervisor',$data,array(
         'id'=> $_GET['id'],
        ));
    
        $phone = $_POST['phone'];
        $age = $_POST['age'];
        $descCandidate = $_POST['descCandidate'];
        $uploadFooter = new upload('footerImage','uploads/footer/',array('jpg','png'),'500000');
        $array = array(
          'idEvent' => $_POST['idEvent'],
          'nameEvent' => $nameEvent,
          'phone' => $phone,
            'age' => $age,
            'descCandidate' => $descCandidate
        );
        if($uploadFooter->checkIsUpload())
        {
          if(file_exists($dataCandidate['footerImage']))
          {
            $upload->removeImage($dataCandidate['footerImage']);

          }
            $uploadFooter->upload();
            $array['footerImage'] =  $uploadFooter->getDirection();
        }


        if($db->checkUsername($username,'','and id != '.$_GET['id']) === false)
       {
        $message = 'الاسم متسخدم من قبل';
       }
       else if($db->checkEmail($email,'and id != '.$_GET['id']) === false)
       {
        $message = 'البريد متسخدم من قبل';
       }
       else if(!preg_match('/^[a-zA-Z.0-9]+$/',$nameEnglish))
       {
        $message = 'يرجي استخدام الاحرف الانجليزي  في الاسم الانجليزي وعلامة (.)';

       }else{
       
    
        $db->update('frontend',array(
          'username' => $username,
          'event' => $nameEvent,
          'nameEnglish' => $nameEnglish
          ),array(
            'idUser'=>$_GET['id']
          ));
          
        $db->update('infocandidate',$array,array(
              'idCandidate'=> $_GET['id'],
          ));
    
          
          $message = "تم التعديل بنجاح";
          $succuess = 1;
          header("Refresh:1;url=candidate.php?action=edit&id=".$_GET['id']);


      }
      


    }
       
    

    ?>
<?= !empty($upload->_errors) ? '<div class="alert alert-warning">'.$upload->_errors.'</div>' : null ?>



<div class="d-flex align-items-center justify-content-between mb-4">
<h1 class="h3 mb-0 text-gray-800">المرشحين (تعديل)</h1>
<a href="candidate.php">
      <img src="assets/img/back.png" />
  </a> 


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
              <form action="<?=$_SERVER['PHP_SELF']?>?action=edit&id=<?=$_GET['id']?>" method="POST" enctype="multipart/form-data">
              <div class="row mb-3">  
              <div class="col-sm-12 mb-3">
                <label for="">الحالة</label>
                <select name="active" class="form-control">
                  <?php 
                  if($dataSupervisor['active'] == '1')
                  {
                    ?>
                     <option value="1" selected>مفعل</option>
                     <option value="0">غير مفعل</option>
                    <?php
                  }else{
                    ?>
                     <option value="1">مفعل</option>
                     <option value="0" selected>غير مفعل</option>
                    <?php
                  }
                  ?>
               
                </select>
              </div>
              <div class="col-sm-12">
                    <input type="hidden" name="idEvent" class="idEvent" >
                    <select name="nameEvent" id="nameEvent" required class="form-control">
                        <?php 
                        foreach($db->getInfo('events') as $row)
                        {
                            if($row['name'] == $dataCandidate['nameEvent'])
                            {
                                ?>
                                <option data-id="<?=$row['id']?>" selected value="<?=$row['name']?>"><?=$row['name']?></option>

                                <?php
                            }
                            else{
                                ?>

                                <option data-id="<?=$row['id']?>" value="<?=$row['name']?>"><?=$row['name']?></option>
    
                                <?php
                            }
                           
                        }
                        ?>
                    </select>
                  </div>  
              </div>

              <div class="row mb-3">
                  <div class="col-sm-12">
                    <input type="text" name="username" value="<?=$dataSupervisor['username']?>" placeholder="الاسم" class="form-control">
                  </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-12">
                    <input type="text" required name="nameEnglish" value="<?=$frontendData['nameEnglish']?>" placeholder="الاسم بالانجليزي" class="form-control">
                    </div>
                </div>

                <div class="row mb-3">
                  <div class="col-sm-12">
                    <input type="text" name="email" value="<?=$dataSupervisor['email']?>" placeholder="البريد" class="form-control">
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-12">
                    <input type="password" name="password" placeholder="كلمة السر الجديدة" class="form-control">
                    <span><?=$passwordDb['password']?></span>
                  </div>
                </div>
               

                <div class="row mb-3">
                  <div class="col-sm-12" >
                  
                  <input type="file"  name="image" class="form-control" id="">
                  <?php 
                  if($dataSupervisor['image'])
                  {
                    ?>
                    <img class="imageEdit img-thumbnail" src="<?=$dataSupervisor['image']?>" width="150" alt="">
                    <?php
                  }
                  ?>
                  </div>
                </div>

               
                <hr />
                <div class="row mb-3">
                <label>صورة الفوتر</label>

                  <div class="col-sm-12"  >
                 
                  <input type="file"  name="footerImage" class="form-control" id="">
                  <?php 
                  if(isset($dataCandidate['footerImage']))
                  {
                    ?>

                    <img class="imageEdit img-thumbnail" src="<?=$dataCandidate['footerImage']?>" width="300" alt="">
                    <?php
                  }
                  ?> 
                </div>
                </div>

                <div class="row mb-3">
                  <div class="col-sm-12">
                    <input type="text" name="phone" value="<?=isset($dataCandidate['phone']) ? $dataCandidate['phone'] : ''?>"  placeholder="رقم الهاتف" class="form-control">
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-12">
                    <input type="number" name="age" value="<?=isset($dataCandidate['age']) ? $dataCandidate['age'] : ''?>" placeholder="السن" min="20" max="100" class="form-control">
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-12">
                    <textarea name="descCandidate" class="form-control" placeholder="وصف الناخب" rows="8"><?=isset($dataCandidate['descCandidate']) ? $dataCandidate['descCandidate'] : ''?></textarea>
                  </div>
                </div>
 

                <div class="row mb-3">
                  <div class="col-sm-12">
                    <button type="submit" name="update" class="btn btn-success">تعديل  <i class="ri-check-double-line"></i></button>
                  </div>
                </div>

              </form><!-- End General Form Elements -->

            </div>
          </div>

        </div>