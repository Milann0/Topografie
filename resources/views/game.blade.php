<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8" />
    <title>Geography Game</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            height: 100%;
            overflow: hidden;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #767677 0%, #adadad 100%);
            color: white;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        #game-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 0;
            min-height: 0;
            height: 100%;
        }

        #header {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 15px 30px 0 30px;
            min-height: 80px;
            gap: 10px;
        }

        #country-name {
            font-size: 2em;
            padding: 10px 30px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            font-weight: bold;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        #score {
            font-size: 1.1em;
            background: rgba(0,0,0,0.15);
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: bold;
            margin-left: 0px;
        }

        #map {
            flex: 1 1 auto;
            width: 95vw;
            height: 70vh;
            min-height: 500px;
            max-width: 1200px;
            border: 2px solid #333;
            border-radius: 12px;
            margin: 0 auto;
            display: block;
            margin-top: 18px;
            margin-bottom: 0;
        }

        #footer {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        #result {
            font-size: 18px;
            padding: 10px;
            min-height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            width: 100%;
        }

        #game-complete {
            font-size: 20px;
            padding: 15px;
            background: rgba(76, 175, 80, 0.9);
            border-radius: 12px;
            margin: 10px;
            text-align: center;
            font-weight: bold;
        }

        #restart-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid white;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            transition: all 0.3s ease;
        }

        #restart-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .correct {
            background: rgba(76, 175, 80, 0.8) !important;
        }

        .incorrect {
            background: rgba(244, 67, 54, 0.8) !important;
        }

        @media (max-width: 800px) {
            #header {
                flex-direction: column;
                gap: 10px;
                padding: 10px 5px 0 5px;
            }
            #map {
                width: 98vw;
                min-height: 300px;
                height: 45vh;
            }
        }
    </style>
</head>

<body>
    <div id="game-container">
        <div id="header">
            <div id="country-name">Loading...</div>
            <div id="score">Score: <span id="correct">0</span> / <span id="total">0</span></div>
        </div>
        <div id="map"></div>
        <div id="footer">
            <div id="result"></div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        let currentCountry = null;
        let correctAnswers = 0;
        let totalQuestions = 0;
        let geojsonLayer = null;
        let gameActive = true;
        let usedCountries = new Set();
        let availableCountries = [];

        const europeanCountries = @json($countries);

        function initializeGame() {
            availableCountries = [...europeanCountries];
            usedCountries.clear();
            correctAnswers = 0;
            totalQuestions = europeanCountries.length;
            updateScore();
        }

        const map = L.map('map', {
            zoomControl: false,
            scrollWheelZoom: false,
            dragging: false,
            attributionControl: false
        }).setView([54, 15], 4);
        map.doubleClickZoom.disable();

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_nolabels/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO'
        }).addTo(map);

        axios.get('https://raw.githubusercontent.com/leakyMirror/map-of-europe/master/GeoJSON/europe.geojson')
            .then(response => {
                const geojson = response.data;

                function style(feature) {
                    return {
                        fillColor: '#e0e0e0',
                        weight: 1,
                        opacity: 1,
                        color: '#666',
                        fillOpacity: 0.7
                    };
                }

                function highlightFeature(e) {
                    if (!gameActive) return;
                    const layer = e.target;
                    layer.setStyle({
                        weight: 3,
                        color: '#333',
                        fillColor: '#ffeb3b',
                        fillOpacity: 0.9
                    });
                    layer.bringToFront();
                }

                function resetHighlight(e) {
                    if (!gameActive) return;
                    geojsonLayer.resetStyle(e.target);
                }

                function onEachFeature(feature, layer) {
                     console.log(feature.properties); 
                    layer.on({
                        mouseover: highlightFeature,
                        mouseout: resetHighlight,
                        click: function (e) {
                            if (!gameActive || !currentCountry) return;

                            const clickedCountryName = feature.properties.NAME || feature.properties.NAME_EN || 'Unknown';
                            const resultDiv = document.getElementById('result');

                            gameActive = false;

                                const clickedCode = feature.properties.ISO2 || '';
                                const currentCode = currentCountry.code;

                                console.log(`Clicked Code: ${clickedCode}, Current Code: ${currentCode}`);

                                const isCorrect = clickedCode.toUpperCase() === currentCode.toUpperCase();

                            if (isCorrect) {
                                correctAnswers++;
                                resultDiv.textContent = '✅ Correct! Well done!';
                                resultDiv.className = 'correct';
                                layer.setStyle({
                                    fillColor: '#4CAF50',
                                    fillOpacity: 0.8,
                                    weight: 3,
                                    color: '#2E7D32'
                                });
                            } else {
                                resultDiv.textContent = `❌ Wrong! That was ${clickedCountryName}. The correct answer was ${currentCountry.name}.`;
                                resultDiv.className = 'incorrect';
                                layer.setStyle({
                                    fillColor: '#f44336',
                                    fillOpacity: 0.8,
                                    weight: 3,
                                    color: '#c62828'
                                });

                                geojsonLayer.eachLayer(function (l) {
                                    const layerCountryName = l.feature.properties.NAME || l.feature.properties.NAME_EN || '';
                                    if (layerCountryName.toLowerCase() === currentCountry.name.toLowerCase()) {
                                        l.setStyle({
                                            fillColor: '#4CAF50',
                                            fillOpacity: 0.6,
                                            weight: 2,
                                            color: '#2E7D32'
                                        });
                                    }
                                });
                            }

                            updateScore();

                            // Mark country as used
                            usedCountries.add(currentCountry.name);

                            if (usedCountries.size >= europeanCountries.length) {
                                setTimeout(() => {
                                    showGameComplete();
                                }, 3000);
                            } else {
                                setTimeout(() => {
                                    startNewRound();
                                }, 3000);
                            }
                        }
                    });
                }

                geojsonLayer = L.geoJson(geojson, {
                    style: style,
                    onEachFeature: onEachFeature
                }).addTo(map);

                initializeGame();
                startNewRound();
            })
            .catch(err => {
                console.error('Error loading GeoJSON:', err);
                document.getElementById('country-name').textContent = 'Error loading map data';
            });

        function startNewRound() {
            if (geojsonLayer) {
                geojsonLayer.eachLayer(function (layer) {
                    geojsonLayer.resetStyle(layer);
                });
            }

            availableCountries = europeanCountries.filter(country => !usedCountries.has(country.name));

            if (availableCountries.length === 0) {
                showGameComplete();
                return;
            }

            const randomIndex = Math.floor(Math.random() * availableCountries.length);
            currentCountry = availableCountries[randomIndex];

            document.getElementById('country-name').textContent = currentCountry.name;
            document.getElementById('result').textContent = '';
            document.getElementById('result').className = '';

            gameActive = true;
        }

        function showGameComplete() {
            const percentage = Math.round((correctAnswers / totalQuestions) * 100);
            const resultDiv = document.getElementById('result');
            
            resultDiv.innerHTML = `
                <div id="game-complete">
                    🎉 Game Complete! 🎉<br>
                    Final Score: ${correctAnswers}/${totalQuestions} (${percentage}%)<br>
                    <button id="restart-btn" onclick="restartGame()">Play Again</button>
                </div>
            `;
            
            document.getElementById('country-name').textContent = 'All countries completed!';
            gameActive = false;
        }

        function restartGame() {
            initializeGame();
            startNewRound();
        }

        function updateScore() {
            document.getElementById('correct').textContent = correctAnswers;
            document.getElementById('total').textContent = totalQuestions;
        }
    </script>
</body>

</html>