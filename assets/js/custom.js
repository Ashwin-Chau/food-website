$(document).ready(function() {
    console.log('food_details.js loaded');

    // Ensure jQuery is loaded
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded.');
        return;
    }

    // Ensure alertify is loaded
    if (typeof alertify === 'undefined') {
        console.warn('Alertify is not loaded. Notifications will be suppressed.');
    }

    // Verify DOM elements
    console.log('Increment buttons found:', $('.input-group-custom .increment-btn, .month-input-group .increment-btn').length);
    console.log('Decrement buttons found:', $('.input-group-custom .decrement-btn, .month-input-group .decrement-btn').length);
    console.log('Add to cart buttons found:', $('.addToCartBtn').length);
    console.log('Cart update buttons found:', $('.updateQuantity').length);
    console.log('Cart delete buttons found:', $('.deleteCartItem').length);

    // Function to validate and correct input quantity (shared for both food details and cart)
    function validateInputQuantity($input) {
        var maxQuantity = parseInt($input.data('max-quantity')) || 0;
        var currentQuantity = parseInt($input.val()) || 1;

        if (isNaN(currentQuantity) || currentQuantity < 1) {
            console.log('Invalid input, setting to 1');
            $input.val(1);
            currentQuantity = 1;
        } else if (currentQuantity > maxQuantity && maxQuantity > 0) {
            console.log('Input exceeds stock, setting to max:', maxQuantity);
            $input.val(maxQuantity);
            if (typeof alertify !== 'undefined') {
                alertify.error('Cannot exceed available stock (' + maxQuantity + ').');
            } else {
                alert('Cannot exceed available stock (' + maxQuantity + ').');
            }
            currentQuantity = maxQuantity;
        }

        // Update button states based on context (food details or cart)
        var $inputGroup = $input.closest('.input-group-custom, .month-input-group');
        if ($inputGroup.hasClass('input-group-custom')) {
            updateDetailButtonStates($inputGroup);
        } else if ($inputGroup.hasClass('month-input-group')) {
            updateCartButtonStates($inputGroup.closest('.cart-item-row'));
        }
        return currentQuantity;
    }

    // Handle manual input in quantity field (both food details and cart)
    $(document).on('input', '.input-quantity', function() {
        console.log('Quantity input changed');
        var $input = $(this);
        validateInputQuantity($input);
    });

    // Handle blur to ensure valid input after user finishes typing (both food details and cart)
    $(document).on('blur', '.input-quantity', function() {
        console.log('Quantity input blurred');
        var $input = $(this);
        var newQuantity = validateInputQuantity($input);

        // If in cart, trigger AJAX update on blur
        if ($input.closest('.month-input-group').length) {
            var $cartItem = $input.closest('.cart-item-row');
            var food_items_id = $cartItem.find('.food_items_id').val();
            var maxQuantity = parseInt($input.data('max-quantity')) || Infinity;

            if (newQuantity !== parseInt($input.data('last-valid-quantity') || newQuantity)) {
                console.log('Triggering cart update on blur, quantity:', newQuantity);
                $cartItem.find('.increment-btn, .decrement-btn').prop('disabled', true);

                $.ajax({
                    method: "POST",
                    url: "functions/handlecart.php",
                    data: {
                        food_items_id: food_items_id,
                        quantity: newQuantity,
                        scope: "update"
                    },
                    dataType: "json",
                    success: function(response) {
                        console.log('Cart update response:', response);
                        if (response.status == 200) {
                            if (typeof alertify !== 'undefined') {
                                alertify.success(response.message);
                            } else {
                                alert(response.message);
                            }
                            $input.data('last-valid-quantity', newQuantity);
                            if ($('#mycart').length) {
                                $('#mycart').load(location.href + " #mycart", function() {
                                    $cartItem.find('.input-quantity').val(newQuantity);
                                    updateCartButtonStates($cartItem);
                                });
                            }
                        } else {
                            if (typeof alertify !== 'undefined') {
                                alertify.error(response.message);
                            } else {
                                alert(response.message);
                            }
                            $input.val($input.data('last-valid-quantity') || 1);
                            updateCartButtonStates($cartItem);
                        }
                        $cartItem.find('.increment-btn, .decrement-btn').prop('disabled', false);
                    },
                    error: function(xhr, status, error) {
                        console.error('Cart update AJAX error:', status, error);
                        if (typeof alertify !== 'undefined') {
                            alertify.error('Error communicating with server: ' + error);
                        } else {
                            alert('Error communicating with server: ' + error);
                        }
                        $input.val($input.data('last-valid-quantity') || 1);
                        updateCartButtonStates($cartItem);
                        $cartItem.find('.increment-btn, .decrement-btn').prop('disabled', false);
                    }
                });
            }
        }
    });

    // Increment button (Food Detail Page)
    $(document).on('click', '.input-group-custom .increment-btn:not(:disabled)', function() {
        console.log('Increment button clicked (food details)');
        var $inputGroup = $(this).closest('.input-group-custom');
        var $input = $inputGroup.find('.input-quantity');
        var currentQuantity = parseInt($input.val()) || 1;
        var maxQuantity = parseInt($input.data('max-quantity')) || 0;

        if (maxQuantity === 0) {
            console.log('Out of stock, cannot increment');
            if (typeof alertify !== 'undefined') {
                alertify.error('Item is out of stock.');
            } else {
                alert('Item is out of stock.');
            }
            return;
        }

        if (currentQuantity < maxQuantity) {
            $input.val(currentQuantity + 1);
            console.log('Incremented to:', currentQuantity + 1);
            updateDetailButtonStates($inputGroup);
        } else {
            console.log('Max quantity reached:', maxQuantity);
            if (typeof alertify !== 'undefined') {
                alertify.error('Cannot exceed available stock (' + maxQuantity + ').');
            } else {
                alert('Cannot exceed available stock (' + maxQuantity + ').');
            }
        }
    });

    // Decrement button (Food Detail Page)
    $(document).on('click', '.input-group-custom .decrement-btn:not(:disabled)', function() {
        console.log('Decrement button clicked (food details)');
        var $inputGroup = $(this).closest('.input-group-custom');
        var $input = $inputGroup.find('.input-quantity');
        var currentQuantity = parseInt($input.val()) || 1;

        if (currentQuantity > 1) {
            $input.val(currentQuantity - 1);
            console.log('Decremented to:', currentQuantity - 1);
            updateDetailButtonStates($inputGroup);
        } else {
            console.log('Minimum quantity (1) reached');
        }
    });

    // Function to update button states (Food Detail Page)
    function updateDetailButtonStates($inputGroup) {
        var $input = $inputGroup.find('.input-quantity');
        var currentQuantity = parseInt($input.val()) || 1;
        var maxQuantity = parseInt($input.data('max-quantity')) || 0;
        var $decrementBtn = $inputGroup.find('.decrement-btn');
        var $incrementBtn = $inputGroup.find('.increment-btn');

        console.log('Updating detail button states: Quantity=', currentQuantity, 'Max=', maxQuantity);

        $decrementBtn.prop('disabled', currentQuantity <= 1);
        $incrementBtn.prop('disabled', currentQuantity >= maxQuantity || maxQuantity === 0);
    }

    // Initialize button states on page load (Food Detail Page)
    $('.input-group-custom').each(function() {
        var $inputGroup = $(this);
        var $input = $inputGroup.find('.input-quantity');
        var maxQuantity = parseInt($input.data('max-quantity')) || 0;
        var currentQuantity = parseInt($input.val()) || 1;

        console.log('Initializing detail: Quantity=', currentQuantity, 'Max=', maxQuantity);

        if (maxQuantity === 0 || currentQuantity < 1) {
            $input.val(1);
        } else if (currentQuantity > maxQuantity) {
            $input.val(maxQuantity);
        }

        updateDetailButtonStates($inputGroup);
    });

    // Add to Cart
    $(document).on('click', '.addToCartBtn:not(:disabled)', function(e) {
        e.preventDefault();
        console.log('Add to cart button clicked');
        var $foodItem = $(this).closest('.food_items_data');
        var $input = $foodItem.find('.input-quantity');
        var quantity = parseInt($input.val());
        var maxQuantity = parseInt($input.data('max-quantity')) || Infinity;
        var food_items_id = $(this).val();

        if (isNaN(quantity) || quantity < 1) {
            console.log('Invalid quantity:', quantity);
            if (typeof alertify !== 'undefined') {
                alertify.error('Invalid quantity.');
            } else {
                alert('Invalid quantity.');
            }
            $input.val(1);
            updateDetailButtonStates($foodItem.find('.input-group-custom'));
            return;
        }

        if (quantity > maxQuantity) {
            console.log('Quantity exceeds stock:', quantity, maxQuantity);
            if (typeof alertify !== 'undefined') {
                alertify.error('Cannot add more than available stock (' + maxQuantity + ').');
            } else {
                alert('Cannot add more than available stock (' + maxQuantity + ').');
            }
            $input.val(maxQuantity);
            updateDetailButtonStates($foodItem.find('.input-group-custom'));
            return;
        }

        $.ajax({
            method: "POST",
            url: "functions/handlecart.php",
            data: {
                food_items_id: food_items_id,
                quantity: quantity,
                scope: "add"
            },
            dataType: "json",
            success: function(response) {
                console.log('Add to cart response:', response);
                if (response.status == 201 || response.status == 'existing' || response.status == 401) {
                    if (typeof alertify !== 'undefined') {
                        alertify.success(response.message);
                    } else {
                        alert(response.message);
                    }
                } else {
                    if (typeof alertify !== 'undefined') {
                        alertify.error(response.message);
                    } else {
                        alert(response.message);
                    }
                }
                if ($('#mycart').length) {
                    $('#mycart').load(location.href + " #mycart");
                }
            },
            error: function(xhr, status, error) {
                console.error('Add to cart AJAX error:', status, error);
                if (typeof alertify !== 'undefined') {
                    alertify.error('Error communicating with server: ' + error);
                } else {
                    alert('Error communicating with server: ' + error);
                }
            }
        });
    });

    // Update Quantity (Cart Widget)
    $(document).on('click', '.cart-item-row .updateQuantity', function() {
        console.log('Cart update quantity clicked');
        var $cartItem = $(this).closest('.cart-item-row');
        var $input = $cartItem.find('.input-quantity');
        var food_items_id = $cartItem.find('.food_items_id').val();
        var currentQuantity = parseInt($input.val()) || 1;
        var maxQuantity = parseInt($input.data('max-quantity')) || Infinity;
        var newQuantity;

        if ($(this).hasClass('increment-btn') && currentQuantity < maxQuantity) {
            newQuantity = currentQuantity + 1;
        } else if ($(this).hasClass('decrement-btn') && currentQuantity > 1) {
            newQuantity = currentQuantity - 1;
        } else {
            if ($(this).hasClass('increment-btn')) {
                console.log('Max quantity reached for cart item:', maxQuantity);
                if (typeof alertify !== 'undefined') {
                    alertify.error('Cannot exceed available stock (' + maxQuantity + ').');
                } else {
                    alert('Cannot exceed available stock (' + maxQuantity + ').');
                }
            }
            return;
        }

        $input.val(newQuantity);
        $input.data('last-valid-quantity', newQuantity); // Store last valid quantity
        updateCartButtonStates($cartItem);
        $cartItem.find('.increment-btn, .decrement-btn').prop('disabled', true);

        $.ajax({
            method: "POST",
            url: "functions/handlecart.php",
            data: {
                food_items_id: food_items_id,
                quantity: newQuantity,
                scope: "update"
            },
            dataType: "json",
            success: function(response) {
                console.log('Cart update response:', response);
                if (response.status == 200) {
                    if (typeof alertify !== 'undefined') {
                        alertify.success(response.message);
                    } else {
                        alert(response.message);
                    }
                    if ($('#mycart').length) {
                        $('#mycart').load(location.href + " #mycart", function() {
                            $cartItem.find('.input-quantity').val(newQuantity);
                            $cartItem.find('.input-quantity').data('last-valid-quantity', newQuantity);
                            updateCartButtonStates($cartItem);
                        });
                    }
                } else {
                    if (typeof alertify !== 'undefined') {
                        alertify.error(response.message);
                    } else {
                        alert(response.message);
                    }
                    $input.val($input.data('last-valid-quantity') || currentQuantity);
                    updateCartButtonStates($cartItem);
                }
                $cartItem.find('.increment-btn, .decrement-btn').prop('disabled', false);
            },
            error: function(xhr, status, error) {
                console.error('Cart update AJAX error:', status, error);
                if (typeof alertify !== 'undefined') {
                    alertify.error('Error communicating with server: ' + error);
                } else {
                    alert('Error communicating with server: ' + error);
                }
                $input.val($input.data('last-valid-quantity') || currentQuantity);
                updateCartButtonStates($cartItem);
                $cartItem.find('.increment-btn, .decrement-btn').prop('disabled', false);
            }
        });
    });

    // Delete Cart Item (Cart Widget)
    $(document).on('click', '.cart-item-row .deleteCartItem', function() {
        console.log('Delete cart item clicked');
        var cart_id = $(this).val();
        $.ajax({
            method: "POST",
            url: "functions/handlecart.php",
            data: {
                cart_id: cart_id,
                scope: "delete"
            },
            dataType: "json",
            success: function(response) {
                console.log('Cart delete response:', response);
                if (response.status == 200) {
                    if (typeof alertify !== 'undefined') {
                        alertify.success(response.message);
                    } else {
                        alert(response.message);
                    }
                    if ($('#mycart').length) {
                        $('#mycart').load(location.href + " #mycart");
                    }
                } else {
                    if (typeof alertify !== 'undefined') {
                        alertify.error(response.message);
                    } else {
                        alert(response.message);
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Cart delete AJAX error:', status, error);
                if (typeof alertify !== 'undefined') {
                    alertify.error('Error communicating with server: ' + error);
                } else {
                    alert('Error communicating with server: ' + error);
                }
            }
        });
    });

    // Update button states (Cart Widget)
    function updateCartButtonStates($cartItem) {
        var $input = $cartItem.find('.input-quantity');
        var currentQuantity = parseInt($input.val()) || 1;
        var maxQuantity = parseInt($input.data('max-quantity')) || Infinity;
        var $decrementBtn = $cartItem.find('.decrement-btn');
        var $incrementBtn = $cartItem.find('.increment-btn');
        console.log('Updating cart button states: Quantity=', currentQuantity, 'Max=', maxQuantity);
        $decrementBtn.prop('disabled', currentQuantity <= 1);
        $incrementBtn.prop('disabled', currentQuantity >= maxQuantity);
    }

    // Initialize cart button states and store initial quantity
    $('.cart-item-row').each(function() {
        var $cartItem = $(this);
        var $input = $cartItem.find('.input-quantity');
        var currentQuantity = parseInt($input.val()) || 1;
        $input.data('last-valid-quantity', currentQuantity); // Store initial valid quantity
        updateCartButtonStates($cartItem);
    });
});