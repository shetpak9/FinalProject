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