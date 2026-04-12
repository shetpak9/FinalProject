document.querySelector("form").addEventListener("submit", function(e) {
    e.preventDefault();

    const form = document.querySelector("form");
    const formData = new FormData(form);

    fetch('/FinalProject/src/api/location', {
        method: 'POST',
        
        body: formData
    })
    .then(res => res.json())
    .then(res => console.log(res))
    .catch(err => console.error('Save failed:', err));

    document.querySelector("form").reset();
    window.location.reload();
});