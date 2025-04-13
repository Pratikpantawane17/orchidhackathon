<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

// Initialize default region or saved region
$defaultLatitude = 19.1631; // Example: Nanded
$defaultLongitude = 77.3144;

$savedLatitude = $_SESSION['region']['latitude'] ?? $defaultLatitude;
$savedLongitude = $_SESSION['region']['longitude'] ?? $defaultLongitude;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle the help request submission
    if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['category'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        $user_id = $_SESSION['user_id'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];

        // Save the region in the session
        $_SESSION['region'] = ['latitude' => $latitude, 'longitude' => $longitude];

        // Insert help request into the database
        $sql = "INSERT INTO requests (title, description, category, user_id, latitude, longitude) 
                VALUES ('$title', '$description', '$category', '$user_id', '$latitude', '$longitude')";
        if ($conn->query($sql) === TRUE) {
            header('Location: dashboard.php');
            exit;
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Help Request</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet-search/3.0.4/leaflet-search.min.css" />
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

       /* Map and Form Styling */
       #map {
            height: 300px;
            width: 50%;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
       }

       form {
            background-color: #fff;
            padding: 25px;
            margin: 20px auto;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 90%;
        }

        label, input, textarea, select, button {
            display: block;
            width: 100%;
            font-size: 1rem;
            margin-bottom: 20px;
        }

        input, textarea, select {
            margin-bottom: 15px;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input:focus, textarea:focus, select:focus {
            border-color: #4CAF50;
            outline: none;
        }

        button {
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        @media (max-width: 768px) {
            #map { height: 250px; }
            form { padding: 15px; }
            button { font-size: 1rem; }
        }

        @media (max-width: 480px) {
            #map { height: 200px; }
            form { padding: 10px; }
            button { padding: 10px; font-size: 0.9rem; }
        }

        label {
            padding: 5x;
        }
    </style>
</head>
<body>

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
      <a href="post-request.php" class="px-4 py-2 hover:bg-gray-200 transition">Post Request</a>
      <a href="offer-help.php" class="px-4 py-2 hover:bg-gray-200 transition">Offer Help</a>
      <a href="profile.php" class="px-4 py-2 hover:bg-gray-200 transition">Profile</a>
      <a href="ad.html" class="px-4 py-2 hover:bg-gray-200 transition">Advertisement</a>
      <a href="logout.php" class="px-4 py-2 hover:bg-gray-200 transition">Logout</a>
    </nav>

  </div>
</header>

<h1 class="text-3xl font-bold text-center my-6 text-green-600">Post Help Request</h1>
<center>
    <div id="map"></div>
</center>
<form method="POST">
    <label for="title">Request Title:</label>
    <input type="text" id="title" name="title" placeholder="Request Title" style="padding: 10px" required>

    <label for="description" >Request Details:</label>
    <textarea id="description" name="description" placeholder="Request Details" rows="4" style="padding: 10px" required></textarea>

    <label for="category">Category:</label>
    <select id="category" name="category">
        <option value="groceries">Groceries</option>
        <option value="repairs">Repairs</option>
        <option value="other">Other</option>
    </select>
    <input type="hidden" name="poster_id" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">

    <label for="latitude">Latitude:</label>
    <input type="text" id="latitude" name="latitude" value="<?php echo htmlspecialchars($savedLatitude); ?>" readonly required>

    <label for="longitude">Longitude:</label>
    <input type="text" id="longitude" name="longitude" value="<?php echo htmlspecialchars($savedLongitude); ?>" readonly required>

    <button type="submit">Post Request</button>
</form>

<div id="saved-region" class="text-center my-4">
    <p><strong>Saved Region:</strong></p>
    <p>Latitude: <?php echo htmlspecialchars($savedLatitude); ?></p>
    <p>Longitude: <?php echo htmlspecialchars($savedLongitude); ?></p>
</div>

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
        const savedLat = <?php echo $savedLatitude; ?>;
        const savedLng = <?php echo $savedLongitude; ?>;
        const map = L.map('map').setView([savedLat, savedLng], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        const marker = L.marker([savedLat, savedLng], { draggable: true }).addTo(map);

        marker.on('moveend', (event) => {
            const { lat, lng } = event.target.getLatLng();
            document.getElementById('latitude').value = lat.toFixed(6);
            document.getElementById('longitude').value = lng.toFixed(6);
        });

        const searchControl = new L.Control.Search({
            url: 'https://nominatim.openstreetmap.org/search?format=json&q={s}',
            jsonpParam: 'json_callback',
            propertyName: 'display_name',
            propertyLoc: ['lat', 'lon'],
            marker: false,
            moveToLocation: function (latlng) {
                map.setView(latlng, 14);
                marker.setLatLng(latlng);
                document.getElementById('latitude').value = latlng.lat.toFixed(6);
                document.getElementById('longitude').value = latlng.lng.toFixed(6);
            },
        });

        map.addControl(searchControl);
    });
</script>

</body>
</html>
