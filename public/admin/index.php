<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>

<?php include_layout_template('admin_header.php'); ?>

<div class="dropdown">
<button type="button" data-toggle="dropdown" id="admin-menu" aria-haspopup="true" aria-expanded="true" class="btn btn-success dropdown-toggle"><span class="glyphicon glyphicon-menu-hamburger"></span>Menu<span class="caret"></span></button>
	<ul class="dropdown-menu" aria-labelledby="admin-menu">
		<li><a href="list_photos.php">List Photos</a></li>
        <li><a href="logfile.php">View Log file</a></li>
		<li><a href="logout.php">Logout</a></li>
	</ul>
</div>

<?php include_layout_template('admin_footer.php'); ?>
		
