<?php
require_once ('database.php');
$db = new DBConnection();
$conn = $db->conn;
session_start();

// Check if the user is not logged in, redirect to login.php
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataTable</title>
	<link rel="stylesheet" href="./assets/css/bootstrap.css">
    <link href="DataTables/datatables.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
   
    
<style>
    .editable{
        display:none;
    }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#" style="margin-left: 710px; font-weight: 500; font-size: 1.3rem;">PHP CRUD</a>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" href="?logout=true">Logout</a>
        </li>
    </ul>  
</div>
</nav>
<div class="container py-3">
    <h2 class="border-bottom border-dark">Table Form</h2>

    <div class="row">
        <div class="col-12">
            <h3 class="text-center">Member List</h3>
        </div>
        <hr>
        <div class="col-12">

            <!-- Table Form start -->
            <form action="" id="form-data">
                <input type="hidden" name="id" value="">
                <table class='table table-hovered table-stripped table-bordered' id="form-tbl">
                    <colgroup>
                        <col width="6%">
                        <col width="20%">
                        <col width="20%">
                        <col width="15%">
                        <col width="20%">
                        <col width="20%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>
                             <input type="checkbox" id="chkall">
                            </th>
                            <th class="text-center p-1">Name</th>
                            <th class="text-center p-1">Email</th>
                            <th class="text-center p-1">Contact</th>
                            <th class="text-center p-1">Address</th>
                            <th class="text-center p-1">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $query = $conn->query("SELECT * FROM `members` order by id asc");
                    while($row = $query->fetch_assoc()):
                    ?>
                    <tr data-id='<?php echo $row['id'] ?>'>
                    <td class="text-center">
                    <input type="checkbox" name="selected_rows[]" onclick="toogleCheckbox(this)"  value="<?php echo $row['id'] ?>" <?php echo $row['visible'] == 1 ? "checked" : "" ?> >    
                    </td>
                        <td name="name"><?php echo $row['name'] ?></td>
                        <td name="email"><?php echo $row['email'] ?></td>
                        <td name="contact"><?php echo $row['contact'] ?></td>
                        <td name="address"><?php echo $row['address'] ?></td>
                        <td class="text-center">
                            <button class="btn btn-primary btn-sm rounded-0 py-0 edit_data noneditable" type="button">Edit</button>
                            <button class="btn btn-danger btn-sm rounded-0 py-0 delete_data noneditable" type="button">Delete</button>
                            <button class="btn btn-sm btn-primary btn-flat rounded-0 px-2 py-0 editable">Save</button>
                            <button class="btn btn-sm btn-dark btn-flat rounded-0 px-2 py-0 editable" onclick="cancel_button($(this))" type="button">Cancel</button></td>

                        </td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </form>
        </div>
        <!-- Table Form end -->

        <!-- multiple delete button -->
        <form action="delete_multiple.php" method="POST">
            <button type="submit" name="delete_multiple_data" class="btn btn-danger" style="position: absolute; top: 15%; margin-left: 180px;">Delete Multiple Data</button>
        </form>   

        <!-- add button -->
        <div class="w-100 d-flex pposition-relative justify-content-center">
        <button class="btn btn-flat btn-primary" id="add_member" type="button" style="top: 15%; position: absolute;  margin-right: 1121px;">Add New Member</button>
        </div>

        <!-- export button -->
        <form method="post" action="export.php">
     <input type="submit" name="export" class="btn btn-success" value="Export CSV" style="top: 15%; position: absolute; margin-left: 365px;" />
    </form>

  <!-- import button -->
  <form id="uploadForm" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="csv_file" class="form-label">Import CSV File:</label>
        <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv">
    </div>
    <button type="submit" class="btn btn-primary">Import CSV</button>

    <!-- Bootstrap-styled progress bar -->
    <div class="progress mt-3" style="display: none;">
        <div class="progress-bar progress-bar-striped bg-info" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div>

    <div id="alertContainer" style="margin-top: 10px;"></div>
</form>

<!-- style of progress bar -->

<style>
        /* Custom CSS for the progress bar */
        .progress {
            height: 30px;
            background-color: #f0f0f0;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 15px;
        }

        .progress .progress-bar {
            background-color: #1e5799; 
            height: 100%;
        }
    </style>
    </div>
</div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script type="text/javascript" src="./assets/js/jquery-3.6.0.js"></script>
<script type="text/javascript" src="./assets/js/bootstrap.js"></script>
<script type="text/javascript" src="./assets/js/script.js"></script>
<script src="DataTables/datatables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- visible and bulk listing script -->
<script>
    $(document).ready(function(){
        $('#form-tbl').DataTable();   

    });

    function toogleCheckbox(box)
    {
       var id = $(box).attr("value");

       if($(box).prop("checked") == true)
       {
         var visible = 1;
       }

       else{
        var visible = 0;
       }

       var data = {
        "search_data": 1,
        "id": id,
        "visible":visible
       }

       $.ajax({
        type:"post",
        url:"delete_multiple.php.",
        data: data,
        success: function (response) {
        }
       });
    }
</script>
</html>