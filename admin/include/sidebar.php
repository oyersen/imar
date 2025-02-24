<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="sidebar">
    <h4 class="sidebar-title">ADMIN PANELİ</h4>
    <p>MERHABA, <?= strtoupper($_SESSION['user'])  ?>.</p>
    <ul class="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link active" href="./dashboard"><i class="fas fa-bullhorn"></i> Duyuru</a>
        </li>
        <?php if (isset($_SESSION['superAdmin']) && $_SESSION['superAdmin'] == 1): ?>
            <li class="nav-item">
                <a class="nav-link" href="kullanici"><i class="fas fa-users"></i> Kullanıcılar</a>
            </li>
        <?php endif; ?>
        <li class="nav-item">
            <a class="nav-link" href="#"><i class="fas fa-cog"></i> Ayarlar</a>
        </li>
        <li class="nav-item">
            <a class="nav-link logout-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Çıkış</a>
        </li>
    </ul>
</div>