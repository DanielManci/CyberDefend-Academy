<?php
require_once 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$id = intval($data['id']);
$title = $conn->real_escape_string($data['title']);
$description = $conn->real_escape_string($data['description']);
$video_link = $conn->real_escape_string($data['video_link']);

$sql = "UPDATE topics SET title='$title', short_description='$short_description', description='$description', video_link='$video_link' WHERE id=$id";

if ($conn->query($sql)) {
    echo "Topic updated successfully.";
} else {
    echo "Error: " . $conn->error;
}
?>
