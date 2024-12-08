<?php
    session_start();
?>
<html>
    <head>
        <link rel="stylesheet" href="welcomePage.css">
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
                    studId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    studemail VARCHAR(50),
                    studpassword VARCHAR(50) NOT NULL,
                    firstname VARCHAR(20) NOT NULL,
                    lastname VARCHAR(20) NOT NULL,
                    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                    )";
            $itemsTable = "CREATE TABLE IF NOT EXISTS Items (
                        itemId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        itemName VARCHAR(100) NOT NULL,
                        itemDesc VARCHAR(200) NOT NULL,
                        price INT NOT NULL,
                        stocks INT(5) NOT NULL,
                        imageName VARCHAR(100) NOT NULL
                        );";
            $db = new mysqli($servername, $username, $password, $dbName);
            $_SESSION["db"] = $db;
            mysqli_query($db, $accountsTable);
            mysqli_query($db, $itemsTable);

            function alert($msg) {
                echo "<script type='text/javascript'>alert('$msg');</script>";
            }
            function addAdmin(){
                $email = "admin1";
                $password = "1234";
                $fname = "";
                $lname = "";
                $countEmail = findEmail($email);
                if ($countEmail == 0) {
                    $insertQuery = $_SESSION['db']->prepare("INSERT INTO Accounts (studemail, studpassword, firstname, lastname)
                    VALUES (?, ?, ?, ?)");
                    $insertQuery->bind_param("ssss", $email, $password, $fname, $lname);
                    $insertQuery->execute();
                    $insertQuery->close();
                } else {
                }
            }
            function verify($email, $password){
                $countEmail = findEmail($email);
                if ($countEmail > 0) {
                    $countPass = checkPass($email, $password);
                    if ($countPass > 0) {
                        setSession($email);
                        if ($email == "admin1"){
                            header("Location: adminPage.php");
                        } else {
                            header("Location: main.php");
                        }
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
                        $_SESSION["cart"] = $row["lastname"] . $row["firstname"] . $row["studId"] . "cart";
                    }
                }
            }
            function findStudID ($email){
                $findStudID = $_SESSION['db']->prepare("SELECT studId FROM Accounts WHERE studemail = ?");
                $findStudID->bind_param("s", $email);
                $findStudID->execute();
                $result = $findStudID->get_result();
                $id;
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        $id = $row["studId"];
                    }
                }
                return $id;
            }
            function createCart($fName, $lName, $email){
                $idNum = findStudID($email);

                $tableName = $lName . $fName . $idNum . "Cart";
                $cart = "CREATE TABLE IF NOT EXISTS " . $tableName . " (
                        itemName VARCHAR(100) PRIMARY KEY,
                        addedToCart INT(5)
                        );";
                mysqli_query($_SESSION['db'], $cart);
            }
            function inputRegInfo($email, $password, $fname, $lname){
                $countEmail = findEmail($email);
                if ($countEmail == 0) {
                    $insertQuery = $_SESSION['db']->prepare("INSERT INTO Accounts (studemail, studpassword, firstname, lastname)
                    VALUES (?, ?, ?, ?)");
                    $insertQuery->bind_param("ssss", $email, $password, $fname, $lname);
                    $insertQuery->execute();
                    $insertQuery->close();
                    createCart($fname, $lname, $email);
                    alert("Account Registered!");
                } else {
                    alert("Account already Exists");
                }
            }
            function passCreds(){
                verify($_POST["loginemail"], $_POST["loginpassword"]);
            }
            function regInfo(){
                inputRegInfo($_POST["registeremail"], $_POST["registerpassword"], $_POST["registerfname"], $_POST["registerlname"]);
            }
            addAdmin();
        ?>
        <header>
            <div class="logo"> <img src="images/sti-logo.png" alt="STI Logo"> </div>
            <div class="title"> <h1>STI Merch Store</h1> </div>
            <div class="login"> <button onclick="openModal('loginModal')">Log in</button> </div>
        </header>

        <div class="section1"> <img src="images/welcome.png" alt="Welcome STIers"> </div>

        <div id="loginModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('loginModal')">&times;</span>
                <div id="loginform">
                    <form method="post">
                        <label for="loginemail">Email:</label>
                        <input type="text" id="loginemail" name="loginemail" required><br>
                        <label for="loginpassword">Password:</label>
                        <input type="password" id="loginpassword" name="loginpassword" required><br>
                        <input type="checkbox" onclick="showLoginPassword()" class="showPassword"><span style="font-size: 0.813rem;">Show Password</span><br><br>
                        <input type="submit" name="login" value="Log in"><br><br>

                        <div class="changeForm">Don't have an account yet? <a href="#" onclick="openModal('registerModal'); closeModal('loginModal'); return false;">Register</a>.</div>
                    </form>
                </div>
            </div>
        </div>

        <div id="registerModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('registerModal')">&times;</span>
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
                        <input type="checkbox" onclick="showRegisterPassword()" class="showPassword"><span style="font-size: 0.813rem;">Show Password</span><br><br>
                        <input type="submit" name="register" value="Register"><br><br>

                        <div class="changeForm">Already have an account? <a href="#" onclick="openModal('loginModal'); closeModal('registerModal'); return false;">Log in</a>.</div>
                    </form>
                </div>
            </div>
        </div>
        <?php
            if(isset($_POST["login"])) {
                passCreds();
            } if(isset($_POST["register"])) {
                regInfo();
            }
        ?>
    </body>