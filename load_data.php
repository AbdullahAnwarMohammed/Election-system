<?php 
require_once("_supervisor/init.php");





$column = array("id", "fullName","fullName","attend");




if( $_POST['mainMaleOrFemale'] == "all" && $_POST['areaName'] == 'all' && $_POST['committees'] == 'all' && $_POST['headquarters'] == 'all')
{
    $query = "
    SELECT * FROM voters 
    WHERE idEvent = ".$_POST['idEVENT']."
    ";
}
if( $_POST['mainMaleOrFemale'] != "all" && $_POST['areaName'] == 'all' && $_POST['committees'] == 'all' && $_POST['headquarters'] == 'all')
{
     $query = "
    SELECT * FROM voters 
    WHERE gender = ".$_POST['mainMaleOrFemale']."
    ";
}
if( $_POST['mainMaleOrFemale'] != "all" && $_POST['areaName'] != 'all' && $_POST['committees'] == 'all' && $_POST['headquarters'] == 'all')
{
     $query = "
    SELECT * FROM voters 
    WHERE gender = ".$_POST['mainMaleOrFemale']."
    AND 
     areaName = '".$_POST['areaName']."'

    ";
}
if( $_POST['mainMaleOrFemale'] != "all" && $_POST['areaName'] != 'all' && $_POST['committees'] != 'all' && $_POST['headquarters'] == 'all')
{
     $query = "
    SELECT * FROM voters 
    WHERE gender = ".$_POST['mainMaleOrFemale']."
    AND 
    areaName = '".$_POST['areaName']."'
    AND 
    allajna = ".$_POST['committees']."
    ";
}
if( $_POST['mainMaleOrFemale'] != "all" && $_POST['areaName'] != 'all' && $_POST['committees'] == 'all' && $_POST['headquarters'] != 'all')
{
     $query = "
    SELECT * FROM voters 
    WHERE gender = ".$_POST['mainMaleOrFemale']."
    AND 
    areaName = '".$_POST['areaName']."'
    AND 
    allajna IN (".$_POST['headquarters'].")
    ";
}
if( $_POST['mainMaleOrFemale'] != "all" && $_POST['areaName'] != 'all' && $_POST['committees'] != 'all' && $_POST['headquarters'] != 'all')
{
     $query = "
    SELECT * FROM voters 
    WHERE gender = ".$_POST['mainMaleOrFemale']."
    AND 
    areaName = '".$_POST['areaName']."'
    AND 
    allajna = ".$_POST['committees']."


    ";
}

if( $_POST['mainMaleOrFemale'] == "all" && $_POST['areaName'] != 'all' && $_POST['committees'] == 'all' && $_POST['headquarters'] == 'all')
{
     $query = "
    SELECT * FROM voters 
    WHERE areaName = '".$_POST['areaName']."'
    ";
}

if( $_POST['mainMaleOrFemale'] == "all" && $_POST['areaName'] == 'all' && $_POST['committees'] != 'all' && $_POST['headquarters'] == 'all')
{
     $query = "
    SELECT * FROM voters 
    WHERE allajna = '".$_POST['committees']."'
    ";
}
if( $_POST['mainMaleOrFemale'] == "all" && $_POST['areaName'] == 'all' && $_POST['committees'] == 'all' && $_POST['headquarters'] != 'all')
{
     $query = "
    SELECT * FROM voters 
    WHERE  allajna IN (".$_POST['headquarters'].")
    ";
}
if( $_POST['mainMaleOrFemale'] == "all" && $_POST['areaName'] != 'all' && $_POST['committees'] != 'all' && $_POST['headquarters'] == 'all')
{
     $query = "
    SELECT * FROM voters 
    WHERE areaName = '".$_POST['areaName']."'
    AND allajna = ".$_POST['committees']."
    ";
}

if( $_POST['mainMaleOrFemale'] == "all" && $_POST['areaName'] != 'all' && $_POST['committees'] != 'all' && $_POST['headquarters'] != 'all')
{
     $query = "
    SELECT * FROM voters 
    WHERE areaName = '".$_POST['areaName']."'
    AND allajna = ".$_POST['committees']."
    AND allajna IN (".$_POST['headquarters'].")
    ";
}

if( $_POST['mainMaleOrFemale'] == "all" && $_POST['areaName'] == 'all' && $_POST['committees'] != 'all' && $_POST['headquarters'] != 'all')
{
     $query = "
    SELECT * FROM voters 
    WHERE allajna = ".$_POST['committees']."
    ";
}
if( $_POST['mainMaleOrFemale'] != "all" && $_POST['areaName'] == 'all' && $_POST['committees'] != 'all' && $_POST['headquarters'] == 'all')
{
     $query = "
    SELECT * FROM voters 
    WHERE gender = ".$_POST['mainMaleOrFemale']."
    AND allajna = ".$_POST['committees']."
    ";
}

