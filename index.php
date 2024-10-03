<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "task27"; 
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$rowsPerPage = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $rowsPerPage;

$validSortColumns = ['id', 'nik', 'nama'];
$sortColumn = isset($_GET['sort']) && in_array($_GET['sort'], $validSortColumns) ? $_GET['sort'] : 'id';
$sortOrder = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'desc' : 'asc';

$totalQuery = "SELECT COUNT(*) AS total FROM people";
$totalResult = $conn->query($totalQuery);
$totalRows = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $rowsPerPage);

$sql = "SELECT * FROM people ORDER BY $sortColumn $sortOrder LIMIT $rowsPerPage OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Styled Table with Pagination</title>
    <style>
        /* General Body Styling */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #00c6ff, #0072ff);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #2F4F4F;
        }

        /* Container Styling */
        .container {
            width: 100%;
            max-width: 900px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            padding: 40px;
            margin: 20px auto;
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        /* Header Styling */
        h2 {
            color: #333;
            font-size: 30px;
            margin-bottom: 20px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 16px;
            text-align: left;
            font-size: 14px;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            position: relative;
        }

        td {
            background-color: #f9f9f9;
            transition: background-color 0.3s ease;
        }

        /* Styling for Hover */
        tr:hover td {
            background-color: #f1f1f1;
        }

        /* Pagination Styling */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            padding: 10px 16px;
            margin: 0 5px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            text-decoration: none;
            color: #007bff;
            border-radius: 4px;
            font-size: 14px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .pagination a:hover {
            background-color: #007bff;
            color: white;
            transform: scale(1.05);
        }

        .pagination a[style="pointer-events: none;"] {
            color: #ccc;
            background-color: #f0f0f0;
        }

        /* Active Page Styling */
        .pagination a[style="font-weight: bold;"] {
            font-weight: bold;
            background-color: #007bff;
            color: white;
        }

        /* Logout Button Styling */
        .logout-link {
            display: inline-block;
            margin: 20px 0;
            padding: 12px 24px;
            background-color: #ff4d4d;
            color: white;
            font-weight: bold;
            border-radius: 6px;
            text-decoration: none;
            text-align: center;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .logout-link:hover {
            background-color: #e60000;
        }

        /* Responsive Layout */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            table th, table td {
                padding: 12px;
                font-size: 12px;
            }

            .pagination a {
                padding: 8px 12px;
                font-size: 12px;
            }

            .logout-link {
                font-size: 14px;
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Data Makhluk</h2>
        
        <table id="myTable">
            <thead>
                <tr>
                    <th><a href="?page=<?php echo $page; ?>&sort=id&order=<?php echo $sortOrder === 'asc' ? 'desc' : 'asc'; ?>">ID</a></th>
                    <th><a href="?page=<?php echo $page; ?>&sort=nik&order=<?php echo $sortOrder === 'asc' ? 'desc' : 'asc'; ?>">NIK</a></th>
                    <th><a href="?page=<?php echo $page; ?>&sort=nama&order=<?php echo $sortOrder === 'asc' ? 'desc' : 'asc'; ?>">Nama</a></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['nik'] . "</td>";
                        echo "<td>" . $row['nama'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No data available</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="pagination">
            <a href="?page=<?php echo max(1, $page - 1); ?>&sort=<?php echo $sortColumn; ?>&order=<?php echo $sortOrder; ?>" <?php if ($page == 1) echo 'style="pointer-events: none;"'; ?>>Prev</a>
            
            <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                <a href="?page=<?php echo $i; ?>&sort=<?php echo $sortColumn; ?>&order=<?php echo $sortOrder; ?>" 
                <?php if ($i == $page) echo 'style="font-weight: bold;"'; ?>>
                <?php echo $i; ?>
                </a>
            <?php } ?>
            
            <a href="?page=<?php echo min($totalPages, $page + 1); ?>&sort=<?php echo $sortColumn; ?>&order=<?php echo $sortOrder; ?>" <?php if ($page == $totalPages) echo 'style="pointer-events: none;"'; ?>>Next</a>
        </div>
        
        <!-- Logout Button at Bottom -->
        <div class="logout-container">
            <a href="logout.php" class="logout-link">Logout</a>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
