<body>
<div class="container">
    <div class="top_text">
        <a href="/FinalProject/">Back to Home</a>
        <p> | Map Area Control</p>
    </div>
    <div class="content">
        <div class="map_section">
            <div class="map">
                <div id="leaflet-map">
                </div>
            </div>
        </div>
    </div>
</body>
<dialog id="areaDialog">
  <form method="POST">
    <h3>Fill Area Information</h3>
    <select name="area_type" id="areaType">
      <option value="" aria-placeholder="Select Area Condition" disabled></option>
      <option value="Restricted Area">Restricted Area</option>
      <option value="Maintenance Under Going">Maintenance</option>
    </select>
    <select name="floor" id="floorId">
        <option value="1">Floor 1</option>
        <option value="2">Floor 2</option>
        <option value="3">Floor 3</option>
        <option value="4">Floor 4</option>
        <option value="5">Floor 5</option>
    </select>
    <textarea name="description" id="descId" row="2" cols="20"></textarea>
    <div>
      <button type="button" id="cancelBtn">Cancel</button>
      <button type="button" id="confirmBtn">OK</button>
    </div>
  </form>
</dialog>
</html>
