<?php require_once("init.php"); ?>
<?php 

if(!$_SESSION['superVisor'])
{
    header("location:login.php");
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
		<title>CP MAGES</title>


		<!-- *************
			************ Common Css Files *************
		************ -->
		<!-- Bootstrap css -->
		<link rel="stylesheet" href="assets/css/bootstrap.min.css">

		<!-- Icomoon Font Icons css -->
		<link rel="stylesheet" href="assets/fonts/style.css">
		
		<!-- Main css -->
		<link rel="stylesheet" href="assets/css/main-light-color.css">


		<!-- *************
			************ Vendor Css Files *************
		************ -->
		
			<!-- Chartist css -->
			<!-- <link rel="stylesheet" href="assets/vendor/chartist/css/chartist.min.css" />
		<link rel="stylesheet" href="assets/vendor/chartist/css/chartist-custom.css" />
		 -->
		<!-- DateRange css -->
		<link rel="stylesheet" href="assets/vendor/daterange/daterange.css" />

			<!-- Data Tables -->
			<link rel="stylesheet" href="assets/vendor/datatables/dataTables.bs4.css" />
		<link rel="stylesheet" href="assets/vendor/datatables/dataTables.bs4-custom.css" />
		<link href="assets/vendor/datatables/buttons.bs.css" rel="stylesheet" />

		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css"  />
		<style>
			table tr td{
				font-weight: bold;
				text-wrap: nowrap;
			}
			.waiting {
				position: fixed;
				left: 0;
				top: 0;
				width: 100%;
				height: 100vh;
				z-index: 9999999999;
				background-color: #000;
				display: none;
				color: #fff;
				justify-content: center;
				align-items: center;
				opacity: 0.9;
			}
		</style>

	</head>

	<body>

	<div class="waiting">
  <span>جاري ادخال البيانات يرجي الانتظار...</span>
  <br />
<div class="spinner"></div>

</div>


		<!-- Loading starts -->
		<!-- <div id="loading-wrapper">
			<div class="spinner-border" role="status">
				<span class="sr-only">Loading...</span>
			</div>
		</div> -->
		<!-- Loading ends -->


		<!-- Page wrapper start -->
		<div class="page-wrapper">