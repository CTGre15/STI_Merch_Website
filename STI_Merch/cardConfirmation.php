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
        
        <form method="post">
        <div class="order-details-container">
            <h3>Payment Confirmed</h3>
            <h3>Order Details</h3>
            <div class="order-details">
                <?php
                    $currentOrder = $_SESSION["currentOrder"];
                    $displayOrderQuery = "SELECT * FROM Orders WHERE orderId = " . $currentOrder;
                    $result = mysqli_query($_SESSION['db'], $displayOrderQuery);
            
                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<p><strong>Order #:</strong> " . $row["orderId"] . "</p>";
                            echo "<p><strong>Customer Name:</strong> " . $row["fullname"] . "</p>";
                            echo "<p><strong>Phone Number:</strong> " . $row["phoneNum"] . "</p>";
                            echo "<p><strong>Delivery Address:</strong> " . $row["orderAddress"] . "</p>";
                            echo "<p><strong>Date Ordered and Paid:</strong> " . $row["orderDate"] . "</p>";
                            echo "<p><strong>Amount Paid:</strong> ₱" . $row["priceToPay"] . "</p>";
                        }
                    } else {
                        echo "<p>No order details found.</p>";
                    }
                ?>
            </div>
            <button class="back-home" name="backToHome">Back to Home</button>
        </div>
        </form>

        <?php
            function logout(){
                session_destroy();
                echo "<script>window.location.href = 'welcome.php';</script>";
            }
            function backToHome(){
                echo "<script>window.location.href = 'main.php';</script>";
            }
            if(isset($_POST["logout"])) {
                logout();
            }
            if(isset($_POST["backToHome"])) {
                backToHome();
            }
        ?>
    </body>
</html>