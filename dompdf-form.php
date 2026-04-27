<?php 
require 'src/config/Database.php';
require 'src/config/LogGateway.php';

$database = new Database("localhost", "school_map", "root", "");
$logGateway = new LogGateway($database);

$logs = $logGateway->getAllLogs();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="view/css/reset.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body{
            font-family: 'Inter';
        }
        header{
            display: flex;
            justify-content: center;

            margin-top: 50px;
        }
        .header_txt__p{
            display: flex;
            justify-content: center;

            margin-top: 30px;
            margin-bottom: 30px;
        }
        .logo img{
            height: 200px;
            width: 200px;
        }
        .header_txt h1{
            font-size: 1.2rem;
        }
        .table_flex{
            display: flex;
            justify-content: center;
        }
        table{
            border: 1px solid black;
            border-radius: 10px;
        }
        table th{
            background: #476b97;
        }
        table td, th{
            padding: 20px;

            border: 1px solid black;
        }
        
    </style>
</head>
<body>
    <header>
        <div class="header_txt">
            <h1 style="font-size: 2rem;">School Map Management System</h1>
            <div class="header_txt__p">
                <h4 style="font-size: 1.5rem;">Activity Logs</h4>
            </div>
        </div>    
    </header>
    <div class="table_flex">
        <table>
            <thead>
                <th>User</th>
                <th>Action</th>
                <th>Entity</th>
                <th>ID</th>
                <th>TimeStamp</th>
            </thead>
            <?php foreach($logs as $row): ?>
            <tr>
                <td><?= $row['name'] ?></td>
                <td><?= $row['action'] ?></td>
                <td><?= $row['entity_type'] ?></td>
                <td><?= $row['id'] ?></td>
                <td><?= $row['created_at'] ?></td>
            </tr>
            <?php endforeach ?>
        </table>
    </div>
</body>
</html>