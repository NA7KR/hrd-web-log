<?php
// Define the base URL of the website to be scanned
$baseURL = "https://test.na7kr.us";

// Directories containing your images (exclude thumbnails)
$imageDirectories = ['cards/', 'Awards/'];

// Function to fetch all links from a given URL
function fetchLinks($url) {
    global $baseURL; // Access the $baseURL variable within the function
    $html = @file_get_contents($url); // Get the HTML content of the page

    if ($html === false) {
        // Handle the case where fetching content fails
        error_log("Failed to fetch content from URL: $url");
        return array();
    }

    $links = array();

    // Use regular expression to find all <a> tags and extract the href attribute
    preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $html, $matches);

    // Iterate over the matched URLs and add them to the links array
    foreach ($matches['href'] as $link) {
        $links[] = $link;
    }

    return $links;
}

// Function to fetch all images from given directories (excluding thumbnails)
function fetchImages($directories) {
    global $baseURL; // Access the $baseURL variable within the function
    $images = array();

    foreach ($directories as $directory) {
        // Check if the directory exists
        if (is_dir($directory)) {
            // Open the directory
            if ($dh = opendir($directory)) {
                // Iterate over each file in the directory
                while (($file = readdir($dh)) !== false) {
                    // Ensure the file is a regular file and has a valid image extension
                    if (is_file($directory . $file) && preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
                        // Exclude thumbnails (assuming thumbnails contain "thumb" in their name)
                        if (strpos($file, 'thumb') === false) {
                            // Construct the image URL and add it to the images array
                            $images[] = $baseURL . '/' . $directory . $file;
                        }
                    }
                }
                // Close the directory handle
                closedir($dh);
            } else {
                error_log("Failed to open directory: $directory");
            }
        } else {
            error_log("Directory does not exist: $directory");
        }
    }

    return $images;
}

// Function to generate sitemap XML
function generateSitemapXML($links, $images) {
    global $baseURL; // Access the $baseURL variable within the function

    // Start building the XML string
    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

    // Add URLs from links
    foreach ($links as $link) {
        $xml .= '<url>';
        $xml .= '<loc>' . htmlspecialchars($baseURL . $link) . '</loc>';
        $xml .= '<lastmod>' . date("Y-m-d") . '</lastmod>';
        $xml .= '<changefreq>weekly</changefreq>';
        $xml .= '<priority>0.8</priority>';
        $xml .= '</url>';
    }

    // Add image URLs
    foreach ($images as $image) {
        $xml .= '<url>';
        $xml .= '<loc>' . htmlspecialchars($image) . '</loc>';
        $xml .= '<lastmod>' . date("Y-m-d") . '</lastmod>';
        $xml .= '<changefreq>monthly</changefreq>';
        $xml .= '<priority>0.6</priority>';
        $xml .= '<image:image>';
        $xml .= '<image:loc>' . htmlspecialchars($image) . '</image:loc>';
        $xml .= '</image:image>';
        $xml .= '</url>';
    }

    // End the XML string
    $xml .= '</urlset>';

    return $xml;
}

// Start crawling the website from the base URL
$links = fetchLinks($baseURL);
$images = fetchImages($imageDirectories);
$sitemapXML = generateSitemapXML($links, $images);

// Output the sitemap XML
header('Content-Type: application/xml');
echo $sitemapXML;
?>
