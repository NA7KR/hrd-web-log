<?php
//qslprintpost.php
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

// Generate and save the QSL card image
$imgfile = "cards-out/$call.jpg"; // Define the filename for the QSL card image
$image->writeImages($imgfile, true); // Write the QSL card image to the file system


$page_title = "Home - Your Website";
$current_page = basename(__FILE__);

// Include page header
include_once 'backend/header.php';
?>
    <main class="main">
        <div class="container">
            <!-- Display QSL Card Image Section -->
            <div class="row">
                <div class="col-12">
                    <center>
                        <!-- Display the generated QSL card image -->
                        <img class="img-fluid qsl-img" src="<?php echo $imgfile; ?>" alt="QSL Card Image">
                    </center>
                </div>
            </div>
            <!-- Download Image Button Section -->
            <div class="row p-3">
                <div class="col-12">
                    <center>
                        <!-- Button to download the generated QSL card image -->
                        <a class="btn btn-secondary" role="button" href="<?php echo $imgfile; ?>" download>Download Image</a>
                    </center>
                </div>
            </div>
        </div>
    </main>

    <?php
        include ('backend/footer.php');
    ?>

    <!-- Include Bootstrap and custom JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/smoothqsl.js"></script>
</body>
</html>
