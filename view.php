<?php
// File: view.php
// Path: blpc-system/view.php
include 'db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id == 0) {
    die("Invalid QR Code");
}

$result = $conn->query("SELECT * FROM residents WHERE id = $id");
$data = $result->fetch_assoc();

if (!$data) {
    die("Resident not found");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>BLPC Visitor | <?php echo htmlspecialchars($data['name']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes glow {
            0% { box-shadow: 0 0 5px rgba(34,197,94,0.3); }
            100% { box-shadow: 0 0 20px rgba(34,197,94,0.6); }
        }
        .animate-slide-up {
            animation: slideUp 0.5s ease-out;
        }
        .btn-map {
            transition: all 0.3s ease;
            animation: glow 1.5s infinite alternate;
        }
        .btn-map:hover {
            transform: scale(1.02);
            background-color: #16a34a;
        }
        @keyframes pulse-bg {
            0% { background-color: rgba(59,130,246,0.1); }
            100% { background-color: rgba(59,130,246,0.2); }
        }
        .info-card {
            animation: pulse-bg 1s ease-in-out infinite alternate;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-100 to-indigo-200 min-h-screen flex items-center justify-center p-4">

<div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden animate-slide-up">
    <!-- Header with gradient -->
    <div class="bg-gradient-to-r from-green-600 to-emerald-600 p-5 text-center">
        <div class="inline-flex items-center justify-center bg-white rounded-full p-2 mb-3">
            <i class="fas fa-home text-green-600 text-xl"></i>
        </div>
        <h2 class="text-white text-lg font-semibold">BLPC Village Access</h2>
        <p class="text-green-100 text-sm">Authorized Visitor Information</p>
    </div>
    
    <!-- Main Content -->
    <div class="p-6">
        <!-- Resident Name Card -->
        <div class="bg-blue-50 rounded-xl p-4 mb-5 info-card">
            <div class="flex items-center space-x-3">
                <div class="bg-blue-500 rounded-full w-12 h-12 flex items-center justify-center">
                    <i class="fas fa-user text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-blue-600 uppercase tracking-wide">Resident</p>
                    <h1 class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($data['name']); ?></h1>
                </div>
            </div>
        </div>
        
        <!-- Address Card -->
        <div class="bg-gray-50 rounded-xl p-4 mb-6">
            <div class="flex items-start space-x-3">
                <div class="bg-gray-200 rounded-full p-2">
                    <i class="fas fa-location-dot text-gray-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Address</p>
                    <p class="text-gray-700 font-medium"><?php echo htmlspecialchars($data['address']); ?></p>
                    <p class="text-xs text-gray-400 mt-1">Block & Lot • Phase Area</p>
                </div>
            </div>
        </div>
        
        <!-- Map Button -->
        <a href="<?php echo htmlspecialchars($data['map_link']); ?>" target="_blank" 
           class="btn-map block bg-green-500 hover:bg-green-600 text-white text-center py-4 rounded-xl font-bold text-lg transition-all shadow-lg">
            <i class="fas fa-map-marked-alt mr-2"></i>
            Open in Google Maps
            <i class="fas fa-external-link-alt ml-2 text-sm"></i>
        </a>
        
        <!-- Security Note -->
        <div class="mt-6 text-center">
            <div class="inline-flex items-center bg-gray-100 rounded-full px-4 py-2">
                <i class="fas fa-shield-alt text-gray-500 mr-2 text-sm"></i>
                <span class="text-xs text-gray-600">This is a verified BLPC access QR</span>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="bg-gray-100 px-6 py-3 text-center border-t border-gray-200">
        <p class="text-xs text-gray-500">
            <i class="fas fa-clock mr-1"></i> QR Code Valid • BLPC Security System
        </p>
    </div>
</div>

</body>
</html>