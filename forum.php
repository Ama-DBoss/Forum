<?php
require_once 'includes/db.con.php'; // Include your database connection
include_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Forum</title>
<meta name="description" content="Best resource for recent updates around the world">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<link rel="stylesheet" href="css/mycss.css">
<link rel="stylesheet" href="css/w3-theme-blue-grey.css">
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<script src="js/new_myjs.js" defer></script>
<style>
html, body, h1, h2, h3, h4, h5 {font-family: "Open Sans", sans-serif}

    .button-container button {
        margin: 10px 5px; /* Add spacing between buttons */
    }
</style>
</head>
<body class="w3-theme-l5">
    
    <!-- Navigation Bar -->
		<?php include_once "includes/pubnav.php"; ?>

<div class="w3-display-container w3-content" style="max-width:1600px;margin-top:10px"><br>
    <div class="w3-row">
        <div class="w3-col m12">
            <div class="w3-container" style="padding:10px 10px">
                <h2 class=" w3-center">FORUMS<br><label class=" w3-text-red">Click a forum below to join and view activities.</label></h2>
                <hr style="border: 1px solid">
                
<!-- Generate buttons dynamically with spacing -->
<div class="button-container w3-center">
    <div class="button-grid">
        <?php
        $fcategories = [
            'cg' => 'CELEBRITY GOSSIPS',
            'lovedm' => 'LOVE, DATING & MARRIAGE',
            'shb' => 'SPORTS HIGHLIGHTS & BETTINGS',
            'cf' => 'CRYPTO & FOREX',
            're' => 'REAL ESTATE',
            'ets' => 'ENTERPRENEURSHIP',
            'tg' => 'TECH GADGETS',
            'fs' => 'BUSINESS, FINANCE & STOCKS',
            'mmcg' => 'ENTERTAINMENT',
            'atm' => 'AUTOMOBILES',
            'hde' => 'HEALTH, DIET & EXERCISE',
            'bsf' => 'BEAUTY, SKINCARE & FRAGRANCE',
            'edu' => 'EDUCATIONAL',
            'ttv' => 'TRAVEL, TOURISM & VACATIONS',
            'wng' => 'WILDLIFE & NATIONAL GEOGRAPHIC',
            'sf' => 'SCIENCE & FICTIONS',
            'fash' => 'FASHION',
            'pol' => 'POLITICS',
            'rel' => 'RELIGION',
            'ls' => 'LIFESTYLE',
            'gen' => 'GENERAL',
            'wr' => 'WORLD RECORDS',
        ];

        $colors = [
            'w3-red', 'w3-blue', 'w3-light-green', 'w3-yellow', 'w3-orange', 
            'w3-teal', 'w3-indigo', 'w3-lime', 'w3-purple', 'w3-pink', 
            'w3-cyan', 'w3-brown', 'w3-amber', 'w3-light-blue', 'w3-green', 
            'w3-dark-grey', 'w3-deep-orange', 'w3-black', 'w3-aqua', 'w3-khaki', 'w3-deep-orange', 'w3-grey'
        ];

        $colors2 = [
            'w3-text-red', 'w3-text-blue', 'w3-text-light-green', 'w3-text-yellow', 'w3-text-orange', 
            'w3-text-teal', 'w3-text-indigo', 'w3-text-lime', 'w3-text-purple', 'w3-text-pink', 
            'w3-text-cyan', 'w3-text-brown', 'w3-text-amber', 'w3-text-light-blue', 'w3-text-green', 
            'w3-text-dark-grey', 'w3-text-deep-orange', 'w3-text-black', 'w3-text-aqua', 'w3-text-khaki', 'w3-text-deep-orange', 'w3-text-grey'
        ];

        $index = 0;
        foreach ($fcategories as $key => $label) {
            // Cycle through the colors array
            $color = $colors[$index % count($colors)];
            $color2 = $colors2[$index % count($colors2)];
            echo "
            <form action='' method='POST' class='button-form'>
                <input type='hidden' name='catname' value='$label'>
                <input type='hidden' name='buttonColor' value='$color2'>
                <button type='submit' name='cnbtn' 
                    class='w3-button w3-round-large w3-ripple w3-medium $color' 
                    id='$key' 
                    onclick='handleAction(\"$key\")'>
                    $label
                </button>
            </form>";
            $index++;
        }
        ?>
    </div>
