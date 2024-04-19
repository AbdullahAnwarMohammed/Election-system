

<?php 
  $message;
  $succuess = 0;

  $Power = $db->getSingleInfo('powers','idUser',$_SESSION['idSuperVisor']);

  if($_SESSION['rankSuperVisor'] == 3)
  {
    if($Power['create_daman'] == 0)
    {
      header("location:index.php");
      exit;
    }
  }
  

  
  

  if(isset($_POST['add']))
  {

    $row = $db->getSingleInfo('musharifin_candidate','idMusharifin',$_SESSION['idSuperVisor']);
    $idCandidate = $row['idCandidate'];
    

   $nameEvent =  $db->getSingleInfo('infocandidate','idCandidate',$idCandidate);  
    $nameEvent = $nameEvent['nameEvent'];

    
    $idSuperVisor = $db->getSingleInfo('musharifin_candidate','idMusharifin',$_GET['master']);
     $idSuperVisor = $idSuperVisor['idCandidate'];
     $phone = $_POST['phone'];
     $gender = $_POST['gender'];



      $username = $_POST['username'];
 
      $idMusharif = $_SESSION['idSuperVisor'];
  
     
      

     $stmt = $db->db->prepare("SELECT * FROM daman");
     $stmt->execute();
     if($stmt->rowCount() > 0)
     {
     $data = array(
         'username' => $username,  
         'phone'=>$phone,
         'gender' => $gender,
         'idMusharif' => $_GET['master'],
         'idSuperviosr' => $idSuperVisor
     );
     }else{
       $data = array(
         'id' => 15000,
         'username' => $username,
         'phone'=>$phone,
         'gender' => $gender,

         'idMusharif' => $_GET['master'],
         'idSuperviosr' =>$idSuperVisor 
     );
     }
    

     
       if($db->checkUsername($username) === false)
        {
          $message = 'الاسم العربي متسخدم من قبل';
        }else if(!preg_match('/^[a-zA-Z.0-9]+$/',$_POST['nameEnglish']))
        {
          $message = 'يرجي استخدام الاحرف الانجليزي  في الاسم الانجليزي وعلامة (.)';

        }
        else if($db->checkNameEnglish($_POST['nameEnglish']) === false)
       {
        $message = 'الاسم الانجليزي متسخدم من قبل';
       }
        else{

              $insert = $db->insertSuperVisor('daman',$data);
              $db->insertSuperVisor('frontend',array(
                'idUser' => $insert, 
                'parent' => $_GET['master'], 
                'username' => $username,
                'event' => $nameEvent,
                'hashUsername' => password_hash(time().$username,PASSWORD_DEFAULT),
                'hashEvent' => password_hash(time().$nameEvent,PASSWORD_DEFAULT),
                'nameEnglish' => trim($_POST['nameEnglish'])
              ));
      

              $select_daman = isset($_POST['select_daman']) ? $_POST['select_daman'] : 0;
              $cancel_madmen = isset($_POST['cancel_madmen']) ? $_POST['cancel_madmen'] : 0;
              $prepare_madmen = isset($_POST['prepare_madmen']) ? $_POST['prepare_madmen'] : 0;
              $create_list = isset($_POST['create_list']) ? $_POST['create_list'] : 0;

              $db->insertSuperVisor('powers',array(
                'idUser' => $insert,
                'idParent' => $_GET['master'],
                'create_daman' => 0,
                'select_daman' =>  $select_daman,
                'cancel_madmen' => $cancel_madmen,
                'prepare_madmen' =>  $prepare_madmen,
                'create_list' => $create_list,
              ));

              $message = "تم الاضافة بنجاح";
              $succuess = 1;
              header("Refresh:3; url=?action=add&master=".$_GET['master']);

        }
  }

  ?>

<main id="main" class="main">
   


<?= !empty($upload->_errors) ? '<div class="alert alert-warning">'.$upload->_errors.'</div>' : null ?>
<div class="d-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"> مُتعهد (اضافة) </h1>
  <a href="daman.php?master=<?=$_SESSION['idSuperVisor']?>">
      <img src="assets/img/back.png" />
  </a>
</div>

<div class="row">

<div class="col-12">
    <div class="alert alert-warning">هذا المُتعهد سيكون تابع لك</div>

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

              <form action="<?=$_SERVER['PHP_SELF']?>?action=add&master=<?=$_GET['master']?>" method="POST" enctype="multipart/form-data">
              <div class="row mb-3">
                  <div class="col-sm-12">
                    <input type="username" required name="username" placeholder="الاسم بالعربي" class="form-control">
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-12">
                  <ul>
                      <li class="text-danger">  الاسم الانجليزي غير مسموح  بالرموز و المسافات ماعدا (.)</li>
                    </ul>
                    <input type="text" required name="nameEnglish" placeholder="الاسم بالانجليزي" class="form-control">
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
<!--                 
                <div class="form-check form-check-inline d-none">
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
                 -->

                 <div class="row mb-3">
               

               
                 
                   <?php 
                  if($Power['select_daman'] == 1)
                  {
                    ?>
               <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox" name="select_daman" id="inlineCheckbox1" value="1">
                      <label class="form-check-label" for="inlineCheckbox1">تحديد مضامين</label>
                      </div>
                    <?php 
                  }else{
                    ?>
<div class="form-check form-check-inline">
                      <input disabled disabled class="form-check-input" type="checkbox" name="select_daman" id="inlineCheckbox1" value="1">
                      <label class="form-check-label" for="inlineCheckbox1">تحديد ضامن</label>
                      </div>
                    <?php 
                  }
                  ?>
                   <?php 
                  if($Power['cancel_madmen'] == 1)
                  {
                    ?>
               <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox" name="cancel_madmen" id="inlineCheckbox1" value="1">
                      <label class="form-check-label" for="inlineCheckbox1"> الغاء مضامين</label>
                      </div>
                    <?php 
                  }else
                  {
                    ?>
          <div class="form-check form-check-inline">
                      <input  disabled class="form-check-input"  type="checkbox" name="cancel_madmen" id="inlineCheckbox1" value="1">
                      <label class="form-check-label" for="inlineCheckbox1">الغاء مضامين </label>
                      </div>
                    <?php 
                  }
                  ?>
                   <?php 
                  if($Power['prepare_madmen'] == 1)
                  {
                    ?>
                <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox" name="prepare_madmen" id="inlineCheckbox1" value="1">
                      <label class="form-check-label" for="inlineCheckbox1">تحضير</label>
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
                      <input class="form-check-input" type="checkbox" name="create_list" id="inlineCheckbox1" value="1">
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
                    <button type="submit" name="add" class="btn btn-success btn-md">اضافة</button>
                  </div>
                </div>

              </form><!-- End General Form Elements -->

            </div>
          </div>

        </div>
        </div>
</main>
