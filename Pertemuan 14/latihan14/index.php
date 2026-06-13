<!--2572019-Darren Nicholas Heng-->
<?php
include_once "koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tes PHP</title>
</head>
<body>
    <h1>this is  my first PHP site</h1>
    
    <?php 
    echo "Ini dari PHP.";   
    $nama = "Darren";
    echo "<p>Hello, ".$nama.".</p>";
    ?>

    <h2>Haiii, <?php echo $nama;?>.</h2>

    <fieldset>
        <legend>Isian Data</legend>
        <form action ="proses.php" method="get">
            <input type="text" name ="fname" placeholder="First Name">
            <input type="email" name="email" placeholder="Email">
            <input type="submit" name="btnSubmint" value="Simpan">
        </form>
    </fieldset>

    <?php
    $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : "";
    ?>
    <form action="index.php" method="get">
        <input type="text" name="keyword" value="<?= htmlspecialchars($keyword); ?>" placeholder="Cari nama atau email">
        <input type="submit" name="btnCari" value="Cari">
    </form>
    
    <?php
        $msg = isset($_GET['msg']) ? trim($_GET['msg']) : "";
        echo "<span style='color:red'>" . $msg . "</span>";

        try { 
            if ($keyword != '') {
                $sql = "SELECT user_id, first_name, email FROM mydb_guest WHERE first_name LIKE :keyword OR email LIKE :keyword";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
            } else {
                $sql = "SELECT user_id, first_name, email FROM mydb_guest";
                $stmt = $conn->prepare($sql);
            }
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo "<table><tr><th>ID</th><th>Firstname</th><th>Email</th></tr>";
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . $row['user_id'] . "</td>";
                    echo "<td>" . $row['first_name'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "</tr>";
                    }
                echo "</table>";
                unset($result);
            } else {
                echo "No records found.";
            }
            } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            }
        $conn = null;
        
    ?>
</body>
</html>