<?php
    include_once "koneksi.php";

    $firstname = FILTER_INPUT(INPUT_GET, 'fname');
    $email = FILTER_INPUT(INPUT_GET, 'email');
        try {
            $sql = "INSERT INTO mydb_guest (first_name, email) VALUES (:first_name, :email)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':first_name' => $firstname,
                ':email' => $email
            ]);
            echo "New record created successfully";
        } catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
        $conn = null;
        header("Location: index.php?msg=Data berhasil ditampilkan");

    if ($keyword != '') {
        $sql = "SELECT user_id, first_name, email FROM mydb_guest WHERE first_name LIKE :keyword OR email LIKE :keyword";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':keyword', '%$keyword%', PDO::PARAM_STR);
        $stmt->execute();
    } else {
        $sql = "SELECT user_id, first_name, email FROM mydb_guest";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }
?>