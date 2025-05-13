<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "studentdb";


$id = $name = $gender = $email = $phone = $address = "";
$errorMsg = "";
$successMsg = "";


if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
   
    $id = trim($_GET['id']);
    
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
 
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
   
    $sql = "SELECT * FROM students WHERE id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
       
        $stmt->bind_param("i", $id);
        
       
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            
            if ($result->num_rows == 1) {
               
                $row = $result->fetch_assoc();
                
             
                $name = $row['name'] ?? '';
                $gender = $row['gender'] ?? '';
                $email = $row['email'] ?? '';
                $phone = $row['phone'] ?? '';
                $address = $row['address'] ?? '';
            } else {
          
                header("location: index.php");
                exit();
            }
        } else {
            $errorMsg = "Error retrieving student data: " . $stmt->error;
        }
        
      
        $stmt->close();
    }
    
  
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
        $name = $_POST["name"] ?? "";
        $gender = $_POST["gender"] ?? "";
        $email = $_POST["email"] ?? "";
        $phone = $_POST["phone"] ?? "";
        $address = $_POST["address"] ?? "";
    
        if (empty($name) || empty($gender) || empty($email)) {
            $errorMsg = "Name, gender, and email are required fields!";
        } else {
        
            $sql = "UPDATE students SET name=?, gender=?, email=?, phone=?, address=? WHERE id=?";
            
            if ($stmt = $conn->prepare($sql)) {
           
                $stmt->bind_param("sssssi", $name, $gender, $email, $phone, $address, $id);
                
               
                if ($stmt->execute()) {
                    $successMsg = "Student updated successfully!";
                } else {
                    $errorMsg = "Error updating student: " . $stmt->error;
                }
                
             
                $stmt->close();
            }
        }
    }
    
 
    $conn->close();
} else {

    header("location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #6f42c1, #6610f2);
            color: white;
            padding: 1.5rem 1rem;
            border-bottom: none;
        }
        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s;
        }
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 3px rgba(102, 16, 242, 0.2);
            border-color: #6610f2;
        }
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #495057;
        }
        .btn {
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s;
        }
        .btn-primary {
            background: linear-gradient(135deg, #6f42c1, #6610f2);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #6610f2, #5e08e9);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 16, 242, 0.3);
        }
        .btn-outline-secondary {
            border: 1px solid #e0e0e0;
            color: #6c757d;
            background: white;
        }
        .btn-outline-secondary:hover {
            background-color: #f8f9fa;
            color: #5c636a;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.2);
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
        .input-icon-wrapper {
            position: relative;
        }
        .input-icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 15px;
            color: #6c757d;
        }
        .input-with-icon {
            padding-left: 40px;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #6610f2;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }
        .back-link:hover {
            color: #5e08e9;
            transform: translateX(-3px);
        }
        .back-link i {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <a href="index.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Students List
        </a>
        
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h2 class="mb-0 fw-bold">Edit Student</h2>
                    </div>
                    <div class="card-body p-4">
                        <?php if (!empty($errorMsg)): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i> <?php echo $errorMsg; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($successMsg)): ?>
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle me-2"></i> <?php echo $successMsg; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $id); ?>" class="needs-validation" novalidate>
                            <div class="mb-4">
                                <label for="name" class="form-label">Name</label>
                                <div class="input-icon-wrapper">
                                    <i class="fas fa-user input-icon"></i>
                                    <input type="text" class="form-control input-with-icon" id="name" name="name" value="<?php echo $name; ?>" placeholder="Enter full name" required>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="gender" class="form-label">Gender</label>
                                <div class="input-icon-wrapper">
                                    <i class="fas fa-venus-mars input-icon"></i>
                                    <select class="form-select input-with-icon" id="gender" name="gender" required>
                                        <option value="" <?php if(empty($gender)) echo "selected"; ?>>Select Gender</option>
                                        <option value="Male" <?php if($gender == "Male") echo "selected"; ?>>Male</option>
                                        <option value="Female" <?php if($gender == "Female") echo "selected"; ?>>Female</option>
                                        <option value="Other" <?php if($gender == "Other") echo "selected"; ?>>Other</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-icon-wrapper">
                                    <i class="fas fa-envelope input-icon"></i>
                                    <input type="email" class="form-control input-with-icon" id="email" name="email" value="<?php echo $email; ?>" placeholder="example@domain.com" required>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="phone" class="form-label">Phone</label>
                                <div class="input-icon-wrapper">
                                    <i class="fas fa-phone input-icon"></i>
                                    <input type="tel" class="form-control input-with-icon" id="phone" name="phone" value="<?php echo $phone; ?>" placeholder="(xxx) xxx-xxxx">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="address" class="form-label">Address</label>
                                <div class="input-icon-wrapper">
                                    <i class="fas fa-home input-icon" style="top: 20px;"></i>
                                    <textarea class="form-control input-with-icon" id="address" name="address" rows="2" placeholder="Enter address"><?php echo $address; ?></textarea>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-2"></i> Update Student
                                </button>
                                <a href="index.php" class="btn btn-outline-secondary px-4">
                                    <i class="fas fa-times-circle me-2"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Client-side form validation
        (function () {
            'use strict'
            
            // Fetch all forms that need validation
            var forms = document.querySelectorAll('.needs-validation')
            
            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>
</html>