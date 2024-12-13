<?php
    session_start();
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbName = "STIMerch";
    $db = new mysqli($servername, $username, $password, $dbName);
    $_SESSION['db'] = $db;
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
            <form method="post" class="logout-container">
                <button name="logout" class="logout">Log out</button>
            </form>
        </header>

        <div class="user"><h1>Welcome Admin</h1></div>
        
        <form method="post" class="listItem" enctype="multipart/form-data">
            <h3>List an Item</h3><br>
            <label for="itemName">Item Name:</label>
            <input type="text" id="itemName" name="itemName" required><br>
            <label for="itemDesc">Item Description:</label>
            <input type="text" id="itemDesc" name="itemDesc" required><br>
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" required><br>
            <label for="stocks">Stocks:</label>
            <input type="number" id="stocks" name="stocks" required><br><br>
            <label for="image">Image:</label>
            <input type="file" id="image" name="image" required><br><br>
            <button name="listItem">List Item</button>
        </form>
        <div class="big-container">
        <h2>Item List</h2>
        <div class="cartContainer">
            <div><b>ID</b></div>
            <div><b>Name</b></div>
            <div><b>Description</b></div>
            <div><b>Price</b></div>
            <div><b>Stocks</b></div>
            <div><b></b></div>
            <?php
                $displayItemsQuery = "SELECT * FROM Items";
                $result = mysqli_query($_SESSION['db'], $displayItemsQuery);
        
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        $stocks = $row["stocks"];
                        
                        echo "<div>";
                        echo $row["itemId"];
                        echo "</div>";
                        echo "<div>";
                        echo $row["itemName"];
                        echo "</div>";
                        echo "<div>";
                        echo $row["itemDesc"];
                        echo "</div>";
                        echo "<div>";
                        echo $row["price"];
                        echo "</div>";
                        echo "<div class='cartItemQuantity'>";
                        echo "<form method='post'>
                                <div><button name='subtract1'>-</button></div>
                                <input type='hidden' name='itemName' value='" . $row["itemName"] . "'>
                                <input type='hidden' name='itemQuantity' value='" . $stocks . "'>
                                <div><span style='color: #008000;'>" . $stocks . "</span></div>
                                <div><button name='add1'>+</button></div>
                            </form>";
                        echo "</div>";
                        echo "<div class='cartItemQuantity'>";
                        echo "<form method='post'>
                                <input type='hidden' name='itemName' value='" . $row["itemName"] . "'>
                                <div><button name='delete'>Delete</button></div>
                            </form>";
                        echo "</div>";
                    }
                }
                ?>
            </div>
        </div>
        <div class="big-container">
        <h2>Orders</h2>
        <div class="cartContainer" id="orders">
            <div><b>ID</b></div>
            <div><b>Customer</b></div>
            <div><b>Items Ordered</b></div>
            <div><b>Phone Number</b></div>
            <div><b>Address</b></div>
            <div><b>Order Type</b></div>
            <div><b>Price to Pay</b></div>
            <div><b>Order Date</b></div>
            <div><b></b></div>
            <?php
                $displayOrdersQuery = "SELECT * FROM Orders";
                $resultOrders = mysqli_query($_SESSION['db'], $displayOrdersQuery);
        
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($resultOrders)) {
                        echo "<div>";
                        echo $row["orderId"];
                        echo "</div>";
                        echo "<div>";
                        echo $row["fullname"];
                        echo "</div>";
                        echo "<div>";
                        echo $row["items"];
                        echo "</div>";
                        echo "<div>";
                        echo $row["phoneNum"];
                        echo "</div>";
                        echo "<div>";
                        echo $row["orderAddress"];
                        echo "</div>";
                        echo "<div>";
                        echo $row["orderType"];
                        echo "</div>";
                        echo "<div>";
                        echo $row["priceToPay"];
                        echo "</div>";
                        echo "<div>";
                        echo $row["orderDate"];
                        echo "</div>";
                        echo "<div class='cartItemQuantity'>";
                        echo "<form method='post'>
                                <input type='hidden' name='orderId' value='" . $row["orderId"] . "'>
                                <div><button name='removeOrder'>Finish Order</button></div>
                            </form>";
                        echo "</div>";
                    }
                }
                ?>
            </div>
        </div>
        <?php

            function refresh(){
                echo "<script>window.location.href = 'cart.php';</script>";
            }
            function alert($msg) {
                echo "<script type='text/javascript'>alert('$msg');</script>";
            }
            function listItem($itemName, $itemDesc, $price, $stocks, $imageFile){
                $imageName = imageUpload($imageFile);
                $insertItemQuery = $_SESSION['db']->prepare("INSERT INTO Items (itemName, itemDesc, price, stocks, imageName)
                VALUES (?, ?, ?, ?, ?)");
                $insertItemQuery->bind_param("ssiis", $itemName, $itemDesc, $price, $stocks, $imageName);
                $insertItemQuery->execute();
                alert("Item Listed");
            }
            function passItemVariables(){
                $itemName = $_POST["itemName"];
                $itemDesc = $_POST["itemDesc"];
                $price = $_POST["price"];
                $stocks = $_POST["stocks"];
                $imageFile = $_FILES["image"];

                listItem($itemName, $itemDesc, $price, $stocks, $imageFile);
            }
            function imageUpload($imageFile){
                $file = $imageFile;
                $fileName = $file["name"];
                $fileTmpName = $file["tmp_name"];
                $fileError = $file["error"];
                $fileType = $file["type"];

                $fileDividedName = explode('.', $fileName);
                $fileExt = strtolower(end($fileDividedName));

                $accepted = array("jpg", "jpeg", "png");

                if (in_array($fileExt, $accepted)){
                    if ($fileError === 0){
                        $fileDestination = "itemsImage/" . $fileName;
                        move_uploaded_file($fileTmpName, $fileDestination);
                    }
                }

                return $fileName;
            }
            function add1($itemName, $itemQuantity){
                $query = "UPDATE Items
                            SET stocks = stocks + 1
                            WHERE itemName = '" . $itemName . "';";
                $add1ToItem = $_SESSION['db']->prepare($query);
                $add1ToItem->execute();
                $add1ToItem->close();
                refresh();
            }
            function subtract1($itemName, $itemQuantity){
                if ($itemQuantity > 1){
                    $query = "UPDATE Items
                                SET stocks = stocks - 1
                                WHERE itemName = '" . $itemName . "';";
                    $subtract1ToItem = $_SESSION['db']->prepare($query);
                    $subtract1ToItem->execute();
                    $subtract1ToItem->close();
                } else {
                    $query = "DELETE FROM Items WHERE itemName = '" . $itemName . "';";
                    $subtract1ToItem = $_SESSION['db']->prepare($query);
                    $subtract1ToItem->execute();
                    $subtract1ToItem->close();
                }
                refresh();
            }
            function deleteItem($itemName){
                $query = "DELETE FROM Items WHERE itemName = '" . $itemName . "';";
                $deleteItem = $_SESSION['db']->prepare($query);
                $deleteItem->execute();
                $deleteItem->close();
                refresh();
            }
            function removeOrder($itemID){
                $query = "DELETE FROM Orders WHERE orderId = " . $itemID . ";";
                $removeOrder = $_SESSION['db']->prepare($query);
                $removeOrder->execute();
                $removeOrder->close();
                refresh();
            }
            function logout(){
                session_destroy();
                echo "<script>window.location.href = 'welcome.php';</script>";
            }
            if(isset($_POST["logout"])) {
                logout();
            }
            if(isset($_POST["listItem"])) {
                passItemVariables();
            }
            if(isset($_POST["add1"])) {
                add1($_POST["itemName"], $_POST["itemQuantity"]);
            }
            if(isset($_POST["subtract1"])) {
                subtract1($_POST["itemName"], $_POST["itemQuantity"]);
            }
            if(isset($_POST["delete"])) {
                deleteItem($_POST["itemName"]);
            }
            
            if(isset($_POST["removeOrder"])) {
                removeOrder($_POST["orderId"]);
            }
        ?>
    </body>
</html>