<?php
// backend.php
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
// Including necessary files
require_once('../config.php'); // Include configuration file (assuming it sets constants like $dbnameWEB and $tbSelect)
$db = new Db();
// Function to generate option list HTML
function OptionList($key1, $key2, $key3, $key4, $key5, $key6) {
    $data = "";
    if ($key1 == true or $key2 == true or $key3 == true or $key4 == true or $key5 == true) {
        $data = "<br>" . PHP_EOL;
    }
    if ($key1 == true) {
        $data .= '<span> <input type="radio" name="optionlist" value="input_band" id="input_band">Band</span>' . PHP_EOL;
    } else {
        $data .= '<span id="input_band"></span>' . PHP_EOL;
        $data .='<span id="Band"></span>' . PHP_EOL;
    }
    // key2
    if ($key2 == true) {
        $data .= '<span> <input type="radio" name="optionlist" value="input_mode" id="input_mode">Mode</span>' . PHP_EOL;
    } else {
        $data .= '<span id="input_mode"></span>' . PHP_EOL;
        $data .='<span id="Mode"></span>' . PHP_EOL;
    }
    // key3
    if ($key3 == true) {
        $data .= '<span> <input type="radio" name="optionlist" value="input_search" id="input_search">Call</span>' . PHP_EOL;
    } else {
        $data .= '<span id="input_search"></span>' . PHP_EOL;
        $data .='<span id="Call"></span>' . PHP_EOL;
    }
    // key 4
    if ($key4 == true) {
        $data .= '<span> <input type="radio" name="optionlist" value="input_state" id="input_state">State</span>' . PHP_EOL;
    } else {
        $data .= '<span id="input_state"></span>' . PHP_EOL;
        $data .='<span id="State"></span>' . PHP_EOL;
    }
    // key 5
    if ($key5 == true) {
        $data .= '<span> <input type="radio" name="optionlist" value="input_country" id="input_country">Country</span>' . PHP_EOL;
    } else {
        $data .='<span id="input_country"></span>' . PHP_EOL;
        $data .='<span id="Country"></span>' . PHP_EOL;
    }
    // key6
    if ($key6 == true) {
        $data .= '<span> <input type="radio" name="optionlist" value="input_none" id="input_none"checked>None</span>' . PHP_EOL;
    } else {
        $data .='<span id="input_none"></span>' . PHP_EOL;
    }
    $data .='<br>' . PHP_EOL;
    Return $data;
}

// Function to generate select form HTML
function generateSelectForm() {
    $data = '<form name="form1" method="post" action="received.php">' . PHP_EOL; // Start form HTML
    $data .= '<table class="center">' . PHP_EOL; // Start table HTML

    $db = new Db();
    // Create a new instance of Db class
    
    // Define the SQL query to fetch options
    $query = getSelect_Backend_Name();

    // Execute the query using the select function
    try {
        $results = $db->select($query);
    } catch (Exception $e) {
        echo "Query: " . $query ."<br>";
        echo "Error executing query: " . $e->getMessage();
    }

    // Check if $options is an array
    if (is_array($results)) {
        // Loop through options and add radio buttons to the form
        foreach ($results as $row) {
            $checked = isset($row['Select_Name']) && $row['Select_Name'] == "callsign_lookup" ? ' checked' : ''; // Check if the option is selected
            $data .= "<tr><td align='left'><input type='radio' value='" . (isset($row['Select_Name']) ? $row['Select_Name'] : '') . "' name='Log'" . $checked . "> " . (isset($row['Select_TXT']) ? $row['Select_TXT'] : '') . "<br></td></tr>" . PHP_EOL;
        }
    } else {
        // Handle the case when $options is not an array (possibly an error)
        $data .= "<tr><td>Error: Options not found</td></tr>" . PHP_EOL;
    }

    // Close table and add submit button to the form
    $data .= "</table><br><div style='text-align: center'><input type='submit' name='Submit1' value='Submit'></div><br>" . PHP_EOL;
    $data .= '<input type="hidden" name="1st" value="true">' . PHP_EOL; // Add hidden input field

    return $data; // Return complete form HTML
}





/**
 * Retrieves and formats the band options for the HTML form
 *
 * @return string HTML code for band options
 */
