<?php
require 'src/display/location_view.php';
require 'src/config/LogUtil.php';


$data = $gateway->getAll();
$allCount = count($data);

$css = '<link rel="stylesheet" href="view/css/locationmanagement.css">';

if(isset($_GET['search'])){
    $search = $_GET['keyword'] ?? '';
    $type_id = $_GET['type_id'] ?? '';
    $status_id = $_GET['status_id'] ?? '';

    $data = $gateway->search($search, $type_id, $status_id);
}

if(isset($_POST['delete'])){
    $id = $_POST['id'];
    $location = $gateway->get($id);
    $gateway->delete($id);
    
    // Log the deletion
    logAction($pdo, $_SESSION['user_id'], 'DELETE', 'location', $id, [
        'room' => $location['room'] ?? null,
        'floor' => $location['floor'] ?? null
    ]);
}

if(isset($_POST['update'])){

    $id = $_POST['id'];

    $current = $gateway->get($id);

    $data = $_POST;

    if(isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {

        $filename = time() . "_" . $_FILES["image"]["name"];
        $target = __DIR__ . "/../uploads/" . $filename;

        move_uploaded_file($_FILES["image"]["tmp_name"], $target);

        $data["image"] = $filename;

    } else {
        $data["image"] = $current["image"];
    }

    $gateway->update($current, $data);
    
    // Log the update
    $changes = [];
    foreach ($data as $key => $value) {
        if ($current[$key] != $value && $key != 'id') {
            $changes[$key] = ['old' => $current[$key], 'new' => $value];
        }
    }
    logAction($pdo, $_SESSION['user_id'], 'UPDATE', 'location', $id, $changes);
    
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}

if(isset($_POST['add_event'])){
    $data = $_POST;

    $eventId = $gateway->addEvent($data);

    // Log the event creation
    logAction($pdo, $_SESSION['user_id'], 'CREATE', 'event', $eventId, [
        'location_id' => $data['location_id'] ?? null,
        'event_title' => $data['event_title'] ?? null
    ]);

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}
if(isset($_POST['announcement'])){
    $data = $_POST;

    $announcementId = $gateway->addAnnouncement($data);

    // Log the announcement creation
    logAction($pdo, $_SESSION['user_id'], 'CREATE', 'announcement', $announcementId, [
        'title' => $data['title'] ?? null,
        'announcement_type' => $data['announcement_type'] ?? null
    ]);

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}

require 'view/partials/header.php';

require 'view/locationmanagement.view.php';

?>
<script>
    const edit = document.getElementById("editDialog");

    function openEditModal(id, room, capacity, status, type, floor, description, image) {
    edit.showModal();

    document.getElementById("edit_id").value = id;
    document.getElementById("edit_room").value = room;
    document.getElementById("edit_capacity").value = capacity;
    document.getElementById("edit_status").value = status;
    document.getElementById("edit_type").value = type;
    document.getElementById("edit_floor").value = floor;
    document.getElementById("edit_description").value = description;
    }

    function closeModal() {
        edit.close();
    }

</script>


