<?php
session_start();

$upload_directory = 'uploads/temp/';
if (!is_dir($upload_directory)) {
    mkdir($upload_directory, 0777, true);
}

if (!isset($_SESSION['uploaded_files'])) {
    $_SESSION['uploaded_files'] = [];
}

if (!empty($_FILES['archivos']['name'][0])) {
    foreach ($_FILES['archivos']['tmp_name'] as $key => $tmp_name) {
        $file_name = basename($_FILES['archivos']['name'][$key]);
        $file_path = $upload_directory . uniqid() . '_' . $file_name;

        if (move_uploaded_file($tmp_name, $file_path)) {
            $_SESSION['uploaded_files'][] = $file_path;
        }
    }
    echo json_encode($_SESSION['uploaded_files']);
} else {
    echo json_encode([]);
}
?>
