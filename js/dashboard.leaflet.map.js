let markers = [];

window.map = L.map('leaflet-map').setView([14.483111, 121.187472], 19);

var Stadia_AlidadeSatellite = L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_satellite/{z}/{x}/{y}{r}.{ext}', {
    minzoom: 17,
    maxZoom: 20,
	attribution: '&copy; CNES, Distribution Airbus DS, © Airbus DS, © PlanetObserver (Contains Copernicus Data) | &copy; <a href="https://www.stadiamaps.com/" target="_blank">Stadia Maps</a> &copy; <a href="https://openmaptiles.org/" target="_blank">OpenMapTiles</a> &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
	ext: 'jpg'
}).addTo(map);

const params = new URLSearchParams(window.location.search);

function getIcon(type_id) {
    let iconUrl = '';

    if (type_id == 1) iconUrl = 'view/images/green-circle-svgrepo-com.svg';
    else if (type_id == 2) iconUrl = 'view/images/red-circle-svgrepo-com.svg';
    else if (type_id == 3) iconUrl = 'view/images/orange-circle-svgrepo-com.svg';
    else iconUrl = 'view/images/blue-circle-svgrepo-com.svg';

    return L.icon({
        iconUrl: iconUrl,
        iconSize: [10, 10],
        iconAnchor: [10, 10],
        popupAnchor: [0, -35]
    });
}

fetch('/FinalProject/src/api/location?' + params.toString())
    .then(res => res.json())
    .then(locations => {
        console.log("Locations:", locations);

        markers.forEach(m => map.removeLayer(m));
        markers = [];

        locations.forEach(loc => {
            // Convert lat/lng to numbers
            const lat = parseFloat(loc.latitude);
            const lng = parseFloat(loc.longitude);

            if (isNaN(lat) || isNaN(lng)) return;

            // Add marker
            const marker = L.marker([lat, lng], {
                        icon: getIcon(loc.type_id)
                    }).addTo(map);
            markers.push(marker);

            // Popup content
            marker.bindPopup(`
                <b>${loc.room}</b><br>
                Floor: ${loc.floor}<br>
                Capacity: ${loc.capacity}<br>
                ${loc.description || ""}
                <br>
                ${loc.image ? `<img src="uploads/${loc.image}" width="150">` : ""}
            `);
        });
    })
    .catch(err => console.error("Error loading locations:", err));