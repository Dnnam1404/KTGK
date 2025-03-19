<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'database.php';

// Fetch all students with their major information
$stmt = $conn->prepare("
    SELECT sv.*, nh.TenNganh 
    FROM SinhVien sv 
    LEFT JOIN NganhHoc nh ON sv.MaNganh = nh.MaNganh
");
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Sinh Viên</title>
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
        <h2>TRANG SINH VIÊN</h2>
        <a href="create.php" class="btn btn-primary mb-3">Thêm Sinh Viên</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>MSSV</th>
                    <th>Họ Tên</th>
                    <th>Giới Tính</th>
                    <th>Ngày Sinh</th>
                    <th>Hình</th>
                    <th>Ngành</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($student['MaSV']); ?></td>
                        <td><?php echo htmlspecialchars($student['HoTen']); ?></td>
                        <td><?php echo htmlspecialchars($student['GioiTinh']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($student['NgaySinh'])); ?></td>
                        <td>
                            <?php if ($student['Hinh']): ?>
                                <img src="<?php echo htmlspecialchars($student['Hinh']); ?>"
                                    alt="Student Image" style="max-width: 100px;">
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($student['TenNganh']); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $student['MaSV']; ?>" class="btn btn-sm btn-primary">Sửa</a>
                            <a href="detail.php?id=<?php echo $student['MaSV']; ?>" class="btn btn-sm btn-info">Chi tiết</a>
                            <a href="delete.php?id=<?php echo $student['MaSV']; ?>"
                                class="btn btn-sm btn-danger"
                                onclick="return confirm('Bạn có chắc muốn xóa sinh viên này?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>