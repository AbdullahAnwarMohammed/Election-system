<?php 
include("include/template/header.php");

function arrayUniqueMultidimensional($array) {
    $serialized = array_map('serialize', $array);
    $unique = array_unique($serialized);
    
    return array_map('unserialize', $unique);
}




?>


        
        <div class="appOne" style="background: #fff;">
        
        <div class="guarantor">
           
           <div class="title" style="<?=$idManger==2 ? "color:#fff;" : ""?>background:<?=$idManger == 2 ? "#555": "#fff"?>;display:flex; justify-content:center;align-items:center">
                <?php 
                $linkGuide = $db->db->prepare("SELECT * FROM user_guide");
                $linkGuide->execute();
                if($idManger == 2)
                {
                    $text = "مرشح";
                    $dataGuide = $linkGuide->fetch()['candidate'];

                }else if($idManger == 3)
                {
                    $text = "مفتاح";
                    $dataGuide = $linkGuide->fetch()['contractor'];

                }else if($idManger == 4)
                {
                    $text = "مضحر";
                    $dataGuide = $linkGuide->fetch()['representative'];

                }else{
                    $text = "مُتعهد";
                    $dataGuide = $linkGuide->fetch()['guarantor'];

                }
                ?>

               <h5> <span style="<?=$idManger==2 ? "color:#75f98c;" : ""?>"> <i class="ri-shield-user-fill"></i>مرحبا </span> <?=$rowFrontend['username']?></h5>


           </div>
                    <p class="btn btn-danger"><?=$text?></p>

           <a  target="_blank" href="<?=$dataGuide?>" class="btn btn-primary btn-sm">دليل المستخدم <i class="ri-error-warning-fill"></i> </a>

           <p>
               هذه الصفحة تمكنكم من متابعة الانتخابات
               <br />
               ولكم كل الشكر والتقدير على دعمكم لنا 🌹
           </p>
           <p>
  
           </p>
       </div>
       
       <div class="search textCenter">
           <div class="appSearch">
               <h6 class="openDropdown d-flex" style="justify-content: space-between;"> <span><i class="ri-search-line"></i> العائلة  </span> <span class="namePage">الصفحة الرئيسية</span> </h6>
               <div class="dropdown ">
                   <form action="" class="formSearch" onsubmit="return false">
                       <div>
                           <input type="text" style="width: 100%;" class="input_value_search" placeholder="البحث عن الإسم او الرقم">
                       </div>
                       <div>
                           
                            
                           <select  class="family_value_search" style="width: 100%;" >
                           <option selected disabled value="none">اسم العائلة</option>

                           
                           <?php 
                               $stmtSearch = $db->db->prepare("SELECT DISTINCT(familyName) FROM voters");
                               $stmtSearch->execute();
                               $rows = $stmtSearch->fetchAll();

                               foreach($rows as $row)
                               {
                                   ?>
                                  <option value="<?=$row['familyName']?>"><?=$row['familyName']?></option>

                                   <?php 
                               }
                               ?>
                           </select>
                       </div>
                       <div >
                       <button data-idevent="<?=$rowFrontend['id']?>" type="button" class="btn btn-success button_search disabled" data-bs-toggle="modal" data-bs-target="#exampleModa2">
                       البحث
                       </button>
                   </div>
                   </form>
               </div>
           </div>
       </div>
       <?php 
        if($idManger == 2 || $idManger == 3 || $idManger == 5 )
        {
            $attend = 0;
            $madmen = 0;
            $maleAttend = 0;
            $maleMadmen = 0;

            $femaleAttend = 0;
            $femaleMadmen = 0;
            $perc = 0;
            if($idManger == 2 )
            {

                $statment = $db->db->prepare(
                    "SELECT * FROM voters INNER JOIN vote
                    WHERE vote.idSupervisor = ? 
                    AND vote.idUser = voters.id
                    "
                );
                $statment->execute([$infoSupervisor['id']]);
                $fetchAll = $statment->fetchAll();

            }else if($idManger == 3)
            {
                    $statment = $db->db->prepare(
                    "SELECT * FROM voters INNER JOIN vote
                    WHERE vote.idParent = ? 
                    AND vote.idUser = voters.id
                    "
                );
                $statment->execute([$rowFrontend['idUser']]);
                $fetchAll = $statment->fetchAll();
            }else{
                $statment = $db->db->prepare(
                    "SELECT * FROM voters INNER JOIN vote
                    WHERE vote.idParent = ? 
                    AND vote.idUser = voters.id
                    "
                );
                $statment->execute([$rowFrontend['idUser']]);
                $fetchAll = $statment->fetchAll();
            }
        
            $temp = array_unique(array_column($fetchAll, 'username'));
            $results = array_intersect_key($fetchAll, $temp);
            if( $statment->rowCount() > 0)
            {
            foreach($results as $row)
            {
               
                if($row['attend'] == 1)
                {
                    $attend++;
                }
                
                if($row['attend'] == 1 || empty($row['attend']))
                {
                    $madmen++;
                }

                if($row['attend'] == 1  && $row['gender'] == 1)
                {
                    $maleAttend++;
                }

                if(empty($row['attend'])  && $row['gender'] == 1)
                {
                    $maleMadmen++ ;
                }
              

                if(empty($row['attend'])  && $row['gender'] == 2)
                {
                    $femaleMadmen++;
                }

                if($row['attend'] == 1 && $row['gender'] == 2)
                {
                    $femaleAttend++;
                }
            }
            $maleMadmen = $maleMadmen + $maleAttend; 
            $femaleMadmen = $femaleMadmen + $femaleAttend; 
            $perc = $attend * 100 / $madmen;
            }
            ?>
            <div style="display: flex;
    justify-content: center;
    gap: 7px ">
            <div style="font-size:12px;" >مضمون : <span style="font-size:12px;background:#198754;padding:0 5px;color:#fff"><?=$madmen?></span>  <span style="font-size:12px;background:#0d6efd;padding:0 5px;color:#fff"><?=$attend?></span></div>
            <div  style="font-size:12px;" >ذكور : <span style="font-size:12px;background:#198754;padding:0 5px;color:#fff"><?=$maleMadmen?></span>  <span style="font-size:12px;background:#0d6efd;padding:0 5px;color:#fff"><?=$maleAttend?></span></div>
            <div  style="font-size:12px;" >اناث : <span style="font-size:12px;background:#198754;padding:0 5px;color:#fff"><?=$femaleMadmen?></span>  <span style="font-size:12px;background:#0d6efd;padding:0 5px;color:#fff"><?=$femaleAttend?></span></div>
            <div  style="font-size:12px;" >النسبة :  <span style="font-size:12px;background:#0d6efd;padding:0 5px;color:#fff"><?=round($perc)?>%</span></div>
            </div>
            <?php
        }
       ?>

        <div class="listOfName">

                <select    class="headquarters" style="font-size:16px;background:#ffd400;border-radius:5px;" >
               <option selected value="all"> المقر</option>
               <?php 
               $stmt = $db->db->prepare("select distinct(name),idAllajna from databaseallijan where id_event = ? AND name is not null");
               $stmt->execute([$idEvent]);
               $fetch  = $stmt->fetchAll();
               foreach($fetch as $row)
               {
                ?>
               <option value="<?=$row['idAllajna']?>"><?=$row['name']?></option>

                <?php 
               }
               ?>
                </select>



                <select    class="committees" style="font-size:16px;background:#ffd400;border-radius:5px;" >
               <option selected value="all"> اللجان</option>
               <?php 
               $stmt = $db->db->prepare("select distinct(allajna) from voters where idEvent = ? AND allajna is not null");
               $stmt->execute([$idEvent]);
               $fetch  = $stmt->fetchAll();
               foreach($fetch as $row)
               {
                ?>
               <option value="<?=trim($row['allajna'])?>"><?=$row['allajna']?></option>

                <?php 
               }
               ?>
                </select>


                <select    class="areaName" style="font-size:16px;background:#ffd400;border-radius:5px;" >
               <option selected value="all"> المناطق</option>
               <?php 
               $stmt = $db->db->prepare("select distinct(areaName) from voters where idEvent = ? AND areaName is not null");
               $stmt->execute([$idEvent]);
               $fetch  = $stmt->fetchAll();
               foreach($fetch as $row)
               {
                ?>
               <option value="<?=trim($row['areaName'])?>"><?=$row['areaName']?></option>

                <?php 
               }
               ?>
                </select>


               <select   id="mainMaleOrFemale" class="mainMaleOrFemaleHomePage" style="font-size:16px;background:#ffd400;border-radius:5px;" >
                <option value="all" selected>الجميع</option>
                <option value="1">ذكور</option>
                <option value="2">اناث</option>
                </select>
                <button style="padding: 0;background:#ffd400" 
                  class="btn btn-sm  mychose fw-bold getMadmen"> <i class="ri-file-add-line"></i> المضامين</button>
                  <button style="padding: 0;background:#ffd400" 
                  class="btn btn-sm  mychose fw-bold getVoters d-none"> <i class="ri-file-add-line"></i> الرئيسية</button>

            <h4 class="openDropdown" > 

                <div class="d-flex" style="font-size: 15px;">
                عدد  
                <span class="getNumber"></span>
                <i class="ri-search-line"></i>
                </div> 
         

              
         
