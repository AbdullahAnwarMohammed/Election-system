
</div>
		<!-- Page wrapper end -->

		<!--**************************
			**************************
				**************************
							Required JavaScript Files
				**************************
			**************************
		**************************-->
		<!-- Required jQuery first, then Bootstrap Bundle JS -->
		<script src="assets/js/jquery.min.js"></script>
		<script>

			const element = document.querySelectorAll('.changeColor li');
const list = [];

document.body.classList.add(localStorage.getItem('color'))

element.forEach(e => {
  list.push(e.getAttribute('data-color'));

  e.addEventListener('click', function(){
    document.body.classList.remove(...list);
    document.body.classList.add(this.getAttribute('data-color'));
    localStorage.setItem('color', this.getAttribute('data-color'))
  });
});
		</script>
		<script src="assets/js/bootstrap.bundle.min.js"></script>
		<script src="assets/js/moment.js"></script>
		<script src="
		https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js
		"></script> 

		<!-- *************
			************ Vendor Js Files *************
		************* -->
		<!-- Slimscroll JS -->
		<script src="assets/vendor/slimscroll/slimscroll.min.js"></script>
		<script src="assets/vendor/slimscroll/custom-scrollbar.js"></script>

			<!-- Data Tables -->
			<script src="assets/vendor/datatables/dataTables.min.js"></script>
		<script src="assets/vendor/datatables/dataTables.bootstrap.min.js"></script>
				<!-- Custom Data tables -->
				<script src="assets/vendor/datatables/custom/custom-datatables.js"></script>
		<script src="assets/vendor/datatables/custom/fixedHeader.js"></script>

			<!-- Download / CSV / Copy / Print -->
			<script src="assets/vendor/datatables/buttons.min.js"></script>
		<script src="assets/vendor/datatables/jszip.min.js"></script>
		<script src="assets/vendor/datatables/pdfmake.min.js"></script>
		<script src="assets/vendor/datatables/vfs_fonts.js"></script>
		<script src="assets/vendor/datatables/html5.min.js"></script>
		<script src="assets/vendor/datatables/buttons.print.min.js"></script>


		<!-- Daterange -->
		<script src="assets/assets/vendor/daterange/daterange.js"></script>
		<script src="assets/assets/vendor/daterange/custom-daterange.js"></script>

		<!-- Chartist JS -->
		<script src="assets/vendor/chartist/js/chartist.min.js"></script>
		<script src="assets/vendor/chartist/js/chartist-tooltip.js"></script>
		<script src="assets/vendor/chartist/js/custom/threshold/threshold.js"></script>
		<script src="assets/vendor/chartist/js/custom/bar/bar-chart-orders.js"></script>

		<!-- jVector Maps -->
		<script src="assets/vendor/jvectormap/jquery-jvectormap-2.0.3.min.js"></script>
		<script src="assets/vendor/jvectormap/world-mill-en.js"></script>
		<script src="assets/vendor/jvectormap/gdp-data.js"></script>
		<script src="assets/vendor/jvectormap/custom/world-map-markers2.js"></script>

		<!-- Rating JS -->
		<script src="assets/vendor/rating/raty.js"></script>	
		<script src="assets/vendor/rating/raty-custom.js"></script>

	

		<!-- Main JS -->
		<script src="assets/js/main.js"></script>
		<script src="assets/js/ajax.js"></script>
		<script src="assets/js/backend.js"></script>
	
	</body>
</html>