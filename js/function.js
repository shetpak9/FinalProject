function onclick(map){
    let marker;

    map.on('click', function(e) {
    const { lat, lng } = e.latlng;

    // Remove old marker
    if (marker) {
        map.removeLayer(marker);
    }

    // Add new marker
    marker = L.marker([lat, lng]).addTo(map);

    // Save to hidden inputs
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;
});
}

function getIcon(type_id) {
    let iconUrl = '';

    if (type_id == 1) iconUrl = '/FinalProject/view/images/green-circle-svgrepo-com.svg?v=1';
    else if (type_id == 2) iconUrl = '/FinalProject/view/images/red-circle-svgrepo-com.svg?v=1';
    else if (type_id == 3) iconUrl = '/FinalProject/view/images/orange-circle-svgrepo-com.svg?v=1';
    else iconUrl = '/FinalProject/view/images/blue-circle-svgrepo-com.svg?v=1';

    return L.icon({
        iconUrl: iconUrl,
        iconSize: [10, 10],
        iconAnchor: [10, 10],
        popupAnchor: [0, -35]
    });
}
function getZoneColor(type){
    switch(type){
        case "Restricted Area": return "red";
        case "Maintenance Under Going": return "orange";
        default: return "blue";
    }
}