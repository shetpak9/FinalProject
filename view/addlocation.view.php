<body>
<form method="POST" action="" enctype="multipart/form-data">
    <div class="container">
        <div class="top_text">
            <a href="/FinalProject/">Back to Home</a>
            <p> | Add Location</p>
        </div>

        <div class="content">
            <div class="sidebar">
                <div class="sidebar__menu">
                    <input type="text" name="room" class="menu-item" placeholder="📍Room name">
                    <select name="type_id" class="menu-item" id="">
                        <option value="" disabled selected>Select Location Type</option>
                        <option value="1">Classroom</option>
                        <option value="2">Lab</option>
                        <option value="3">Office</option>
                        <option value="4">Other Facilities</option>
                    </select>
                    <input type="text" class="menu-item" placeholder="🚪 Room Number">
                    <select name="floor" class="menu-item" id="">
                        <option value="" disabled selected>Select a Floor</option>
                        <option value="1">Floor 1</option>
                        <option value="2">Floor 2</option>
                        <option value="3">Floor 3</option>
                        <option value="4">Floor 4</option>
                        <option value="5">Floor 5</option>
                    </select>
                    <input type="number" name="capacity"class="menu-item" placeholder="👥 Capacity">
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">
                    <textarea name="description" class="menu-item" placeholder="📄 Description"></textarea>
                </div>

                <div class="picture">
                    <h3>Upload Image</h3>
                    <div class="upload-box">
                        <input type="file" name="image" id="imageUpload" accept="image/*">
                        <label for="imageUpload" class="upload-label">
                            Click to Upload Image
                        </label>
                    </div>
                </div>

                <div class="button">
                    <input type="submit" name="submit" value="Save">
                </div>
            </div>
            
            <div class="map_section">
                <div class="map">
                    <div id="leaflet-map">

                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
</body>
</html>
<script src="js/leaflet.map.js"></script>
<script src="js/function.js"></script>
<script>
    onclick(map);
</script>