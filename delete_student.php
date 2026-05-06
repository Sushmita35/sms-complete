<?php
include("database.php");

if(isset($_GET['id'])){

    $id = intval($_GET['id']);

    // DELETE CHILD DATA
    mysqli_query($conn,"DELETE FROM attendance WHERE student_id='$id'");
    mysqli_query($conn,"DELETE FROM results WHERE student_id='$id'");
    mysqli_query($conn,"DELETE FROM fees WHERE student_id='$id'");

    // DELETE STUDENT
    $delete = mysqli_query($conn,"DELETE FROM students WHERE student_id='$id'");

    if($delete){
        header("Location: view_students.php?msg=deleted"); // ✅ FIXED
        exit();
    } else {
        echo "Error deleting student: " . mysqli_error($conn);
    }

}else{
    echo "No ID received";
}
?>