<?php 

    include('functions/userfunctions.php');
    include('includes/header.php');
    if (isset($_SESSION['message'])) {
    echo "<div class='custom-alert' id='flash-message'>" . $_SESSION['message'] . "</div>";
    unset($_SESSION['message']);
}
    include('includes/banner.php'); 
    include('includes/menu.php');
    include('includes/trending_food_items.php');
    include('includes/aboutus.php');
?>



<!-- footer -->

        <footer>
            <div class="footer_main">
    
                <div class="footer_tag">
                    <h2>Quick Link</h2>
                    <p><a href="#Home">Home</a></p>
                    <p><a href="#About">About</a></p>
                    <p><a href="#Menu">Menu</a></p>
                </div>
    
                <div class="footer_tag">
                    <h2>Contact</h2>
                    <p>+977 9807227048</p>
                    <p>+977 </p>
                    <p>ashwinchaudhary511@gmail.com</p>
                    <p>prajwonjungt@gmail.com</p>
                </div>
    
                <div class="footer_tag">
                    <h2>Our Service</h2>
                    <p>Fast Delivery</p>
                    <p>Easy Payments</p>
                    <p>24 x 7 Service</p>
                </div>
    
                <div class="footer_tag">
                    <h2>Follows</h2>
                    <i class="fa-brands fa-facebook-f"></i>
                    <i class="fa-brands fa-twitter"></i>
                    <i class="fa-brands fa-instagram"></i>
                    <i class="fa-brands fa-linkedin-in"></i>
                </div>

                <div class="footer_tag">
                    <!-- <h2>Location</h2> -->
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3535.495379491617!2d84.56627948070515!3d27.609170314972733!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3994e8676c67cb1b%3A0x42cc7462bb3af372!2sSungava%20College!5e0!3m2!1sen!2snp!4v1715960125326!5m2!1sen!2snp"
                        width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
    
            </div>
    
            <p class="end">Copyright © by <span></i>Aashwin and Prajwol</span></p>
    
        </footer>

<?php include('includes/footer.php'); ?>