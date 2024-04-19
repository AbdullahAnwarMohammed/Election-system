<?php 
require_once "init.php"; 
$db = new DB();
if(isset($_POST['action']))
{
    // Get Madmen 
    if($_POST['action'] == 'getMadmen')
    {

        $sql = "
        SELECT * FROM voters INNER JOIN vote WHERE
        vote.idSupervisor = ?
        AND 
        vote.level = ?
        OR 
        vote.level = ?
        
        AND 
        vote.idUser = voters.id
        ";
            $get = $db->db->prepare($sql);
            $get->execute([$_SESSION['idSuperVisor'],'1','2']);
            $fetchAll = $get->fetchAll(PDO::FETCH_ASSOC);
            // $temp = array_unique(array_column($fetchAll, 'username'));
            // $fetchAll = array_intersect_key($fetchAll, $temp);
            $sub_array = array();
            $sub_array[] = $row["id"];
            $sub_array[] = $row["name"];
            $sub_array[] = $row["category_name"];
            $sub_array[] = $row["price"];
            $data[] = $sub_array;
            
            $output = array(
                "draw"    =>'',
                "recordsTotal"  =>  '',
                "recordsFiltered" => '',
                "data"    => $fetchAll
               );
               

                                
    }

    if($_POST['action'] == 'changePerson')
    {
        $folderPath = 'uploads/supervisor/';
        $image_parts = explode(";base64,", $_POST['image']);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = $folderPath . uniqid() . '.png';

        $removeImage = $db->db->prepare("SELECT * FROM `supervisor` WHERE id = ?");
        $removeImage->execute([$_SESSION['idSuperVisor']]);
       $fetch= $removeImage->fetch();
        $image = $fetch['image'];
        unlink($image);

        $removeImage = $db->db->prepare("update `supervisor` SET image = ? WHERE id = ?");
        $removeImage->execute([$file,$_SESSION['idSuperVisor']]);


       file_put_contents($file, $image_base64);
       echo json_encode(["image uploaded successfully."]);


    }

    if($_POST['action'] == 'showMadmen')
    {
        $table = '';
        $table .='
        
        <table class="table text-center">
        <tr>
        <th>الضامنين</th>
        <th>النوع</th>
        </tr>
        ';
       // Vote 
       $vote = $db->db->prepare("SELECT * FROM vote WHERE idUser = ? AND idSupervisor = ? ");
       $vote->execute([$_POST['idUser'],$_SESSION['idSuperVisor']]);
       $datas = $vote->fetchAll();
       foreach($datas as $data)
       {
        $ex1 = $db->db->prepare( "SELECT * FROM daman WHERE id = ? and idSuperviosr = ?");
        $ex1->execute([$data['idParent'],$_SESSION['idSuperVisor']]);
        if($ex1->rowCount() > 0)
        {
            $data = $ex1->fetch();
            $table .='
            <tr>
            <td>'.$data['username'].'</td>
            <td>مُتعهد</td>

            </tr>
            ';
        }
        else 
        {
            $ex2 = $db->db->prepare( "SELECT * FROM supervisor WHERE id = ?");
            $ex2->execute([$data['idParent']]);
            $rank = '';
            $data = $ex2->fetch();

            if($data['rank'] == '2')
            {
                $rank = 'مرشح';
            }
            
            if($data['rank'] == '3')
            {
                $rank = 'مفتاح';
            }
            
            

            if($ex2->rowCount() > 0)
            {
                $table .='
                <tr>
                <td>'.$data['username'].'</td>
                <td>'.$rank.'</td>
                </tr>
                ';
            }
            else 
            {
                echo 'notfound';
            }
        }

       }
       $table.='
       </table>
       ';
       echo $table;
       
    }

    if($_POST['action'] == 'showAttend')
    {
        $table = '';
        $table .='
        
        <table class="table text-center">
        <tr>
        <th>الضامنين</th>
        <th>النوع</th>
        </tr>
        ';
       // Vote 
       $vote = $db->db->prepare("SELECT * FROM vote WHERE idUser = ? AND level = ?");
       $vote->execute([$_POST['idUser'],'2']);
       $datas = $vote->fetchAll();
       foreach($datas as $data)
       {
        $ex1 = $db->db->prepare( "SELECT * FROM daman WHERE id = ?");
        $ex1->execute([$data['idParent']]);
        if($ex1->rowCount() > 0)
        {
            $data = $ex1->fetch();
            $table .='
            <tr>
            <td>'.$data['username'].'</td>
            <td>مُتعهد</td>

            </tr>
            ';
        }
        else 
        {
            $ex2 = $db->db->prepare( "SELECT * FROM supervisor WHERE id = ?");
            $ex2->execute([$data['idParent']]);
            $data = $ex2->fetch();

            if($data['rank'] == '2')
            {
                $rank = 'مرشح';
            }
            
            if($data['rank'] == '3')
            {
                $rank = 'مفتاح';
            }
            
            if($ex2->rowCount() > 0)
            {

                $table .='
                <tr>
                <td>'.$data['username'].'</td>
                <td>'.$rank.'</td>
                </tr>
                ';
            }
            else 
            {
                echo 'notfound';
            }
        }

       }
       $table.='
       </table>
       ';
       echo $table;
       
    }


    if($_POST['action'] == 'attendAction')
    {
        $type = $_POST['type'];
       if($type == 'attend')
       {
        echo actionAttend('2');
       }else{
        echo actionAttend('1');
       }
    }


    if($_POST['action'] == 'selectByljnaAttend')
    {
        echo x();

       if($_POST['attend'] == 'attend')
       {
       
       }else{

       }
    }
    if($_POST['action'] == 'selectByljna')
    {
        echo x();

    }

    // Deletes Backend 
    if($_POST['action'] == 'deleteEvent')
    {
    // جلب بيانات المرشح 
    $stmt1 = $db->db->prepare("select * from  infocandidate where idEvent = ?");
    $stmt1->execute([$_POST['id']]);
    
    // جلب بيانات كل الاشخاص اللى تحت المرشح
    foreach($stmt1->fetchAll() as $row)
    {
        // المرشح
        $id =  $row['idCandidate'];
        // جلب بيانات المضامين
        $stmt4 = $db->db->prepare("select * from `daman` where idSuperviosr = ?");
        $stmt4->execute([$id]);
         // تعديل البيانات ف جدول daman
        foreach($stmt4->fetchAll() as $row)
        {
            $stmt2 = $db->db->prepare("
            update frontend set 
            event = null
            where 
            idUser = ?
          
            ");
            $stmt2->execute([$row['id']]);
        }

        // تعديل البيانات ف جدول frontend
        $stmt2 = $db->db->prepare("
        update frontend set 
        event = null
        where 
        parent = ?
        or 
        idUser = ?
         
        ");
        $stmt2->execute([$id,$id]);
        // تعديل البيانات ف جدول infocandidate
        $stmt3 = $db->db->prepare("
        update infocandidate set 
        nameEvent = null,
        idEvent = 0
        where 
        idEvent = ?
        ");

        $stmt3->execute([$_POST['id']]);

        

    }
    $db->deleteSuperVisor('events','id',$_POST['id']);
    
    //   $dataEvent = $db->getSingleInfo('events','id',$_POST['id']);
    //   $data = $db->getSingleInfo('infocandidate','nameEvent' ,$dataEvent['name']);
    //   $getAll = $db->getAll('infocandidate','nameEvent' ,$dataEvent['name'],'yes');
       
    //   if(!isset($data['idCandidate'])){
    //     $db->deleteSuperVisor('events','id',$_POST['id']);
    //     $db->deleteSuperVisor('frontend','event',$dataEvent['name']); 
    //   }
      
      
    //  if(isset($data['idCandidate'])){
    
   
    //      $idCandidate = $data['idCandidate'];

    //      $data_m_s = $db->getAll('musharifin_candidate','idCandidate',$idCandidate);
    //   if($data_m_s){
    //     foreach($data_m_s as $row)
    //     {
    //       $db->deleteSuperVisor('daman','idMusharif',$row['idMusharifin']);
    //       $db->deleteSuperVisor('supervisor','id',$row['idMusharifin']);
    //       $db->deleteSuperVisor('supervisor','id',$row['idCandidate']);
    //     }
      
    //   }
   
    //   foreach($getAll as $row){
    //     $db->deleteSuperVisor('supervisor','id',$row['idCandidate']);
    //     $db->deleteSuperVisor('musharifin_candidate','idCandidate',$row['idCandidate']);
    //   }
    //    $db->deleteSuperVisor('infocandidate','nameEvent' ,$dataEvent['name']);
    //    $db->deleteSuperVisor('events','id',$_GET['id']);
    //    $db->deleteSuperVisor('frontend','event',$dataEvent['name']);



    //  }

  
    }

    if($_POST['action'] == 'deleteSuperVisor'){
        file_exists($db->getSingleInfo('supervisor','id',$_POST['id'])['image']) ? unlink( $Supervisor->getSingleInfo('id',$_GET['id'])['image']) : false;
        if($db->deleteSuperVisor('supervisor','id',$_POST['id']))
        {
          echo 'done';
        }    
    }


    if($_POST['action'] == 'deleteVoters')
    {
        if (isset($_POST['option_id'])) {
            foreach ($_POST['option_id'] as $option_id) {
                $db->deleteSuperVisor('voters','id',$option_id);

            }
        }
    }
    if($_POST['action'] == 'deleteCandidate')
    {
        $db->deleteSuperVisor('infocandidate','idCandidate',$_POST['id']) ;
                    $db->deleteSuperVisor('frontend','idUser',$_POST['id']) ;
                    $db->deleteSuperVisor('frontend','parent',$_POST['id']) ;
                     $data_m_c = $db->getAll('musharifin_candidate','idCandidate' , $_POST['id'],'yes');
                     if($data_m_c){
                     foreach($data_m_c as $row){
                    
                         $db->deleteSuperVisor('supervisor','id',$row['idMusharifin']);
                         $db->deleteSuperVisor('daman','idMusharif',$row['idMusharifin']);
                     }
                 }
             
                 if($Supervisor->deleteSuperVisor('id',$_POST['id']))
                 {
                     $db->deleteSuperVisor('musharifin_candidate','idCandidate',$_POST['id']);    
                 }
    }

    //musharifin.php?action=delete&id=<?=$row['id']
    if($_POST['action'] == 'deleteMusharifin')
    {
        $db->deleteSuperVisor('musharifin_candidate','idMusharifin',$_POST['id']);
        $db->deleteSuperVisor('daman','idMusharif',$_POST['id']);
        $db->deleteSuperVisor('frontend','parent',$_POST['id']);
        $db->deleteSuperVisor('frontend','idUser',$_POST['id']);  
        $db->deleteSuperVisor('supervisor','id',$_POST['id']);
        $db->deleteSuperVisor('powers','idUser',$_POST['id']);
          
    }
}

// فى حالة التحويل ما بين ارقم اللجان
function x()
{
        
    $data = '';
    global $db;
    if(!empty($_POST['attend']))
    {
        if($_POST['attend'] == 'attend')
        {
            if($_POST['val'] == 'all')
            {
            $al7dour = $db->db->prepare("
            SELECT DISTINCT voters.* FROM voters INNER JOIN vote ON
            voters.id = vote.idUser AND 
            vote.idSupervisor = ?  AND vote.level = 2
            ");
            $al7dour->execute([$_SESSION['idSuperVisor']]);
            }else{
                $al7dour = $db->db->prepare("
                SELECT DISTINCT voters.* FROM voters INNER JOIN vote ON
                voters.id = vote.idUser AND 
                vote.idSupervisor = ?  AND vote.level = 2 AND allajna = ?
                ");
                $al7dour->execute([$_SESSION['idSuperVisor'],$_POST['val']]);
            }
        }else{
            if($_POST['val'] == 'all')
            {
            $al7dour = $db->db->prepare("
            SELECT DISTINCT voters.* FROM voters INNER JOIN vote ON
            voters.id = vote.idUser AND 
            vote.idSupervisor = ?  AND vote.level = 1
            ");
            $al7dour->execute([$_SESSION['idSuperVisor']]);
            }else{
                $al7dour = $db->db->prepare("
                SELECT DISTINCT voters.* FROM voters INNER JOIN vote ON
                voters.id = vote.idUser AND 
                vote.idSupervisor = ?  AND vote.level IN(1,2) AND allajna = ?
                ");
                $al7dour->execute([$_SESSION['idSuperVisor'],$_POST['val']]);
            }
        }
        
    }else{
        if($_POST['val'] == 'all')
        {
        $al7dour = $db->db->prepare("
        SELECT DISTINCT voters.* FROM voters INNER JOIN vote ON
        voters.id = vote.idUser AND 
        vote.idSupervisor = ?  AND vote.level = 1 
        ");
        $al7dour->execute([$_SESSION['idSuperVisor']]);
        }else{
            $al7dour = $db->db->prepare("
            SELECT DISTINCT voters.* FROM voters INNER JOIN vote ON
            voters.id = vote.idUser AND 
            vote.idSupervisor = ?  AND vote.level IN(1,2) AND allajna = ?
            ");
            $al7dour->execute([$_SESSION['idSuperVisor'],$_POST['val']]);
        }
    }
    $fetchAl7dour = $al7dour->fetchAll();
    $countAl7dour = $al7dour->rowCount();
    $male = 0;$female = 0;
    foreach($fetchAl7dour as $row)
    {
        if($row['gender'] == 1)
        {
            $male +=1;
        }else{
          $female +=1;
        }
    }

      
  $data .= '
  <div class="text-end bg-dark text-white ">
        <span>ذكور:'.$male.'</span>
        <span>اناث:'.$female.'</span>
  </div>
  <table class="table table-primary table-striped">
                <tr>
                <th>#</th>
                <th>الاسم</th>
                </tr>
        ';

                $x = 0;
                foreach($fetchAl7dour as $row)
                {
                    // عدد المضامين
                    $a = $db->db->prepare(
                        "SELECT * FROM vote WHERE idUser = ?"
                    );
                    $a->execute([$row['id']]);
                    $count = $a->rowCount();
                    $changeColor = $row['gender'] == 1 ? "#062bb1" : "#c51334";

                    $x++;
           $data .='
                    <tr>
                    <td>'.$x.'</td>
                    <td><span style="color:'.$changeColor.'" class="fw-bold">'.$row['fullName'].'<span class="badge bg-success Smadmen"  data-id="'.$row['id'].'" data-bs-toggle="modal" data-bs-target="#madmen">'.$count.'</span></td>
                    </tr>
                    ';
                }
          $data .='</table>';
          


    return $data;
}

// فى حالة الضغط على زر حضور او غياب
function actionAttend($level)
{
    $data = '';
    global $db;
    $al7dour = $db->db->prepare("
    SELECT DISTINCT voters.* FROM voters INNER JOIN vote ON
    voters.id = vote.idUser AND 
    vote.idSupervisor = ?  AND vote.level = ?
    ");
    $al7dour->execute([$_SESSION['idSuperVisor'],$level]);
    $fetchAl7dour = $al7dour->fetchAll();
    $countAl7dour = $al7dour->rowCount();
    $male = 0;$female = 0;
    foreach($fetchAl7dour as $row)
    {
        if($row['gender'] == 1)
        {
            $male +=1;
        }else{
          $female +=1;
        }
    }

      
  $data .= '
  <div class="text-end bg-dark text-white ">
        <span>ذكور:'.$male.'</span>
        <span>اناث:'.$female.'</span>
  </div>
  <table class="table table-primary table-striped" id="printAttend">
                <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>اللجنة</th>
                </tr>
        ';

                $x = 0;
                
                foreach($fetchAl7dour as $row)
                {
                    // عدد المضامين
                    $a = $db->db->prepare(
                        "SELECT * FROM vote WHERE idUser = ?"
                    );
                    $a->execute([$row['id']]);
                    $count = $a->rowCount();
                    $changeColor = $row['gender'] == 1 ? "#062bb1" : "#c51334";

                    $x++;
           $data .='
                    <tr>
                    <td>'.$x.'</td>
                    <td><span style="color:'.$changeColor.'" class="fw-bold">'.$row['fullName'].'<span class="badge bg-success Smadmen"  data-id="'.$row['id'].'" data-bs-toggle="modal" data-bs-target="#madmen">'.$count.'</span></td>
                    <td>'.$row['allajna'].'</td>    

                    </tr>
                    ';
                }
          $data .='</table>';
          


    return $data;
}


/// Delete Event Alert 



?>  