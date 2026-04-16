<body>
    <div class="container">
        <header>
            <h1>Interactive <span>Map</span></h1>
            <p>View and Navigate School Locations</p>
        </header>
        <form action="" method="GET">
            <div class="search_bar">
                <input type="text" name="keyword" placeholder="Search locations...">
            </div>

            <div class="main_content">
                <div class="map_section">
                    <div class="placeholder_map">
                        <div id="leaflet-map">
    
                        </div>
                    </div>
                </div>
                
                <div class="content_section">
                    <div class="filter_section">
                        <img src="" alt="">
                        <label>Filters</label>
                        <select class="search_bar__filter__select" name="type_id" id="">
                            <option value="" <?= !isset($_GET['type_id']) ? 'selected' : '' ?>>All types</option>
                            <option value="1" <?= isset($_GET['type_id']) && $_GET['type_id'] === '1' ? 'selected' : '' ?>>Classroom</option>
                            <option value="2" <?= isset($_GET['type_id']) && $_GET['type_id'] === '2' ? 'selected' : '' ?>>Lab</option>
                            <option value="3" <?= isset($_GET['type_id']) && $_GET['type_id'] === '3' ? 'selected' : '' ?>>Office</option>
                            <option value="4" <?= isset($_GET['type_id']) && $_GET['type_id'] === '4' ? 'selected' : '' ?>>Other Facilities</option>
                        </select>
                        <select class="search_bar__filter__select" name="status_id" id="">
                            <option value="" <?= !isset($_GET['status_id']) ? 'selected' : '' ?>>All Status</option>
                            <option value="1" <?= isset($_GET['status_id']) && $_GET['status_id'] === '1' ? 'selected' : '' ?>>Available</option>
                            <option value="2" <?= isset($_GET['status_id']) && $_GET['status_id'] === '2' ? 'selected' : '' ?>>Maintenance</option>
                            <option value="3" <?= isset($_GET['status_id']) && $_GET['status_id'] === '3' ? 'selected' : '' ?>>Occupied</option>
                        </select>
                        <select class="search_bar__filter__select" name="floor" id="">
                            <option value="" <?= !isset($_GET['floor']) ? 'selected' : '' ?>>All Floors</option>
                            <option value="1" <?= isset($_GET['floor']) && $_GET['floor'] === '1' ? 'selected' : '' ?>>Floor 1</option>
                            <option value="2" <?= isset($_GET['floor']) && $_GET['floor'] === '2' ? 'selected' : '' ?>>Floor 2</option>
                            <option value="3" <?= isset($_GET['floor']) && $_GET['floor'] === '3' ? 'selected' : '' ?>>Floor 3</option>
                            <option value="4" <?= isset($_GET['floor']) && $_GET['floor'] === '4' ? 'selected' : '' ?>>Floor 4</option>
                            <option value="5" <?= isset($_GET['floor']) && $_GET['floor'] === '5' ? 'selected' : '' ?>>Floor 5</option>
                        </select>
                    </div>
                    <div class="map_details">
                        <div class="map_details__button">
                            <button name="report">Report Lost Item</button>
                            <button name="favorite">Add to Fav</button>
                        </div>

                        <div class="map_details__image">
                            <img src="" alt="">
                        </div>

                        <div class="map_details__info">
                            <h3>Location Name</h3>
                            <p class="map_details__Description">Location details and description go here. This section provides more information about the selected location on the map.</p>
                            <p class="map_details__Type_id">Location Type: </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>    
    </div>
</body>
</html>