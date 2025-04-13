<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

// Initialize default region or saved region
$defaultLatitude = 19.1631; // Example: Default location (Nanded)
$defaultLongitude = 77.3144;

$savedLatitude = $_SESSION['region']['latitude'] ?? $defaultLatitude;
$savedLongitude = $_SESSION['region']['longitude'] ?? $defaultLongitude;

// Handle form submission to save the selected region
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['latitude']) && isset($_POST['longitude'])) {
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // Save the selected region in the session
    $_SESSION['region'] = ['latitude' => $latitude, 'longitude' => $longitude];

    // Refresh saved region
    $savedLatitude = $latitude;
    $savedLongitude = $longitude;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offer Help</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
        font-family: 'Arial', sans-serif;
        background: radial-gradient(
            circle at 80% 0%,
            #daf1f9 0%,
            #f1e8f6 50%,
            #f0f0f0 70%,
            #dfeaed9e 100%
        );
       }
        #map {
            height: 400px;
            width: 100%;
            margin-bottom: 20px;
            border-radius: 15px;
            border: 5px solid black;
        }
        #saveb {
            width: 21vh;
            height: 5vh;
            border-radius: 6px;
            background-color: rgb(34, 201, 209);
            transition: transform 0.3s ease, background-color 0.3s ease;
        }
        #saveb:hover {
            background-color: #f8f8f8;
            transform: scale(1.1);
        }
        .request {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 16px;
            margin-bottom: 20px;
            border: 4px solid rgb(28, 145, 154);
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        #btns, #bts1 {
            padding: 1%;
            border-radius: 5px;
            margin: 5px;
            font-weight: bold;
            transition: transform 0.3s ease, background-color 0.3s ease;
            display: inline-block;
        }
        #btns {
            background-color: rgba(255, 11, 11, 0.89);
        }
        #bts1 {
            background-color: rgb(15, 255, 11);
        }
        .delete-button:hover, #bts1:hover {
            cursor: pointer;
            transform: scale(1.1);
        }
    </style>
</head>
<body class="bg-gray-100">

   <!-- Header -->
<header class="py-4 shadow-md">
  <div class="container mx-auto px-4 flex flex-col lg:flex-row justify-between items-center gap-4">
    
    <!-- Left: Logo Section -->
    <div class="flex items-center space-x-3">
      <img src="./assets/logo1.png" alt="CommUnity Logo" class="rounded-full w-11 h-11">
      <h1 class="text-2xl lg:text-3xl font-extrabold text-gray-800">CommUnity</h1>
    </div>

    <!-- Middle: Primary Nav Links -->
    <nav class="flex flex-wrap justify-center gap-3">
    <a href="dashboard.php" class="px-4 py-2 hover:bg-gray-200 transition">Dashboard</a>
    <a href="post-request.php" class="px-4 py-2 hover:bg-gray-200 transition" >Post Request</a>
      <a href="offer-help.php" class="px-4 py-2 hover:bg-gray-200 transition" style="font-weight: 800">Offer Help</a>
      <a href="profile.php" class="px-4 py-2 hover:bg-gray-200 transition">Profile</a>
      <a href="ad.html" class="px-4 py-2 hover:bg-gray-200 transition">Advertisement</a>
      <a href="logout.php" class="px-4 py-2 hover:bg-gray-200 transition">Logout</a>
    </nav>

  </div>