</div>
<style>
.button-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center; /* Center the buttons horizontally */
    gap: 15px; /* Add spacing between buttons */
}

.button-form {
    margin: 0; /* Reset margins for forms */
}

.w3-button {
    min-width: 150px; /* Set a minimum width for consistent button sizes */
    text-align: center;
    padding: 10px 15px;
}
</style>
                <hr style="border: 1px solid">
<div id="vendorOutput">
<?php
// Define the number of items per page
$pageLimit = 20;

// Check if a category button was clicked
if (isset($_POST['cnbtn'])) {
    // Escape input to prevent SQL injection
    $catname = mysqli_real_escape_string($DBconn, $_POST['catname']);
    $buttonColor = mysqli_real_escape_string($DBconn, $_POST['buttonColor']);

    // Determine the current page
    $currentPage = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    if ($currentPage < 1) {
        $currentPage = 1;
    }

    // Calculate the offset for pagination
    $offset = ($currentPage - 1) * $pageLimit;

    // Query to fetch RSS feeds with pagination
    $cnquery = "SELECT * FROM rss_feeds WHERE category_name = '$catname' ORDER BY pub_date DESC LIMIT $offset, $pageLimit";
    $cnResult = $DBconn->query($cnquery);

    // Query to count total records for pagination
    $countQuery = "SELECT COUNT(*) AS total FROM rss_feeds WHERE category_name = '$catname'";
    $countResult = $DBconn->query($countQuery);
    $totalRecords = $countResult->fetch_assoc()['total'];
    $totalPages = ceil($totalRecords / $pageLimit);

    // Initialize variables for output
    $dcn = $catname;
    $topics = [];

    if ($cnResult && $cnResult->num_rows > 0) {
        while ($cnRow = mysqli_fetch_assoc($cnResult)) {
            $topics[] = [
                'id' => $cnRow['id'],
                'title' => $cnRow['title']
            ];
        }
    }
} else {
    $dcn = "";
    $topics = [];
    $buttonColor = "";
    $totalPages = 1;
    $currentPage = 1;
}
?>


    <!-- Display the output -->
    <div class="w3-row" id="tdm">
        <div id="dcn" class="w3-wide w3-center">
            <strong><h3 class="w3-wide <?php echo htmlspecialchars($buttonColor) ; ?>"><?php echo htmlspecialchars($dcn); ?></h3></strong>
        </div>

<div id="" class="w3-col s12 m3 l3" style="padding:10px 10px">
    <fieldset>
        <legend><b><u>TOPICS</u></b></legend>
        <div id="feeds">
            <?php if (!empty($topics)): ?>
                <?php foreach ($topics as $topic): ?>
                    <form id="topicForm" method="POST" action="">
                        <p class=" <?php echo htmlspecialchars($buttonColor) ; ?>"
                            onclick="handleAction('<?php echo htmlspecialchars($topic['title']); ?>')" 
                            style="cursor:pointer" 
                            type="button" 
                            name="myp" 
                            id="myp">
                            <i class="fa fa-arrow-right w3-text-amber"></i> 
                            <?php echo htmlspecialchars($topic['title']); ?>
                        </p>
                    </form>
                <?php endforeach; ?>

                <!-- Pagination Links -->
                <div class="pagination">
                    <?php if ($currentPage > 1): ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="cnbtn" value="1">
                            <input type="hidden" name="catname" value="<?php echo htmlspecialchars($catname); ?>">
                            <input type="hidden" name="buttonColor" value="<?php echo htmlspecialchars($buttonColor); ?>">
                            <input type="hidden" name="page" value="<?php echo $currentPage - 1; ?>">
                            <button type="submit">Previous</button>
                        </form>
                    <?php endif; ?>

                    <span>Page <?php echo $currentPage; ?> of <?php echo $totalPages; ?></span>

                    <?php if ($currentPage < $totalPages): ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="cnbtn" value="1">
                            <input type="hidden" name="catname" value="<?php echo htmlspecialchars($catname); ?>">
                            <input type="hidden" name="buttonColor" value="<?php echo htmlspecialchars($buttonColor); ?>">
                            <input type="hidden" name="page" value="<?php echo $currentPage + 1; ?>">
                            <button type="submit">Next</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <p>No topics found.</p>
            <?php endif; ?>
        </div>
    </fieldset>
