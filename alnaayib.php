<?php 
include("include/template/header.php");

function arrayUniqueMultidimensional($array) {
    $serialized = array_map('serialize', $array);
    $unique = array_unique($serialized);
    
    return array_map('unserialize', $unique);
}




?>


        
        <div class="appOne" style="background: #fff;">
    
      
        <div class="listOfName">

            
            <h4 class="openDropdown" style="background-color: #388024;"> 

                <div class="d-flex" style="font-size: 15px;">
                المهام  
                <span class="getNumberSearchPageTasks"></span>
                <i class="ri-search-line"></i>
                </div>      
                </h4>
                
            
            <div class="dropdown active" id="showVoters">
           <div class="table-responsive" >
         
            <table class="table tableTasks  table-secondary table-striped " style="width:100%;">
            <div style="position: absolute;
    z-index: 9999999;
    left: 23%;
    top: 46px;
    font-size: 13px;">
                  <select id="select_option" style="background-color: #ffd400;">
                  <option value="0">الحالية</option>
                  <option value="1">الكل</option>
                  </select>
                </div>
            <thead>
                
                    <tr>
                        <th >#</th>
                        <th>المهام</th>
                        <th>رتبه</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>

            </div>

        

            </div>
    
          
        
      
        
        </div>

        <!-- Modal Add New task -->
        <div class="modal fade" id="modal_add_new_task" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <h4>اضافة مهمة جديدة</h4>

        <form id="formTask">
          <div class="mb-3">
            <?php 
            $stmt = $db->db->prepare('select id from tasks order by id desc limit 1');
            $stmt->execute();
            $lastIdRow = $stmt->fetch(PDO::FETCH_ASSOC);
            ?>
            <input type="text" disabled value="<?=$lastIdRow['id'] + 1?>" placeholder="مسلسل" class="form-control" >
            <input type="hidden" name="id_user" value="<?=$rowFrontend['idUser']?>">
            <input type="hidden" name="action" value="addNewTask">
          </div>
          <div class="mb-3">
            <input type="text"  name="title"  placeholder="نص" class="form-control" >
          </div>
          <div class="mb-3">
            <input type="date"  name="date"  class="form-control" >
          </div>
          <div class="mb-3">
           <textarea   name="descrption" placeholder="نص طويل" class="form-control" cols="30" rows="10"></textarea>
          </div>
          <div class="row">
          <div class="col">
            <select name="importance" class="form-control">
            <option value="5" id="">5</option>
            <option value="4" id="">4</option>
            <option value="3" id="">3</option>
            <option value="2" id="">2</option>
            <option value="1" id="">1</option>
            </select>
          </div>
          <div class="col d-flex align-items-center">
            <label for="">تمت</label>
            <input   type="checkbox" name="status">
          </div>
          </div>
          <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">اغلاق</button>
        <button type="submit" class="btn btn-primary">حفظ</button>
      </div>
        </form>
      </div>
    
    </div>
  </div>
</div>



        <!-- Modal Update Task -->
        <div class="modal fade" id="modal_update_task" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <h4>اضافة مهمة جديدة</h4>

        <form id="formUpdateTask">
          <div class="mb-3">
            <input type="text" id="id" disabled placeholder="مسلسل" class="form-control" >
            <input type="hidden" name="id_user" value="<?=$rowFrontend['idUser']?>">
            <input type="hidden" name="action" value="updateTask">
            <input type="hidden" id="id_task" name="id_task">
          </div>
          <div class="mb-3">
            <input type="text" id="title_task"  name="title"  placeholder="نص" class="form-control" >
          </div>
          <div class="mb-3">
            <input type="date" id="date_task" name="date"  class="form-control" >
          </div>
          <div class="mb-3">
           <textarea  id="descrption_task"  name="descrption" placeholder="نص طويل" class="form-control" cols="30" rows="10"></textarea>
          </div>
          <div class="row">
          <div class="col">
            <select id="importance_task" name="importance" class="form-control">
            <option value="5" id="">5</option>
            <option value="4" id="">4</option>
            <option value="3" id="">3</option>
            <option value="2" id="">2</option>
            <option value="1" id="">1</option>
            </select>
          </div>
          <div class="col d-flex align-items-center">
            <label for="">تمت</label>
            <input  id="status_task" type="checkbox" name="status">
          </div>
          </div>
          <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">اغلاق</button>
        <button type="submit" class="btn btn-primary">حفظ</button>
      </div>
        </form>
      </div>
    
    </div>
  </div>
</div>




        <div class="listOfName">

            
            <h4 class="openDropdown" style="background-color: #171717;" > 

                <div class="d-flex" style="font-size: 15px;">
                المعاملات  
                <span class="getNumberSearchPage"></span>
                <i class="ri-search-line"></i>
                </div>      
                </h4>
                
            
            <div class="dropdown active" id="showVoters">
           <div class="table-responsive" >

            <table class="table tableTransactions  table-secondary table-striped " style="width:100%;">
   
            <thead>
                    <tr>
                        <th >#</th>
                        <th>المعاملة</th>
                        <th>الجهة</th>
                      <th>المندوب</th>
                        <th>رتبة</th> 
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>

            </div>

        

            </div>
    
          
        
      
        
        </div>


        <?php 
        require_once "modal.php";
        require_once "include/template/footer.php";
        ?>
   