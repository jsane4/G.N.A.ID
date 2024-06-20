<?php
$con = new mysqli('localhost', 'root', '', 'gna_store');

if ($con->connect_error) {
    die("Koneksi gagal: " . $con->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $section = $_POST['section'];
    $content = $_POST['content'];

    $stmt = $con->prepare("UPDATE footer_content SET content = ? WHERE section = ?");
    $stmt->bind_param('ss', $content, $section);

    if ($stmt->execute()) {
        echo "Perubahan berhasil disimpan";
    } else {
        echo "Gagal menyimpan perubahan";
    }

    $stmt->close();
    exit;
}

$sections = ['layanan-kami', 'hubungi-kami'];
$content = [];

foreach ($sections as $section) {
    $stmt = $con->prepare("SELECT content FROM footer_content WHERE section = ?");
    $stmt->bind_param('s', $section);
    $stmt->execute();
    $stmt->bind_result($content[$section]);
    $stmt->fetch();
    $stmt->close();
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer with Edit Function</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        footer {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            padding: 20px;
            background-color: #FFFFFF;
            border-top: 1px solid #ddd;
            color: #000;
            margin-top: 320px;
        }

        footer div {
            flex: 1;
            min-width: 200px;
            text-align: center;
            margin: 10px 0;
            position: relative; /* Added for floating button positioning */
        }

        .bi-whatsapp {
            color: #25D366;
            font-size: 50px;
        }

        .bi-cart-fill,
        .bi-person-fill,
        .bi-list {
            color: black;
            font-size: 30px;
        }

        .edit-button {
            position: right;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }

        .floating-editor {
            display: none;
            position: absolute;
            top: 40px;
            right: 10px;
            background-color: #fff;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .floating-editor textarea {
            width: 100%;
        }
    </style>
</head>
<body>
    <footer class="d-flex justify-content-between align-items-center p-3">
        <div id="layanan-kami">
            <h3>Layanan Kami</h3>
            <div id="layanan-kami-text">
                <?php echo nl2br($content['layanan-kami']); ?>
            </div>
            <div class="edit-button" onclick="editContent('layanan-kami')">✎</div>
            <div id="layanan-kami-edit" class="floating-editor">
                <textarea id="layanan-kami-edit-textarea" class="form-control" rows="5"></textarea>
                <button class="btn btn-primary" onclick="saveEdit('layanan-kami')">Simpan</button>
                <button class="btn btn-secondary" onclick="cancelEdit('layanan-kami')">Batal</button>
            </div>
        </div>

        <div id="temukan-kami">
            <h3>Temukan Kami</h3>
            <div id="temukan-kami-text">
                <div class="social-icons">
                    <a href="https://www.tiktok.com/@g.n.a.id"><i class="bi bi-tiktok"></i></a>
                    <a href="https://m.facebook.com/gnaid-100066774738487/"><i class="bi bi-facebook"></i></a>
                    <a href="https://www.instagram.com/g.n.a.id/?hl=en"><i class="bi bi-instagram"></i></a>
                </div>
            </div>
        </div>

        <div id="hubungi-kami">
            <h3>Hubungi Kami</h3>
            <div id="hubungi-kami-text">
                <?php echo nl2br($content['hubungi-kami']); ?>
            </div>
            <div class="edit-button" onclick="editContent('hubungi-kami')">✎</div>
            <div id="hubungi-kami-edit" class="floating-editor">
                <textarea id="hubungi-kami-edit-textarea" class="form-control" rows="5"></textarea>
                <button class="btn btn-primary" onclick="saveEdit('hubungi-kami')">Simpan</button>
                <button class="btn btn-secondary" onclick="cancelEdit('hubungi-kami')">Batal</button>
            </div>
        </div>
    </footer>

    <script>
        function editContent(section) {
            document.getElementById(`${section}-text`).style.display = 'none';
            document.getElementById(`${section}-edit`).style.display = 'block';
            document.getElementById(`${section}-edit-textarea`).value = document.getElementById(`${section}-text`).innerText.trim();
        }

        function cancelEdit(section) {
            document.getElementById(`${section}-edit`).style.display = 'none';
            document.getElementById(`${section}-text`).style.display = 'block';
        }

        function saveEdit(section) {
            const newText = document.getElementById(`${section}-edit-textarea`).value;
            
            // Make an AJAX call to save changes to the database
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById(`${section}-text`).innerHTML = newText.replace(/\n/g, '<br>');
                    cancelEdit(section);
                }
            };
            xhr.send(`section=${section}&content=${encodeURIComponent(newText)}`);
        }
    </script>
</body>
</html>
