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