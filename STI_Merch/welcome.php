<?php
    session_start();
?>
<html>
    <head>
        <link rel="stylesheet" href="welcome.css">
        <script src="functions.js"></script>
    </head>
    <body>
        <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbName = "STIMerch";
            
            $conn = mysqli_connect($servername, $username, $password);
            $sql = "CREATE DATABASE IF NOT EXISTS ". $dbName .";";
            mysqli_query($conn, $sql);
            $accountsTable = "CREATE TABLE IF NOT EXISTS Accounts (
                    studemail VARCHAR(50) PRIMARY KEY,
                    studpassword VARCHAR(50) NOT NULL,
                    firstname VARCHAR(20) NOT NULL,
                    lastname VARCHAR(20) NOT NULL,
                    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                    )";
            $db = new mysqli($servername, $username, $password, $dbName);
            $_SESSION["db"] = $db;
            mysqli_query($db, $accountsTable);

            function alert($msg) {
                echo "<script type='text/javascript'>alert('$msg');</script>";
            }
            function verify($db, $email, $password){
                $countEmail = findEmail($email);
                if ($countEmail > 0) {
                    $countPass = checkPass($email, $password);
                    if ($countPass > 0) {
                        setSession($email);
                        header("Location: main.php");
                    } else {
                        alert("Incorrect Password");
                    }
                } else {
                    alert("Incorrect Email");
                }
            }
            function findEmail($email){
                $findEmailQuery = $_SESSION["db"]->prepare("SELECT COUNT(*) FROM Accounts WHERE studemail = ?");
                $findEmailQuery->bind_param("s", $email);
                $findEmailQuery->execute();
                $findEmailQuery->bind_result($countEmail);
                $findEmailQuery->fetch();
                $findEmailQuery->close();

                return $countEmail;
            }
            function checkPass($email, $password){
                $findPasswordQuery = $_SESSION["db"]->prepare("SELECT COUNT(*) FROM Accounts WHERE studemail = ? AND studpassword = ?");
                $findPasswordQuery->bind_param("ss", $email, $password);
                $findPasswordQuery->execute();
                $findPasswordQuery->bind_result($countPass);
                $findPasswordQuery->fetch();
                $findPasswordQuery->close();

                return $countPass;
            }
            function setSession ($email){
                $selectQuery = $_SESSION['db']->prepare("SELECT * FROM Accounts WHERE studemail = ?");
                $selectQuery->bind_param("s", $email);
                $selectQuery->execute();
                $result = $selectQuery->get_result();

                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        $_SESSION["user"] = $email;
                        $_SESSION["fName"] = $row["firstname"];
                        $_SESSION["lName"] = $row["lastname"];
                    }
                }
            }
            function inputRegInfo($db, $email, $password, $fname, $lname){
                $insertQuery = $db->prepare("INSERT INTO Accounts (studemail, studpassword, firstname, lastname)
                VALUES (?, ?, ?, ?)");
                $insertQuery->bind_param("ssss", $email, $password, $fname, $lname);
                $insertQuery->execute();
                $insertQuery->close();
                alert("Account Registered!");
            }
            function passCreds($db){
                verify($db, $_POST["loginemail"], $_POST["loginpassword"]);
            }
            function regInfo($db){
                inputRegInfo($db, $_POST["registeremail"], $_POST["registerpassword"], $_POST["registerfname"], $_POST["registerlname"]);
            }
        ?>
        <div id="loginform">
            <form method="post">
                <label for="loginemail">Email:</label>
                <input type="text" id="loginemail" name="loginemail" required><br>
                <label for="loginpassword">Password:</label>
                <input type="password" id="loginpassword" name="loginpassword" required><br>
                <input type="submit" name="login" value="Log in">
            </form>
        <div class="changeForm">Don't have an account yet? <a href="#" onclick="openRegister(); return false;">Register</a>.</div>
        </div>
        <div id="registerform">
            <form method="post">
                <label for="registerfname">First Name:</label>
                <input type="text" id="registerfname" name="registerfname" required><br>
                <label for="registerlname">Last Name:</label>
                <input type="text" id="registerlname" name="registerlname" required><br>
                <label for="registeremail">Email:</label>
                <input type="text" id="registeremail" name="registeremail" required><br>
                <label for="registerpassword">Password:</label>
                <input type="password" id="registerpassword" name="registerpassword" required><br>
                <input type="submit" name="register" value="Register">
            </form>
        <div class="changeForm">Already have an account? <a href="#" onclick="openLogin(); return false;">Log in</a>.</div>
        </div>
        <?php
            if(isset($_POST["login"])) {
                passCreds($db);
            } if(isset($_POST["register"])) {
                regInfo($db);
            }
        ?>
    </body>