if( $_POST['mainMaleOrFemale'] != "all" && $_POST['areaName'] == 'all' && $_POST['committees'] == 'all' && $_POST['headquarters'] != 'all')
{
     $query = "
    SELECT * FROM voters 
    WHERE gender = ".$_POST['mainMaleOrFemale']."
    AND allajna IN (".$_POST['headquarters'].")
    ";
}
if( $_POST['mainMaleOrFemale'] == "all" && $_POST['areaName'] != 'all' && $_POST['committees'] == 'all' && $_POST['headquarters'] != 'all')
{
       $query = "
    SELECT * FROM voters 
    WHERE areaName = '".$_POST['areaName']."'
    AND allajna IN (".$_POST['headquarters'].")
    ";
}

// else{
//   $query = "
//     SELECT * FROM voters 
//     WHERE areaName= ".$_POST['mainMaleOrFemale']."
//     AND allajna IN (".$_POST['headquarters'].")
//     ";
// }

// if(empty($_POST['mainMaleOrFemale']) || $_POST['mainMaleOrFemale'] == "all" &&
//  $_POST['committees'] == 'all' && $_POST['headquarters'] == 'all' && $_POST['areaName'] != 'all'){
//     $query = "
//     SELECT * FROM voters 
//     WHERE idEvent = ".$_POST['idEVENT']."
//     AND 
//     areaName = ".$_POST['areaName']."
//     ";  
// }

// if(empty($_POST['mainMaleOrFemale']) || $_POST['mainMaleOrFemale'] == "all" &&
//  $_POST['committees'] != 'all' && $_POST['headquarters'] == 'all' && $_POST['areaName'] == 'all'){
//     $query = "
//     SELECT * FROM voters 
//     WHERE idEvent = ".$_POST['idEVENT']."
//     AND 
//     allajna = ".$_POST['committees']."
//     ";  
// }

// if(empty($_POST['mainMaleOrFemale']) || $_POST['mainMaleOrFemale'] == "all" &&
//  $_POST['committees'] == 'all' && $_POST['headquarters'] != 'all' && $_POST['areaName'] == 'all' ){
//     $query = "
//     SELECT * FROM voters 
//     WHERE idEvent = ".$_POST['idEVENT']."
//     AND 
//     allajna IN (".$_POST['headquarters'].")
//     ";  
// }






if(isset($_POST["search"]["value"]))
{
   if($_POST['typeSearch'] == 1)
        {
            $query .= '
            AND fullName LIKE "%'.$_POST["search"]["value"].'%" 
            ';
        }else{
            $query .= '
            AND fullName LIKE "'.$_POST["search"]["value"].'%" 
            ';
        }
}

if(isset($_POST["order"]))
{
 $query .= 'ORDER BY '.$column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
}
else
{
 $query .= 'ORDER BY id DESC ';
}
$query1 = '';

if($_POST["length"] != -1)
{
 $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}


$statement = $db->db->prepare($query);

$statement->execute();


$number_filter_row = $statement->rowCount();

$statement = $db->db->prepare($query . $query1);

$statement->execute();

$result = $statement->fetchAll();


