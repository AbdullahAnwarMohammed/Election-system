<?php 
require_once "init.php";
$message = '';
if(isset($_SESSION['superVisor']))
{
header("location:index.php");
exit;
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{
$email = trim(strip_tags($_POST['email']));
$password = trim(strip_tags($_POST['password']));
$login = new Login($email,$password);
if($data = $login->actionLogin())
{
$_SESSION['superVisor'] = $data['username'];
$_SESSION['idSuperVisor'] = $data['id'];
$_SESSION['rankSuperVisor'] = $data['rank'];
header("location:index.php");
}else{
$message = '<div class=" alert alert-danger my-4 text-center">يوجد خطأ فى البيانات * </div>';
}
}
?>

<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Meta -->
	<meta name="description" content="Responsive Bootstrap4 Dashboard Template">
	<meta name="author" content="ParkerThemes">
	<link rel="shortcut icon" href="img/fav.png" />

	<!-- Title -->
	<title>CP MAGLES</title>
	
	<!-- *************
		************ Common Css Files *************
	************ -->
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="assets/css/bootstrap.min.css" />

	<!-- Master CSS -->
	<link rel="stylesheet" href="assets/css/main.css" />

</head>

<body class="authentication">

	<!-- Container start -->
	<div class="container">
		
		<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
		
			<div class="row justify-content-md-center">

				<div class="col-xl-4 col-lg-5 col-md-6 col-sm-12">
					<div class="login-screen">
					<?=$message ?  $message  : '' ?>
						<img src="assets/img/icon.png" width="130" class="text-center py-4 d-block m-auto" alt="">
						<div class="login-box">
								<h2 class="text-center ">منصة مجلس</h2>
							<h5>مرحباً بك<br />يرجي تسجيل الدخول</h5>
							<div class="form-group">
								<input required name="email" type="text" class="form-control" placeholder="البريد" />
							</div>
							<div class="form-group">
								<input required name="password" type="password" class="form-control" placeholder="كلمة المرور" />
							</div>
							<div class="actions mb-4">
								
								<button type="submit" class="btn btn-primary">تسجيل الدخول</button>
							</div>

						
						</div>
					</div>
				</div>
			</div>
		</form>

	</div>
	<!-- Container end -->

</body>
</html>