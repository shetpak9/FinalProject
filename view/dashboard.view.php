<body>
    <div class="dashboard">
        <header>
            <h1>Dash<span>board</span></h1>
            <p>Overview of school map and locations</p>
        </header>

        <section class="cards">
            <div class="cards__info">
                <div class="card__text">
                    <h3>Total Locations</h3>
                    <h2><?= $locations ?></h2>
                </div>
                <div class="card__icon">
                    <img src="view/images/pin-map.png" alt="Location Icon">
                </div>
            </div>
            <div class="cards__info">
                <div class="card__text">
                    <h3>Available Rooms</h3>
                    <h2><?= $available ?></h2>
                </div>
                <div class="card__icon">
                    <img src="view/images/room.png" alt="Room Icon">
                </div>
            </div>
            <div class="cards__info">
                <div class="card__text">
                    <h3>Total Capacity</h3>
                    <h2><?= $capacity ?></h2>
                </div>
                <div class="card__icon">
                    <img src="view/images/people.png" alt="Capacity Icon">
                </div>
            </div>
        </section> 

        <section class="main-content">
            <div class="map">
                <div class="map__header">
                    <h3>Interactive Map</h3>
                </div>
                <div class="placeholder__map">
                    <div id="leaflet-map">
                    
                    </div>
                </div>
            </div>

            <div class="main_control">
                <div class="control">
                    <form method="GET" action="">
                        <h3>Floor</h3>
                        <select class="control__select" name="floor" onchange="this.form.submit()">
                            <option value="" disabled selected>Select a floor</option>
                            <?php
                                foreach(array_keys($floor) as $f) {
                                    $selected = (isset($_GET['floor']) && $_GET['floor'] == $f) ? 'selected' : '';
                                    echo "<option value='" . $f . "' $selected>Floor " . $f . "</option>";
                                }
                            ?>
                        </select>

                        <h3>Room</h3>
                        <select class="control__select" name="room">
                        <?php
                            if(isset($_GET['floor'])) {
                                $selectedFloor = $_GET['floor'];
                                foreach(array_keys($floor) as $f) {
                                    if($f == $selectedFloor){
                                        foreach($floor[$f] as $room) {
                                            $selected = (isset($_GET['room']) && $_GET['room'] == $room) ? 'selected' : '';
                                            echo "<option value='" . $room . "' $selected>" . $room . "</option>";
                                        }
                                    }
                                }
                            } else {
                                echo "<option value='' disabled selected>Select a floor first</option>";
                            }
                        ?>
                        </select>
                    </form>
                </div>

                <div class="status_location">
                    <div class="status">
                        <div class="status__text">
                            <h3>Status</h3>
                        </div>
                        <div class="status__color">
                            <p><span class="green"></span> Available</p>
                            <p><span class="red"></span> Occupied</p>
                            <p><span class="orange"></span> Maintenance</p>
                        </div>
                    </div>
                    <div class="location_type">
                        <div class="location_type__text">
                            <h3>Location Types</h3>
                        </div>
                        <div class="location_type__details">
                            <div class="row">
                                <span><?= $types[1] ?></span>
                                <span><?= $classroomCount ?></span>
                            </div>
                            <div class="row">
                                <span><?= $types[2] ?></span>
                                <span><?= $labCount ?></span>
                            </div>
                            <div class="row">
                                <span><?= $types[3] ?></span>
                                <span><?= $officeCount ?></span>
                            </div>
                            <div class="row">
                                <span><?= $types[4] ?></span>
                                <span><?= $otherCount ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="activity">
                <div class="activity__container">
                    <h3>Recent Activity</h3>
                    <div class="flex_wrap">
                        <ul>
                            <li>
                                <div class="activity__recent">
                                    <div class="activity__image">
                                        <img src="view/images/stopwatch.png" alt="Stopwatch Icon">
                                    </div>
                                    <div class="activity__text">
                                        <p class="room_text room_no">Room 211</p>
                                        <p class="room_text room_status">Status changed to Maintenance</p>
                                        <p class="room_text time">5 mins ago</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="activity__recent">
                                    <div class="activity__image">
                                        <img src="view/images/stopwatch.png" alt="Stopwatch Icon">
                                    </div>
                                    <div class="activity__text">
                                        <p class="room_text room_no ">Room 111</p>
                                        <p class="room_text room_status">Status changed to Available</p>
                                        <p class="room_text time">20 mins ago</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="activity__recent">
                                    <div class="activity__image">
                                        <img src="view/images/stopwatch.png" alt="Stopwatch Icon">
                                    </div>
                                    <div class="activity__text">
                                        <p class="room_text room_no">Room 316</p>
                                        <p class="room_text room_status">Status changed to Available</p>
                                        <p class="room_text time">5 mins ago</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="quick-actions">
            <div class="quick-actions__button">
                <button onclick="window.location.href='addlocation'">Add Locations <br> <img src="view/images/map.png" alt=""></button>
                <button onclick="window.location.href='locationmanagement'">Manage Locations <br><img src="view/images/location.png" alt=""></button>
                <button onclick="window.location.href='viewlogs'">View Logs <br><img src="view/images/bar-chart.png" alt=""></button>
            </div>
        </section>
        
    </div>
</body>
</html>