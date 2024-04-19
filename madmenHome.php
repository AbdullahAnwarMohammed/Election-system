<?php 
require_once("_supervisor/init.php");

$id = $_GET['id'];
$username = $_GET['username'];

        $prepare = $db->db->prepare("SELECT * FROM frontend WHERE id = ? ");
        $prepare->execute([$id]);
        $rowFrontend = $prepare->fetch();
        if($rowFrontend && $rowFrontend['nameEnglish'] == $username)
        {
            $idParent = $rowFrontend['parent'];
            $nameEvent = $rowFrontend['event'];
            $username = $rowFrontend['username'];



            $rowFrontend = $db->getSingleInfo('frontend','id',$_GET['id']);

            // فى حالة انك مش ضامن
            $idManger = $db->getSingleInfo('supervisor','id',$rowFrontend['idUser']);

            // فى حالة انك  ضامن
            $idMangerDaman = $db->getSingleInfo('daman','id',$rowFrontend['idUser']);

            // جلب بيانات المرشح التابع للضامن
            if(isset($idManger['active'] ) && $idManger['active'] == 0 )
            {
                header("location:404.html");
            }

            if($idMangerDaman !== false)
            {
                $SuperVisor = $db->getSingleInfo('supervisor','id',$idMangerDaman['idSuperviosr']);
                if($SuperVisor['active'] == 0)
                {
                    header("location:_supervisor");
                }
            }
            
            // if(isset($idManger['rank']) && $idManger['rank'] != 4 || !empty($idMangerDaman))
            // {
            //     header( "location:index.php?username=".$_GET['username']."&id=".$_GET['id']);
            //     exit;
            // }



            if($idParent !== NULL){
                $infoSupervisor = $db->getSingleInfo('supervisor','id',$idParent);
                $frontend = $db->getSingleInfo('frontend','idUser',$infoSupervisor['id']);
                $username = $frontend['event'];
                if($frontend['parent'] != NULL)
                {
                    $infoSupervisor = $db->getSingleInfo('supervisor','id',$frontend['parent']);
                }else{
                    $infoSupervisor = $db->getSingleInfo('supervisor','id',$idParent);
    
                }
            }else{
                $infoSupervisor = $db->getSingleInfo('supervisor','id',$rowFrontend['idUser']);
            }


            $infocandidate = $db->getSingleInfo('infocandidate','idCandidate',$infoSupervisor['id']);
        

     
            $Events = $db->getSingleInfo('events','name',$nameEvent);
            $idEvent = $Events['id'];
            $allVoters = $db->getAll('voters','idEvent',$idEvent,'yes');


            

        }else{
          header("location:_supervisor");
           exit;
        }



$db = new DB();






$Power = $db->getSingleInfo('powers','idUser',$rowFrontend['idUser']);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" 
      content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

      <link rel="icon" type="image/x-icon" href="assets/images/icon.png">


    <title>CP MAGLES </title>



    <link href="assets/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="assets/css/remixicon.css" rel="stylesheet">
    <link  href="assets/css/sweetalert2.min.css"  rel="stylesheet"/>   
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" >
    <link href="assets/css/select2.min.css" rel="stylesheet" />
    <link  href="assets/css/style.css" rel="stylesheet">
    <link  href="assets/css/responsive.css" rel="stylesheet">

</head>

