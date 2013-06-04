<?php
    // Set these things...
    $token = "";
    $dbhost = "";
    $dbuser = "";
    $dbpass = "";
    $dbname = "";
    $dbtabl = "";
    $timezone = "America/New_York";

    if(isset($_POST['foodaction'])) {
        // We've received a blob, so lets do something with it.
        $json = $_POST['foodaction'];
        $decoded = json_decode($json, TRUE);
        if ($decoded === FALSE) {
            throw new Exception('Bad JSON input');
        }

        if(strlen($decoded['Content']) > 128) {
            echo "ERR_CONTENT_TOO_BIG";
            exit;
        }

        if($decoded['Token'] == $token) {
            // Token is valid. Go forth.
            $action = $decoded['Action'];
            $content = $decoded['Content'];
            // Test doesnt require a SQL connection. I dont think...
            if($action == "test") {
                echo "OK_TEST";
                exit;
            }
            //Actions will require a MySQL connection, so...
            $dbconnect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
            if (mysqli_connect_errno()) {
                printf("DB connect failed: %s\n", mysqli_connect_error());
            }
            if($action == "add") {
                // Add to the log
                $content = mysqli_real_escape_string($dbconnect, $content);
                $query = "INSERT INTO $dbtabl VALUES(NULL, CURRENT_TIMESTAMP, '$content')";
                $result = mysqli_query($dbconnect, $query);
            } else if($action == "clear") {
                $query = "TRUNCATE TABLE $dbtabl";
                $result = mysqli_query($dbconnect, $query);
            } else {
                echo "ERR_WRONG_ACTION";
            }
            // Handle the result
            if($result) {
                echo "OK";
            } else {
                echo "ERR_DB";
            }
        } else {
            echo "ERR_WRONG_TOKEN";
        }
    } else {
        // Display the database here
        date_default_timezone_set($timezone);
        echo "<!DOCTYPE html>\n";
        echo "<html><head>\n";
        echo "<title>Food Log</title>\n";
        echo "<link href=\"bootstrap.min.css\" rel=\"stylesheet\" media=\"screen\">\n";
        echo "<link href=\"dt-override.css\" rel=\"stylesheet\" media=\"screen\">\n";
        echo "<script type=\"text/javascript\" language=\"javascript\" src=\"jquery.js\"></script>\n";
        echo "<script type=\"text/javascript\" language=\"javascript\" src=\"jquery.dataTables.min.js\"></script>\n";
        echo "<script type=\"text/javascript\" language=\"javascript\" src=\"dt-override.js\"></script>\n";
        echo "</head><body>\n";
        echo "<div class=\"container\">\n";
        echo "<div class=\"page-header\">\n";
        echo "<h1>food <small>(it's good for you)</small></h1></div>\n";
        $dbconnect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
        if(mysqli_connect_errno()) {
            printf("DB connect failed: %s\n", mysqli_connect_error());
        }
        $query = "SELECT UNIX_TIMESTAMP(ts) as ts,content FROM $dbtabl";
        $result = mysqli_query($dbconnect, $query);
        echo "<table class=\"table table-striped table-bordered table-hover\" id=\"foodtable\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
        echo "<thead><tr><th>Logged at</th><th>Item</th></tr></thead>\n";
        echo "<tbody>\n";
        while($row = mysqli_fetch_assoc($result)) {
            $time = $row['ts'];
            $readableTime = date('D j M h:i a', $time);
            $content = $row['content'];
            echo "<tr><td>$readableTime</td><td>$content</td></tr>\n";
        }
        echo "</tbody>\n";
        echo "</table>\n</div>\n</body>\n</html>";
    }
?>
