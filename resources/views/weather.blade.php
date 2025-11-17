<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weatherly</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* ☀️ CLEAR — Sun glow */
        .sunny-bg {
            background: radial-gradient(circle at center, #ffec9e, #ffd467, #ffb300);
            animation: sunnyPulse 4s infinite alternate;
        }
        @keyframes sunnyPulse {
            from { filter: brightness(1); }
            to { filter: brightness(1.3); }
        }

        /* ☁ CLOUDY — floating blobs */
        .cloudy-bg {
            background: linear-gradient(#d7e1ec, #b8c6d8);
            position: relative;
            overflow: hidden;
        }
        .cloudy-bg::before,
        .cloudy-bg::after {
            content: "";
            position: absolute;
            background: rgba(255,255,255,0.7);
            width: 200px;
            height: 80px;
            border-radius: 50px;
            animation: cloudFloat 25s linear infinite;
            top: 20%;
        }
        .cloudy-bg::after {
            top: 50%;
            animation-duration: 35s;
        }
        @keyframes cloudFloat {
            0% { transform: translateX(-300px); }
            100% { transform: translateX(900px); }
        }

        /* 🌧 BEAUTIFUL DIAGONAL RAIN — LONG DROPS VERSION */
        .rain-bg {
            background: linear-gradient(#8fb3d9, #648ebd);
            position: relative;
            overflow: hidden;
        }

        /* Layer containers */
        .rain-bg::before,
        .rain-bg::after {
            content: "";
            position: absolute;
            inset: 0;
            background-repeat: repeat;
            pointer-events: none;
        }

        /* Softer long rain streaks */
        .rain-bg::before {
            background-image:
                radial-gradient(ellipse 2px 15px, rgba(255,255,255,0.35) 50%, transparent 50%),
                radial-gradient(ellipse 1.8px 6px, rgba(255,255,255,0.25) 50%, transparent 50%);
            background-size: 120px 200px, 160px 260px;
            animation: rainSoft 1.8s linear infinite;
            opacity: 0.55;
        }

        /* Stronger long rain streaks */
        .rain-bg::after {
            background-image:
                radial-gradient(ellipse 2.2px 15px, rgba(255,255,255,0.5) 50%, transparent 50%),
                radial-gradient(ellipse 2px 8px, rgba(255,255,255,0.4) 50%, transparent 50%);
            background-size: 100px 180px, 140px 240px;
            animation: rainHard 1.2s linear infinite;
            opacity: 0.9;
        }

        /* Diagonal falling motion */
        @keyframes rainSoft {
            from { background-position: 0 -100px, 20px -160px; }
            to   { background-position: -40px 100px, -20px 160px; }
        }

        @keyframes rainHard {
            from { background-position: 0 -80px, 20px -140px; }
            to   { background-position: -60px 80px, -40px 140px; }
        }



        /* ❄ SNOW — Blizzard effect (OPTION B) */
        .snow-bg {
            background: linear-gradient(#dfe9f5, #cfe0f3);
            position: relative;
            overflow: hidden;
        }
        .snow-bg::before,
        .snow-bg::after {
            content: "";
            position: absolute;
            inset: 0;
            background-repeat: repeat;
            background-size: 200px 200px;
            pointer-events: none;
        }

        /* Background layer 1 (slow) */
        .snow-bg::before {
            background-image:
                radial-gradient(white 2px, transparent 2px),
                radial-gradient(white 1px, transparent 1px),
                radial-gradient(white 3px, transparent 3px);
            animation: snowDrift1 12s linear infinite;
            opacity: 0.7;
        }

        /* Background layer 2 (fast) */
        .snow-bg::after {
            background-image:
                radial-gradient(white 2px, transparent 2px),
                radial-gradient(white 1px, transparent 1px),
                radial-gradient(white 3px, transparent 3px);
            animation: snowDrift2 6s linear infinite;
            opacity: 0.9;
        }

        @keyframes snowDrift1 {
            from { background-position: 0 0; }
            to { background-position: -300px 600px; }
        }

        @keyframes snowDrift2 {
            from { background-position: 0 0; }
            to { background-position: -150px 600px; }
        }

        /* ⚡ STORM */
        .storm-bg {
            background: #2c3e50;
            position: relative;
            overflow: hidden;
        }
        .storm-bg::after {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(255,255,255,0.9);
            opacity: 0;
            animation: lightningFlash 4s infinite;
        }
        @keyframes lightningFlash {
            0%, 92% { opacity: 0; }
            93% { opacity: 0.3; }
            94% { opacity: 0; }
            95% { opacity: 0.8; }
            100% { opacity: 0; }
        }

        /* 🌫 FOG */
        .fog-bg {
            background: linear-gradient(#cfd4d6, #e3e4e5);
            position: relative;
            overflow: hidden;
        }
        .fog-bg::after {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(255,255,255,0.35);
            filter: blur(12px);
            animation: fogMove 9s ease-in-out infinite alternate;
        }
        @keyframes fogMove {
            from { transform: translateX(-20px); }
            to { transform: translateX(20px); }
        }
    </style>
</head>

<body class="bg-blue-100 min-h-screen flex items-center justify-center relative transition-colors duration-1000">

    <!-- Auth Buttons -->
    <div class="absolute top-4 right-4 flex items-center gap-3">
        @auth
            <form action="/logout" method="POST">
                @csrf
                <button class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-lg shadow">
                    Logout
                </button>
            </form>
        @else
            <a href="/login" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow">Login</a>
            <a href="/register" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow">Register</a>
        @endauth
    </div>

    <div id="weatherContainer"
         class="bg-blue-50 border border-blue-300 rounded-xl p-8 w-full max-w-lg mx-6 shadow-xl text-center transition-colors duration-1000 relative overflow-hidden">

        <h1 class="text-4xl font-bold text-blue-700 mb-4">🌤 Weatherly</h1>
        <p class="text-gray-600 mb-6">Search the weather by city or country</p>

        <div class="space-y-3">
            <input id="cityInput" type="text" placeholder="Enter location e.g. Tokyo, Manila, Paris"
                   class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />

            <button id="searchBtn"
                    class="w-full py-3 bg-blue-600 hover:bg-blue-700 
                           text-white font-semibold rounded-lg transition">
                Check Weather
            </button>
        </div>

        <div id="loadingSpinner" class="hidden mt-6 flex justify-center">
            <div class="w-10 h-10 border-4 border-blue-300 border-t-blue-600 rounded-full animate-spin"></div>
        </div>

        <div id="weatherBox"
             class="hidden mt-6 p-5 bg-blue-50 border border-blue-300 rounded-xl text-left opacity-0 transition-opacity duration-700">

            <h3 class="font-bold text-2xl text-blue-900 mb-4 flex items-center gap-2">
                <span id="weatherIcon">☀️</span>
                <span id="locationName"></span>
            </h3>

            <p class="text-gray-700 text-lg">Temperature: <b id="tempValue"></b></p>
            <p class="text-gray-700 text-lg">Condition: <b id="conditionValue"></b></p>
            <p class="text-gray-700 text-lg">Humidity: <b id="humidityValue"></b></p>
        </div>

        <div id="forecastBox" class="hidden mt-6 text-left opacity-0 transition-opacity duration-700">
            <h4 class="font-bold text-xl mb-3 text-blue-700">5-Day Forecast</h4>
            <div id="forecastContainer" class="flex space-x-3 overflow-x-auto pb-2"></div>
        </div>

        <p id="errorMsg" class="hidden text-red-600 mt-3"></p>
    </div>

    <script src="/js/weather.js?v=35"></script>
</body>
</html>
