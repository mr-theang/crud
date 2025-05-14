<?php

$id = null;
$errorMsg = "";
$successMsg = "";


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "studentdb";


if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
    
    $id = trim($_GET['id']);
    
 
    if (!is_numeric($id)) {
        $errorMsg = "Invalid student ID format.";
    } else {
        try {
          
            $conn = new mysqli($servername, $username, $password, $dbname);
            
           
            if ($conn->connect_error) {
                throw new Exception("Connection failed: " . $conn->connect_error);
            }
            
            
            $sql = "DELETE FROM students WHERE id = ?";
            $stmt = $conn->prepare($sql);
            
            if ($stmt) {
             
                $stmt->bind_param("i", $id);
                
               
                if ($stmt->execute()) {
                   
                    if ($stmt->affected_rows > 0) {
                        $successMsg = "Student deleted successfully.";
                    } else {
                        $errorMsg = "No student found with that ID.";
                    }
                } else {
                    $errorMsg = "Error executing delete statement: " . $stmt->error;
                }
                
         
                $stmt->close();
            } else {
                $errorMsg = "Error preparing delete statement: " . $conn->error;
            }
            
          
            $conn->close();
            
        } catch (Exception $e) {
            $errorMsg = "Database error: " . $e->getMessage();
        }
    }
} else {
    $errorMsg = "Student ID is required to perform deletion.";
}


session_start();
if (!empty($errorMsg)) {
    $_SESSION['error_msg'] = $errorMsg;
} else if (!empty($successMsg)) {
    $_SESSION['success_msg'] = $successMsg;
}


header("Location: index.php");
exit();
?>