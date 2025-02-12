<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REHBER</title>

    <link rel="canonical" href="https://www.imar.istanbul/tr" />
    <link rel="shortcut icon" href="https://www.imar.istanbul/assets/images/favicon.ico" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./src/js/xlsx.full.min.js"></script>
    <link rel="stylesheet" href="./src/css/bootstrap.css">
    <script src="./src/js/pagination.js"></script>
    <link rel="stylesheet" href="./src/css/pagination.css" />
    <link rel="stylesheet" href="./src/css/rehber.css">


</head>

<body>
    <div class="container">
        <div class="header">
            <img id="ibb_logo" src="./src/img/ibb_logo.png">
            <h2>
                Personel Listesi</h2>
            <a href="./"> <img id="imar_logo" src="./src/img/favicon.ico"></a>
        </div>
        <input type="text" id="search" class="form-control" placeholder="Personel Ara..." autocomplete="off">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Sicil No</th>
                        <th>Ad</th>
                        <th>Soyad</th>
                        <th>Ünvan</th>
                        <th>Departman</th>
                        <th>Telefon</th>
                    </tr>
                </thead>
                <tbody id="personel-list">
                    <tr id="no-results" style="display: none;">
                        <td colspan="6" style="text-align: center; font-weight: bold; color: red;">Personel
                            Bulunmamaktadır.</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="pagination"></div>
    </div>

    <script src="./src/js/rehber.js"></script>

</body>

</html>