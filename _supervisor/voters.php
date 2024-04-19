<?php 
include("include/template/_header.php");
if($_SESSION['rankSuperVisor'] != 1 || !isset($_GET['id']) || !is_numeric($_GET['id']))
{
    header("location:index.php");
    exit;
}
?>
    
    <!-- Sidebar wrapper start -->
    <?php  include("include/template/_sidebar.php"); ?>

    <!-- Sidebar wrapper end -->

    <!-- Page content start  -->
    <div class="page-content">

        <!-- Header start -->
        <?php 
        include("include/template/_navbar.php");
        ?>
        <!-- Header end -->

        <!-- Page header start -->
        <div class="page-header">
            <ol class="breadcrumb">
                <?php 
                if(isset($_GET['action']) && $_GET['action'] == 'edit')
                {
                ?>
                <li class="breadcrumb-item">بيانات الناخب</li>
                <?php 
                }
                else if(isset($_GET['action']) && $_GET['action'] == 'add'){
                    ?>
                    <li class="breadcrumb-item">اضافة ناخب</li>
                    <?php 
              
                }else{
                    ?>
                    <li class="breadcrumb-item">بيانات الناخبين</li>
                    <?php 
                }
                ?>
                
                <!-- <li class="breadcrumb-item active">Admin Dashboard</li> -->
            </ol>

            <ul class="app-actions">
                <li>
                    <a href="#" id="reportrange">
                        <span class="range-text"></span>
                        <i class="icon-chevron-down"></i>	
                    </a>
                </li>
                <li>
                    <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print">
                        <i class="icon-print"></i>
                    </a>
                </li>
                <li>
                    <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Download CSV">
                        <i class="icon-cloud_download"></i>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Page header end -->
        
        <div class="main-container">

<!-- Row start -->
<div class="row gutters">
  
    
   
</div>
<?php 
if(isset($_GET['action']))
{
    if($_GET['action'] == 'delete')
    {
        $db->deleteSuperVisor('voters','id',$_GET['id']);
        header("location:voters.php?id=".$_GET['idEvent']);
    }
    if($_GET['action'] == 'edit')
    {
        require_once "include/template/forms/voters/editVoter.php";
    }
    if($_GET['action'] == 'add')
    {
        require_once "include/template/forms/voters/addVoter.php";
    }
}
else 
{


?>
<?php 
$event = $db->getSingleInfo('events','id',$_GET['id']);

?>
<h2>قاعدة بيانات حدث : <?=$event['name']?></h2>
<a href="?action=add&id=<?=$event['id']?>" class="btn btn-success my-2">اضافة ناخب</a>
<a href="#" class="removes btn-danger p-2" style="background-color: tomato;">حذف الناخبين</a>
<a href="events.php" class=" btn-dark p-2">رجوع </a>
<div class="row text-center text-white" >
    <?php 
    $stmt = $db->db->prepare("select * from voters where idEvent = ?");
    $stmt->execute([$_GET['id']]);
    $all = 0;
    $male = 0;
    $female = 0;
   foreach($stmt->fetchAll() as $row)
   {
    $all++;
    if($row['gender'] == 1)
    {
        $male++;
    }else{
        $female++;
    }
   }
    ?>
    <div class="col-md-4 bg-dark py-2"><h4>
        العدد 
        <br />
        <?=$all?></h4></div>
    <div class="col-md-4 bg-info py-2"><h4>
        ذكور
        <br />
        <?=$male?></h4>
    </div>
    <div class="col-md-4 bg-danger py-2"><h4>
    آناث
    <br />

    <?=$female?>
   
    </h4></div>
</div>
<div class="table-container">
                    <div class="table-container">
                        <div class="t-header d-flex  justify-content-between align-items-center  text-white bg-info">
                        <?=$event['name']?>
                        </div>
                        <div class="table-responsive">

        <table id="empTable" class=" table custom-table" style="width: 100%;">
            <thead >
                <tr>
                <th>الاسم</th>
                <th>رقم القيد</th>
                <th>رقم الجنسية</th>
                <th>العائلة</th>
                <th>المنطقة</th>
                <th>رقم الصندوق</th>
                <th>رقم الهاتف</th>
                <th>اللجنة</th>
                <th>الاعدادت</th>
                </tr>
            </thead>

        </table>
                        </div>
                    </div>
        </div>
        
<!-- Row end -->
<?php 
}
?>


</div>

    </div>
    <!-- Page content end -->
    <?php 
        include("include/template/_footer.php");
        ?>

<script>
    $(function(){

        $(".removes").on("click",function(){
            if(confirm("سوف تقوم بحذف العناصر المحددة , هل انت متاكد من ذالك !! ")) {
                var option_id = [];
				$(':checkbox:checked').each(function(i){
					option_id[i] = $(this).data("id");
				});
                if(option_id.length === 0) {
					alert ("من فضلك قوم بالتحديد اولاً");
				}else{
					$.ajax({
						url: 'ajax_backend.php',
						method: 'POST',
						data : {action:'deleteVoters',option_id:option_id},
						success: function(data){
                            console.log(data);
							for(var i=0; i<option_id.length; i++)
						      {
						       $('#hide'+option_id[i]).parent().parent().fadeOut('slow');
						      }
						}
					})
				}
            }else{
                $(':checkbox:checked').each(function(i){
					$(this).prop("checked",false)
				});
            }
          

        })
        
        function getAjax(information) {
            var empDataTable = $('#empTable').DataTable({

                language: {
                    url: '//cdn.datatables.net/plug-ins/2.0.0/i18n/ar.json',
                },  
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                "processing": true,
                "bDestroy": true,
                "bJQueryUI": true,
                'ajax': {

                    'url': 'ajaxVoters.php',
                    'data': {
                        'idEvent': '<?= $event['id'] ?>',
                        'information': information

                    }
                },
                pageLength: 100,
                
  
                'columns': [
                  
                    {
                        data: 'fullName'
                    },
                   
                    {
                        data: 'raqmAlqayd'
                    },
                    {
                        data: 'nationalityNumber'
                    },
                    {
                        data: 'familyName'
                    },
                    {
                        data: 'areaName'
                    },
                    {
                        data: 'raqmAlsunduq'
                    },
                    {
                        data: 'phone'
                    },
                    {
                        data:'allajna'
                    },
                    {
                        data:'setting'
                    }
                  

                ]
            });
        }
        getAjax('key');
    })
</script>