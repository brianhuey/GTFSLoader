<?php
header("Access-Control-Allow-Origin: *");
$link = mysqli_connect(ini_get("mysqli.default_host"),ini_get("mysqli.default_user"),ini_get("mysqli.default_pw"), 'GTFS');

if (!$link) {
    die('Connect Error (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
}


$results = mysqli_query($link, 'SELECT * FROM get_shapes');
// $data = mysqli_fetch_array($results);
$output = array("type" => "FeatureCollection");
$currentCompany = "";

while($row = mysqli_fetch_array($results)) {
  if ($row["route_id"] != $currentRoute) {
  	// First level
    $output["features"][] = array();

    // get a reference to the newly added array element
    end($output["features"]);
    // Second Level
    $currentRoute = $row["route_id"];
    $currentItem = & $output["features"][key($output["features"])];
    $currentItem['type'] = "Feature";
    $currentItem['geometry'] = array("type" => "MultiLineString", "coordinates" => array());
    $currentItem['id'] = key($output["features"]);
	$currentItem['properties'] = array("route_id" => $row["route_id"], "route_color" => $row["rt_col"], "agency_name" => $row["agency_name"]);
 
    }

			$currentItem['geometry']['coordinates'][0][] = array(floatval($row["sh_lon"]), floatval($row["sh_lat"]));

	}


// foreach ($data as $datum) {
//   if ($datum["agency_name"] != $currentCompany) {
//     $output[] = array();
// 
//     // get a reference to the newly added array element
//     end($output);
//     $currentItem = & $output[key($output)];
// 
//     $currentCompany = $datum["agency_name"];
//     $currentItem['agency_name'] = $currentCompany;
//     $currentItem['geometry'] = array();
//   }
//   $currentItem['geometry'][] = array($datum["sh_lon"], $datum["sh_lat"]);
// }
echo json_encode($output);
mysqli_close($link);
?>
