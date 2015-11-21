<?php require_once("../includes/initialize.php"); ?>
<?php

	// 1. the current page number ($current_page)
	$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;

	// 2. records per page ($per_page)
	$per_page = 4;

	// 3. total record count ($total_count)
	$total_count = Photograph::count_all();
	

	// Find all photos
	// use pagination instead
	//$photos = Photograph::find_all();
	
	$pagination = new Pagination($page, $per_page, $total_count);
	
	// Instead of finding all records, just find the records 
	// for this page
	$sql = "SELECT * FROM photographs ";
	$sql .= "LIMIT {$per_page} ";
	$sql .= "OFFSET {$pagination->offset()}";
	$photos = Photograph::find_by_sql($sql);
	
	// Need to add ?page=$page to all links we want to 
	// maintain the current page (or store $page in $session)
	
?>

<?php include_layout_template('header.php'); ?>
<div class="content container">
    <div class="row">
        <section class="col-sm-12">

<div class="jumbotron">
    
    
<h1>Welcome to Iris's photo gallery!<small> Proudly presented by Iris's husband.</small></h1><br/>
<p class="lead">Iris is my dear <span class="glyphicon glyphicon-heart"></span><strong>wife</strong>, I love her with my whole heart. We went to the same high school, unfortunately we missed each other. But the truth is we are born to be a family, so we reunited in Washington.DC, then fell in love and married.</p>

<button type="button" class="btn btn-info" data-toggle="modal" data-target="#policy">More details</button>
    
<div class="modal" id="policy" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="modalLabel">More About Us</h3>
            </div>
            <div class="modal-body">
    
<p class="lead">Like most of the beautiful girls, my wife also like taking pictures, so I built this website to present them, to share with people who care about us.</p><br/>
<p class="lead">Most of these pictures are taken when we went to national parks or happy land, we are kind of outdoor people. Both of us take regular exercise to build the body and our favorite food is meat.</p>
            </div>
        </div>
    </div>
</div>
    
</div>
        </section>

<?php foreach($photos as $photo): ?>
        <section class="col-sm-3">
  <div style="float: left; margin-left: 20px;">
		<a href="photo.php?id=<?php echo $photo->id; ?>">
			<img src="<?php echo $photo->image_path(); ?>" class="img-thumbnail img-responsive" width="200" /><br />
		</a>
    <h3><?php echo $photo->caption; ?></h3>
  </div>
            </section>
<?php endforeach; ?>
    </div>

<div id="pagination" style="clear: both;">
<?php
	if($pagination->total_pages() > 1) {
		
		if($pagination->has_previous_page()) { 
    	echo "<a class=\"btn btn-danger\"href=\"index.php?page=";
      echo $pagination->previous_page();
      echo "\"><span class=\"glyphicon glyphicon-chevron-left\"></span> Previous</a> "; 
    }

		for($i=1; $i <= $pagination->total_pages(); $i++) {
			if($i == $page) {
				echo " <span class=\"btn btn-success btn-lg\" class=\"selected\">{$i}</span> ";
			} else {
				echo " <a class=\"btn btn-info\" href=\"index.php?page={$i}\">{$i}</a> "; 
			}
		}

		if($pagination->has_next_page()) { 
			echo " <a class=\"btn btn-primary\" href=\"index.php?page=";
			echo $pagination->next_page();
			echo "\">Next <span class=\"glyphicon glyphicon-chevron-right\"></span></a> "; 
    }
		
	}

?>
</div>
</div>


<?php include_layout_template('footer.php'); ?>
