<?php



echo "<form action='/' method='GET'><input name='url' type='text'><button>Search</button></form>";


$url = "";

if (isset($_GET['url'])) {
    $url = $_GET['url'];
}


$site = file_get_contents($url);


preg_match_all("~<h3 class=\"rst-ocb-i-h\"><span>(.*)</span></h3>~iUs", $site, $carMatches);
preg_match_all("~<span class=\"rst-uix-grey\"(.*)</span>~iUs", $site, $priceMatches);
preg_match_all("~<span class=\"rst-ocb-i-d-l-i-s\">(.*)</span>~iUs", $site, $yearMatches);


$years = array_values(array_filter($yearMatches[1], function ($possibleYear) {
    return (strlen($possibleYear) === 4 && is_numeric($possibleYear));
}));

$prices = array_map(function ($dirtyPrice) {

    $parts = explode("<", $dirtyPrice);

    return array_pop($parts);
}, $priceMatches[1]);



$cars = array_map(function ($item) {
    $parts = explode(" ", $item);
    array_shift($parts);

    return implode(" ", $parts);
}, $carMatches[1]);


$count = count($cars);

$data = [];

for ($i = 0; $i < $count; $i++) {
    $data[] = [
        'name' => $cars[$i],
        'year' => $years[$i],
        'price' => $prices[$i]
    ];
}


//print_r($data);


echo "<table border='1'><thead><th>Name</th><th>Price</th><th>Year</th></thead><tbody>";


foreach ($data as $car) {
    echo "<tr>";
    echo "<td>{$car['name']}</td>";
    echo "<td>{$car['price']}</td>";
    echo "<td>{$car['year']}</td>";
    echo "</tr>";
}

echo "</tbody></table>";