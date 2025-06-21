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
                $customer = getByID("customer ", $id);

                if(mysqli_num_rows($customer ) > 0)
                {
                    $data = mysqli_fetch_array($customer );
                    ?>
                    <div class="card">
                        <div class="card-header">
                            <h4>Edit Users
                                <a href="users.php" class="btn btn-primary float-end">Back</a>
                            </h4>
                        </div>
                        <div class="card-body">
                            <form action="code.php" method="POST" enctype="multipart/form-data" onsubmit="return validateEditUser()">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <input type="hidden" name="customer_id" value="<?= $data['id'] ?>">
                                        <label for="">Name</label>
                                        <input type="text" name="name" value="<?= $data['name'] ?>" placeholder="Enter your name" class="form-control" 
                                        onblur="nameValidation('name','nameErr')" id="name">
                                        <span id="nameErr" class="text-danger"></span>
                                    </div>

                                    <div class="col-md-12  mb-3">
                                        <label for="">Email</label>
                                        <input type="text" name="email" value="<?= $data['email'] ?>" placeholder="Enter your email" class="form-control" id="email"
                                        onblur="emailValidation('email','emailErr')">
                                        <span id="emailErr" class="text-danger"></span>
                                    </div>
                                    
                
                                    <div class="col-md-12  mb-3">
                                        <label for="">Password</label>
                                        <input type="password" name="password" value="<?= $data['password'] ?>" placeholder="Enter password" class="form-control" id="password"
                                        onblur="passwordValidation('password','passwordErr')" >
                                        <span id="passwordErr" class="text-danger"></span>
                                    </div>

                                    <div class="col-md-12  mb-3">
                                        <label for="">Confirm Password</label>
                                        <input type="password" name="cpassword" value="<?= $data['password'] ?>" placeholder="Enter password" class="form-control" id="cpassword"
                                        onblur="checkPass('cpassword','password','cpassErr')" >
                                        <span id="cpassErr" class="text-danger"></span>
                                    </div>

                                    <div class="col-md-6  mb-3">
                                    <label for="">Admin</label>
                                    <input type="checkbox" <?= $data['role_as'] ? "checked":"" ?> name="role_as">
                                    </div>

                                    <div class="col-md-12  mb-3">
                                    <button type="submit" class="btn btn-primary" name="update_users_btn">Update</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                    <?php
                }
                else
                {
                    echo "User not found";
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


<?php include('includes/footer.php'); ?>