<?php 
require_once "init.php"; 

if(isset($_POST['action']))
{



    if($_POST['action'] == 'load_data_search')
    {
        $idEvent = $_POST['idEvent'];
        $query2 = "
        SELECT * FROM vote WHERE idParent = ?
        ";
        $stmt2 = $db->db->prepare($query2);
        $stmt2->execute([$_POST['idParent']]);
        
        $query = "
        SELECT * FROM voters 
        WHERE idEvent = ".$_POST['idEvent']."
        ";
        foreach($stmt2->fetchAll() as $row)
        {
            $query .="
            AND id != ".$row['idUser']."
            ";
        }



        $input = trim($_POST['input']); // قيمة الحقل
        $selected = trim($_POST['selected']); // الاختيار باسم العائلة
        $type = $_POST['type']; // حالة البحث ب اسم او باسم العائلة او 
        
        $querySearch = "SELECT * FROM voters ";

        if($type == 'name_only')
        {

            $querySearch .= " WHERE familyName = ? AND idEvent = ?";
            // الاسم
            $params = array($input,$idEvent);
            $page = 'name_only';
        }

        if($type == 'name_family')
        {
            $querySearch .= " WHERE (familyName = ? AND fullName LIKE ?) AND idEvent = ?";
            // الاسم
            $params = array($selected,"$input%",$idEvent);

            $page = 'name_family';
        }

        if($type == 'family_only')
        {
            $querySearch .= "WHERE familyName = ? AND idEvent = ?";
            // الاسم
            $params = array($selected,$idEvent);

            $page = 'family_only';
        }
        $stmt = $db->db->prepare($querySearch);
        $stmt->execute($params);

        
      $familyname = empty($selected) ? '' : $selected;

      $results = $stmt->fetchAll();
      function compareByName($a, $b) {
          return strcmp($a["fullName"], $b["fullName"]);
        }
        usort($results, 'compareByName');



      $mad = 0;
      foreach($results as $row)
      {
            if($_POST['rank'] == 2)
            {
                $sql  = "SELECT DISTINCT username FROM vote WHERE idUser = ? ";
                $madmen = $db->db->prepare($sql);
                $madmen->execute([$row['id']]);
            }else{
                $sql  = "SELECT DISTINCT username FROM vote WHERE idUser = ? AND idParent = ?";
                $madmen = $db->db->prepare($sql);
                $madmen->execute([$row['id'],$_POST['idParent']]);
            }
          $fetch = $madmen->fetchAll();
          foreach($fetch as $fet)
          {
              
                  $mad++;
            
          }
          
      }

    
        $data = '
        <div class="parents">
        <div class="d-flex" style=" justify-content:space-between;align-items:center;flex-direction:row-reverse">
        <div class="appBtnPrint">
        <button class="btn-print-csv">CSV</button>
        <button class="btn-print-pdf">PDF</button>
        <button class="print" data-pagename="البحث عن عائلة '.$familyname.'">PRINT</button>
        </div>
   
        </div>
        <div class="table-responsive">
        <table id="table_search" class="styleTable printTable table table-danger table-striped">
          <thead>
          <tr>
                <th class="hidePdf">
                <input type="checkbox" data-target=".insideCheckbox"" class="checkall" />
                

        <span style="background:#ffd400;color:#15336a;
        right: 1px;
        padding: 4px;
        top: 46px;
        position: absolute;
        font-size:10px;
        "class="badge">'.$stmt->rowCount().'</span>

      ';

      if($mad != 0)
      {
        $data .='
        <span style="
        position: absolute;
        right: 48px;
        top: 45px;
         background: #198754;
        color: #fff;
        width: 17px;
        text-align: center;
        border-radius: 7px;
        ">
        
        '.$mad.'
        </span>';
      }
      $data .='
                </th>
                <th class="name "><div class="text-center">عوائل <span style="color:#61ffa9">'.$familyname.'</span>  </div></th>
                <th  class="hidePdf">العائلة</th>
                <th  class="hidePdf">مضمون</th>
            </tr>
          </thead>
          <tbody>
       
        ';
        $y=1;
     

        foreach($results as $row)
        {

            if($_POST['rank'] == 2)
            {
                $query3 = "SELECT * FROM vote WHERE idUser = ? AND idSupervisor = ? ";
                $stmt = $db->db->prepare($query3);
                $stmt->execute([$row['id'],$_POST['idSupervisor']]);
                $countVote = $stmt->fetchAll();
            }else{
                $query3 = "SELECT * FROM vote WHERE idUser = ? AND idSupervisor = ? AND idParent = ?";
                $stmt = $db->db->prepare($query3);
                $stmt->execute([$row['id'],$_POST['idSupervisor'],$_POST['idParent']]);
                $countVote = $stmt->fetchAll();
            }
        
            $count = '';
            if(count($countVote) > 0)
            {
                    $count = '<span  class="badge bg-success rounded-2"> '.count($countVote).'</span>';
            }
    


            $query = "SELECT * FROM vote WHERE idUser = ? AND idParent = ?";
            $stmt = $db->db->prepare($query);
            $stmt->execute([$row['id'],$_POST['idParent']]);
            $dataVote = $stmt->fetch();

            $changeColor = $row['gender'] == 1 ? "#062bb1" : "#c51334";
            $data .= 
            '
            <tr>
            <td>
            <div class="d-flex" style="gap:2px">
            <span class="styleNumberCount">'.($y++).'</span>

            <input type="checkbox"  class="checkboxMulti getVote insideCheckbox" 
            data-id = "'.$row['id'].'"
            data-userid = "'.$row['id'].'"
            data-idparent = "'.$_POST['idParent'].'"
            data-username = "'.$row['fullName'].'"
              multiple
            />
            <span class="hidePrint">' .$count. '</span>
            </div>
            </td>
            <td><a  style="color:'.$changeColor.'"  href="#" data-id='.$row['id'].' class="details" data-bs-toggle="modal" data-bs-target="#myChoseMadmen25">'.$row['fullName'].'</a></td>
            <td><a data-username="'.$row['fullName'].'" href="#" class="searchForAqarb text-dark"><i class="ri-parent-fill"></i></a></td>

            <td>
            ';
            if($dataVote && $dataVote['idParent'] == $_POST['idParent']){
                
                if($dataVote['level'] == 1)
                {
                $data.='
                <button  
                data-name="'.$row['fullName'].'"                                                
                data-iduser = "'.$row['id'].'"
                data-idparent = "'. $_POST['idParent'].'"
                data-username = "'.$row['fullName'].'"
                data-level = "2"
                 href="#"
                class="btn-success myBtnTable  resBtn resetVote">
                <i class="ri-close-line"></i></button>
                ';

                }
            else{
                $data .='
                <button     class=" btn-primary myBtnTable">
                <i class="ri-check-line"></i>
                </button>
                '; 

                }
                
                
                $data .= '
                </td>
                </tr>
                ';
           
            
            }
        }
        $page = isset($page) ? $page : '';
        
     
        $data .='
        </tbody>
        </table>
        </div>
        <div class="insertMulti">
        <select class="valueSelectMulti" id="valueList">
            <option value="insertVote">مضمون</option>
            <option value="delete">غير مضمون</option>
            <option disabled>----</option>
            ';
            $lists = $db->getRows('list',array(
                'where' => array('idParent'=>$_POST['idUser']),
                'return_type' => 'count'
            ));
            if($lists > 0){
                $Lists = $db->getAll('list','idParent',$_POST['idUser'],true);
                if($Lists !== false){
                    foreach($Lists as $row){
             
                        $data .='
                        <option class="lists" value="insertlist" data-value='.$row['id'].'>'.$row['name'].'</option>
                         ';
                       
                      
                        }
                }
                
            }
        $data .='
        </select>
        <button  class="btnMultiVote" data-page='.$page.'>تطبيق</button>
        </div> 
        ';
        $data .="</div>";
         echo $data;
    }

    if($_POST['action'] == 'updateVoter')
    {   
        $idParent = $_POST['idParent'];
        $idUser = $_POST['idUser'];
        if($_POST['rank'] == '4' || $_POST['rank'] == '2')
        {
            $actionVote = $db->db->prepare(
                "
                UPDATE voters SET
                attend = ?
                WHERE 
                id = ?
                "
            );
            $actionVote->execute(['1',$idUser]);

            $update = $db->update('vote',array(
                'level' => '2'
            ),array(
                'idUser' => $idUser,
                'idSupervisor' => $_POST['idSupervisor']
            ));

        }else{

            $actionVote = $db->db->prepare(
                "
                UPDATE voters SET
                attend = ?
                WHERE 
                id = ?
                "
            );
            $actionVote->execute(['1',$idUser]);

        $update = $db->update('vote',array(
            'level' => '2'
        ),array(
            'idUser' => $idUser,
            'idParent' => $idParent
        ));



        }

        if($update !== false)
        {
            echo 'done';
        }else{
            echo 'error';
        }

    }

    
     
    if($_POST['action'] == 'deleteVote')
    {
        $idParent = $_POST['idParent'];
        $idUser = $_POST['idUser'];
        $stmt = $db->db->prepare("DELETE FROM vote WHERE 
        idUser = ? AND idParent = ?
        ");
        $Delete = $stmt->execute([$_POST['idUser'],$_POST['idParent']]);

        if($Delete){
            echo 'done';
        }else{
            echo 'error';
        }
    }

    if($_POST['action'] == 'insertSearchToList')
    {
        $idUsers = $_POST['idUser'];
        $idParent = $_POST['idParent'];
        $idList = $_POST['idList'];
        foreach($idUsers as $row)
        {
            $checkFound = $db->db->prepare(
                "SELECT * FROM listcontent WHERE idUser = ? AND idParent = ?"
            );
    
            $checkFound->execute([$row,$idParent]);
            if(!$checkFound->rowCount() > 0)
            {
                $insert = $db->insertSuperVisor('listcontent',array(
                'idUser' => $row,
                'idParent' => $idParent,
                'idList' => $idList,
                'idFrontend' => $_POST['idFrontend'],
                'idSupervisor' => $_POST['idSupervisor']
            ));
                $db->update('voters',array(
                'have_list' => '1',
                ),array(
                'id'=>$row
            ));
            $db->update('vote',array(
                'have_list' => '1',
                ),array(
                'idUser'=>$row
            ));
        }
        }  
     
        if($insert){
                echo 'done';
        }
      
             
       
    
    }

    if($_POST['action'] == 'multiVote')
    {

        if($_POST['type'] == "insert")
        {

                $idUsers =  $_POST['idUsers'];
                $idParent = $_POST['idParent'];
                $username = $_POST['names'];
                $idSupervisor = $_POST['idSupervisor'];
               
              foreach($idUsers as $key=>$value)
              {
              
            
                $UpdateLevelOne = $db->db->prepare("
              UPDATE vote SET level = 1
                WHERE 
               idUser = ?
                AND 
                  idSupervisor = ? 
                ");
                   $UpdateLevelOne->execute([$idUsers[$key],$idSupervisor]);

            
             
                }
                if($UpdateLevelOne)
                {
                    echo 'done';
                }

                      
        }     
        else if($_POST['type'] == "insertVote")
        {

            $idUsers =  $_POST['idUsers'];
            $idParent = $_POST['idParent'];
            $username = $_POST['names'];

                
                foreach($idUsers as $key=>$value)
                {
                        $checkExists = $db->db->prepare(
                            "SELECT * FROM vote WHERE idUser = ? AND idParent = ?"
                        );
                        $checkExists->execute([$idUsers[$key],$idParent]);
                        if(!$checkExists->rowCount() > 0)
                        {
                            $Vote = $db->db->prepare("
                            INSERT INTO vote(idUser,idParent,username,level,idFrontend,idSupervisor) VALUES(?,?,?,?,?,?)
                        ");
                        $Vote->execute([$idUsers[$key],$idParent,$username[$key],'1',$_POST['idFrontend'],$_POST['idSupervisor']]);

                        }

                
                }
           
            if($Vote)
            {
                echo 'done';
            }

    

               
              

                      
        }     
       
       
        elseif($_POST['type'] == 'deleteFromList'){
          
            $idUsers =  $_POST['idUsers'];
            foreach($idUsers as $row)
            {
             $idParent = $_POST['idParent'];
            $db->update('voters',array(
                'have_list' => '0'
            ),array(
                'id'=>$row
            ));
            $db->update('vote',array(
                'have_list' => '0'
            ),array(
                'idUser'=>$row
            ));

            $Delete = $db->db->prepare(
                "DELETE FROM listcontent WHERE idUser = ? AND idParent = ?"
            );
            $Delete->execute([$row,$idParent]);
            }
           
            if($Delete){
                echo 'done';
            }
        }
        // else if($_POST['type'] == "insertLevel2")
        // {
        //     $idUsers =  $_POST['idUsers'];
        //     $idParent = $_POST['idParent'];
        //     $idSupervisor = $_POST['idSupervisor'];

        //     foreach($idUsers as $row)
        //     {
              
        //         if($_POST['rank'] != 2)
        //         {
                    
        //             $query = $db->db->prepare(
        //                 "
        //                 SELECT * FROM vote WHERE level = ?
        //                 AND idUser = ? 
        //                 AND idParent = ?
        //                 "
        //             );
        //             $query->execute(['1',$row,$idParent]);
        //         }else{
        //             $query = $db->db->prepare(
        //                 "
        //                 SELECT * FROM vote WHERE level = ?
        //                 AND idUser = ? 
        //                 AND idSupervisor = ?
        //                 "
        //             );
        //             $query->execute(['1',$row,$idSupervisor]);
        //         }
             
        //         if($_POST['rank'] != 2)
        //         {
        //             if($query->rowCount() > 0)
        //             {
        //                $Vote->update('vote',array(
        //                 'level' => '2'
        //                ),array(
        //                 'idUser' => $row,
        //                 'idParent' => $idParent
        //                ));
        //             }

        //         }else{
        //             if($query->rowCount() > 0)
        //             {
        //                $Vote->update('vote',array(
        //                 'level' => '2'
        //                ),array(
        //                 'idUser' => $row,
        //                 'idSupervisor' => $idSupervisor
        //                ));
        //             }
        //         }
              
        //     }
        //     if($Vote)
        //     {
        //         echo 'done';
        //     }
          
        // }
        else if($_POST['type'] == "NotPresent")
        {
            $idUsers =  $_POST['idUsers'];
            $idParent = $_POST['idParent'];
            foreach($idUsers as $row)
            {
                if($_POST['rank'] == 4 || $_POST['rank'] == 2)
                {
                    $query = $db->db->prepare(
                        "
                        SELECT * FROM vote WHERE level = ?
                        AND idUser = ? 
                        AND idSupervisor = ?
                        "
                    );
                    $query->execute(['2',$row,$_POST['idSupervisor']]);
                }else{
              
                $query = $db->db->prepare(
                    "
                    SELECT * FROM vote WHERE level = ?
                    AND idUser = ? 
                    AND idParent = ?
                    "
                );
                $query->execute(['2',$row,$idParent]);
    
                 }
    
    
                if($query->rowCount() > 0)
                {
    
                    
                    if($_POST['rank'] == 4 || $_POST['rank'] == 2)
                    {
                        $db->update('vote',array(
                            'level' => '1'
                           ),array(
                            'idUser' => $row,
                            'idSupervisor' => $_POST['idSupervisor']
                           ));
    
                    }else{
                     
    
                           $db->update('vote',array(
                            'level' => '1'
                           ),array(
                            'idUser' => $row,
                            'idParent' => $idParent
                           ));
                    }
                
            }
           

            }
            echo 'done';

        }
        else 
        {
           
            $idUsers =  $_POST['idUsers'];
            $idSupervisor =  $_POST['idSupervisor'];
            $idParent =  $_POST['idParent'];

            if($_POST['rank'] == 2)
            {
                foreach($idUsers as $row){
                    $Delete = $db->deleteSuperVisor('vote','idUser',$row);
                    $Delete = $db->db->prepare("
                    DELETE FROM vote WHERE idUser = ?
                    AND 
                    idSupervisor = ?
                    ");
                    $Delete->execute([$row,$idSupervisor]);
                }
            }else{
                foreach($idUsers as $row){
                    $Delete = $db->deleteSuperVisor('vote','idUser',$row);
                    $Delete = $db->db->prepare("
                    DELETE FROM vote WHERE idUser = ?
                    AND 
                    idParent = ?
                    AND 
                    idSupervisor = ?
                    
                    ");
                    $Delete->execute([$row,$idParent,$idSupervisor]);
                }
            }
         
            if($Delete){
                echo 'done';
            }else{
                echo 'error';
            }
      
        }
    }


    if($_POST['action'] == 'searchForAqarb')
    {

        $query2 = "
     SELECT * FROM vote WHERE idParent = ?
     ";
    $stmt2 = $db->db->prepare($query2);
    $stmt2->execute([$_POST['idParent']]);

    $query = "
    SELECT * FROM voters 
    WHERE idEvent = ".$_POST['idEvent']."
    ";
    foreach($stmt2->fetchAll() as $row)
    {
        $query .="
        AND id != ".$row['idUser']."
        ";
    }


    

        $username =  trim($_POST['username']);
        $arrValue = explode(" ",$username);
        $pttern = "/$arrValue[0]/i";
         $serach = preg_replace($pttern,"",$username,1);
        $pttern2 = "/$arrValue[0] $arrValue[1]/i";
        $search2 = preg_replace($pttern2,"",$username,1);

   
        $stmt = $db->db->prepare(
            "
            SELECT * FROM voters
            WHERE    fullName LIKE  :search OR  fullName LIKE  :search2 AND voters.idEvent = ".$_POST['idEvent']."
           
            "
        );
        $stmt->execute([':search'=>$serach."%",':search2'=>"%".$search2]);
        if($stmt->rowCount() > 0)
        {
            $data = '
            <div class="table-responsive parents">
            <div class="d-flex" style=" justify-content:space-between;align-items:center;flex-direction:row-reverse">
            <div class="appBtnPrint">
            <button class="btn-print-csv">CSV</button>
            <button class="btn-print-pdf">PDF</button>
            <button class="print" data-pagename="البحث عن عائلة '.$username.'">PRINT</button>
            </div>
            </div>
                    <table id="table_search" class="styleTable table table-success table-striped">
          <thead>
          <tr>
                <th class="hidePdf"><input type="checkbox" class="checkall" /></th>
                <th class="name">
                <div class="text-center">
                عائلة <span style="color:#61ffa9">'.$username.' </span> <span style="background:#7fdcfd;color:#15336a" class="badge  rounded-0">'.$stmt->rowCount().'</span>
                </div>
                </th>
                <th class="hidePdf">مضمون</th>
            </tr>
          </thead>
          <tbody>
       
        ';
            $y=1;
        foreach($stmt->fetchAll() as $row)
        {

            
            if($_POST['rank'] == 2)
            {
                $query3 = "SELECT * FROM vote WHERE idUser = ? AND idSupervisor = ? ";
                $stmt = $db->db->prepare($query3);
                $stmt->execute([$row['id'],$_POST['idSupervisor']]);
                $countVote = $stmt->fetchAll();
            }else{
                $query3 = "SELECT * FROM vote WHERE idUser = ? AND idSupervisor = ? AND idParent = ?";
                $stmt = $db->db->prepare($query3);
                $stmt->execute([$row['id'],$_POST['idSupervisor'],$_POST['idParent']]);
                $countVote = $stmt->fetchAll();
            }

            
        $count = '';
        if(count($countVote) > 0)
        {
                $count = '<span  class="badge bg-success rounded-2"> '.count($countVote).'</span>';
        }
    


            $query = "SELECT * FROM vote WHERE idUser = ? AND idParent = ?";
            $stmt = $db->db->prepare($query);
            $stmt->execute([$row['id'],$_POST['idParent']]);
            $dataVote = $stmt->fetch();

            $changeColor = $row['gender'] == 1 ? "#062bb1" : "#c51334";
            $data .= 
            '
            <tr>
            <td >
            <div class="responsive-name">
            <span class="styleNumberCount">'.($y++).'</span>

            <input type="checkbox"  class="checkboxMulti getVote insideCheckbox" 
            data-id = "'.$row['id'].'"

            data-userid = "'.$row['id'].'"
            data-idparent = "'.$_POST['idParent'].'"
            data-username = "'.$row['fullName'].'"
            />
            '.$count.'
            </div>
            </td>
            <td ><a href="#" style="padding:0 5px;color:'.$changeColor.'"  data-id="'.$row['id'].'" class="details" data-bs-toggle="modal" data-bs-target="#myChoseMadmen25">'.$row['fullName'].'</a></td>

            <td>
            ';
            if($dataVote && $dataVote['idParent'] == $_POST['idParent']){
                
                if($dataVote['level'] == 1)
                {
                $data.=' <button  data-name="'.$row['fullName'].'"  data-page="aqarbNameOnly"
                class=" btn-success myBtnTable resetVote  "  data-page="true"    data-iduser="'.$row['id'].'"> <i class="ri-close-line"></i>  </button>
                ';
                /*               
*/
                }
            else{
                $data .='
                <button     class="  btn-primary myBtnTable btn-success  resetVote  ">
                <i class="ri-check-line"></i>
                </button>
                '; 
                /*                <button  data-name="'.$row['fullName'].'"   data-page="aqarbNameOnly"
                class="btn btn-danger resetVote   btn-sm" data-page="true"     data-iduser="'.$row['id'].'"> <i class="ri-close-line"></i>  </button>
*/
                }
                
                
                $data .= '
                </td>
                </tr>
                ';
           
            
            }
        }
        $data .='
        </tbody>
        </table>
        </div>
        <input type="hidden" class="usernameSA" value="'.$username.'" />
        <div class="insertMulti">
        <select class="valueSelectMulti" id="valueList">

            <option value="insert">مضمون</option>
            <option value="delete">غير مضمون</option>
            <option disabled>----</option>
            ';
            $lists = $db->getRows('list',array(
                'where' => array('idParent'=>$_POST['idUser']),
                'return_type' => 'count'
            ));
            if($count > 0){
                $Lists = $db->getAll('list','idParent',$_POST['idUser'],true);
                if($Lists !== false){
                    foreach($Lists as $row){
             
                        $data .='
                        <option class="lists" value="insertlist" data-value='.$row['id'].'>'.$row['name'].'</option>
                         ';
                       
                      
                        }
                }
                
            }
        $data .='
        </select>
        <button  class="btnMultiVote"  data-page="aqarbNameOnly">تطبيق</button>
        </div> 
        ';
         echo $data;
        }
        else 
        {
            echo 'notfound';
        }

    }

    // Datatable Insert MultiVote

    
    if($_POST['action'] == 'updateVoteLevelOneByCircle')
    {
        $idUser =  $_POST['idUser'];
        $idParent = $_POST['idParent'];
        $username =$_POST['username'];
        $idSupervisor = $_POST['idSupervisor'];
        $idFrontend = $_POST['idFrontend'];

        $checkIfExist = $db->db->prepare("
        SELECT * FROM vote WHERE idUser = ? 
        AND idParent = ? 
        ");

         $checkIfExist->execute([$idUser,$idParent]);
        // !== false لو موجود
        if($checkIfExist->rowCount() == 0)
        {
               $insert = $db->insertSuperVisor('vote',array(
                    'idUser' => $idUser,
                    'idParent' => $idParent,
                    'username' => $username,
                    'level' => 1,
                    'idFrontend' => $idFrontend,
                    'idSupervisor' => $idSupervisor
                ));   
        }
        if($insert)
        {
            echo 'done';
        }

    }
    if($_POST['action'] == 'insertVoteMulti')
    {
        //	idUser	idParent	username	level	
        $idUsers =  $_POST['idUsers'];
        $idParent = $_POST['idParent'];
        $username =$_POST['names'];
        $level = $_POST['level'];
        $idSupervisor = $_POST['idSupervisor'];
        $insert = "no";

        foreach($idUsers as $key=>$val)
        {
            $insert = "no";
            $vote = $db->getSingleInfo('vote','username',$username[$key]);
            $checkIfExist = $db->db->prepare("
            SELECT * FROM vote WHERE idUser = ? 
            AND idParent = ? 
            ");

             $checkIfExist->execute([$val,$idParent]);
            // !== false لو موجود
            if($checkIfExist->rowCount() == 0)
            {
                   $insert = $db->insertSuperVisor('vote',array(
                        'idUser' => $val,
                        'idParent' => $idParent,
                        'username' => $username[$key],
                        'level' => $level,
                        'idFrontend' => $_POST['idFrontend'],
                        'idSupervisor' => $idSupervisor
                    ));   
            }
          
          
         
        }

        if($insert !== false)
        {
        echo 'done';
        }
       
        

      
    }
    // deleteVoteMulti 
    if($_POST['action'] == 'deleteVoteMulti')
    {
        $idUser = $_POST['idUser'];
        $idParent = $_POST['idParent'];
        foreach($idUser as $row){
            $check = $db->db->prepare("SELECT * FROM voters WHERE id = ? AND attend = ? ");
            $check->execute([$row,'0']);
            if($check->rowCount() > 0)
            {
                if($_POST['idSupervisor'] == $idParent)
                {
                   
                    $sql = "
                    DELETE FROM vote WHERE idUser = ? AND idSupervisor = ? 
                    ";
                    $Delete = $db->db->prepare($sql);
                    $Delete->execute([$row,$_POST['idSupervisor']]);
    
                }else{
                    $sql = "
                    DELETE FROM vote WHERE idUser = ? AND idParent = ? 
                    ";
                    $Delete = $db->db->prepare($sql);
                    $Delete->execute([$row,$idParent]);
                }
            }
          
           
           
        }
        if(isset($Delete)){
            echo 'done';
        }else{
            echo 'no';
        }
    }

    

     // presetnmain  main page 
     if($_POST['action'] == 'presetnmain')
     {
         
         $idUsers =  $_POST['idUsers'];
         $idParent = $_POST['idParent'];
         $rank = $_POST['rank'];
         foreach($idUsers as $row)
         {
            if($rank == '4' || $rank == '2')
            {
                $query = $db->db->prepare(
                    "
                    SELECT * FROM vote WHERE level = ?
                    AND idUser = ? 
                    AND idSupervisor = ?
                    "
                );
                $query->execute(['1',$row,$_POST['idSupervisor']]);
            }else{
                $query = $db->db->prepare(
                    "
                    SELECT * FROM vote WHERE level = ?
                    AND idUser = ? 
                    AND idParent = ?
                    "
                );
                $query->execute(['1',$row,$idParent]);
            }
            
            if($query->rowCount() > 0)
            {
            if($rank == '4' || $rank == '2')
            {


                $actionVote = $db->db->prepare(
                    "
                    UPDATE voters SET
                    attend = ?
                    WHERE 
                    id = ?
                    "
                );
                $actionVote->execute(['1',$row]);


                    $db->update('vote',array(
                    'level' => '2'
                    ),array(
                    'idUser' => $row,
                    'idSupervisor' => $_POST['idSupervisor']
                    ));
                    
            }else{

                $actionVote = $db->db->prepare(
                    "
                    UPDATE voters SET
                    attend = ?
                    WHERE 
                    id = ?
                    "
                );
                $actionVote->execute(['1',$row]);


                $db->update('vote',array(
                    'level' => '2'
                    ),array(
                    'idUser' => $row,
                    'idParent' => $idParent
                    ));
            }
    
            
         }
       
        
         }
         if($Vote)
         {
            echo 'done';
         }
 
     }

    // delete present main page 
    if($_POST['action'] == 'notpresetnmain')
    {
        
        $idUsers =  $_POST['idUser'];
        $idParent = $_POST['idParent'];
        $rank = $_POST['rank'];
        foreach($idUsers as $idUser)
        {
            if($rank == 4 || $rank == 2)
            {
                $query = $db->db->prepare(
                    "
                    SELECT * FROM vote WHERE level = ?
                    AND idUser = ? 
                    AND idSupervisor = ?
                    "
                );
                $query->execute(['2',$idUser,$_POST['idSupervisor']]);
            }else{
                $query = $db->db->prepare(
                    "
                    SELECT * FROM vote WHERE level = ?
                    AND idUser = ? 
                    AND idParent = ?
                    "
                );
                $query->execute(['2',$idUser,$idParent]);
            }
       
            if($query->rowCount() > 0)
            {
            if($rank == '4' || $rank == '2')
            {
                $actionVote = $db->db->prepare(
                    "
                    UPDATE voters SET
                    attend = ?
                    WHERE 
                    id = ?
                    "
                );
                $actionVote->execute(['0',$idUser]);

                   $db->update('vote',array(
                    'level' => '1'
                   ),array(
                    'idUser' => $idUser,
                    'idSupervisor' => $_POST['idSupervisor']
                   ));
            }else{

                $actionVote = $db->db->prepare(
                    "
                    UPDATE voters SET
                    attend = ?
                    WHERE 
                    id = ?
                    "
                );
                $actionVote->execute(['0',$idUser]);


                $db->update('vote',array(
                    'level' => '1'
                   ),array(
                    'idUser' => $idUser,
                    'idParent' => $idParent
                   ));
            }
    
        }
           
          
        }

        if($Vote)
        {
         echo 'done';
        }

    } 


    if($_POST['action'] == "insertLevel2")
        {
            $idUsers =  $_POST['idUsers'];
            $idParent = $_POST['idParent'];
            $idSupervisor = $_POST['idSupervisor'];
            $names = $_POST['names'];
            $idFrontend = $_POST['idFrontend'];
            foreach($idUsers as $key=>$value)
            {
                $checkIfMadmoen = $db->db->prepare("SELECT * FROM vote WHERE idUser = ? AND idSupervisor = ?");
                $checkIfMadmoen->execute([$value,$idSupervisor]);

                 // مضمون
            if($checkIfMadmoen->rowCount() > 0)
            {
                $actionVote = $db->db->prepare(
                    "
                    UPDATE voters SET
                    attend = ?
                    WHERE 
                    id = ?
                    "
                );
                $actionVote->execute(['1',$value]);

                
                
                $action = $db->db->prepare(
                    "
                    UPDATE vote SET
                    level = ?
                    WHERE 
                    idUser = ?
                    AND 
                    idSupervisor = ?
                    "
                );
                $action->execute([2,$value,$idSupervisor]);
            } 
            else 
            {
                $actionVote = $db->db->prepare(
                    "
                    UPDATE voters SET
                    attend = ?
                    WHERE 
                    id = ?
                    "
                );
                $actionVote->execute(['1',$value]);

                $action = $db->db->prepare("
                INSERT INTO vote(`idUser`, `idParent`, `username`, `level`, `idFrontend`, `idSupervisor`) 
                VALUES(?,?,?,?,?,?)
                ");
                $action->execute([$value,$idParent,$names[$key],2,$idFrontend,$idSupervisor]);
            }
            

            }
           
            if($action)
            {
                echo 'done';
            }


        }

     // Up Vote 1 Level Mandob
     if($_POST['action'] == 'resVoteBackMandob')
     {

        $actionVote = $db->db->prepare(
            "
            UPDATE voters SET
            attend = ?
            WHERE 
            id = ?
            "
        );
        $actionVote->execute(['0',$_POST['idUser']]);


         $stmt = $db->db->prepare("UPDATE vote SET 
         level = ?  WHERE  idUser = ? AND idSupervisor = ? 
         ");
         $Delete = $stmt->execute([1,$_POST['idUser'],$_POST['idSupervisor']]);
         if($Delete){
             echo 'done';
         }

        
     }


     // Up Vote 1 Level
     if($_POST['action'] == 'resVoteBack')
     {
        $actionVote = $db->db->prepare(
            "
            UPDATE voters SET
            attend = ?
            WHERE 
            id = ?
            "
        );
        $actionVote->execute(['0',$_POST['idUser']]);

         $stmt = $db->db->prepare("UPDATE vote SET 
         level = ? WHERE idUser = ? AND idSupervisor = ? 
         ");
         $Delete = $stmt->execute([1,$_POST['idUser'],$_POST['idSupervisor']]);
         if($Delete){
             echo 'done';
         }
        
     }

      // Delete One Vote User
    if($_POST['action'] == 'resetVote')
    {
     

        $stmt = $db->db->prepare("DELETE FROM vote WHERE 
        idUser = ? AND idParent = ?
        ");
        $Delete = $stmt->execute([$_POST['idUser'],$_POST['idParent']]);
        if($Delete){
            echo 'done';
        }
       
    }

    // Delete One Vote User
    if($_POST['action'] == 'resetVoteMandob')
    {
        $stmt = $db->db->prepare("DELETE FROM vote WHERE 
        idUser = ? AND idSupervisor = ?
        ");
        $Delete = $stmt->execute([$_POST['idUser'],$_POST['idSupervisor']]);
        if($Delete){
            echo 'done';
        }
       
    }
    // Update One Vote
    if($_POST['action'] == 'updateVote')
    {
        $idParent = $_POST['idParent'];
        $idUser = $_POST['idUser'];
        $level = $_POST['level'];
        $update = $db->update('vote',array(
            'level' => $level
        ),array(
            'idUser' => $idUser,
            'idParent' => $idParent
        ));
        
        if($update !== false)
        {
            echo 'done';
        }
    }

    // Create New List
    if($_POST['action'] == 'insertList')
    {
        //action=insertList&idParent=61&nameList=&typeList=1
        $idParent = $_POST['idParent'];
        $nameList = trim($_POST['nameList']);
        $typeList = $_POST['typeList'];
        $idEvent = $_POST['idEvent'];
        $check = $db->db->prepare("SELECT * FROM list WHERE name = ? AND idParent = ?");
        $check->execute([$nameList,$idParent]);


        // التحقق من ان الاسم ليس موجود من قبل 
        if($check->rowCount() > 0)
        {
            echo 'found';
        }else{
            $insert = $db->insertSuperVisor('list',array(
                'idParent' => $idParent,
                'name' => $nameList,
                'type' => $typeList,
                'idFrontend' => $idEvent,
            ));
            if($insert)
            {
                echo 'done';
            }
        }

      
    }

    // Insert User For List
    if($_POST['action'] == 'insertVoteList')
    {

    $idUsers = $_POST['idUser'];
    $idParent = $_POST['idParent'];
    $idList = $_POST['idList'];

      
        foreach($idUsers as $row){
    $check = $db->db->prepare("SELECT * FROM listcontent WHERE idUser = ? AND idList = ?");
    $check->execute([$row   ,$idList]);
        if($check->rowCount() == 0)
        {                        
            $insert = $db->insertSuperVisor('listcontent',array(
                'idUser' => $row,
                'idParent' => $idParent,
                'idList' => $idList,
                'idFrontend' => $_POST['idFrontend'],
                'idSupervisor' => $_POST['idSupervisor']
            ));
            

                $db->update('voters',array(
                    'have_list' => '1',
                ),array(
                    'id'=>$row
                ));

                $db->update('vote',array(
                    'have_list' => '1',
                ),array(
                    'idUser'=>$row
                ));

            

        }
    }

    if($insert){
        echo 'done';
    }


            
        }

    


    // فتح القائمة
    if($_POST['action'] == 'openlist')
    {

        $query2 = "
        SELECT * FROM vote WHERE idParent = ?
        ";
        $stmt2 = $db->db->prepare($query2);
        $stmt2->execute([$_POST['idParent']]);

        $query = "
        SELECT * FROM voters 
        WHERE idEvent = ".$_POST['idEvent']."
        ";
        foreach($stmt2->fetchAll() as $row)
        {
            $query .="
            AND id != ".$row['idUser']."
            ";
        }


      $stmt = $db->db->prepare('SELECT DISTINCT voters.* FROM voters
      INNER JOIN listcontent ON listcontent.idUser = voters.id
      AND listcontent.idList = '.$_POST['idList'].' ');
     $stmt->execute();
     $fetchAll = $stmt->fetchAll();

     $datalistcontent = $stmt->fetch();

     // معرفة اسم القائمة
     $listcontent = $db->getSingleInfo('list','id',$_POST['idList']);

     $data = ''; 

  

     if($stmt->rowCount() > 0){
        $mad = 0;
         foreach($fetchAll as $row)
        {
            if($_POST['rank'] == 2)
            {
                $sql  = "SELECT DISTINCT username FROM vote WHERE idUser = ? ";
                $madmen = $db->db->prepare($sql);
                $madmen->execute([$row['id']]);
            }else{
                $sql  = "SELECT DISTINCT username FROM vote WHERE idUser = ? AND idParent = ?";
                $madmen = $db->db->prepare($sql);
                $madmen->execute([$row['id'],$_POST['idParent']]);
            }
          
            $fetch = $madmen->fetchAll();
            foreach($fetch as $fet)
            {
                    $mad++;
            }
            
        }
       
       
        $data .= '
        <div class="table-responsive parents">
        <div class="d-flex" style=" justify-content:space-between;align-items:center;flex-direction:row-reverse">
        <div class="appBtnPrint">
        <button class="btn-print-csv">CSV</button>
        <button class="btn-print-pdf">PDF</button>
        <button class="print" data-pagename="القوائم قائمة '.$listcontent['name'].'">PRINT</button>
        </div>
   
        </div>
        <table id="table_search" class="styleTable table table-warning table-striped tablelistopen">
          <thead>
          <tr>
                <th class="hidePdf">
                <input type="checkbox" data-target=".insideCheckbox"" class="checkall" />
                <span style="background:#ffd400;color:#15336a;
                right: 1px;
                padding: 4px;
                top: 45px;
                position: absolute;
                "class="badge">'.$stmt->rowCount().'</span>


                <span style="
                position: absolute;
                right: 48px;
                top: 45px;
                 background: #198754;
                color: #fff;
                width: 17px;
                text-align: center;
                border-radius: 7px;
                ">'.$mad.'</span>
                </th>
                <th class="name text-center">قائمة <span class="badge bg-dark rounded-0"></span><span style="color:#61ffa9">'.$listcontent['name'].'</span></th>
                <th  class="hidePdf">العائلة</th>
                <th  class="hidePdf">
                ';
                $data .='<span style="
                position: absolute;
                top: 20px;
                color: #fff;
                background: #0d6efd;
                border-radius: 5px;
                left:0;
                font-size:14px
                ">حضور</span>';
                $number = 0;
                foreach($fetchAll as $row)
                {
                    $getAttend = $db->db->prepare(
                        "SELECT * FROM vote WHERE level = ? AND idUser = ?
                        AND idParent = ?
                        "
                    );
                    $getAttend->execute([2,$row['id'],$_POST['idParent']]);
                    if($getAttend->rowCount() > 0)
                    {
                        $number++;
                    }
                }
               
                $data.='
                <span style="
                       background: #0d6efd;
                       color:#fff;
                       padding:0 5px;
                       border-radius:3px;
                ">'.$number.'</span>
                ';
                $data.='
                </th>
            </tr>
          </thead>
          <tbody>
        ';
        $y=1;

        function compareByName($a, $b) {
            return strcmp($a["fullName"], $b["fullName"]);
          }
          usort($fetchAll, 'compareByName');


     foreach($fetchAll as $row)
     {
      
        if($_POST['rank'] == 2)
        {
            $query3 = "SELECT * FROM vote WHERE idUser = ? AND idSupervisor = ? ";
            $stmt = $db->db->prepare($query3);
            $stmt->execute([$row['id'],$_POST['idSupervisor']]);
            $countVote = $stmt->fetchAll();
        }else{
            $query3 = "SELECT * FROM vote WHERE idUser = ? AND idSupervisor = ? AND idParent = ?";
            $stmt = $db->db->prepare($query3);
            $stmt->execute([$row['id'],$_POST['idSupervisor'],$_POST['idParent']]);
            $countVote = $stmt->fetchAll();
        }
    
        $count = '';
        if(count($countVote) > 0)
        {
                $count = '<span  class="badge bg-success rounded-2"> '.count($countVote).'</span>';
        }
    
        

        $x = "SELECT * FROM vote WHERE idUser = ? AND idParent = ?";
        $stmt = $db->db->prepare($x);
        $stmt->execute([$row['id'],$_POST['idParent']]);
       $dataVote = $stmt->fetch();

       $changeColor = $row['gender'] == 1 ? "#062bb1" : "#c51334";


          // هنا التعديل
          $query = "SELECT * FROM listcontent WHERE idParent = ? AND idList = ?";
          $countAll = $db->db->prepare($query);
          $countAll->execute([$_POST['idParent'],$_POST['idList']]);


       $data .= 
       '
       <tr>
       <td>
       <div class="d-flex" style="gap:2px">
       
       <span class="styleNumberCount">'.($y++).'</span>
       <input type="checkbox"  class="checkboxMulti insideCheckbox"   data-id='.$_POST['idList'].'
       data-userid = "'.$row['id'].'"
       data-idparent = "'.$_POST['idParent'].'"
       data-username = "'.$row['fullName'].'"
       />
       '.$count.'

       </div>
       </td>
       <td><a href="#"  style="color:'.$changeColor.'" data-id="'.$row['id'].'" class="details" data-bs-toggle="modal" data-bs-target="#myChoseMadmen25">'.$row['fullName'].'</a></td>
       <td><a data-username="'.$row['fullName'].'" href="#" class="searchForAqarb text-dark"><i class="ri-parent-fill"></i></a></td>

       <td>
     
       ';

  if($dataVote && $dataVote['idParent'] == $_POST['idParent']){

        if($dataVote['level'] == 1)
        {
            $data.='
            <button  data-name="'.$row['fullName'].'"   data-page="refreshopenlist"  data-id='.$_POST['idList'].' 
            class=" btn-success resetVote  myBtnTable "    data-iduser="'.$row['id'].'"> <i class="ri-close-line"></i>  </button>
            
            
            ';
            /*
            */
        }else{
            $data .='
            <button     class=" btn-primary    myBtnTable">
            <i class="ri-check-line"></i>
            </button>
           
            '; 
            /* <button  data-name="'.$row['fullName'].'"  
            class="btn btn-danger resetVote   btn-sm"   data-page="refreshopenlist"  data-id='.$_POST['idList'].'    data-iduser="'.$row['id'].'"> <i class="ri-close-line"></i>  </button>*/
            
        }
        $data .= '
        </td>
        </tr>
        ';

}

  
        
     }
     $data .='
     </tbody>
     </table>
     </div>
      </div>
      </div>
      ';
  

            $data .='
            <div class="insertMulti">
            <select class="valueSelectMulti">
                <option value="insertVote">مضمون</option>
                <option value="delete">غير مضمون</option>
                <option class="bg-danger text-white" data-id="refreshopenlist" value="deleteFromList">حذف من القائمة</option>
            </select>
            <button  class="btnMultiVote" data-page="refreshopenlist" data-id='.$_POST['idList'].'>تطبيق</button>
            </div> 
            ';
    }else{
        $data = 'zero';
    }
   
     echo $data;
    }


     // My mandub 
     if($_POST['action'] == 'mandub')
     {
         $query2 = "
         SELECT * FROM vote WHERE idParent = ?
         ";
         $stmt2 = $db->db->prepare($query2);
         $stmt2->execute([$_POST['idParent']]);
         
        
     
       
             $query = "
             SELECT * FROM voters 
             WHERE idEvent = ".$_POST['idEvent']."
             ";
       
        
         foreach($stmt2->fetchAll() as $row)
         {
             $query .="
             AND id != ".$row['idUser']."
             ";
         }
 
 
 
 
      
         if(empty($_POST['change']) || $_POST['change'] == "all")
         {
             $stmt = $db->db->prepare('
             SELECT voters.* FROM voters INNER JOIN vote ON voters.id = vote.idUser AND vote.idSupervisor = ?
             ');
            $stmt->execute([$_POST['idSupervisor']]);
           
         }else{
             $stmt = $db->db->prepare('
             SELECT voters.* FROM voters INNER JOIN vote ON voters.id = vote.idUser AND vote.idSupervisor = ?
             AND voters.allajna = ?
             ');
             $stmt->execute([$_POST['idSupervisor'],$_POST['change']]);
         }
 
     
      
      $data = ''; 
      if($stmt->rowCount() > 0){
         $data .= '
         <div class="parents">
         <div class="d-flex " style=" justify-content:space-between;align-items:center">
 
         <h4>مضامين <span class="badge bg-dark mt-2">'.$stmt->rowCount().'
 
         </span>
         <select id="changeChosemandub" style="font-size:16px">
         ';
         if($_POST['change'] == 'all')
         {
             $data .= '
             <option disabled value="all">اختيار اللجنة</option>
             <option value="all" selected>الجميع</option>
             ';
         }else{
             $data .= '
             <option disabled selected value="all">اختيار اللجنة</option>
             <option value="all">الجميع</option>
             ';
         }
         $getAllajna = $db->db->prepare(
             "SELECT DISTINCT(allajna) FROM voters WHERE allajna != ?
             AND idEvent = ?
             "
         );
         $getAllajna->execute([' ',$_POST['idEvent']]);
         foreach($getAllajna->fetchAll() as $row)
         {
         if($_POST['change'] == $row['allajna'])
         {
             $data .= '
             <option  selected value="'.$row['allajna'].'">'.$row['allajna'].'</option>
             ';
         }
         else{
             $data .= '
             <option   value="'.$row['allajna'].'">'.$row['allajna'].'</option>
             ';
         }
         
         }
         ?>
        
         
         <?php 
         $data .='
 
         </select>
         </h4>
         <div class="appBtnPrint">
         <button class="btn-print-csv">CSV</button>
         <button class="btn-print-pdf">PDF</button>
         <button class="print" data-pagename="المضامين">PRINT</button>
         </div>
 
         </div>
 
     
         <div class="table-responsive ">
         <table id="table_search" class="styleTable table table-primary table-striped">
           <thead>
           <tr>
             <th class="hidePdf"><span class="showPrint">تسلسل</span><input type="checkbox" data-target=".insideCheckbox"" class="checkall" /></th>
                 <th class="name">الاسم</th>
                 <th class="hidePdf">حضور</th>
             </tr>
           </thead>
           <tbody>
         ';
      $Power = $db->getSingleInfo('powers','idUser',$_POST['idParent']);
      $disabled = ($Power !== false && $Power['prepare_madmen'] == 0) ? 'disabled' : '';
      $y = 1;
      foreach($stmt->fetchAll() as $row)
      {
 
         $x = "SELECT * FROM vote WHERE idUser = ? AND idSupervisor = ?";
         $stmt = $db->db->prepare($x);
         $stmt->execute([$row['id'],$_POST['idSupervisor']]);
        $dataVote = $stmt->fetch();
        
        $changeColor = $row['gender'] == 1 ? "#062bb1" : "#c51334";
 
        
     $query3 = "SELECT * FROM vote WHERE idUser = ? AND idSupervisor = ? ";
     $stmt = $db->db->prepare($query3);
     $stmt->execute([$row['id'],$_POST['idSupervisor']]);
     $countVote = $stmt->fetchAll();
     $count = '';
     if(count($countVote) > 0)
     {
             $count = '<span  class="badge bg-success rounded-2"> '.count($countVote).'</span>';
     }
 
        $data .= 
        
        '
        <tr>
        <td>
        <div class="d-flex" style="gap:2px">
        <span class="styleNumberCount">'.($y++).'</span>
 
        <input type="checkbox"  class="checkboxMulti insideCheckbox"  
        data-userid = "'.$row['id'].'"
        data-idparent = "'.$_POST['idParent'].'"
        data-username = "'.$row['fullName'].'"
        />
        <span class="hidePrint">'.$count.'</span>
        </div>
        </td>
        <td><a href="#" data-id="'.$row['id'].'" style="color:'.$changeColor.'"  class="details" data-bs-toggle="modal" data-bs-target="#modalDetails">'.$row['fullName'].'</a></td>
  
        <td >
      
        ';
 
   if($dataVote){
         if($dataVote['level'] == 1)
         {
             $data.='<button  
             data-names="'.$row['fullName'].'"                                                
             data-iduser = "'.$row['id'].'"
             data-idparent = "'. $_POST['idParent'].'"
             data-username = "'.$row['fullName'].'"
              href="#"
              data-page="mandub"
             '.$disabled.'
             class=" btn-success myBtnTable  votemandub" >
             <i class="ri-hand-heart-fill"></i>
            </button>
             ';
             /*            <button  data-name="'.$row['fullName'].'"   data-page="mychose" 
 class="btn btn-danger resetVote  btn-sm "    data-iduser="'.$row['id'].'"> <i class="ri-close-line"></i>  </button>
 */
         }else{
             $data .='
 
             <button 
             data-names="'.$row['fullName'].'"                                                
             data-iduser = "'.$row['id'].'"
             data-idparent = "'. $_POST['idParent'].'"
             data-username = "'.$row['fullName'].'"   
             
             class="btn-primary resVoteBackmandob  myBtnTable">
             <i class="ri-check-line"></i>
             </button>
             '; 
             /*            <button  data-name="'.$row['fullName'].'"  
             class="btn btn-danger resetVote   btn-sm"   data-page="mychose" data-iduser='.$row['id'].'> <i class="ri-close-line"></i>  </button>
 */
             
         }
         $data .= '
         </td>
         </tr>
         ';
 
 }
 
         
      }
      $data .='
      </tbody>
      </table>
      </div>
       </div>
       </div>
       ';
   
 
             $data .='
             <div class="insertMulti">
             <select class="valueSelectMulti">
                 <option value="insert2">مضمون</option>
                 <option value="delete">غير مضمون</option>
                 <option value="NotPresent">غير حاضر</option>
                 <option disabled>----</option>
                 ';
                 $lists = $db->getRows('list',array(
                     'where' => array('idParent'=>$_POST['idUser']),
                     'return_type' => 'count'
                 ));
                 if($lists > 0){
                     $Lists = $db->getAll('list','idParent',$_POST['idUser'],true);
                     if($Lists !== false){
                         foreach($Lists as $row){
                  
                             $data .='
                             <option class="lists" value="insertlist" data-value='.$row['id'].'>'.$row['name'].'</option>
                              ';
                            
                           
                             }
                     }
                     
                 }else{
                     echo 'no';
                 }
             $data .='
             </select>
             <button  class="btnMultiVote" data-page="mandub" >تطبيق</button>
             </div> 
             ';
     }else{
 
         $data = '
  
         <select id="changeChosemandub" style="font-size:16px">
         ';
         if($_POST['change'] == 'all')
         {
             $data .= '
             <option disabled value="all">اختيار اللجنة</option>
             <option value="all" selected>الجميع</option>
             ';
         }
         else
         {
             $data .= '
             <option disabled selected value="all">اختيار اللجنة</option>
             <option value="all">الجميع</option>
             ';
         }
         $getAllajna = $db->db->prepare(
             "SELECT DISTINCT(allajna) FROM voters WHERE allajna != ?
             AND idEvent = ?
             "
         );
         $getAllajna->execute([' ',$_POST['idEvent']]);
         foreach($getAllajna->fetchAll() as $row)
         {
         if($_POST['change'] == $row['allajna'])
         {
             $data .= '
             <option  selected value="'.$row['allajna'].'">'.$row['allajna'].'</option>
             ';
         }else{
             $data .= '
             <option   value="'.$row['allajna'].'">'.$row['allajna'].'</option>
             ';
         }
         
         }
         $data .='
 
         </select>';
     }
     $data .="</div>";
      echo $data;
     }
     

    // My Chose 
    if($_POST['action'] == 'mychose')
    {

        if($_POST['idParent'] == $_POST['idSupervisor'])
        {
            $query2 = "
            SELECT * FROM vote WHERE idSupervisor = ?
            ";
            $stmt2 = $db->db->prepare($query2);
            $stmt2->execute([$_POST['idSupervisor']]);
        }else{
            $query2 = "
            SELECT * FROM vote WHERE idParent = ?
            ";
            $stmt2 = $db->db->prepare($query2);
            $stmt2->execute([$_POST['idParent']]);
        }
       
    
      
            $query = "
            SELECT * FROM voters 
            WHERE idEvent = ".$_POST['idEvent']."
            ";
      
       
        foreach($stmt2->fetchAll() as $row)
        {
            $query .="
            AND id != ".$row['idUser']."
            ";
        }

        if($_POST['change'] != "" AND $_POST['maleOrFemale'] == '')
        {
            if($_POST['idParent'] == $_POST['idSupervisor'])
            {
                if($_POST['change'] != 'all')
                {
                    $stmt = $db->db->prepare('
                    SELECT voters.* FROM voters INNER JOIN vote ON voters.id = vote.idUser AND vote.idSupervisor = ?
                    AND voters.allajna = ?
                    ');
                    $stmt->execute([$_POST['idSupervisor'],$_POST['change']]);

                }else{
                    $stmt = $db->db->prepare('
                    SELECT voters.* FROM voters INNER JOIN vote ON voters.id = vote.idUser AND vote.idParent = ? AND vote.idSupervisor = ?
                    ');
                    $stmt->execute([$_POST['idSupervisor']]);

                }
               
            }else{
                if($_POST['change'] != 'all')
                {

                    $stmt = $db->db->prepare('
                    SELECT voters.* FROM voters INNER JOIN vote ON voters.id = vote.idUser AND vote.idParent = ?
                    AND voters.allajna = ? AND vote.idSupervisor = ?
                    ');
                    $stmt->execute([$_POST['idParent'],$_POST['change'],$_POST['idSupervisor']]);

                }else{

                    $stmt = $db->db->prepare('
                    SELECT voters.* FROM voters INNER JOIN vote ON voters.id = vote.idUser AND vote.idParent = ? 
                    AND vote.idSupervisor = ?
                    ');
                    $stmt->execute([$_POST['idParent'],$_POST['idSupervisor']]);

                }
            }   
        }
        else if($_POST['change'] == "" AND $_POST['maleOrFemale'] == '')
        {
            if($_POST['idParent'] == $_POST['idSupervisor'])
            {
                $stmt = $db->db->prepare('
                SELECT voters.* FROM voters INNER JOIN vote ON voters.id = vote.idUser AND vote.idSupervisor = ?
                ');
               $stmt->execute([$_POST['idSupervisor']]);
            }else{
                $stmt = $db->db->prepare('
                SELECT voters.* FROM voters INNER JOIN vote ON voters.id = vote.idUser AND vote.idParent = ?
                AND vote.idSupervisor = ?
                ');
               $stmt->execute([$_POST['idParent'],$_POST['idSupervisor']]);
            }          
        }else if($_POST['change'] != "" && $_POST['change'] != "all" && $_POST['maleOrFemale'] == 'all')
        {
            if($_POST['idParent'] == $_POST['idSupervisor'])
            {
                $stmt = $db->db->prepare('
                SELECT voters.* FROM voters INNER JOIN vote ON voters.id = vote.idUser AND vote.idSupervisor = ?
                AND voters.allajna = ?
                ');
               $stmt->execute([$_POST['idSupervisor'],$_POST['change']]);
            }else{
                $stmt = $db->db->prepare('
                SELECT voters.* FROM voters INNER JOIN vote ON voters.id = vote.idUser AND vote.idParent = ?
                AND vote.idSupervisor = ?
                ');
               $stmt->execute([$_POST['idParent'],$_POST['idSupervisor']]);
            }    
        }else if($_POST['change'] == "all" AND $_POST['maleOrFemale'] == 'all')
        {
            if($_POST['idParent'] == $_POST['idSupervisor'])
            {
                $stmt = $db->db->prepare('
                SELECT voters.* FROM voters INNER JOIN vote ON voters.id = vote.idUser AND vote.idSupervisor = ?
                ');
               $stmt->execute([$_POST['idSupervisor']]);
            }else{
                $stmt = $db->db->prepare('
                SELECT voters.* FROM voters INNER JOIN vote ON voters.id = vote.idUser AND vote.idParent = ?
                AND vote.idSupervisor = ?
                ');
               $stmt->execute([$_POST['idParent'],$_POST['idSupervisor']]);
            }      
        }
       
       else{
            if($_POST['idParent'] == $_POST['idSupervisor'])
            {
             
                if($_POST['change'] == 'all')
                {
                    $stmt = $db->db->prepare('
                    SELECT voters.* FROM voters INNER JOIN vote ON voters.id = vote.idUser AND vote.idSupervisor = ?
                     AND voters.gender = ?
                    ');
                    $stmt->execute([$_POST['idSupervisor'],$_POST['maleOrFemale']]);                    
                }else{
                    $stmt = $db->db->prepare('
                    SELECT voters.* FROM voters INNER JOIN vote ON voters.id = vote.idUser AND vote.idSupervisor = ?
                    AND voters.allajna = ? AND voters.gender = ?
                    ');
                    $stmt->execute([$_POST['idSupervisor'],$change,$_POST['maleOrFemale']]);
                }
            }else{
                $stmt = $db->db->prepare('
                SELECT voters.* FROM voters INNER JOIN vote ON voters.id = vote.idUser AND vote.idParent = ?
                AND voters.allajna = ?  AND voters.gender = ?  AND vote.idSupervisor = ?
                ');
                $stmt->execute([$_POST['idParent'],$_POST['change'],$_POST['maleOrFemale'],$_POST['idSupervisor']]);
            }
            

        }

        $result = $stmt->fetchAll();
       
        $temp = array_unique(array_column($result, 'fullName'));
        $results = array_intersect_key($result, $temp);
     $data = ''; 
     if($stmt->rowCount() > 0){
        $data .= '
        <div class="parents">
        <div class="d-flex " style=" justify-content:space-between;align-items:center">


        </span>
        <div>
        ';

        $data .='
        <select id="changeChose" style="font-size:16px">
        
        ';
        if($_POST['change'] == 'all')
        {
            $data .= '
            <option disabled value="all">اختيار اللجنة</option>
            <option value="all" selected>الجميع</option>
            ';
        }else{
            $data .= '
            <option disabled selected value="all">اختيار اللجنة</option>
            <option value="all">الجميع</option>
            ';
        }
        $getAllajna = $db->db->prepare(
            "SELECT DISTINCT(allajna) FROM voters WHERE allajna != ?
            AND idEvent = ?
            "
        );
        $getAllajna->execute([' ',$_POST['idEvent']]);
        foreach($getAllajna->fetchAll() as $row)
        {
        if($_POST['change'] == $row['allajna'])
        {
            $data .= '
            <option  selected value="'.$row['allajna'].'">'.$row['allajna'].'</option>
            ';
        }
        else{
            $data .= '
            <option   value="'.$row['allajna'].'">'.$row['allajna'].'</option>
            ';
        }
        
        }
        ?>
       
        
        <?php 
        $data .='

        </select>
        </div>
        </h4>
        <div class="appBtnPrint">
        <button class="btn-print-csv">CSV</button>
        <button class="btn-print-pdf">PDF</button>
        <button class="print" data-pagename="المضامين">PRINT</button>
        </div>

        </div>

    
        <div class="table-responsive ">
     
        
        
      
        <table id="table_search" class="styleTable table table-primary table-striped">
     ';
     if($_POST['rank'] == 2){
        $data .='
        <select id="maleOrFemaleChose" >
        ';
        if($_POST['maleOrFemale'] == '')
        {
            $data .='
            <option  selected disabled value="all">الجنس</option>
            <option  value="all">الكل</option>
            <option value="1">ذكور</option>
            <option value="2">آناث</option>
            ';
        }
         if($_POST['maleOrFemale'] == 'all')
        {
            $data .='
            <option   disabled value="all">الجنس</option>
            <option selected  value="all">الكل</option>
            <option value="1">ذكور</option>
            <option value="2">آناث</option>
            '; 
        }
        if($_POST['maleOrFemale'] == '1'){
            $data .='
               <option  disabled value="all">الجنس</option>
                <option value="all">الكل</option>
                <option selected value="1">ذكور</option>
                <option value="2">آناث</option>
                ';
          
        }
        if($_POST['maleOrFemale'] == '2'){
            $data .='
             <option  disabled value="all">الجنس</option>
             <option value="all">الكل</option>
             <option  value="1">ذكور</option>
             <option selected value="2">آناث</option>
            ';
        }
        $data .='
     
        </select>
        ';
    }
       $data.='
          <thead>

         
            <tr style="position:relative">
              

        
                <th class="hidePdf"><span class="showPrint">تسلسل</span><input type="checkbox" data-target=".insideCheckbox"" class="checkall" /></th>
                <th class="name">اسماء المضامين <span class="badge" style="background:#7fdcfd;color:#15336a">'.count($results).'</span></th>
                <th class="hidePdf">حضور</th>
            </tr>
          </thead>
          <tbody>
        ';
     $Power = $db->getSingleInfo('powers','idUser',$_POST['idParent']);
     $disabled = ($Power !== false && $Power['prepare_madmen'] == 0) ? 'disabled' : '';
     $y = 1;
     function compareByName($a, $b) {
        return strcmp($a["fullName"], $b["fullName"]);
      }
      usort($results, 'compareByName');

     foreach($results as $row)
     {
        if($_POST['rank'] == 2)
        {
            $x = "SELECT * FROM vote WHERE idUser = ? AND idSupervisor = ?";
            $stmt = $db->db->prepare($x);
            $stmt->execute([$row['id'],$_POST['idSupervisor']]);
        }
        else{
            $x = "SELECT * FROM vote WHERE idUser = ? AND idParent = ?";
            $stmt = $db->db->prepare($x);
            $stmt->execute([$row['id'],$_POST['idParent']]);
        }
        
   
    foreach($stmt->fetchAll() as $item)
    {
      $dataVote = $item;
    }

      
        

       $changeColor = $row['gender'] == 1 ? "#062bb1" : "#c51334";

       if($_POST['rank'] == 2)
       {
           $query3 = "SELECT * FROM vote WHERE idUser = ? AND idSupervisor = ? ";
           $stmt = $db->db->prepare($query3);
           $stmt->execute([$row['id'],$_POST['idSupervisor']]);
           $countVote = $stmt->fetchAll();
       }else{
        
           $query3 = "SELECT * FROM vote WHERE idUser = ? AND idSupervisor = ? AND idParent = ?";
           $stmt = $db->db->prepare($query3);
           $stmt->execute([$row['id'],$_POST['idSupervisor'],$_POST['idParent']]);
           $countVote = $stmt->fetchAll();
       }
       

    $count = '';
    if(count($countVote) > 0)
    {
            $count = '<span  class="badge bg-success rounded-2"> '.count($countVote).'</span>';
    }

       $data .= 
       
       '
       <tr>
       <td>
       <div class="d-flex" style="gap:2px">
       <span class="styleNumberCount">'.($y++).'</span>

       <input type="checkbox"  class="checkboxMulti getVote insideCheckbox"  
       data-id = "'.$row['id'].'"

       data-userid = "'.$row['id'].'"
       data-idparent = "'.$_POST['idParent'].'"
       data-username = "'.$row['fullName'].'"
       />
       <span class="hidePrint">'.$count.'</span>
       </div>
       </td>
       <td><a href="#" data-id="'.$row['id'].'" style="color:'.$changeColor.'"  class="details" data-bs-target="#myChoseMadmen25" data-bs-toggle="modal" data-bs-dismiss="modal" >'.$row['fullName'].'</a></td>
 
       <td >
     
       ';

       if($_POST['rank'] == 2)
       {
        $check = $dataVote['idSupervisor'] == $_POST['idSupervisor'] ? true : false;
       }else{
        $check = $dataVote['idParent'] == $_POST['idParent'] ? true : false ;
       }

  if($dataVote && $check ){
        if($_POST['rank'] != '4' AND $_POST['rank'] != '2')
        {   
            if($dataVote['level'] == 1)
            {
                $data.='<button  
                data-names="'.$row['fullName'].'"                                                
                data-iduser = "'.$row['id'].'"
                data-idparent = "'. $_POST['idParent'].'"
                data-username = "'.$row['fullName'].'"
                 href="#"
                 data-page="mychose"
                '.$disabled.'
                class=" btn-success myBtnTable  " >
                <i class="ri-hand-heart-fill"></i>
               </button>
                ';
                /*            <button  data-name="'.$row['fullName'].'"   data-page="mychose" 
    class="btn btn-danger resetVote  btn-sm "    data-iduser="'.$row['id'].'"> <i class="ri-close-line"></i>  </button>
    */
            }else{
                $data .='
    
                <button 
                data-names="'.$row['fullName'].'"                                                
                data-iduser = "'.$row['id'].'"
                data-idparent = "'. $_POST['idParent'].'"
                data-username = "'.$row['fullName'].'"   
                
                class="btn-primary   myBtnTable">
                <i class="ri-check-line"></i>
                </button>
                '; 
                /*            <button  data-name="'.$row['fullName'].'"  
                class="btn btn-danger resetVote   btn-sm"   data-page="mychose" data-iduser='.$row['id'].'> <i class="ri-close-line"></i>  </button>
    */
                
            }

        }else{
            if($dataVote['level'] == 1)
            {
                $data.='<button  
                data-names="'.$row['fullName'].'"                                                
                data-iduser = "'.$row['id'].'"
                data-idparent = "'. $_POST['idParent'].'"
                data-username = "'.$row['fullName'].'"
                 href="#"
                 data-page="mychose"
                '.$disabled.'
                class=" btn-success myBtnTable  vote" >
                <i class="ri-hand-heart-fill"></i>
               </button>
                ';
                /*            <button  data-name="'.$row['fullName'].'"   data-page="mychose" 
    class="btn btn-danger resetVote  btn-sm "    data-iduser="'.$row['id'].'"> <i class="ri-close-line"></i>  </button>
    */
            }else{
                $data .='
    
                <button 
                data-names="'.$row['fullName'].'"                                                
                data-iduser = "'.$row['id'].'"
                data-idparent = "'. $_POST['idParent'].'"
                data-username = "'.$row['fullName'].'"   
                
                class="btn-primary resVoteBack  myBtnTable">
                <i class="ri-check-line"></i>
                </button>
                '; 
                /*            <button  data-name="'.$row['fullName'].'"  
                class="btn btn-danger resetVote   btn-sm"   data-page="mychose" data-iduser='.$row['id'].'> <i class="ri-close-line"></i>  </button>
    */
                
            }
        }


        $data .= '
        </td>
        </tr>
        ';

}

        
     }
     $data .='
     </tbody>
     </table>
     </div>
      </div>
      </div>
      ';
  

            $data .='
            <div class="insertMulti">
            <select class="valueSelectMulti">
            ';
            if($_POST['rank'] != 2)
            {
                $data.='
                <option value="delete">غير مضمون</option>
                ';
              
            }else{
                $data.='
                <option value="insert2">حاضر</option>
                <option value="delete">غير مضمون</option>
                <option value="NotPresent">غير حاضر</option>
            ';
            }
         

            $data.='
                <option disabled>----</option>
                ';
                $lists = $db->getRows('list',array(
                    'where' => array('idParent'=>$_POST['idUser']),
                    'return_type' => 'count'
                ));
                if($lists > 0){
                    $Lists = $db->getAll('list','idParent',$_POST['idUser'],true);
                    if($Lists !== false){
                        foreach($Lists as $row){
                 
                            $data .='
                            <option class="lists" value="insertlist" data-value='.$row['id'].'>'.$row['name'].'</option>
                             ';
                           
                          
                            }
                    }
                    
                }
            $data .='
            </select>
            <button  class="btnMultiVote" data-page="mychose" >تطبيق</button>
            </div> 
            ';
    }else{

        $data = '
        
        
        <div>
        
        ';

        if($_POST['rank'] == 2)
        {
            $data .='
            <select id="maleOrFemaleChose" style="font-size:16px">
            ';
            if($_POST['maleOrFemale'] == '')
            {
                $data .='
                <option  selected disabled value="all">الجنس</option>
                <option  value="all">الكل</option>
                <option value="1">ذكور</option>
                <option value="2">آناث</option>
                ';
            }
             if($_POST['maleOrFemale'] == 'all')
            {
                $data .='
                <option   disabled value="all">الجنس</option>
                <option selected  value="all">الكل</option>
                <option value="1">ذكور</option>
                <option value="2">آناث</option>
                '; 
            }
            if($_POST['maleOrFemale'] == '1'){
                $data .='
                   <option  disabled value="all">الجنس</option>
                    <option value="all">الكل</option>
                    <option selected value="1">ذكور</option>
                    <option value="2">آناث</option>
                    ';
              
            }
            if($_POST['maleOrFemale'] == '2'){
                $data .='
                 <option  disabled value="all">الجنس</option>
                 <option value="all">الكل</option>
                 <option  value="1">ذكور</option>
                 <option selected value="2">آناث</option>
                ';
            }
            $data .='
         
            </select>
            ';
        }
        
        $data .='
        <select id="changeChose" style="font-size:16px">
        ';
        if($_POST['change'] == 'all')
        {
            $data .= '
            <option disabled value="all">اختيار اللجنة</option>
            <option value="all" selected>الجميع</option>
            ';
        }else{
            $data .= '
            <option disabled selected value="all">اختيار اللجنة</option>
            <option value="all">الجميع</option>
            ';
        }
        $getAllajna = $db->db->prepare(
            "SELECT DISTINCT(allajna) FROM voters WHERE allajna != ?
            AND idEvent = ?
            "
        );
        $getAllajna->execute([' ',$_POST['idEvent']]);
        foreach($getAllajna->fetchAll() as $row)
        {
        if($_POST['change'] == $row['allajna'])
        {
            $data .= '
            <option  selected value="'.$row['allajna'].'">'.$row['allajna'].'</option>
            ';
        }else{
            $data .= '
            <option   value="'.$row['allajna'].'">'.$row['allajna'].'</option>
            ';
        }
        
        }
        $data .='

        </select>
        </div>
        ';
    }
    $data .="</div>";
     echo $data;
    }
    

    // Details 
    if($_POST['action'] == 'details')
    {
        $details = $db->getSingleInfo('voters','id',$_POST['idUser']);
        $gender = $details['gender'] == '1' ? 'ذكر': 'انثى';
        ?>
        <div class="table-responsive">
        <table class="table table-striped table-success">
            <tr>
            <?php 
             $changeColor = $details['gender'] == 1 ? "#062bb1" : "#c51334";

            ?>
            <td  style="width:18%" class="text-dark fw-bold px-2">الاسم</td>
            <td style="color:<?=$changeColor?>"><?=$details['fullName']?></td>
            </tr>
            <tr>
            <td  class="text-dark fw-bold px-2">الهاتف</td>
            <td  class="text-primary ">
                <?=$details['phone']?>
                <?php 
                if($_POST['rank'] == 2)
                {
                    ?>
                    <input type="text" style="background: transparent;border: none;padding-right: 20px;" class=" newValuePhone" placeholder="تغير رقم الهاتف"  />
                  <?php 
                }
                ?>

            </td>
            </tr>
            <tr>
            <td  class="text-dark fw-bold px-2">الجنس</td>
            <td  class="text-primary "><?=$gender?></td>
            </tr>
            <tr>
            <td  class="text-dark fw-bold px-2"> العائلة</td>
            <td  class="text-primary "><?=$details['familyName']?></td>
            </tr>
            <td  class="text-dark fw-bold px-2"> المنطقة</td>
            <td  class="text-primary "><?=$details['areaName']?></td>
            </tr>
            <tr>
            <td  class="text-dark fw-bold px-2">رقم القيد </td>
            <td  class="text-primary "><?=$details['raqmAlqayd']?></td>
            </tr>
            <tr>
            <tr>
            <td  class="text-dark fw-bold px-2">الصندوق </td>
            <td  class="text-primary "><?=$details['raqmAlsunduq']?></td>
            </tr>
            
            <td  class="text-dark fw-bold px-2">اللجنة </td>
            <td  class="text-primary ">
                <?=$details['allajna']?>
                <?php 
                if($_POST['rank'] == 2)
                {
                    ?>
                                    <input type="text" style="background: transparent;border: none;padding-right: 20px;"   class="newValeLjna form-control-sm" placeholder="تغير رقم اللجنة" />
                    <?php
                    } 
                ?>
         </td>



            </tr>
            <tr>
            <td  class="text-dark fw-bold px-2">المدرسة </td>
            <?php 
            $stmt = $db->db->prepare('select * from databaseallijan where idAllajna = ? and id_event = ?');
            $stmt->execute([$details['allajna'],$_POST['idEvent']]);
            $fetch = $stmt->fetch();
            if(!empty($fetch))
            {
                ?>
                <td  class="text-primary "><?=$fetch['name']?></td>
                </tr>
                <?php
            }else{
              ?>
             <td  class="text-danger ">لا يوجد بيانات</td>

              <?php
            }
        
       
             if($_POST['idSupervisor'] == $_POST['idUserNow'])
             {

            $gets = array();
            $query = "
            SELECT * FROM listcontent WHERE idUser = ?
            AND idSupervisor  = ?
            ";
                
            $stmt = $db->db->prepare($query);
            $stmt->execute([$_POST['idUser'],$_POST['idSupervisor']]);
            foreach($stmt->fetchAll() as $data) {
                $gets[] =  $data['idList'];
            }
            $data = [];
           foreach($gets as $id) { 
               $data[] = $db->getSingleInfo('list','id', $id);
           }
      

            ?>
              <tr>
            <td  class="text-dark fw-bold px-2">القائمة </td>

            <td  class="text-primary ">
                <?php
                if(!empty($data))
                {
                    foreach($data as $row)
                        {
                            echo $row['name'] . " , ";
                        }
                }else{
                    echo 'لا يوجد';
                }
                ?>
            </td>

            </tr>
            <?php 
            }else{
                ?>
                <tr>
                <td  class="text-dark fw-bold px-2">القائمة </td>
                <?php 
                $getSingleItem = $db->db->prepare(
                    "SELECT * FROM listcontent WHERE idUser = ?
                    AND 
                    idParent = ?
                    "
                );
                $getSingleItem->execute([$_POST['idUser'],$_POST['idParent']]);
                $ids = []; 
                foreach($getSingleItem->fetchAll() as $row)
                 {
                    $ids[] =  $row['idList'];
                 }
                 $data = array();
                foreach($ids as $id)
                {
                  $data[] = $db->getSingleInfo('list','id', $id);
                }
                ?>
                <td>
                    <?php 
                    foreach($data as $row)
                    {
                        echo $row['name'] . ",";
                    }
                    ?>
                </td>
                </tr>
                <?php 
            }
            ?>
           
           
            <tr>
                <?php 
                if($_POST['idSupervisor'] != $_POST['idUserNow'])
                {
                    // if($_POST['rank'] == 4)
                    // {
                    //     echo 'x';
                    //     $query = "SELECT * FROM vote WHERE idUser = ? AND idSupervisor = ? ";
                    //     $stmt = $db->db->prepare($query);
                    //     $stmt->execute([$details['id'],$_POST['idSupervisor']]);
                    // }else{
                        $query = "SELECT * FROM vote WHERE idUser = ? AND idParent = ? ";
                        $stmt = $db->db->prepare($query);
                        $stmt->execute([$details['id'],$_POST['idSupervisor']]);
                    // }
          
                    if($stmt->rowCount()){
                        $count = '<span class="badge bg-success">'.$stmt->rowCount().'</span>';
                    }else{
                        $count = '<span class="badge bg-dark">0</span>';
                    }
                }else{
              $query = "SELECT * FROM vote WHERE idUser = ? AND idSupervisor = ? ";
               $stmt = $db->db->prepare($query);
               $stmt->execute([$details['id'],$_POST['idSupervisor']]);
               $countVote = $stmt->fetchAll();

                $count = count($countVote) > 0 ? '<span class="badge bg-success">'.count($countVote).'</span>' : '<span class="badge bg-dark">0</span>';
                }
                ?>
                <td style="font-size:22px;"  class="text-dark fw-bold  px-2 fixedStyleTd" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample"> الضامنين </td>
                <td  style="font-size:22px; " class="fixedStyleTd"><?=$count?> 
                <?php 
                if($_POST['idSupervisor'] == $_POST['idUserNow'])
                {

                ?>
                        <button style="margin-right:57px" class="btn btn-dark btn-sm changeDataDetails" data-id="<?=$details['id']?>">تعديل</button>

                <?php 
                }



           
                ?>
</td>

              
             </tr>
           
              
  
 
</div>
          


        </table>
<?php 
if($_POST['rank'] == 2)
{
if($_POST['idSupervisor'] == $_POST['idUserNow'])
{
                ?>

        <div class="collapse" id="collapseExample">
            <div class="card card-body">
                <?php 
            $query = "SELECT * FROM vote WHERE idUser = ? AND idSupervisor = ?";
            $stmt = $db->db->prepare($query);
            $stmt->execute([$details['id'],$_POST['idSupervisor']]);
            $data = $stmt->fetchAll();
            $usernames = [];

            if($stmt->rowCount() > 0)
            {
                foreach($data  as $row)
                {
                    if($db->getSingleInfo('supervisor','id',$row['idParent']))
                    {
                        $usernames[] = $db->getSingleInfo('supervisor','id',$row['idParent']);

                    }else{
                        $usernames[] = $db->getSingleInfo('daman','id',$row['idParent']);

                    }

                }
               foreach($usernames as $username)
               {
                echo '<span>'.$username['username'].'</span>';
               }
            }else{
                echo '<span class="text-danger">لا يوجد بيانات</span>';

            }
                ?>
            </div>
        </div>
        <?php 
}
}
?>
        
    </div>
        <div class="input-group  input-group-md ">
       <span class="input-group-text" id="inputGroup-sizing-default">عرض اقارب </span>
        <select class="select_degree form-control">
        <option value="1">من الدرجة الاولي</option>
        <option  value="2">من الدرجة الثانية</option>
        <option  value="3">بشكل الثالثة</option>
        <option  value="4">بشكل موسع</option>

        </select>
       <button data-name="<?=$details['fullName']?>"  data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="click search_degree">تطبيق</button> 
       </div>

       </div>
        <?php 
       
    }


    if($_POST['action'] == 'search_degree')
    {
        $idEvent = $_POST['idEvent'];

        $query2 = "
        SELECT * FROM vote WHERE idParent = ?
        ";
        $stmt2 = $db->db->prepare($query2);
        $stmt2->execute([$_POST['idParent']]);

        $query = "
        SELECT * FROM voters 
        WHERE idEvent = ".$_POST['idEvent']."
        ";
        foreach($stmt2->fetchAll() as $row)
        {
            $query .="
            AND id != ".$row['idUser']."
            ";
        }



        $select_value = $_POST['select_value'];
         $select_value;
        $username =  trim($_POST['username']);
        $arrValue = explode(" ",$username);
        $fixedLength = explode(" ",$username);

        $query = "SELECT * FROM voters WHERE  fullName LIKE :search AND idEvent = $idEvent";
        $params = [];
        
        if($select_value == "1")
        {   
            if(in_array("عبد",$arrValue)){
                $pattern = array_shift($arrValue);
                $pattern = array_shift($arrValue);

            }
            else{
                $pattern = array_shift($arrValue);

            }
            $pattern =  implode(" ",$arrValue);
            $params = array(":search"=>"%".$pattern);
        }else if($select_value == "2")
        {
           if(in_array("عبد",$arrValue)){
            $pattern = array_shift($arrValue);
            $pattern = array_shift($arrValue);
            $pattern = array_shift($arrValue);
           }else{
            $pattern = array_shift($arrValue);
            $pattern = array_shift($arrValue);
           }
           $pattern =  implode(" ",$arrValue);
            $params = array(":search"=>"%".$pattern);
        }else if($select_value == "3")
        {
            if(in_array("عبد",$arrValue)){
                $pattern = array_shift($arrValue);
                $pattern = array_shift($arrValue);
                $pattern = array_shift($arrValue);
                $pattern = array_shift($arrValue);
               }else{
                $pattern = array_shift($arrValue);
                $pattern = array_shift($arrValue);
                $pattern = array_shift($arrValue);
               }
                 $pattern =  implode(" ",$arrValue);
                $params = array(":search"=>"%".$pattern);
            
        }
        else
        {
            $pttern = end($arrValue);
            $params = array(":search"=>"%".$pttern);

        }

        $stmt = $db->db->prepare($query);
        $stmt->execute($params);
        $fetchAll = $stmt->fetchAll();
        $count = 0;
        foreach($fetchAll as $row)
        {
          
            $nameEx = explode(" ",trim($row['fullName']));
            
            if(count($fixedLength) == count($nameEx) && $username != trim($row['fullName']))
            {
                $count++;
            }
        }

        $data = ''; 
        if($stmt->rowCount() > 0){
          
           $data .= '
           <div class="table-responsive">
           <div class="d-flex" style=" justify-content:space-between;align-items:center">
           <h6 class="text-end">عائلة '.$username.' <span class="badge bg-success">'.$count.'</span></h6>
           <div class="appBtnPrint">
           <button class="btn-print-csv">CSV</button>
           <button class="btn-print-pdf">PDF</button>
           <button class="print" data-pagename="عائلة '.$username.'">PRINT</button>
           </div>
           </div>

           <table id="table_search_degree" class="styleTable table table-warning table-striped">
             <thead>
             <tr>
                   <th><input type="checkbox" data-target=".insideCheckbox"" class="checkall" /></th>
                   <th class="name">الاسم</th>
                   <th>مضمون</th>
               </tr>
             </thead>
             <tbody>
           ';
           $y = 1;
        foreach($fetchAll as $row)
        {
           



            $nameEx = explode(" ",trim($row['fullName']));
            if(count($fixedLength) == count($nameEx) && $username != trim($row['fullName']))
            {
              
            
            
            $query3 = "SELECT * FROM vote WHERE idUser = ? AND idSupervisor = ? ";
            $stmt = $db->db->prepare($query3);
            $stmt->execute([$row['id'],$_POST['idSupervisor']]);
            $countVote = $stmt->fetchAll();
            $count = '';
            if(count($countVote) > 0)
            {
                    $count = '<span  class="badge bg-success rounded-2"> '.count($countVote).'</span>';
            }
    
            
   
           $query = "SELECT * FROM vote WHERE idUser = ? AND idParent = ?";
           $stmt = $db->db->prepare($query);
           $stmt->execute([$row['id'],$_POST['idParent']]);
          $dataVote = $stmt->fetch();
           
          $changeColor = $row['gender'] == 1 ? "#062bb1" : "#c51334";

 
          $data .= 
          
          '
          <tr>
          <td>
          <div class="d-flex" style="gap:2px">
          <span class="styleNumberCount">'.($y++).'</span>

          <input type="checkbox"  class="checkboxMulti getVote insideCheckbox"  
          data-id = "'.$row['id'].'"

          data-name="'.$_POST['username'].'"
          data-userid = "'.$row['id'].'"
          data-idparent = "'.$_POST['idParent'].'"
          data-username = "'.$row['fullName'].'"
          data-id="'.$row['id'].'"
          />
          <span class="hidePrint">'.$count.'</span>
          </div>
          </td>
          <td style="color:'.$changeColor.'">'.$row['fullName'].'</td>
           <td>
        
          ';
            
     if($dataVote && $dataVote['idParent'] == $_POST['idParent']){
   
           if($dataVote['level'] == 1)
           {

            $data.=' <button
            data-search="'.$_POST['username'].'"
            data-name="'.$row['fullName'].'"                                                
            data-iduser = "'.$row['id'].'"
            data-idparent = "'. $_POST['idParent'].'"
            data-username = "'.$row['fullName'].'"
             href="#"
             data-page="details"

            class=" btn-success myBtnTable resetVote  " 
            > 
             <i class="ri-close-line"></i>  </button>
            ';

           
               /*               <button  data-search="'.$_POST['username'].'"  data-name="'.$row['fullName'].'"   data-page="details" 
   class="btn btn-danger resetVote  btn-sm "    data-iduser="'.$row['id'].'"> <i class="ri-close-line"></i>  </button>
*/
           }else{
               $data .='
               <button 
               data-names="'.$row['fullName'].'"                                                
               data-iduser = "'.$row['id'].'"
               data-idparent = "'. $_POST['idParent'].'"
               data-username = "'.$row['fullName'].'"   
               
               class="btn-primary resVoteBack  myBtnTable">
               <i class="ri-check-line"></i>
               </button>
               '; 
               /*               <button  data-search="'.$_POST['username'].'" data-name="'.$row['fullName'].'"  
               class="btn btn-danger resetVote   btn-sm"   data-page="details" data-iduser='.$row['id'].'> <i class="ri-close-line"></i>  </button>
*/
               
           }
           $data .= '
           </td>
           </tr>
           ';
        }
  
   
      
             }
        }
        $data .='
        </tbody>
        </table>
        </div>
         </div>
         </div>
         ';
         $page = isset($page) ? $page : '';

   
               $data .='
                    
        <div class="insertMulti">
        <select class="valueSelectMulti" id="valueList">
            <option value="delete">غير مضمون</option>
            <option disabled>----</option>
            ';
            $lists = $db->getRows('list',array(
                'where' => array('idParent'=>$_POST['idUser']),
                'return_type' => 'count'
            ));
            if($lists > 0){
                $Lists = $db->getAll('list','idParent',$_POST['idUser'],true);
                if($Lists !== false){
                    foreach($Lists as $row){
             
                        $data .='
                        <option class="lists" value="insertlist" data-value='.$row['id'].'>'.$row['name'].'</option>
                         ';
                       
                      
                        }
                }
                
            }
        $data .='
        </select>
        <button  class="btnMultiVote" data-name="'.$username.'" data-page="details">تطبيق</button>
        </div> 
               ';
       }else{
           $data = 'zero';
       }
    
        echo $data;
       }
       
   if($_POST['action'] == 'deletelist')
   {
    $idList =  $_POST['idList'];
    $listcontent = $db->getAll('listcontent','idList',$idList);
    if($listcontent !== false){
    foreach($listcontent as $row)
    {
        $db->update('voters',array(
            'have_list' => '0'
        ),array(
            'id' => $row['idUser']
        ));
    }
    }
    $db->deleteSuperVisor('list','id',$idList);

   }

   if($_POST['action'] == 'increment_counts')
   {
        $stmt = $db->db->prepare("SELECT * FROM counts WHERE idSupervisor = ? AND idUser = ?");
        $stmt->execute([$_POST['idSupervisor'],$_POST['idUser']]);
        if($stmt->rowCount() > 0)
        {
            $stmtUpdate = $db->db->prepare("
            UPDATE counts
            SET count = count + 1
            WHERE 
            idSupervisor = ? 
            AND 
            idUser = ?        
        ");
             $stmtUpdate->execute([$_POST['idSupervisor'],$_POST['idUser']]);
    
    
        }else{
            $stmt = $db->db->prepare("INSERT INTO counts(`idSupervisor`, `idUser`,`count`)
            VALUES(?,?,1)
            ");
            $stmt->execute([$_POST['idSupervisor'],$_POST['idUser']]);
        }
   }

   if($_POST['action'] == 'decrement_counts')
   {
        $stmt = $db->db->prepare("SELECT * FROM counts WHERE idSupervisor = ? AND idUser = ?");
        $stmt->execute([$_POST['idSupervisor'],$_POST['idUser']]);
        if($stmt->rowCount() > 0 && $stmt->fetch()['count'] > 0)
        {
                $stmtUpdate = $db->db->prepare("
                UPDATE counts
                SET count = count - 1
                WHERE 
                idSupervisor = ? 
                AND 
                idUser = ?        
                ");
                $stmtUpdate->execute([$_POST['idSupervisor'],$_POST['idUser']]);
        }
   }
   if($_POST['action'] == 'get_single_count')
    {
        $stmt = $db->db->prepare("SELECT * FROM counts WHERE idSupervisor = ? AND idUser = ?");
        $stmt->execute([$_POST['idSupervisor'],$_POST['idUser']]);
        if($stmt->rowCount() > 0)
        {
            echo $stmt->fetch()['count'];
        }else{
            echo '0';
        }
    }
   
   if($_POST['action'] == 'get_all_counts')
   {
    $stmt = $db->db->prepare("SELECT * FROM counts WHERE idSupervisor = ?");
    $stmt->execute([$_POST['idSupervisor']]);
    if($stmt->rowCount() > 0)
    {
        $counts = 0;
        foreach($stmt->fetchAll() as $row){
            $counts += $row['count'];
        }
        echo $counts;
    }else{
        echo '0';
    }
   } 
   
   if($_POST['action'] == 'changeDataDetails')
   {
     $newValeLjna = trim($_POST['newValeLjna']);
    $newValuePhone = trim($_POST['newValuePhone']);
    $done = false;
    if($newValeLjna == '' && $newValuePhone == '')
    {
       echo 'empty';
    } 
    else if($newValeLjna != '' && $newValuePhone != '')
    {
        $update = $db->db->prepare("UPDATE voters SET phone = ? ,allajna = ? WHERE id = ?");
        $update->execute([$newValuePhone,$newValeLjna,$_POST['idUser']]);
        $done = true;
    }
    
    else if ($newValeLjna != '' && $newValuePhone == '')
    {
        $update = $db->db->prepare("UPDATE voters SET allajna = ? WHERE id = ?");
        $update->execute([$newValeLjna,$_POST['idUser']]);
        $done = true;

    }

    else
    {
        $update = $db->db->prepare("UPDATE voters SET phone = ? WHERE id = ?");
        $update->execute([$newValuePhone,$_POST['idUser']]);
        $done = true;

    }

    if($done)
    {
        echo 'done';
    }

   }


   if($_POST['action'] == 'getNumberMadmen')
   {
        if($_POST['type'] == 'attend')
        {
            if($_POST['rank'] == 2 ||  $_POST['rank']  == 4)
            {
                $request = $db->db->prepare(
                    "SELECT DISTINCT username FROM vote WHERE idSupervisor = ? AND level = ?"
                );
                $request->execute([$_POST['idSupervisor'],2]);
                echo $request->rowCount();
            }else{
                $request = $db->db->prepare(
                    "SELECT DISTINCT username FROM vote WHERE idSupervisor = ? AND idParent = ? AND level = ?"
                );
                $request->execute([$_POST['idSupervisor'],$_POST['idParent'],2]);
                echo $request->rowCount();
            }
         
        }
        else 
        {
            if($_POST['rank'] == 2)
            {
                $request = $db
                ->db->prepare(
                    "SELECT DISTINCT username FROM vote WHERE idSupervisor = ?"
                );
                $request->execute([$_POST['idSupervisor']]);
                echo $request->rowCount();
            }
            else{
                $request = $db
                ->db->prepare(
                    "SELECT DISTINCT username FROM vote WHERE idSupervisor = ? AND idParent= ?"
                );
                $request->execute([$_POST['idSupervisor'],$_POST['idParent']]);
                echo $request->rowCount();
            }
          
        }
       

   }

   if($_POST['action'] == 'checkSearchVoters')
   {
        $idUser = $_POST['idUser'];
        $check =  trim($_POST['classname']);
        if($check != "#5dff98")
        {
            $insert = $db->insertSuperVisor('vote',array(
                'idUser' => $_POST['id'],
                'idParent' => $_POST['idUser'],
                'username' => $_POST['username'],
                'level' => 1,
                'idFrontend' => $_POST['idFrontend'],
                'idSupervisor' => $_POST['idSupervisor']
            ));   
        }
        else 
        {
            $sql = "
            DELETE FROM vote WHERE idUser = ? AND idSupervisor = ? 
            ";

            $Delete = $db->db->prepare($sql);
            $Delete->execute([$_POST['id'],$_POST['idUser']]);
        }
   }




   if($_POST['action'] == 'updateTask')
   {
    $title = trim($_POST['title']);
    $date = $_POST['date'];
    $descrption = trim($_POST['descrption']);
    $importance = $_POST['importance'];
    $status =  isset($_POST['status']) ? 1 : 0;

    $actionTask = $db->db->prepare(
        "
        UPDATE tasks SET
        title = ?,
        date = ?,
        descrption = ?,
        status = ?,
        importance = ?
        WHERE 
        id = ?
        "
    );


   $update =  $actionTask->execute([$title,$date,$descrption,$status,$importance,$_POST['id_task']]);
    if($update)
    {
        echo $_POST['id_task'];
    }



   }

  

   // عرض التاسكات
   if($_POST['action'] == 'show_next_task')
   {
    if($_POST['type'] == 'next')
    {


    $stmt = $db->db->prepare("SELECT * FROM tasks where id > ? AND id_user = ?  limit 1");
    $stmt->execute([$_POST['id'],$_POST['id_user']]);
    $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
    if($stmt->rowCount() > 0)
    {
        echo json_encode($fetch);
    }
    else 
    {
        $stmt = $db->db->prepare("select * from tasks where id_user = ? 
        order by id asc limit 1");
        $stmt->execute([$_POST['id_user']]);
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($fetch);
    }
    }
    else
    {
        $stmt = $db->db->prepare("SELECT * FROM tasks where id < ? AND id_user = ? order by id DESC limit 1");
        $stmt->execute([$_POST['id'],$_POST['id_user']]);
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
        if($stmt->rowCount() > 0)
        {
            echo json_encode($fetch);
        }
        else 
        {
            $stmt = $db->db->prepare("select * from tasks where id_user = ? 
            order by id desc limit 1");
            $stmt->execute([$_POST['id_user']]);
            $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($fetch);
    
        }
    }

   }


   if($_POST['action'] == 'add_new_mandoub')
   {
    $name = trim($_POST['name']);
    $id_user = $_POST['id_user'];
    $stmt = $db->db->prepare("insert into mandoub_transactions(`id_user`,`name`) values(?,?)");
    $add = $stmt->execute([$id_user,$name]);
    if($add)
    {
        $stmt2 = $db->db->prepare("select * from mandoub_transactions");
        $stmt2->execute();
        echo json_encode(['data'=>$stmt2->fetchAll(PDO::FETCH_ASSOC),'status'=>1]);

    }
   }

   
   if($_POST['action'] == 'show_next_trans')
   {

    if($_POST['type'] == 'next')
    {

    $stmt = $db->db->prepare("SELECT * FROM transactions where id > ? AND id_user = ?  limit 1");
    $stmt->execute([$_POST['id'],$_POST['id_user']]);
    $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
    if($stmt->rowCount() > 0)
    {
        echo json_encode($fetch);
    }
    else 
    {
        $stmt = $db->db->prepare("select * from transactions where id_user = ? 
        order by id asc limit 1");
        $stmt->execute([$_POST['id_user']]);
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($fetch);
    }
    }
    else
    {
        $stmt = $db->db->prepare("SELECT * FROM transactions where id < ? AND id_user = ? order by id DESC limit 1");
        $stmt->execute([$_POST['id'],$_POST['id_user']]);
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
        if($stmt->rowCount() > 0)
        {
            echo json_encode($fetch);
        }
        else 
        {
            $stmt = $db->db->prepare("select * from transactions where id_user = ? 
            order by id desc limit 1");
            $stmt->execute([$_POST['id_user']]);
            $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($fetch);
    
        }
    }

   }
    

}

?>