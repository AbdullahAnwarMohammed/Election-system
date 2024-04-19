<?php 
require_once "init.php";

$db = new db();
if(isset($_POST['addEvent']))
{

  
$name = trim($_POST['username']);
$desc = $_POST['desc'];
$admin = $_SESSION['superVisor'];
$startingDate = $_POST['startingDate'];
$expireDate = $_POST['expireDate'];

  $total_row = count(file($_FILES['database']['tmp_name']));
  $nameFile = $_FILES['database']['name'];
   $file_location = str_replace("\\", "/", $_FILES['database']['tmp_name']);

  $upload = new upload('image','uploads/events/',array('jpg','png'),'500000');

    $data = array(
    'name' => $name,
    'descElection' => $desc,
    'byAdmin' => $admin,
    'startingDate' => $startingDate,
    'expireDate' => $expireDate,
    'nameFile' => $nameFile
    );
    if($upload->checkIsUpload())
    {
    $upload->upload();
    $data['image'] = $upload->getDirection();
    }
    $data['numberVoters'] = $total_row;
    if(empty($upload->_errors)){
      if($db->checkNameEvent($name) !== false)
      {
      $id = $db->insertSuperVisor('events',$data);
      $message = "تم الاضافة بنجاح";
      }else{
      $message = 'اسم الحدث موجود من قبل';
      }
    }
   



    if(empty($upload->_errors) && !empty($_FILES['database']['name']))
      {
        if(is_uploaded_file($_FILES['databaseAllijan']['tmp_name']))
        {
          
      // $total_row = count(file($_FILES['databaseAllijan']['tmp_name']));
      // $nameFile = $_FILES['databaseAllijan']['name'];
      $file_location2 = str_replace("\\", "/", $_FILES['databaseAllijan']['tmp_name']);
          $query_1 = '
          LOAD DATA LOCAL INFILE "'.$file_location2.'" IGNORE 
          INTO TABLE databaseallijan 
          FIELDS TERMINATED BY "," 
          LINES TERMINATED BY "\r\n" 
          IGNORE 1 LINES 
          (@column1,@column2) 
          SET id_event="'.$id.'", name = @column1,
          idAllajna = @column2
          ';

          /*
          رقم الجنسية استبدالة ب الرقم المدني 

          */
          $statement = $db->db->prepare($query_1);
      
          $statement->execute();

        }
          if(is_uploaded_file($_FILES['database']['tmp_name']))
          {

           

          
            $query_1 = '
            LOAD DATA LOCAL INFILE "'.$file_location.'" IGNORE 
            INTO TABLE voters 
            FIELDS TERMINATED BY "," 
            LINES TERMINATED BY "\r\n" 
            IGNORE 1 LINES 
            (@column1,@column2,@column3,@column4,@column5,@column6,
            @column7,@column8,@column9) 
            SET idEvent="'.$id.'",fullName = @column1,
            gender = @column2,raqmAlqayd = @column3,
            nationalityNumber = @column4,familyName = @column5
            ,areaName = @column6,
            raqmAlsunduq=@column7,phone=@column8,allajna=@column9
            ';

            /*
            رقم الجنسية استبدالة ب الرقم المدني 

            */
            $statement = $db->db->prepare($query_1);
        
            $statement->execute();
          }else{
            $message = "من فضلك ارفع الملف";
          }
       }
      
    
     $output = array(
      'message' => $message
     );

     echo json_encode($output);
    
   
}