<!--                    
                 <button style="padding: 0;background:#ffd400" 
                  class="btn btn-sm  mychose fw-bold" data-bs-toggle="modal" data-bs-target="#exampleModa2"> <i class="ri-file-add-line"></i> المضامين</button>
              -->
                <!-- <a href="madmenHome.php?username=<?=$rowFrontend['nameEnglish']?>&id=<?=$rowFrontend['id']?>" target="_blank"  style="padding: 0;background:#ffd400">المضامين</a> -->
          
               
                </h4>
                
            <div id="showMamen" class="d-none dropdown">
                
                <div class="selectMadmen">
                <select style="font-size: 16px; background: rgb(255, 212, 0); border-radius: 5px;" id="GenderHome">
                <option value="" selected disabled>الجنس</option>
                <option value="all"  >الكل</option>
                        <option value="1">ذكور</option>
                        <option value="2">اناث</option>
                </select>


                <select style="font-size: 16px; background: rgb(255, 212, 0); border-radius: 5px;" id="committee">
                <option   value="" selected disabled>اللجان</option>
                <option value="all"  >الكل</option>


                <?php 
                  $getAllajna = $db->db->prepare(
                    "SELECT DISTINCT(allajna) FROM voters WHERE  idEvent = ?"
                );
                $getAllajna->execute([$idEvent]);
                foreach($getAllajna->fetchAll() as $row)
                {
               
                    ?>
                    <option   value=<?=$row['allajna']?>><?=$row['allajna']?></option>
                   <?php 
             
               
                
                }

                ?>
                    <option>1</option>
                </select>
                </div>
             
                <table class="table getTableMadmen  table-secondary table-striped" style="width:100%;">
            
                    <thead>
                    <tr>
                        <th>
                            <input type="checkbox" data-target=".mainCheckbox" class="checkall checkallMadmen" />
                            <?php   
                            if($idManger == 2)
                        {
                        ?>
                            <span class="getNumberMadmen2" style="position: absolute;right: 60px;"></span>

                        <?php 
                        } 
                        ?>
                        </th>
                        <th class="name2 " style="text-align: right;">اسماء المضامين</th>
                        <th> العائلة</th>
                        <th>
                        <span class="getNumberAttend"></span>

