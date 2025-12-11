<?php
$errors = [];
$imgName = "";

// Khi form submit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $account = $_POST['account'];
    $pwd     = $_POST['pwd'];
    $repwd   = $_POST['repwd'];
    $gt      = $_POST['gt'];
    $st      = $_POST['st'];
    $tinh    = $_POST['tinh'];

    // 1. Kiểm tra mật khẩu trùng nhau
    if ($pwd !== $repwd) {
        $errors[] = "Mật khẩu và Nhập lại mật khẩu không trùng nhau.";
    }

    // 2. Kiểm tra file hình nếu có
    if (!empty($_FILES['img']['name'])) {
        $imgName = $_FILES['img']['name'];
        $ext = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
        $allowed = ["jpg", "jpeg", "png", "gif", "bmp"];

        if (!in_array($ext, $allowed)) {
            $errors[] = "File hình phải là .jpg, .jpeg, .png, .gif hoặc .bmp.";
        }
    }

    // Nếu không có lỗi -> in thông tin
    if (empty($errors)) {
        echo "<h2>Thông tin thành viên</h2>";
        echo "Tên đăng nhập: $account <br>";
        echo "Giới tính: $gt <br>";
        echo "Sở thích: $st <br>";
        echo "Tỉnh: $tinh <br>";
        echo $imgName ? "Hình ảnh: $imgName <br>" : "Không chọn hình ảnh<br>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký thành viên</title>
</head>
<body>

<?php
// In lỗi (nếu có)
if (!empty($errors)) {
    echo "<div style='color:red;'><b>Lỗi:</b><ul>";
    foreach ($errors as $err) echo "<li>$err</li>";
    echo "</ul></div>";
}
?>

<fieldset>
    <legend>Form thông tin thành viên</legend>

    <form action="" method="post" enctype="multipart/form-data">
        <label>Tên đăng nhập (*)</label><br>
        <input type="text" name="account" required><br>

        <label>Mật khẩu (*)</label><br>
        <input type="password" name="pwd" required><br>

        <label>Nhập lại mật khẩu (*)</label><br>
        <input type="password" name="repwd" required><br>

        <label>Giới tính (*)</label><br>
        <label>Nam</label>
        <input type="radio" name="gt" value="Nam" required>
        <label>Nữ</label>
        <input type="radio" name="gt" value="Nữ"><br>

        <label>Sở thích</label><br>
        <input type="text" name="st"><br>

        <label>Hình ảnh (tùy chọn)</label><br>
        <input type="file" name="img" accept=".jpg,.jpeg,.png,.gif,.bmp"><br>

        <label>Tỉnh (*)</label><br>
        <select name="tinh" required>
            <option value="">-- Chọn tỉnh --</option>
            <option value="Ha Noi">Ha Noi</option>
            <option value="Ho Chi Minh">Ho Chi Minh</option>
            <option value="An Giang">An Giang</option>
            <option value="Tien Giang">Tien Giang</option>
            <option value="Thanh Hoa">Thanh Hoa</option>
        </select><br><br>

        <input type="submit" value="Gửi">
        <input type="reset" value="Xóa">
    </form>
</fieldset>

</body>
</html>
