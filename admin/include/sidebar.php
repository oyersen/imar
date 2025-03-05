<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
    // Oturum zaman aşımı süresi (saniye)
    $timeout = 1800; // 30 dakika

    // Oturumun geçerli olup olmadığını kontrol et
    if (!isset($_SESSION['user'])) {
        header('Location: index');
        exit;
    }

    // Zaman aşımını kontrol et
    if (isset($_SESSION['timeout']) && time() > $_SESSION['timeout']) {
        session_destroy();
        header('Location: index.php?timeout=1'); // Zaman aşımı mesajı ile giriş sayfasına yönlendir
        exit;
    }

    // Son işlem zamanını güncelle
    $_SESSION['timeout'] = time() + $timeout;
    $_SESSION['last_activity'] = time();
}
?>
<link rel="stylesheet" href="assets/css/layout.css">
<div class="sidebar">
    <h4 class="sidebar-title"></h4>
    <p>MERHABA, <?= strtoupper($_SESSION['user']) ?>.</p>
    <ul class="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'profil.php' ? 'active' : '' ?>"
                href="profil.php"><i class="fas fa-cog"></i> Profil</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'duyurular.php' ? 'active' : '' ?>"
                href="./duyurular"><i class="fas fa-bullhorn"></i> Duyuru</a>
        </li>
        <?php if (isset($_SESSION['superAdmin']) && $_SESSION['superAdmin'] == 1): ?>
            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'admin.php' ? 'active' : '' ?>" href="./admin"><i
                        class="fas fa-users"></i> Kullanıcılar</a>
            </li>
        <?php endif; ?>
        <li class="nav-item">
            <a class="nav-link logout-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Çıkış</a>
        </li>
    </ul>
</div>