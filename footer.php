<?php
$conn = new mysqli('localhost', 'root', '', 'gna_store');

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari database
$sections = ['layanan-kami', 'hubungi-kami'];
$content = [];

foreach ($sections as $section) {
    $stmt = $conn->prepare("SELECT content FROM footer_content WHERE section = ?");
    $stmt->bind_param('s', $section);
    $stmt->execute();
    $stmt->bind_result($content[$section]);
    $stmt->fetch();
    $stmt->close();
}

$conn->close();
?>

<style>
        footer {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            padding: 20px;
            background-color: #FFFFFF; 
            border-top: 1px solid #ddd;
            color: #000; /* Text color */
            margin-top: 160px;
        }

        footer div {
            flex: 1;
            min-width: 200px;
            text-align: center;
            margin: 10px 0;
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

        .icons i {
            margin-right: 10px; 
        }

        .icons i:not(:last-child) {
            border-right: 1px solid #ccc; 
            padding-right: 20px; 
        }
</style>

<footer>
         <div id="layanan-kami">
            <h3>Layanan Kami</h3>
            <div id="layanan-kami-text">
                <?php echo nl2br($content['layanan-kami']); ?>
            </div>
        </div>
        
        <div>
            <h3>Temukan Kami</h3>
            <div class="social-icons">
                <a href="https://www.tiktok.com/@g.n.a.id"><i class="bi bi-tiktok"></i></a>
                <a href="https://m.facebook.com/gnaid-100066774738487/"><i class="bi bi-facebook"></i></a>
                <a href="https://www.instagram.com/g.n.a.id/?hl=en"><i class="bi bi-instagram"></i></a>
            </div>
        </div>
        
        <div id="hubungi-kami">
            <h3>Hubungi Kami</h3>
            <div id="hubungi-kami-text">
                <?php echo nl2br($content['hubungi-kami']); ?>
            </div>
        </div>
    </footer>

    <script>

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