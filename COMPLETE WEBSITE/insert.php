<?php
if (isset($_POST['submit'])) {
    if (isset($_POST['username']) && isset($_POST['phone']) &&
        isset($_POST['profession']) && isset($_POST['address'])) {
        
        $username = $_POST['username'];
        $phone = $_POST['phone'];
        $profession = $_POST['profession'];
        $address = $_POST['address'];
       

        $host = "localhost";
        $dbUsername = "root";
        $dbPassword = "";
        $dbName = "E-Mistri";

        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

        if ($conn->connect_error) {
            die('Could not connect to the database.');
        }
        else {
            $Select = "SELECT phone FROM register WHERE phone = ? LIMIT 1";
            $Insert = "INSERT INTO register(username, phone, profession, address) values(?, ?, ?, ?)";

            $stmt = $conn->prepare($Select);
            $stmt->bind_param("i", $phone);
            $stmt->execute();
            $stmt->bind_result($resultphone);
            $stmt->store_result();
            $stmt->fetch();
            $rnum = $stmt->num_rows;

            if ($rnum == 0) {
                $stmt->close();

                $stmt = $conn->prepare($Insert);
                $stmt->bind_param("siss",$username, $phone, $profession, $address);
                if ($stmt->execute()) {
                    echo "New record inserted sucessfully.";
                }
                else {
                    echo $stmt->error;
                }
            }
            else {
                echo "Someone already registers using this phone.";
            }
            $stmt->close();
            $conn->close();
        }
    }
    else {
        echo "All field are required.";
        die();
    }
}
else {
    echo "Submit button is not set";
}
?>