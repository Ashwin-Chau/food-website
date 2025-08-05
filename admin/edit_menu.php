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
          $menu = getByID("menu", $id);

          if(mysqli_num_rows($menu) > 0)
          {
            $data = mysqli_fetch_array($menu);
            ?>
              <div class="card">
                <div class="card-header">
                  <h4>Edit Menu
                    <a href="menu.php" class="btn btn-primary float-end">Back</a>
                  </h4>
                </div>

                <div class="card-body">
                  <form action="code.php" method="POST" enctype="multipart/form-data" onsubmit="return validateEditMenu()" >
                    <div class="row">

                      <div class="col-md-6">
                        <input type="hidden" name="menu_id" value="<?= $data['id'] ?>">
                        <label for="">Name</label>
                        <input type="text" name="name" value="<?= $data['name'] ?>" placeholder="Enter Classes Name" class="form-control"
                        onblur="cnameValidation('name','nameErr')" oninput="generateSlug()" id="name" >
                        <span id="nameErr" class="text-danger"></span>
                      </div>

                      <div class="col-md-6">
                        <label for="">Slug</label>
                        <input type="text" name="slug" value="<?= $data['slug'] ?>" placeholder="Enter Slug" class="form-control"
                        onblur="slugValidation('slug','slugErr')" id="slug">
                        <span id="slugErr" class="text-danger"></span>
                      </div>

                      <div class="col-md-12">
                        <label for="">Description</label>
                        <textarea rows="3" name="description" placeholder="Enter description" class="form-control"><?= $data['description'] ?></textarea>
                      </div>

                      <div class="col-md-12">
                        <label for="image" class="mb-0">Upload Food Image</label>
                          <input type="file" name="image" class="form-control" id="menuImage" 
                            onblur="imageValidation('menuImage', 'menuImageErr', 'old_image')">
                          <span id="menuImageErr" class="text-danger"></span>
                          <label for="">Current Image</label>
                          <input type="hidden" name="old_image" id="old_image" value="<?= htmlspecialchars($data['image']) ?>">
                          <?php if (!empty($data['image'])) { ?>
                            <img src="../uploads/<?= htmlspecialchars($data['image']) ?>" height="50px" width="50px" alt="Current Image">
                          <?php } else { ?>
                            <p>No current image</p>
                          <?php } ?>
                      </div>

                      <!-- <div class="col-md-12">
                        <label for="">Meta Title</label>
                        <input type="text" name="meta_title" value="<?= $data['meta_title'] ?>" placeholder="Enter meta title" class="form-control"
                        onblur="mtitleValidation('meta_title','mtitleErr')" id="meta_title" >
                        <span id="mtitleErr" class="text-danger"></span>
                      </div> -->
                      
                      <div class="col-md-6">
                        <label for="">Status</label>
                        <input type="checkbox" <?= $data['status'] ? "checked":"" ?> name="status">
                      </div>

                      <div class="col-md-6">
                        <label for="">Popular</label>
                        <input type="checkbox" <?= $data['popular'] ? "checked":"" ?> name="popular">
                      </div>

                      <div class="col-md-12">
                        <button type="submit" class="btn btn-primary" name="update_menu_btn">Update</button>
                      </div>
     
                    </div>
                  </form>
                </div>
              </div>
            <?php
        }
        else
        {
          echo "Classes not found";
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