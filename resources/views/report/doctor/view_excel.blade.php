<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Excel View</title>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/luckysheet@latest/dist/plugins/css/pluginsCss.css' />
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/luckysheet@latest/dist/plugins/plugins.css' />
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/luckysheet@latest/dist/css/luckysheet.css' />
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/luckysheet@latest/dist/assets/iconfont/iconfont.css' />
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            background-color: #f4f4f4;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        }
        #luckysheet {
            position: absolute;
            top: 50px; /* beri jarak untuk tombol close */
            left: 0;
            width: 100%;
            height: calc(100% - 50px);
        }
        .top-bar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 50px;
            background-color: #ffffff;
            border-bottom: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 15px;
            box-sizing: border-box;
            z-index: 999;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .top-bar-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }
        .close-btn {
            background-color: #e74c3c;
            color: #fff;
            border: none;
            padding: 8px 15px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.2s ease;
        }
        .close-btn:hover {
            background-color: #c0392b;
        }
        .error-message {
            text-align: center;
            padding-top: 50px;
            font-size: 18px;
            color: #777;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <div class="top-bar-title">Melihat File Excel: {{ $file ?? 'Nama File' }}</div>
    <button class="close-btn" onclick="window.close()">Tutup</button>
</div>

<div id="luckysheet"></div>

<script src="https://cdn.jsdelivr.net/npm/luckysheet@latest/dist/plugins/js/plugin.js"></script>
<script src="https://cdn.jsdelivr.net/npm/luckysheet@latest/dist/luckysheet.umd.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const officer = encodeURIComponent("{{ $officer }}");
    const prov    = encodeURIComponent("{{ $prov }}");
    const kota    = encodeURIComponent("{{ $kota }}");
    const name    = encodeURIComponent("{{ $name }}");

    const dataFetchUrl = `/report/doctor/excel-data/${officer}/${prov}/${kota}/${name}`;

    fetch(dataFetchUrl)
        .then(res => res.json())
        .then(response => {
            console.log("Response:", response);

            if (response.error) {
                document.getElementById('luckysheet').innerHTML =
                    '<div class="error-message">' + response.error + '</div>';
                return;
            }

            if (!Array.isArray(response) || response.length === 0) {
                document.getElementById('luckysheet').innerHTML =
                    '<div class="error-message">Data kosong atau format tidak valid</div>';
                return;
            }

            const sheet = response[0];
            const originalData = sheet.data;

            if (!sheet || !originalData || !Array.isArray(originalData)) {
                document.getElementById('luckysheet').innerHTML =
                    '<div class="error-message">Format sheet tidak valid</div>';
                return;
            }

            let celldata = [];
            for (let r = 0; r < originalData.length; r++) {
                for (let c = 0; c < originalData[r].length; c++) {
                    const cellValue = originalData[r][c];
                    if (cellValue !== null && cellValue !== undefined) {
                        celldata.push({r, c, v: {v: cellValue, m: cellValue.toString()}});
                    }
                }
            }

            luckysheet.create({
                container: 'luckysheet',
                data: [{name: sheet.name || 'Sheet1', celldata: celldata}],
                showinfobar: false,
                showtoolbar: true,
                showsheetbar: true,
                allowEdit: true,
                lang: 'en',
                rowCount: 1000,
                columnCount: 50,
            });
        })
        .catch(err => {
            document.getElementById('luckysheet').innerHTML =
                '<div class="error-message">Terjadi kesalahan saat memuat data: ' + err.message + '</div>';
            console.error(err);
        });
});
</script>
</body>
</html>