$data = array();
$y = 1; 
$attend = 0;
foreach($result as $row)
{
    
    // $attend++;


    if($_POST['rank'] == 2)
    {
        $x = "SELECT * FROM vote WHERE idUser = ? AND idSupervisor = ? ";
        $stmt = $db->db->prepare($x);
        $stmt->execute([$row['id'],$_POST['idSupervisor']]);
        $dataVote = $stmt->fetch();
        if($dataVote)
        {
            if( $row['attend'] == 1 && $row['id'] == $dataVote['idUser'])
            {
                $attend++;
            }
        }
       
      
    
    }else{
        $x = "SELECT * FROM vote WHERE idUser = ? AND idParent = ? AND idSupervisor = ? ";
        $stmt = $db->db->prepare($x);
        $stmt->execute([$row['id'],$_POST['idParent'],$_POST['idSupervisor']]);
       $dataVote = $stmt->fetch();
     
       if($dataVote)
        {
            if( $row['attend'] == 1 && $row['id'] == $dataVote['idUser'])
            {
                $attend++;
            }
        }
       
    }

   


    $first = '
    <span class="styleNumberCount updateVoteLevelOne" data-name="'.$row['fullName'].'" data-id="'.$row['id'].'">'.($y++).'</span>
    <input name="selector[]"  type="checkbox" class="getVote mainCheckbox" data-name="'.$row['fullName'].'" data-id="'.$row['id'].'" multiple >
                            <input type="hidden" class="names" name="names[]" value="'.$row['fullName'].'" >
    ';
    $changeColor = $row['gender'] == 1 ? "#062bb1" : "#c51334";

    $subName =trim($row['fullName']);
    
//    $implode = count($subName);
$name =trim($row['fullName']);
$ex = explode(" ",$name);
if(strlen($name) >= 1)
{
     if(count($ex) == 6){
        array_pop($ex);
        $name = implode(" ",$ex)."...";

     }
    if(count($ex) == 7)
    {
        array_pop($ex);
        array_pop($ex);
        $name = implode(" ",$ex)."...";
    }
    
     if(count($ex) == 9)
    {
        array_pop($ex);
        array_pop($ex);
        array_pop($ex);
        $name = implode(" ",$ex)."...";
    }
    
      if(count($ex) == 10)
    {
        array_pop($ex);
        array_pop($ex);
        array_pop($ex);
        array_pop($ex);
        $name = implode(" ",$ex)."...";
    }
 
 
   
}
    $name = '
    <a  href="#"   data-id="'.$row['id'].'" class="details" data-bs-toggle="modal" data-bs-target="#modalDetails" style="color:'.$changeColor.'">'.$name.'</a>
    ';
   
    $searchfamily = '
    <a data-username="'.$row['fullName'].'" class="searchForAqarb text-dark" href="#" data-bs-toggle="modal" data-bs-target="#exampleModa2">'.$row['familyName'].'</a>
    ';
        $button = '';
       
        if($dataVote && $dataVote['idParent'] == $_POST['idParent']){
            if($_POST['rank'] == '2' || $_POST['rank'] == '4')
            {

                if($dataVote['level'] == 1)
                {
                $button='
                <button  
                data-name="'.$row['fullName'].'"                                                
                data-iduser = "'.$row['id'].'"
                data-idparent = "'. $_POST['idParent'].'"
                data-username = "'.$row['fullName'].'"
                data-level = "2"
                 href="#"
                class="btn-success myBtnTable resBtn resetVoteMain">
                <i class="ri-close-line"></i></button>
                ';
                }else{
                    $button ='
                    <button 
                    data-names="'.$row['fullName'].'"                                                
                    data-iduser = "'.$row['id'].'"
                    data-idparent = "'. $_POST['idParent'].'"
                    data-username = "'.$row['fullName'].'"   
                    
                    class="btn-primary resVoteBack  myBtnTable">
                    <i class="ri-check-line"></i>
                    </button>
                  
                    ';
                }

            }
            else 
            {

                if($dataVote['level'] == 1)
                {
                $button='
                <button  
                data-name="'.$row['fullName'].'"                                                
                data-iduser = "'.$row['id'].'"
                data-idparent = "'. $_POST['idParent'].'"
                data-username = "'.$row['fullName'].'"
                data-level = "2"
                 href="#"
                class="btn-success myBtnTable resBtn resetVoteMain">
                <i class="ri-close-line"></i></button>
                ';
                }else{
                    $button ='
                    <button 
                    data-names="'.$row['fullName'].'"                                                
                    data-iduser = "'.$row['id'].'"
                    data-idparent = "'. $_POST['idParent'].'"
                    data-username = "'.$row['fullName'].'"   
                    
                    class="btn-primary   myBtnTable">
                    <i class="ri-check-line"></i>
                    </button>
                  
                    ';
                }

            }

           

    }
    if($dataVote && $dataVote['idParent'] != $_POST['idUserNow'] &&  $dataVote['idSupervisor'] == $_POST['idSupervisor'] && $dataVote['idParent'] != $_POST['idSupervisor'] && $dataVote['level'] == 2 ){
        $button .='
        <button 
        data-names="'.$row['fullName'].'"                                                
        data-iduser = "'.$row['id'].'"
        data-idparent = "'. $_POST['idParent'].'"
        data-username = "'.$row['fullName'].'"   
        
        class="btn-primary resVoteBack  myBtnTable">
        <i class="ri-check-line"></i>
        </button>
      
        ';
    }


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

    // <span class="badge bg-dark rounded-2">0</span>
    $count = '';
    if(count($countVote) > 0)
    {
            $count = '<span   class="badge bg-success rounded-2"> '.count($countVote).'</span>';
    }
    

    $sub_array = array();

    $sub_array[] = '<div  class="responsive-name">'. $first .  $count.'</div>';
   
  
    $sub_array[] =   $name;
    $sub_array[] = $searchfamily;
    $sub_array[] = $button;



    $data[] = $sub_array;

}

function count_all_data()
{
    global $db;
 $query = "SELECT * FROM voters";
 $statement = $db->db->prepare($query);
 $statement->execute();
 return $statement->rowCount();
}

$output = array(
 'draw'   => intval($_POST['draw']),
 'recordsTotal' => count_all_data(),
 'recordsFiltered' => $number_filter_row,
 'data'   => $data,
 'count' => count($result),
 'attend' => $attend
);

echo json_encode($output);



?>