<?php 
require_once("_supervisor/init.php");

$db = new DB();

$draw = $_POST['draw'];  
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir'];

$searchValue =$_POST['search']['value']; // Search value




if($_POST['action'] == 'ajaxSearch')
{
    $searchQuery = " ";
    if($searchValue != ''){
    $searchQuery .= " and (fullName like '%".$searchValue."%' ) ";
    }
    $records = $db->db->prepare("select count(*) as allcount from voters where idEvent = ?");
    $records->execute([$_POST['idEvent']]);
    $records = $records->fetch();
    $totalRecords = $records['allcount'];
     
    
    $sel = $db->db->prepare("select count(*) as allcount from voters WHERE
    idEvent = ".$_POST['idEvent']." AND 1 ".$searchQuery);
    $sel->execute();
    $records = $sel->fetch();
    $totalRecordwithFilter = $records['allcount'];
    
    
    $stmt = $db->db->prepare("select * from voters where 1".$searchQuery."order by id ".$columnSortOrder ." limit ".$row.",".$rowperpage);
    $stmt->execute();
    $data = array();
    while($item = $stmt->fetch())
    {
        $stmtVote = $db->db->prepare("select * from vote where idUser = ? and idParent = ?");
        $stmtVote->execute([$item['id'],$_POST['idUser']]);
        $fetch = $stmtVote->fetch();

        $bk = isset($fetch['level']) && $fetch['level'] == 1 ? "#5dff98" : "";
        // if(isset($fetch['level']) && $fetch['level'] == 1)
        // {
        //     $checkbox = '<input type="checkbox" data-idvoter="'.$item['id'].'" data-username="'.$item['fullName'].'" class="checkbox_ajax" checked />';
    
        // }else{
        //     $checkbox = '<input type="checkbox"  data-idvoter="'.$item['id'].'" data-username="'.$item['fullName'].'"  class="checkbox_ajax"  />';
        // }
        
        $color = $item['gender'] == 1 ? "#062bb1" : "#c51334";
        $name = '<span data-classname="'.$bk.'"  data-idvoter="'.$item['id'].'" data-username="'.$item['fullName'].'" style="color:'.$color.';background:'.$bk.';cursor: pointer;" class="addVoteFromSearch d-block">
        <i style="font-size:25px">'.$item['fullName'].'</i>
        <br />
        <i style=" font-size:16px">15 سنة</i>
        </span>

        ';
        $data[] = array(
            "name"=>$name
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
}



if($_POST['action'] == 'ajaxTasks')
{

    $searchQuery = " ";
    if($searchValue != ''){
    $searchQuery .= " and (title like '%".$searchValue."%' ) ";
    }

    
    $records = $db->db->prepare("select count(*) as allcount from tasks where id_user = ?");
    $records->execute([$_POST['idUser']]);
    $records = $records->fetch();
    $totalRecords = $records['allcount'];

     
    $sel = $db->db->prepare("select count(*) as allcount from tasks WHERE
    id_user = ".$_POST['idUser']." AND 1 ".$searchQuery);
    $sel->execute();
    $records = $sel->fetch();
    $totalRecordwithFilter = $records['allcount'];
    if($_POST['val_selected'] == 0)
    {
        $stmt = $db->db->prepare("select * from tasks where id_user = ? and status = 0 and  1".$searchQuery."order by importance ".$columnSortOrder ." limit ".$row.",".$rowperpage);
    }else{
        $stmt = $db->db->prepare("select * from tasks where id_user = ?  and  1".$searchQuery."order by importance ".$columnSortOrder ." limit ".$row.",".$rowperpage);

    }
    $stmt->execute([$_POST['idUser']]);
    $data = array();
    $i = 0;
    while($item = $stmt->fetch())
    {
      
        $classStatus  = $item['status'] == 1 ? "line-through" : "";
        $classColor  = $item['status'] == 1 ? "text-danger" : "text-primary";
        $title = '
        <div class="d-flex justify-content-between align-items-center">
        <a href="tasks.php?username='.$_POST['username']."&id=".$_POST['idFrontend'].'&id_task='.$item['id'].'"
        class="'.$classColor.' editTask"
        style="text-decoration:'.$classStatus.'"
        >

        '.$item["title"].'

        </a>

        <button class="btn btn-danger btn-sm" data-id="'.$item['id'].'" id="btnDeleteTask"><i class="ri-delete-bin-line"></i></button>

    
        </div>
        ';
        $i++;
        $data[] = array(
            'counter' => $i,
            "title"=>$title,
            'importance'=> $item['importance']
        );
    }
 

    $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data,
        'columnSortOrder' => $columnSortOrder

    );
     

    echo json_encode($response);
}


// آجاكس المعاملات

if($_POST['action'] == 'ajaxTransactions')
{
    $searchQuery = " ";
    if($searchValue != ''){
    $searchQuery .= " and (applicant like '%".$searchValue."%' ) ";
    }

    
    $records = $db->db->prepare("select count(*) as allcount from transactions where id_user = ?");
    $records->execute([$_POST['idUser']]);
    $records = $records->fetch();
    $totalRecords = $records['allcount'];

     
    $sel = $db->db->prepare("select count(*) as allcount from transactions WHERE
    id_user = ".$_POST['idUser']." AND 1 ".$searchQuery);
    $sel->execute();
    $records = $sel->fetch();
    $totalRecordwithFilter = $records['allcount'];
 
    $stmt = $db->db->prepare("select * from transactions where id_user = ?  and  1".$searchQuery."order by importance ".$columnSortOrder ." limit ".$row.",".$rowperpage);

    $stmt->execute([$_POST['idUser']]);
    $data = array();
    $i = 0;
    while($item = $stmt->fetch())
    {
        $order_to = $db->db->prepare('select * from order_to where id = ?');
        $order_to->execute([$item['direct_to']]);
        
        $mandoub = $db->db->prepare('select * from mandoub_transactions where id = ?');
        $mandoub->execute([$item['almandub']]);
        
        $i++;

        $title = '
        <a href="transactions.php?username='.$_POST['username']."&id=".$_POST['idFrontend'].'&id_trans='.$item['id'].'"
        >
        '.$item["applicant"].'
        </a>';
        $data[] = array(
            'counter' => $i,
            "title"=>$title,
            'order_to' => $order_to->fetch()['name'],
            'mandoub' => $mandoub->fetch()['name'],
            'importance' => $item['importance']
        );
    }
 

    $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data,
        'columnSortOrder' => $columnSortOrder

    );
     

    echo json_encode($response);
}
