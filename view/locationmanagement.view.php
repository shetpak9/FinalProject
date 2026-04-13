<body>
    
<div class="container">
    <div class="top_text">
        <div class="flex-group">
            <img src="" alt="">
            <a href="">Back To Home</a>
        </div>
        <header>
            <h1>Location <span>Management</span></h1>
            <p>Browse, search and manage all locations</p>
        </header>
    </div>

    <div class="search_bar">
        <input type="text" name="search" class="icon-input" placeholder="Search...">
        
        <div class="search_bar__filter">
            
            <img class="card_icon" src="view/images/filter-svgrepo-com.svg" alt="">
            <h4>Filters: </h4>
            <select class="search_bar__filter__select" name="type_id" id="">
                <option value="">All types</option>
                <option value="1">Classroom</option>
                <option value="2">Lab</option>
                <option value="3">Office</option>
                <option value="4">Other Facilities</option>
            </select>

            <select class="search_bar__filter__select" name="status_id" id="">
                <option value="">All Status</option>
                <option value="1">Available</option>
                <option value="2">Maintenance</option>
                <option value="3">Not Available</option>
            </select>
        </div>
    </div>

    <div class="content">
    <?php foreach($data as $row):?>
        <div class="card">
            <div class="card__header">
                <h3><?= $row['room'] ?></h3>

                <?php
                    $statusTxt = '';
                    $statusClass = '';
                    $locationTxt = '';

                    switch($row['status_id']){
                        case '1':
                            $statusTxt = "Available";
                            $statusClass = 'green';
                            break;
                        case '2':
                            $statusTxt = 'Maintenance';
                            $statusClass = 'orange';
                            break;
                        case '3':
                            $statusTxt = 'Occupied';
                            $statusClass = 'red';
                            break;
                    }

                    switch($row['type_id']){
                        case '1':
                            $locationTxt = 'Classroom';
                            break;
                        case '2':
                            $locationTxt = 'Lab';
                            break;
                        case '3':
                            $locationTxt = 'Office';
                            break;
                        case '4':
                            $locationTxt = 'Other Facilities';
                            break;
                    }


                ?>

                <span class="status <?= $statusClass ?>">
                    <?= $statusTxt ?>
                </span>
            </div>
            <p><img class="card_icon" src="view/images/map-mark-symbol-of-ios-7-svgrepo-com.svg" alt="">Floor <?= $row['floor']?> </p><br>
            <div class="details_button">
                <div class="details">
                    <p><img class="card_icon"src="view/images/people-svgrepo-com.svg" alt="">Capacity: <?= $row['capacity'] ?></p> <p><img class="card_icon" src="view/images/cube-alt-2-svgrepo-com.svg" alt=""> <?= $locationTxt ?></p>
                </div>
                <div class="com_button">
                    <form method="POST" action="">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button class="edit"
                            type="button"
                            onclick="openEditModal(
                                '<?= $row['id'] ?>',
                                '<?= $row['room'] ?>',
                                '<?= $row['capacity'] ?>',
                                '<?= $row['status_id'] ?>',
                                '<?= $row['type_id'] ?>',
                                '<?= $row['floor'] ?>',
                                '<?= $row['description'] ?>'
                            )">
                            Edit
                        </button>
                        <input class="delete" type="submit" name="delete" value="Delete">
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
</div>
<dialog id="editDialog" class="edit_dialog">
    <form method="POST" class="modal_form" enctype="multipart/form-data">
        <input type="hidden" id="edit_id" name="id">
        <div class="modal_header">
            <h2>Edit Location</h2>
        </div>

        <div class="modal_body">

            <h3>Basic Information</h3>

            <div class="input_group">
                <label>Room Name*</label>
                <input type="text" id="edit_room" name="room">
            </div>

            <div class="row">
                <div class="input_group">
                    <label>Status*</label>
                    <select id="edit_status" name="status_id">
                        <option value="1">Available</option>
                        <option value="2">Maintenance</option>
                        <option value="3">Occupied</option>
                    </select>
                </div>

                <div class="input_group">
                    <label>Type</label>
                    <select id="edit_type" name="type_id">
                        <option value="1">Classroom</option>
                        <option value="2">Lab</option>
                        <option value="3">Office</option>
                        <option value="4">Other Facilities</option>
                    </select>
                </div>
            </div>

            <div class="input_group">
                <label>Description*</label>
                <input type="text" id="edit_description" name="description">
            </div>

            <div class="row">
                <div class="input_group">
                    <label>Capacity</label>
                    <input type="number" id="edit_capacity" name="capacity">
                </div>

                <div class="input_group">
                    <label>Floor</label>
                    <select id="edit_floor" name="floor">
                        <option value="1">Floor 1</option>
                        <option value="2">Floor 2</option>
                        <option value="3">Floor 3</option>
                        <option value="4">Floor 4</option>
                        <option value="5">Floor 5</option>
                    </select>
                </div>
            </div>

            <div class="row bottom">
                <div class="input_group">
                    <label>Upload Image</label>
                    <input type="file" name="image" id="edit_image" accept="image/*">
                </div>
                <div class="actions">
                    <button type="submit" name="update" class="save_btn">Save</button>
                    <button type="button" onclick="closeModal()">Cancel</button>
                </div>
            </div>

        </div>
    </form>
</dialog>
</body>
</html>