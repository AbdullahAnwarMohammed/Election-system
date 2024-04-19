<?php 
include("include/template/header.php");

function arrayUniqueMultidimensional($array) {
    $serialized = array_map('serialize', $array);
    $unique = array_unique($serialized);
    
    return array_map('unserialize', $unique);
}




?>


        
        <div class="appOne" style="background: #fff;">
    
  
        <div class="container py-4">
            <?php 

            if(isset($_POST['add_btn_task']))
            {
                $title = trim($_POST['title']);
                $date = $_POST['date'];
                $descrption = trim($_POST['descrption']);
                $importance = $_POST['importance'];
                $status =  isset($_POST['status']) ? 1 : 0;
                //`id_user`, `title`, `date`, `descrption`, `status`, `importance`
                $insert = $db->insertSuperVisor('tasks',array(
                    'id_user' =>$rowFrontend['idUser'],
                    'title' => $title,
                    'date' => $date,
                    'descrption' => $descrption,
                    'status' => $status,
                    'importance' => $importance
                    )); 
                    if($insert)
                    {
                        echo '<div class="alert alert-success">تم اضافة المهمة بنجاح</div>';
                    }  
            }
            if(isset($_POST['delete']))
            {
                $id = intval($_GET['id']);
                $stmt = $db->db->prepare("delete from tasks where id = ?");
                $deleted = $stmt->execute([$id]);
                if($deleted)
                {
                    echo '<div class="alert alert-success">تم الحذف بنجاح</div>';
                }
            }
           
            if(isset($_POST['update']))
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
                    echo '<div class="alert alert-success   ">تم التعديل بنجاح</div>';
                }
            
            
            }

            if(isset($_GET['action']))
            {
                if($_GET['action'] == 'add')
                {
                    ?>
                    <div class="add_new_taks">
                    <form id="formTask" action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
          <div class="mb-3">
            <div class="row">
                <div class="col">
                <?php 
                    $stmt = $db->db->prepare('select id from tasks order by id desc limit 1');
                    $stmt->execute();
                    $lastIdRow = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($lastIdRow)
                    {
                        ?>
                        <input type="text"  required value="<?=$lastIdRow['id'] + 1?>" disabled  placeholder="مسلسل" class="form-control" >
                        <?php 
                    }else{
                        ?>
                    <input type="text" value="1" disabled  placeholder="مسلسل" class="form-control" >

                        <?php 
                    }
                    ?>
                </div>

                <div class="col">
                <input type="text"  name="title" required placeholder="نص" class="form-control" >

                </div>
            </div>
           
            
          </div>
          
          <div class="mb-3">
            <input type="date"  name="date" required  class="form-control" >
          </div>
          <div class="mb-3">
           <textarea   name="descrption" placeholder="نص طويل" class="form-control" cols="30" rows="10"></textarea>
          </div>
          
          <div class="modal-footer d-felx justify-content-between" style="  flex-direction: row-reverse;">
          <button type="submit" name="add_btn_task" class="btn btn-success">حفظ</button>

          <div class="d-flex align-items-center" style="gap: 10px;">
          <label for="" class="d-flex">الاهمية</label>
          <select name="importance" class="form-control">
            <option value="5" id="">5</option>
            <option value="4" id="">4</option>
            <option value="3" id="">3</option>
            <option value="2" id="">2</option>
            <option value="1" id="">1</option>
            </select>
            <div class="d-flex align-items-center">
            <label for="">تمت</label>
            <input   type="checkbox" name="status" style="width:21px;height:21px">
            </div>
          </div>

            </div>
        </form>
                    </div>
                    <?php
                }
            }
            else{
            ?>
            <div class="show_task_page">
            <?php   
                    if(isset($_GET['id_task']))
                    {
                        $id = $_GET['id_task'];
                        $stmt = $db->db->prepare("select * from tasks where id_user = ? and id = ? order by id asc limit 1");
                        $stmt->execute([$rowFrontend['idUser'],$id]);
                        $fetch = $stmt->fetch();
                    }
                    else{
                        $stmt = $db->db->prepare("select * from tasks where id_user = ?  order by id asc limit 1");
                        $stmt->execute([$rowFrontend['idUser']]);
                        $fetch = $stmt->fetch();
                    }
                  
                    if($stmt->rowCount() > 0)
                    {

                  
                    ?>
                   <!-- Update -->
                <div class="head d-flex justify-content-between align-items-center py-2" style="background: blue;color:#fff">
                <i data-id="<?=$fetch['id']?>" style="font-size: 40px;" data-type="next" class="next_id_task ri-arrow-right-s-line"></i>
                <p id="title_task"><?=$fetch['title']?> <a href=" <?=$_SERVER['REQUEST_URI']?>&action=add"  class="btn btn-success">+</a></p>
                <i data-id="<?=$fetch['id']?>" data-type="prev" style="font-size: 40px;"  class="next_id_task ri-arrow-left-s-line"></i>
                </div>
                <div class="body_show_task">
                  
                    <form id="form_tasks" action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
                        <div class="my-2">
                            <div class="row">
                                <div class="col">
                                <label for="">مسلسل</label>
                        <input type="text" id="id_show_task"  disabled value="<?=$fetch['id']?>" class="form-control">
                        <input type="hidden" id="id_dask" name="id_task"  value="<?=$fetch['id']?>" class="form-control">

                                </div>
                                <div class="col bg-danger text-white p-1">
                                <label for="">عنوان المهمة</label>
                        <input type="text" id="title_task_page" name="title" value="<?=$fetch['title']?>"  class="form-control">

                                </div>
                            </div>
                        </div>
                       
                        <div class="my-2">
                        <label for="">تاريخ</label>
                        <input type="date" id="date_task" name="date"  value="<?=$fetch['date']?>"  class="form-control">
                        </div>
                        <div class="my-2">
                        <label for="">تفاصيل</label>
                        <textarea  id="desc_task" name="descrption" class="form-control" cols="30" rows="10"><?=$fetch['descrption']?></textarea>
                        </div>
                        <div class="row">
                            <div class="col-3">
                             
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-2">
                        <div class="d-flex align-items-center" style="gap: 10px;">
                        <label for="">الاهمية</label>
                        <select id="importance_task" name="importance"  class="form-control d-block">
                                <option value="5" <?= $fetch['importance'] == 5 ? "selected" : "" ?>>5</option>
                                <option value="4" <?= $fetch['importance'] == 4 ? "selected" : "" ?>>4</option>
                                <option value="3" <?= $fetch['importance'] == 3 ? "selected" : "" ?>>3</option>
                                <option value="2" <?= $fetch['importance'] == 2 ? "selected" : "" ?>>2</option>
                                <option value="1 <?= $fetch['importance'] == 1 ? "selected" : "" ?>">1</option>
                        </select>
                        <div class="d-flex align-items-center">
                        <label for="">تمت</label>
                        <input name="status" style="width:21px;height:21px" id="status_task" type="checkbox" <?= $fetch['status'] == 1 ? "checked" : "" ?> name="">
                        </div>

                        </div>
                        <button type="submit" name="update" class="btn btn-success"> حفظ </button>
                        <button type="submit" name="delete" class="btn btn-danger"> حذف </button>
                        </div>
                    </form>
                </div>
                <?php 
                    }
                    else{
                        echo '<span class="d-block text-center alert alert-warning">لا يوجد بيانات<a href="'.$_SERVER['REQUEST_URI'].'&action=add"  class="btn btn-success">+</a></span>';
                    }
                    ?>
            </div>
            <?php 
            }
            ?>
        </div>

        </div>






    


        <?php 
        require_once "modal.php";
        require_once "include/template/footer.php";
        ?>
   