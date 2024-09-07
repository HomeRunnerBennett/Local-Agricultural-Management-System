document.addEventListener('DOMContentLoaded', function() {
    const links = document.querySelectorAll('nav ul li a');
    const contentDiv = document.getElementById('content');

    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const page = link.getAttribute('data-page');

            fetch(page)
                .then(response => response.text())
                .then(data => {
                    contentDiv.innerHTML = data;
                })
                .catch(error => console.error('Error loading page:', error));
        });
    });
});

// script.js

document.getElementById('search').addEventListener('input', function() {
    var query = this.value;

    if (query.length > 0) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'search_results.php?q=' + query, true);
        xhr.onload = function() {
            if (this.status == 200) {
                document.getElementById('results').innerHTML = this.responseText;
            }
        };
        xhr.send();
    } else {
        document.getElementById('results').innerHTML = '';
    }
});

function updateWeather() {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "fetch_weather.php", true); // Assuming you have a PHP script that returns the latest weather as JSON
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var data = JSON.parse(xhr.responseText);
                document.getElementById("weatherDate").textContent = data.date;
                document.getElementById("weatherTemperature").textContent = data.temperature + " °C";
                document.getElementById("weatherPrecipitation").textContent = data.precipitation + " mm";
                document.getElementById("weatherWindSpeed").textContent = data.wind_speed + " km/h";
                document.getElementById("weatherHumidity").textContent = data.humidity + "%";
            }
        };
        xhr.send();
    }

    // Call the function every 10 minutes (600000 milliseconds)
    setInterval(updateWeather, 600000);