<span class="textAttend">حضور</span>

                        </th>
                        
                    </tr>
                    </thead>
                    <tbody>
                   
                    </tbody>
                </table>
              
            <div class="insertList ">
            <select name="" style="padding: 8px 0;" id="valueList2" class="form-control  rounded-0">

               
<?php   
    
    if($idManger == 4 )
    {
        ?>
    <option value="insert2" class="text-success">حاضر</option>

        <?php 
    }
if($Power !== false && $rowFrontend['idUser'] == $infoSupervisor['id'])
{

        ?>

        <option value="insertnameoflist" class="text-success">مضمون</option>
        <option value="resetdata" class="text-danger">غير مضمون</option>
        <?php 
}

if($Power === false)
{
?>
 <option value="insert2" class="text-success">حاضر</option>
<option value="insertnameoflist" class="text-success">مضمون</option>
<option value="resetdata" class="text-danger">غير مضمون</option>

<?php 
}

if($Power !== false && $Power['select_daman'] == 1)
{

?>
            <option value="insertnameoflist" class="text-dark ">مضمون</option>

<?php 
}
?>


<?php 

if($Power !== false && $Power['cancel_madmen'] == 1)
{
?>
<option value="resetdata" class="text-danger">غير مضمون</option>

<?php 
}
?>
<?php 
// $idManger == $idManger['rank']
if($idManger == 4 || $idManger == 2 )
{
    ?>
<option value="notpresentmain" class="text-dark">غير حاضر</option>
    <?php 
}
?>
<option disabled>----</option>

    <?php 
    $count = $db->getRows('list',array(
        'where' => array('idParent'=>$rowFrontend['idUser']),
        'return_type' => 'count'
    ));
    if($count > 0){
        $Lists = $db->getAll('list','idParent',$rowFrontend['idUser'],true);
        foreach($Lists as $row){
        ?>
        <option value="<?=$row['id']?>"><?=$row['name']?></option>
        <?php
        }
    }
    ?>
