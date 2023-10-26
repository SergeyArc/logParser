<?php

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

$filename = 'test.txt';
$dbHost = 'mariadb';
$dbName = 'trend';
$dbUsername = 'trend';
$dbPassword = 'trend-pwd';
$table = 'data';

try {
    $fileParser = new FileParser($filename);
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage();
    exit();
}

$databaseHandler = new DatabaseHandler($dbHost, $dbName, $dbUsername, $dbPassword, $table);

if (isset($_POST["submit"])) {
    $uploaddir = 'uploads/';
    $uploadfile = $uploaddir . basename($_FILES['file']['name']);

    $fileExt = strtolower(pathinfo($uploadfile, PATHINFO_EXTENSION));
    if ($fileExt !== "txt") {
        exit("только txt-файлы");
    }

    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
        foreach ($fileParser->parseFile() as $data) {
            $databaseHandler->insertData($data);
        }
        echo "Файл успешно загружен";
    } else {
        exit("ошибка при загрузке файла");
    }
}

?>

<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="file" id="fileToUpload">
    <br>
    <input type="submit" name="submit" value="submit">
</form>