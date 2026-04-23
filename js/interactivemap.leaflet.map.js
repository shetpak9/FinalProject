let markers = [];

let reportLatLng = null;
let reportMarker = null;

let selectedLocationId = null;

console.log("JS LOADED");
window.map = L.map('leaflet-map').setView([14.483111, 121.187472], 19);

var Stadia_AlidadeSatellite = L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_satellite/{z}/{x}/{y}{r}.{ext}', {
    minzoom: 17,
    maxZoom: 20,
	attribution: '&copy; CNES, Distribution Airbus DS, © Airbus DS, © PlanetObserver (Contains Copernicus Data) | &copy; <a href="https://www.stadiamaps.com/" target="_blank">Stadia Maps</a> &copy; <a href="https://openmaptiles.org/" target="_blank">OpenMapTiles</a> &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
	ext: 'jpg'
}).addTo(map);

map.on('click', function(e) {
    const { lat, lng } = e.latlng;

    reportLatLng = { lat, lng };

    // remove old report marker
    if (reportMarker) {
        map.removeLayer(reportMarker);
    }

    // add new marker
    reportMarker = L.marker([lat, lng]).addTo(map);

    reportMarker.bindPopup("Report location").openPopup();
    
    setTimeout(() => {
                    reportMarker.closePopup();
                    map.removeLayer(reportMarker);
                }, 1000);
});

const params = new URLSearchParams(window.location.search);

fetch('/FinalProject/src/api/location?' + params.toString())
    .then(res => res.json())
    .then(locations => {
        console.log("Locations:", locations);

        markers.forEach(m => map.removeLayer(m));
        markers = [];

        locations.forEach(loc => {
            const lat = parseFloat(loc.latitude);
            const lng = parseFloat(loc.longitude);

            if (isNaN(lat) || isNaN(lng)) return;

            const marker = L.marker([lat, lng], {
                icon: getIcon(loc.type_id)
            }).addTo(map)
            markers.push(marker);

            const typeloc = {
                1: "Classroom",
                2: "Laboratory",
                3: "Office",
                4: "Other Facilities"
            }
            marker.on('click', () => {

                console.log("Marker clicked!");
                document.querySelector(".map_details__info h3").textContent = "Location Name: " + loc.room;
                document.querySelector(".map_details__Description").textContent = "Description: " + (loc.description || "");
                document.querySelector(".map_details__Type_id").textContent = "Location Type: " + (typeloc[loc.type_id] || "");

                selectedLocationId = loc.id;

                const img = document.querySelector(".map_details__image img");

                if (loc.image) {
                    img.src = "uploads/" + loc.image;
                    img.style.display = "block";
                } else {
                    img.src = "view/images/Screenshot 2026-04-16 194758.png";
                }
            });
        });
    })
    .catch(err => console.error("Error loading locations:", err));

const dialog = document.getElementById("reportDialog");

document.querySelector('[name="report"]').addEventListener('click', function(e) {
    e.preventDefault();

    if (!reportLatLng) {
        alert("Click on the map first!");
        return;
    }

    dialog.showModal(); 
});
document.getElementById("cancelReport").addEventListener("click", () => {
    dialog.close();
});

document.getElementById("submitReport").addEventListener("click", () => {

    const item = document.getElementById("itemName").value;
    const desc = document.getElementById("itemDesc").value;
    const state = document.getElementById("itemState").value;

    if (!item) {
        alert("Enter item name");
        return;
    }

    fetch('/FinalProject/src/api/lost_item', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            name: item,
            description: desc,
            item_state: state,
            latitude: reportLatLng.lat,
            longitude: reportLatLng.lng
        })
    })
    .then(res => res.json())
    .then(() => {
        // show marker
        L.marker([reportLatLng.lat, reportLatLng.lng])
            .addTo(map)
            .bindPopup(`<b>${item}</b><br>${desc}`);
    });

    dialog.close();
});

document.querySelector('[name="favorite"]').addEventListener('click', function(e) {
    console.log("BUTTON CLICKED");
    e.preventDefault();

    if (!selectedLocationId) {
        alert("Click a location marker first!");
        return;
    }

    fetch('/FinalProject/src/api/favorite', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ location_id: selectedLocationId })
    })
    .then(res => res.json())
    .then(res => {
        console.log(res);
        alert("Added to favorites!");
    })
    .catch(err => console.error("Favorite error:", err));

    window.location.reload;
});
document.querySelectorAll(".show-on-map-btn").forEach(btn => {
    btn.addEventListener("click", function() {

        const locationId = this.dataset.locationId;

        fetch(`/FinalProject/src/api/location/${locationId}`)
            .then(res => res.json())
            .then(loc => {
                console.log("Location" + loc);
                
                const lat = parseFloat(loc.latitude);
                const lng = parseFloat(loc.longitude);

                if (isNaN(lat) || isNaN(lng)) {
                    alert("Invalid location coordinates");
                    return;
                }

                map.setView([lat, lng], 20);

                const marker = L.marker([lat, lng]).addTo(map);

                marker.bindPopup(`
                    <b>${loc.room}</b><br>
                    ${loc.description || ''}
                `).openPopup();
                setTimeout(() => {
                    marker.closePopup();
                    map.removeLayer(marker);
                }, 3000);
            })
            .catch(err => console.error("Error loading location:", err));
    });
});
document.querySelectorAll(".showItem-on-map-btn").forEach(btn => {
    btn.addEventListener("click", function() {
         const itemId = this.dataset.itemId;

         fetch(`/FinalProject/src/api/lost_item/${itemId}`)
            .then(res => res.json())
            .then(item => {
                console.log("Items: " + item)

                const lat = parseFloat(item.latitude);
                const lng = parseFloat(item.longitude);

                if (isNaN(lat) || isNaN(lng)) {
                    alert("Invalid location coordinates");
                    return;
                }

                map.setView([lat, lng], 20);

                const marker = L.marker([lat, lng]).addTo(map);
                let mess;
                if(item.item_state == 'Lost Item'){
                    mess = 'Item Lost Here';
                } else {
                    mess = 'Item Found Here';
                }

                marker.bindPopup(`${mess}`).openPopup();

                setTimeout(() => {
                    marker.closePopup();
                    map.removeLayer(marker);
                }, 3000);
            })
            .catch(err => console.error("Error loading item:", err));
    });
});
