<nav class="navbar navbar-main navbar-expand-lg px-0  mx-4  shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <h5 class="font-weight-bolder mb-0">Welcome to Admin Dashboard</h5>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <div class="input-group input-group-outline">
              <ul class="navbar-nav ms-auto float-end">

                <?php
                  if(isset($_SESSION['auth']))
                    {
                      ?>
                        
                     <h5> <?= $_SESSION['auth_user']['name'] ?> </h5>
                    
                    
                      <?php
                    }
                    else
                    {


                    }
                ?>
              </ul>
            </div>
          </div>
          
        </div>
      </div>
    </nav>