function band() {
    $db = new Db();
    // SQL query to retrieve distinct bands from the database
    $query = getSelect_Backend_Band();
    
    // Fetch band options from the database
    try {
        $results = $db->select($query);
    } catch (Exception $e) {
        echo "Query: " . $query ."<br>";
        echo "Error executing query: " . $e->getMessage();
    }
    $data = '<span id="Band">' . PHP_EOL;
    
    // Iterate over each band option
    foreach ($results as $row) {
        // Check if the band value is not empty
        if (!empty($row[DB_COL_BAND])) {
            // Check if the band is 15m and set the radio button as checked
            if ($row[DB_COL_BAND] == "15m") {
                $data .= '<input type="radio" value="' . $row[DB_COL_BAND] . '" checked name="Band" >' . $row[DB_COL_BAND] . PHP_EOL;
            } else {
                // Render radio button for other bands
                $data .= '<input type="radio" value="' . $row[DB_COL_BAND] . '" name="Band" >' . $row[DB_COL_BAND] . PHP_EOL;
            }
        }
    }
    
    // Close the span tag
    $data .= '</span>' . PHP_EOL;
   
    // Return the formatted HTML code for band options
    return $data;
}



/**
 * Retrieves and formats the mode options for the HTML form
 *
 * @return string HTML code for mode options
 */
function mode() {
    $db = new Db();
    // SQL query to retrieve distinct modes from the database
    $query = getSelect_Backend_Mode();
    
    // Fetch mode options from the database
    try {
        $results = $db->select($query);
    } catch (Exception $e) {
        echo "Query: " . $query ."<br>";
        echo "Error executing query: " . $e->getMessage();
    }
    $data = '<span id="Mode">' . PHP_EOL;
    // Iterate over each mode option
    foreach ($results as $row) {
        // Check if the mode is SSB and set the radio button as checked
        if ($row[DB_COL_MODE] == "SSB") {
            $data .= '<input type="radio" value=' . $row[DB_COL_MODE] . ' checked name="Mode"  >' . $row[DB_COL_MODE] . PHP_EOL;
        } else {
            // Render radio button for other modes
            $data .= '<input type="radio" value=' . $row[DB_COL_MODE] . ' name="Mode"  >' . $row[DB_COL_MODE] . PHP_EOL;
        }
    }
    // Close the span tag
    $data .= '</span>' . PHP_EOL;
   
    // Return the formatted HTML code for mode options
    return $data;
}



/**
 * Retrieves and formats the state options for the HTML form
 *
 * @return string HTML code for state options
 */
function OptionState() {
    require_once('../config.php');
    $db = new Db();
        // SQL query to retrieve distinct states from the database
        
        $query = getSelect_Backend_State();

        // Execute the query to fetch state options
        
        try {
            $results = $db->select($query);
        } catch (Exception $e) {
            echo "Query: " . $query ."<br>";
            echo "Error executing query: " . $e->getMessage();
        }
        $data = '<span id="State">' . PHP_EOL;
        $data .= '<select name="State">' . PHP_EOL;
        // Iterate over each state option
        foreach ($results as $row) {
            $Name = $row['State'];
            $Value = $row['State Worked'];
            // Append an option element for the state
            $data .= '<option value="' . $Value . '">' . $Name . '</option>' . PHP_EOL;
        }
        // Close the select element
        $data .= '</select>' . PHP_EOL;
        // Close the span tag
        $data .= '</span>' . PHP_EOL;
   
    // Return the formatted HTML code for state options
    return $data;
}


/**
 * Retrieves and formats the country options for the HTML form
 *
 * @return string HTML code for country options
 */
function OptionCountry() {
    try {
        $db = new Db();
        // Include necessary files and initialize the database connection
        
        $query = getSelect_Backend_Country();
        // Query to retrieve distinct countries from the database
        try {
            $results = $db->select($query);
        } catch (Exception $e) {
            echo "Query: " . $query ."<br>";
            echo "Error executing query: " . $e->getMessage();
        }
        
        // Initialize the HTML data
        $data = '<span id="Country">' . PHP_EOL;
        $data .= '<select name="Country">' . PHP_EOL;
        
        // Iterate over each country option
        foreach ($results as $row) {
            $Name = $row['Countrys Worked'];
            // Append an option element for the country
            $data .= '<option>' . $Name . '</option>' . PHP_EOL;
        }
        
        // Close the select element and the span tag
        $data .= '</select>' . PHP_EOL;
        $data .= '</span>' . PHP_EOL;
    } catch (Exception $e) {
        // If an error occurs while fetching options, display error message
        $data = "<tr><td>Error loading options: " . $e->getMessage() . "</td></tr>" . PHP_EOL;
    }
    // Return the formatted HTML code for country options
    return $data;
}

