<?php
    // Start session to track visitors
    session_start();

    // Step 1: Visitor Counter Logic
    if (!isset($_SESSION['has_visited'])) {
        $_SESSION['has_visited'] = true;
        $visitor_count = file_exists("visitor_counter.txt") ? intval(file_get_contents("visitor_counter.txt")) : 0;
        $visitor_count++;
        file_put_contents("visitor_counter.txt", $visitor_count);  // Update visitor counter
    }

    // Step 2: GMAP and Download Counters
    $gmap_count = file_exists("gmap_counter.txt") ? intval(file_get_contents("gmap_counter.txt")) : 0;
    $download_count = file_exists("download_counter.txt") ? intval(file_get_contents("download_counter.txt")) : 0;

    // Step 3: Output message for the user
    $last_output = "";

    // Step 4: Handle Form Data (Width, Height, Prefix)
    $gmap_prefix = isset($_POST['prefix']) ? trim($_POST['prefix']) : "";
    $width = isset($_POST['width']) ? intval($_POST['width']) : 0;
    $height = isset($_POST['height']) ? intval($_POST['height']) : 0;

    // Step 5: Handle GMAP File Download
    if (isset($_GET['gmap'])) {
        $download_file = urldecode($_GET['gmap']);
        if (preg_match('/^[^.][-a-z0-9_.]+$/i', $download_file)) {
            $download_file = "./maps/" . $download_file;
            if (file_exists($download_file)) {
                // Increment download counter
                $download_count++;
                file_put_contents("download_counter.txt", $download_count);

                // File download logic
                header("Content-Description: File Transfer");
                header("Content-Type: application/zip");
                header("Content-Disposition: attachment; filename=\"" . basename($download_file) . "\"");
                header("Expires: 0");
                header("Cache-Control: must-revalidate");
                header("Pragma: public");
                header("Content-Length: " . filesize($download_file));
                flush();
                readfile($download_file);
                unlink($download_file);
                exit();
            } else {
                exit("Requested file not found.");
            }
        } else {
            exit("Invalid request for file download.");
        }
    }

    // Step 6: GMAP Generation Logic (Form Submission)
    if ($width > 0 && $height > 0) {
        if ($gmap_prefix === "") { $gmap_prefix = "default"; }
        if (!preg_match('/^[a-z0-9_-]+$/i', $gmap_prefix)) {
            exit("Invalid characters in GMAP prefix. Only alphanumeric, hyphen, and underscore are allowed.");
        }

        $levels_count = $width * $height;

        // Ensure the 'maps' directory exists
        if (!file_exists("maps")) {
            mkdir("maps", 0777, true);
        }

        // Initialize zip archive for GMAP levels
        $zip = new ZipArchive();
        $filename = "./maps/" . $gmap_prefix . ".zip";
        if ($zip->open($filename, ZipArchive::CREATE) !== TRUE) {
            exit("Could not open <$filename> for zipping.");
        }

        // Level template creation (64x64 grid of tiles)
        $level_template = "GLEVNW01\n";
        for ($i = 0; $i < 64; $i++) {
            $level_template .= "BOARD 0 " . $i . " 64 0 ";
            for ($j = 0; $j < 64; $j++) $level_template .= "AA";
            $level_template .= "\n";
        }

        // GMAP file content creation
        $gmap = "GRMAP001\nWIDTH " . $width . "\nHEIGHT " . $height . "\nLEVELNAMES\n";
        for ($i = 0; $i < $levels_count; $i++) {
            $x = $i % $width;
            $y = intval($i / $width);
            $levelname = $gmap_prefix . $i . ".nw";
            $gmap .= "\"" . $levelname . "\"";
            if ($i < ($levels_count - 1)) $gmap .= ",";
            if ($x == ($width - 1)) $gmap .= "\n";

            $level_copy = $level_template;

            // Create links between levels
            $links = "";
            if ($i + $width < $levels_count) $links .= "LINK " . $gmap_prefix . "_" . ($i + $width) . ".nw 0 0 64 1 playerx 61\n";
            if ($i + 1 < $levels_count) $links .= "LINK " . $gmap_prefix . "_" . ($i + 1) . ".nw 63 0 1 64 0 playery\n";
            if ($i - $width >= 0) $links .= "LINK " . $gmap_prefix . "_" . ($i - $width) . ".nw 0 0 64 1 playerx 61\n";
            if ($i - 1 >= 0) $links .= "LINK " . $gmap_prefix . "_" . ($i - 1) . ".nw 0 0 1 64 61 playery\n";

            $level_copy .= $links;
            $zip->addFromString($levelname, $level_copy);
        }

        // Finalize GMAP file and close zip
        $gmap .= "LEVELNAMESEND\n";
        $zip->addFromString($gmap_prefix . ".gmap", $gmap);
        $zip->close();

        // Increment GMAP generation counter
        $gmap_count++;
        file_put_contents("gmap_counter.txt", $gmap_count);

        // Provide download link to the user
        $last_output .= "<center><a id='dllink' href='generatelevels.php?gmap=" . urlencode($gmap_prefix . ".zip") . "' onclick='clickAndDisable(this);'>Download GMAP HERE</a></center>";
    } else {
        $last_output .= "<center>This tool has generated <b>" . $gmap_count . "</b> GMAPs so far!</center>";
    }
?>

