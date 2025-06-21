function toggleDropdown() {
        document.getElementById('dropdownMenu').classList.toggle('active');
    }

    function toggleMenu() {
        const navLinks = document.querySelector('.nav-links');
        const userLinks = document.querySelector('.user-links');
        const searchForm = document.querySelector('.search-form');
        const icon = document.querySelector('.icon');
        navLinks.classList.toggle('active');
        userLinks.classList.toggle('active');
        searchForm.classList.toggle('active');
        icon.classList.toggle('active');
    }

function togglePasswordVisibility(inputId, eyeIconId) {
    var inputField = document.getElementById(inputId);
    var eyeIcon = document.getElementById(eyeIconId);

    // Check if the input field type is password or text
    if (inputField.type === "password") {
        inputField.type = "text"; // Change type to text to show the password
        eyeIcon.src = "assets/images/eye-open.png"; // Change eye icon to open
    } else {
        inputField.type = "password"; // Change type to password to hide the password
        eyeIcon.src = "assets/images/eye-close.png"; // Change eye icon to close
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('.form-container form');
    if (form) {
        form.addEventListener('submit', (e) => {
            const start = new Date(document.getElementById('start_date').value);
            const end = new Date(document.getElementById('end_date').value);
            if (start && end && start > end) {
                e.preventDefault();
                alert('End date must be after start date.');
            }
        });
    }
});


// const scrollContainer = document.querySelector('.explore-menu-list');

// scrollContainer.addEventListener('wheel', (e) => {
//     if (e.deltaY !== 0) {
//         e.preventDefault();
//         scrollContainer.scrollLeft += e.deltaY;
//     }
// });

// const scrollContainer = document.querySelector('.explore-menu-list');
// let scrollSpeed = 1; // Adjust speed here
// let scrollInterval;

// function autoScroll() {
//     scrollInterval = setInterval(() => {
//         scrollContainer.scrollLeft += scrollSpeed;

//         // Loop back to start when reaching the end
//         if (scrollContainer.scrollLeft + scrollContainer.clientWidth >= scrollContainer.scrollWidth) {
//             scrollContainer.scrollLeft = 0;
//         }
//     }, 20); // Adjust interval for smoother/faster scrolling
// }

// autoScroll();

// // Optional: pause scroll on hover
// scrollContainer.addEventListener('mouseenter', () => clearInterval(scrollInterval));
// scrollContainer.addEventListener('mouseleave', autoScroll);

// for message box
setTimeout(function () {
        const msg = document.getElementById('flash-message');
        if (msg) {
            msg.style.opacity = '0';
            msg.style.transition = 'opacity 0.5s ease';
            setTimeout(() => {
                msg.style.display = 'none';
            }, 500); // wait for fade out
        }
    }, 2000); // Show for 3 seconds







