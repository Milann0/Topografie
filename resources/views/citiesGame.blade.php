<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>European Capitals Game</title>
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

        #capital-name {
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

        .capital-marker {
            background: #ff6b6b;
            border: 3px solid white;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }

        .capital-marker:hover {
            transform: scale(1.5);
            background: #ff5252;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.5);
        }

        .capital-marker.correct {
            background: #4CAF50 !important;
            animation: bounce 0.6s ease-in-out;
            transform: scale(1.3);
        }

        .capital-marker.incorrect {
            background: #f44336 !important;
            animation: shake 0.6s ease-in-out;
        }

        .capital-marker.show-correct {
            background: #81C784 !important;
            animation: pulse 1s ease-in-out;
            transform: scale(1.2);
        }

        .save-status {
            font-size: 14px;
            padding: 5px 10px;
            margin-top: 10px;
            border-radius: 5px;
            background: rgba(0, 0, 0, 0.2);
        }

        .save-success {
            background: rgba(76, 175, 80, 0.8) !important;
        }

        .save-error {
            background: rgba(244, 67, 54, 0.8) !important;
        }

        @keyframes bounce {
            0%, 20%, 60%, 100% { transform: scale(1.3) translateY(0); }
            40% { transform: scale(1.3) translateY(-8px); }
            80% { transform: scale(1.3) translateY(-4px); }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-3px); }
            20%, 40%, 60%, 80% { transform: translateX(3px); }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1.2); opacity: 1; }
            50% { transform: scale(1.4); opacity: 0.8; }
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
            .capital-marker {
                width: 14px;
                height: 14px;
            }
        }
    </style>
</head>

