<?php
include('functions/userfunctions.php');
include('includes/header.php');
include('functions/authenticate.php');

// Fetch customer details
$customer = getCustomerDetails($con); // Pass the database connection
?>

<div class="breadcrumb py-3 bg-primary">
    <div class="container">
        <h6 class="text-white">
            <a class="text-white" href="index.php">Home /</a>
            <a class="text-white" href="checkout.php">Checkout</a>
        </h6>
    </div>
</div>

<div class="checkout-section py-5">
    <div class="container">
        <div class="checkout-wrapper">
            <div class="checkout-content shadow">
                <?php if (!$customer): ?>
                    <p class="text-danger text-center">Unable to load customer details. Please fill in the form below.</p>
                <?php endif; ?>
                <form action="functions/placeorder.php" method="POST" onsubmit="return validateCheckout()">
                    <div class="checkout-row">
                        <div class="checkout-details">
                            <h2>Basic Details</h2>
                            <hr>
                            <div class="details-row">
                                <div class="form-group">
                                    <label class="fw-bold">Name</label>
                                    <input type="text" name="name" placeholder="Enter your full name" class="input-field"
                                        onblur="nameValidation('name','nameErr')" id="name" required>
                                    <span id="nameErr" class="text-danger"></span>
                                </div>
                                <div class="form-group">
                                    <label class="fw-bold">Email</label>
                                    <input type="email" name="email" placeholder="Enter your email" class="input-field"
                                        id="email" onblur="emailValidation('email','emailErr')" required>
                                    <span id="emailErr" class="text-danger"></span>
                                </div>
                                <div class="form-group">
                                    <label class="fw-bold">Phone</label>
                                    <input type="text" name="phone" placeholder="Enter your phone number" class="input-field"
                                        id="contact" oninput="limitContactLength(this)" onblur="contactValidation('contact','contactErr')" required>
                                    <span id="contactErr" class="text-danger"></span>
                                </div>
                                <div class="form-group">
                                    <label class="fw-bold">Zipcode</label>
                                    <input type="text" name="zipcode" placeholder="Enter your area pin code" class="input-field"
                                        id="zipcode" oninput="limitContactLength(this)" required>
                                </div>
                                <div class="form-group full-width">
                                    <label class="fw-bold">Address</label>
                                    <input type="text" name="address" class="input-field" id="address"
                                        onblur="addressValidation('address','addressErr')" placeholder="Address" required>
                                    <span id="addressErr" class="text-danger"></span>
                                </div>
                                <div class="form-group full-width">
                                    <label class="fw-bold">Notes (Optional)</label>
                                    <textarea name="notes" class="form-control notes-field" id="notes" placeholder="Specify any additions or removals for your order (e.g., no onions, extra cheese)"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="checkout-book-details">
                            <h2>Order Details</h2>
                            <table class="book-table">
                                <thead>
                                    <tr>
                                        <th>Food Name</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $items = getCartItems($con);
                                    $totalPrice = 0;
                                    if (!$items || mysqli_num_rows($items) == 0) {
                                        echo '<tr><td colspan="3" class="text-center">Your cart is empty.</td></tr>';
                                    } else {
                                        while ($citem = mysqli_fetch_assoc($items)) {
                                            $totalPrice += $citem['price'] * $citem['quantity'];
                                    ?>
                                        <tr>
                                            <td class="align-middle"><?= htmlspecialchars($citem['name']); ?></td>
                                            <td class="align-middle">Rs <?= number_format($citem['price']); ?></td>
                                            <td class="align-middle">x <?= htmlspecialchars($citem['quantity']); ?></td>
                                        </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <hr>
                            <h3>Total Price: <span class="total-price">Rs <?= number_format($totalPrice, 2); ?></span></h3>
                            <div class="checkout-action">
                                <input type="hidden" name="payment_mode" value="COD">
                                <button type="submit" name="placeOrderBtn" class="submit-btn">Confirm and place order | COD</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/validation.js"></script>

<?php include('includes/footer.php'); ?>