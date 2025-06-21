<div class="food" id="Food">
    <h1>Today Top<span>Food</span></h1>
    
            <div class="menu_box">
                <?php
                     
                        $trendingFood = getAllTrending();
                        if(mysqli_num_rows($trendingFood) > 0)
                        {
                            foreach($trendingFood as $item) {
                                ?>

                <div class="menu_card">
                    <a href="food_items_view.php?food_items=<?= $item['slug']; ?>">
                    <div class="menu_image">
                        <img src="uploads/<?= $item['image']; ?>" alt="Food Image">
                    </div>

                    <div class="menu_info">
                        <h2><?= $item['name']; ?></h2>
                        <p>
                            <?= $item['description']; ?>
                        </p>
                        <h3>Rs <?= $item['price']; ?></h3>
                        
                        <a href="food_items_view.php?food_items=<?= $item['slug']; ?>" class="menu_btn">Order Now</a>
                    </div> 
                    </a>   
                </div> 
                            

                <?php
                            }
                        }
                    ?>  
                
            </div>
    
</div>