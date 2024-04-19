<?php
include("include/template/_header.php");
$frontend = $db->getSingleInfo('frontend','idUser',$_SESSION['idSuperVisor']);

?>
<style>
    #empTable tr td, #empTable tr th{
        text-align: center;
    }
    </style>
<!-- Sidebar wrapper start -->
<?php include("include/template/_sidebar.php"); ?>

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
            <li class="breadcrumb-item"><?=$frontend['event']?></li>
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

    <!-- Main container start -->
    <div class="main-container">
        <div class="row">
            <div class="col-6 btn btn-info active toggleStatics" data-toggle="key">مُفتاح</div>
            <div class="col-6 btn btn-dark  toggleStatics" data-toggle="contractor">مُتعهد</div>
            <div class="col-12">
               <?php 
                $statment = $db->db->prepare(
                    "SELECT * FROM voters INNER JOIN vote
                    WHERE vote.idSupervisor = ? 
                    AND vote.idUser = voters.id
                    "
                );
                $statment->execute([$_SESSION['idSuperVisor']]);
                $fetchAll = $statment->fetchAll();
              $temp = array_unique(array_column($fetchAll, 'username'));
              $results = array_intersect_key($fetchAll, $temp);

              $attend = 0;
            $madmen = 0;
            $maleMadmen = 0;
            $maleAttend = 0;
            $femaleMadmen = 0;
            $femaleAttend = 0;
            $perc= 0;
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
            
            </div>
        </div>
        <div class="table-container">
                    <div class="table-container">
                        <div class="t-header d-flex  justify-content-between align-items-center  text-white bg-info">
                            الاحصائيات
                        </div>
                        <div class="table-responsive">

        <table id="empTable" class=" table custom-table" style="width: 100%;">
            <thead >
                <tr >
                <th>الاسم</th>
                <th>مضمون</th>
                <th>ذكور</th>
                <th>اناث</th>
                <th>النسبة</th>

                </tr>
            </thead>

        </table>
                        </div>
                    </div>
        </div>
    </div>
    <!-- Main container end -->

</div>
<!-- Page content end -->
<?php
include("include/template/_footer.php");
?>

<script>
    $(function() {


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

                    'url': 'ajaxStatistics.php',
                    'data': {
                        'usernow': '<?= $_SESSION['idSuperVisor'] ?>',
                        'information': information

                    }
                },
                pageLength: 100,
                'columns': [
                    {
                        data: 'name'
                    },
                    {
                        data: 'madmoen'
                    },
                    {
                        data: 'male'
                    },
                    {
                        data: 'female'
                    },
                    {
                        data: 'perc'
                    },

                ]
            });
        }
        getAjax('key');

        $(".toggleStatics").on("click", function() {

            $(this).siblings().removeClass("btn-info")
            $(this).siblings().addClass("btn-dark")
            $(this).addClass(" btn-info");
            getAjax($(this).data("toggle"))

        })


    })
</script>