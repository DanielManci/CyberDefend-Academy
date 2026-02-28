<?php
require_once 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$id = intval($data['id']);
$question = $conn->real_escape_string($data['question']);
$option_a = $conn->real_escape_string($data['option_a']);
$option_b = $conn->real_escape_string($data['option_b']);
$option_c = $conn->real_escape_string($data['option_c']);
$option_d = $conn->real_escape_string($data['option_d']);
$correct_answer = $conn->real_escape_string($data['correct_answer']);

$sql = "UPDATE test_questions SET 
  question='$question', option_a='$option_a', option_b='$option_b',
  option_c='$option_c', option_d='$option_d', correct_answer='$correct_answer'
  WHERE id=$id";

echo $conn->query($sql) ? "Test question updated." : "Error: " . $conn->error;
?>
