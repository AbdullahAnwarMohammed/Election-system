
  <?php 

$message;
$succuess = 0;
      if(isset($_POST['add']))
      {
      
        
          $username = trim($_POST['username']);
          $nameEnglish = trim($_POST['nameEnlish']);


          $email = $_POST['email'];
          $password = $_POST['password'];
          $rank = $_POST['rank'];
          $upload = new upload('image','uploads/supervisor/',array('jpg','png'),'500000');
          $phone = trim($_POST['phone']);
          $data = array(
              'username' => $username,
              'phone' => $phone,
              'email' => $email,
              'password' => password_hash($password,PASSWORD_DEFAULT), 
              'rank' => $rank
          );
          if($upload->checkIsUpload())
          {
            $upload->upload();
            $data['image'] =  $upload->getDirection();
          }



        if($db->checkUsername($username) === false)
       {
        $message = 'الاسم متسخدم من قبل';
        }
       else if($db->checkEmail($email) === false){
        $message = 'البريد متسخدم من قبل';
       }else if(!preg_match('/^[a-zA-Z.0-9]+$/',$nameEnglish))
       {
         $message = 'يرجي استخدام الاحرف الانجليزي  في الاسم الانجليزي وعلامة (.)';
       }
        else
        {
          
            
          $id = $db->insertSuperVisor('supervisor',$data);
  

          $db->insertSuperVisor('passwords',
            array(
              'id_user' => $id,
              'password' => $password
            )
          );
          
          $nameEvent = $_POST['nameEvent']; 
          $phone = $_POST['phone'];
          $age = $_POST['age'];
          $descCandidate = $_POST['descCandidate'];
        

          $uploadFooter = new upload('footerImage','uploads/footer/',array('jpg','png'),'500000');
          $array = array(
            'idEvent' => $_POST['idEvent'],
            'idCandidate' => $id,
            'nameEvent' => $nameEvent,
            'phone' => $phone,
            'age' => $age,
            'descCandidate' => $descCandidate
          );
          if($uploadFooter->checkIsUpload())
          {
                  $uploadFooter->upload();
                  $array['footerImage'] =  $uploadFooter->getDirection();            
          }
          
          $db->insertSuperVisor('infocandidate',$array);

          $db->insertSuperVisor('frontend',array(
            'username' => $username, 
            'idUser' => $id, 
            'event' => $nameEvent,
            'hashUsername' => password_hash(time().$username,PASSWORD_DEFAULT),
            'hashEvent' => password_hash(time().$nameEvent,PASSWORD_DEFAULT),
            'nameEnglish' => $nameEnglish
          ));

          $message = "تم الاضافة بنجاح";
          $succuess = 1;
          
        }

        
        
        
            

          
        
          
        }

  ?>

  <?= !empty($upload->_errors) ? '<div class="alert alert-warning">'.$upload->_errors.'</div>' : null ?>




  <div class="d-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">اضافة مرشح</h1>

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
                <form class="needs-validation" novalidate  action="<?=$_SERVER['PHP_SELF']?>?action=add" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                <div class="col-sm-12">
                    <input type="hidden" class="idEvent" name="idEvent" >

                  
                      <select id="nameEvent" name="nameEvent" required class="form-control">

                          <?php 
                          foreach($db->getInfo('events') as $row)
                          {
                            
                              ?>
                              <option class="val" data-id="<?=$row['id']?>" value="<?=$row['name']?>"><?=$row['name']?></option>

                              <?php
                          }
                          ?>
                      </select>
                    </div>  
                </div>
                <div class="row mb-3">
                  
                    <div class="col-sm-12">
                      <input type="text" name="username" required placeholder="الاسم بالعربي" class="form-control">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <div class="col-sm-12">
                        <input type="text" required name="nameEnlish" placeholder="الاسم بالانجليزي" class="form-control">
                    </div>
                </div>


                  <div class="row mb-3">
                    <div class="col-sm-12">
                      <input type="email" name="email" required placeholder="البريد" class="form-control">
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-sm-12">
                      <input type="password" name="password" required placeholder="كلمة السر" class="form-control">
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-sm-12">
                    <select class="form-control" name="rank">
                    <option value="2">مرشح</option>
                    </select>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <div class="col-sm-12">
                    <label>صورة المرشح</label>

                    <input type="file"  name="image" class="form-control" id="">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <div class="col-sm-12">
                    <label>صورة الفوتر</label>

                    <input type="file"   name="footerImage" class="form-control" id="">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <div class="col-sm-12">
                      <input type="text" name="phone"  placeholder="رقم الهاتف" class="form-control">
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-sm-12">
                      <input type="number" name="age" placeholder="السن" min="20" max="100" class="form-control">
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-sm-12">
                      <textarea name="descCandidate" class="form-control" placeholder="وصف المرشح" rows="8"></textarea>
                    </div>
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