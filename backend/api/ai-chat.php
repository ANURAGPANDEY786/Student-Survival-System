<?php
header("Content-Type: application/json");

// 🔑 PUT YOUR OPENAI API KEY HERE
$apiKey = "YOUR_OPENAI_API_KEY";

$input = json_decode(file_get_contents("php://input"), true);
$userMsg = $input["message"] ?? "";

// 🧠 YOUR PROJECT CONTEXT (VERY IMPORTANT)
$systemPrompt = "
You are an AI assistant for a project called 'Student Survival System (SSS)'.

This system helps students with:
- Finding PG (hostels) with reviews and complaints
- Food services (mess/tiffin)
- Medical support (nearby hospitals with map)
- Risk analysis (safe or risky PG)
- Emergency help features

Answer clearly like a helpful assistant.
Explain features, working, benefits, and technical parts.
Keep answers simple for students and interview explanation.
";

$data = [
  "model" => "gpt-4o-mini",
  "messages" => [
    ["role" => "system", "content" => $systemPrompt],
    ["role" => "user", "content" => $userMsg]
  ]
];

$ch = curl_init("https://api.openai.com/v1/chat/completions");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "Content-Type: application/json",
  "Authorization: Bearer $apiKey"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
curl_close($ch);

echo $response;