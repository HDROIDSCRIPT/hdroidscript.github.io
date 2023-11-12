<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roblox Avatar Loader</title>
    <style>
        body {
            background-color: #181818;
            color: #ffffff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        h1 {
            color: #61dafb;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px; /* Adjusted margin-top value */
        }

        label {
            font-size: 18px;
            margin-bottom: 10px;
        }

        input,
        select {
            padding: 10px;
            font-size: 16px;
            width: 200px;
            border: 2px solid #61dafb;
            border-radius: 5px;
            margin-bottom: 15px;
            background-color: #2c2c2c;
            color: #ffffff;
        }

        button {
            padding: 10px 20px;
            font-size: 18px;
            background-color: #61dafb;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
        }

        button:hover {
            background-color: #4fa3d1;
        }

        .avatar-container {
            position: relative;
            display: inline-block;
            width: 200px;
            height: 200px;
            margin-bottom: 20px;
        }

        .square-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 8px solid #61dafb;
            border-radius: 15px;
            box-sizing: border-box;
            pointer-events: none;
        }

        img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 15px;
        }

        p {
            font-size: 18px;
        }

        .username-text {
            margin-top: 20px;
            font-size: 20px;
            color: #61dafb;
        }

        .description-text {
            margin-top: 20px;
            font-size: 16px;
            color: #61dafb;
        }
    </style>
</head>

<body>
    <h1>Roblox Avatar Generator</h1>
    <form action="server.php" method="get">
        <label for="userIdInput">Enter Roblox Id:</label>
        <input type="text" name="userid" id="userIdInput" placeholder="ID" value="<?php echo isset($_GET['userid']) ? htmlspecialchars($_GET['userid']) : ''; ?>" required>

        <label for="imageFormat">Select Image Format:</label>
        <select id="imageFormat" name="imageFormat">
            <option value="png" <?php echo ($_GET['imageFormat'] === 'png') ? 'selected' : ''; ?>>PNG</option>
            <option value="jpeg" <?php echo ($_GET['imageFormat'] === 'jpeg') ? 'selected' : ''; ?>>JPEG</option>
        </select>

        <button type="submit">Load Avatar</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $userId = isset($_GET['userid']) ? htmlspecialchars($_GET['userid']) : '';
        $imageFormat = isset($_GET['imageFormat']) ? htmlspecialchars($_GET['imageFormat']) : 'jpeg'; // Change 'png' to 'jpeg'

        function getAvatarUrl($userId, $imageFormat)
        {
            $apiUrl = "https://thumbnails.roblox.com/v1/users/avatar?size=420x420&format={$imageFormat}&userIds={$userId}";

            // Initialize cURL session
            $ch = curl_init();
            // Set cURL options
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // Execute cURL session and get the response
            $response = curl_exec($ch);

            // Check for cURL errors
            if (curl_errno($ch)) {
                echo 'Curl error: ' . curl_error($ch);
                return null;
            }

            // Close cURL session
            curl_close($ch);

            $data = json_decode($response, true);

            if (isset($data['data'][0]['imageUrl'])) {
                return $data['data'][0]['imageUrl'];
            } else {
                return null;
            }
        }

        $avatarUrl = getAvatarUrl($userId, $imageFormat);

        if ($avatarUrl) {
            echo "<div class='avatar-container'>";
            echo "<div class='square-overlay'></div>";
            echo "<img src='{$avatarUrl}' alt='Roblox Avatar'>";
            echo "</div>";
        } else {
            echo "<p>Unable to load the avatar from the user ID, if you entered a correct id try to load the character again, if its not working, select another image format and then select the image format you wanted to use.</p>";
        }
    }
    ?>
</body>

</html>
