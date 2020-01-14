var pageNum=1;
	function loadMore(){
		var t=$('#smarttext').val();
		var c=$('#city_name').val();
		$.get('/result.php?ajax=1&string='+t+'&city='+c+'&page='+pageNum, function(data){
				$('#resultset').append(data);
				pageNum++;
			});
			return false;
	}
	function menu(){
		$('#mobile_menu').slideToggle();
	}
$(function() {
    var cache = {};
	var box=$( "#smarttext" );
	box.autocomplete({
      minLength: 0,
	  autoFocus: true,
      source: function( request, response ) {
        var term = request.term;
        if ( term in cache ) {
          response( cache[ term ] );
          return;
        }							
        $.getJSON( "/suggestion.php?smart=1", request, function( data, status, xhr ) {
          cache[ term ] = data;
          response( data );
        });
      }
    });
	var cacheKeyword={};
	$('#keyword').autocomplete({
      minLength: 0,
	  autoFocus: true,
      source: function( request, response ) {
        var term = request.term;
        if ( term in cacheKeyword ) {
          response( cacheKeyword[ term ] );
          return;
        }							
        $.getJSON( "/suggestion.php?keyword=1", request, function( data, status, xhr ) {
          cacheKeyword[ term ] = data;
          response( data );
        });
      }
    });
	var cacheArea={};
	$('#area').autocomplete({
      minLength: 0,
	  autoFocus: true,
      source: function( request, response ) {
        var term = request.term;
        if ( term in cacheArea ) {
          response( cacheArea[ term ] );
          return;
        }							
        $.getJSON( "/suggestion.php?area=1", request, function( data, status, xhr ) {
          cacheArea[ term ] = data;
          response( data );
        });
      }
    });
	
  });
   function check()
{
var q=$('#smarttext').val();
if(q=='')
{
alert("Enter some Keyword.");
return false;
}
}
 $(function() {
 var cityName;
 $("#city_name_btn").click(function(e){
  e.preventDefault();
  cityName=$("#city_name").val();
  $('#city_name_btn').hide();
 $('#city_name_box').show();
  $('#city_name').focus();
  $('#city_name').select();
   });
 $("#city_name").blur(function(){
 $("#city_name_box").hide();
 $("#city_name_btn").show();
 $(this).val(cityName);
 });
    var cacheCity = {};
	var box=$( "#city_name" );
	box.autocomplete({
      minLength: 0,
	  autoFocus: true,
      source: function( request, response ) {
        var term = request.term;
        if ( term in cacheCity ) {
          response( cacheCity[ term ] );
          return;
        }							
        $.getJSON( "/suggestion.php?citysug=1", request, function( data, status, xhr ) {
          cacheCity[ term ] = data;
          response( data );
        });
      },
	  select: function( event, ui ) {
	  cityName=ui.item.value;
	  $("#city_name").val(ui.item.value);
	  $("#city_button").html(ui.item.value);
	  $("#city_name_box").hide();
	  $("#city_name_btn").show();
	  $("#smarttext").focus();
	  return false;
	  }
    });
	$('#city').autocomplete({
      minLength: 0,
	  autoFocus: true,
      source: function( request, response ) {
        var term = request.term;
        if ( term in cacheCity ) {
          response( cacheCity[ term ] );
          return;
        }							
        $.getJSON( "/suggestion.php?citysug=1", request, function( data, status, xhr ) {
          cacheCity[ term ] = data;
          response( data );
        });
      }
    });
  });
  //Location
  $(function() {
  //getting location
if (navigator.geolocation) {
	navigator.geolocation.getCurrentPosition(initialize, errorFunction);
} 

function errorFunction(){
    console.log("Geocoder failed");
}
//location found

var directionsDisplay;
var directionsService = new google.maps.DirectionsService();
var map;

function initialize(position) {
  directionsDisplay = new google.maps.DirectionsRenderer();
  var city = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
  var mapOptions = {
    zoom:13,
    center: city
  }
  map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
  directionsDisplay.setMap(map);
}

function calcRoute() {
  var start = document.getElementById("start").value;
  var end = document.getElementById("end").value;
  var request = {
    origin:start,
    destination:end,
    travelMode: google.maps.TravelMode.DRIVING
  };
  directionsService.route(request, function(result, status) {
    if (status == google.maps.DirectionsStatus.OK) {
      directionsDisplay.setDirections(result);
    }
  });
}
 });