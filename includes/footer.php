

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/js/script.js"></script>
<script src="assets/js/custom.js"></script>
<script src="assets/js/my_orders.js"></script>

    
    <script>

      alertify.set('notifier','position', 'top-center');
      <?php
        if(isset($_SESSION['message']))
        {         
          ?>
            alertify.success('<?= $_SESSION['message']; ?>');
          <?php 
          unset($_SESSION['message']);
        }
      ?>
    </script>

    </body>
</html>