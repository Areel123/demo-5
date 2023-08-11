<?php
// delete multiple data

$connection = mysqli_connect("localhost","root","","inline_db");

if(isset($_POST['search_data']))
{
    $id = $_POST['id'];
    $visible = $_POST['visible'];

    $query = "UPDATE `members` SET visible='$visible' WHERE id= '{$id}'";
    $query_run = mysqli_query($connection, $query);
}

if(isset($_POST['delete_multiple_data']))
{
    $id = "1";
    $query = "DELETE FROM `members` WHERE visible= '{$id}' ";

    $query_run = mysqli_query($connection, $query);
}

    if( $delete)
    {
        $_SESSION['success'] = "Your Data is DELETED";
        header('Location: index.php');
    }

    else
    {  
        $_SESSION['status'] = "Your Data is NOT DELETED";
        header('Location: index.php');
    }
?>