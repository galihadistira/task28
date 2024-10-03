<?php
session_start();
session_unset();
session_destroy();
header("Location: login.php");
exit();
?>
<?php
session_start();

// Jika user belum login, redirect ke halaman login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "task27";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data dari database untuk ditampilkan di tabel
$sql = "SELECT id, name FROM people";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data People</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="table-container">
        <h3>Data People</h3>
        <a href="logout.php" class="logout-link">Logout</a>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID</th>
                    <th>Name</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $no = 1;
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $no++ . "</td>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No data available</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="pagination">
            <a href="#">Prev</a>
            <a href="#">1</a>
            <a href="#">2</a>
            <a href="#">Next</a>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>

<!-- File logout.php -->
<?php
session_start();
session_unset();
session_destroy();
header("Location: login.php");
exit();
?>
