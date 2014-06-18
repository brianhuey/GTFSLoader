var basezoom= 12;  
var center = new L.LatLng(37.80467, -122.295);
  
// Create leaflet map
var map = new L.Map("map", {center: center, zoom: basezoom});

// Stock Stamen Tile Layer code
var baseUrl = 'http://{s}.tile.stamen.com/toner-lite/{z}/{x}/{y}.png';
var baseAttribution = 'Map tiles by <a href="http://stamen.com/">Stamen Design</a>, <a href="http://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> and contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/" target="_blank">CC-BY-SA</a>';
var subdomains = 'abcd';
var stamenUrl = 'http://{s}.tile.stamen.com/toner-lite/{z}/{x}/{y}.png';  
var stamenLayer = new L.TileLayer(stamenUrl, 
    {subdomains: ["a","b","c","d"], maxZoom: 18, attribution: baseAttribution});
map.addLayer(stamenLayer);

var routesUrl = 'http://api.availabs.org/gtfs/agency/1/routes';

d3.json(routesUrl,function(error, json) {
  if (error) return console.warn(error);

var routes = topojson.feature(json,json.objects.routes);
L.geoJson(routes, {
  style: function(feature) {
      if(feature.properties.route_color == null) 
        return {color: "black", weight: 2};
       else 
    return {color: "#" + feature.properties.route_color, weight: 2};
  },
  onEachFeature: function(feature, layer) {
    layer.bindPopup(feature.properties.route_long_name);
  }
}
  ).addTo(map);
console.log(routes);
});