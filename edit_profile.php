<?php
session_start();
include('config/dbcon.php');
include('includes/header.php');

// Get logged-in user ID
$user_id = $_SESSION['auth_user']['user_id'] ?? null;

if (!$user_id) {
    echo "You must be logged in to edit your profile.";
    exit;
}

// Fetch current user data
$sql = "SELECT name, email FROM customer WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "User not found.";
    exit;
}

$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    if ($name && $email) {
        $update_sql = "UPDATE customer SET name = ?, email = ? WHERE id = ?";
        $update_stmt = $con->prepare($update_sql);
        $update_stmt->bind_param("ssi", $name, $email, $user_id);

        if ($update_stmt->execute()) {
            echo "<script>alert('Profile updated successfully.'); window.location.href = 'my_profile.php';</script>";
            exit;
        } else {
            echo "<p style='color:red;'>Update failed. Try again.</p>";
        }
    } else {
        echo "<p style='color:red;'>All fields are required.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <style>
        body { font-family: Arial; padding-top: 100px; }
        .form-box {
            max-width: 400px;
            margin: auto;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            background: #f9f9f9;
        }
        h2 { text-align: center; }
        label { display: block; margin-top: 10px; }
        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        button {
            margin-top: 15px;
            width: 100%;
            padding: 10px;
            border: none;
            background-color: #007BFF;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Edit Profile</h2>
        <form method="POST">
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>

<?php include('includes/footer.php'); ?>
