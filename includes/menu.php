                
<div class="menu" id="Menu">
    <h1>Our <span>Menu</span></h1>

    <div class="explore-menu-list">
        <a href="food_items.php?menu=all" class="explore-menu-list-item">
                <img src="assets/images/menu_1.png" alt="Menu Image">
                <p>All Menu</p>
        </a>
        <?php
        $menu = getMenuItem("menu");

        if(mysqli_num_rows($menu) > 0)
        {
            foreach($menu as $item)
            {
        ?>
            

            <a href="food_items.php?menu=<?= $item['slug']; ?>" class="explore-menu-list-item">
                <img src="uploads/<?= $item['image']; ?>" alt="Menu Image">
                <p><?= $item['name']; ?></p>
            </a>
        <?php
            }
        }
        else
        {
            echo "<p>No data available</p>";
        }
        ?>
    </div>
</div>
<script>
    const scrollContainer = document.querySelector('.explore-menu-list');

scrollContainer.addEventListener('wheel', (e) => {
    if (e.deltaY !== 0) {
        e.preventDefault();
        scrollContainer.scrollLeft += e.deltaY;
    }
});

const scrollContainer = document.querySelector('.explore-menu-list');
let scrollSpeed = 1; // Adjust speed here
let scrollInterval;

function autoScroll() {
    scrollInterval = setInterval(() => {
        scrollContainer.scrollLeft += scrollSpeed;

        // Loop back to start when reaching the end
        if (scrollContainer.scrollLeft + scrollContainer.clientWidth >= scrollContainer.scrollWidth) {
            scrollContainer.scrollLeft = 0;
        }
    }, 20); // Adjust interval for smoother/faster scrolling
}

autoScroll();

// Optional: pause scroll on hover
scrollContainer.addEventListener('mouseenter', () => clearInterval(scrollInterval));
scrollContainer.addEventListener('mouseleave', autoScroll);
</script>

<hr>