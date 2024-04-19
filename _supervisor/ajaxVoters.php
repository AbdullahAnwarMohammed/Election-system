<?php
require_once("init.php");


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
   $searchQuery .= " and (id like '%".$searchValue."%' ) 
   OR 
   (fullName like '%".$searchValue."%' ) 
   OR 
   (raqmAlqayd like '%".$searchValue."%' ) 
   OR 
   (nationalityNumber like '%".$searchValue."%' ) 
   OR 
   (familyName like '%".$searchValue."%' ) 
   OR 
   (areaName like '%".$searchValue."%' ) 
   OR 
   (raqmAlsunduq like '%".$searchValue."%' ) 
   OR 
   (phone like '%".$searchValue."%' ) 
   OR 
   (allajna like '%".$searchValue."%' ) 
   ";
}

## جلب عدد المفاتيح 
if($_POST['information'] == 'key')
{
    $mofta7 = $db->db->prepare("select count(*) as allcount from voters where idEvent = ?");
}

$mofta7->execute([$_POST['idEvent']]);
$records = $mofta7->fetch();
$totalRecords = $records['allcount'];
 
## جلب عدد المفاتيح  عشان باجنيشن
if($_POST['information'] == 'key')
{
    $sel = $db->db->prepare("select count(*) as allcount from voters WHERE
    idEvent = ".$_POST['idEvent']." AND 1 ".$searchQuery);
}


$sel->execute();
$records = $sel->fetch();
$totalRecordwithFilter = $records['allcount'];


    $mofta7 = $db->db->prepare("select * from voters where idEvent = ? AND 1".$searchQuery."
    order by ".$columnName." ".$columnSortOrder ." limit ".$row.",".$rowperpage);



$mofta7->execute([$_POST['idEvent']]);
$data = array();




    while($row = $mofta7->fetch()){
        /*مضمون */
        $changeColor = $row['gender'] == 1 ? "#062bb1" : "#c51334";
        $fullName = '
        <input type="checkbox" class="deleteVote" id="hide'.$row['id'].'" data-id='.$row['id'].'  />
        <span >
        <a href="?action=edit&id='.$row['id'].'&idEvent='.$_POST['idEvent'].'"" target="_blank"  style="color:'.$changeColor.'">
        '.$row['fullName'].'
        </a>
        </span>';

        $raqmAlqayd = $row['raqmAlqayd'];
        $nationalityNumber = $row['nationalityNumber'];
        $familyName = $row['familyName'];
        $areaName = $row['areaName'];
        $raqmAlsunduq = $row['raqmAlsunduq'];
        $phone = $row['phone'];
        $allajna = $row['allajna'];
        $setting = '
      
        <a href="?action=delete&id='.$row['id'].'&idEvent='.$_POST['idEvent'].'" class="text-danger">حذف</a>
            
        ';
        $data[] = array(
                "fullName"=>$fullName,
                'raqmAlqayd' => $raqmAlqayd,
                'nationalityNumber' => $nationalityNumber,
                'familyName' => $familyName,
                'areaName' => $areaName,
                'raqmAlsunduq' => $raqmAlsunduq,
                'phone' => $phone,
                'allajna' => $allajna,
                'setting' => $setting
            );
    }



## Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);
 
echo json_encode($response);
 