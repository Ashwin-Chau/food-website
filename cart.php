<?php 
include('functions/userfunctions.php');
include('includes/header.php'); 
include('functions/authenticate.php'); 
?>


<div class="cart-section py-5">
    <div class="container">
        <div class="cart-wrapper">
            <div class="cart-content">
                <div id="mycart">
                    <?php
                    $items = getCartItems();
                    if (mysqli_num_rows($items) > 0) {
                    ?>
                        <div class="cart-header align-items-center">
                            <div class="cart-col-package">
                                <h2>Food Name</h2>
                            </div>
                            <div class="cart-col-price">
                                <h2>Price</h2>
                            </div>
                            <div class="cart-col-month">
                                <h2>Quantity</h2>
                            </div>
                            <div class="cart-col-remove">
                                <h2>Remove</h2>
                            </div>
                        </div>

                        <div class="cart-items">
                            <?php                  
                            foreach ($items as $citem) {
                            ?>
                                <div class="cart-item shadow-sm mb-3 food_items_data">
                                    <div class="cart-item-row align-items-center">
                                        <div class="cart-col-package">
                                            <h5><?= htmlspecialchars($citem['name']) ?></h5>
                                        </div>
                                        <div class="cart-col-price">
                                            <h5>Rs <?= htmlspecialchars($citem['price']) ?></h5>
                                        </div>
                                        <div class="cart-col-month">
                                            <input type="hidden" class="food_items_id" value="<?= htmlspecialchars($citem['food_items_id']) ?>">
                                            <div class="month-input-group" aria-live="polite">
                                                <button class="month-btn decrement-btn updateQuantity" aria-label="Decrease quantity">-</button>
                                                    <input type="number" class="month-input text-center input-quantity updateQuantity"
                                                        value="<?= htmlspecialchars($citem['quantity']) ?>" min="1"
                                                        data-max-quantity="<?= htmlspecialchars($citem['available_quantity']) ?>" aria-label="Quantity">
                                                <button class="month-btn increment-btn updateQuantity" aria-label="Increase quantity">+</button>
                                            </div>
                                        </div>
                                        <div class="cart-col-remove">
                                            <button class="delete-btn deleteCartItem" value="<?= htmlspecialchars($citem['cid']) ?>">
                                                <i class="fa fa-trash me-2"></i>Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>

                        <div class="cart-checkout">
                            <a href="checkout.php" class="checkout-btn">Proceed to checkout</a>
                        </div>
                    <?php
                    } else {
                    ?>
                        <div class="empty-cart shadow text-center">
                            <h4 class="py-3">Your cart is empty</h4>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>