if(isset($_POST['update_event']))
{
 

  
   $supervisor = $db->getSingleInfo('events','id',$_POST['id']);
    $name = $_POST['name'];
    $desc = $_POST['desc'];
    $startingDate = $_POST['startingDate'];
    $expireDate = $_POST['expireDate'];
   $dataUpdate = array(
      'name' => $name,
      'descElection' => $desc,
      'startingDate' => $startingDate,
      'expireDate' => $expireDate,
    );


  

    if(!empty($_FILES['databaseCon']['name']) && !empty($_FILES['database']['tmp_name']))
    {
      echo '2database';
      exit;
    }

   $upload = new upload('image','uploads/events/',array('jpg','png','jpeg'),'10000000');
  
   if($upload->checkIsUpload())
    {
      $upload->removeImage($supervisor['image']);
      $upload->upload();
      $dataUpdate['image'] =  $upload->getDirection();          
    }

    if( !empty($_FILES['database']['name']) )
    {   
  
      $total_row = count(file($_FILES['database']['tmp_name']));
      $file_location = str_replace("\\", "/", $_FILES['database']['tmp_name']);
      $dataUpdate['numberVoters'] = $total_row;
      if(is_uploaded_file($_FILES['database']['tmp_name'])){
        $db->deleteSuperVisor('voters','idEvent',$_POST['id']);
        $query_1 = '
        LOAD DATA LOCAL INFILE "'.$file_location.'" IGNORE 
        INTO TABLE voters 
        FIELDS TERMINATED BY "," 
        LINES TERMINATED BY "\r\n" 
        IGNORE 1 LINES 
        (@column1,@column2,@column3,@column4,@column5,@column6,
        @column7,@column8,@column9) 
        SET idEvent="'.$_POST['id'].'",fullName = @column1,
        gender = @column2,raqmAlqayd = @column3,
        nationalityNumber = @column4,familyName = @column5
        ,areaName = @column6,
        raqmAlsunduq=@column7,phone=@column8,allajna=@column9
       
        ';
    
        $statement = $db->db->prepare($query_1);
    
       $statement->execute();
      

      }
    }
    

    if(!empty($_FILES['committees']['name']) )
    {

      $file_location_com = str_replace("\\", "/", $_FILES['committees']['tmp_name']);
      $db->deleteSuperVisor('databaseallijan','id_event',$_POST['id']);
      $query_1 = '
      LOAD DATA LOCAL INFILE "'.$file_location_com.'" IGNORE 
      INTO TABLE databaseallijan 
      FIELDS TERMINATED BY "," 
      LINES TERMINATED BY "\r\n" 
      IGNORE 1 LINES 
      (@column1,@column2) 
      SET id_event="'.$_POST['id'].'", name = @column1,
      idAllajna = @column2
      ';

      /*
      رقم الجنسية استبدالة ب الرقم المدني 

      */
      $statement = $db->db->prepare($query_1);
  
      $statement->execute();

    }




    if(!empty($_FILES['addCommittees']['name']) )
    {
      $file_location_add = str_replace("\\", "/", $_FILES['addCommittees']['tmp_name']);
      $query_1 = '
      LOAD DATA LOCAL INFILE "'.$file_location_add.'" IGNORE 
      INTO TABLE databaseallijan 
      FIELDS TERMINATED BY "," 
      LINES TERMINATED BY "\r\n" 
      IGNORE 1 LINES 
      (@column1,@column2) 
      SET id_event="'.$_POST['id'].'", name = @column1,
      idAllajna = @column2
      ';

      /*
      رقم الجنسية استبدالة ب الرقم المدني 

      */
      $statement = $db->db->prepare($query_1);
  
      $statement->execute();

    }

   
    if(!empty($_FILES['databaseCon']['name']) )
    {

      $total_row = count(file($_FILES['databaseCon']['tmp_name']));
      $file_location = str_replace("\\", "/", $_FILES['databaseCon']['tmp_name']);
      $dataUpdate['numberVoters'] = $supervisor['numberVoters'] + $total_row;
      if(is_uploaded_file($_FILES['databaseCon']['tmp_name'])){
        $query_1 = '
        LOAD DATA LOCAL INFILE "'.$file_location.'" IGNORE 
      INTO TABLE voters 
      FIELDS TERMINATED BY "," 
      LINES TERMINATED BY "\r\n" 
      IGNORE 1 LINES 
      (@column1,@column2,@column3,@column4,@column5,@column6,
      @column7,@column8,@column9) 
      SET idEvent="'.$_POST['id'].'",fullName = @column1,
      gender = @column2,raqmAlqayd = @column3,
      nationalityNumber = @column4,familyName = @column5
      ,areaName = @column6,
      raqmAlsunduq=@column7,phone=@column8,allajna=@column9
       
        ';
    
        $statement = $db->db->prepare($query_1);
    
       $statement->execute();

    }
  }
    if( !empty($_FILES['database']['name']) )
    {   
  
      $total_row = count(file($_FILES['database']['tmp_name']));
      $file_location = str_replace("\\", "/", $_FILES['database']['tmp_name']);
      $dataUpdate['numberVoters'] = $total_row;
      if(is_uploaded_file($_FILES['database']['tmp_name'])){
        $db->deleteSuperVisor('voters','idEvent',$_POST['id']);
        $query_1 = '
        LOAD DATA LOCAL INFILE "'.$file_location.'" IGNORE 
        INTO TABLE voters 
        FIELDS TERMINATED BY "," 
        LINES TERMINATED BY "\r\n" 
        IGNORE 1 LINES 
        (@column1,@column2,@column3,@column4,@column5,@column6,
        @column7,@column8,@column9) 
        SET idEvent="'.$_POST['id'].'",fullName = @column1,
        gender = @column2,raqmAlqayd = @column3,
        nationalityNumber = @column4,familyName = @column5
        ,areaName = @column6,
        raqmAlsunduq=@column7,phone=@column8,allajna=@column9
       
        ';
    
        $statement = $db->db->prepare($query_1);
    
       $statement->execute();
      

      }
    }
      $editcandidate = $db->getSingleInfo('infocandidate','nameEvent',$supervisor['name']);
      $editfrontend = $db->getSingleInfo('frontend','event',$supervisor['name']);
      if($editcandidate)
      {
        $db->update('infocandidate',array(
            'nameEvent' => $name
          ),array(
              'nameEvent' => $editcandidate['nameEvent']
          ));
      }
    
      if($editfrontend){
    $db->update('frontend',array(
            'event' => $name
        ),array(
            'event' => $editfrontend['event']
        ));
        }


    $db->update('events',$dataUpdate,array(
        'id'=> $_POST['id'],
    ));
    

}


?>