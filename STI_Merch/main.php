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
        <link rel="stylesheet" href="main.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

        <!-- Navigation bar -->
        <nav class="navbar">
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#shop">Shop</a></li>
                <li><a href="#about">About</a></li>
            </ul>
            <div class="user-info">
                <form method="post">
                    <span>Hello, <?php echo $_SESSION["fName"] . " " . $_SESSION["lName"]; ?></span>
                    <button name="viewCart" class="cart-icon"><i class="fas fa-shopping-cart"></i></button>
                </form>
            </div>
        </nav>

        <!-- Home Section -->
        <section id="home">
            <div class="home-grid">
                <div class="slideshow-container">
                    <div class="mySlides fade">
                        <img src="images/home.png" class="slide-image">
                    </div>
                    <div class="mySlides fade">
                        <img src="images/home2.jpg" class="slide-image">
                    </div>
                    <div class="mySlides fade">
                        <img src="images/home3.jpg" class="slide-image">
                    </div>
                    <div class="mySlides fade">
                        <img src="images/home4.jpg" class="slide-image">
                    </div>
                    <div class="mySlides fade">
                        <img src="images/home5.jpg" class="slide-image">
                    </div>
                    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                    <a class="next" onclick="plusSlides(1)">&#10095;</a>
                </div>
                <div class="welcome-text">
                    <h3>Welcome to STI Merch Store</h3>
                    <p>Explore our wide range of products and find what you need. We offer high-quality merchandise at affordable prices. Shop now and enjoy exclusive deals and discounts!</p>
                </div>
            </div>
        </section>

        <!-- Shop Section -->
        <section id="shop">
            <h3 class="browseItem">Browse our selection:</h3>
            <div class="selectionContainerBox">
                <div class="selectionContainer">
                    <?php
                        $displayItemQuery = "SELECT * FROM Items";
                        $result = mysqli_query($_SESSION['db'], $displayItemQuery);
                
                        if (mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)) {
                                echo "<a class='clickable' href='displayItem.php?itemName=" . $row["itemName"] . "'>";
                                echo "<div class='itemFrame'>";
                                echo "<div id='imageFrame'><img id='itemPic' src='itemsImage/" . $row['imageName'] . "' alt='" . $row["itemName"] . "'></div>";
                                echo "<div id='itemDesc'>";
                                echo "<b class='itemName'>" . $row["itemName"] . "</b>";
                                echo "<p class='itemDesc'>" . $row["itemDesc"] . "</p>";
                                echo "<p class='itemPrice'>₱" . $row["price"] . "</p>";
                                echo "<p class='itemStocks'>Stocks: " . $row["stocks"] . "</p>";
                                echo "</div>";
                                echo "</div>";
                                echo "</a>";
                            }
                        }
                    ?>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about">
            <div class="about-container">
                <h3 class="about-title">About Us</h3>
                <p class="about-text">Welcome to STI Merch Store, your number one source for all things merchandise. We're dedicated to giving you the very best of products, with a focus on quality, customer service, and uniqueness.</p>
                <p class="about-text">Founded in 2021, STI Merch Store has come a long way from its beginnings. When we first started out, our passion for providing the best products drove us to do intense research, and gave us the impetus to turn hard work and inspiration into a booming online store. We now serve customers all over the country, and are thrilled to be a part of the quirky, eco-friendly, fair trade wing of the fashion industry.</p>
                <p class="about-text">We hope you enjoy our products as much as we enjoy offering them to you. If you have any questions or comments, please don't hesitate to contact us.</p>
                <p class="about-signature">Sincerely,<br>The STI Merch Store Team</p>
            </div>
        </section>

        <?php
            function logout(){
                session_destroy();
                echo "<script>window.location.href = 'welcome.php';</script>";
            }
            function viewCart(){
                echo "<script>window.location.href = 'cart.php';</script>";
            }
            if(isset($_POST["logout"])) {
                logout();
            }
            if(isset($_POST["viewCart"])) {
                viewCart();
            }
        ?>
        <script>
            let slideIndex = 0;
            showSlides();

            function showSlides() {
                let i;
                let slides = document.getElementsByClassName("mySlides");
                for (i = 0; i < slides.length; i++) {
                    slides[i].style.display = "none";  
                }
                slideIndex++;
                if (slideIndex > slides.length) {slideIndex = 1}    
                slides[slideIndex-1].style.display = "block";  
                setTimeout(showSlides, 5000); // Change image every 5 seconds
            }

            function plusSlides(n) {
                showSlides(slideIndex += n);
            }
        </script>
    </body>
</html>