</header>

    <main class="max-w-4xl mx-auto py-8">
        <h2 class="text-2xl font-semibold mb-4">Select Your Location</h2>
        <div id="map"></div>
        <form method="POST" class="text-center">
            <input type="hidden" id="latitude" name="latitude" value="<?php echo htmlspecialchars($savedLatitude); ?>">
            <input type="hidden" id="longitude" name="longitude" value="<?php echo htmlspecialchars($savedLongitude); ?>">
            <button type="submit" id="saveb" class="text-white font-bold text-lg">Save Location</button>
        </form>

        <h2 class="text-2xl font-semibold my-4">Help Requests Near You</h2>

        <?php
  
        if (isset($_SESSION['region'])) {
            $user_lat = $_SESSION['region']['latitude'];
            $user_lng = $_SESSION['region']['longitude'];

            $sql = "SELECT requests.*, users.name AS user_name, 
                        (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
                        cos(radians(longitude) - radians(?)) + 
                        sin(radians(?)) * sin(radians(latitude)))) AS distance 
                    FROM requests
                    JOIN users ON requests.user_id = users.id
                    HAVING distance <= 1.0
                    ORDER BY distance ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ddd", $user_lat, $user_lng, $user_lat);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $escrow_id = $row['escrow_id'] ?? ''; // Use empty string if not set
                    $provider_id = $row['provider_id'] ?? ''; // Use empty string if not set
                    echo "<div class='request'>
                            <h3 class='text-xl font-bold'>" . htmlspecialchars($row['title']) . "</h3>
                            <p>" . htmlspecialchars($row['description']) . "</p>
                            <p><strong>Category:</strong> " . htmlspecialchars($row['category']) . "</p>
                            <p><strong>Requested by:</strong> " . htmlspecialchars($row['user_name']) . "</p>";

                    // Show delete button only for the user's own requests
                    if ($row['user_id'] == $_SESSION['user_id']) {
                        echo "<form action='delete_request.php' method='POST' onsubmit='return confirm(\"Are you sure you want to delete this request?\");'>
                                <input type='hidden' name='request_id' value='" . htmlspecialchars($row['id']) . "'>
                                <button type='submit' class='delete-button' id='btns'>Delete Request</button>
                              </form>";
                    }

                    // Offer Help button for all requests
                    echo "<form action='messages.php' method='POST'>
                            <input type='hidden' name='request_id' value='" . htmlspecialchars($row['id']) . "'>
                            <button type='submit' id='bts1'>Offer Help</button>
                          </form>
                          </div>";
                }
            } else {
                echo "<p>No requests found in your region within 1km radius.</p>";
            }
        } else {
            echo "<p>Please select your region first.</p>";
        }
        ?>
    </main>
    <script
    src="https://app.wonderchat.io/scripts/wonderchat.js"
    data-name="wonderchat"
    data-address="app.wonderchat.io"
    data-id="cm9f5mmos10uzkbvo3u2j4kiy"
    data-widget-size="normal"
    data-widget-button-size="normal"
  
    defer
  ></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-search/3.0.4/leaflet-search.min.js" defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Initialize the map and set its view to the saved region or default
            const savedLat = <?php echo $savedLatitude; ?>;
            const savedLng = <?php echo $savedLongitude; ?>;

            const map = L.map('map').setView([savedLat, savedLng], 14);

            // Add OpenStreetMap tiles to the map
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Add a draggable marker to the map
            const marker = L.marker([savedLat, savedLng], { draggable: true }).addTo(map);

            // Update latitude and longitude fields when the marker is dragged
            marker.on('moveend', (event) => {
                const { lat, lng } = event.target.getLatLng();
                document.getElementById('latitude').value = lat.toFixed(6); // Format to 6 decimal places
                document.getElementById('longitude').value = lng.toFixed(6);
            });

            // Add search control to the map
            const searchControl = new L.Control.Search({
                url: 'https://nominatim.openstreetmap.org/search?format=json&q={s}', // Search endpoint
                jsonpParam: 'json_callback',
                propertyName: 'display_name',
                propertyLoc: ['lat', 'lon'],
                marker: false,
                moveToLocation: function (latlng) {
                    map.setView(latlng, 14); // Zoom to the selected location
                    marker.setLatLng(latlng); // Move marker to the selected location
                    document.getElementById('latitude').value = latlng.lat.toFixed(6);
                    document.getElementById('longitude').value = latlng.lng.toFixed(6);
                },
            });

            map.addControl(searchControl);
        });
    </script>
</body>
</html>
