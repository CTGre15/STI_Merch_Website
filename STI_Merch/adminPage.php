<?php
    session_start();
?>
<html>
    <head>
        <link rel="stylesheet" href="adminPage.css">
        <script src="functions.js"></script>
    </head>
    <body>
        <header>
            <div class="logo"> <img src="images/sti-logo.png" alt="STI Logo"> </div>
            <div class="title"> <h1>STI Merch Store</h1> </div>
            <form method="post">
                <button name="logout" class="logout">Log out</button>
            </form>
        </header>

        <div class="user"><h1>Welcome Admin</h1></div>
        
        <form method="post" class="listItem">
            <h3>List an Item</h3><br>
            <label for="itemName">Item Name:</label>
            <input type="text" id="itemName" name="itemName" required><br>
            <label for="itemDesc">Item Description:</label>
            <input type="text" id="itemDesc" name="itemDesc" required><br>
            <label for="price">Price:</label>
            <input type="int" id="price" name="price" required><br>
            <label for="stocks">Stocks:</label>
            <input type="int" id="stocks" name="stocks" required><br><br>
            <label for="image">Image:</label>
            <input type="file" id="image" name="image"><br><br>
            <button name="listItem" class="listItem">List Item</button>
        </form>
        
        <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbName = "STIMerch";
            $db = new mysqli($servername, $username, $password, $dbName);
            $_SESSION['db'] = $db;

            function alert($msg) {
                echo "<script type='text/javascript'>alert('$msg');</script>";
            }
            function listItem($itemName, $itemDesc, $price, $stocks){
                $insertItemQuery = $_SESSION['db']->prepare("INSERT INTO Items (itemName, itemDesc, price, stocks)
                VALUES (?, ?, ?, ?)");
                $insertItemQuery->bind_param("ssii", $itemName, $itemDesc, $price, $stocks);
                $insertItemQuery->execute();
                alert("Item Listed");
            }
            function passItemVariables(){
                $itemName = $_POST["itemName"];
                $itemDesc = $_POST["itemDesc"];
                $price = $_POST["price"];
                $stocks = $_POST["stocks"];

                listItem($itemName, $itemDesc, $price, $stocks);
            }
            function logout(){
                session_destroy();
                header("Location: welcome.php");
            }
            if(isset($_POST["logout"])) {
                logout();
            }
            if(isset($_POST["listItem"])) {
                passItemVariables();
            }
        ?>
    </body>
</html>