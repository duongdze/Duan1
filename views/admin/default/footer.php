
		</div> <!-- /#page-content-wrapper -->
	</div> <!-- /#wrapper -->

	<script>
	// Toggle sidebar
	document.addEventListener('DOMContentLoaded', function(){
		var toggle = document.getElementById('menu-toggle');
		if (toggle) {
			toggle.addEventListener('click', function(e){
				e.preventDefault();
				var sidebar = document.getElementById('sidebar-wrapper');
				if (sidebar.style.display === 'none') {
					sidebar.style.display = '';
				} else {
					sidebar.style.display = 'none';
				}
			});
		}
	});
	</script>

</body>
</html>