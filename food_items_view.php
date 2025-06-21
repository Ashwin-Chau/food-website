<?php 
include('functions/userfunctions.php'); 
include('includes/header.php'); 

if (!isset($_SESSION['auth'])) {
    redirect("index.php", 'Login to continue');
}

if (isset($_GET['food_items'])) {
    $food_items_slug = mysqli_real_escape_string($con, $_GET['food_items']); // Sanitize input
    $food_items_data = getSlugActive("food_items", $food_items_slug);
    $food_items = mysqli_fetch_array($food_items_data);

    if ($food_items) {
        // Use quantity field for stock check
        $in_stock = isset($food_items['quantity']) && $food_items['quantity'] > 0;
        $max_quantity = max(0, (int)$food_items['quantity']); // Ensure non-negative
?>

<style>
/* Base Styles */
body {
    font-family: 'Poppins', sans-serif;
    line-height: 1.6;
    background-color: #f4f7fa;
    color: #333;
    margin: 0;
}

/* Container */
.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
    width: 100%;
}

/* Breadcrumb Section */
.breadcrumb-section {
    background: linear-gradient(135deg, #3b82f6, #2b6cb0);
    padding: 15px 0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.breadcrumb-section h4 {
    margin: 0;
    color: #fff;
    font-size: 1rem;
    font-weight: 400;
}

.breadcrumb-section a {
    color: #fff;
    text-decoration: none;
    transition: color 0.3s;
}

.breadcrumb-section a:hover {
    color: #facc15;
}

/* Main Content */
.bg-light {
    background-color: #f9fafb;
    padding: 50px 0;
}

.row {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -15px;
}

.col-4, .col-8 {
    padding: 15px;
}

.col-4 {
    width: 33.333%;
}

.col-8 {
    width: 66.667%;
}

@media (max-width: 991px) {
    .col-4 {
        width: 40%;
    }
    .col-8 {
        width: 60%;
    }
}

@media (max-width: 768px) {
    .col-4, .col-8 {
        width: 100%;
    }
}

/* Card Styling */
.card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: transform 0.3s, box-shadow 0.3s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

.card-body1 {
    padding: 25px;
    text-align: center;
}

.card-body1 img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Food Details */
.food-details {
    padding: 25px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.food-details h4 {
    font-size: 2rem;
    font-weight: 700;
    color: #1e4976;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.food-details h4 .trending {
    font-size: 1.1rem;
    color: #dc3545;
    font-weight: 600;
    background: #ffe5e5;
    padding: 4px 12px;
    border-radius: 12px;
}

hr {
    border: 0;
    border-top: 1px solid #e5e7eb;
    margin: 20px 0;
}

/* Stock Status */
.stock-status {
    display: inline-block;
    padding: 6px 12px;
    font-size: 0.9rem;
    font-weight: 500;
    border-radius: 12px;
    margin-bottom: 15px;
}

.stock-status.in-stock {
    background: #e6f4ea;
    color: #28a745;
}

.stock-status.out-stock {
    background: #f8d7da;
    color: #dc3545;
}

/* Available Quantity */
.available-quantity {
    font-size: 0.9rem;
    color: #374151;
    margin-top: 8px;
    display: block;
}

/* Price Section */
.price-row {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 25px;
}

.price-col {
    width: 50%;
}

.price-col h4 {
    font-size: 1.6rem;
    font-weight: 600;
    color: #1e4976;
}

.price-col h4 span {
    color: #28a745;
    font-weight: 700;
}

@media (max-width: 768px) {
    .price-col {
        width: 100%;
        margin-bottom: 15px;
    }
}

/* Quantity Section */
.quantity-section {
    margin-bottom: 25px;
}

.quantity-section h4 {
    font-size: 1.3rem;
    font-weight: 500;
    color: #1e4976;
    margin-bottom: 12px;
}

.input-group-custom {
    display: flex;
    align-items: center;
    width: 140px;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.decrement-btn, .increment-btn {
    background: #007bff;
    color: #fff;
    border: none;
    width: 40px;
    height: 40px;
    font-size: 1.2rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border-radius: 6px;
    transition: background 0.3s, transform 0.2s, box-shadow 0.2s;
}

.decrement-btn:hover, .increment-btn:hover {
    background: #0056b3;
    transform: scale(1.05);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.decrement-btn:disabled, .increment-btn:disabled {
    background: #d1d5db;
    color: #6b7280;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.input-quantity {
    width: 60px;
    text-align: center;
    border: none;
    padding: 10px;
    font-size: 1.1rem;
    background: #fff;
    height: 40px;
    line-height: 1.5;
}

.input-quantity:disabled {
    background: #f1f3f5;
    color: #6b7280;
}

/* Add to Cart Button */
.add-to-cart-btn {
    display: inline-flex;
    align-items: center;
    padding: 14px 28px;
    background: #007bff;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: background 0.3s, transform 0.2s;
}

.add-to-cart-btn i {
    margin-right: 10px;
}

.add-to-cart-btn:hover {
    background: #0056b3;
    transform: scale(1.03);
}

.add-to-cart-btn:disabled {
    background: #d1d5db;
    cursor: not-allowed;
    transform: none;
}

/* Description */
.food-details h6 {
    font-size: 1.2rem;
    font-weight: 600;
    color: #1e4976;
    margin-bottom: 12px;
}

.food-details p {
    font-size: 1rem;
    color: #374151;
    line-height: 1.8;
}

/* Responsive Design */
@media (max-width: 768px) {
    .food-details h4 {
        font-size: 1.6rem;
    }

    .food-details h4 .trending {
        font-size: 1rem;
    }

    .price-col h4 {
        font-size: 1.4rem;
    }

    .quantity-section h4 {
        font-size: 1.2rem;
    }

    .input-group-custom {
        width: 140px;
    }

    .decrement-btn, .increment-btn {
        width: 36px;
        height: 36px;
        font-size: 1.1rem;
    }

    .input-quantity {
        width: 50px;
        height: 36px;
        font-size: 1rem;
    }

    .add-to-cart-btn {
        padding: 12px 24px;
        font-size: 1rem;
    }

    .card-body1 img {
        height: 200px;
    }

    .available-quantity {
        font-size: 0.85rem;
    }
}

@media (max-width: 480px) {
    .food-details h4 {
        font-size: 1.4rem;
    }

    .food-details h6 {
        font-size: 1.1rem;
    }

    .food-details p {
        font-size: 0.9rem;
    }

    .card-body1 img {
        height: 180px;
    }

    .stock-status, .available-quantity {
        font-size: 0.85rem;
    }

    .input-group-custom {
        width: 130px;
    }

    .decrement-btn, .increment-btn {
        width: 34px;
        height: 34px;
        font-size: 1rem;
    }

    .input-quantity {
        width: 48px;
        height: 34px;
        font-size: 0.95rem;
    }
}
</style>

<!-- Breadcrumb Section -->
<!-- <div class="breadcrumb-section">
    <div class="container">
        <h4>
            <a href="index.php">Home</a> /
            <a href="index.php#Menu">Menu</a> /
            <?= htmlspecialchars($food_items['name']); ?>
        </h4>
    </div>
</div> -->

<!-- Main Content -->
<div class="bg-light py-4">
    <div class="container food_items_data">
        <div class="row">
            <div class="col-4">
                <div class="card card-body1">
                    <img src="Uploads/<?= htmlspecialchars($food_items['image']); ?>" alt="<?= htmlspecialchars($food_items['name']); ?>" loading="lazy">
                </div>
            </div>
            <div class="col-8">
                <div class="food-details">
                    <h4>
                        <?= htmlspecialchars($food_items['name']); ?>
                        <?php if ($food_items['trending']) { ?>
                            <span class="trending">Trending</span>
                        <?php } ?>
                    </h4>
                    <div class="stock-status <?= $in_stock ? 'in-stock' : 'out-stock'; ?>">
                        <?= $in_stock ? 'In Stock' : 'Out of Stock'; ?>
                    </div>
                    <hr>
                    <div class="price-row">
                        <div class="price-col">
                            <h4>Rs <?= number_format($food_items['price']); ?></h4>
                        </div>
                    </div>
                    <div class="quantity-section">
                        <h4>Quantity</h4>
                        <div class="input-group-custom">
    <button class="decrement-btn" <?= !$in_stock ? 'disabled' : ''; ?>>-</button>
    <input type="number" class="input-quantity" value="1" min="1" data-max-quantity="<?= $max_quantity; ?>" <?= !$in_stock ? 'disabled' : ''; ?>>
    <button class="increment-btn" <?= !$in_stock ? 'disabled' : ''; ?>>+</button>
</div>
                        <span class="available-quantity">Available: <?= $max_quantity; ?></span>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <button class="add-to-cart-btn addToCartBtn" value="<?= $food_items['id']; ?>" <?= !$in_stock ? 'disabled' : ''; ?>>
                                <i class="fa fa-shopping-cart"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                    <hr>
                    <h6>Package Description:</h6>
                    <p><?= htmlspecialchars($food_items['description']); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/food_details.js"></script>

<?php
    } else {
        echo "Food Not Found";
    }
} else {
    echo "Something went wrong";
}

include('includes/footer.php'); 
?>