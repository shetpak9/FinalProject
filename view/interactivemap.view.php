<body>
<div class="layout">
    <aside class="sidebar">
        <div class="menu_top">
            <img 
                    src="view/images/Copilot_20260424_221814-removebg-preview.png" 
                    data-light="view/images/Copilot_20260424_221814-removebg-preview.png"
                    data-dark="view/images/678720890_4334231273387713_5956134698655175783_n-removebg-preview.png"
                    class="theme-img"
                >
            <div class="menu_btn_flex">
                <img 
                    src="view/images/event-svgrepo-com(1).svg" 
                    data-light="view/images/event-svgrepo-map-com.svg"
                    data-dark="view/images/event-svgrepo-map-com(1).svg"
                    class="theme-img"
                >
                <button type="button" onclick="document.getElementById('eventDialog').showModal();">Event</button>
            </div>
            <div class="menu_btn_flex">
                <img 
                    src="view/images/heart-svgrepo-com.svg" 
                    data-light="view/images/heart-svgrepo-com.svg"
                    data-dark="view/images/heart-svgrepo-com(1).svg"
                    class="theme-img"
                >
                <button type="button" onclick="document.getElementById('favDialog').showModal();">Favorites</button>
            </div>
            <div class="menu_btn_flex">
                <img 
                    src="view/images/lost-items-missing-svgrepo-com.svg" 
                    data-light="view/images/lost-items-missing-svgrepo-com.svg"
                    data-dark="view/images/lost-items-missing-svgrepo-com(1).svg"
                    class="theme-img"
                >
                <button type="button" onclick="document.getElementById('itemDialog').showModal();">Lost Items</button>
            </div>
        </div>
        <div class="menu_bottom">
            <div class="menu_bottom__flex">
                <img 
                    src="view/images/logout-2-svgrepo-com.svg" 
                    data-light="view/images/logout-2-svgrepo-com.svg"
                    data-dark="view/images/logout-2-svgrepo-com(1).svg"
                    class="theme-img"
                >
                <button type="button" onclick="window.location.href='controllers/logout.php'">Logout</button>
            </div>  
            <div class="menu_bottom__flex">  
                <img 
                    src="view/images/gear-svgrepo-com.svg" 
                    data-light="view/images/gear-svgrepo-com.svg"
                    data-dark="view/images/gear-svgrepo-com(1).svg"
                    class="theme-img"
                >
                <button type="button" onclick="document.getElementById('settingsDialog').showModal();">
                    Settings
                </button>
            </div>
        </div>
    </aside>
    <div class="container">
        <header>
            <div class="flex_text">  
                <h1>Interactive <span>Map</span></h1>
                <p>View and Navigate School Locations</p>
            </div>
        </header>
        <form action="" method="GET">
            <div class="search_bar">
                <input type="text" name="keyword" placeholder="Search locations...">
                <button><img src="view/images/search-alt-2-svgrepo-com.svg" alt=""></button>
            </div>
            <div class="main_content">
                <div class="map_section">
                    <div class="map_legends">
                        <div class="status__color">
                            <p><span class="green"></span> Classroom</p>
                            <p><span class="red"></span> Lab</p>
                            <p><span class="orange"></span> Office</p>
                            <p><span class="blue"></span> Other Facilities</p>
                        </div>
                    </div>
                    <div class="placeholder_map">
                        <div id="leaflet-map">
    
                        </div>
                    </div>
                </div>
                
                <div class="content_section">
                    <div class="filter_section">
                        <div class="filter_section__txt">
                            <img src="view/images/filter-svgrepo-com.svg" alt="">
                            <label>Filter:</label>
                        </div>
                        <div class="filter_section__dd">
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
                            <input type="submit" value="Filter">
                        </div>
                    </div>
                    <div class="map_details">
                        <div class="map_details__button">
                            <button type="button" name="report"><img src="view/images/report-text-svgrepo-com.svg" alt=""> Report Lost Item</button>
                            <button type="button" name="favorite"><img src="view/images/heart-svgrepo-com1.svg" alt=""> Add to Fav</button>
                        </div>

                        <div class="map_details__image">
                            <img src="view/images/Screenshot 2026-04-16 194758.png" alt="">
                        </div>

                        <div class="map_details__info">
                            <h3>Location Name:</h3>
                            <p class="map_details__Description">Description:</p>
                            <p class="map_details__Type_id">Location Type: </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>    
    </div>
</div>
<dialog class="report_dialog" id="reportDialog">
    <h3>Report Lost Item</h3>
    <div class="report_container">
        <input type="text" class="report_txtbox" id="itemName" placeholder="Item name">
        <select name="item_state" class="report_txtbox" id="itemState" required>
            <option value="" placeholder="" disabled>What are you reporting?</option>
            <option value="Lost Item">Lost Item</option>
            <option value="Item Found">Item Found</option>
        </select>
        <textarea id="itemDesc" class="report_txtbox" placeholder="Description"></textarea>
        <div style="margin-top:10px;">
            <button id="submitReport">Submit</button>
            <button id="cancelReport">Cancel</button>
        </div>
    </div>
