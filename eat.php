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
    
        if($decoded['Token'] == $token) {
            // Token is valid. Go forth.
            $action = $decoded['Action'];
            $content = $decoded['Content'];
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
                echo "Invalid action.";
            }
        } else {
            echo "Invalid Token.";
        }
    } else {
        // Display the database here
        date_default_timezone_set($timezone);
        echo "<html><head>";
        echo "<title>Food Log</title>";
        echo "</head><body>";
        $dbconnect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
        if(mysqli_connect_errno()) {
            printf("DB connect failed: %s\n", mysqli_connect_error());
        }
        #$query = "SELECT UNIX_TIMESTAMP(CONVERT_TZ(ts,'UTC','America/New_York')) as ts,content FROM $dbtabl ORDER BY ts DESC";
        $query = "SELECT UNIX_TIMESTAMP(ts) as ts,content FROM $dbtabl ORDER BY ts DESC";
        $result = mysqli_query($dbconnect, $query);
        echo "<table><tr><td><strong>Logged at</strong></td><td><strong>Item</strong></td></tr>";
        while($row = mysqli_fetch_assoc($result)) {
            $time = $row['ts'];
            $readableTime = date('D j M h:i a', $time);
            $content = $row['content'];
            echo "<tr><td>$readableTime</td><td>$content</td></tr>";
        }
        echo "</table></body></html>";
    }
?>