/**
 * Generates HTML select options for country
 *
 * @param int $i Index
 * @return string HTML select options for country
 */
function country($i) {
    // Initialize the result variable
    $result = "";
    // Check the length of the CountrysArray
    if (count($CountrysArray) >= 2) {
        // Initialize the select variable
        $select = "<BR><select id='mySelect' name='Country'>";
        // Iterate over each country in the CountrysArray
        foreach ($CountrysArray as $CountryArray) {
            // Check if user_input is set and assign selected attribute accordingly
            $selected = isset($user_input) && $user_input == $CountryArray['value'] ? ' selected="selected"' : '';
            // Append an option element for the country
            $select .= "<option value=\"$CountryArray\"$selected>$CountryArray</option>";
        }
    } else {
        // Iterate over each country in the CountrysArray and append it to the select variable
        foreach ($CountrysArray as $CountryArray) {
            $select .= $CountryArray;
        }
    }

    // Return the select variable
    return $select;
}
/**
 * Generates HTML select options for quantity
 *
 * @return string HTML select options for quantity
 */
function OptionQty() {
    // Initialize the data variable with the opening select tag and default option
    $data = '<select name="Qty">' . PHP_EOL;
    $data .= '<option>50</option>' . PHP_EOL;
    $data .= '<option>100</option>' . PHP_EOL;
    $data .= '<option>250</option>' . PHP_EOL;
    $data .= '<option>500</option>' . PHP_EOL;
    $data .= '<option>1000</option>' . PHP_EOL;
    $data .= '<option>All</option>' . PHP_EOL;
    // Close the select tag
    $data .= '</select><br>' . PHP_EOL;
    // Return the formatted HTML select options for quantity
    return $data;
}

function safe($value) {
    return $value;
}

#################################################
# Make Grid
# $i mod 2 is checking to see if the row number is odd or even odd rows are colored differently than even rows to create a datagrid look and feel
#################################################

function grid_style($i) {
    if (($i % 2) != 0) {
        $style = "<tr bgcolor='#5e5eff'>";
        return $style;
    } else {
        $style = "<tr bgcolor='#FFC600'>";
        return $style;
    }
}


function qrzcom_interface($callsign) {
    $lookup = "<a href='http://www.qrz.com/db/$callsign'>$callsign</a>";
    return ($lookup);
}

class Lookup {
    private $db;
    public $variables;
    public function __construct($data = array()) {
        $this->db = new DB();
        $this->variables = $data;
    }
    public function count($field, $table) {
        if ($field) {
            return $this->db->single("SELECT COUNT(" . $field . ")" . " FROM " . $table);
        }
    }
}


// Function to create directory if it doesn't exist
function makeDir($path)
{
    return is_dir($path) || mkdir($path, 0777, true);
}

