<?php
include("include/template/_header.php");
$candidate = $db->getSingleInfo('infocandidate','idCandidate', $_SESSION['idSuperVisor']);
$rowFrontend = $db->getSingleInfo('frontend','idUser', $_SESSION['idSuperVisor']);
?>

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

<?php 

if(isset($_POST['changePassword']))
{
	$password = trim(strip_tags($_POST['password']));
	$statusPassword = $_POST['statusPassword'];
	$update = $db->db->prepare("update infocandidate set
	statusPassword = ?,
	password = ?
	where
	idCandidate = ?
	");
	$up = $update->execute([$statusPassword,$password,$_SESSION['idSuperVisor']]);
	if($up)
	{
		echo '
		<script>
		alert("تم تعديل البيانات بنجا");
		</script>
		';
		header("location:index.php");
	}
}

 if(isset($_POST['uploadCover']))
 {
	 if(isset($_FILES['file']))
	 {
	

	 $upload = new upload('file','uploads/footer/',array('jpg','png'),'5000000');

	 if($upload->checkIsUpload())
	 {
		
	  
		 $upload->upload();
		 $update = $db->db->prepare("
			 UPDATE infocandidate
			 SET footerImage = ?
			 WHERE 
			 idCandidate  = ? 
		 ");
		 $update->execute([$upload->getDirection(),$_SESSION['idSuperVisor']]);
	 }
	 }
   
	  header("location:index.php");

	  } 
?>
<div class="page-header">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><?= isset($rowFrontend['event']) ? $rowFrontend['event'] : 'الرئيسية'?></li>
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
	if ($_SESSION['rankSuperVisor'] == 1) {
	?>
	
		<div class="d-flex justify-content-between mb-4">
			<h4>الصفحة الرئيسية</h4>

			<a href="events.php?action=add" class="btn btn-md btn-success "><i class="fas fa-download fa-sm text-white-50"></i> انشاء حدث</a>
		</div>

	<?php
	}



	if ($_SESSION['rankSuperVisor'] === 3) {


		if ($_SERVER['HTTP_HOST'] == 'localhost') {
			$link = $_SERVER['HTTP_HOST'] . '/electionSite/index.php?username=' . $rowFrontend['nameEnglish'] . '&id=' . $rowFrontend['id'];
		} else {
			$link = $_SERVER['HTTP_HOST'] . '/' . 'index.php?username=' . $rowFrontend['nameEnglish'] . '&id=' . $rowFrontend['id'];;
		}

	?>
		<div class="d-flex justify-content-between mb-4">

			<a href="#" data-link='<?= $link ?>' class="btn btn-info copylink "> صفحتك <i class="fas fa-link"></i></a>

			<a href="daman.php?master=<?= $_SESSION['idSuperVisor'] ?>" class="btn btn-success">المُتعهدون</a>
		</div>
	<?php
	}

	if($_SESSION['rankSuperVisor'] == 3){
		$Damans = $db->getAll('daman','idMusharif',$_SESSION['idSuperVisor'],'ye');
		$countDaman  = $Damans ? count($Damans) : '0';
		$countsVote = 0;
		if($Damans){
			foreach($Damans as $row)
			{
				$Votes = $db->getAll('vote','idParent',$row['id'],'yes');
				if(!empty($Votes)){
				foreach($Votes as $val)
				{
					$countsVote += 1;
				}

				}
			}
		}
		?>

<div class="row gutters">
							<div class="col-md-6 border  bg-white">
								<div class="overall-earnings">
									<div class="earnings-icon">
										<i class="icon-local_airport"></i>
									</div>
									<div class="earnings-stats">
										<p>المُتعهدون</p>
										<h3><?=$countDaman?></h3>
									</div>
								</div>
							</div> 
							<div class="col-md-6 border bg-white">
								<div class="overall-earnings">
									<div class="earnings-icon dark">
										<i class="icon-star"></i>
									</div>
									<div class="earnings-stats">
										<p>اصوات المُتعهدون</p>
										<h3><?=$countsVote?></h3>
									</div>
								</div>
							</div>
							
						</div>



<?php
	}
	
	?>

	


	<?php

	if ($_SESSION['rankSuperVisor'] == 1) {
		$adminCount = $db->getAll('supervisor','rank', '1', 'yes');
		$infocandidateCount = $db->getAll('supervisor','rank', '2', 'yes');
		$musharifinCount = $db->getAll('supervisor','rank', '3', 'yes');
		$eventsCount = $db->getAll('events');

	?>
		<div class="row">
			<div class="col-lg-3 mb-4 text-center">
				<div class="card bg-dark text-white shadow">
					<div class="card-body" style="background-image: linear-gradient( 135deg, #92FFC0 10%, #002661 100%);">
						<h4><a class="text-white" href="events.php">الحدث <i class="far fa-calendar-plus"></i></a></h4>
						<span class="h2 ">
							<?= !empty($eventsCount) ? count($eventsCount) : 'صفر' ?>
						</span>
					</div>
				</div>

			</div>
			<div class="col-lg-3 mb-4 text-center">
				<div class="card  text-white shadow">
					<div class="card-body" style="background-image: linear-gradient( 135deg, #92FFC0 10%, #002661 100%);">
						<h4><a class="text-white" href="supervisors.php">المشرفين <i class="fas fa-user-shield"></i></a></h4>
						<span class="h2 ">
							<?= !empty($adminCount) ? count($adminCount) : 'صفر' ?>
						</span>
					</div>
				</div>

			</div>
			<div class="col-lg-3 mb-4 text-center">
				<div class="card   text-white shadow">
					<div class="card-body" style="background-image: linear-gradient( 135deg, #92FFC0 10%, #002661 100%);">
						<h4><a class="text-white" href="candidate.php">المرشحين <i class="fas fa-users"></i></a></h4>
						<span class="h2 ">
							<?= !empty($infocandidateCount) ? count($infocandidateCount) : 'صفر' ?>
						</span>
					</div>
				</div>

			</div>
			
		</div>
	<?php
	}
	?>
	<!-- TOP INDEX -->
	<?php
	if ($_SESSION['rankSuperVisor'] == 2) {
		$usernow = $db->getSingleInfo('supervisor','id', $_SESSION['idSuperVisor']);

		
		$supervisor = $db->getSingleInfo('supervisor','id',$_SESSION['idSuperVisor']);
		$frontend = $db->getSingleInfo('frontend','idUser',$_SESSION['idSuperVisor']);
		$passwords = $db->getSingleInfo('passwords','id_user',$_SESSION['idSuperVisor']);

		$nameEnglish = $frontend['nameEnglish'];
		$password = $passwords['password'];
	
	?>
	<div class="row gutters">
			<div class="col-12">
				<div class="user-details h-320" style="background:url(<?=$candidate['footerImage']?>);background-size:cover;">
					
					<div class="user-thumb">
						<img src="<?=$usernow['image']?>" alt="Admin Template">
					</div>
				</div>
			</div>

			<div class="col-12">
			<div class="row gutters" style="margin-top: 60px;">
			<div class="col-xl-4 col-lg-2 col-md-4 col-sm-4 col-6 border activeLink link" data-link="information">
				<div class="overall-earnings">
					<div class="earnings-icon">
						<i class="icon-home"></i>
					</div>
					<div class="earnings-stats">
						<h5>البيانات</h5>
					</div>
				</div>
			</div>
			<div class="col-xl-4 col-lg-2 col-md-4 col-sm-4 col-6 border bg-white link" data-link="members">
				<div class="overall-earnings " >
					<div class="earnings-icon">
						<i class="icon-users"></i>
					</div>
					<div class="earnings-stats">
						<h5>المستخدمون</h5>
					</div>
				</div>
			</div>
			
		
			</div>

			<div class="row gutters">
			
				<div class="col-12 information my-4">
					<div class="desc">
						<h5>وصف المرشح : <?=$_SESSION['superVisor']?></h5>
						<p>
							<?=$candidate['descCandidate']?>
						</p>
					</div>
					<div class="row gutters bg-white py-2">
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="">الاسم بالانجليزي</label>
									<input class="form-control" value="<?=$nameEnglish?>" disabled type="text" placeholder="">
								</div>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
								<label for="">عن انتخابات</label>

									<input class="form-control" value="<?=$candidate['nameEvent']?>" disabled type="text" placeholder="">
								</div>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
								<label for="">تاريخ الميلاد</label>

									<input class="form-control " value="<?=$candidate['age']?>" disabled  type="text" placeholder="">
								</div>
							</div>
							
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label for="">كلمة المرور</label>
									<input class="form-control "  value="<?=$password?>" disabled  type="text" placeholder="">
								</div>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
								<label for="">البريد</label>

									<input class="form-control" value="<?=$supervisor['email']?>" disabled type="text" placeholder="">
								</div>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
								<label for="">تاريخ التسجيل</label>

									<input class="form-control" value="<?=$supervisor['dateReg']?>" disabled type="text" placeholder="">
								</div>
							</div>
							
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
								<label for="">الوظيفة</label>

									<input class="form-control" value="مرشح" disabled type="text" placeholder="">
								</div>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
								<label for="">رابط الموقع</label>

									<input class="form-control" value="<?= 'https://'.$_SERVER['HTTP_HOST'] . '/' . 'index.php?username=' . $frontend['nameEnglish'] . '&id=' . $frontend['id']?>" disabled   type="text" placeholder="">
								</div>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
								<label for="">رقم الهاتف</label>

									<input class="form-control " value="<?=$supervisor['phone']?>" disabled type="text" placeholder="">
								</div>
							</div>
							<div class="col-md-12 my-2" >
								<?php 
								$stmt = $db->db->prepare("select * from infocandidate where idCandidate = ?");
								$stmt->execute([$_SESSION['idSuperVisor']]);
								$fetch = $stmt->fetch();

								?>	
								<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
									<div class="row">
									<div class="col-md-6">
										<label for="">تفعيل/ايقاف كلمة السر لصفحة الانتخابات</label>
										<select name="statusPassword" class="form-control">
										<?php 
										if($fetch['statusPassword'] == 1)
										{
											?>
											<option selected value="1">تفعيل</option>
											<option value="0">ايقاف</option>
											<?php 
										}
										else 
										{
											?>
											<option value="1">تفعيل</option>
											<option selected value="0">ايقاف</option>
											<?php 
										}
										?>
										
										</select>
									</div>
									<div class="col-md-6">
										<label for="">كلمة مرور صفحة الانتخابات</label>
										<input class="form-control" name="password" required type="text" value="<?=$fetch['password']?>" />
									</div>
									<div class="col-12 my-1">
										<button name="changePassword" type="sumbit" class="btn-block btn btn-info">تعديل البيانات</button>
									</div>
									</div>
							</form>

									</div>
									
						

							
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 ">
							<label for="">الصورة الشخصية</label>
        					<input type="file" class="form-control imagePerson">
								<div class="avatar  xxxl">
									<a target="_blank" href="<?= 'https://'.$_SERVER['HTTP_HOST'] . '/' . 'index.php?username=' . $frontend['nameEnglish'] . '&id=' . $frontend['id']?>">
									
									<img  src="<?=$supervisor['image']?>" width="100" class=" circle border" alt=""> 

									</a>
								</div>
								<div class="col-12 my-4">
    <style>

.changeColor li {
        display: inline-block;
        width: 50px;
        height: 50px;
        cursor: pointer;
    }

    .changeColor li:nth-of-type(1) {
        background-color: #1a8e5f;
    }
    .changeColor li:nth-of-type(2) {
        background-color: #a7c957;
    }
    .changeColor li:nth-of-type(3) {
        background-color: #c1121f;
    }
    .changeColor li:nth-of-type(4) {
        background-color: #fb8500;
    }
    .changeColor li:nth-of-type(5) {
        background-color: #000;
    }
    .changeColor li:nth-of-type(6) {
        background-color: #219ebc;
    }
    .changeColor li:nth-of-type(7) {
        background-color: #ff006e;
    }
    .changeColor li:nth-of-type(8) {
        background-color: #c77dff;
    }
    </style>
    <ul class="changeColor">
        <li data-color="1a8e5f" ></li>
        <li data-color="a7c957"></li>
        <li data-color="c1121f"></li>
        <li data-color="fb8500"></li>
        <li data-color="t000"></li>
        <li data-color="t219ebc"></li>
        <li data-color="ff006e"></li>
        <li data-color="c77dff"></li>
        
    </ul>

    </div>

							</div>
							<div class="col-xl-8 col-lg-8 col-md-4 col-sm-4">
							<label for="">صورة الغلاف</label>
        <form action="<?=$_SERVER['PHP_SELF']?>" method="POST" enctype="multipart/form-data">
         <input type="file" name="file" class="form-control imageCover">
         <button type="submit" name="uploadCover" class="btn btn-success my-1">تعديل</button>

        </form>

								<img  src="<?=$candidate['footerImage']?>" class="img-thumbnail" alt=""> 
							</div>
							
							
						</div>
				</div>
				<div class="col-12 members d-none my-4">
					<div class="row gutters">
					<div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
						<div class="user-details2 h-320">
							<div class="user-thumb">
								<h1  class="text-success">
									<i class="icon-vpn_key"></i>
								</h1>
							</div>
							<h5 class="my-2">المفاتيح</h5>
							<a href="show_condidate.php?master=<?=$_SESSION['idSuperVisor']?>" class="btn btn-lg btn-primary text-white">لينك</a>
						</div>
					</div>
					<div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
						<div class="user-details2 h-320">
							<div class="user-thumb">
								<h1  class="text-success">
								<img src="assets/img/support.png" width="30" alt="">
								</h1>
							</div>
							<h5 class="my-2">المُتعهدون</h5>
							
							<a href="show_damans.php?master=<?=$_SESSION['idSuperVisor']?>" class="btn btn-lg btn-primary text-white">لينك</a>
						</div>
					</div>
					<div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
						<div class="user-details2 h-320">
							<div class="user-thumb">
							<h1 class="text-success">
								<img src="assets/img/attend.png" width="30" alt="">
							</h1>
							</div>
							<h5 class="my-2">المحضرون</h5>

							<a href="mandub.php" class="btn btn-lg btn-primary text-white">لينك</a>
						</div>
					</div>
					<div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
						<div class="user-details2 h-320">
							<div class="user-thumb">
								<h1  class="text-success">
									<img src="assets/img/peoples.png" width="30" alt="">
								</h1>
							</div>
							<h5 class="my-2">المضامين</h5>
							
							<a href="madmen.php" class="btn btn-lg btn-primary text-white">لينك</a>
						</div>
					</div>
					
					</div>
				</div>

				
			</div>


			</div>
		
		</div>
	<?php 
	}
	?>




<div class="modal fade" style="direction: ltr;" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Crop image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <div class="row">
                        <div class="col-md-8">  
                            <!--  default image where we will set the src via jquery-->
                            <img id="image">
                            
                        </div>
                        <div class="col-md-4">
                        <style type="text/css">
                            img {
                                display: block;
                                max-width: 100%;
                            }
                            .previewImage {
                                overflow: hidden;
                                width: 160px; 
                                height: 160px;
                                margin: 10px;
                                border: 1px solid red;
                            }
                            
                        </style>

                            <div class="previewImage"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" data-action="" id="crop">Crop</button>
            </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

<script>
$(function(){
	var bs_modal = $('#modal');
    var image = document.getElementById('image');
    var cropper,reader,file;
   
            
   function showModalImage(ele)
   {
    $("body").on("change", ele, function(e) {
        var files = e.target.files;
        var done = function(url) {
            image.src = url;
            bs_modal.modal('show');
        };

     
        

        if(ele == ".imagePerson")
        {
            $("#crop").attr("data-action","imagePerson")
        }
        else 
        {

            $("#crop").attr("data-action","imageCover")
        }


        if (files && files.length > 0) {
            file = files[0];

            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function(e) {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
            }
        }
    });

   }
   showModalImage('.imagePerson');

    bs_modal.on('shown.bs.modal', function() {
        cropper = new Cropper(image, {
            aspectRatio: 1,
            viewMode: 3,
            preview: '.previewImage'
        });
    }).on('hidden.bs.modal', function() {
        cropper.destroy();
        cropper = null;
    });


    
        
    $("#crop").click(function() {
       if($(this).data("action") == "imagePerson")
       {
        changeImage('changePerson');
       }
     
    });

    
    function changeImage(action,width,height)
    {

        canvas = cropper.getCroppedCanvas({
            width: 850,
            height: 350,
        });

        canvas.toBlob(function(blob) {
            url = URL.createObjectURL(blob);
            var reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = function() {
                var base64data = reader.result;
                console.log(base64data)
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "ajax_backend.php",
                    data: {action:action,image: base64data},
                    success: function(data) { 
                        bs_modal.modal('hide');
                        alert("success upload image");
                    }
                });
            };
        });
    
    }


})


$(".link").on("click",function(){
	let link = $(this).data("link");
	if(link === "members")
	{
		
		$(".members").removeClass("d-none");
		$(this).addClass("activeLink");
		$(this).siblings().removeClass("activeLink")
		$(".information").hide();
	}else{
		$(".members").addClass("d-none");
		$(this).addClass("activeLink");
		$(this).siblings().removeClass("activeLink")

		$(".information").show();
	}
})
</script>