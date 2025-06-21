// Limit contact number length to 10
function limitContactLength(input) {
    if (input.value.length > 10) {
        input.value = input.value.slice(0, 10);
    }
}

//----------------------------------------------------------------------------------------------
//Validating records for Add User from admin dashboard
//----------------------------------------------------------------------------------------------

function validateAddUser() {
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
//Validating records for Edit User from admin dashboard
//----------------------------------------------------------------------------------------------

function validateEditUser() {
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
//Validating records for Add Menu from admin dashboard
//----------------------------------------------------------------------------------------------

function validateAddMenu() {
    var isCnameValid = cnameValidation('name', 'nameErr');
    var isSlugValid = slugValidation('slug', 'slugErr');
    var isMtitleValid = mtitleValidation('meta_title', 'mtitleErr');
    

    // Check if all validations pass
    if (isCnameValid && isSlugValid  && isMtitleValid) {
        // All validations passed, allow form submission
        return true;
    } else {
        // At least one validation failed, prevent form submission
        return false;
    }
}

//----------------------------------------------------------------------------------------------
//Validating records for Edit Menu from admin dashboard
//----------------------------------------------------------------------------------------------

function validateEditMenu() {
    var isCnameValid = cnameValidation('name', 'nameErr');
    var isSlugValid = slugValidation('slug', 'slugErr');
    var isMtitleValid = mtitleValidation('meta_title', 'mtitleErr');
    

    // Check if all validations pass
    if (isCnameValid && isSlugValid  && isMtitleValid) {
        // All validations passed, allow form submission
        return true;
    } else {
        // At least one validation failed, prevent form submission
        return false;
    }
}

//----------------------------------------------------------------------------------------------
//Validating records for Add Food Items from admin dashboard
//----------------------------------------------------------------------------------------------

function validateAddFoodItems() {
    var isPnameValid = pnameValidation('name', 'nameErr');
    var isSlugValid = slugValidation('slug', 'slugErr');
    var isOpriceValid = opriceValidation('original_price', 'opriceErr');
    var isSpriceValid = spriceValidation('selling_price', 'spriceErr');
    

    // Check if all validations pass
    if (isPnameValid && isSlugValid  && isOpriceValid && isSpriceValid) {
        // All validations passed, allow form submission
        return true;
    } else {
        // At least one validation failed, prevent form submission
        return false;
    }
}

//----------------------------------------------------------------------------------------------
//Validating records for Edit Food Items from admin dashboard
//----------------------------------------------------------------------------------------------

function validateEditFoodItems() {
    var isPnameValid = pnameValidation('name', 'nameErr');
    var isSlugValid = slugValidation('slug', 'slugErr');
    var isOpriceValid = opriceValidation('original_price', 'opriceErr');
    var isSpriceValid = spriceValidation('selling_price', 'spriceErr');
    

    // Check if all validations pass
    if (isPnameValid && isSlugValid  && isOpriceValid && isSpriceValid) {
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

function cnameValidation(inputId, errorId) {
    var name = document.getElementById(inputId).value;
    var errorId = document.getElementById(errorId);
    errorId.innerHTML = "";
    const nameRegex = /^[a-zA-Z ]{3,}$/;
    if (!nameRegex.test(name)) {
            
        if (name.length < 3) {
            errorId.innerHTML = "Classes name is too short";
        }
        else {
            errorId.innerHTML = "Invalid class name";
        }
        return false;
        
    }
    return true;
}

function pnameValidation(inputId, errorId) {
    var name = document.getElementById(inputId).value;
    var errorId = document.getElementById(errorId);
    errorId.innerHTML = "";
    const nameRegex = /^[a-zA-Z ]{4,}$/;
    if (!nameRegex.test(name)) {
            
        if (name.length < 4) {
            errorId.innerHTML = "Packages name is too short";
        }
        else {
            errorId.innerHTML = "Invalid package name";
        }
        return false;
        
    }
    return true;
}

function slugValidation(inputId, errorId) {
    var name = document.getElementById(inputId).value;
    var errorId = document.getElementById(errorId);
    errorId.innerHTML = "";
    const nameRegex = /^[a-zA-Z\- ]{3,}$/;
    if (!nameRegex.test(name)) {
            
        if (name.length < 3) {
            errorId.innerHTML = "Slug is too short";
        }
        else {
            errorId.innerHTML = "Invalid slug";
        }
        return false;
        
    }
    return true;
}

function mtitleValidation(inputId, errorId) {
    var name = document.getElementById(inputId).value;
    var errorId = document.getElementById(errorId);
    errorId.innerHTML = "";
    const nameRegex = /^[a-zA-Z ]{4,}$/;
    if (!nameRegex.test(name)) {
            
        if (name.length < 4) {
            errorId.innerHTML = "Meta title is too short";
        }
        else {
            errorId.innerHTML = "Invalid meta title";
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

function opriceValidation(inputId, errorId) {
    var price = document.getElementById(inputId).value;
    var errorId = document.getElementById(errorId);
    errorId.innerHTML = "";
    const priceRegex = /^[0-9]+(\.[0-9]{1,2})?$/;
    if (!priceRegex.test(price)) {
        errorId.innerHTML = "Enter number only";
        return false;
    }
    return true;
}

function spriceValidation(inputId, errorId) {
    var price = document.getElementById(inputId).value;
    var errorId = document.getElementById(errorId);
    errorId.innerHTML = "";
    const priceRegex = /^[0-9]+(\.[0-9]{1,2})?$/;
    if (!priceRegex.test(price)) {
        errorId.innerHTML = "Enter number only";
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

function updateWordCount(inputId1, inputId2) {
    var messageTextarea = document.getElementById(inputId1);
    var displayCount = document.getElementById(inputId2);

    var message = messageTextarea.value.trim();
    var words = message.split(/\s+/);

    var wordCount = words.length;
    displayCount.innerHTML = wordCount + "/150";
}


function validateMessage(inputId1, errorId) {
    var messageTextarea = document.getElementById(inputId1);
    var errorElement = document.getElementById(errorId);

    var message = messageTextarea.value.trim();
    var words = message.split(/\s+/);

    var wordCount = words.length;
    errorElement.innerHTML = "";

    if (message === "") {
        errorElement.innerHTML = "Message cannot be empty";
        return false;
    }
    if (wordCount < 25) {
        errorElement.innerHTML = "Must be greater than 25 characters";
        return false;
    }
    if (wordCount > 150) {
        errorElement.innerHTML = "Cannot exceed 150 characters";
        return false;
    }
    return true;
}





