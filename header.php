<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "studentdb";

// Initialize variables
$students = [];
$errorMsg = "";

// Start session for messages
session_start();

// Create database connection
try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Prepare SQL statement to retrieve all students
    $sql = "SELECT * FROM students ORDER BY id ASC";
    $result = $conn->query($sql);
    
    if ($result) {
        // Fetch data and store in array
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
    } else {
        $errorMsg = "Error retrieving students: " . $conn->error;
    }
    
    // Close connection
    $conn->close();
} catch (Exception $e) {
    $errorMsg = "Database error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .table-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-top: 1.5rem;
        }
        .table {
            margin-bottom: 0;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }
        .table td, .table th {
            padding: 15px;
            vertical-align: middle;
        }
        .action-btn {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            margin-right: 5px;
            transition: all 0.2s;
        }
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }
        .alert {
            border-radius: 10px;
            border: none;
            margin-top: 1rem;
        }
        
        /* Navbar styling */
        .navbar-dark .navbar-brand {
            font-size: 1.4rem;
        }
        
        .navbar {
            padding-top: 0.7rem;
            padding-bottom: 0.7rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.3s;
            border-radius: 4px;
        }
        
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .navbar-dark .navbar-nav .nav-link.active {
            background-color: rgba(255, 255, 255, 0.15);
        }
        
        .input-group {
            border-radius: 50px;
            overflow: hidden;
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        
        .dropdown-item {
            padding: 0.5rem 1.5rem;
            transition: all 0.2s;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fa;
        }
        
        /* Responsive adjustments */
        @media (max-width: 991.98px) {
            .navbar .input-group {
                margin-bottom: 1rem;
            }
            
            .dropdown {
                display: block;
                margin-bottom: 1rem;
            }
        }
        
        @media (max-width: 767.98px) {
            .col-md-6.text-md-end {
                text-align: center !important;
            }
        }
        
        /* Enhance breadcrumb appearance */
        .breadcrumb {
            font-size: 0.85rem;
            color: #6c757d;
        }
        
        /* Button styling */
        .btn-success {
            background: linear-gradient(135deg, #28a745, #20913a);
            border: none;
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(40, 167, 69, 0.4);
        }
        
        .btn-outline-secondary {
            border-color: #dee2e6;
        }
        
        .btn-outline-secondary:hover {
            background-color: #f8f9fa;
            border-color: #ced4da;
        }
        
        /* Card styling */
        .stats-card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-card .card-body {
            padding: 1.5rem;
        }
        
        .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }
        
        /* Footer styling */
        .footer {
            border-top: 1px solid #dee2e6;
            padding: 1.5rem 0;
            margin-top: 3rem;
        }
    </style>
</head>
<body>
    <!-- Modern Header Component -->
    <header class="header">
        <!-- Top Navigation Bar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <!-- Brand Logo and Name -->
                <a class="navbar-brand d-flex align-items-center" href="header.php">
                    <i class="fas fa-graduation-cap fs-3 me-2"></i>
                    <span class="fw-bold">StudentDB</span>
                </a>
                
                <!-- Responsive Toggle Button -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                        data-bs-target="#navbarContent" aria-controls="navbarContent" 
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <!-- Navigation Content -->
                <div class="collapse navbar-collapse" id="navbarContent">
                    <!-- Main Navigation Links -->
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" href="header.php">
                                <i class="fas fa-home me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="header.php">
                                <i class="fas fa-chart-bar me-1"></i> Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="header.php">
                                <i class="fas fa-cog me-1"></i> Settings
                            </a>
                        </li>
                    </ul>
                    
                    <!-- Search Form -->
                    <form class="d-flex me-2">
                        <div class="input-group">
                            <input class="form-control" type="search" placeholder="Search students" aria-label="Search">
                            <button class="btn btn-light" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                    
                    <!-- User Profile Dropdown -->
                    <div class="dropdown">
                        <a class="btn btn-outline-light dropdown-toggle d-flex align-items-center" href="#" role="button" 
                           id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-2"></i>
                            <span class="d-none d-sm-inline">Admin</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-lock me-2"></i> Change Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Breadcrumb and Page Title Section -->
        <div class="bg-light py-3 border-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="h3 mb-0">Students Data</h1>
                    </div>
                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                        <a href="add.php" class="btn btn-success rounded-pill">
                            <i class="fas fa-plus me-2"></i> Add New Student
                        </a>
                        <div class="btn-group ms-2">
                            <button type="button" class="btn btn-outline-secondary rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-download me-1"></i> Export
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="far fa-file-excel me-2"></i> Excel</a></li>
                                <li><a class="dropdown-item" href="#"><i class="far fa-file-pdf me-2"></i> PDF</a></li>
                                <li><a class="dropdown-item" href="#"><i class="far fa-file-csv me-2"></i> CSV</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container my-4">
        <!-- Stats Cards Row -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="stats-card card h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stats-icon bg-primary me-3">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0"><?php echo count($students); ?></h5>
                            <p class="card-text text-muted mb-0">Total Students</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card card h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stats-icon bg-success me-3">
                            <i class="fas fa-male"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">
                                <?php 
                                    $maleCount = array_reduce($students, function($carry, $item) {
                                        return $carry + ($item['gender'] == 'Male' ? 1 : 0);
                                    }, 0);
                                    echo $maleCount;
                                ?>
                            </h5>
                            <p class="card-text text-muted mb-0">Male Students</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card card h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stats-icon bg-info me-3">
                            <i class="fas fa-female"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">
                                <?php 
                                    $femaleCount = array_reduce($students, function($carry, $item) {
                                        return $carry + ($item['gender'] == 'Female' ? 1 : 0);
                                    }, 0);
                                    echo $femaleCount;
                                ?>
                            </h5>
                            <p class="card-text text-muted mb-0">Female Students</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card card h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stats-icon bg-warning me-3">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">Today</h5>
                            <p class="card-text text-muted mb-0"><?php echo date('d M Y'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Alert Messages -->
        <?php if (!empty($errorMsg)): ?>
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> <?php echo $errorMsg; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success_msg'])): ?>
            <div class="alert alert-success" role="alert">
                <i class="fas fa-check-circle me-2"></i> <?php echo $_SESSION['success_msg']; ?>
            </div>
            <?php unset($_SESSION['success_msg']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error_msg'])): ?>
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> <?php echo $_SESSION['error_msg']; ?>
            </div>
            <?php unset($_SESSION['error_msg']); ?>
        <?php endif; ?>

        <!-- Students Table -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($students) > 0): ?>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><?php echo $student['id']; ?></td>
                                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                                    <td>
                                        <span class="badge <?php echo $student['gender'] == 'Male' ? 'bg-primary' : 'bg-info'; ?> rounded-pill">
                                            <?php echo htmlspecialchars($student['gender']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($student['phone']); ?></td>
                                    <td><?php echo htmlspecialchars($student['email']); ?></td>
                                    <td><?php echo htmlspecialchars($student['address']); ?></td>
                                    <td>
                                        <a href="edit.php?id=<?php echo $student['id']; ?>" class="btn btn-primary action-btn" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="delete.php?id=<?php echo $student['id']; ?>" class="btn btn-danger action-btn" title="Delete" onclick="return confirm('Are you sure you want to delete this student?');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <!-- <a href="view.php?id=<?php echo $student['id']; ?>" class="btn btn-info action-btn" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a> -->
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="py-5">
                                        <i class="fas fa-database text-muted fs-1 mb-3"></i>
                                        <h5>No students found in the database.</h5>
                                        <p class="text-muted">Add your first student to get started</p>
                                        <a href="add.php" class="btn btn-primary mt-2">
                                            <i class="fas fa-plus me-2"></i> Add New Student
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
       <footer class="footer bg-white border-top mt-auto py-3">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
        <div class="text-center text-md-start mb-2 mb-md-0">
            <span class="text-muted">© <?php echo date('Y'); ?> Student Management System</span>
        </div>
        <div class="text-center text-md-end">
            <a href="#" class="text-decoration-none text-muted me-3">Privacy Policy</a>
            <a href="#" class="text-decoration-none text-muted me-3">Terms of Service</a>
            <a href="#" class="text-decoration-none text-muted">Contact</a>
        </div>
    </div>
        </footer>
                     

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>