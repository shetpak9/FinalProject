let markers = [];

let pendingShape = null;

window.map = L.map('leaflet-map').setView([14.483111, 121.187472], 19);

const drawnItems = new L.FeatureGroup();
map.addLayer(drawnItems);

const drawControl = new L.Control.Draw({
    draw: {
        polygon: true,
        polyline: false,
        rectangle: true,
        circle: true,
        marker: false
    },
    edit: {
        featureGroup: drawnItems
    }
});
map.addControl(drawControl);

var Stadia_AlidadeSatellite = L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_satellite/{z}/{x}/{y}{r}.{ext}', {
    minzoom: 17,
    maxZoom: 20,
	attribution: '&copy; CNES, Distribution Airbus DS, © Airbus DS, © PlanetObserver (Contains Copernicus Data) | &copy; <a href="https://www.stadiamaps.com/" target="_blank">Stadia Maps</a> &copy; <a href="https://openmaptiles.org/" target="_blank">OpenMapTiles</a> &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
	ext: 'jpg'
}).addTo(map);

const params = new URLSearchParams(window.location.search);

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
    }).catch(err => console.error("Error loading locations:", err));

map.on(L.Draw.Event.CREATED, function (e) {

    const layer = e.layer;

    pendingShape = layer;

    drawnItems.addLayer(layer);

    document.getElementById('areaDialog').showModal();
});
document.getElementById("confirmBtn").addEventListener("click", function () {

    if (!pendingShape) return;

    const geojson = pendingShape.toGeoJSON();

    const zone_type = document.getElementById("areaType").value;
    const floor = document.getElementById("floorId").value;
    const description = document.getElementById("descId").value;

    fetch('/FinalProject/src/api/shape', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            geojson,
            zone_type,
            floor,
            description
        })
    })
    .then(res => res.json())
    .then(data => {
        console.log("Shape saved:", data);
    });

    // reset
    pendingShape = null;
    document.getElementById("areaDialog").close();
});
document.getElementById("cancelBtn").addEventListener("click", function () {

    if (pendingShape) {
        drawnItems.removeLayer(pendingShape);
        pendingShape = null;
    }

    document.getElementById("areaDialog").close();
});

fetch('/FinalProject/src/api/shape')
.then(res => res.json())
.then(data => {
  data.forEach(shape => {
    const geo = JSON.parse(shape.geojson);
    console.log("Shapes:", shape);
    const layer = L.geoJSON(geo, {
      onEachFeature: function (feature, layer) {
        layer._shapeId = shape.id;
        layer.bindPopup(`
          ${shape.zone_type} <br> 
          Floor: ${shape.floor}<br>
          ${shape.description || ""}
        `);
      },
      style: function () {
        return {
          color: getZoneColor(shape.zone_type),
          fillOpacity: 0.3,
          weight: 1
        };
      }
    });
    layer.eachLayer(l => {
        drawnItems.addLayer(l);
    });
  });
});
map.on(L.Draw.Event.EDITED, function (e) {

    e.layers.eachLayer(layer => {

        const geojson = layer.toGeoJSON();

        fetch('/FinalProject/src/api/shape/' + layer._shapeId, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                geojson
            })
        });

    });

});
map.on(L.Draw.Event.DELETED, function (e) {
    const layers = e.layers;

    layers.eachLayer(layer => {
        fetch('/FinalProject/src/api/shape/' + layer._shapeId, {
            method: 'DELETE'
        });
    });
});