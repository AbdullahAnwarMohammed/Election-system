<?php 

$message;
$succuess = 0;



if(isset($_POST['add']))
{
  // $row = $musharifin_candidate->getSingleInfo('idMusharifin',$_POST['idMusharifin']);
  // $idCandidate = $row['idCandidate'];
  $nameEvent =  $db->getSingleInfo('infocandidate','idCandidate',$_GET['master']);
  $nameEvent = $nameEvent['nameEvent'];
  $nameEnglish = trim($_POST['nameEnglish']);
  $phone = $_POST['phone'];
  $gender = $_POST['gender'];


    $username = trim($_POST['username']);
   
    $idMusharif = $_GET['master'];
    
    $stmt = $db->db->prepare("SELECT * FROM daman");
    $stmt->execute();
    if($stmt->rowCount() > 0)
    {
    $data = array(
      'username' => $username,  
      'phone' => $phone,  
      'gender' => $gender,
      'idSuperviosr' => $_GET['master']
    );
    }else{
      $data = array(
        'id' => 15000,
        'username' => $username,
        'phone' => $phone,  
        'gender' => $gender,
        'idSuperviosr' => $_GET['master']
    );
    }
    

  

   
        if($db->checkUsername($username) === false)
        {
          $message = 'الاسم  العربي مستخدم من قبل';
        }else if(!preg_match('/^[a-zA-Z.0-9]+$/',$_POST['nameEnglish']))
        {
          $message = ' يرجي استخدام الاحرف الانجليزية  وعلامة  نقطة فقط(.)';

        }
        else if($db->checkNameEnglish($nameEnglish) === false)
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
              header("Refresh:3; url=?action=add&master=".$_GET['master']);

        }
   
        

       
  
      }
?>
<div class="col-12">
<div class="alert alert-success">اضافة مُتعهد </div>

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
              <form action="<?=$_SERVER['PHP_SELF']?>?action=add&master=<?=$_GET['master']?> " method="POST" enctype="multipart/form-data">
              <!-- <div class="row mb-3">
                <div class="col-sm-12" >
                    <select name="idMusharifin" id="" class="form-control">
                      
                    $musharifins = $musharifin_candidate->getAll('idCandidate',$_SESSION['idSuperVisor'],'yes');
                    foreach($musharifins as $row){
                        $getName = $Supervisor->getSingleInfo('id',$row['idMusharifin']);
                       
                        <option value="$getName['id']">$getName['username']</option>
                        
                    }
                        
                    </select>
                </div>
              </div> -->
              <div class="row mb-3">
                  <div class="col-sm-12">
                    <input type="username" required name="username" placeholder="الاسم بالعربي" class="form-control">
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-12">
                  <ul>
                      <li class="text-danger"> في الاسم الانجليزي غير مسموح بالرموز و المسافات ماعدا (.)</li>
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
                

                <div class="row mb-3">
                  <div class="col-sm-12">
                    <button type="submit" name="add" class="btn btn-success btn-lg">اضافة</button>
                    <a href="show_damans.php?master=<?=$_SESSION['idSuperVisor']?>" class="btn btn-danger">المُتعهدون</a>
                  </div>
                </div>

              </form><!-- End General Form Elements -->

            </div>
          </div>

        </div>
        </div>
        </div>