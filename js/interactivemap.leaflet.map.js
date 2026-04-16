let markers = [];

window.map = L.map('leaflet-map').setView([14.483111, 121.187472], 20)

var Stadia_AlidadeSatellite = L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_satellite/{z}/{x}/{y}{r}.{ext}', {
	minZoom: 20,
	maxZoom: 20,
	attribution: '&copy; CNES, Distribution Airbus DS, © Airbus DS, © PlanetObserver (Contains Copernicus Data) | &copy; <a href="https://www.stadiamaps.com/" target="_blank">Stadia Maps</a> &copy; <a href="https://openmaptiles.org/" target="_blank">OpenMapTiles</a> &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
	ext: 'jpg'
}).addTo(map);

const params = new URLSearchParams(window.location.search);

fetch('/FinalProject/src/api/location?' + params.toString())
    .then(res => res.jsqon())
    .then(locations => {
        console.log("Locations:", locations);

        markers.forEach(m => map.removeLayer(m));
        markers = [];

        locations.forEach(loc => {
            const lat = parseFloat(loc.latitude);
            const lng = parseFloat(loc.longitude);

            if (isNaN(lat) || isNaN(lng)) return;

            const marker = L.marker([lat, lng]).addTo(map)
            markers.push(marker);

            marker.on('click', () => {
                document.querySelector(".map_details__info h3").textContent = "Location Name: " + loc.room;
                document.querySelector(".map_details__Description").textContent = "Description: " + (loc.description || "");
                document.querySelector(".map_details__Type_id").textContent = "Location Type: " + (loc.type_id || "");

                const img = document.querySelector(".map_details__image img");

                if (loc.image) {
                    img.src = "uploads/" + loc.image;
                    img.style.display = "block";
                } else {
                    img.style.display = "none";
                }
            });
        });
    })
    .catch(err => console.error("Error loading locations:", err));