                
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
<hr>