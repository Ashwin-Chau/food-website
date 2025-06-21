<nav>
    <div class="nav-container">
        <div class="logo">
            <a href="index.php" class="logo">FOODHUB</a>
        </div>

        <div class="menu-toggle" onclick="toggleMenu()">
            <i class="fas fa-bars"></i>
        </div>

        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="index.php#Menu">Menu</a></li>
            <li><a href="index.php#About">About</a></li>
        </ul>

        <form class="navbar-search-form" method="GET" action="food_items.php">
            <input type="text" name="query" class="search-input" placeholder="Search food ">
            <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
        </form>

        <?php
        if (isset($_SESSION['auth'])) {
        ?>
            <ul class="user-links">
                <li><a href="cart.php">My Cart</a></li>
                <li><a href="my_orders.php">My Orders</a></li>
                <li class="dropdown" id="userDropdown">
                    <a class="nav-link" onclick="toggleDropdown()"> <?= htmlspecialchars($_SESSION['auth_user']['name']) ?> </a>
                    <ul class="dropdown-menu" id="dropdownMenu">
                        <li><a href="my_profile.php">My Profile</a></li>
                        <li><a href="change_password.php">Change Password</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        <?php
        } else {
        ?>
            <div class="icon">
                <a href="login.php"><button>Login</button></a>
                <a href="register.php"><button>Register</button></a>
            </div>
        <?php
        }
        ?>
    </div>
</nav>