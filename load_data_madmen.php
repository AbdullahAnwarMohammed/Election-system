<?php 
require_once("_supervisor/init.php");

$db = new DB();



$column = array("","idUser","username","attend");



if(empty($_POST['gender']) && empty($_POST['committee']) 
|| $_POST['gender'] == 'all' && empty($_POST['committee'])
|| $_POST['gender'] == 'all' && $_POST['committee'] == 'all'
|| empty($_POST['gender'] ) && $_POST['committee'] == 'all' )
{
  
   
    if($_POST['idParent'] == $_POST['idSupervisor'])
    {
        $query = "
        SELECT * FROM vote INNER JOIN voters
        WHERE idSupervisor = ".$_POST['idSupervisor']."
        AND vote.idUser = voters.id
        ";
        
    }else{
        $query = "
        SELECT * FROM vote  INNER JOIN voters
        WHERE vote.idParent = ".$_POST['idParent']."
        AND vote.idUser = voters.id
        ";
    }

}else{
   
    if(!empty($_POST['committee']) && empty($_POST['gender']) || $_POST['gender'] == 'all')
    {
     
        if($_POST['idParent'] == $_POST['idSupervisor'])
        {
            $query = "
            SELECT * FROM vote INNER JOIN voters  
            WHERE
            voters.id = vote.idUser AND vote.idSupervisor = ".$_POST['idSupervisor']."
            AND voters.allajna = ".$_POST['committee']."
            AND vote.idUser = voters.id

            ";
        }
        else{
            $query = "
            SELECT vote.* FROM vote INNER JOIN voters 
            WHERE
 
            voters.id = vote.idUser AND vote.idParent = ".$_POST['idParent']."
            AND voters.allajna = ".$_POST['committee']."
            AND vote.idUser = voters.id

            ";
        }

       

    }
    else if(empty($_POST['committee'])  || $_POST['committee'] == 'all' && !empty($_POST['gender']) )
    {
      
        
        if($_POST['idParent'] == $_POST['idSupervisor'])
        {
            $query = "
            SELECT * FROM vote INNER JOIN voters  
            WHERE
            voters.id = vote.idUser AND vote.idSupervisor = ".$_POST['idSupervisor']."
            AND voters.gender = ".$_POST['gender']."
            ";
        }else{
            $query = "
            SELECT * FROM vote INNER JOIN voters WHERE 
            voters.id = vote.idUser AND vote.idParent = ".$_POST['idParent']."
            AND voters.gender = ".$_POST['gender']."
            ";
        }
      
     
    }
    else if($_POST['committee'] == 'all' && $_POST['gender']  == 'all' )
    {
        if($_POST['idParent'] == $_POST['idSupervisor'])
        {
            $query = "
            SELECT * FROM vote 
            WHERE idSupervisor = ".$_POST['idSupervisor']."
            ";
        }
        else 
        {
            $query = "
            SELECT * FROM vote 
            WHERE idParent = ".$_POST['idParent']."
            ";
        }
        
       
    }
    else
    {
        if($_POST['idParent'] == $_POST['idSupervisor'])
        {
            $query = "
            SELECT * FROM vote INNER JOIN voters WHERE 
            voters.id = vote.idUser AND vote.idSupervisor = ".$_POST['idSupervisor']."
            AND voters.gender = ".$_POST['gender']."
            AND voters.allajna = ".$_POST['committee']."
            ";
        }
        else {
            $query = "
            SELECT * FROM vote INNER JOIN voters WHERE 
            voters.id = vote.idUser AND vote.idParent = ".$_POST['idParent']."
            AND voters.gender = ".$_POST['gender']."
            AND voters.allajna = ".$_POST['committee']."
            ";
        }
      
    }
}


    
// }



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
$attend= 0;
foreach($results as $row)
{
 
    $x = "SELECT * FROM vote WHERE idUser = ? AND idSupervisor = ?";
    $stmt = $db->db->prepare($x);
    $stmt->execute([$row['idUser'],$_POST['idSupervisor']]);
   $dataVote = $stmt->fetch();
    if($row['attend'] == 1)
    {
        $attend++;
    }

    $first = '
    <span class="styleNumberCount">'.($y++).'</span>
    <input name="selector[]"  type="checkbox" class="getVote mainCheckbox" data-name="'.$row['username'].'"  data-id="'.$row['idUser'].'" multiple >
    <input type="hidden" class="names" name="names[]" value="'.$row['username'].'" >
    ';


    $getColor  = $db->db->prepare("SELECT * FROM voters WHERE id = ?");
    $getColor->execute([$row['idUser']]);
    $fetch = $getColor->fetch();
    $changeColor = $fetch['gender'] == 1 ? "#062bb1" : "#c51334";

    $subName =trim($row['fullName']);
    
//    $implode = count($subName);
$name =trim($row['fullName']);
$ex = explode(" ",$name);
if(strlen($name) >= 60)
{
     if(count($ex) == 9){
        array_pop($ex);
        $name = implode(" ",$ex)."...";

     }
    if(count($ex) >= 10)
    {
        array_pop($ex);
        array_pop($ex);
        array_pop($ex);
        $name = implode(" ",$ex)."...";
    }
 
   
}
    $name = '
    <a  href="#"  data-id="'.$row['idUser'].'" class="details" data-bs-toggle="modal" data-bs-target="#modalDetails" style="color:'.$changeColor.'">'.$name.'</a>

    ';

   
    $searchfamily = '
    <a data-username="'.$row['username'].'" class="searchForAqarb text-dark" href="#" data-bs-toggle="modal" data-bs-target="#exampleModa2">'.$row['familyName'].'</a>
    ';
        $button = '';
       
        if($dataVote && $dataVote['idSupervisor'] == $_POST['idSupervisor']){
            if($_POST['rank'] == 2)
            {
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
        }else{
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
            class="btn-success myBtnTable  ">
            <i class="ri-close-line"></i></button>

       
            ';
            }else{
                $button ='
             

                <button 
                data-names="'.$row['username'].'"                                                
                data-iduser = "'.$row['idUser'].'"
                data-idparent = "'. $_POST['idParent'].'"
                data-username = "'.$row['username'].'"   
                
                class="btn-primary   myBtnTable">
                <i class="ri-check-line"></i>
                </button>
              
                ';
                /* </button>
                <button  data-name="'.$row['fullName'].'"  
                class="btn-danger  btn-sm resBtn  resetVoteMain "     data-iduser="'.$row['id'].'"> <i class="ri-close-line"></i>  </button>*/
            }
        }

    }

    if($_POST['rank'] == 2)
    {
        $query3 = "SELECT * FROM vote WHERE idUser = ? AND idSupervisor = ? ";
        $stmt = $db->db->prepare($query3);
        $stmt->execute([$row['idUser'],$_POST['idSupervisor']]);
        $countVote = $stmt->fetchAll();
    }else{
        $query3 = "SELECT * FROM vote WHERE idUser = ? AND idSupervisor = ? AND idParent = ?";
        $stmt = $db->db->prepare($query3);
        $stmt->execute([$row['idUser'],$_POST['idSupervisor'],$_POST['idParent']]);
        $countVote = $stmt->fetchAll();
    }

    
    // <span class="badge bg-dark rounded-2">0</span>
    $count = '';
    if(count($countVote) > 0)
    {
            $count = '<span  class="badge bg-success rounded-2"> '.count($countVote).'</span>';
    }
    

    $sub_array = array();
    $sub_array[] = '<div data-x='.$row['id'].' class="responsive-name"> '.$first.'   '.$count.' </div>';
   
  
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
 'recordsFiltered' => count($results),
 'data'   => $data,
 'attend' => $attend,
);

echo json_encode($output);



?>