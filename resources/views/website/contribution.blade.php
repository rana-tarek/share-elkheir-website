<!DOCTYPE html>
<html>
  <head>
    <title>Akram</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{URL::to('assets/css/main.css')}}">
  </head>
  <body>
      <nav>
        <div class="row">
            <div class="col-md-9">
                <img src="{{URL::to('assets/images/Akram05.png')}}" width="53" style="margin-top: -20px;">
                <h1 class="title" style="float: none!important;">أكرم</h1>
                <span class="slogan">خليك اكرم مع فودافون</span>
            </div>
            <div class="col-md-3">
                <ul class="links">
                    <li>
                        <a href="#" class="google"></a>
                    </li>
                    <li>
                        <a href="#" class="apple"></a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
    <div class="sub-nav">
        <div class="row">
            <div class="col-md-8 col-sm-6">
                <ul class="labels" id="contribution">
                    <li>
                        <img src="{{URL::to('assets/')}}/img/bag-icon.png" />
                        <span id="food_box">0 شنطة رمضان</span>
                    </li>
                    <li>
                        <img src="{{URL::to('assets/')}}/img/meal-icon.png" />
                        <span id="hot_meal">0 وجبة ساخنة</span>
                    </li>
                </ul>
                <ul class="labels" id="access">
                    <li>
                        <img src="{{URL::to('assets/')}}/img/easy-icon.png" />
                        <span id="easy_access">0 سهل</span>
                    </li>
                    <li>
                        <img src="{{URL::to('assets/')}}/img/normal-icon.png" />
                        <span id="normal_access">0 عادی</span>
                    </li>
                    <li>
                        <img src="{{URL::to('assets/')}}/img/difficult-icon.png" />
                        <span id="difficult_access">0 صعب</span>
                    </li>
                </ul>
            </div>
            <div class="col-md-1 col-md-offset-1 col-sm-2">التبرعات</div>
            <div class="col-md-1 col-sm-1">
                <div class="onoffswitch">
                    <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch">
                    <label class="onoffswitch-label" for="myonoffswitch"></label>
                </div>
            </div>
            <div class="col-md-1">امكانية الوصول</div>
        </div>

    </div>
    <span class="powerd"></span>
    <div class="fill">
        <div id="map-canvas"></div>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-md-3 col-sm-3">
                        <img src="" id="profile_img" class="circletag">
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <h3 id="name">dcffdc</h3>
                        <p class="time" id="time">2 hours ago</p>
                        <p class="area" id="area">Imbaba, Giza</p>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <img id="icon" src="{{URL::to('assets/')}}/images/icon.png" width="50" class="circletag">
                    </div>
                </div>
                <div class="modal-body">
                    <p class="label-bar">الكمية</p>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 50%;background-color: #00B7E3" id="quantity">
                        </div>
                    </div>
                    <p class="label-bar">امكانية الوصول</p>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 100%;background-color: rgb(69,168,87)" id="access">
                        </div>
                    </div>
                    <p style="color: rgb(69,168,87);text-align: center" id="covered">The area is covered</p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="{{URL::to('assets/js/pretty.js')}}"></script>
    <script>


      var markers = [];
      var circles = [];
      var type = 'contribution';
      var map;
      var hot_meal_count = 0;
      var food_box_count = 0;
      var easy_count = 0;
      var normal_count = 0; 
      var difficult_count = 0; 

      function initMap() {

        map = new google.maps.Map(document.getElementById('map-canvas'), {
          center: {lat: 30.0657982, lng: 31.2111215},
          zoom: 15
        });

        map.addListener('click', function(e) {
                map.setCenter(e.latLng);
                getContributions(map, markers, type);
              });
        
        map.addListener('dragend', function() {
            getContributions(map, markers, type);
          });

        map.addListener('zoom_changed', function() {
            getContributions(map, markers, type);
          });

        // Try HTML5 geolocation.
        map.addListener('idle', function() {
            if (navigator.geolocation) {
              navigator.geolocation.getCurrentPosition(function(position) {
                var pos = {
                  lat: position.coords.latitude,
                  lng: position.coords.longitude
                };

                map.setCenter(pos);
                getContributions(map, markers, type);                
                
              }, function(error) {
                    getContributions(map, markers, type);
              });
            } else {
              // Browser doesn't support Geolocation
                getContributions(map, markers, type);
            }
        });
      }

      function getContributions(map, markers, type)
      {
        var infowindow = new google.maps.InfoWindow();
        hot_meal_count = 0;
        food_box_count = 0; 
        easy_count = 0;
        normal_count = 0; 
        difficult_count = 0;
        for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(null);
                circles[i].setMap(null);
            }
            markers.length = 0;
            circles.length = 0;


        var lat1 = map.getBounds().getSouthWest().lat();
        var lng1 = map.getBounds().getSouthWest().lng();
        var lat2 = map.getBounds().getNorthEast().lat();
        var lng2 = map.getBounds().getNorthEast().lng();
        var zoom =  map.getZoom();
        
        $.ajax({
              url: 'get-contributions',
              type: "post",
              data: {'lat1':lat1, 'lng1':lng1, 'lat2':lat2, 'lng2':lng2, 'zoom_level':zoom, '_token': $('input[name=_token]').val()},
              success: function(data){
                var contributions = data.contributions;
                if(contributions){
                    contributions.forEach(function(contribution) {
                        var color,size, icon;
                        
                        if(type == 'contribution'){
                            if(contribution.type_of_contribution == 'hot_meals')
                            {
                                color = '#00B7E3';
                                icon = {
                                    url: "{{URL::to('assets/img/meal.png')}}", // url
                                    scaledSize: new google.maps.Size(35, 45), // scaled size

                                };
                                hot_meal_count += 1;
                            }
                            else if(contribution.type_of_contribution == 'food_boxes')
                            {
                                color = '#25B337';
                                icon = {
                                    url: "{{URL::to('assets/img/bag.png')}}", // url
                                    scaledSize: new google.maps.Size(35, 45), // scaled size
                                };
                                food_box_count += 1; 
                            }

                            if(contribution.quantity == 'small')
                                size = 50;
                            else if(contribution.quantity == 'medium')
                                size = 100;
                            else if(contribution.quantity == 'large')
                                size = 200;
                        }
                        else
                        {
                            if(contribution.access == 'easy')
                            {
                                color = 'rgb(69,168,87)';
                                icon = {
                                    url: "{{URL::to('assets/img/easy.png')}}", // url
                                    scaledSize: new google.maps.Size(10, 10), // scaled size

                                };
                                easy_count +=1;
                            }
                            else if(contribution.access == 'normal')
                            {
                                color = 'rgb(255, 203, 5)';
                                icon = {
                                    url: "{{URL::to('assets/img/normal.png')}}", // url
                                    scaledSize: new google.maps.Size(10, 10), // scaled size
                                };
                                normal_count +=1;
                            }
                            else
                            {
                                color = 'rgb(149,0,20)';
                                icon = {
                                    url: "{{URL::to('assets/img/difficult.png')}}", // url
                                    scaledSize: new google.maps.Size(10, 10), // scaled size
                                };
                                difficult_count +=1;
                            }

                            size = 200;
                        }
                      // Create marker 
                      var marker = new google.maps.Marker({
                        map: map,
                        position: new google.maps.LatLng(contribution.latitude, contribution.longitude),
                        icon: icon,
                        user_name: contribution.user.name,
                        user_image: contribution.user.image,
                        area: contribution.area,
                        type: contribution.type_of_contribution,
                        quantity: contribution.quantity,
                        access: contribution.access,
                        covered: contribution.covered,
                        date: prettyDate(contribution.created_at)
                      });

                      // Add circle overlay and bind to marker
                      var circle = new google.maps.Circle({
                        map: map,
                        radius: size,
                        fillColor: color,
                        strokeColor: '#FFFFFF',
                        strokeOpacity: 1,
                        strokeWeight: 0

                      });
                        circle.bindTo('center', marker, 'position');
                        markers.push(marker);
                        circles.push(circle);
                        makeInfoWindowEvent(map, infowindow, marker);
                        
                    });
                }
                if(type == 'contribution'){
                    $('#hot_meal').text(hot_meal_count + ' وجبة ساخنة');
                    $('#food_box').text(food_box_count + ' شنطة رمضان');
                }
                else
                {
                    $('#easy_access').text(easy_count + ' سهل');
                    $('#normal_access').text(normal_count + ' عادی');
                    $('#difficult_access').text(difficult_count + ' صعب');
                }
              }
            }); 

      }


      function makeInfoWindowEvent(map, infowindow, marker) {
        google.maps.event.addListener(marker, 'click', function() {
            $('#profile_img').attr('src',marker.user_image);
            if(marker.type == 'hot_meals')
            {
                $('#icon').attr('src', "{{URL::to('assets/img/meal-icon.png')}}");
                $('#quantity').css("background-color", "#00B7E3");
            }
            else if(marker.type == 'food_boxes')
            {
                $('#icon').attr('src', "{{URL::to('assets/img/bag-icon.png')}}");
                $('#quantity').css("background-color", "#25B337");
            }
            var name = marker.user_name.split(' ')[0];
            $('#name').text(name);
            $('#time').text(marker.date);
            $('#area').text(marker.area);
            if(marker.quantity == 'small')
                $('#quantity').css("width", "25%");
            else if(marker.quantity == 'medium')
                $('#quantity').css("width", "50%");
            else if(marker.quantity == 'large')
                $('#quantity').css("width", "100%");

            if(marker.access == 'easy')
            {
                $('#access').css("background-color", "rgb(69,168,87)");
                $('#access').css("width", "100%");
            }
            else if(marker.access == 'normal')
            {
                $('#access').css("background-color", "rgb(255, 202, 4)");
                $('#access').css("width", "50%");
            }
            else if(marker.access == 'difficult')
            {
                $('#access').css("background-color", "rgb(207,0,27)");
                $('#access').css("width", "25%");
            }

            if(marker.covered == 1)
                $('#covered').text('المنطقة لا تحتاج المزيد من المساعدة');
            else
            {
                $('#covered').text('المنطقة تحتاج المزيد من المساعدة');
                $('#covered').css("color", "rgb(206,0,0");
            }
            $('#myModal').modal('show');
        });
      }

       $(document).ready(function() {
            $("#myonoffswitch").click(function() {
                var chkbox = document.getElementById("myonoffswitch");
                if(chkbox.checked)
                {
                    type = 'access';
                    getContributions(map, markers, type);
                    $('#access').css("display", "block");
                    $('#contribution').css("display", "none");
                }
                else
                {
                    type = 'contribution';
                    getContributions(map, markers, type);
                    $('#access').css("display", "none");
                    $('#contribution').css("display", "block");
                }
            });                 
        });
      
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCq9t3oh8EaHrpe0jOIIX81foKPSUA4mbI&callback=initMap">
    </script>
  </body>
</html>