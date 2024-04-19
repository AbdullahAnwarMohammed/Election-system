<?php 
require_once("_supervisor/init.php");

$db = new DB();



$column = array("id", "fullName","attend");



if( $_POST['mainMaleOrFemale'] == "all" && $_POST['mandubLgna'] == "all" && $_POST['selectAttendOr'] == "all")
{
    $query = "
    SELECT * FROM vote 
    WHERE idSupervisor = ".$_POST['idSupervisor']."
    ";
    
}else{
    if($_POST['mainMaleOrFemale'] != "all" && $_POST['mandubLgna'] == 'all' && $_POST['selectAttendOr'] == "all")
    {
        if($_POST['mainMaleOrFemale'] == "male")
        {
            $query = "
            SELECT DISTINCT * FROM `vote`  inner join voters on vote.idSupervisor = ".$_POST['idSupervisor']." AND voters.id = vote.idUser AND voters.gender = 1
            ";
            
        }else{
            $query = "
            SELECT DISTINCT * FROM `vote`  inner join voters on vote.idSupervisor = ".$_POST['idSupervisor']." AND voters.id = vote.idUser AND voters.gender = 2

            ";
        }
        
    }else if($_POST['mainMaleOrFemale'] == "all" && $_POST['selectAttendOr'] == "all" &&$_POST['mandubLgna'] != 'all')
    {
        
        $query = "
        SELECT DISTINCT * FROM `vote`  inner join voters on vote.idSupervisor = ".$_POST['idSupervisor']." AND voters.id = vote.idUser 
        AND voters.allajna = ".$_POST['mandubLgna']."
        ";
    }else if($_POST['selectAttendOr'] != "all" && $_POST['mainMaleOrFemale'] == "all" && $_POST['mandubLgna'] == "all"){
        if($_POST['selectAttendOr'] == "attend")
        {
            $query = "
            SELECT * FROM vote 
            WHERE idSupervisor = ".$_POST['idSupervisor']."
            AND level = 2
            ";
            
        }else{
            $query = "
            SELECT * FROM vote 
            WHERE idSupervisor = ".$_POST['idSupervisor']."
            AND level = 1
            ";
        }
        
    }else if($_POST['mainMaleOrFemale'] != "all" && $_POST['selectAttendOr'] != "all" && $_POST['mandubLgna'] == 'all'){
        
        if($_POST['selectAttendOr'] == "attend" &&  $_POST['mainMaleOrFemale']  == 'male')
        {
            $query = "
            SELECT DISTINCT * FROM `vote`  inner join voters on vote.idSupervisor = ".$_POST['idSupervisor']." AND voters.id = vote.idUser AND vote.level = 2 AND voters.gender = 1
            ";
            
        }else if($_POST['selectAttendOr'] == "attend" &&  $_POST['mainMaleOrFemale']  == 'female'){
            $query = "
            SELECT DISTINCT * FROM `vote`  inner join voters on vote.idSupervisor = ".$_POST['idSupervisor']." AND voters.id = vote.idUser AND vote.level = 2 AND voters.gender = 2
            ";
        }else if($_POST['selectAttendOr'] == "no-attend" &&  $_POST['mainMaleOrFemale']  == 'male')
        {
            $query = "
            SELECT DISTINCT * FROM `vote`  inner join voters on vote.idSupervisor = ".$_POST['idSupervisor']." AND voters.id = vote.idUser AND vote.level = 1 AND voters.gender = 1
            ";
        }else{
            $query = "
            SELECT DISTINCT * FROM `vote`  inner join voters on vote.idSupervisor = ".$_POST['idSupervisor']." AND voters.id = vote.idUser AND vote.level = 1 AND voters.gender = 2
            ";
        }

    }
    else{
        
        if($_POST['mainMaleOrFemale'] == "male")
        {
            $query = "
            SELECT DISTINCT * FROM `vote`  inner join voters on vote.idSupervisor = ".$_POST['idSupervisor']." AND voters.id = vote.idUser AND voters.gender = 1
            AND voters.allajna = ".$_POST['mandubLgna']."
            ";
            
        }else{
            $query = "
            SELECT DISTINCT * FROM `vote`  inner join voters on vote.idSupervisor = ".$_POST['idSupervisor']." AND voters.id = vote.idUser AND voters.gender = 2
            AND voters.allajna = ".$_POST['mandubLgna']."
            ";
        }
        
    }

    
  
}