</dialog>
<dialog id="eventDialog" class="event_dialog">
    <div class="event_container">
        <div class="event_header">
            <div class="event_header__text">
                <h3>Events</h3>
                <p>Upcoming school events</p>
            </div>    
            <div class="event_header__xbtn">
                <button onclick="document.getElementById('eventDialog').close()">X</button>
            </div>
        </div>
        <div class="event_content">
            <?php foreach($events as $event): 
                $dt = new DateTime($event['time']);

                $date = $dt->format('F j, Y');
                $time = $dt->format('g:iA')
            ?>
                <div class="event_card">
                    <div class="event_title">
                        <h3> <?= $event['title'] ?? '' ?></h3>
                        <p class="status">
                            <?php
                                if($event['event_status'] == 'Upcoming'){
                                    $color = '#68b868';
                                }
                                else{
                                $color = '#e78c36';
                                }
                            ?>
                        <span style="background: <?= $color ?>;" class="status_type">
                            <?= $event['event_status'] ?? '' ?>
                        </span>
                        </p>
                    </div>

                    <div class="event_details">
                        <p class="event_description"><?= $event['description'] ?? '' ?></p>
                        <div class="event_details__flex">
                            <div class="event_icon">
                                <img src="view/images/stopwatch.png" alt="">
                            </div>
                            <div class="event_text">
                                <p class="uppertex"> <?= $date ?></p>
                                <p class="lowertext"><?= $time ?></p>
                            </div>    
                        </div>
                        <div class="event_details__flex">
                            <div class="event_icon">
                                <img src="view/images/pin-map.png" alt="">
                            </div>
                            <div class="event_text">
                                <p class="uppertex"> <?= $event['room'] ?></p>
                                <p class="lowertext">Floor <?= $event['floor'] ?></p>
                            </div>    
                        </div>
                        <div class="event_details__flex">
                            <div class="event_icon">
                                <img src="view/images/people.png" alt="">
                            </div>
                            <div class="event_text">
                                <p class="uppertex"> Organizer</p>
                                <p class="lowertext"><?= $event['organizer'] ?></p>
                            </div>    
                        </div>
                        <div class="event_btn">
                            <button type="button" 
                                    class="show-on-map-btn"
                                    data-location-id="<?= $event['location_id']?>"
                                    onclick="document.getElementById('eventDialog').close();">
                            Show on Map</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</dialog>
<dialog id="favDialog" class="fav_dialog">
    <div class="fav_container">
        <div class="fav_header">
            <div class="fav_header__text">
                <h3>Favorite Locations</h3>
                <p>Quick access to your frequently visited places</p>
            </div>
            <div class="fav_header__xbtn">
                <button onclick="document.getElementById('favDialog').close()">X</button>
            </div>
        </div>
        <div class="fav_content">
            <?php foreach($fav as $row): ?>
                <div class="fav_card">
                    <div class="fav_title">
                        <h3><?= $row['room'] ?></h3>
                        <form method="POST">
                            <input type="hidden" name="fav_id" value="<?= $row['favorite_id'] ?>">
                            <button type="submit" name="delete_fav"><img src="view/images/garbage-can-svgrepo-com.svg" alt=""></button>
                        </form>
                    </div>
                    <div class="fav_details">
                        <?php
                            if($row['status_type'] == 'Available'){
                                $color = '#68b868';
                            }else if($row['status_type'] == 'Maintenance'){
                                $color = '#e78c36';
                            }else{
                                $color = '#f03232';
                            }
                        ?>
                        <p><span style="background: <?= $color ?>;"><?= $row['status_type'] ?></span> <?= $row['loc_type'] ?></p>
                        <p><img src="view/images/floor-plan-svgrepo-com.svg" alt=""> Floor <?= $row['floor']?></p>
                        <p><img src="view/images/people.png" alt=""> Capacity: <?= $row['capacity'] ?></p>
                        <p><?= $row['description'] ?></p>
                    </div>
                    <div class="fav_btn">
                            <button type="button" 
                                    class="show-on-map-btn"
                                    data-location-id="<?= $row['id']?>"
                                    onclick="document.getElementById('favDialog').close();">
                            Show on Map</button>
                        </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</dialog>
<dialog id="itemDialog" class="item_dialog">
    <div class="item_container">
        <div class="item_header">
            <div class="item_header__txt">
                <h3>Lost Items</h3>
            </div>
            <div class="item_header__xbtn">
                <button onclick="document.getElementById('itemDialog').close()">X</button>
            </div>
        </div>
        <div class="item_content">
            <?php foreach($lost as $row):
            $src= '';
            if($row['item_state'] == "Item Found"){
                $src ="view/images/searching-location-svgrepo-com.svg";
            } else{
                $src ="view/images/status-error-filled-svgrepo-com.svg";
            }
            ?>
            <div class="item_card">
               <div class="item_img">
                    <img src="<?= $src ?>" alt="">
               </div> 
               <div class="item_txt">
                    <h3><?= $row['item_state'] ?></h3>
                    <p>Item Name: <?= $row['name'] ?></p>
                    <p>Description: <?= $row['description'] ?></p>
               </div>
               <div class="item_btn">
                <button type="button" 
                        class="showItem-on-map-btn"
                        data-item-id="<?= $row['id']?>"
                        onclick="document.getElementById('itemDialog').close();">
                    Show on Map
                </button>
               </div>
            </div>

            <?php endforeach ?>
        </div>
    </div>
</dialog>
<dialog id="settingsDialog" class="settings_dialog">
    <div class="settings_container">

        <div class="settings_header">
            <h3>Settings</h3>
            <button type="button" onclick="document.getElementById('settingsDialog').close()">X</button>
        </div>

        <div class="settings_body">
            <div class="settings_section">
                <label class="settings_option">
                    <input type="checkbox" id="toggleMarkers" checked>
                    Show Markers
                </label>

                <label class="settings_option">
                    <input type="checkbox" id="toggleShapes" checked>
                    Show Zones
                </label>
                <label class="settings_option">
                        <input type="checkbox" name="darkmode" id="themeToggle">
                    Dark Mode
                </label>
            </div>
        </div>
    </div>
</dialog>
</body>
</html>