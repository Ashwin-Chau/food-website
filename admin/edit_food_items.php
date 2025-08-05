<?php 

include('../middleware/adminMiddleware.php');
include('includes/header.php'); 


?>


<div class="container">
  <div class="row">
    <div class="col-md-12">
        <?php
            if(isset($_GET['id']))
                {

                    $id = $_GET['id'];

                    $food_items = getByID("food_items",$id);

                    if(mysqli_num_rows($food_items) > 0)
                    {
                        $data = mysqli_fetch_array($food_items);

                        ?>
                        <div class="card">
                            <div class="card-header">
                                <h4>Edit Food Items
                                <a href="food_items.php" class="btn btn-primary float-end">Back</a>
                                </h4>
                            </div>
                            <div class="card-body">
                                <form action="code.php" method="POST" enctype="multipart/form-data" onsubmit="return validateEditFoodItems()">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="mb-0"> Select Menu</label>
                                            <select name="menu_id" class="form-select mb-2">
                                                <option selected>Select Menu</option>
                    
                                                <?php 
                                                    $menu = getAll("menu");
                    
                                                    if(mysqli_num_rows($menu) > 0)
                                                    {
                                                        foreach ($menu as $item) {
                                                            ?>
                                                                <option value="<?= $item['id']; ?>" <?= $data['menu_id'] == $item['id']?'selected':'' ?>><?= $item['name']; ?></option>
                                                            <?php
                                                        }  
                                                    }
                                                    else
                                                    {
                                                        echo "No Menu Available";
                                                    }
                                                ?>  
                                            </select>
                                        </div>

                                        <input type="hidden" name="food_items_id" value="<?= $data['id']; ?>">

                                        <div class="col-md-6">
                                            <label class="mb-0">Name</label>
                                            <input type="text" name="name" value="<?= $data['name']; ?>" placeholder="Enter Name" class="form-control mb-2" 
                                            oninput="generateSlug()"
                                            onblur="pnameValidation('name','nameErr')" id="name" >
                                            <span id="nameErr" class="text-danger"></span>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="mb-0">Slug</label>
                                            <input type="text"  name="slug" value="<?= $data['slug']; ?>" placeholder="Enter Slug" class="form-control mb-2"
                                            onblur="slugValidation('slug','slugErr')" id="slug" >
                                            <span id="slugErr" class="text-danger"></span>
                                        </div>


                                        <div class="col-md-12">
                                            <label class="mb-0">Description</label>
                                            <textarea rows="3"  name="description" placeholder="Enter Description" class="form-control mb-2"><?= $data['description']; ?></textarea>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="image" class="mb-0">Upload Food Image</label>
                                            <input type="file" name="image" class="form-control" id="foodImage" 
                                                onblur="imageValidation('foodImage', 'foodImageErr', 'old_image')">
                                            <span id="foodImageErr" class="text-danger"></span>
                                            <label for="">Current Image</label>
                                            <input type="hidden" name="old_image" id="old_image" value="<?= htmlspecialchars($data['image']) ?>">
                                            <?php if (!empty($data['image'])) { ?>
                                                <img src="../uploads/<?= htmlspecialchars($data['image']) ?>" height="50px" width="50px" alt="Current Image">
                                            <?php } else { ?>
                                                <p>No current image</p>
                                            <?php } ?>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="mb-0">Price</label>
                                            <input type="text" name="price" value="<?= $data['price']; ?>" placeholder="Enter Original Price"
                                             class="form-control mb-2" onblur="opriceValidation('original_price','opriceErr')" id="original_price">
                                             <span id="opriceErr" class="text-danger"></span>
                                        </div>


                                        <div class="row">
                                        
                                            <div class="col-md-6">
                                                <label class="mb-0">Quantity</label>
                                                <input type="number"  name="quantity" value="<?= $data['quantity']; ?>" placeholder="Enter Quantity" class="form-control mb-2"  
                                                onblur="quantityValidation('quantity','qtyErr')" id="quantity">
                                                <span id="qtyErr" class="text-danger"></span>
                                            </div>

                                            <div class="col-md-3">
                                                <br><label class="mb-0">Status</label> <br>
                                                <input type="checkbox" name="status" <?= $data['status'] == '0'?'':'checked' ?>>
                                            </div>

                                            <div class="col-md-3">
                                                <br><label class="mb-0">Trending</label> <br>
                                                <input type="checkbox" name="trending" <?= $data['trending'] == '0'?'':'checked' ?>>
                                            </div>

                                        </div>
                                        
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary" name="update_food_items_btn">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php
                    
                    }
                    else
                    {
                        echo "Product Not Found for given id";
                    }
                 
                }
            else
            {
                echo "Id missing from url";
            }
        ?>
    </div>
  </div>
</div>

<script>
function generateSlug() {
    // Get the value from the name field
    const nameInput = document.getElementById('name').value;
    // Convert to lowercase and replace spaces with hyphens
    const slug = nameInput.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
    // Update the slug field
    document.getElementById('slug').value = slug;
}
</script>

<?php include('includes/footer.php'); ?>