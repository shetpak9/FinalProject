<?php
require 'src/display/location_view.php';

$data = $gateway->getAll();

$css = '<link rel="stylesheet" href="view/css/locationmanagement.css">';

if(isset($))

if(isset($_POST['delete'])){
    $id = $_POST['id'];
    $gateway->delete($id);
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
    
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}

require 'view/partials/header.php';

require 'view/locationmanagement.view.php';

?>
<script>
    const dialog = document.getElementById("editDialog");

    function openEditModal(id, room, capacity, status, type, floor, description, image) {
    dialog.showModal();

    document.getElementById("edit_id").value = id;
    document.getElementById("edit_room").value = room;
    document.getElementById("edit_capacity").value = capacity;
    document.getElementById("edit_status").value = status;
    document.getElementById("edit_type").value = type;
    document.getElementById("edit_floor").value = floor;
    document.getElementById("edit_description").value = description;
}

    function closeModal() {
        dialog.close();
    }
</script>


