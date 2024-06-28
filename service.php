<?php
session_start();
if (!isset($_SESSION["email"]) && !isset($_SESSION["password"])) {
    echo "<script>alert('Please Login')</script>";
    echo "<script>window.location.href = 'login.php'</script>";
}
include('dbconnect.php');

try {
    $stmt = $conn->prepare("SELECT * FROM tbl_services");
    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    $services = []; // Ensure $services is an array even in case of an error
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="styles/mystyle.css">
    <title>Services</title>
    <style>
        .service-card {
            max-width: 350px;
            height: 400px;
            margin: 20px;
            text-align: justify;
        }
        body {
            font-family: Garamond, serif;
            font-size: larger;
        }
        
        .w3-modal-content {
            max-width: 500px;
            text-align: justify;
        }

        @media screen and (min-width: 1920px) {
            body {
                max-width: 60%;
                margin: auto;
            }
        }

    </style>
    <script>
        function showModal(serviceId) {
            document.getElementById(serviceId).style.display='block';
        }

        function closeModal(serviceId) {
            document.getElementById(serviceId).style.display='none';
        }
    </script>
</head>
<body>
    <div class="w3-header w3-container w3-pale-red w3-padding-28 w3-center">
        <h1 style="font-size:calc(8px + 4vw);">MYCLINIC</h1>
        <p style="font-size:calc(8px + 1vw);;">We serve the people</p>
    </div>
    <div class="w3-bar w3-pink">
        <a href="login.php" class="w3-bar-item w3-button w3-right">Logout</a>
        <a href="service.php" class="w3-bar-item w3-button w3-left">Services</a>    
        <a href="index.php" class="w3-bar-item w3-button w3-right">Back</a>  
    </div>
    <div class="w3-container w3-padding-32">
        <h1>Available Services</h1>
        <div class="w3-container w3-row w3-padding w3-center" style="margin: auto;">
            <div class="w3-container w3-row w3-card w3-margin ">
            <?php foreach ($services as $service): ?>
                <div class="w3-col s12 m6 l4 w3-margin-bottom">
                    <div class="service-card" onclick="showModal('modal<?= $service['service_id'] ?>')">
                    <img src="images/<?= $service['service_id'] ?>.png" alt="<?= htmlspecialchars($service['service_name']) ?>" style="width:100%; height:200px; object-fit: cover;">
                        <h3><?= $service['service_name'] ?></h3>
                        <p><?= substr($service['service_description'], 0, 100) ?>...</p>
                    </div>
                </div>

                <!-- Modal for displaying service details -->
                <div id="modal<?= $service['service_id'] ?>" class="w3-modal">
                    <div class="w3-modal-content w3-animate-opacity">
                        <header class="w3-container w3-pale-red">
                            <span onclick="closeModal('modal<?= $service['service_id'] ?>')" 
                                  class="w3-button w3-display-topright">&times;</span>
                            <h2><?= $service['service_name'] ?></h2>
                        </header>
                        <div class="w3-container">
                            <p><strong>Description:</strong> <?= $service['service_description'] ?></p>
                            <p><strong>Price:</strong> $<?= number_format($service['service_price'], 2) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <footer class=" w3-container  w3-pale-red w3-center">
        <p>Copyright MyClinic&copy</p>
    </footer>
</body>
</html>
