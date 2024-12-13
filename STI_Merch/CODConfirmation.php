<?php
    session_start();
    $order = $_SESSION["itemsOrdered"];
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbName = "STIMerch";
    $db = new mysqli($servername, $username, $password, $dbName);
    $_SESSION['db'] = $db;
    
?>
<html>
    <head>
        <link rel="stylesheet" href="CODCheckoutPage.css">
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

        <div class="user"><h1>Checkout</h1></div>
        
        <form method="post" class="listItem">
            <h3>Payment Pending...</h3><br>
            <h3>Order Details</h3><br>
            <?php
                $currentOrder = $_SESSION["currentOrder"];
                $displayOrderQuery = "SELECT * FROM Orders WHERE OrderID = " . $currentOrder;
                $result = mysqli_query($_SESSION['db'], $displayOrderQuery);
        
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "Order #" . $row["orderId"] . "<br>";
                        echo "Customer Name: " . $row["fullname"] . "<br>";
                        echo "Phone Number: " . $row["phoneNum"] . "<br>";
                        echo "Delivery Address: " . $row["orderAddress"] . "<br>";
                        echo "Date Ordered: " . $row["orderDate"] . "<br>";
                        echo "Amount to Pay: " . $row["priceToPay"] . "<br>";
                    }
                }
            ?>
            <input type="button" value="Back to Home" onclick="window.location.href = 'main.php'" />;
        </form>

        <?php
            function logout(){
                session_destroy();
                echo "<script>window.location.href = 'welcome.php';</script>";
            }
            if(isset($_POST["logout"])) {
                logout();
            }
        ?>
    </body>
</html>