</div>
<script>
    // Handle form actions dynamically
    function handleAction(action) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'ftopic_gen.php', true); // Point to a PHP script that handles actions
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById('feed-details').innerHTML = xhr.responseText;
            }
        };
        xhr.send(`action=${action}`);
    }
	</script>
                    <div id="" class="w3-col s12 m7 l9" style="padding:10px 10px">
                        <fieldset>
                            <legend><b><u>DISCUSSION</u></b></legend>
                            <div id="feed-details"></div>
							<?php
$subcomtxtErr = "";
$subcomtxt = "";
$errors = array(); 

// connect to the database
$DBconn = mysqli_connect('localhost', 'root', '', 'forum');

if (isset($_POST['subcom'])) {
    // Receive all input values from the form
    $ctxt = $_POST['subcomtxt'];
    $cid = $_POST['mforumid'];
    $ccat = $_POST['mforumcat'];
    $ctopic = $_POST['mforumtopic'];
    $cd = date('Y-m-d');
    $ccu = 'Anonymous';
    $image = "images/avatar2.png"; // Default image if not logged in

    // Validate input (ensure no errors)
    if (empty($ctxt)) {
        $errors[] = "Comment cannot be empty.";
    }

    if (count($errors) == 0) {
        // Insert the new comment into the database using prepared statements
        $query = "INSERT INTO forum_comm (fcom_user, fcom_pp, fcom_cat, fcom_topic, fcom_com, fcom_id, fcom_date) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($DBconn, $query)) {
            mysqli_stmt_bind_param($stmt, 'sssssss', $ccu, $image, $ccat, $ctopic, $ctxt, $cid, $cd);
            if (mysqli_stmt_execute($stmt)) {
                // Successfully inserted the comment
                // Optionally redirect to a different page
                //header('Location: forum.php');
            } else {
                echo '<p class="w3-text-red">Error: ' . htmlspecialchars(mysqli_stmt_error($stmt)) . '</p>';
            }
            mysqli_stmt_close($stmt);
        } else {
            echo '<p class="w3-text-red">Error: ' . htmlspecialchars(mysqli_error($DBconn)) . '</p>';
        }
    }
}

// Handle and display errors
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo '<div class="error">' . htmlspecialchars($error) . '</div>';
    }
}
?>

                        </fieldset>
                    </div>
                    </div><br>
            </div>
        </div>
    </div>
</div>
</div>

<button onclick="topFunction()" id="myBtn" title="Go to top" class="fa fa-arrow-up" style="border-radius: 50%"></button>

<?php include_once 'includes/footer.php'; ?>
<script src="js/script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
        $(document).ready(function () {
            // Fetch the RSS content dynamically
            $.ajax({
                url: 'get_rss.php', // Path to your PHP script
                type: 'GET',
                beforeSend: function () {
                    // Optional: Show a loading indicator
                    $('#rss-content').html('<p>Loading RSS feed...</p>');
                },
                success: function (data) {
                    // Insert the fetched RSS content into the div
                    $('#rss-content').html(data);
                },
                error: function (xhr, status, error) {
                    // Handle errors gracefully
                    console.error('Error loading RSS feed:', status, error);
                    $('#rss-content').html('<p>Failed to load RSS feed. Please try again later.</p>');
                }
            });
        });
    </script>
</body>
</html>
