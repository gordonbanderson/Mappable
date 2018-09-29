var options = {
    latitude: $Latitude,
    longitude: $Longitude,
    zoom: $Zoom,
    pitch: $Pitch,
    heading: $Heading,
    domid: '$DomID'
}
console.log('From streetview google template.js');
registerStreetView(options);