if(isset($_POST["search"]["value"]))
{
 $query .= '
 AND username LIKE "%'.$_POST["search"]["value"].'%" 
 ';
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

$temp = array_unique(array_column($result, 'username'));
$results = array_intersect_key($result, $temp);



$data = array();
$y = 1; 
$attend = 0;
foreach($results as $row)
{
    if($row['attend'] == 1)
    {
        $attend++;
    }

    $x = "SELECT * FROM vote WHERE idUser = ? AND idSupervisor = ?";
    $stmt = $db->db->prepare($x);
    $stmt->execute([$row['idUser'],$_POST['idSupervisor']]);
   $dataVote = $stmt->fetch();
  

    $first = '
    
    <span class="styleNumberCount">'.($y++).'</span>
    <input name="selector[]"  type="checkbox" class="getVote mainCheckbox" data-id="'.$row['idUser'].'" multiple >
    <input type="hidden" class="names" name="names[]" value="'.$row['username'].'" >
    ';

    $getColor  = $db->db->prepare("SELECT * FROM voters WHERE id = ?");
    $getColor->execute([$row['idUser']]);
    $fetch = $getColor->fetch();
    $changeColor = $fetch['gender'] == 1 ? "#062bb1" : "#c51334";




    $name = '
    <a  href="#"  data-id="'.$row['idUser'].'" class="details" data-bs-toggle="modal" data-bs-target="#modalDetails" style="color:'.$changeColor.'">'.$row['username'].'</a>

    ';

   
    $searchfamily = '
    <a data-username="'.$row['username'].'" class="searchForAqarb text-dark" href="#" data-bs-toggle="modal" data-bs-target="#exampleModa2"><i class="ri-parent-fill"></i></a>
    ';
        $button = '';
       
        if($dataVote && $dataVote['idSupervisor'] == $_POST['idSupervisor']){
            
            
            if($dataVote['level'] == 1)
            {
            $button='
            <button  
            data-name="'.$row['username'].'"                                                
            data-iduser = "'.$row['idUser'].'"
            data-idparent = "'. $_POST['idParent'].'"
            data-username = "'.$row['username'].'"
            data-level = "2"
             href="#"
            class="btn-success myBtnTable resBtn votemandub">
            <i class="ri-close-line"></i></button>

       
            ';
            }else{
                $button ='
             

                <button 
                data-names="'.$row['username'].'"                                                
                data-iduser = "'.$row['idUser'].'"
                data-idparent = "'. $_POST['idParent'].'"
                data-username = "'.$row['username'].'"   
                
                class="btn-primary resVoteBackMandob  myBtnTable">
                <i class="ri-check-line"></i>
                </button>
              
                ';
                /* </button>
                <button  data-name="'.$row['fullName'].'"  
                class="btn-danger  btn-sm resBtn  resetVoteMain "     data-iduser="'.$row['id'].'"> <i class="ri-close-line"></i>  </button>*/
            }

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
            $count = '<span  class="badge bg-success rounded-2"> '.count($countVote).'</span>';
    }
    

    $sub_array = array();
    $sub_array[] = '<div class="responsive-name"> '.$first.'   '.$count.' </div>';
   
  
    $sub_array[] =   $name;
    // $sub_array[] = $searchfamily;
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
 'recordsFiltered' => count($results),
 'data'   => $data,
 'attend' => $attend 
);

echo json_encode($output);



?>