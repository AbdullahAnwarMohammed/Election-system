<?php 
require_once("_supervisor/init.php");

if(isset($_GET['id']) AND $_GET['username'])
{
    $id = $_GET['id'];
    $username = $_GET['username'];
    
}else{
    header("location:_supervisor");
           exit; 
}

        $prepare = $db->db->prepare("SELECT * FROM frontend WHERE id = ? ");
        $prepare->execute([$id]);
        $rowFrontend = $prepare->fetch();


        if($rowFrontend && $rowFrontend['nameEnglish'] == $username)
        {
        
            $idParent = $rowFrontend['parent'];
            // اسم الحدث
            $nameEvent = $rowFrontend['event'];
            // اسم الشخص اللى دخل على الموقع دلوقتي
            $username = $rowFrontend['username'];



            // $rowFrontend = $db->getSingleInfo('frontend','id',$_GET['id']);

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
                if($SuperVisor['active'] == 0 || $idMangerDaman['active'] == 0)
                {
                    header("location:_supervisor");
                }
            }

            if(isset($idManger['rank']) && $idManger['rank'] == 4)
            {
                header("location:mandub.php?username=".$_GET['username']."&id=".$_GET['id']);
                exit;
            }



            if($idParent !== NULL){
                $infoSupervisor = $db->getSingleInfo('supervisor','id',$idParent);
                $frontend = $db->getSingleInfo('frontend','idUser',$infoSupervisor['id']);
                $username = $frontend['event'];
                if($frontend['parent'] !== NULL)
                {
                    $infoSupervisor = $db->getSingleInfo('supervisor','id',$frontend['parent']);
                }else{
                    $infoSupervisor = $db->getSingleInfo('supervisor','id',$idParent);
    
                }
            }else{
                $infoSupervisor = $db->getSingleInfo('supervisor','id',$rowFrontend['idUser']);
            }

            // بيانات الشخص المرشح
            $infocandidate = $db->getSingleInfo('infocandidate','idCandidate',$infoSupervisor['id']);
            

            
            
            // $Events = $db->getSingleInfo('events','name',$nameEvent);
            // $idEvent = $Events['id'];
            if($infocandidate['idEvent'] != 0)
            {
               $idEvent =  $infocandidate['idEvent'];
                $allVoters = $db->getAll('voters','idEvent',$infocandidate['idEvent'],'yes');
                $Event = $db->getSingleInfo('events','id',$idEvent);
                $expire = $Event['expireDate'];
            }else{
                $idEvent = 0;
            }
         
    if($infocandidate['statusPassword'] == 1)
            {
                if(!isset($_SESSION['login']) || $_SESSION['login'] != $_GET['username'])
                {
    
                    if(!isset($idManger['rank']))
                    {
                        $idManger = 5;
                    }else{
                        $idManger = $idManger['rank'];
                    }
                    header("location:login.php?username=".$_GET['username']."&id=".$_GET['id']."&rank=".$idManger);
                    exit;
                }
            }
           
            

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


    <title>CP MAGLES</title>



    <link href="assets/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="assets/css/remixicon.css" rel="stylesheet">
    <link  href="assets/css/sweetalert2.min.css"  rel="stylesheet"/>   
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" >
    <link href="assets/css/select2.min.css" rel="stylesheet" />
    <link  href="assets/css/style.css" rel="stylesheet">
    <link  href="assets/css/responsive_min.css" rel="stylesheet">

</head>

<body>
    <!-- معلومات  --> 
    <?php 
    if(isset($idManger['rank']))
    {
        $idManger = $idManger['rank'];
    }else{
        $idManger = 5;
    }
    ?>
    <input type="hidden" class="idSupervisor" value="<?=$infoSupervisor['id']?>" />
    <input type="hidden" class="level" value="<?=$idManger?>" />
    <input type="hidden" class="nameEvent" value="<?=$nameEvent?>">
    <input type="hidden" class="expireDate" value="<?=$expire?>">
    <input type="hidden" class="idEvent" value="<?=$idEvent?>">
    <input type="hidden" class="idFrontend" value="<?=$rowFrontend['id']?>">
    <input type="hidden" name="idUser" class="idUser" value="<?=$rowFrontend['idUser']?>" />
    
    <input type="hidden" class="username" value="<?=$_GET['username']?>">
   <div class="loading">
    
   <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
   </div>
    <!-- تغير الالوان -->
    <div class="app">
        <?php 
        $background = '';
        if($idManger == 3)
        {
            $background = "#892222";
        }
        if($idManger == 5)
        {
            $background = "#267c30";
        }

        $admin = $idManger == 2 ? "background:#004aad !important" : "background:#354562 !important";

        ?>
        <div class="header" style="<?=$admin?>;background:<?=$background?>">

        <div class="appHeader ">
            <a href="/_supervisor/">
            <img width="150"  src="<?=$infoSupervisor['image'] ? '_supervisor/'.$infoSupervisor['image']  : 'assets/images/supervisor.png' ?>" alt="">

            </a>
                <div class="info text-center">
                    <h5 style="color:<?=$idManger == 2 ?> ? #fff : #8eff8e;"><?= $infoSupervisor['username'] ?></h5>
                    <small style="cursor:pointer;font-size:13px;color:#ffea82;display:block" >مرشح انتخابات</small>

                    <p><?= $infocandidate['idEvent'] != 0 ? $infocandidate['nameEvent']  :'لا يوجد' ?></p>
                    
                    <?php 
                     $stmt = $db->db->prepare("SELECT * FROM counts WHERE idSupervisor = ?");
                     $stmt->execute([$infoSupervisor['id']]);                   
                    if( $rowFrontend['idUser'] == $infoSupervisor['id'] && $stmt->rowCount() > 0)
                    {
        
                        ?>
                          <small style="cursor:pointer;font-size:21px;color:#ffea82;"  class="get_counts">...</small>

                        <?php 
                    }
                    ?>

                </div>
            </div>
        </div>