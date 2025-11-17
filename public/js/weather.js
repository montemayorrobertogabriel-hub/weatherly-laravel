console.log("weather.js loaded!");

document.addEventListener("DOMContentLoaded", () => {
    const searchBtn = document.getElementById("searchBtn");
    const weatherBox = document.getElementById("weatherBox");
    const forecastBox = document.getElementById("forecastBox");
    const forecastContainer = document.getElementById("forecastContainer");
    const loadingSpinner = document.getElementById("loadingSpinner");
    const container = document.getElementById("weatherContainer");
    const errorMsg = document.getElementById("errorMsg");

    const bgClasses = [
        "sunny-bg", "cloudy-bg", "rain-bg", "snow-bg", "storm-bg", "fog-bg"
    ];

    const iconMap = {
        Clear: "☀️",
        Clouds: "☁️",
        Rain: "🌧️",
        Drizzle: "🌦️",
        Thunderstorm: "⛈️",
        Snow: "❄️",
        Mist: "🌫️",
        Fog: "🌫️",
        Haze: "🌫️",
        Smoke: "🌫️"
    };

    const weatherBG = {
        Clear: "sunny-bg",
        Clouds: "cloudy-bg",
        Rain: "rain-bg",
        Drizzle: "rain-bg",
        Thunderstorm: "storm-bg",
        Snow: "snow-bg",
        Mist: "fog-bg",
        Fog: "fog-bg",
        Haze: "fog-bg",
        Smoke: "fog-bg"
    };

    searchBtn.addEventListener("click", async () => {
        const city = document.getElementById("cityInput").value.trim();
        errorMsg.classList.add("hidden");

        if (!city) return alert("Enter a city");

        weatherBox.classList.add("hidden");
        weatherBox.classList.remove("opacity-100");
        forecastBox.classList.add("hidden");
        forecastBox.classList.remove("opacity-100");
        loadingSpinner.classList.remove("hidden");

        try {
            const response = await fetch(`/api/weather?city=${city}`);
            const data = await response.json();
            loadingSpinner.classList.add("hidden");

            if (!data.weather) {
                errorMsg.innerText = "City not found.";
                errorMsg.classList.remove("hidden");
                return;
            }

            const mainWeather = data.weather[0].main;

            document.getElementById("locationName").innerText = data.name;
            document.getElementById("weatherIcon").innerText =
                iconMap[mainWeather] || "🌤️";
            document.getElementById("tempValue").innerText = `${data.main.temp}°C`;
            document.getElementById("conditionValue").innerText = data.weather[0].description;
            document.getElementById("humidityValue").innerText = `${data.main.humidity}%`;

            bgClasses.forEach(bg => container.classList.remove(bg));
            if (weatherBG[mainWeather]) container.classList.add(weatherBG[mainWeather]);

            weatherBox.classList.remove("hidden");
            setTimeout(() => weatherBox.classList.add("opacity-100"), 100);

            if (!data.daily || data.daily.length === 0) {
                forecastBox.classList.add("hidden");
                return;
            }

            forecastContainer.innerHTML = "";
            data.daily.forEach(day => {
                const date = new Date(day.dt * 1000);
                const displayDate = date.toLocaleDateString(undefined, {
                    weekday: "short",
                    month: "short",
                    day: "numeric"
                });

                const icon = iconMap[day.weather[0].main] || "🌤️";

                const card = document.createElement("div");
                card.className =
                    "flex-none w-24 p-3 bg-white rounded-lg shadow-md text-center";

                card.innerHTML = `
                    <p class="font-semibold">${displayDate}</p>
                    <p class="text-2xl">${icon}</p>
                    <p class="text-gray-800">${day.temp.day}°C</p>
                    <p class="text-gray-600 text-xs">${day.weather[0].main}</p>
                `;

                forecastContainer.appendChild(card);
            });

            forecastBox.classList.remove("hidden");
            setTimeout(() => forecastBox.classList.add("opacity-100"), 100);

        } catch (err) {
            console.error(err);
            loadingSpinner.classList.add("hidden");
            errorMsg.innerText = "Weather fetch failed.";
            errorMsg.classList.remove("hidden");
        }
    });
});
