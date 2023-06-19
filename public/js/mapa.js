let map;
let marker;
let geocoder;

function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 12,
        center: { lat: -34.6699, lng: -58.5622 },
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    geocoder = new google.maps.Geocoder();

    marker = new google.maps.Marker({
        position: map.getCenter(),
        map: map,
        draggable: true
    });

    google.maps.event.addListener(marker, 'dragend', function(event) {
        $('#lat').val(event.latLng.lat());
        $('#lng').val(event.latLng.lng());
    });
}

