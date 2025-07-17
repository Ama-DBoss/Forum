<?php
require_once 'includes/db.con.php'; // Include your database connection
include 'includes/functions.php';
//include ('server.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
if (isset($_POST['action'])) {
    $action = $_POST['action'];
        $sn = 1;
		if(isset($_SESSION['username'])){
			$sun = $_SESSION['username'];
		}else{
			$sun = '';
		}
		global $sun;
        echo '';
        $mypquery = "SELECT * FROM rss_feeds WHERE title= '$action' ";
        $results = mysqli_query($DBconn, $mypquery);

        if (mysqli_num_rows($results) > 0) {
            while ($row = mysqli_fetch_assoc($results)) {
                $images = json_decode($row['files'], true);
                $image = htmlspecialchars($images[0] ?? 'no-image.jpg');
								$dcn = $row['category_name'] ;
								$dct = $row['title'] ;
								$dci = $row['id'] ;
								if(empty($row['files'])){
									$pia = 'images/forestbridge.jpg';
								}else{
									$pia = $row['files'];
								}
        echo '
				<strong><u><h3 class="w3-wide w3-center">' . htmlspecialchars(strtoupper($row['title'])) .'</h3></u></strong>
				<label><b>Published: </b>' . htmlspecialchars($row['pub_date']) .'</label><br>
				<label><b>Source / Credit: </b>' . htmlspecialchars($row['link']) .'</label><br><br>
				<img src="' . htmlspecialchars($pia) .'" alt="Avatar" class="w3-left w3-margin-right" style="width:100%"><br><br><br><br>
				<p>' . htmlspecialchars($row['content']) .'</p>
				<p><a href="' . htmlspecialchars($row['link']) .'" target="_blank" class="w3-text-blue">Read More</a></p>
				<div>
					<fieldset>
						<legend style="font-size:20px; padding:10px 10px;">Comments Section</legend>
						<form action="" method="POST">
						<input type="hidden" name="mforumid" value="'. $dci .'">
						<input type="hidden" name="mforumcat" value="'. $dcn .'">
						<input type="hidden" name="mforumtopic" value="'. $dct .'">
						<textarea name="subcomtxt" style="width:100%" placeholder="Write your comments here..."></textarea><br><br>
						<input type="submit" name="subcom" value="Comment">
						</form>';
						
        $cquery = "SELECT * FROM forum_comm WHERE fcom_topic = '$action' ";
        $results = mysqli_query($DBconn, $cquery);

        if (mysqli_num_rows($results) > 0) { ?>
    <hr style="border: 1px solid">
            <?php while ($row = mysqli_fetch_assoc($results)) {
                echo '
		<p><img src="'. htmlspecialchars($row['fcom_pp']) .'" alt="User Image" style="width:50px; height:50px;">
<strong>'. htmlspecialchars(strtoupper($row['fcom_user'])) .':</strong> '. htmlspecialchars($row['fcom_com']) .'<br>
'. htmlspecialchars($row['fcom_date']) .'</p>';
			}
		}
		?>
								</fieldset>
							</div>
				<?php
            }
        } else {
            echo '<p class="w3-center w3-text-red">We\'re Sorry!!!<br>Topic Could Not Be Found.</p>';
        }
    }
}
?>
