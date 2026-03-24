<?php
// File: index.php
// Path: blpc-system/index.php
include 'db.php';

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM residents WHERE id = $delete_id");
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BLPC Gate System | Guard Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .qr-card:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease;
        }
        .btn-hover {
            transition: all 0.3s ease;
        }
        .btn-hover:hover {
            transform: scale(1.02);
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .loading-spinner {
            animation: spin 1s linear infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">

<!-- Header -->
<div class="bg-white shadow-lg sticky top-0 z-10 animate-fade-in">
    <div class="container mx-auto px-6 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <i class="fas fa-qrcode text-3xl text-indigo-600"></i>
                <h1 class="text-2xl font-bold text-gray-800">BLPC Gate System</h1>
            </div>
            <div class="flex items-center space-x-4">
                <i class="fas fa-shield-alt text-gray-500"></i>
                <span class="text-gray-600">Guard Dashboard</span>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-6 py-8">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 animate-fade-in">
        <div class="bg-white rounded-xl shadow-md p-6 flex items-center space-x-4">
            <div class="bg-indigo-100 rounded-full p-3">
                <i class="fas fa-users text-2xl text-indigo-600"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Residents</p>
                <p class="text-2xl font-bold text-gray-800">
                    <?php 
                        $count = $conn->query("SELECT COUNT(*) as total FROM residents")->fetch_assoc();
                        echo $count['total'];
                    ?>
                </p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 flex items-center space-x-4">
            <div class="bg-green-100 rounded-full p-3">
                <i class="fas fa-qrcode text-2xl text-green-600"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">QR Ready</p>
                <p class="text-2xl font-bold text-gray-800">All Residents</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 flex items-center space-x-4">
            <div class="bg-yellow-100 rounded-full p-3">
                <i class="fas fa-map-marker-alt text-2xl text-yellow-600"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Active Gates</p>
                <p class="text-2xl font-bold text-gray-800">Main Gate</p>
            </div>
        </div>
    </div>

    <!-- Add Resident Button & Modal Trigger -->
    <div class="mb-6 flex justify-between items-center animate-fade-in">
        <h2 class="text-xl font-semibold text-gray-800"><i class="fas fa-list mr-2"></i>Residents Directory</h2>
        <button onclick="openAddModal()" class="bg-indigo-600 text-white px-5 py-2 rounded-lg flex items-center space-x-2 btn-hover shadow-md">
            <i class="fas fa-plus"></i>
            <span>Add New Resident</span>
        </button>
    </div>

    <!-- Residents Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden animate-fade-in">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    $residents = $conn->query("SELECT * FROM residents ORDER BY id DESC");
                    while ($row = $residents->fetch_assoc()):
                    ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $row['id']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="bg-indigo-100 rounded-full h-8 w-8 flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-indigo-600 text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($row['name']); ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($row['address']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="generate_qr.php?id=<?php echo $row['id']; ?>" class="text-indigo-600 hover:text-indigo-900 inline-flex items-center space-x-1">
                                <i class="fas fa-qrcode"></i>
                                <span>QR</span>
                            </a>
                            <button onclick="openEditModal(<?php echo $row['id']; ?>, '<?php echo addslashes($row['name']); ?>', '<?php echo addslashes($row['address']); ?>', '<?php echo addslashes($row['map_link']); ?>')" class="text-green-600 hover:text-green-900 ml-3 inline-flex items-center space-x-1">
                                <i class="fas fa-edit"></i>
                                <span>Edit</span>
                            </button>
                            <a href="?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this resident?')" class="text-red-600 hover:text-red-900 ml-3 inline-flex items-center space-x-1">
                                <i class="fas fa-trash"></i>
                                <span>Delete</span>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="residentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white animate-fade-in">
        <div class="flex justify-between items-center mb-4">
            <h3 id="modalTitle" class="text-lg font-bold text-gray-800">Add New Resident</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="residentForm" action="save_resident.php" method="POST">
            <input type="hidden" id="residentId" name="id" value="">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Full Name</label>
                <input type="text" id="residentName" name="name" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Address</label>
                <input type="text" id="residentAddress" name="address" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Google Maps Link</label>
                <div class="flex space-x-2">
                    <input type="url" id="residentMapLink" name="map_link" required class="flex-1 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="https://maps.google.com/?q=latitude,longitude">
                    <button type="button" onclick="getCurrentLocation()" class="bg-green-500 text-white px-3 py-2 rounded-lg hover:bg-green-600 transition flex items-center space-x-1">
                        <i class="fas fa-location-dot"></i>
                        <span class="hidden sm:inline">Auto</span>
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">
                    <i class="fas fa-map-pin mr-1"></i> 
                    Click <strong class="text-green-600">Auto</strong> to use your current location, or manually enter coordinates
                </p>
                <div id="locationStatus" class="text-xs mt-1 hidden"></div>
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg btn-hover font-semibold">
                <i class="fas fa-save mr-2"></i>Save Resident
            </button>
        </form>
    </div>
</div>

<script>
    // Store original form action for edit mode
    let isEditMode = false;
    
    function openAddModal() {
        isEditMode = false;
        document.getElementById('modalTitle').innerText = 'Add New Resident';
        document.getElementById('residentForm').action = 'save_resident.php';
        document.getElementById('residentId').value = '';
        document.getElementById('residentName').value = '';
        document.getElementById('residentAddress').value = '';
        document.getElementById('residentMapLink').value = '';
        document.getElementById('locationStatus').classList.add('hidden');
        document.getElementById('residentModal').classList.remove('hidden');
    }

    function openEditModal(id, name, address, mapLink) {
        isEditMode = true;
        document.getElementById('modalTitle').innerText = 'Edit Resident';
        document.getElementById('residentForm').action = 'update_resident.php';
        document.getElementById('residentId').value = id;
        document.getElementById('residentName').value = name;
        document.getElementById('residentAddress').value = address;
        document.getElementById('residentMapLink').value = mapLink;
        document.getElementById('locationStatus').classList.add('hidden');
        document.getElementById('residentModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('residentModal').classList.add('hidden');
    }

    window.onclick = function(event) {
        let modal = document.getElementById('residentModal');
        if (event.target == modal) {
            modal.classList.add('hidden');
        }
    }
    
    // Auto-location function
    function getCurrentLocation() {
        const statusDiv = document.getElementById('locationStatus');
        const mapLinkInput = document.getElementById('residentMapLink');
        
        // Show loading status
        statusDiv.innerHTML = `
            <div class="flex items-center space-x-2 text-blue-600 mt-2 p-2 bg-blue-50 rounded-lg">
                <i class="fas fa-spinner loading-spinner"></i>
                <span>Getting your current location...</span>
            </div>
        `;
        statusDiv.classList.remove('hidden');
        
        // Check if browser supports geolocation
        if (!navigator.geolocation) {
            statusDiv.innerHTML = `
                <div class="flex items-center space-x-2 text-red-600 mt-2 p-2 bg-red-50 rounded-lg">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Geolocation is not supported by your browser</span>
                </div>
            `;
            return;
        }
        
        // Get current position
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;
                
                // Create Google Maps link with coordinates
                const googleMapsLink = `https://maps.google.com/?q=${latitude},${longitude}`;
                mapLinkInput.value = googleMapsLink;
                
                // Show success message with coordinates
                statusDiv.innerHTML = `
                    <div class="flex items-center space-x-2 text-green-600 mt-2 p-2 bg-green-50 rounded-lg">
                        <i class="fas fa-check-circle"></i>
                        <span>Location captured! (${latitude.toFixed(6)}, ${longitude.toFixed(6)})</span>
                    </div>
                `;
                
                // Auto-hide success message after 3 seconds
                setTimeout(() => {
                    if (statusDiv.classList.contains('hidden') === false) {
                        statusDiv.classList.add('hidden');
                    }
                }, 3000);
            },
            function(error) {
                let errorMessage = "";
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage = "Location permission denied. Please enable location access.";
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage = "Location information unavailable.";
                        break;
                    case error.TIMEOUT:
                        errorMessage = "Location request timed out.";
                        break;
                    default:
                        errorMessage = "An unknown error occurred.";
                        break;
                }
                statusDiv.innerHTML = `
                    <div class="flex items-center space-x-2 text-red-600 mt-2 p-2 bg-red-50 rounded-lg">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>${errorMessage}</span>
                    </div>
                `;
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    }
    
    // Optional: Add address reverse geocoding (get address from coordinates)
    // This requires Google Maps Geocoding API, but for simplicity we just get coordinates
    // You can also add manual coordinate input helper
    function showCoordinateHelper() {
        const mapLink = document.getElementById('residentMapLink').value;
        if (mapLink && mapLink.includes('q=')) {
            const coords = mapLink.split('q=')[1].split('&')[0];
            if (coords.includes(',')) {
                const [lat, lng] = coords.split(',');
                alert(`Latitude: ${lat}\nLongitude: ${lng}\n\nThis location will be used for Google Maps navigation.`);
            }
        }
    }
</script>

</body>
</html>