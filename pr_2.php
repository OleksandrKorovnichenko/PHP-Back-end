<?php

$apiKey = "";
$cx = "";

$items = [];

if (isset($_GET['search']) && !empty($_GET['search'])) {

    $search = urlencode($_GET['search']);

    $url = "https://www.googleapis.com/customsearch/v1?key={$apiKey}&cx={$cx}&q={$search}";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resultJson = curl_exec($ch);

    if ($resultJson === false) {
        echo "cURL Error: " . curl_error($ch);
    }

    curl_close($ch);

    $resultArray = json_decode($resultJson, true);

    if (isset($resultArray['items'])) {
        $items = $resultArray['items'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Browser</title>
</head>
<body>

<h2>My Browser</h2>

<form method="GET" action="/index.php">
    <label for="search">Search:</label>
    <input type="text" id="search" name="search"
           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <br><br>
    <input type="submit" value="Submit">
</form>

<?php


if (!empty($items)) {
    echo "<h3>Results:</h3>";

    foreach ($items as $item) {
        echo "<div style='margin-bottom: 20px;'>";
        echo "<a href='" . htmlspecialchars($item['link']) . "' target='_blank'>";
        echo "<strong>" . htmlspecialchars($item['title']) . "</strong>";
        echo "</a><br>";
        echo "<span>" . htmlspecialchars($item['snippet']) . "</span>";
        echo "</div>";
    }
} elseif (isset($_GET['search'])) {
    echo "<p>No results found</p>";
}
?>

</body>
</html>
