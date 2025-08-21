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

// Sorting and filtering for user registrations
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'period';
$order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
$filter_type = isset($_GET['filter_type']) ? $_GET['filter_type'] : '';
$start_date = isset($_GET['start_date']) && $_GET['start_date'] !== '' ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) && $_GET['end_date'] !== '' ? $_GET['end_date'] : '';
$error = '';

// Validate sorting parameters
$valid_sort_columns = ['period', 'registration_count'];
$sort = in_array($sort, $valid_sort_columns) ? $sort : 'period';
$order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';

$valid_filter_types = ['daily', 'last7days', 'last15days', 'last30days'];
$filter_type = in_array($filter_type, $valid_filter_types) ? $filter_type : '';

// Initialize variables
$registration_data = [];
$use_manual_dates = false;

// Set dates based on filter_type
if ($filter_type) {
    $today = new DateTime();
    $end_date = $today->format('Y-m-d');
    switch ($filter_type) {
        case 'daily':
            $start_date = $end_date;
            break;
        case 'last7days':
            $start_date = $today->modify('-6 days')->format('Y-m-d');
            break;
        case 'last15days':
            $start_date = $today->modify('-14 days')->format('Y-m-d');
            break;
        case 'last30days':
            $start_date = $today->modify('-29 days')->format('Y-m-d');
            break;
    }
} elseif ($start_date && $end_date) {
    $use_manual_dates = true;
}

// Fetch registration data
$fetch_all = empty($filter_type) && empty($start_date) && empty($end_date);
if ($fetch_all || ($start_date && $end_date)) {
    if (!$fetch_all) {
        $start = DateTime::createFromFormat('Y-m-d', $start_date);
        $end = DateTime::createFromFormat('Y-m-d', $end_date);
        if (!$start || !$end) {
            $error = 'Invalid date format.';
            $start_date = $end_date = '';
        } elseif ($start > $end) {
            $error = 'End date must be after start date.';
            $start_date = $end_date = '';
        }
    }

    if (!$error || $fetch_all) {
        if (function_exists('getUserRegistrations')) {
            $result = getUserRegistrations($fetch_all ? '' : $start_date, $fetch_all ? '' : $end_date);
            if ($result === false) {
                $error = 'Failed to fetch user registration data: ' . mysqli_error($con);
            } else {
                while ($row = mysqli_fetch_assoc($result)) {
                    $registration_data[] = [
                        'period' => $row['period'],
                        'registration_count' => (int)$row['registration_count']
                    ];
                }
            }
        } else {
            $error = 'User registration tracking is not implemented yet.';
        }
    }
}

// Apply sorting
if (function_exists('sortItem')) {
    $registration_data = sortItem($registration_data, $sort, $order);
} else {
    $error = 'Sorting functionality is not implemented yet.';
}
?>

