<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

// Get current admin data
$admin_id = $_SESSION['admin']['id'];
$stmt = $conn->prepare("SELECT username, profile_image, password FROM admin WHERE id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = isset($_POST['username']) ? trim($_POST['username']) : $admin['username'];
    $current_password = isset($_POST['current_password']) ? trim($_POST['current_password']) : '';
    $new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';
    $confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';
    $update_fields = [];
    $params = [];
    $types = '';

    // Username update
    if ($new_username !== $admin['username'] && $new_username !== '') {
        $update_fields[] = "username = ?";
        $params[] = $new_username;
        $types .= 's';
    }

    // Password update (only if any password field is filled)
    if (!empty($current_password) || !empty($new_password) || !empty($confirm_password)) {
        // Verify current password (plain text comparison since passwords aren't hashed)
        if ($current_password === $admin['password']) {
            if (!empty($new_password) && $new_password === $confirm_password) {
                $update_fields[] = "password = ?";
                $params[] = $new_password;
                $types .= 's';
            } elseif ($new_password !== $confirm_password) {
                $error_message = "New passwords do not match.";
            } else {
                $error_message = "Please enter a new password and confirm it.";
            }
        } else {
            $error_message = "Current password is incorrect.";
        }
    }

    // Profile image update
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = "uploads/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_extension = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');

        if (in_array($file_extension, $allowed_extensions)) {
            $new_filename = uniqid() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;

            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
                // Delete old image if it exists
                if (!empty($admin['profile_image']) && file_exists($admin['profile_image'])) {
                    unlink($admin['profile_image']);
                }
                $update_fields[] = "profile_image = ?";
                $params[] = $upload_path;
                $types .= 's';
            } else {
                $error_message = "Error uploading file. Please try again.";
            }
        } else {
            $error_message = "Invalid file type. Please upload JPG, JPEG, PNG, or GIF.";
        }
    }

    // If there are no errors and at least one field to update
    if (empty($error_message) && !empty($update_fields)) {
        $params[] = $admin_id;
        $types .= 'i';

        $sql = "UPDATE admin SET " . implode(", ", $update_fields) . " WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            $success_message = "Profile updated successfully!";
            // Refresh admin data
            $stmt = $conn->prepare("SELECT username, profile_image, password FROM admin WHERE id = ?");
            $stmt->bind_param("i", $admin_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $admin = $result->fetch_assoc();
        } else {
            $error_message = "Error updating profile: " . $conn->error;
        }
    } elseif (empty($error_message) && empty($update_fields)) {
        // No changes were made
        $error_message = "No changes to update.";
    }
}
?>

<!-- FontAwesome for eye icon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

<div>
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($success_message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($error_message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <h3 class="fw-bold mb-5">Profile Settings</h3>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row mb-4 align-items-center">
                            <div class="col-md-12">
                                <?php if (!empty($admin['profile_image'])): ?>
                                    <img src="<?= htmlspecialchars($admin['profile_image']) ?>"
                                         alt="Profile Image"
                                         class="rounded-circle img-fluid"
                                         style="width: 100px; height: 100px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto"
                                         style="width: 100px; height: 100px;">
                                        <i class="fas fa-user fa-2x text-secondary"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="mt-2">
                                    <input type="file" class="form-control form-control-sm" name="profile_image"
                                           accept="image/*">
                                    <p class="text-muted fs-12 pt-2">Max 2MB (JPG, PNG, GIF)</p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username"
                                           value="<?= htmlspecialchars($admin['username']) ?>" required>
                                </div>
                            </div>
                        </div>
                        <h5 class="mb-3 fw-bold">Change Password</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3 position-relative">
                                <label for="current_password" class="form-label">Current Password</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control password-field" id="current_password"
                                           name="current_password" value="<?= htmlspecialchars($admin['password']) ?>">
                                    <span class="toggle-password position-absolute" style="right: 10px;top: 7px"
                                          toggle="#current_password"><i class="fa fa-eye"></i></span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3 position-relative">
                                <label for="new_password" class="form-label">New Password</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control password-field" id="new_password"
                                           name="new_password">
                                    <span class="toggle-password position-absolute" style="right: 10px;top: 7px"
                                          toggle="#new_password"><i class="fa fa-eye"></i></span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3 position-relative">
                                <label for="confirm_password" class="form-label">Confirm New Password</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control password-field" id="confirm_password"
                                           name="confirm_password">
                                    <span class="toggle-password position-absolute" style="right: 10px;top: 7px"
                                          toggle="#confirm_password"><i class="fa fa-eye"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.toggle-password').forEach(function (eye) {
        eye.addEventListener('click', function () {
            var input = document.querySelector(this.getAttribute('toggle'));
            if (input.type === 'password') {
                input.type = 'text';
                this.querySelector('i').classList.remove('fa-eye');
                this.querySelector('i').classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                this.querySelector('i').classList.remove('fa-eye-slash');
                this.querySelector('i').classList.add('fa-eye');
            }
        });
    });
</script>
