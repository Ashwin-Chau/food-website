<?php
include('../middleware/adminMiddleware.php');
include('../functions/myfunctions.php');
include('includes/header.php');

// Fetch data for all cards
$total_users = getTotalUsers();
$total_menus = getTotalMenus();
$total_food_items = getTotalFoodItems();
$total_orders = getTotalOrders();
$total_revenue = getTotalRevenue();
$pending_orders = getTotalPendingOrders();
$top_selling_item = getTopSellingItem();
?>

<div class="main-content">
    <div class="container py-4">
        <h2 class="mb-4">Admin Dashboard</h2>

        <!-- Dashboard Cards -->
        <div class="content mb-5">
            <div class="row g-4"> <!-- Changed from g-3 to g-4 for larger gaps -->
                <div class="col-md-3">
                    <a href="users.php" class="text-decoration-none">
                        <div class="card bg-gradient-dark text-white p-3">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <h5>Total Users</h5>
                            <p class="mb-0"><?php echo htmlspecialchars($total_users); ?></p>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="menu.php" class="text-decoration-none">
                        <div class="card bg-gradient-dark text-white p-3">
                            <i class="fas fa-utensils fa-2x mb-2"></i>
                            <h5>Total Menu</h5>
                            <p class="mb-0"><?php echo htmlspecialchars($total_menus); ?></p>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="food_items.php" class="text-decoration-none">
                        <div class="card bg-gradient-dark text-white p-3">
                            <i class="fas fa-hamburger fa-2x mb-2"></i>
                            <h5>Total Food Items</h5>
                            <p class="mb-0"><?php echo htmlspecialchars($total_food_items); ?></p>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="delivered.php" class="text-decoration-none">
                        <div class="card bg-gradient-dark text-white p-3">
                            <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                            <h5>Delivered Orders</h5>
                            <p class="mb-0"><?php echo htmlspecialchars($total_orders); ?></p>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="total_sales.php" class=" text-decoration-none">
                        <div class="card bg-gradient-dark text-white p-3">
                            <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                            <h5>Total Revenue</h5>
                            <p class="mb-0">Rs <?php echo number_format($total_revenue, 2); ?></p>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="orders.php" class="text-decoration-none">
                        <div class="card bg-gradient-dark text-white p-3">
                            <i class="fas fa-hourglass-half fa-2x mb-2"></i>
                            <h5>Pending Orders</h5>
                            <p class="mb-0"><?php echo htmlspecialchars($pending_orders); ?></p>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="total_sales.php#popular_menu" class="text-decoration-none">
                        <div class="card bg-gradient-dark text-white p-3">
                            <i class="fas fa-star fa-2x mb-2"></i>
                            <h5>Top Selling Item</h5>
                            <p class="mb-0"><?php echo htmlspecialchars($top_selling_item['name']) . ' (' . htmlspecialchars($top_selling_item['quantity']) . ')'; ?></p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<style>
    .add-menu-item,
    .add-food-item {
        padding-left: 20px; /* Indentation for sub-items in sidebar */
    }

    /* Ensure main content is not hidden behind the sidebar */
    .main-content {
        margin-left: 250px; /* Adjust based on sidebar width */
        padding: 20px;
        min-height: 100vh;
        box-sizing: border-box;
    }

    /* Optional: Increase card spacing further */
    .card {
        margin-bottom: 15px; /* Adds vertical spacing between cards */
    }

    @media (max-width: 767.98px) {
        .main-content {
            margin-left: 0; /* Remove margin on smaller screens */
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