// Function to process image
function image($iKey)
{
    global $db, $callsign, $EQSL;

    // Get SQL query and parameters
    $query = getSelect_qsl2();
    $params = array("ikey" => $iKey);

    try {
        // Execute SQL query
        $results = $db->select($query, $params);
    } catch (Exception $e) {
        // Handle query execution error
        echo "Error executing query: " . $e->getMessage();
        error_log($e->getMessage());
        return;
    }

    // Check if any results returned
    if (empty($results)) {
        echo "No results found for iKey: $iKey\n";
        return;
    }

    // Extract data from the first row of the result
    $firstRow = $results[0];

    // Extract individual values
    $Year = $firstRow["Year"];
    $Month = $firstRow["Month"];
    $Day = $firstRow["Day"];
    $Hour = $firstRow["Hour"];
    $Minute = $firstRow["Minute"];
    $Call = $firstRow["Call"];
    $Band = $firstRow["Band"];
    $Mode = $firstRow["Mode"];

    // Clean up Mode
    $Mode = str_replace(["USB", "LSB"], "SSB", $Mode);
    // Clean up Call
    $Call_R = str_replace("/", "-", $Call);

    $fileMultiply = 1000;
    $groupNumber = floor($iKey / $fileMultiply);
    $FileNoGroup = $groupNumber * $fileMultiply;
    $fileNoGroupHigh = ($groupNumber + 1) * $fileMultiply - 1;

    include("../config.php");
    $base = $base . 'cards/';
    $filePath = $base . $FileNoGroup . "-" . $fileNoGroupHigh;
    $pathToThumbs = $filePath . '/thumbs/';
    $Index = 'received.php';
    $HTaccess = '.htaccess';

    // Create necessary directories and symbolic links if they don't exist
    if (!file_exists($filePath)) {
        mkdir($filePath, 0777, true);
        createSymlink($base, $filePath, $Index, $HTaccess);
        mkdir($pathToThumbs, 0777, true);
        createSymlink($base, $pathToThumbs, $Index, $HTaccess);
    }

    $FileName = "$filePath/E-$iKey-$Call_R.jpg";

    // Check if image file exists
    if (!file_exists($FileName)) {
        $eqsl = "http://www.eqsl.cc/qslcard/GeteQSL.cfm?UserName=$callsign&Password=$EQSL&CallsignFrom=$Call&QSOBand=$Band&QSOMode=$Mode&QSOYear=$Year&QSOMonth=$Month&QSODay=$Day&QSOHour=$Hour&QSOMinute=$Minute";
        $str = @file_get_contents($eqsl);

        // Check if eQSL fetch was successful
        if ($str === false) {
            echo "Failed to fetch data from eQSL\n";
            return;
        }

        // Handle possible errors returned from eQSL
        $errors = [
            "Error: You must specify the QSO Date/Time as QSOYear, QSOMonth, QSODay, QSOHour, and QSOMinute",
            "Error: No match on Username/Password for that QSO Date/Time",
            "Error: (n) overlapping accounts for that QSO Date/Time. User needs to correct that immediately",
            "Error: I cannot find that log entry",
            "Error: That QSO has been Rejected by (username)"
        ];

        foreach ($errors as $error) {
            if (strpos($str, $error) !== false) {
                // Log the error and display message
                $file = '/opt/error.txt';
                $errormsg = @file_get_contents($file);
                $eqslp = str_replace("Password=$EQSL", "Password=PASSWORD", $eqsl);
                $errormsg .= $FileName . " Error: $error Connection: $eqslp\n";
                @file_put_contents($file, $errormsg);
                echo "$error\n";
                return;
            }
        }

        // Extract image URL from eQSL response and save image locally
        $pic = getTexts($str, '<img src=', ' alt="" />');
        $imageData = @file_get_contents("http://www.eqsl.cc/$pic");

        if ($imageData !== false) {
            @file_put_contents($FileName, $imageData);
            @chmod("$FileName", 0644);

            // Create thumbnail
            $thumbWidth = 100;
            $thumbFile = "{$pathToThumbs}E-$iKey-$Call_R.jpg";
            createThumbnail($FileName, $thumbFile, $thumbWidth);
        }
    } else {
        echo "The file $FileName exists\n";
    }
}

// Function to create thumbnail
function createThumbnail($sourceFile, $destFile, $thumbWidth)
{
    $img = @imagecreatefromjpeg($sourceFile);
    if (!$img) {
        echo "Failed to create image from file: $sourceFile\n";
        return;
    }

    $width = imagesx($img);
    $height = imagesy($img);
    $newHeight = floor($height * ($thumbWidth / $width));
    $tmpImg = imagecreatetruecolor($thumbWidth, $newHeight);
    imagecopyresized($tmpImg, $img, 0, 0, 0, 0, $thumbWidth, $newHeight, $width, $height);
    imagejpeg($tmpImg, $destFile);
    @chmod($destFile, 0644);
}

// Function to create symbolic link
function createSymlink($base, $targetDir, $index, $htaccess)
{
    symlink($base . $index, $targetDir . "/" . $index);
    symlink($base . $htaccess, $targetDir . "/" . $htaccess);
}

// Function to extract text between two strings
function getTexts($string, $start, $end)
{
    $text = "";
    $posStart = strrpos($string, $start);
    $posEnd = strrpos($string, $end, $posStart);
    
    if ($posStart > 0 && $posEnd > 0) {
        $posStart = $posStart + strlen($start) + 2;
        $posEnd--;
        $text = substr($string, $posStart, $posEnd - $posStart);
    }

    return $text;
}

?>
