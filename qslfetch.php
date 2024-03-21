<?php
//qslfetch.php
// NEW
/*
Copyright © 2024 NA7KR Kevin Roberts. All rights reserved.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/
?>

<?php
session_start();

include_once '../config.php';
include_once 'backend/db.class.php';
include_once ("backend/querybuilder.php");

// Check if form has been submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['call'])) {
    $_SESSION['form_submitted'] = true;
} else {
    $_SESSION['form_submitted'] = false;
}

// Redirect to received.php if form has been submitted and page is refreshed
if ($_SESSION['form_submitted'] && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == $_SERVER['REQUEST_URI']) {
    header("Location: home.php");
    exit();
}

// Redirect to received.php if accessing qslfetch.php directly
if (!isset($_SERVER['HTTP_REFERER'])) {
    header("Location: home.php");
    exit();
}

// Clear the form submitted flag
$_SESSION['form_submitted'] = false;
$page_title = "Home - Your Website";
$current_page = basename(__FILE__);

// Include page header
include_once 'backend/header.php';
?>

<body>

<main class="main">
    <div class="container">
        <?php
        try {
            if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['call'])) {
                $call = htmlspecialchars($_POST["call"]);
                if (!preg_match('/^[A-Za-z0-9\/]+$/', $call)) {
                    throw new Exception("Invalid callsign format provided, please use a standard amateur callsign such as W1AW, W1AW/P, W1AW/M, W1AW/8, VE3/W1AW, etc.");
                }
                $query = getSelect_Fetch();
                $params = ['call' => $call];
                $results = $db->select($query, $params);
                if (!empty($results)) {
                    foreach ($results as $row) {
                        //echo htmlspecialchars($row['COL_CALL']) . " <br>";
                    }
                } else {
                    echo "No records found.";
                    echo "<br>" . $sql . "<br>" . $call . "<br>";
                }
            } else {
                echo "<p class=\"lead\">No callsign provided, please enter a callsign.</p>";
                goto end;
            }
        } catch (Exception $e) {
            echo "<p class=\"lead\">Error: " . $e->getMessage() . "</p>";
            goto end;
        }
        ?>
        <p class="lead">Hello, <?php echo htmlspecialchars($call); ?>! Thank you for working <?php echo htmlspecialchars($station_name) ; ?></p>
        <p>You have the following QSOs available for QSL download:</p>
        <form action="qslprint.php" method="POST" name="qsofetch" id="qsofetch" onsubmit="return validateQsoFetchForm()">
            <input type="hidden" name="call" value="<?php echo htmlspecialchars($call); ?>" />
            <input type="hidden" name="maxqso" value="<?php echo htmlspecialchars($qsl_num_qso_rows); ?>" />
            <table width="80%" class="table table-striped table-hover">
                <thead>
                <tr>
                    <th id="thle">Print</th>
                    <th>Callsign</th>
                    <th>Timestamp</th>
                    <th>Band/Freq</th>
                    <th>RST</th>
                    <th>Mode</th>
                </tr>
                </thead>
                <?php
                foreach  ($results as $row) {
                    echo "<tr>\n";
                    echo "<td><input type=\"checkbox\" name=\"qq[]\" value=\"" . htmlspecialchars($row[$db_COL_PRIMARY_KEY]) . "\"></td>";
                    echo "<td>" . htmlspecialchars($row[$db_COL_CALL]) . "</td>";
                    echo "<td>" . htmlspecialchars($row['formatted_time']) . "Z</td>\n";
                    if (strlen($row[$db_COL_FREQ]) > 0) {
                        echo "<td>" . htmlspecialchars(sprintf("%.3f", $row[$db_COL_FREQ] / 1000000)) . "</td>\n";
                    } else {
                        echo "<td>" . htmlspecialchars($row[$db_COL_FREQ]) . "</td>\n";
                    }
                    if (is_int(strlen($row[$db_COL_RST_RCVD]))) {
                        echo "<td>" . htmlspecialchars($row[$db_COL_RST_RCVD]) . "</td>\n";
                    } else {
                        if (strcasecmp($row[$db_COL_MODE], "CW") === 0) {
                            echo "<td>599</td>\n";
                        } else {
                            echo "<td>59</td>\n";
                        }
                    }
                    echo "<td>" . htmlspecialchars($row[$db_COL_MODE]) . "</td>\n";
                }
                echo "</table><br>\n";
                ?>
                <p>Click the checkbox next to each QSO you want to print on the QSL Card. You may select up to <?php echo htmlspecialchars($qsl_num_qso_rows); ?> QSOs per Card</p>
                <div style="text-align: center;">
                    <button type="submit" class="btn btn-secondary">Retrieve</button>
                </div>
        </form>
        <?php end: ?>
    </div>
</main>
<?php
include ('backend/footer.php');
?>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