<div class="main-content1">
    <div class="container py-4">
        <h2 class="mb-4">Admin Dashboard</h2>

        <!-- Dashboard Cards -->
        <div class="content mb-5">
            <div class="row g-4">
                <div class="col-md-3">
                    <a href="users.php" class="text-decoration-none">
                        <div class="card bg-gradient-dark text-white p-3">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <h5 class="text-white">Total Users</h5>
                            <p class="mb-0"><?php echo htmlspecialchars($total_users); ?></p>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="menu.php" class="text-decoration-none">
                        <div class="card bg-gradient-dark text-white p-3">
                            <i class="fas fa-utensils fa-2x mb-2"></i>
                            <h5 class="text-white">Total Menu</h5>
                            <p class="mb-0"><?php echo htmlspecialchars($total_menus); ?></p>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="food_items.php" class="text-decoration-none">
                        <div class="card bg-gradient-dark text-white p-3">
                            <i class="fas fa-hamburger fa-2x mb-2"></i>
                            <h5 class="text-white">Total Food Items</h5>
                            <p class="mb-0"><?php echo htmlspecialchars($total_food_items); ?></p>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="delivered.php" class="text-decoration-none">
                        <div class="card bg-gradient-dark text-white p-3">
                            <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                            <h5 class="text-white">Delivered Orders</h5>
                            <p class="mb-0"><?php echo htmlspecialchars($total_orders); ?></p>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="total_sales.php" class="text-decoration-none">
                        <div class="card bg-gradient-dark text-white p-3">
                            <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                            <h5 class="text-white">Total Revenue</h5>
                            <p class="mb-0">Rs <?php echo number_format($total_revenue, 2); ?></p>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="orders.php" class="text-decoration-none">
                        <div class="card bg-gradient-dark text-white p-3">
                            <i class="fas fa-hourglass-half fa-2x mb-2"></i>
                            <h5 class="text-white">Pending Orders</h5>
                            <p class="mb-0"><?php echo htmlspecialchars($pending_orders); ?></p>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="total_sales.php#popular_menu" class="text-decoration-none">
                        <div class="card bg-gradient-dark text-white p-3">
                            <i class="fas fa-star fa-2x mb-2"></i>
                            <h5 class="text-white">Top Selling Item</h5>
                            <p class="mb-0"><?php echo htmlspecialchars($top_selling_item['name']) . ' (' . htmlspecialchars($top_selling_item['quantity']) . ')'; ?></p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- User Registrations Section -->
        <h3 class="mt-5 mb-3">User Registrations</h3>
        <?php if ($error) { ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>
        <form method="GET" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label for="filter_type" class="form-label">Filter Type</label>
                    <select name="filter_type" id="filter_type" class="form-select" onchange="updateDateInputs(this.value)">
                        <option value="" <?= $filter_type == '' ? 'selected' : '' ?>>All Data</option>
                        <option value="daily" <?= $filter_type == 'daily' ? 'selected' : '' ?>>Daily</option>
                        <option value="last7days" <?= $filter_type == 'last7days' ? 'selected' : '' ?>>Last 7 Days</option>
                        <option value="last15days" <?= $filter_type == 'last15days' ? 'selected' : '' ?>>Last 15 Days</option>
                        <option value="last30days" <?= $filter_type == 'last30days' ? 'selected' : '' ?>>Last 30 Days</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="<?= htmlspecialchars($use_manual_dates ? $start_date : '') ?>">
                </div>
                <div class="col-md-2">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="<?= htmlspecialchars($use_manual_dates ? $end_date : '') ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-2"></i>Filter</button>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-secondary w-100" onclick="window.location.href='?sort=<?= htmlspecialchars($sort) ?>&order=<?= htmlspecialchars($order) ?>&filter_type='"><i class="fas fa-calendar-times me-2"></i>Clear Dates</button>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-primary w-100" onclick="window.location.href='?sort=period&order=DESC&filter_type='"><i class="fas fa-sync-alt me-2"></i>Reset</button>
                </div>
            </div>
            <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
            <input type="hidden" name="order" value="<?= htmlspecialchars($order) ?>">
        </form>

        <!-- User Registrations Chart -->
        <div class="mb-4">
            <canvas id="registrationChart" height="100"></canvas>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">
                            <a href="?sort=period&order=<?= ($sort == 'period' && $order == 'ASC') ? 'DESC' : 'ASC' ?>&filter_type=<?= htmlspecialchars($filter_type) ?>&start_date=<?= htmlspecialchars($start_date) ?>&end_date=<?= htmlspecialchars($end_date) ?>" class="text-white text-decoration-none">
                                Period <?= $sort == 'period' ? ($order == 'ASC' ? '<i class="fas fa-sort-up"></i>' : '<i class="fas fa-sort-down"></i>') : '<i class="fas fa-sort"></i>' ?>
                            </a>
                        </th>
                        <th scope="col">
                            <a href="?sort=registration_count&order=<?= ($sort == 'registration_count' && $order == 'ASC') ? 'DESC' : 'ASC' ?>&filter_type=<?= htmlspecialchars($filter_type) ?>&start_date=<?= htmlspecialchars($start_date) ?>&end_date=<?= htmlspecialchars($end_date) ?>" class="text-white text-decoration-none">
                                Registration Count <?= $sort == 'registration_count' ? ($order == 'ASC' ? '<i class="fas fa-sort-up"></i>' : '<i class="fas fa-sort-down"></i>') : '<i class="fas fa-sort"></i>' ?>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($registration_data)) { ?>
                        <?php foreach ($registration_data as $item) { ?>
                            <tr>
                                <td><?= htmlspecialchars($item['period']) ?></td>
                                <td><?= htmlspecialchars($item['registration_count']) ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="2" class="text-center py-4">No User Registration Data Found</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
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
    .main-content1 {
        padding: 20px;
        min-height: 100vh;
        box-sizing: border-box;
    }

    /* Optional: Increase card spacing further */
    .card {
        margin-bottom: 15px; /* Adds vertical spacing between cards */
    }

    @media (max-width: 767.98px) {
        .main-content1 {
            margin-left: 0; /* Remove margin on smaller screens */
        }
    }

    /* Gradient background for cards */
    .bg-gradient-dark {
        background: linear-gradient(135deg, #343a40, #495057);
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuLink = document.getElementById('menu-link');
        const foodItemsLink = document.getElementById('food-items-link');
        const addMenuItem = document.querySelector('.add-menu-item');
        const addFoodItem = document.querySelector('.add-food-item');

        if (menuLink) {
            menuLink.addEventListener('click', function (e) {
                addMenuItem.style.display = 'block';
                addFoodItem.style.display = 'none';
            });
        }

        if (foodItemsLink) {
            foodItemsLink.addEventListener('click', function (e) {
                addFoodItem.style.display = 'block';
                addMenuItem.style.display = 'none';
            });
        }

        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            if (link !== menuLink && link !== foodItemsLink) {
                link.addEventListener('click', function () {
                    addMenuItem.style.display = 'none';
                    addFoodItem.style.display = 'none';
                });
            }
        });

        // Update date inputs based on filter type
        function updateDateInputs(filterType) {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const today = new Date();
            let startDate, endDate;

            switch (filterType) {
                case 'daily':
                    startDate = endDate = today.toISOString().split('T')[0];
                    break;
                case 'last7days':
                    endDate = today.toISOString().split('T')[0];
                    startDate = new Date(today);
                    startDate.setDate(today.getDate() - 6);
                    startDate = startDate.toISOString().split('T')[0];
                    break;
                case 'last15days':
                    endDate = today.toISOString().split('T')[0];
                    startDate = new Date(today);
                    startDate.setDate(today.getDate() - 14);
                    startDate = startDate.toISOString().split('T')[0];
                    break;
                case 'last30days':
                    endDate = today.toISOString().split('T')[0];
                    startDate = new Date(today);
                    startDate.setDate(today.getDate() - 29);
                    startDate = startDate.toISOString().split('T')[0];
                    break;
                default:
                    startDate = endDate = '';
                    break;
            }

            startDateInput.value = startDate;
            endDateInput.value = endDate;
        }

        // Initialize date inputs
        const filterType = document.getElementById('filter_type').value;
        if (filterType) {
            updateDateInputs(filterType);
        }

        // User Registrations Chart
        const registrationData = <?php echo json_encode($registration_data); ?>;
        const registrationChartCtx = document.getElementById('registrationChart').getContext('2d');
        new Chart(registrationChartCtx, {
            type: 'line',
            data: {
                labels: registrationData.map(item => item.period),
                datasets: [{
                    label: 'Registration Count',
                    data: registrationData.map(item => item.registration_count),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Registration Count' }
                    },
                    x: {
                        title: { display: true, text: 'Period' }
                    }
                },
                plugins: {
                    legend: { display: true },
                    title: { display: true, text: 'User Registrations Over Time' }
                }
            }
        });
    });
</script>