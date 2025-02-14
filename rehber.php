<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REHBER</title>

    <link rel="canonical" href="https://www.imar.istanbul/tr" />
    <link rel="shortcut icon" href="https://www.imar.istanbul/assets/images/favicon.ico" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

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
                İletişim Listesi</h2>
            <a href="./"> <img id="imar_logo" src="./src/img/favicon.ico"></a>
        </div>
        <input type="text" id="search" class="form-control" placeholder="Personel Ara..." autocomplete="off">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Adı</th>
                        <th>Soyadı</th>
                        <th>Görevi</th>
                        <th>Dahili No</th>
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
        <div id="pagination" style="visibility:hidden;"></div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="personelModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Personel Detayı</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body">
                    <img id="personelImage" src="" alt="Personel Resmi" class="img-fluid mb-3">

                    <p><strong>Adı:</strong> <span id="modalAd"></span></p>
                    <p><strong>Soyadı:</strong> <span id="modalSoyad"></span></p>
                    <p><strong>Görevi:</strong> <span id="modalGorevi"></span></p>

                    <p><strong>Dahili No:</strong> <span id="modalTelefon"></span></p>
                </div>
            </div>
        </div>
    </div>

    <script src="./src/js/rehber.js"></script>

</body>

</html>