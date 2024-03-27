<?php
// select_countrys.php
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
// Initialize variables


$title = "About";

include_once("../config.php");
require_once('backend/db.class.php');
x_once("backend/backend.php");
require_once("backend/querybuilder.php");
include('backend/header.php');

// Create a new instance of the Db class


?>	
    <div class="container">
        <div class="row">
            <div class="col-12 my-2">
                <center>
                    <!-- Display station name and callsign -->
                    <h3><?php echo $station_name; ?> - <?php echo $callsign; ?></h3>
                </center>
            </div>
        </div>
        <div class="row justify-content-around">
            <div class="col-5">
                <!-- Welcome message -->
                <p>Welcome to the <?php echo $station_name; ?></p>
                <br>
                <p>This About page is not done please check back soon</p>

                <?php include 'backend/footer.php'; ?>
            </div>
        </div>
    </div>
</main>
</body>
</html>
