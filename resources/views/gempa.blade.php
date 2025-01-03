<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        *{
            margin:0;
            padding: 0;
            box-sizing:border-box;
        }
        #map { height: 100vh; }
    </style>
     <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>
<body>
    <div id="map"></div>
    <script>
        // Membuat peta dengan lokasi awal di Indonesia dan zoom level 5
        var map = L.map('map').setView([-0.3155398750904368, 117.1371634207888], 5);

        // Menambahkan tile layer dari OpenStreetMap ke peta
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 5, // Zoom maksimum yang diizinkan
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Mengambil data gempa dari API BMKG dalam format JSON
        const datas = {!! file_get_contents("https://data.bmkg.go.id/DataMKG/TEWS/gempaterkini.json") !!};
        const gempas = datas.Infogempa.gempa; // Ekstrak array data gempa
        console.log(gempas); // Debugging: Cetak data gempa ke konsol

        let numberQuake = 1; // Counter untuk nomor gempa

        // Iterasi melalui setiap data gempa
        gempas.forEach(gempa => {
            // Memisahkan koordinat (latitude dan longitude) dari string
            const splitedCoordinate = gempa.Coordinates.split(',');
            const latitude = splitedCoordinate[0]; // Koordinat latitude
            const longitude = splitedCoordinate[1]; // Koordinat longitude

            // Membuat konten popup untuk setiap marker
            const popupContent = `
                <h3>Gempa Ke ${numberQuake}</h3>
                <p>
                    Wilayah: ${gempa.Wilayah}<br>
                    Waktu: ${gempa.Tanggal}, ${gempa.Jam}<br>
                    Kedalaman: ${gempa.Kedalaman}<br>
                    Kekuatan: ${gempa.Magnitude} SR<br>
                    Potensi: ${gempa.Potensi}
                </p>
            `;

            // Menambahkan marker ke peta dengan koordinat yang sesuai
            L.marker([latitude, longitude])
                .addTo(map) // Tambahkan marker ke peta
                .bindPopup(popupContent); // Tambahkan popup ke marker

            numberQuake++; // Increment nomor gempa
        });
    </script>
</body>
</html>