<!-- HTML Form and Dark/Light Mode Toggle -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GMAP Generator</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <style id="theme-style">
        /* Default theme (light) */
        :root {
            --bg-color: #f9f9f9;
            --text-color: #333;
            --content-bg-color: #fff;
            --btn-bg-color: #4CAF50;
            --btn-hover-color: #45a049;
            --link-color: #007BFF;
        }
        
        /* Apply colors dynamically based on light/dark mode */
        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Roboto', sans-serif;
        }
        h1 {
            text-align: center;
            color: var(--text-color);
        }
        .content {
            margin: 20px auto;
            width: 500px;
            padding: 20px;
            background-color: var(--content-bg-color);
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
        }
        td {
            padding: 10px;
        }
        .demension {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn {
            background-color: var(--btn-bg-color);
            color: white;
            padding: 10px 20px;
            text-align: center;
            border-radius: 5px;
            font-size: 18px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: var(--btn-hover-color);
        }
        #dllink {
            display: block;
            margin: 20px;
            font-size: 1.2em;
            color: var(--link-color);
            text-decoration: none;
        }
        #dllink:hover {
            text-decoration: underline;
        }

        #loading {
            display: none;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<!-- Dark/Light Mode Toggle -->
<div style="text-align: center; margin: 20px;">
    <button id="toggle-theme" class="btn">Switch to Dark Mode</button>
</div>

<!-- Form for GMAP generation -->
<form action="generatelevels.php" method="POST" onsubmit="return validateForm();">
    <h1>GMAP Generator</h1>
    <div class="content">
        <h3>Instructions:</h3>
        <p>Fill out the form below to create a GMAP. Width and Height should be positive integers.<br>
        The prefix will be used for naming levels. For example: "GmapName_".</p>
        
        <center>
        <table>
            <tr>
                <td> Width: </td>
                <td> 
                    <input name="width" id="width" type="number" value="10" class="demension" title="Enter the width (number of columns) of the GMAP">
                </td>
            </tr>
            <tr>
                <td> Height: </td>
                <td> 
                    <input name="height" id="height" type="number" value="10" class="demension" title="Enter the height (number of rows) of the GMAP">
                </td>
            </tr>
            <tr>
                <td> Prefix: </td>
                <td> 
                    <input name="prefix" type="text" value="" class="demension" title="Enter the prefix for naming levels, e.g., 'GmapName_'">
                </td>
            </tr>
        </table>
        <br>
        <input type="submit" value="Create GMAP" class="btn" title="Click to generate the GMAP file">
        </center>
    </div>

    <!-- Progress Indicator -->
    <div id="loading">
        <img src="https://i.gifer.com/ZZ5H.gif" alt="Loading..." width="50px"><br>
        <span>Generating your GMAP, please wait...</span>
    </div>
</form>

<!-- Display total visitors, GMAPs generated, and downloads -->
<div style="text-align: center; margin-top: 20px;">
    <p><b>Total Visitors:</b> <?php echo file_get_contents("visitor_counter.txt"); ?></p>
    <p><b>Total GMAPs Generated:</b> <?php echo $gmap_count; ?></p>
    <p><b>Total GMAPs Downloaded:</b> <?php echo $download_count; ?></p>
</div>

<?php echo $last_output; ?>

<!-- JavaScript for Dark/Light Mode, Validation, and Progress Indicator -->
<script>
    // Load the saved theme from localStorage
    document.addEventListener("DOMContentLoaded", function () {
        const theme = localStorage.getItem("theme");
        if (theme) {
            applyTheme(theme);
        }
    });

    // Toggle between dark and light mode
    document.getElementById("toggle-theme").addEventListener("click", function () {
        const currentTheme = localStorage.getItem("theme");
        const newTheme = currentTheme === "dark" ? "light" : "dark";
        applyTheme(newTheme);
    });

    // Apply the selected theme
    function applyTheme(theme) {
        const root = document.documentElement;
        if (theme === "dark") {
            root.style.setProperty('--bg-color', '#1e1e1e');
            root.style.setProperty('--text-color', '#f1f1f1');
            root.style.setProperty('--content-bg-color', '#2e2e2e');
            root.style.setProperty('--btn-bg-color', '#4a90e2');
            root.style.setProperty('--btn-hover-color', '#357ABD');
            root.style.setProperty('--link-color', '#57A6FF');
            document.getElementById("toggle-theme").textContent = "Switch to Light Mode";
        } else {
            root.style.setProperty('--bg-color', '#f9f9f9');
            root.style.setProperty('--text-color', '#333');
            root.style.setProperty('--content-bg-color', '#fff');
            root.style.setProperty('--btn-bg-color', '#4CAF50');
            root.style.setProperty('--btn-hover-color', '#45a049');
            root.style.setProperty('--link-color', '#007BFF');
            document.getElementById("toggle-theme").textContent = "Switch to Dark Mode";
        }
        localStorage.setItem("theme", theme);  // Save user preference in localStorage
    }

    // Form validation before submission
    function validateForm() {
        var width = document.getElementById("width").value;
        var height = document.getElementById("height").value;
        var prefix = document.getElementById("prefix").value;

        if (width <= 0 || height <= 0) {
            alert("Width and Height must be positive integers.");
            return false;
        }

        var validPrefix = /^[a-z0-9_-]+$/i;
        if (!validPrefix.test(prefix)) {
            alert("Prefix contains invalid characters. Only alphanumeric, hyphen, and underscore are allowed.");
            return false;
        }

        document.getElementById("loading").style.display = "block";  // Show loading indicator
        return true;
    }

    // Disable the download link after it's clicked
    function clickAndDisable(link) {
        link.onclick = function (event) {
            event.preventDefault();
        }
    }
</script>

</body>
</html>
