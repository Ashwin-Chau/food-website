<?php 
include('functions/userfunctions.php'); 
include('includes/header.php'); 

// Check if a search query is provided
if(isset($_GET['query']) && !empty(trim($_GET['query'])))
{
    $search_query = trim($_GET['query']);
    $search_results = searchFoodItems($search_query);
    ?>
    <!-- Main Content for Search Results -->
    <div class="py-3">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1>Search Results for <span>"<?= htmlspecialchars($search_query); ?>"</span></h1>
                    <hr>  
                    
                    <!-- Display search results -->
                    <div class="food" id="Food">
                        <div class="menu_box">
                            <?php
                            if(!empty($search_results) && is_array($search_results) && count($search_results) > 0)
                            {
                                foreach($search_results as $item)
                                {
                                    ?>
                                    <div class="menu_card">
                                        <a href="food_items_view.php?food_items=<?= $item['slug']; ?>">
                                            <div class="menu_image">
                                                <img src="uploads/<?= $item['image']; ?>" alt="Food Image">
                                            </div>
                                            <div class="menu_info">
                                                <h2><?= $item['name']; ?></h2>
                                                <p><?= $item['description']; ?></p>
                                                <h3>Rs <?= $item['price']; ?></h3>
                                                <a href="food_items_view.php?food_items=<?= $item['slug']; ?>" class="menu_btn">Order Now</a>
                                            </div> 
                                        </a>   
                                    </div> 
                                    <?php
                                }
                            }
                            else
                            {
                                redirect("index.php", "No food items found matching your search.");
                            }
                            ?> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
// If no search query, fall back to menu-based logic
elseif(isset($_GET['menu']))
{
    $menu_slug = $_GET['menu'];

    if($menu_slug === 'all')
    {
        // Handle "All Menu" case: Fetch all active food items
        $all_food_items = getAllActiveFood();
        ?>
        <!-- Main Content for All Menu -->
        <div class="py-3">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h1>Variety of <span>All Menus</span></h1>
                        <hr>  
                        
                        <!-- Food items related to all menus -->
                        <div class="food" id="Food">
                            <div class="menu_box">
                                <?php
                                if(!empty($all_food_items) && is_array($all_food_items) && count($all_food_items) > 0)
                                {
                                    foreach($all_food_items as $item)
                                    {
                                        ?>
                                        <div class="menu_card">
                                            <a href="food_items_view.php?food_items=<?= $item['slug']; ?>">
                                                <div class="menu_image">
                                                    <img src="uploads/<?= $item['image']; ?>" alt="Food Image">
                                                </div>
                                                <div class="menu_info">
                                                    <h2><?= $item['name']; ?></h2>
                                                    <p><?= $item['description']; ?></p>
                                                    <h3>Rs <?= $item['price']; ?></h3>
                                                    <a href="food_items_view.php?food_items=<?= $item['slug']; ?>" class="menu_btn">Order Now</a>
                                                </div> 
                                            </a>   
                                        </div> 
                                        <?php
                                    }
                                }
                                else
                                {
                                    redirect("index.php#Menu", "No Food variety available");
                                }
                                ?> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else
    {
        // Handle specific menu case
        $menu_data = getSlugActive("menu", $menu_slug);
        $menu = mysqli_fetch_array($menu_data);

        if($menu)
        {
            $mid = $menu['id'];
            ?>
            <!-- Main Content for Specific Menu -->
            <div class="py-3">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h1>Variety of <span><?= $menu['name']; ?></span></h1>
                            <hr>  
                            
                            <!-- Food items related to specific menu -->
                            <div class="food" id="Food">
                                <div class="menu_box">
                                    <?php
                                    $menu_items = getFoodByMenu($mid);

                                    if(mysqli_num_rows($menu_items) > 0)
                                    {
                                        foreach($menu_items as $item)
                                        {
                                            ?>
                                            <div class="menu_card">
                                                <a href="food_items_view.php?food_items=<?= $item['slug']; ?>">
                                                    <div class="menu_image">
                                                        <img src="uploads/<?= $item['image']; ?>" alt="Food Image">
                                                    </div>
                                                    <div class="menu_info">
                                                        <h2><?= $item['name']; ?></h2>
                                                        <p><?= $item['description']; ?></p>
                                                        <h3>Rs <?= $item['price']; ?></h3>
                                                        <a href="food_items_view.php?food_items=<?= $item['slug']; ?>" class="menu_btn">Order Now</a>
                                                    </div> 
                                                </a>   
                                            </div> 
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        redirect("index.php#Menu", "No Food variety available");
                                    }
                                    ?> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        else
        {
            redirect("index.php", "Something Went Wrong");
        }
    }
}
else
{
    
    redirect("index.php", "Type Something");
}
include('includes/footer.php'); 
?>