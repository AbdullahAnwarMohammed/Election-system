<?php
require_once("init.php");

$db = new DB();

$draw = $_POST['draw'];  
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue =$_POST['search']['value']; // Search value


## Search 
$searchQuery = " ";
if($searchValue != ''){
   $searchQuery .= " and (id like '%".$searchValue."%' ) ";
}

## جلب عدد المفاتيح 
if($_POST['information'] == 'key')
{
    $mofta7 = $db->db->prepare("select count(*) as allcount from musharifin_candidate where idCandidate = ?");
}
else 
{
    $mofta7 = $db->db->prepare("select count(*) as allcount from daman where idSuperviosr = ?");

}
$mofta7->execute([$_POST['usernow']]);
$records = $mofta7->fetch();
$totalRecords = $records['allcount'];
 
## جلب عدد المفاتيح  عشان باجنيشن
if($_POST['information'] == 'key')
{
    $sel = $db->db->prepare("select count(*) as allcount from musharifin_candidate WHERE
idCandidate = ".$_POST['usernow']." AND 1 ".$searchQuery);
}
else
{
    $sel = $db->db->prepare("select count(*) as allcount from daman WHERE
    idSuperviosr = ".$_POST['usernow']." AND 1 ".$searchQuery);
}

$sel->execute();
$records = $sel->fetch();
$totalRecordwithFilter = $records['allcount'];


## بيانات المفتاح
if($_POST['information'] == 'key')
{
    // "select * from vote WHERE 1 ".$searchQuery."
    //  order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

    $mofta7 = $db->db->prepare("select * from musharifin_candidate where idCandidate = ? AND 1".$searchQuery."order by id ".$columnSortOrder ." limit ".$row.",".$rowperpage);

}
else 
{
    $mofta7 = $db->db->prepare("select * from daman where idSuperviosr = ?AND 1".$searchQuery."order by id ".$columnSortOrder ." limit ".$row.",".$rowperpage);
}
$mofta7->execute([$_POST['usernow']]);
$data = array();
if($_POST['information'] == 'key')
{
    while($row = $mofta7->fetch()){
        $supervisor = $db->db->prepare("select * from supervisor where id = ?");
        $supervisor->execute([$row['idMusharifin']]);
        $supervisor = $supervisor->fetch();
    
        $salary = $supervisor['id'];
        $salaryarray = "$ $salary";

        /*مضمون */
        $madmoen = $db->db->prepare("SELECT * FROM voters INNER JOIN vote
        WHERE vote.idParent = ? 
        AND vote.idUser = voters.id");
        $madmoen->execute([$supervisor['id']]);
        $fetchAll = $madmoen->fetchAll();
        $temp = array_unique(array_column($fetchAll, 'username'));
        $results = array_intersect_key($fetchAll, $temp);
        
        $madmoenCount = count($fetchAll);
        $attend = 0;
        $male = 0;
        $female = 0;
        $attendMale = 0;
        $MadmoenMale = 0;

        $attendFemale = 0;
        $MadmoenFemale = 0;

        $perc = 0;
        foreach($fetchAll as $row)
        {
           if($row['level'] == 2)
           {
            $attend++;
           }
            if($row['gender'] == 1)
            {
                $male++;
                if($row['level'] == 1)
                {
                    $MadmoenMale++;
                }else{
                    $attendMale++;
                }

            }

            if($row['gender'] == 2)
            {
                if($row['level'] == 1)
                {
                    $MadmoenFemale++;
                }else{
                    $attendFemale++;
                }
                
            }


           
            
        }
       if($madmoenCount != 0)
       {
        $perc = '<span class="btn btn-sm btn-info">'. round($attend * 100 / $madmoenCount).'%</span>';
       }else{
        $perc = '<span class="btn btn-sm btn-danger">0</span>';
       }

     
        $madmoen = '
        <span class="btn btn-success btn-sm">'.$madmoenCount.'</span><span class="btn btn-info  btn-sm">'.$attend.'</span>
        ';
        $male = '
        <span class="btn btn-success btn-sm">'.$MadmoenMale.'</span><span class="btn btn-info  btn-sm">'.$attendMale.'</span>
        ';
        $female = '
        <span class="btn btn-success btn-sm">'.$MadmoenFemale.'</span><span class="btn btn-info  btn-sm">'.$attendFemale.'</span>
        ';   
        
        
        $rowFrontend = $db->getSingleInfo('frontend','idUser', $supervisor['id']);
        if ($_SERVER['HTTP_HOST'] == 'localhost') {
          $link = 'https://'.$_SERVER['HTTP_HOST'] . '/electionSite/index.php?username=' . $rowFrontend['nameEnglish'] . '&id=' . $rowFrontend['id'];
        } else {
          $link =  'https://'.  $_SERVER['HTTP_HOST'] . '/' . 'index.php?username=' . $rowFrontend['nameEnglish'] . '&id=' . $rowFrontend['id'];;
        }

        
        $changeColor = $supervisor['gender'] == 0 ? "#062bb1" : "#c51334";
        $username = '<span ><a style="color:'.$changeColor.'" target="_blank" href="'.$link.'">'.$supervisor['username'].'</a></span>';


        
        $data[] = array(
                "name"=>$username,
                'madmoen' => $madmoen,
                'male' => $male,
                'female' => $female,
                'perc' => $perc
            );
    }
}
else 
{

    while($row = $mofta7->fetch()){
 
        /*مضمون */
        $madmoen = $db->db->prepare("SELECT * FROM voters INNER JOIN vote
        WHERE vote.idParent = ? 
        AND vote.idUser = voters.id");
        $madmoen->execute([$row['id']]);
        $fetchAll = $madmoen->fetchAll();
        $temp = array_unique(array_column($fetchAll, 'username'));
        $results = array_intersect_key($fetchAll, $temp);
        
        $madmoenCount = count($fetchAll);
        $attend = 0;
        $male = 0;
        $female = 0;
        $attendMale = 0;
        $MadmoenMale = 0;

        $attendFemale = 0;
        $MadmoenFemale = 0;

        $perc = 0;
        foreach($fetchAll as $row2)
        {
           if($row2['level'] == 2)
           {
            $attend++;
           }
            if($row2['gender'] == 1)
            {
                $male++;
                if($row2['level'] == 1)
                {
                    $MadmoenMale++;
                }else{
                    $attendMale++;
                }

            }

            if($row2['gender'] == 2)
            {
                if($row2['level'] == 1)
                {
                    $MadmoenFemale++;
                }else{
                    $attendFemale++;
                }
                
            }


           
            
        }
        if($madmoenCount != 0)
        {
         $perc = '<span class="btn btn-sm btn-info">'. round($attend * 100 / $madmoenCount).'%</span>';
        }else{
         $perc = '<span class="btn btn-sm btn-danger">0</span>';
        }

        
        $madmoen = '
        <span class="btn btn-success btn-sm">'.$madmoenCount.'</span><span class="btn btn-info  btn-sm">'.$attend.'</span>
        ';
        $male = '
        <span class="btn btn-success btn-sm">'.$MadmoenMale.'</span><span class="btn btn-info  btn-sm">'.$attendMale.'</span>
        ';
        $female = '
        <span class="btn btn-success btn-sm">'.$MadmoenFemale.'</span><span class="btn btn-info  btn-sm">'.$attendFemale.'</span>
        ';

        $salary = $row['id'];
        $salaryarray = "$ $salary";

        $rowFrontend = $db->getSingleInfo('frontend','idUser', $row['id']);
        if ($_SERVER['HTTP_HOST'] == 'localhost') {
          $link = 'https://'.$_SERVER['HTTP_HOST'] . '/electionSite/index.php?username=' . $rowFrontend['nameEnglish'] . '&id=' . $rowFrontend['id'];
        } else {
          $link =  'https://'.  $_SERVER['HTTP_HOST'] . '/' . 'index.php?username=' . $rowFrontend['nameEnglish'] . '&id=' . $rowFrontend['id'];;
        }

        $changeColor = $row['gender'] == 0 ? "#062bb1" : "#c51334";
        $username = '<span >
        <a target="_blank" href="'.$link.'" style="color:'.$changeColor.'">
        '.$row['username'].'
        </a>
        </span>';
        
        $data[] = array(
                "name"=>$username,
                'madmoen' => $madmoen,
                'male' => $male,
                'female' => $female,
                'perc' => $perc
            );
    }
}


## Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);
 
echo json_encode($response);
 