<body>
<?php 
    if(isset($idManger['rank']))
    {
        $idManger = $idManger['rank'];
    }else{
        $idManger = 5;
    }
    ?>
    <!-- معلومات  --> 
    <input type="hidden" class="idSupervisor" value="<?=$infoSupervisor['id']?>" />
    <input type="hidden" class="level" value="<?=$idManger?>" />
    <input type="hidden" class="nameEvent" value="<?=$nameEvent?>">
    <input type="hidden" class="expireDate" value="<?=$Events['expireDate']?>">
    <input type="hidden" class="idEvent" value="<?=$idEvent?>">
    <input type="hidden" class="idFrontend" value="<?=$rowFrontend['id']?>">
    <input type="hidden" name="idUser" class="idUser" value="<?=$rowFrontend['idUser']?>" />
   
   <div class="loading">
    
   <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
   </div>
    <!-- تغير الالوان -->
    <div class="app">
     
        <div class="header" style="background:#008dd1">

        <div class="appHeader ">
            <a href="/_supervisor/">
            <img width="150"  src="<?=$infoSupervisor['image'] ? '_supervisor/'.$infoSupervisor['image']  : 'assets/images/supervisor.png' ?>" alt="">

            </a>
                <div class="info text-center">
                <h5 style="color:#8eff8e;"><?= $infoSupervisor['username'] ?></h5>
                    <small style="cursor:pointer;font-size:13px;color:#ffea82;display:block" >مرشح انتخابات</small>

                    <p><?=$Events['name']?></p>
                    <small>(<?=count($allVoters) ?>)</small>
                </div>
            </div>
        </div>


        <div class="appOne" style="background: #fff;">
        <div class="guarantor">
           
        <div class="title" style="display:flex; justify-content:center;align-items:center">
           
              
                <h5> <i class="ri-shield-user-fill"></i>مرحبا  <?=$rowFrontend['username']?> </h5>

           </div>
           <p style="background:#01e37c;font-size:20px;color:#14623f;font-weight:bold">
           صفحة حضور الناخبين وفرز الاصوات 🌹
           </p>
           <p>
           <p class="badge bg-danger">مندوب</p>
            <a target="_blank" href="https://docs.google.com/viewerng/viewer?url=http://optujss.cluster051.hosting.ovh.net/uploads/files/KtabPDF.Com_hbBZkZt2.pdf&hl=ar" class="btn btn-dark btn-sm"> دليل المستخدم <i class="ri-error-warning-fill"></i> </a>
            </p>

       </div>

    
   

        <div class="listOfName">

        <select id="mandubLgna" style="font-size:16px;background:#ffd400;border-radius:5px;">
        <option  selected value="all">اللجان</option>

        <?php 
           $get = $db->db->prepare("SELECT DISTINCT(allajna) FROM voters");
           $get->execute();
           foreach($get->fetchAll() as $row)
           {
            ?>
            <option value="<?=$row['allajna']?>"><?=$row['allajna']?></option>
            <?php
           }
           ?>

         </select>
                        


       
                   
            <h4 class="openDropdown" > 
                <div class="d-flex" style="font-size: 15px;">
                عدد
                <?php 
                $stmt = $db->db->prepare('   SELECT DISTINCT(username) FROM vote 
                WHERE idSupervisor = '.$infoSupervisor['id'].' ');
                $stmt->execute();
             
                ?>
                <span><?= $stmt->rowCount()?></span>
                <i class="ri-search-line"></i>
                </div> 
              
               
               
                </h4>
              
                
            <div class="dropdown active">
           <div class="table-responsive">

            <table class="table myTable  table-secondary table-striped refreshData" style="width:100%;">
   
            <thead class="theadMain">
         
            <select   id="mainMaleOrFemale" class="mainMaleOrFemaleMandob" style="font-size:16px;background:#ffd400;border-radius:5px;" >
                
                <!-- <option value="all" selected>الجميع</option> -->
                <?php
                $powermandob = $db->getSingleInfo('powermandob','id_mandob',$rowFrontend['idUser']);
                if($powermandob['power'] == 'male')
                {
                    ?>
                        <option selected value="male" >ذكور</option>
                    <?php 
                }else{
                    ?>
                      <option  selected value="female">اناث</option>
                    <?php 
                }
                ?>
            
                </select>
                
                
                <select id="selectAttendOr" class="selectAttendOr text-dark"  style="font-size:16px;background:#ffd400;border-radius:5px;" >
                <option value="all" selected>الناخبين</option>
                <option value="attend">حضور</option>
                <option value="no-attend">غياب</option>
                </select>
                   
            </div>

                    <tr>
                        <th><input type="checkbox" data-target=".mainCheckbox" class="checkall" /></th>
                        <th class="name2">الفرز </th>
                        <th id="getNumber">حاضر</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>

            </div>


            <div class="insertList ">
            <select name="" style="padding: 8px 0;" id="valueList" class="form-control  rounded-0">

             


           
                <option value="presentmain" class="text-dark">حاضر</option>
                <option value="notpresentmain" class="text-dark">غير حاضر</option>

                    <?php 
                    $count = $db->getRows('list',array(
                        'where' => array('idParent'=>$rowFrontend['idUser']),
                        'return_type' => 'count'
                    ));
                    if($count > 0){
                        $Lists = $List->getAll('idParent',$rowFrontend['idUser'],true);
                        foreach($Lists as $row){
                        ?>
                        <option value="<?=$row['id']?>"><?=$row['name']?></option>
                        <?php
                        }
                    }
                    ?>
            </select>
           
            <button id="insetListContent"  class=" py-2 rounded-0">تطبيق</button>

           
              
            </div>
            </div>
    
            <input type="hidden" class="idParent" name="idParent" value="<?=$rowFrontend['idUser']?>" />

                    
          
        </div>



        <footer class="footer">
            <img src="_supervisor/<?=$infocandidate['footerImage']?>" alt="">
        </footer>
        <div id="counter" >
        <div class="buttons">
            <h6 class="text-white">اصوات اللجنة</h6>
            <button class="btn  btn-success increment_btn">+</button>
            <span class="get_count">0</span>
            <button class="btn  btn-success decrement_btn">-</button>

        </div>
        <div class="all_counts" style="color:#ffe563">0</div>
        </div>
        <?php      require_once "modal.php"; ?>
      
    </div>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/sweetalert2.min.js"></script>
    <script src="assets/js/html2pdf.bundle.js"></script>
    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/select2.min.js"></script>
    <script src="assets/js/ajax.js"></script>
    <script src="assets/js/scriptMadmen.js"></script>


</body>

</html>