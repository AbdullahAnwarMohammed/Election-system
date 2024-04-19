<?php 
include("include/template/header.php");




?>


        
        <div class="appOne" style="background: #fff;">
    <!--
        `id_user`, `date`, `applicant`, `phone_applicant`, 
        `order_by`, `phone_order_by`, `almueamala`, `details`, `direct_to`, 
        `almandub`, `importance`, `status_order`, `option_order`, `comment`
    --> 


            <?php 
            if(isset($_POST['add_transaction']))
            {
                $date = trim($_POST['date']);
                $applicant = trim($_POST['applicant']);
                $phone_applicant = trim($_POST['phone_applicant']);
                $order_by = trim($_POST['order_by']);
                $phone_order_by = trim($_POST['phone_order_by']);
                $almueamala = trim($_POST['almueamala']);
                $details = trim($_POST['details']);
                $direct_to = trim($_POST['direct_to']);
                $almandub = trim($_POST['almandub']);
                $importance = trim($_POST['importance']);
                $status_order = isset($_POST['statu s_order']) ? 1 : 0;
                $option_order = trim($_POST['option_order']);
                $comment = trim($_POST['comment']);


               $stmt = $db->db->prepare(
                "INSERT INTO transactions(
                    `id_user`, `date_trans`, `applicant`, `phone_applicant`, 
                    `order_by`, `phone_order_by`, `almueamala`, `details`, `direct_to`, 
                    `almandub`, `importance`, `status_order`, `option_order`, `comment`
                )
                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)
                "
               );
               $add = $stmt->execute([
                $rowFrontend['idUser'],$date,$applicant,$phone_applicant,$order_by,$phone_order_by,
                $almueamala,$details,$direct_to,$almandub,$importance,$status_order,$option_order,$comment
                ]
              
               );

               if($add)
               {
                   echo 'Done..';
               }

            }
            ?>

          <div class="container py-4">
            <?php 
            if(isset($_POST['add_btn_task']))
            {
                
            }
            if(isset($_POST['delete']))
            {
              
                $id = intval($_POST['id_show_trans_hidden']);
                $stmt = $db->db->prepare("delete from transactions where id = ?");
                $deleted = $stmt->execute([$id]);
                if($deleted)
                {
                    echo '<div class="alert alert-success">تم الحذف بنجاح</div>';
                }
              
            }

            if(isset($_POST['update']))
            {
                
                $date_trans = $_POST['date'];
                $applicant = trim($_POST['applicant']);
                $phone_applicant = trim($_POST['phone_applicant']);
                $order_by = trim($_POST['order_by']);
                $phone_order_by = trim($_POST['phone_order_by']);
                $almueamala = $_POST['almueamala'];
                $details = $_POST['details'];
                $direct_to = $_POST['direct_to'];
                $almandub = trim($_POST['almandub']);
                $importance = $_POST['importance'];
                $status_order =  isset($_POST['status_order']) ? 1 : 0;
                $option_order = $_POST['option_order'];
                $comment = $_POST['comment'];

               /* 
                `date_trans`, `applicant`, `phone_applicant`, 
                `order_by`, `phone_order_by`, `almueamala`, `details`, `direct_to`,
                 `almandub`, `importance`, `status_order`, `option_order`, `comment`
                 */
                $actionTask = $db->db->prepare(
                    "
                    UPDATE transactions SET
                    date_trans = ?,
                    applicant = ?,
                    phone_applicant = ?,
                    order_by = ?,
                    phone_order_by = ?,
                    almueamala = ?,
                    details = ?,
                    direct_to = ?,
                    almandub = ?,
                    importance = ?,
                    status_order = ?,
                    option_order = ?,
                    comment = ?
                    WHERE 
                    id = ?
                    "
                );
            
            
               $update =  $actionTask->execute([
                $date_trans,$applicant,
                $phone_applicant,$order_by,$phone_order_by,$almueamala,$details,
                $direct_to,$almandub,$importance,$status_order,$option_order,$comment,
                $_POST['id_show_trans_hidden']
                ]);

                if($update)
                {
                    echo '<div class="alert alert-success   ">تم التعديل بنجاح</div>';
                }
            

            }

            
            if(isset($_GET['action']))
            {
                if($_GET['action'] == 'add')
                {
                    $stmt_transactions = $db->db->prepare('select * from transactions where id = ? order by id asc');
                    $stmt_transactions->execute([$rowFrontend['idUser']]);
                    $result = $stmt_transactions->rowCount() > 0 ? $stmt_transactions->fetch()['id'] + 1 : 1; 
                    ?>
                    <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
                        <div class="row my-2">
                            <div class="col">
                                <label for="">مسلسل</label>
                                <input type="text" value="<?=$result?>" disabled class="form-control">
                            </div>
                            <div class="col">
                                <label for="">تاريخ الاستلام</label>
                                <input required type="date" value="<?=date("Y-m-d")?>" id="date" name="date"  class="form-control">
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col">
                                <label for="">مقدم الطلب</label>
                                <input required  type="text" id="applicant" name="applicant" class="form-control">
                            </div>
                            <div class="col">
                                <label for="">هاتف مقدم الطلب</label>
                                <input required  type="number" id="phone_applicant"  name="phone_applicant"  class="form-control">
                            </div>
                        </div>

                        <div class="row my-2">
                            <div class="col">
                                <label required  for="">عن طريق</label>
                                <input type="text" id="order_by" name="order_by" class="form-control">
                            </div>
                            <div class="col">
                                <label for="">الهاتف</label>
                                <input required  type="number" id="phone_order_by"  name="phone_order_by"  class="form-control">
                            </div>
                        </div>
                        <div class="my-2">
                            <div class="col">
                                <label for="">المعاملة</label>
                                <input required type="text" id="almueamala" name="almueamala" class="form-control">
                            </div>
                        </div>
                        <div class="my-2">
                            <div class="col">
                                <label for="">تفاصيل</label>
                                <input required  type="text" id="details" name="details" class="form-control">
                            </div>
                        </div>

                        <div class="my-2">
                            <div class="col">
                                <label for="">موجه الي</label>
                                <select required  name="direct_to" id="direct_to" class="form-control">
                                    <?php
                                    $stmt = $db->db->prepare("select * from order_to");
                                    $stmt->execute();
                                    foreach($stmt->fetchAll() as $row)
                                    {
                                        ?>
                                            <option value="<?=$row['id']?>"><?=$row['name']?></option>
                                        <?php 
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="my-2">
                            <div class="col">
                                <label for="">مندوب <a href="#" data-bs-toggle="modal" data-bs-target="#add_new_mandoub_modal">اضافة مندوب</a></label>
                                <?php 
                                $stmt = $db->db->prepare('select * from mandoub_transactions where id_user = ?');
                                $stmt->execute([$rowFrontend['idUser']]);
                                ?>
                                <select required name="almandub" id="almandub" class="form-control">
                                    <?php 
                                    if($stmt->rowCount())
                                    {
                                        foreach($stmt->fetchAll() as $item)
                                        ?>
                                        <option value="<?=$item['id']?>"><?=$item['name']?></option>
                                        <?php 
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="my-2">
                            <div class="col">
                                <label for="">الاهمية</label>
                                <select required name="importance" id="importance" class="form-control">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                        </div>

                        <div class="my-2">
                            <div class="row">
                                <div class="col">
                                <label for="">حالة الطلب</label>
                                <input  type="checkbox" name="status_order" >
                                </div>
                                <div class="col">
                                <label for="">الانتظار</label>
                                <input type="radio" name="option_order" value="1">
                                <label for="">الموافقة</label>
                                <input type="radio" name="option_order" value="2">
                                <label for="">مرفوض</label>
                                <input type="radio" name="option_order" value="3">
                                <label for="">تم</label>
                                <input type="radio" name="option_order" value="4">
                                </div>
                            </div>
                        </div>

                        <div class="my-2">
                            <label for="">ملاحظات</label>
                            <input type="text" class="form-control" name="comment">
                        </div>

                        <input type="submit" name="add_transaction" value="اضافة" class="btn btn-success">

                    </form>
                    <?php 
                }
            }
            else
            {
            ?>


            <div class="show_task_page">
            <?php   
                    if(isset($_GET['id_trans']))
                    {
                        $id = $_GET['id_trans'];
                        $stmt = $db->db->prepare("select * from transactions where id_user = ? and id = ? order by id asc limit 1");
                        $stmt->execute([$rowFrontend['idUser'],$id]);
                        $fetch = $stmt->fetch();
                    }
                    else{
                        $stmt = $db->db->prepare("select * from transactions where id_user = ?  order by id asc limit 1");
                        $stmt->execute([$rowFrontend['idUser']]);
                        $fetch = $stmt->fetch();
                    }
                  
                    if($stmt->rowCount() > 0)
                    {

                  
                    ?>
                   <!-- Update -->
                <div class="head d-flex justify-content-between align-items-center py-2" style="background: blue;color:#fff">
                <i data-id="<?=$fetch['id']?>" style="font-size: 40px;" data-type="next" class="next_id_trans ri-arrow-right-s-line"></i>
                <p id="title_trans"><?=$fetch['applicant']?> <a href=" <?=$_SERVER['REQUEST_URI']?>&action=add"  class="btn btn-success">+</a></p>
                <i data-id="<?=$fetch['id']?>" data-type="prev" style="font-size: 40px;"  class="next_id_trans ri-arrow-left-s-line"></i>
                </div>
                <div class="body_show_task">
                  
                <form id="form_trans" action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
                        <div class="row my-2">
                            <div class="col">
                                <label for="">مسلسل</label>
                                <input type="text" id="id_show_trans" name="id_show_trans" value="<?=$fetch['id']?>" disabled class="form-control">
                                <input type="hidden" id="id_show_trans_hidden" name="id_show_trans_hidden" value="<?=$fetch['id']?>" class="form-control">
                            </div>
                            <div class="col">
                                <label for="">تاريخ الاستلام</label>
                                <input required type="date" value="<?=$fetch['date_trans']?>" id="date" name="date"  class="form-control">
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col">
                                <label for="">مقدم الطلب</label>
                                <input required  type="text" id="applicant" value="<?=$fetch['applicant']?>" name="applicant" class="form-control">
                            </div>
                            <div class="col">
                                <label for="">هاتف مقدم الطلب</label>
                                <input required  type="number" id="phone_applicant" value="<?=$fetch['phone_applicant']?>"   name="phone_applicant"  class="form-control">
                            </div>
                        </div>

                        <div class="row my-2">
                            <div class="col">
                                <label required  for="">عن طريق</label>
                                <input type="text" id="order_by" name="order_by" value="<?=$fetch['order_by']?>" class="form-control">
                            </div>
                            <div class="col">
                                <label for="">الهاتف</label>
                                <input required  type="number" id="phone_order_by" value="<?=$fetch['phone_order_by']?>"  name="phone_order_by"  class="form-control">
                            </div>
                        </div>
                        <div class="my-2">
                            <div class="col">
                                <label for="">المعاملة</label>
                                <input required type="text" id="almueamala" value="<?=$fetch['almueamala']?>" name="almueamala" class="form-control">
                            </div>
                        </div>
                        <div class="my-2">
                            <div class="col">
                                <label for="">تفاصيل</label>
                                <input required  type="text" id="details" value="<?=$fetch['details']?>"  name="details" class="form-control">
                            </div>
                        </div>

                        <div class="my-2">
                            <div class="col">
                                <label for="">موجه الي</label>
                                <select required  name="direct_to" id="direct_to" class="form-control">
                                    <?php
                                    $stmt = $db->db->prepare("select * from order_to");
                                    $stmt->execute();
                                    foreach($stmt->fetchAll() as $row)
                                    {
                                        $selected = $row['id'] == $fetch['direct_to'] ? "selected" : "";
                                        ?>
                                            
                                            <option  <?= $selected ?> class="direct_to"  value="<?=$row['id']?>"><?=$row['name']?></option>
                                        <?php 
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="my-2">
                            <div class="col">
                                <label for="">مندوب <a href="#" data-bs-toggle="modal" data-bs-target="#add_new_mandoub_modal">اضافة مندوب</a></label>
                                <?php 
                                $stmt = $db->db->prepare('select * from mandoub_transactions where id_user = ?');
                                $stmt->execute([$rowFrontend['idUser']]);
                                $fetchAll = $stmt->fetchAll();
                                ?>
                                <select required name="almandub" id="almandub" class="form-control">
                                    <?php 
                                    if($stmt->rowCount())
                                    {
                                        foreach($fetchAll as $item)
                                        {
                                        $selected = $item['id'] == $fetch['almandub'] ? "selected" : "";
                                        ?>
                                        
                                        <option class="almandub" <?= $selected ?> value="<?=$item['id']?>"><?=$item['name']?></option>
                                        <?php 
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="my-2">
                            <div class="col">
                                <label for="">الاهمية</label>
                                <select required name="importance" id="importance" class="form-control">
                                    <?php 
                                    $array = [1,2,3,4,5];
                                    foreach($array as $item)
                                    {
                                        $selected = $item == $fetch['importance'] ? "selected" : "";
                                        ?>
                                  <option class="importance" <?= $selected ?> value="<?=$item?>"><?=$item?></option>
                                        <?php 
 
                                    }
                                    ?>
                                    
                                </select>
                            </div>
                        </div>

                        <div class="my-2">
                            <div class="row">
                                <div class="col">
                                <label for="">حالة الطلب</label>
                                <?php 
                                 $selected =  $fetch['status_order'] == 1 ? "checked" : "";
                                ?>
                                <input value="<?=$fetch['status_order']?>" class="status_order"  type="checkbox" name="status_order" <?= $selected ?>  >
                                </div>
                                <div class="col">
                                <label for="">الانتظار</label>
                                <input class="option_order" type="radio" name="option_order" <?= $fetch['option_order'] == "1" ? "checked" : "" ?> value="1">
                                <label  for="">الموافقة</label>
                                <input class="option_order" type="radio" name="option_order" <?= $fetch['option_order'] == "2" ? "checked" : "" ?> value="2">
                                <label for="">مرفوض</label>
                                <input class="option_order"  type="radio" name="option_order"  <?= $fetch['option_order'] == "3" ? "checked" : "" ?> value="3">
                                <label for="">تم</label>
                                <input class="option_order" type="radio" name="option_order" <?= $fetch['option_order'] == "4" ? "checked" : "" ?>  value="4">
                                </div>
                            </div>
                        </div>

                        <div class="my-2">
                            <label for="">ملاحظات</label>
                            <input type="text"  class="comment form-control" value="<?=$fetch['comment']?>" name="comment">
                        </div>

                      <div class="d-flex justify-content-between">
                        <input type="submit" name="update" value="تعديل" class="btn btn-success">
                        <input type="submit" name="delete" value="حذف" class="btn btn-danger">
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

                        <!-- Modal Add New Mandoub -->
<div class="modal fade" id="add_new_mandoub_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="add_from_mandoub">
            <div class="form-group">
                
                <input type="text" id="name" placeholder="الاسم" class="form-control" name="name">
            </div>
            <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
        <button type="submit" class="btn btn-primary">حفظ</button>
      </div>
        </form>
      </div>
  
    </div>
  </div>
</div>


          </div>

        <?php 
        require_once "modal.php";
        require_once "include/template/footer.php";
        ?>
   