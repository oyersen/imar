<?php

$relativeDbPath = '../../src/db/imarphs.db'; // Göreceli yol
$absoluteDbPath = 'E:\xampp\htdocs\imarPHS\src\db\imarphs.db';// Mutlak yol (burayı kendi yolunuzla değiştirin)

echo "<h2>Göreceli Yol Kontrolü</h2>";
echo "<p>Göreceli yol: " . $relativeDbPath . "</p>";

$realRelativePath = realpath($relativeDbPath);
echo "<p>realpath() sonucu (göreceli): " . ($realRelativePath ? $realRelativePath : "Bulunamadı") . "</p>";

if ($realRelativePath) {
    echo "<p>Dosya var mı? " . (file_exists($realRelativePath) ? "Evet" : "Hayır") . "</p>";
    echo "<p>Dizin var mı? " . (is_dir(dirname($realRelativePath)) ? "Evet" : "Hayır") . "</p>";
}

echo "<hr>";

echo "<h2>Mutlak Yol Kontrolü</h2>";
echo "<p>Mutlak yol: " . $absoluteDbPath . "</p>";

$realAbsolutePath = realpath($absoluteDbPath);
echo "<p>realpath() sonucu (mutlak): " . ($realAbsolutePath ? $realAbsolutePath : "Bulunamadı") . "</p>";

if ($realAbsolutePath) {
    echo "<p>Dosya var mı? " . (file_exists($realAbsolutePath) ? "Evet" : "Hayır") . "</p>";
    echo "<p>Dizin var mı? " . (is_dir(dirname($realAbsolutePath)) ? "Evet" : "Hayır") . "</p>";
}

echo "<hr>";

// Veritabanı bağlantısı denemesi (sadece hata mesajını kontrol etmek için)
echo "<h2>Veritabanı Bağlantısı Denemesi</h2>";
try {
    $db = new PDO('sqlite:' . ($realRelativePath ? $realRelativePath : $absoluteDbPath));
    echo "<p>Veritabanı bağlantısı başarılı!</p>";
} catch (PDOException $e) {
    echo "<p>Veritabanı bağlantısı başarısız: " . $e->getMessage() . "</p>";
}
