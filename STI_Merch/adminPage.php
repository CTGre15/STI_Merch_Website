<?php
    session_start();
?>
<html>
    <head>
        <script src="functions.js"></script>
    </head>
    <body>
        <h1>Welcome Admin</h1>
        
        <form method="post">
            <h3>List an Item</h3>
            <label for="itemName">Item Name:</label>
            <input type="text" id="itemName" name="itemName" required><br>
            <label for="itemDesc">Item Description:</label>
            <input type="text" id="itemDesc" name="itemDesc" required><br>
            <label for="price">Price:</label>
            <input type="int" id="price" name="price" required><br>
            <label for="stocks">Stocks:</label>
            <input type="int" id="stocks" name="stocks" required><br>
            Attach Image: -Ginagawa pa- <br>
            <button name="listItem">List Item</button>
        </form>
        <form method="post">
            <button name="logout">Log out</button>
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