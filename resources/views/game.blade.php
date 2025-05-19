<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8" />
<title>Game</title>
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>
<style>
    #map { height: 600px; width: 800px; margin: auto; }
    body { font-family: Arial, sans-serif; text-align: center; padding: 20px; }
    #result { font-size: 24px; margin-top: 20px; }
</style>
</head>
<body>

<h1>Click on the right country:</h1>
<h2>{{ $land['name'] }}</h2>

<div id="map"></div>
<div id="result"></div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    const correctCountryCode = "{{ $land['code'] }}";

    const map = L.map('map', {
        zoomControl: false,
        scrollWheelZoom: false,
        dragging: true
    }).setView([54, 15], 4);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_nolabels/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO'
        }).addTo(map);

    axios.get('https://raw.githubusercontent.com/leakyMirror/map-of-europe/master/GeoJSON/europe.geojson')
        .then(response => {
            const geojson = response.data;

            function style(feature) {
                return {
                    fillColor: '#c4c4c4',
                    weight: 1,
                    opacity: 1,
                    color: 'black',
                    fillOpacity: 0.7
                };
            }

            function highlightFeature(e) {
                const layer = e.target;
                layer.setStyle({
                    weight: 3,
                    color: '#666',
                    fillColor: '#ffcc00',
                    fillOpacity: 0.9
                });
                layer.bringToFront();
            }

            function resetHighlight(e) {
                geojsonLayer.resetStyle(e.target);
            }

            function onEachFeature(feature, layer) {
                layer.on({
                    mouseover: highlightFeature,
                    mouseout: resetHighlight,
                    click: function(e) {
                        const clickedCode = feature.properties.ISO_A2;
                        if(clickedCode === correctCountryCode) {
                            document.getElementById('result').textContent = '✅ Goed gedaan!';
                            layer.setStyle({fillColor: 'green'});
                        } else {
                            document.getElementById('result').textContent = '❌ Fout! Probeer opnieuw.';
                            layer.setStyle({fillColor: 'red'});
                        }
                    }
                });
            }

            const geojsonLayer = L.geoJson(geojson, {
                style: style,
                onEachFeature: onEachFeature
            }).addTo(map);

        })
        .catch(err => {
            console.error('Fout bij laden GeoJSON:', err);
        });
</script>

</body>
</html>
