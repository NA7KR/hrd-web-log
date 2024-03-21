<?php
// backend/header.php
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
require_once("../config.php");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <!-- Bootstrap JS (optional) -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/smoothqsl.js"></script>
 
    <?php
         include 'backend/back.php';
    $java = isset($java) ? $java : false;

    if ($java) {
        include 'backend/java.php';
    }
    ?>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="#"><?php echo $Site_Name;?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <!-- Navigation links -->
        <?php foreach ($navigation_links as $link => $label): ?>
            <li class="nav-item"><a class="nav-link <?php echo ($current_page == $link) ? 'active' : ''; ?>" href="<?php echo $link; ?>"><?php echo $label; ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</nav>
