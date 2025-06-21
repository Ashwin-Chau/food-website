$(document).ready(function () {

    $(document).on('click', '.delete_food_items_btn', function (e) {
        e.preventDefault();

        var id = $(this).val();
        // alert(id);

        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if(willDelete) {
                $.ajax({
                    method: "POST",
                    url: "code.php",
                    data: {
                        'food_items_id':id,
                        'delete_food_items_btn': true
                    },
                    success: function (response) {
                        if(response == 300)
                        {
                            
                            swal("Success!", "Food Items deleted Sucessfully", "success");
                             $("#food_items_table").load(location.href + "  #food_items_table");
                        }
                        else if(response == 500)
                        {
                             swal("Error!", "Something went wrong!", "error");
                        }
                    }
                });
            } 
        });
    });

    $(document).on('click', '.delete_menu_btn', function (e) {
        e.preventDefault();

        var id = $(this).val();
        // alert(id);

        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if(willDelete) {
                $.ajax({
                    method: "POST",
                    url: "code.php",
                    data: {
                        'menu_id':id,
                        'delete_menu_btn': true
                    },
                    success: function (response) {
                        if(response == 301)
                        {
                            
                            swal("Success!", "Menu deleted Sucessfully", "success");
                             $("#menu_table").load(location.href + "  #menu_table");
                        }
                        else if(response == 500)
                        {
                             swal("Error!", "Something went wrong!", "error");
                        }
                    }
                });
            } 
        });
    });

    $(document).on('click', '.delete_users_btn', function (e) {
        e.preventDefault();

        var id = $(this).val();
        // alert(id);

        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if(willDelete) {
                $.ajax({
                    method: "POST",
                    url: "code.php",
                    data: {
                        'users_id':id,
                        'delete_users_btn': true
                    },
                    success: function (response) {
                        if(response == 300)
                        {
                            
                            swal("Success!", "User data deleted Sucessfully", "success");
                             $("#users_table").load(location.href + "  #users_table");
                        }
                        else if(response == 500)
                        {
                             swal("Error!", "Something went wrong!", "error");
                        }
                    }
                });
            } 
        });
    });

    $(document).on('click', '.delete_orders_btn', function (e) {
        e.preventDefault();

        var id = $(this).val();
        // alert(id);

        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if(willDelete) {
                $.ajax({
                    method: "POST",
                    url: "code.php",
                    data: {
                        'orders_id':id,
                        'delete_orders_btn': true
                    },
                    success: function (response) {
                        if(response == 302)
                        {
                            
                            swal("Success!", "Orders deleted Sucessfully", "success");
                             $("#orders_table").load(location.href + "  #orders_table");
                        }
                        else if(response == 500)
                        {
                             swal("Error!", "Something went wrong!", "error");
                        }
                    }
                });
            } 
        });
    });

});