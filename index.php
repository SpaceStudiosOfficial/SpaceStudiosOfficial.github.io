<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = 'images/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $uploadedFile = $_FILES['fileInput'];
    $extension = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);
    $newFileName = uniqid('image_') . '.' . $extension;
    $targetFilePath = $uploadDir . $newFileName;

    if (move_uploaded_file($uploadedFile['tmp_name'], $targetFilePath)) {
        $response = ['imageUrl' => 'https://vaultune-image-ai-panel.replit.dev/' . $newFileName];
        echo json_encode($response);
    } else {
        $response = ['error' => 'Failed to upload the image.'];
        echo json_encode($response);
    }
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Generator</title>
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f2f2f2;
        }

        button {
            padding: 10px 20px;
            margin: 5px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            background-color: #3498db;
            color: #fff;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #2980b9;
        }

        input[type="file"] {
            display: none;
        }

        img {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
            border-radius: 5px;
        }

        form {
            margin-top: 10px;
        }

        @media (max-width: 600px) {
            button {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <form id="customMessageForm">
        <input type="text" id="customMessage" placeholder="Enter Custom Message">
        <button type="button" onclick="sendCustomMessage()">Send Custom Message</button>
    </form>
    <button onclick="sendAlert()">Alert</button>
    <button onclick="selectImage()">Select Image</button>
    <button onclick="sendImage()">Send Image</button>

    <br>

    <form id="imageForm" enctype="multipart/form-data">
        <input type="file" name="fileInput" id="fileInput" onchange="handleFileSelect(event)" style="display: none;" />
    </form>

    <br>
    <img style="max-width: 200px; height: auto;" id="imagePreview" src="" alt="Image Preview">

    <script>
        function sendAlert() {
            const webhookUrl = "https://discord.com/api/webhooks/1210738712661590026/RQ3IlAllPU3XSmyiM7GySyIPtpPnu2vB3A9I3_ElyHkh0R0FkV-LimusIKk-pbntjz51";
            const message = "**Your Image Is Being Generated…**";

            const request = new XMLHttpRequest();
            request.open("POST", webhookUrl);
            request.setRequestHeader('Content-type', 'application/json');

            const myEmbed = {
                author: {
                    name: ""
                },
                title: "",
                description: message,
                color: hexToDecimal("#000080")
            };

            const params = {
                username: "Vaultune AI",
                embeds: [myEmbed]
            };

            request.send(JSON.stringify(params));
        }

        function sendImage() {
            const webhookUrl = "https://discord.com/api/webhooks/1210738712661590026/RQ3IlAllPU3XSmyiM7GySyIPtpPnu2vB3A9I3_ElyHkh0R0FkV-LimusIKk-pbntjz51";

            const request = new XMLHttpRequest();
            request.open("POST", webhookUrl);
            request.setRequestHeader('Content-type', 'application/json');

            const formData = new FormData(document.getElementById('imageForm'));

            fetch('index.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    const imageUrl = data.imageUrl;

                    const myEmbed = {
                        author: {
                            name: ""
                        },
                        title: "",
                        image: {
                            url: imageUrl
                        },
                        color: hexToDecimal("#000080")
                    };

                    const params = {
                        username: "Vaultune AI",
                        embeds: [myEmbed]
                    };

                    request.send(JSON.stringify(params));
                });
        }

        function selectImage() {
            document.getElementById('fileInput').click();
        }

        function previewImage(file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const imagePreview = document.getElementById('imagePreview');
                imagePreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        function handleFileSelect(event) {
            const file = event.target.files[0];
            previewImage(file);
        }

        function hexToDecimal(hex) {
            return parseInt(hex.replace("#", ""), 16);
        }

        function sendCustomMessage() {
            const webhookUrl = "https://discord.com/api/webhooks/1210738712661590026/RQ3IlAllPU3XSmyiM7GySyIPtpPnu2vB3A9I3_ElyHkh0R0FkV-LimusIKk-pbntjz51";
            const customMessage = document.getElementById('customMessage').value;

            const request = new XMLHttpRequest();
            request.open("POST", webhookUrl);
            request.setRequestHeader('Content-type', 'application/json');

            const myEmbed = {
                author: {
                    name: ""
                },
                title: "",
                description: customMessage,
                color: hexToDecimal("#990000")
            };

            const params = {
                username: "Vaultune AI",
                embeds: [myEmbed]
            };

            request.send(JSON.stringify(params));
        }
    </script>
</body>

</html>
