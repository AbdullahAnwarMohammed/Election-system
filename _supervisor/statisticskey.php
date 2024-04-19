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

                    'url': 'ajaxStatisticsKey.php',
                    'data': {
                        'usernow': '<?= $_SESSION['idSuperVisor'] ?>',
                        'information': information

                    }
                },
                pageLength: 10,
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

     


    })
</script>