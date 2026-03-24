<?php
// File: generate_qr.php
// Path: blpc-system/generate_qr.php
include 'db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id == 0) {
    header("Location: index.php");
    exit();
}

// Get resident data
$result = $conn->query("SELECT * FROM residents WHERE id = $id");
$resident = $result->fetch_assoc();
if (!$resident) {
    header("Location: index.php");
    exit();
}

// Use the Google Maps link directly from database
$mapLink = $resident['map_link'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code | <?php echo htmlspecialchars($resident['name']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.9; }
            100% { transform: scale(1); opacity: 1; }
        }
        .animate-bounce-slow {
            animation: bounce 2s ease-in-out infinite;
        }
        .qr-code-img {
            animation: pulse 2s ease-in-out infinite;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-900 to-gray-700 min-h-screen flex items-center justify-center p-4">

<div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden fade-in-up">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-6 text-center">
        <div class="flex justify-center mb-3">
            <i class="fas fa-qrcode text-5xl text-white animate-bounce-slow"></i>
        </div>
        <h1 class="text-white text-xl font-bold">BLPC Gate Pass</h1>
        <p class="text-indigo-200 text-sm">Scan to open location directly</p>
    </div>
    
    <!-- Resident Info -->
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-center space-x-3 mb-4">
            <div class="bg-indigo-100 rounded-full p-2">
                <i class="fas fa-user text-indigo-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Resident Name</p>
                <p class="font-bold text-gray-800 text-lg"><?php echo htmlspecialchars($resident['name']); ?></p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <div class="bg-green-100 rounded-full p-2">
                <i class="fas fa-map-marker-alt text-green-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Address</p>
                <p class="text-gray-700"><?php echo htmlspecialchars($resident['address']); ?></p>
            </div>
        </div>
    </div>
    
    <!-- QR Code - Direct Google Maps Link -->
    <div class="p-6 flex flex-col items-center bg-gray-50">
        <p class="text-sm text-gray-500 mb-3 flex items-center">
            <i class="fas fa-camera mr-2"></i> Scan QR to open Google Maps directly
        </p>
        <div class="bg-white p-4 rounded-xl shadow-lg">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=<?php echo urlencode($mapLink); ?>" 
                 class="qr-code-img w-64 h-64"
                 alt="QR Code">
        </div>
        
        <!-- Destination Info -->
        <div class="mt-5 w-full">
            <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                <div class="flex items-center space-x-2 mb-2">
                    <i class="fas fa-location-dot text-blue-600"></i>
                    <span class="text-xs font-semibold text-blue-800">Destination Location:</span>
                </div>
                <p class="text-xs text-gray-700 break-all font-mono"><?php echo htmlspecialchars($mapLink); ?></p>
            </div>
        </div>
        
        <!-- Manual Link Copy -->
        <div class="mt-4 w-full">
            <div class="bg-gray-100 rounded-lg p-3 flex items-center justify-between">
                <span class="text-xs text-gray-600 truncate flex-1"><?php echo htmlspecialchars($mapLink); ?></span>
                <button onclick="copyToClipboard()" class="ml-2 text-indigo-600 hover:text-indigo-800">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
        </div>
        
        <a href="index.php" class="mt-6 inline-flex items-center text-indigo-600 hover:text-indigo-800">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </a>
    </div>
    
    <!-- Footer Note -->
    <div class="bg-gray-100 p-3 text-center">
        <p class="text-xs text-gray-500">
            <i class="fas fa-map-marked-alt mr-1"></i> QR opens Google Maps • BLPC Security
        </p>
    </div>
</div>

<script>
    function copyToClipboard() {
        const url = "<?php echo htmlspecialchars($mapLink); ?>";
        navigator.clipboard.writeText(url).then(() => {
            // Show temporary notification
            const btn = event.target.closest('button');
            const originalIcon = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i>';
            setTimeout(() => {
                btn.innerHTML = originalIcon;
            }, 1500);
        });
    }
</script>

</body>
</html>