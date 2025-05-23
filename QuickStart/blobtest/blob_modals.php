<?php


include("classes/connect.php");


$host = "localhost";
$user = "root";
$pass = "";
$dbname = "aiot";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$query = "SELECT Paket2,Paket3, userid, id,Nama FROM user ORDER BY id DESC";
$result = $conn->query($query);

// Data yang akan disimpan
$packet2 = pack("C*", 0x05, 0x07, 0x5D, 0x16, 0x00, 0x01, 0x20, 0x01, 0x91, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x02, 0x00, 0x00, 0x00, 0x85);
$packet3 = pack("C*", 0x08, 0x03, 0x5D, 0x16, 0x00, 0x01, 0x20, 0x01, 0x91, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x02, 0x00, 0x00, 0x00, 0x85, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0xCF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFE, 0xAA, 0xEA, 0xAA, 0xAA, 0xAA, 0xAA, 0x6A, 0x95, 0xA6, 0x55, 0x55, 0x55, 0x55, 0x55, 0x55, 0x55, 0x44, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00);


if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data2FromDB = $row['Paket2'];
        $data3FromDB = $row['Paket3'];
        $useridFromDB = $row['userid'];
        $usernamaFromDB = $row['Nama'];

        echo "User ID: " . $row['id'] . "<br>";
        echo "Data dari DB2 (hex): " . bin2hex($data2FromDB) . "<br>";
        echo "Data dari DB3 (hex): " . bin2hex($data3FromDB) . "<br>";

        echo "Data packet2 (hex): " . bin2hex($packet2) . "<br>";
        echo "Data packet3 (hex): " . bin2hex($packet2) . "<br>";


        if (bin2hex($data2FromDB) === bin2hex($packet2)&&bin2hex($data3FromDB) === bin2hex($packet3)) {
            echo "✅ Data cocok dengan <br>";
            echo "useridFromDB: " . $useridFromDB . "<br>";
            echo "useridFromDB: " . $usernamaFromDB . "<br>";

            $userQuery = "SELECT Nama FROM user WHERE id = ?";
            $stmt = $conn->prepare($userQuery);
            $stmt->bind_param("i", $useridFromDB);
            $stmt->execute();
            $userResult = $stmt->get_result();

            if ($userResult->num_rows > 0) {
                $user = $userResult->fetch_assoc();
                echo "User ID: " . $user['id'] . "<br>";
                echo "Nama: " . $user['Nama'] . "<br><br>";
            } 
        } else {
            echo "❌ Data tidak cocok<br><br>";
        }
    }
} else {
    echo "⚠️ Tidak ada data di database";
}
