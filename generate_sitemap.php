<?php
// genrare_sitemap.php
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
include '../config.php'; // Assuming this contains base configurations

header('Content-Type: application/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"';
echo ' xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

$domain = 'http://test.na7kr.us/'; // Replace with your actual domain

// Function to get the current date in the format required for lastmod tag
function getCurrentDate() {
    return date("Y-m-d");
}

// Loop through menu items for pages
foreach ($menuItems as $name => $url) {
    echo "<url>";
    echo "<loc>" . htmlspecialchars($domain . $url) . "</loc>";
    echo "<lastmod>" . getCurrentDate() . "</lastmod>";
    echo "<changefreq>weekly</changefreq>";
    echo "<priority>0.8</priority>";
    echo "</url>";
}

// Directory containing your images
$directory = '../qsl/cards/';

// PHP version greater than 5.2.4 can use RecursiveDirectoryIterator
// If not, replace with another method to fetch image list
$images = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

foreach ($images as $image) {
    // Ensure the file is an actual image
    if ($image->isFile() && preg_match('/\.(jpg|jpeg|png|gif)$/i', $image->getFilename())) {
        $imageUrl = $domain . 'images/' . $image->getFilename();
        echo "<url>";
        echo "<loc>" . htmlspecialchars($imageUrl) . "</loc>";
        echo "<lastmod>" . getCurrentDate() . "</lastmod>";
        echo "<changefreq>monthly</changefreq>";
        echo "<priority>0.6</priority>";
        echo "<image:image>";
        echo "<image:loc>" . htmlspecialchars($imageUrl) . "</image:loc>";
        echo "</image:image>";
        echo "</url>";
    }
}

echo '</urlset>';
?>
