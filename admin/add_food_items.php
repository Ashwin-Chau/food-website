<?php 
 
include('../middleware/adminMiddleware.php');
include('includes/header.php');

?>


<div class="container">
  <div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Add Food Items</h4>
            </div>
            <div class="card-body">
              <form action="code.php" method="POST" enctype="multipart/form-data" onsubmit="return validateAddFoodItems()" >
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
                                      <option value="<?= $item['id']; ?>"><?= $item['name']; ?></option>
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

                  <div class="col-md-6">
                    <label class="mb-0">Name</label>
                    <input type="text"  name="name" placeholder="Enter Food Name" class="form-control mb-2" oninput="generateSlug()"
                    onblur="pnameValidation('name','nameErr')" id="name" >
                    <span id="nameErr" class="text-danger"></span>
                  </div>

                  <div class="col-md-6">
                    <label class="mb-0">Slug</label>
                    <input type="text"  name="slug" placeholder="Enter Slug" class="form-control mb-2" onblur="slugValidation('slug','slugErr')" 
                    id="slug">
                    <span id="slugErr" class="text-danger"></span>
                  </div>

                  <div class="col-md-12">
                    <label class="mb-0">Description</label>
                    <textarea rows="3" name="description" placeholder="Enter discription" class="form-control mb-2"></textarea>
                  </div>

                  <div class="col-md-6">
                    <label for="" class="mb-0">Upload Image</label>
                    <input type="file" name="image" class="form-control">
                  </div>

                  <div class="col-md-6">
                    <label class="mb-0">Price</label>
                    <input type="text"  name="price" placeholder="Enter Price" class="form-control mb-2" 
                    onblur="opriceValidation('original_price','opriceErr')" id="original_price" >
                    <span id="opriceErr" class="text-danger"></span>
                  </div>


                  <div class="row">

                      <div class="col-md-6">
                        <label class="mb-0">Quantity</label>
                        <input type="number"  name="quantity" placeholder="Enter Quantity" class="form-control mb-2"  
                        id="quantity">
                        
                      </div>

                      <div class="col-md-3">
                          <br><label class="mb-0">Status</label> <br>
                          <input type="checkbox" name="status">
                      </div>

                      <div class="col-md-3">
                          <br><label class="mb-0">Trending</label> <br>
                          <input type="checkbox" name="trending">
                      </div>

                  </div>

                  
                  
                  <div class="col-md-12">
                    <button type="submit" class="btn btn-primary" name="add_food_items_btn">Save</button>
                  </div>
                </div>
              </form>
            </div>
        </div>
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