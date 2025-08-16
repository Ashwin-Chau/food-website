<?php
include('functions/userfunctions.php');
include('includes/header.php');
include('functions/authenticate.php');


$cartItems = getCartItems();

if(mysqli_num_rows($cartItems) == 0 )
{
    header('Location: index.php');
}
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
                                    <span id="nameErr" class="text-danger name"></span>
                                </div>
                                <div class="form-group">
                                    <label class="fw-bold">Email</label>
                                    <input type="email" name="email" placeholder="Enter your email" class="input-field"
                                        id="email" onblur="emailValidation('email','emailErr')" required>
                                    <span id="emailErr" class="text-danger email"></span>
                                </div>
                                <div class="form-group">
                                    <label class="fw-bold">Phone</label>
                                    <input type="text" name="phone" placeholder="Enter your phone number" class="input-field"
                                        id="phone" oninput="limitContactLength(this)" onblur="contactValidation('phone','phoneErr')" required>
                                    <span id="phoneErr" class="text-danger phone"></span>
                                </div>
                                <div class="form-group">
                                    <label class="fw-bold">Zipcode</label>
                                    <input type="text" name="zipcode" placeholder="Enter your area pin code" class="input-field"
                                        id="zipcode" oninput="limitContactLength(this)" required>
                                    <span id="zipcodeErr" class="text-danger zipcode"></span>
                                </div>
                                <div class="form-group full-width">
                                    <label class="fw-bold">Address</label>
                                    <input type="text" name="address" class="input-field" id="address"
                                        onblur="addressValidation('address','addressErr')" placeholder="Address" required>
                                    <span id="addressErr" class="text-danger address"></span>
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
                                <div id="paypal-button-container" style="margin-top: 15px;"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/validation.js"></script>
<script src="https://www.paypal.com/sdk/js?client-id=AaaWFm718GEbZls-NpksMZh8cz86UTf2a_cSGgsh284E_FW_YS56HcVWbxsNYFu3gZ1Z9V5t7UUydqsG&currency=USD"></script>
<script>
    
    paypal.Buttons({
        onClick(){
            var name = $('#name').val();
            var email = $('#email').val();
            var phone = $('#phone').val();
            var zipcode = $('#zipcode').val();
            var address = $('#address').val();

            if(name.length == 0)
            {
                $('.name').text("*This field is required")             
            } else{
                $('.name').text("")
            }

            if(email.length == 0)
            {
                $('.email').text("*This field is required")                
            } else{
                $('.email').text("")
            }

            if(phone.length == 0)
            {
                $('.phone').text("*This field is required")               
            } else{
                $('.phone').text("")
            }

            if(zipcode.length == 0)
            {
                $('.zipcode').text("*This field is required")                
            } else{
                $('.zipcode').text("")
            }

            if(address.length == 0)
            {
                $('.address').text("*This field is required")              
            } else{
                $('.address').text("")
            }

            if(name.length == 0 || email.length == 0 || phone.length == 0 || zipcode.length == 0 || address.length == 0)
            {
                return false;
            }
        },
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '0.2'//'<?= $totalPrice ?>' // Pass the total price dynamically
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(orderData) {

                // console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                const transaction = orderData.purchase_units[0].payments.captures[0];
                // alertify.success(`PayPal Transaction ${transaction.status}: ${transaction.id}`);
                // alert(`Transaction ${transaction.status}: ${transaction.id}\n\nSee console for all available details`);

                var name = $('#name').val();
                var email = $('#email').val();
                var phone = $('#phone').val();
                var zipcode = $('#zipcode').val();
                var address = $('#address').val();

                var data = {
                    'name': name,
                    'email': email,
                    'phone': phone,
                    'zipcode': zipcode,
                    'address': address,
                    'payment_mode': "Paid by PayPal",
                    'payment_id': transaction.id,
                    'placeOrderBtn': true
                }

                $.ajax({
                    method: "POST",
                    url: "functions/placeorder.php",
                    data: data,
                    // success: function (response) {
                    //     if(response == 201)
                    //     {
                    //         alertify.success("Order Place Successfully");
                    //         // actions.redirect('my_orders.php')
                    //         window.location.href = 'my_orders.php'
                    //     }
                    // }
                    success: function (response) {
                        if (response == 201) {
                            alertify.success("Payment successful! Redirecting to your orders...");
                            setTimeout(function() {
                                window.location.href = 'my_orders.php';
                            }, 2000); // 2-second delay
                        } else {
                            alertify.error("Something went wrong while placing your order.");
                        }
                    }

                })
            });
        }
    }).render('#paypal-button-container');
</script>

<?php include('includes/footer.php'); ?>