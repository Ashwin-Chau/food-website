<?php 
include('../middleware/adminMiddleware.php');
include('includes/header.php');
?>

<div class="container">
  <div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Add Menu</h4>
            </div>
            <div class="card-body">
              <form action="code.php" method="POST" enctype="multipart/form-data" onsubmit="return validateAddMenu()">
                <div class="row">
                  <div class="col-md-6">
                    <label for="name">Name</label>
                    <input type="text" name="name" placeholder="Enter Menu Name" class="form-control" onblur="cnameValidation('name','nameErr')" oninput="generateSlug()" id="name">
                    <span id="nameErr" class="text-danger"></span>
                  </div>
                  <div class="col-md-6">
                    <label for="slug">Slug</label>
                    <input type="text" name="slug" placeholder="Enter Slug" class="form-control" onblur="slugValidation('slug','slugErr')" id="slug">
                    <span id="slugErr" class="text-danger"></span>
                  </div>
                  <div class="col-md-12">
                    <label for="description">Description</label>
                    <textarea rows="3" name="description" placeholder="Enter description" class="form-control"></textarea>
                  </div>
                  <div class="col-md-12">
                    <label for="image">Upload Image</label>
                    <input type="file" name="image" class="form-control">
                  </div>
                  <div class="col-md-6">
                    <label for="status">Status</label>
                    <input type="checkbox" name="status">
                  </div>
                  <div class="col-md-6">
                    <label for="popular">Popular</label>
                    <input type="checkbox" name="popular">
                  </div>
                  <div class="col-md-12">
                    <button type="submit" class="btn btn-primary" name="add_menu_btn">Save</button>
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