document.querySelector("form").addEventListener("submit", function(e) {
    e.preventDefault();

    const data = {
        room: document.querySelector('[name="room"]').value,
        type_id: document.querySelector('[name="type_id"]').value,
        floor: document.querySelector('[name="floor"]').value,
        capacity: document.querySelector('[name="capacity"]').value,
        description: document.querySelector('[name="description"]').value,
        latitude: document.getElementById('latitude').value,
        longitude: document.getElementById('longitude').value
    };

    fetch('/FinalProject/src/api/location', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => console.log(res))
    .catch(err => console.error('Save failed:', err));
});