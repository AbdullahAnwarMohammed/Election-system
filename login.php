<?php 
require_once("_supervisor/init.php");


$message = '';
$id = $_GET['id'];
$username = $_GET['username'];
if(!empty($_GET['username']) && !empty($_GET['id']) && !empty($_GET['rank'])){

if(isset($_SESSION['login']) AND $_SESSION['login'] == $username)
{
    header("location:_supervisor");
    exit;
}
        $prepare = $db->db->prepare("SELECT * FROM frontend WHERE id = ? ");
        $prepare->execute([$id]);
        $rowFrontend = $prepare->fetch();
        // $parent = $_GET['id'];
        if($rowFrontend['parent'] != NULL)
        {
          $parent = $rowFrontend['parent'];
        }else{
            $parent = $rowFrontend['idUser'];
 
        }

        if($rowFrontend && $rowFrontend['nameEnglish'] == $username)
        {
      
            if($_SERVER['REQUEST_METHOD'] == "POST")
            {
                $password = trim(strip_tags($_POST['password']));
                if($_GET['rank'] == 5)
                {
                    $first = $db->db->prepare("select * from daman where id = ?");
                    $first->execute([$rowFrontend['idUser']]);
                    $idSuperVisor = $first->fetch()['idSuperviosr'];
                     $stmt = $db->db->prepare("SELECT * FROM infocandidate WHERE idCandidate = ? AND password = ?");
                    $stmt->execute([$idSuperVisor,$password]);
                }else{
                    $stmt = $db->db->prepare("SELECT * FROM infocandidate WHERE idCandidate = ? AND password = ?");
                    $stmt->execute([$parent,$password]);
                }
                if($stmt->rowCount() > 0)
                {
                    $_SESSION['login'] = $_GET['username'];
                    header("location:index.php?username=".$_GET['username']."&id=".$_GET['id']);
                }else{
                    $message = 'يوجد خطأ فى كلمة المرور';
                }
            }
        }else{
          header("location:_supervisor");
            exit;
        }

    }else{
     header("location:_supervisor");
        exit;
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CP MAGLES</title>
    <style>
        body{
            background-color: #323e42;
        }
        .app{
            height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
        form{
            text-align: center;
            justify-content: center;
            align-items: center;
            display: flex;
            flex-direction: column;
            width: 50%;
        }
        input{
            padding: 20px 0;
            border: 1px solid #eee;
            color: green;
            font-size: 15px;
            width: 100%;
            border-radius: 10px;
            text-align: center;
        }
        input:focus{
            outline: none;
            border: none;
        }
      

        input[type="submit"]{
            background-color: #1b7479;
            color:#fff;
            border: none;
            cursor: pointer;
            width: 50%;
            margin: 20px 0;
            font-weight: bold;
            font-size: 20px;

        }
    </style>
</head>
<body>
   <div class="app">

    <?php 
    if(!empty($message))
    {
        ?>
        <div style="color:red"><?=$message?></div>
        <?php
    }
    ?>
    <img src="assets/images/icon.png" style="margin: 20px 0;" alt="" width="120">

   <form action="<?=$_SERVER['PHP_SELF']?>?username=<?=$username?>&id=<?=$id?>&rank=<?=$_GET['rank']?>" method="POST">
        <input type="password" name="password" placeholder="كلمة المرور">
        <input type="submit" value="تسجيل الدخول">
    </form>
   </div>
</body>
</html>