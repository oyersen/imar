<?php
header("Content-Type: application/json");
header("Content-Type: text/html; charset=UTF-8");

setlocale(LC_ALL, 'tr_TR.UTF-8'); // Türkçe karakter desteği
$baseDir = __DIR__ . "/src/files"; // Dosyaların olduğu ana klasör
$response = [];

if (is_dir($baseDir)) {
    $folders = scandir($baseDir);
    foreach ($folders as $folder) {
        if ($folder !== "." && $folder !== ".." && is_dir("$baseDir/$folder")) {
            $folderName = mb_strtoupper($folder, 'UTF-8'); // Türkçe karakter desteğiyle büyük harfe çevir
            $files = array_diff(scandir("$baseDir/$folder"), [".", ".."]);
            $fileList = [];

            foreach ($files as $file) {
                if (is_file("$baseDir/$folder/$file")) {
                    $fileList[] = [
                        "name" => $file,
                        "path" => "/src/files/$folder/$file"
                    ];
                }
            }

            if (!empty($fileList)) {
                $response[$folderName] = $fileList;
            }
        }
    }
}

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
