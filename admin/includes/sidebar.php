<?php
$page = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
?>

<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" id="sidenav-main" style="z-index: 1000;">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="index.php" target="_blank">
            <span class="ms-1 font-weight-bold text-white">FoodHub</span>
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto max-height-vh-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link text-white <?= $page == 'index.php' ? 'active bg-gradient-primary' : '' ?>" href="index.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-gauge"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white <?= $page == 'users.php' ? 'active bg-gradient-primary' : '' ?>" href="users.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <span class="nav-link-text ms-1">Users</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white <?= $page == 'menu.php' ? 'active bg-gradient-primary' : '' ?>" href="menu.php" id="menu-link">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <span class="nav-link-text ms-1">All Menu</span>
                </a>
            </li>

            <li class="nav-item add-menu-item" style="<?= $page == 'menu.php' || $page == 'add_menu.php' ? '' : 'display: none;' ?>">
                <a class="nav-link text-white <?= $page == 'add_menu.php' ? 'active bg-gradient-primary' : '' ?>" href="add_menu.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    <span class="nav-link-text ms-1">Add Menu</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white <?= $page == 'food_items.php' ? 'active bg-gradient-primary' : '' ?>" href="food_items.php" id="food-items-link">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-hamburger"></i>
                    </div>
                    <span class="nav-link-text ms-1">All Food Items</span>
                </a>
            </li>

            <li class="nav-item add-food-item" style="<?= $page == 'food_items.php' || $page == 'add_food_items.php' ? '' : 'display: none;' ?>">
                <a class="nav-link text-white <?= $page == 'add_food_items.php' ? 'active bg-gradient-primary' : '' ?>" href="add_food_items.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    <span class="nav-link-text ms-1">Add Food Items</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white <?= $page == 'orders.php' ? 'active bg-gradient-primary' : '' ?>" href="orders.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-bag-shopping"></i>
                    </div>
                    <span class="nav-link-text ms-1">Orders</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white <?= $page == 'total_sales.php' ? 'active bg-gradient-primary' : '' ?>" href="total_sales.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-bag-shopping"></i>
                    </div>
                    <span class="nav-link-text ms-1">Total Sales</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0">
        <div class="mx-3">
            <a class="btn bg-gradient-primary mt-4 w-100" href="../logout.php">
                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span class="nav-link-text ms-2">Logout</span>
                </div>
            </a>
        </div>
    </div>
</aside>

<style>
    .add-menu-item,
    .add-food-item {
        padding-left: 20px; /* Indentation for sub-items */
    }

    /* Ensure main content is not hidden behind the sidebar */
    .main-content {
        margin-left: 250px; /* Adjust based on your sidebar width */
        padding: 20px;
        min-height: 100vh; /* Ensure it takes full height */
        box-sizing: border-box;
    }

    @media (max-width: 767.98px) {
        .main-content {
            margin-left: 0; /* Remove margin on smaller screens if sidebar collapses */
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuLink = document.getElementById('menu-link');
        const foodItemsLink = document.getElementById('food-items-link');
        const addMenuItem = document.querySelector('.add-menu-item');
        const addFoodItem = document.querySelector('.add-food-item');

        menuLink.addEventListener('click', function (e) {
            addMenuItem.style.display = 'block';
            addFoodItem.style.display = 'none';
        });

        foodItemsLink.addEventListener('click', function (e) {
            addFoodItem.style.display = 'block';
            addMenuItem.style.display = 'none';
        });

        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            if (link !== menuLink && link !== foodItemsLink) {
                link.addEventListener('click', function () {
                    addMenuItem.style.display = 'none';
                    addFoodItem.style.display = 'none';
                });
            }
        });
    });
</script>