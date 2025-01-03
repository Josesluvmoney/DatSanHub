<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];


    $sql = "INSERT INTO contact_messages (name, email, phone, subject, message) 
            VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $email, $phone, $subject, $message);
    
    if ($stmt->execute()) {

        $googleFormUrl = 'https://docs.google.com/forms/d/e/1FAIpQLSflP3Wi6DakI-rEB9vVKkYSMmfVg3TbDCCzbst6UpEZvhkDdA/formResponse';
        
        $postData = array(
            'entry.862739061' => $name,
            'entry.1479223327' => $email,
            'entry.989700705' => $phone,
            'entry.2023827924' => $subject,
            'entry.134204988' => $message
        );

        // Tạo context cho POST request
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($postData)
            )
        );

        $context  = stream_context_create($options);
        $result = file_get_contents($googleFormUrl, false, $context);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    
    $stmt->close();
    $conn->close();
}
?> 