<body>
    <div id="game-container">
        <div id="header">
            <div id="capital-name">Loading...</div>
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
        let currentCapital = null;
        let correctAnswers = 0;
        let totalQuestions = 0;
        let gameActive = true;
        let usedCapitals = new Set();
        let availableCapitals = [];
        let capitalMarkers = [];

        // Set up axios with CSRF token
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const europeanCapitals = [
            { name: 'London', lat: 51.5074, lon: -0.1278, country: 'United Kingdom' },
            { name: 'Paris', lat: 48.8566, lon: 2.3522, country: 'France' },
            { name: 'Berlin', lat: 52.5200, lon: 13.4050, country: 'Germany' },
            { name: 'Madrid', lat: 40.4168, lon: -3.7038, country: 'Spain' },
            { name: 'Rome', lat: 41.9028, lon: 12.4964, country: 'Italy' },
            { name: 'Amsterdam', lat: 52.3676, lon: 4.9041, country: 'Netherlands' },
            { name: 'Vienna', lat: 48.2082, lon: 16.3738, country: 'Austria' },
            { name: 'Prague', lat: 50.0755, lon: 14.4378, country: 'Czech Republic' },
            { name: 'Budapest', lat: 47.4979, lon: 19.0402, country: 'Hungary' },
            { name: 'Warsaw', lat: 52.2297, lon: 21.0122, country: 'Poland' },
            { name: 'Stockholm', lat: 59.3293, lon: 18.0686, country: 'Sweden' },
            { name: 'Oslo', lat: 59.9139, lon: 10.7522, country: 'Norway' },
            { name: 'Copenhagen', lat: 55.6761, lon: 12.5683, country: 'Denmark' },
            { name: 'Helsinki', lat: 60.1699, lon: 24.9384, country: 'Finland' },
            { name: 'Dublin', lat: 53.3441, lon: -6.2675, country: 'Ireland' },
            { name: 'Lisbon', lat: 38.7223, lon: -9.1393, country: 'Portugal' },
            { name: 'Athens', lat: 37.9755, lon: 23.7348, country: 'Greece' },
            { name: 'Brussels', lat: 50.8503, lon: 4.3517, country: 'Belgium' },
            { name: 'Bern', lat: 46.9480, lon: 7.4474, country: 'Switzerland' },
            { name: 'Luxembourg', lat: 49.6117, lon: 6.1319, country: 'Luxembourg' },
            { name: 'Reykjavik', lat: 64.1466, lon: -21.9426, country: 'Iceland' },
            { name: 'Valletta', lat: 35.8989, lon: 14.5146, country: 'Malta' },
            { name: 'Nicosia', lat: 35.1856, lon: 33.3823, country: 'Cyprus' },
            { name: 'Bratislava', lat: 48.1486, lon: 17.1077, country: 'Slovakia' },
            { name: 'Ljubljana', lat: 46.0569, lon: 14.5058, country: 'Slovenia' },
            { name: 'Zagreb', lat: 45.8150, lon: 15.9819, country: 'Croatia' },
            { name: 'Sarajevo', lat: 43.8486, lon: 18.3564, country: 'Bosnia and Herzegovina' },
            { name: 'Belgrade', lat: 44.7866, lon: 20.4489, country: 'Serbia' },
            { name: 'Bucharest', lat: 44.4268, lon: 26.1025, country: 'Romania' },
            { name: 'Sofia', lat: 42.6977, lon: 23.3219, country: 'Bulgaria' },
            { name: 'Riga', lat: 56.9496, lon: 24.1052, country: 'Latvia' },
            { name: 'Vilnius', lat: 54.6872, lon: 25.2797, country: 'Lithuania' },
            { name: 'Tallinn', lat: 59.4370, lon: 24.7536, country: 'Estonia' },
            { name: 'Ankara', lat: 39.9334, lon: 32.8597, country: 'Turkey' },
            { name: 'Minsk', lat: 53.9045, lon: 27.5615, country: 'Belarus' },
            { name: 'Kyiv', lat: 50.4501, lon: 30.5234, country: 'Ukraine' },
            { name: 'Chisinau', lat: 47.0105, lon: 28.8638, country: 'Moldova' },
            { name: 'Podgorica', lat: 42.4304, lon: 19.2594, country: 'Montenegro' },
            { name: 'Pristina', lat: 42.6629, lon: 21.1655, country: 'Kosovo' },
            { name: 'Skopje', lat: 41.9973, lon: 21.4280, country: 'North Macedonia' },
            { name: 'Tirana', lat: 41.3275, lon: 19.8187, country: 'Albania' },
            { name: 'Andorra la Vella', lat: 42.5063, lon: 1.5218, country: 'Andorra' },
            { name: 'Monaco', lat: 43.7384, lon: 7.4246, country: 'Monaco' },
            { name: 'San Marino', lat: 43.9424, lon: 12.4578, country: 'San Marino' },
            { name: 'Vatican City', lat: 41.9029, lon: 12.4534, country: 'Vatican City' },
            { name: 'Vaduz', lat: 47.1410, lon: 9.5209, country: 'Liechtenstein' }
        ];

        function initializeGame() {
            availableCapitals = [...europeanCapitals];
            usedCapitals.clear();
            correctAnswers = 0;
            totalQuestions = europeanCapitals.length;
            updateScore();
        }

        const map = L.map('map', {
            zoomControl: false,
            scrollWheelZoom: false,
            dragging: false,
            attributionControl: false
        }).setView([54, 15], 4);
        map.doubleClickZoom.disable();

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO'
        }).addTo(map);

        function addCapitalMarkers() {
            capitalMarkers = [];
            europeanCapitals.forEach((capital) => {
                const marker = L.divIcon({
                    className: 'capital-marker',
                    iconSize: [16, 16],
                    iconAnchor: [8, 8]
                });

                const capitalMarker = L.marker([capital.lat, capital.lon], { icon: marker })
                    .addTo(map)
                    .on('click', function() {
                        handleCapitalClick(capital, this);
                    });

                capitalMarkers.push({ marker: capitalMarker, capital: capital });
            });
        }

        function handleCapitalClick(clickedCapital, markerInstance) {
            if (!gameActive || !currentCapital) return;

            const resultDiv = document.getElementById('result');
            gameActive = false;

            const isCorrect = clickedCapital.name === currentCapital.name;

            if (isCorrect) {
                correctAnswers++;
                resultDiv.textContent = `✅ Correct! ${currentCapital.name} is the capital of ${currentCapital.country}!`;
                resultDiv.className = 'correct';
                markerInstance.getElement().classList.add('correct');
            } else {
                resultDiv.textContent = `❌ Wrong! That's ${clickedCapital.name} (${clickedCapital.country}). ${currentCapital.name} is the capital of ${currentCapital.country}.`;
                resultDiv.className = 'incorrect';
                markerInstance.getElement().classList.add('incorrect');

                capitalMarkers.forEach(({ marker, capital }) => {
                    if (capital.name === currentCapital.name) {
                        marker.getElement().classList.add('show-correct');
                    }
                });
            }

            updateScore();
            usedCapitals.add(currentCapital.name);

            if (usedCapitals.size >= europeanCapitals.length) {
                setTimeout(() => {
                    showGameComplete();
                }, 4000);
            } else {
                setTimeout(() => {
                    startNewRound();
                }, 4000);
            }
        }

        function startNewRound() {
            capitalMarkers.forEach(({ marker }) => {
                const element = marker.getElement();
                element.classList.remove('correct', 'incorrect', 'show-correct');
            });

            availableCapitals = europeanCapitals.filter(capital => !usedCapitals.has(capital.name));

            if (availableCapitals.length === 0) {
                showGameComplete();
                return;
            }

            const randomIndex = Math.floor(Math.random() * availableCapitals.length);
            currentCapital = availableCapitals[randomIndex];

            document.getElementById('capital-name').textContent = currentCapital.name;
            document.getElementById('result').textContent = '';
            document.getElementById('result').className = '';

            gameActive = true;
        }

        async function saveGameScore() {
            try {
                const response = await axios.post('/api/games/save-score', {
                    score: correctAnswers,
                    total_questions: totalQuestions,
                    game_type: 'capitals'
                });
                
                if (response.data.success) {
                    console.log('Score saved successfully');
                    return true;
                } else {
                    console.error('Failed to save score:', response.data.message);
                    return false;
                }
            } catch (error) {
                console.error('Error saving score:', error);
                return false;
            }
        }

        function showGameComplete() {
            const percentage = Math.round((correctAnswers / totalQuestions) * 100);
            const resultDiv = document.getElementById('result');
            
            // Save score to database
            saveGameScore().then(saved => {
                const saveStatus = saved ? 
                    '<div class="save-status save-success">✅ Score saved!</div>' : 
                    '<div class="save-status save-error">❌ Failed to save score</div>';
                
                resultDiv.innerHTML = `
                    <div id="game-complete">
                        🎉 Game Complete! 🎉<br>
                        Final Score: ${correctAnswers}/${totalQuestions} (${percentage}%)<br>
                        ${saveStatus}
                        <button id="restart-btn" onclick="restartGame()">Play Again</button>
                    </div>
                `;
            });
            
            document.getElementById('capital-name').textContent = 'All capitals completed!';
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

        addCapitalMarkers();
        initializeGame();
        startNewRound();
    </script>
</body>

</html>