</select>
<?php 
if($idManger != '4')
{
?>
<button id="insetListContent"  class="voteButtonMain py-2 rounded-0">تطبيق</button>

<?php 
}
?>
<?php 

                ?>


</div>
            </div>
            <div class="dropdown active" id="showVoters">
           <div class="table-responsive" >
           <select class="searchname">
           <option value="1" >بحث عام</option>

        <option value="0">بحث مخصص</option>
        </select>
            <table class="table myTable  table-secondary table-striped refreshData" style="width:100%;">
   
            <thead>
                    <tr>
                        <th>
                        <input type="checkbox" data-target=".mainCheckbox" class="checkall" />
                         
                            <span class="getNumberMadmen" style="position: absolute;right: 56px;z-index:1   "></span>

                      
                        </th>

                        
                        <th class="name2" >اسماء الناخبين</th>
                        <th style="width: 10px;"> العائلة</th>
                        <th>
                        <span class="getNumberAttend"></span>

                        <span class="textAttend">حضور</span>
                        </th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>

            </div>

            <div class="insertList ">

                       <?php 

                                       ?>
            <select name="" style="padding: 8px 0;" id="valueList" class="form-control  rounded-0">

               
                <?php   
                    
                    if($idManger == 4 )
                    {
                        ?>
                        <option value="insert2" class="text-success">حاضر</option>

                        <?php 
                    }
            if($Power !== false && $rowFrontend['idUser'] == $infoSupervisor['id'])
            {

                        ?>

                        <option value="insertnameoflist" class="text-success">مضمون</option>
                        <option value="resetdata" class="text-danger">غير مضمون</option>
                        <?php 
            }

             if($Power === false)
             {
                ?>
                <option value="insertnameoflist" selected class="text-success">مضمون</option>
                                <option value="resetdata" class="text-danger">غير مضمون</option>

                <option value="insert2" class="text-success">حاضر</option>


                <?php 
             }
             
            if($Power !== false && $Power['select_daman'] == 1)
            {
                
                ?>
                            <option value="insertnameoflist" class="text-dark ">مضمون</option>

                <?php 
            }
            ?>


            <?php 
          
            if($Power !== false && $Power['cancel_madmen'] == 1)
            {
                ?>
                <option value="resetdata" class="text-danger">غير مضمون</option>

                <?php 
            }
            ?>
                <?php 
                // $idManger == $idManger['rank']
                if($idManger == 4 || $idManger == 2 )
                {
                    ?>
                <option value="notpresentmain" class="text-dark">غير حاضر</option>
                    <?php 
                }
                ?>
                <option disabled>----</option>

                    <?php 
                    $count = $db->getRows('list',array(
                        'where' => array('idParent'=>$rowFrontend['idUser']),
                        'return_type' => 'count'
                    ));
                    if($count > 0){
                        $Lists = $db->getAll('list','idParent',$rowFrontend['idUser'],true);
                        foreach($Lists as $row){
                        ?>
                        <option value="<?=$row['id']?>"><?=$row['name']?></option>
                        <?php
                        }
                    }
                    ?>
            </select>
            <?php 
            if($idManger != '4')
            {
                ?>
            <button id="insetListContent"  class="voteButtonMain py-2 rounded-0">تطبيق</button>

                <?php 
            }
            ?>
              
            </div>

            </div>
    
            <div class="countVoters">
                <?php 
                      // عدد الاشخاص فى القوائم
                      $query = "SELECT * FROM listcontent WHERE idParent = ? ";
                      $countAll = $db->db->prepare($query);
                      $countAll->execute([$rowFrontend['idUser']]);
                ?>
                    <input type="hidden" id="userNow" value="<?=$rowFrontend['idUser']?>">
                    
                    <h5  class="titlelist <?= $count > 0  ? 'openDropdown': '' ?>"> 
                     <span class="text-white"><?= $count > 0  ? '<span class="badge" style="background:#15336a">'.$count.'</span>' : '<span class="badge bg-light text-dark">0</span>' ?> القوائم </span>
                    </h5>
                    
                  <?php 
                    if($count > 0){
                    $Lists = $db->getAll('list','idParent',$rowFrontend['idUser'],true);
                    ?>
                    <div class="dropdown" style="width: 100%;">
                     <table style="margin: 0;" class="table table-warning table-bordered table-striped tablelist">
                        <thead>
                        <tr>
                        <th>
                        <span class="badge text-warning" style="width:100%;text-align:left;font-size:15px;font-weight:bold;"><?=$countAll->rowCount()?></span>
                        </th>
                        <th class="name text-center" >القائمة</th>
                        <th style="padding:0 10px; text-align: center;">النوع</th>
                        </tr>
                        </thead>
                        <?php
                        $i = 0; 
                    foreach($Lists as $row){
                        $i++;
                        // عدد الاشخاص فى القوائم
                        $query = "SELECT * FROM listcontent WHERE idList = ? ";
                        $count = $db->db->prepare($query);
                        $count->execute([$row['id']]);
                     
                        ?>

                        
                        <tr>

                        <td>
                        <div class="responsive-name">
                        <span class="styleNumberCount  text-white"  style="background:#15336a"><?=$i?></span>
                        <input type="checkbox"  
                        class="checkboxlist"
                         data-id=<?=$row['id']?> />
                         <i style="width:22px" class="badge bg-dark text-warning"><?=$count->rowCount()?></i>
                    </div>
                    </td>
                            <td     class="itemlist" style="padding:0 20px"
                             data-bs-target="#exampleModa2"  
                             data-bs-toggle="modal"   
                             data-id="<?=$row['id']?>"
                               data-name="<?=$row['name']?>">
                            <?=$row['name']?> </td>
                            
                            <td style="text-align: left;">
                            <span style="margin: 0 10px;"   class=" <?=$row['type'] == 1 ? 'badge bg-success': 'badge bg-warning' ?>" > <?=$row['type'] == 1 ? ' مضمون':' تجريبي' ?>  </span>

                            </td>
                        </tr>
                   

                       
                    <?php 
                    }
                    }
                    ?>
                                        </table>
                                        <button style="float:left;
                                         width:100%" 
                                         class=" btndeletelist">حذف <i class="ri-delete-bin-5-fill"></i></button>

                                        </div>

 
            </div>
                    
            <div class="createListNames">
                <div class="parent">
                <h3 class="openDropdown text-center">انشاء قائمة</h3>
                <div class="dropdown active">

                <form action="" id="createList">
                <input type="hidden" name="action" value="insertList" >
                <input type="hidden" name="idEvent" value="<?=$rowFrontend['id']?>" >
                <input type="hidden" class="idParent" name="idParent" value="<?=$rowFrontend['idUser']?>" />
                <input type="text" id="nameList" name="nameList" placeholder="اكتب اسم القائمة">
                
                <select name="typeList">
                <option value="1">مضمون</option>
                <option value="2">تجريبي</option>
                </select>
                <?php 
                if($Power !== false AND $Power['create_list'] == 0)
                {
                    ?>
                    <button class="click  width-100" disabled>متوقف</button>

                    <?php 
                }else{

                    if($idManger!='4')
                    {
                        ?>
                <button  class="click width-100">انشاء</button>

                        <?php
                    }
                    ?>
                    

                    <?php 
                }
                ?>
                </form>

                </div>
                
                </div>
            </div>
        
      
        
        </div>

        <?php 
        require_once "modal.php";
        require_once "include/template/footer.php";
        ?>
   