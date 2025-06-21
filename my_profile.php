<?php
// Start session
session_start();

// Include DB connection (update with your DB credentials)
include('config/dbcon.php');
include('includes/header.php'); 

// Assuming user ID is stored in session
$user_id = $_SESSION['auth_user']['user_id'];

if (!$user_id) {
    echo "You must be logged in to view this page.";
    exit;
}

// Fetch user info
$sql = "SELECT id, name, email, role_as, created_at FROM customer WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <style>
        body { font-family: Arial;  padding-top: 100px; }
        .profile-box {
            max-width: 400px;
            margin: auto;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            background: #f9f9f9;
            
        }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <div class="profile-box">
        <h2>My Profile</h2>
        <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <!-- <p><strong>Role:</strong> <?= $user['role_as'] == 1 ? 'Admin' : 'User' ?></p> -->
        <!-- <p><strong>Joined:</strong> <?= htmlspecialchars($user['created_at']) ?></p> -->
        <p style="text-align: center;"><a href="edit_profile.php">Edit Profile</a></p>

    </div>
</body>
</html>
<?php include('includes/footer.php'); ?>

