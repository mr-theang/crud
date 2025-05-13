<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "studentdb";


$name = $gender = $email = $phone = $address = "";
$errorMsg = "";
$successMsg = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $gender = $_POST["gender"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    
  
    if (empty($name) || empty($gender) || empty($email)) {
        $errorMsg = "Name, gender, and email are required fields!";
    } else {

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
     
        $stmt = $conn->prepare("INSERT INTO students (name, gender, email, phone, address) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $gender, $email, $phone, $address);
        
       
        if ($stmt->execute()) {
            $successMsg = "Student added successfully!";
            
            $name = $gender = $email = $phone = $address = "";
        } else {
            $errorMsg = "Error: " . $stmt->error;
        }
        
   
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Student</title>
  
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
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
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
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.2);
            border-color: #0d6efd;
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
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #0b5ed7, #0a58ca);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
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
        .floating-container {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body>
    <div class="container mt-5 floating-container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h2 class="mb-0 fw-bold">Add New Student to database</h2>
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
                        
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="needs-validation" novalidate>
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
                                <label for="phone" class="form-label">Phone</label>
                                <div class="input-icon-wrapper">
                                    <i class="fas fa-phone input-icon"></i>
                                    <input type="tel" class="form-control input-with-icon" id="phone" name="phone" value="<?php echo $phone; ?>" placeholder="(xxx) xxx-xxxx">
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
                                <label for="address" class="form-label">Address</label>
                                <div class="input-icon-wrapper">
                                    <i class="fas fa-home input-icon" style="top: 20px;"></i>
                                    <textarea class="form-control input-with-icon" id="address" name="address" rows="2" placeholder="Enter your address"><?php echo $address; ?></textarea>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-plus-circle me-2"></i> Add New
                                </button>
                                <a href="index.php" class="btn btn-outline-secondary px-4">
                                    <i class="fas fa-times-circle me-2"></i> Exit
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
  
        (function () {
            'use strict'
            
        
            var forms = document.querySelectorAll('.needs-validation')
            
           
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