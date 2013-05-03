(function ($) {
  Drupal.macPolloStores = new Object();
  Drupal.macPolloStores.map = null;
  Drupal.macPolloStores.points = new Array();
  Drupal.macPolloStores.winPopup = null;
  Drupal.macPolloStores.geocoder = null;  

  var country;
  var city;
  /*
   */
  Drupal.macPolloStores.addPoint = function (lat, lon, ico, nid) {
    return new google.maps.Marker({
      map: Drupal.macPolloStores.map,
      draggable: false,
      position: new google.maps.LatLng(lat, lon),
      icon: ico,
      nid: nid
    });
  }

  Drupal.macPolloStores.addPoints = function (points) {
    if (points != undefined && points.locality != undefined && points.locality.point != undefined) {
      for (var key in points.locality.point) {
      // SImple test to avoid errors in IE8.
        if (points.locality.point[key]['lat'] != undefined) {
          Drupal.macPolloStores.points[key] = Drupal.macPolloStores.addPoint(points.locality.point[key]['lat'], points.locality.point[key]['lng'], points.locality.icon[key], points.locality.nids[key]);

          google.maps.event.addListener(Drupal.macPolloStores.points[key], 'click', (function(nid, map, winPopup, point) {
            return function () {
              $.ajax({
                url: Drupal.settings.basePath + 'shops/popup/' + point.nid,
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                  try {
                  var popup = $('<div id="macpollo-stores-map-popup" />').html(data);
                    winPopup.setContent(popup[0]);
                    winPopup.open(map , point);
                    Drupal.attachBehaviors($('#macpollo-stores-map-popup'));
                  }catch (e) {
                  }
                }
              });
            }
          })(key, Drupal.macPolloStores.map, Drupal.macPolloStores.winPopup, Drupal.macPolloStores.points[key]));
        }
      }
    }
  }

  Drupal.behaviors.macPolloStores = {
    attach: function (context, settings) {
      $('#mapa-macpollo-stores', context).once('mapa-macpollo-stores', function() {
        Drupal.macPolloStores.winPopup = new google.maps.InfoWindow(); //Popup de informacion
        var myOptions = {
          zoom: 5,
          center: new google.maps.LatLng(4.683822128075886, -74.08053107738493),
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        Drupal.macPolloStores.ico = settings.macPolloStores.ico;

        country = settings.macPolloStores.stores.country;
        city = settings.macPolloStores.stores.city;

        Drupal.macPolloStores.map = new google.maps.Map(this, myOptions);

        Drupal.macPolloStores.addPoints(settings.macPolloStores.points[country]);

        Drupal.macPolloStores.geocoder = new google.maps.Geocoder();

        var json = {
          'region': country.toLowerCase(),
          'address': (city ? city + ",": "") + settings.macPolloStores.stores.country_des
        }

        Drupal.macPolloStores.geocoder.geocode(json, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
            Drupal.macPolloStores.map.setCenter(results[0].geometry.location); 
            if (city)
              Drupal.macPolloStores.map.setZoom(10);                      
          }          
        });
        //Drupal.macPolloStores.addPoints();
      });

      $('.macpollo-stores-see-map').once('macpollo-stores-see-map', function() {
        $(this).click(function (e) {
          e.preventDefault();
          try{
          Drupal.macPolloStores.winPopup.close(); //Cerrar popup abiertos en el mapa

          Drupal.macPolloStores.map.panTo(new google.maps.LatLng($(this).attr('data-lat'), $(this).attr('data-lng')));
          Drupal.macPolloStores.map.setZoom(15);                   
          $('html,body').animate({ 'scrollTop' : $('#zone-content').offset().top}, 2000);
          }catch(e){console.log(e);}         
        });
      });
    }
  }
})(jQuery)
  
