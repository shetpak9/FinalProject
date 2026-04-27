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
function captureMapAndGeneratePDF() {
    console.log("Starting map capture...");
    console.log("html2canvas defined?", typeof html2canvas);
    
    if (typeof html2canvas === 'undefined') {
        alert("html2canvas library not loaded. Please refresh the page.");
        return;
    }

    const mapElement = document.getElementById('leaflet-map');
    if (!mapElement) {
        alert("Map element not found!");
        return;
    }

    // Capture the map element using html2canvas
    html2canvas(mapElement, {
        useCORS: true,
        allowTaint: true,
        backgroundColor: '#ffffff',
        scale: 2
    }).then(canvas => {
        console.log("Map captured successfully");
        
        // Convert canvas to base64 image
        const imageData = canvas.toDataURL('image/png');
        console.log("Image data length:", imageData.length);

        // Send to PHP backend
        fetch('/FinalProject/controllers/generate-map-pdf.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                mapImage: imageData,
                title: 'Map Report',
            })
        })
        .then(res => {
            console.log("Response status:", res.status);
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.blob();
        })
        .then(blob => {
            console.log("Blob received:", blob.size, "bytes");
            
            // Download the PDF
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'map-report.pdf';
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);
            
            console.log("PDF downloaded successfully");
            alert("PDF downloaded successfully!");
        })
        .catch(err => {
            console.error("Error:", err);
            alert("Error generating PDF: " + err.message);
        });
    }).catch(err => {
        console.error("Error capturing map:", err);
        alert("Error capturing map: " + err.message);
    });
}