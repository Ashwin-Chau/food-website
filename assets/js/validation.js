// Limit contact number length to 10
function limitContactLength(input) {
    if (input.value.length > 10) {
        input.value = input.value.slice(0, 10);
    }
}

//----------------------------------------------------------------------------------------------
//Validating records for registration form
//----------------------------------------------------------------------------------------------

function validateUserRegister() {
    var isNameValid = nameValidation('name', 'nameErr');
    var isEmailValid = emailValidation('email', 'emailErr');
    var isPasswordValid = passwordValidation('password', 'passwordErr');
    var isPassMatch = checkPass('cpassword', 'password', 'cpassErr');

    // Check if all validations pass
    if (isNameValid && isEmailValid  && isPasswordValid && isPassMatch) {
        // All validations passed, allow form submission
        return true;
    } else {
        // At least one validation failed, prevent form submission
        return false;
    }
}

//----------------------------------------------------------------------------------------------
//Validating records for reset password form
//----------------------------------------------------------------------------------------------

function validateResetPassword() {
    var isPasswordValid = passwordValidation('password', 'passwordErr');
    var isPassMatch = checkPass('cpassword', 'password', 'cpassErr');

    // Check if all validations pass
    if (isPasswordValid && isPassMatch) {
        // All validations passed, allow form submission
        return true;
    } else {
        // At least one validation failed, prevent form submission
        return false;
    }
}

//----------------------------------------------------------------------------------------------
//Validating records for reset password form
//----------------------------------------------------------------------------------------------

function validateChangePassword() {
    var isPasswordValid = passwordValidation('new_password','passwordErr');
    var isPassMatch = checkPass('confirm_password','new_password','cpassErr');

    // Check if all validations pass
    if (isPasswordValid && isPassMatch) {
        // All validations passed, allow form submission
        return true;
    } else {
        // At least one validation failed, prevent form submission
        return false;
    }
}

//----------------------------------------------------------------------------------------------
//Validating records for Checkout form
//----------------------------------------------------------------------------------------------

function validateCheckout() {
    var isNameValid = nameValidation('name', 'nameErr');
    var isEmailValid = emailValidation('email', 'emailErr');
    var isContactValid = contactValidation('contact', 'contactErr');
    var isAddressValid = addressValidation('address', 'addressErr');

    // Check if all validations pass
    if (isNameValid && isEmailValid  && isContactValid && isAddressValid ) {
        // All validations passed, allow form submission
        return true;
    } else {
        // At least one validation failed, prevent form submission
        return false;
    }
}


//----------------------------------------------------------------------------------------------
// functions to validate input fields
//----------------------------------------------------------------------------------------------

function nameValidation(inputId, errorId) {
    var name = document.getElementById(inputId).value;
    var errorId = document.getElementById(errorId);
    errorId.innerHTML = "";
    const nameRegex = /^[a-zA-Z ]{4,}$/;
    if (!nameRegex.test(name)) {
            
        if (name.length < 4) {
            errorId.innerHTML = "Name is too short";
        }
        else {
            errorId.innerHTML = "Invalid name";
        }
        return false;
        
    }
    return true;
}

function emailValidation(inputId, errorId) {
    var email = document.getElementById(inputId).value;
    var errorId = document.getElementById(errorId);
    errorId.innerHTML = "";
    const emailRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if (!emailRegex.test(email)) {
        errorId.innerHTML = "Invalid email";
        return false;
    }
    return true;
}

function addressValidation(inputId, errorId) {
    var address = document.getElementById(inputId).value;
    var errorId = document.getElementById(errorId);
    errorId.innerHTML = "";
    const addressRegex = /^(?=.*[a-zA-Z])[a-zA-Z0-9\s,'-]{4,}$/;
    if (!addressRegex.test(address)) {
        errorId.innerHTML = "Invalid address";
        return false;
    }
    return true;
}

function contactValidation(inputId, errorId) {
    var contact = document.getElementById(inputId).value;
    var errorId = document.getElementById(errorId);
    errorId.innerHTML = "";
    const contactRegex = /^(98|97)\d{8}$/;
    if (!contactRegex.test(contact)) {
        errorId.innerHTML = "Invalid contact no.";
        return false;
    }
    return true;
}

function passwordValidation(inputId, errorId) {
    const passwordRegex = /^(?=.*[A-Za-z])(?=.*[^A-Za-z0-9]).{8,25}$/;
    var password = document.getElementById(inputId).value;
    var errorId = document.getElementById(errorId);
    errorId.innerHTML = "";
    if (!passwordRegex.test(password)) {
        if (password.length < 8) {
            errorId.innerHTML = "Password must be at least 8 characters long";
        }
        else {
            errorId.innerHTML = "Password must contain at least one letter and one special character";
        }
        return false;
    }
    return true;
}

function checkPass(inputId1, inputId2, errorId) { 
    var cpass = document.getElementById(inputId1).value;
    var pass = document.getElementById(inputId2).value;
    var errorId = document.getElementById(errorId);
    errorId.innerHTML = "";
    if (pass != cpass) {
        errorId.innerHTML = "Password does not match";
        return false;
    }
    return true;
}










