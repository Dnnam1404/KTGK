<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'database.php';

// Lấy mã sinh viên từ URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $maSV = $_GET['id'];

    // Truy vấn để lấy thông tin chi tiết sinh viên theo mã số sinh viên
    $stmt = $conn->prepare("
        SELECT sv.*, nh.TenNganh 
        FROM SinhVien sv 
        LEFT JOIN NganhHoc nh ON sv.MaNganh = nh.MaNganh 
        WHERE sv.MaSV = :maSV
    ");
    $stmt->bindParam(':maSV', $maSV, PDO::PARAM_STR);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        die("Không tìm thấy sinh viên với mã số sinh viên: $maSV");
    }
} else {
    die("Mã số sinh viên không được cung cấp.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Sinh Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">QUẢN LÝ SINH VIÊN</a>
            <div class="navbar-nav">
                <a class="nav-link active" href="index.php">Sinh Viên</a>
                <a class="nav-link" href="hocphan.php">Học Phần</a>
                <?php if (isset($_SESSION['masv'])): ?>
                    <a class="nav-link" href="dangky.php">Đăng Ký</a>
                    <a class="nav-link" href="logout.php">Đăng Xuất</a>
                <?php else: ?>
                    <a class="nav-link" href="login.php">Đăng Nhập</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Chi Tiết Sinh Viên</h2>

        <table class="table table-bordered">
            <tr>
                <th>Mã Sinh Viên</th>
                <td><?php echo htmlspecialchars($student['MaSV']); ?></td>
            </tr>
            <tr>
                <th>Họ Tên</th>
                <td><?php echo htmlspecialchars($student['HoTen']); ?></td>
            </tr>
            <tr>
                <th>Giới Tính</th>
                <td><?php echo htmlspecialchars($student['GioiTinh']); ?></td>
            </tr>
            <tr>
                <th>Ngày Sinh</th>
                <td><?php echo date('d/m/Y', strtotime($student['NgaySinh'])); ?></td>
            </tr>
            <tr>
                <th>Hình</th>
                <td>
                    <?php if ($student['Hinh']): ?>
                        <img src="<?php echo htmlspecialchars($student['Hinh']); ?>" alt="Student Image" style="max-width: 150px;">
                    <?php else: ?>
                        <img src="default-avatar.jpg" alt="No Image" style="max-width: 150px;">
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Ngành</th>
                <td><?php echo htmlspecialchars($student['TenNganh']); ?></td>
            </tr>
        </table>

        <a href="index.php" class="btn btn-primary">Quay lại</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>