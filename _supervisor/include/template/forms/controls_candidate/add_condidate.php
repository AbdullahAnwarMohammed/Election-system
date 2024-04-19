<?php 
if(!$_SESSION['superVisor'])
{
    header("location:login.php");
}
if($_SESSION['idSuperVisor'] != $_GET['master'])
{
    header("location:index.php");
}

?>
<?php 
    $message;
    $succuess = 0;
    if(isset($_POST['add']))
    {
     
        $idCandidate = $_GET['master'];
       $nameEvent = $db->getSingleInfo('infocandidate','idCandidate',$idCandidate)['nameEvent'];
       $username = trim($_POST['username']);
       $phone = trim($_POST['phone']);
       $email = $_POST['email'];
       $password = $_POST['password'];
        $gender = $_POST['gender'];
      $nameEnglish = trim($_POST['nameEnlish']);
       $data = array(
           'username' => $username,
           'email' => $email,
           'phone' => $phone,
           'password' => password_hash($password,PASSWORD_DEFAULT), 
           'rank' => 3,
           'gender' => $gender
       );
    
     
    
        if($db->checkUsername($username) === false)
       {
        $message = 'الاسم العربي متسخدم من قبل';
        }
       else if($db->checkEmail($email) === false){
        $message = 'البريد متسخدم من قبل';
       } else if(!preg_match('/^[a-zA-Z.0-9]+$/',$nameEnglish))
       {
         $message = 'يرجي استخدام الاحرف الانجليزي  في الاسم الانجليزي وعلامة (.)';
       }else if($db->checkNameEnglish($nameEnglish) === false)
       {
        $message = 'الاسم الانجليزي متسخدم من قبل';
       }
       else{

        $insert = $db->insertSuperVisor('supervisor',$data);
        $db->insertSuperVisor('passwords',
            array(
              'id_user' => $insert,
              'password' => $password
            )
          );
           $db->insertSuperVisor('musharifin_candidate',
             array(
               'idMusharifin' => $insert,
               'idCandidate' => $_GET['master']
             )
           );
          

           $db->insertSuperVisor('frontend',array(
            'idUser' => $insert, 
            'parent' => $_GET['master'], 
            'username' => $username, 
            'event' => $nameEvent,
            'hashUsername' => password_hash(time().$username,PASSWORD_DEFAULT),
            'hashEvent' => password_hash(time().$nameEvent,PASSWORD_DEFAULT),
            'nameEnglish' =>  $nameEnglish
          ));

          $create_daman = isset($_POST['create_daman']) ? $_POST['create_daman'] : 0;
          $select_daman = isset($_POST['select_daman']) ? $_POST['select_daman'] : 0;
          $cancel_madmen = isset($_POST['cancel_madmen']) ? $_POST['cancel_madmen'] : 0;
          $prepare_madmen = isset($_POST['prepare_madmen']) ? $_POST['prepare_madmen'] : 0;
          $create_list = isset($_POST['create_list']) ? $_POST['create_list'] : 0;
          $db->insertSuperVisor('powers',array(
            'idUser' => $insert,
            'idParent' => $_GET['master'],
            'create_daman' => $create_daman,
            'select_daman' =>  $select_daman ,
            'cancel_madmen' => $cancel_madmen,
            'prepare_madmen' =>  $prepare_madmen,
            'create_list' =>  $create_list
          ));


           $message = "تم الاضافة بنجاح";
           $succuess = 1;
           //header("Refresh:1;url=supervisors.php");
      
      
      
       }
      }

    ?>






<div class="col-lg-12">

<div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">المفاتيح</h1>

    <a href="show_condidate.php?master=<?=$_SESSION['idSuperVisor']?>">
      <img src="assets/img/back.png" />
  </a>
</div>
      <div class="col-12 alert alert-danger">
        اضافة مفتاح
      </div>

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
              
            <form class="needs-validation" novalidate
                action="<?=$_SERVER['PHP_SELF']?>?action=add&master=<?=$_GET['master']?> " method="POST"
                enctype="multipart/form-data">

                <div class="row mb-3">
                    <div class="col-sm-12">
                        <input type="text" required name="username" placeholder="الاسم بالعربي" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-12">
                    <ul>
                      <li class="text-danger"> في الاسم الانجليزي غير مسموح بالرموز و المسافات ماعدا (.)</li>
                    </ul>

                        <input type="text" required name="nameEnlish" placeholder="الاسم بالانجليزي" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-12">
                        <input type="number" required name="phone" placeholder="رقم الهاتف" class="form-control">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-12">
                        <input type="email" required name="email" placeholder=" البريد الالكتروني (اسم المستخدم للدخول)" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-12">
                        <input type="password" required name="password" placeholder="كلمة السر" class="form-control">
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

                <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="create_daman" id="inlineCheckbox1" value="1">
                <label class="form-check-label" for="inlineCheckbox1">انشاء مُتعهد </label>
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
                        <button type="submit" name="add" class="btn btn-primary">اضافة <i
                                class="ri-file-add-line"></i></button>
                    </div>
                </div>

            </form><!-- End General Form Elements -->

        </div>
    </div>

</div>