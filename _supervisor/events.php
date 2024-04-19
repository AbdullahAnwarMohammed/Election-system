<?php 
include("include/template/_header.php");
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
                <li class="breadcrumb-item">الرئيسية</li>
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
        

        
        <?php 
            if(isset($_GET['action']))
            {
               
                if($_GET['action'] == 'deleteEvent')
                {
                   $sql = "ALTER TABLE voters AUTO_INCREMENT = 1 ";
                   $stmt  = $db->db->prepare($sql);
                    $stmt->execute();
                    header("location:events.php");
                }
                if($_GET['action'] == 'add')
                {
                    require_once "include/template/forms/events/addEvent.php";
                }
                if($_GET['action'] == 'show')
                {
                    require_once "include/template/forms/events/showEvent.php";
                }
                if($_GET['action'] == 'edit')
                {
                    require_once "include/template/forms/events/editEvent.php";

                }
            }
            else
            {
                ?>
                 <div class="d-flex align-items-center justify-content-between ">
            <h1 class="h3 mb-0 text-gray-800">الاحداث</h1>
    
            <a href="?action=add" class="btn btn-md btn-success shadow-sm"><i class="fas fa-plus-square"></i>اضافة</a>
            </div>

            <div class="row">
            <div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-6">
							<div class="info-tiles">
								<div class="info-icon">
									<i class="icon-flag2"></i>
								</div>
								<div class="stats-detail">
									<h3><?= $db->getInfo('events')  ? count($db->getInfo('events')) : '0'?></h3>
									<p>الاحداث</p>
								</div>
							</div>
						</div>
            </div>

            <div class="row gutters">
         
                    

                <?php 
                  if($db->getInfo('events'))
                  {
                    foreach($db->getInfo('events') as $row)
                    {
                    $countVoters = $db->db->prepare("SELECT COUNT(id) as count FROM voters WHERE idEvent = ?");
                    $countVoters->execute([$row['id']]);
                        
                    ?>
                       <div class="col-xl-3  col-md-6 col-sm-12 col-12">

                       <div class="card-deck">
                                    <div class="card text-center">
									<div class="card-body">
										<h5 class="card-title" >
                                      <a class="text-primary d-block mt-3 " href="?action=show&id=<?=$row['id']?>">
                                      <span class="icon-visibility"></span> <?=$row['name']?></a> 
                                                <span style="position: absolute;top:0;left:0"
                                            class="badge  bg-danger text-white"><?=$countVoters->fetch()['count']?> <span class="icon-users"></span></span>

                                        </h5>
                                        <hr />
                                        <p class=" my-3 card-text d-flex justify-content-center" style="gap: 10px;" >
                                            <a title="حذف" href="#"  class="deleteEvent" data-id="<?=$row['id']?>">
                                            <img src="assets/img/remove.png" />
                                            </a>

                                            <a  title="تعديل" href="?action=edit&id=<?=$row['id']?>" >

                                            <img src="assets/img/edit-info.png" />
                                            </a>

                                            <a href="voters.php?id=<?=$row['id']?>">
                                                <img src="assets/img/db.png" alt="">
                                            </a>


                                        </a>
										</p>
									</div>
								</div>
                                </div>
                                </div>
                    <?php 
                  }
                }else{
                    ?>
                  <a href="?action=deleteEvent" class=" btn btn-danger my-4">تفريغ قاعدة البيانات</a>

                    <?php 
                }
                ?>
            </div>
                <?php 
            }
            ?>
            

        </div>
        <!-- Main container end -->

    </div>
    <!-- Page content end -->
    <?php 
        include("include/template/_footer.php");
        ?>