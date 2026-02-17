<?php
header('Content-Type: application/json');

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['image']) || !isset($data['wish'])) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
    exit;
}

$image_data = $data['image'];
$wish = htmlspecialchars($data['wish']);

// Clean up base64 image data
$image_parts = explode(";base64,", $image_data);
$image_type_aux = explode("image/", $image_parts[0]);
$image_type = $image_type_aux[1];
$image_base64 = base64_decode($image_parts[1]);

// Create unique filename
$file_id = uniqid();
$filename = $file_id . '.jpg';
$file_path = '../uploads/' . $filename;

// Save image to folder
if (file_put_contents($file_path, $image_base64)) {
    
    // Save wish to a JSON file (simplified database)
    $wishes_file = 'wishes.json';
    $current_wishes = [];
    
    if (file_exists($wishes_file)) {
        $current_wishes = json_decode(file_get_contents($wishes_file), true);
    }
    
    $current_wishes[] = [
        'id' => $file_id,
        'image' => $filename,
        'wish' => $wish,
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    file_put_contents($wishes_file, json_encode($current_wishes, JSON_PRETTY_PRINT));

    echo json_encode(['success' => true, 'message' => 'Kenangan berjaya disimpan!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal simpan gambar.']);
}
?>
