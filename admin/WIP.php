<?php

$imagePath = "../plugins/images/WIP.jpg";

if (!file_exists($imagePath)) {
    die("The image does not exist.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work in Progress</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        img {
            max-width: 100%;
            max-height: 100%;
            display: block;
        }
    </style>
</head>
<body>
    <img src="<?php echo $imagePath; ?>" alt="Work in Progress">
</body>
</html>
