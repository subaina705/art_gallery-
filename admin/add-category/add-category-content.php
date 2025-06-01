<?php
// DB connection
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$alert = "";
$alert_type = "success";

// Handle Create
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "create") {
    $category_name = trim($_POST["category_name"]);
    if (!empty($category_name)) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $category_name);
        if ($stmt->execute()) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $alert = "Error: " . $stmt->error;
            $alert_type = "danger";
        }
        $stmt->close();
    } else {
        $alert = "Category name is required.";
        $alert_type = "warning";
    }
}

// Handle Update
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "update") {
    $id = intval($_POST["id"]);
    $name = trim($_POST["category_name"]);
    if (!empty($name)) {
        $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        if ($stmt->execute()) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $alert = "Error: " . $stmt->error;
            $alert_type = "danger";
        }
        $stmt->close();
    } else {
        $alert = "Category name cannot be empty.";
        $alert_type = "warning";
    }
}

// Handle Delete
if (isset($_GET["delete"])) {
    $id = intval($_GET["delete"]);

    $check = $conn->prepare("SELECT COUNT(*) FROM artwork WHERE category_id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $check->bind_result($count);
    $check->fetch();
    $check->close();

    if ($count > 0) {
        $alert = "Error: Cannot delete category. It is associated with one or more artworks.";
        $alert_type = "danger";
    } else {
        $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $alert = "Category deleted successfully.";
            $alert_type = "success";
        } else {
            $alert = "Error deleting category.";
            $alert_type = "danger";
        }
        $stmt->close();
    }
}

// Fetch all categories
$categories = [];
$result = mysqli_query($conn, "SELECT id, name FROM categories ORDER BY id DESC");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
}

$editing_id = isset($_GET["edit"]) ? intval($_GET["edit"]) : null;

mysqli_close($conn);
?>

<?php if (!empty($alert)): ?>
    <div class="alert alert-<?php echo $alert_type; ?> alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($alert); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="">
    <h3 class="mb-5 fw-bold">Manage Categories</h3>

    <!-- Add Category Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" class="row g-3">
                <input type="hidden" name="action" value="create">
                <div class="col-lg-4">
                    <input type="text" name="category_name" class="form-control" placeholder="Enter category name"
                           required>
                </div>
                <div class="col-lg-4">
                    <button type="submit" class="btn btn-primary btn-sm m-0">Add Category</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Category Table -->
    <div class="card">
        <div class="card-body">
            <?php if (count($categories) > 0): ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th style="width: 220px;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($categories as $cat): ?>
                            <tr>
                                <td><?php echo $cat["id"]; ?></td>
                                <td>
                                    <?php if ($editing_id == $cat["id"]): ?>
                                        <form method="POST" class="d-flex gap-2">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="id" value="<?php echo $cat["id"]; ?>">
                                            <input type="text" name="category_name" class="form-control form-control-sm"
                                                   value="<?php echo htmlspecialchars($cat["name"]); ?>" required>
                                            <button type="submit" class="btn btn-success btn-sm">Save</button>
                                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>"
                                               class="btn btn-secondary btn-sm">Cancel</a>
                                        </form>
                                    <?php else: ?>
                                        <?php echo htmlspecialchars($cat["name"]); ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-4">
                                        <?php if ($editing_id != $cat["id"]): ?>
                                            <a href="?edit=<?php echo $cat["id"]; ?>" class="text-black fs-18"><i
                                                        class="fa-solid fa-pen"></i></a>
                                        <?php endif; ?>
                                        <a href="?delete=<?php echo $cat["id"]; ?>" class="text-danger fs-18"
                                           onclick="return confirm('Delete this category?');"><i
                                                    class="fa-solid fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info" role="alert">
                    No categories found!
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
