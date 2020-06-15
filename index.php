
<html lang="pt-br">
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
 <script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <meta charset="utf-8" >
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.7/css/select.bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <title>hackathon ccr </title>
  </head>
<?php
  if(!isset($_GET['destino'])){
    if(!isset($_GET['lat']) OR !isset($_GET['long'])){
      $lat = -28.734270191728744;
      $long = -15.441318564568661;
      $criar_rota = 0;
      $destino_voz = '""';
    }else{
      $criar_rota = 0;
      $lat = $_GET['lat'];
      $long = $_GET['long'];
      $destino_voz = '""';
    }
    echo '<script>var lat_user = ' . $lat . '; var long_user = ' .  $long. ';' . ' var criar_rota = ' .  $criar_rota. ';'. ' var destino_voz = ' .  $destino_voz. ';</script>';
  }else{
    if(!isset($_GET['lat']) OR !isset($_GET['long'])){
      $criar_rota = 0;
      $lat = -28.734270191728744;
      $long = -15.441318564568661;
      $destino_voz = '""';
    }else{
      $criar_rota = 1;
      $lat = $_GET['lat'];
      $long = $_GET['long'];
      $destino_voz = $_GET['destino'];
    }
    echo '<script>var lat_user = ' . $lat . '; var long_user = ' .  $long. ';' . ' var criar_rota = ' .  $criar_rota. ';'. ' var destino_voz = ' .  $destino_voz. ';</script>';
  }
?>

  <body>
    <style>
      #map {
        border: 2px solid green;
        border-radius: 1px;
        height: 100%;
      }
         /* HIDE RADIO */
      [type=radio] { 
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
      }

      /* IMAGE STYLES */
      [type=radio] + img {
        cursor: pointer;
      }

      /* CHECKED STYLES */
      [type=radio]:checked + img {
        outline: 2px solid blue;
      }
    </style>



  <!-- Modal -->
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <label>Origem</label>
          <input class="form-control" id="origem" name="origem" type="text" placeholder="Digite a origem..">
          <label>Destino</label>
          <input class="form-control" id="destino" name="destino" type="text" placeholder="Digite o destino..">
          <input class="form-control" id="waypoints" name="waypoints" type="hidden">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">FECHAR</button>
          <button type="button" class="btn btn-primary" onclick="calcularRota()" >PROCURAR ROTA</button>
        </div>
      </div>
    </div>
  </div>


    <style>
      .button_class{
        border-radius: 5px;
        padding-top: 8px;
        padding-bottom: 8px;
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.15);
        background-color: #604acb;
        font-family: OpenSans;
        font-size: 16px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: -0.32px;
        text-align: center;
        color: #ffffff;
      }

      .avaliao-salva-successo {
        font-family: OpenSans;
        font-size: 20px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        line-height: 0.72;
        letter-spacing: -0.36px;
        text-align: center;
        color: #000000;
      }
  </style>
  <!-- Modal -->
  <div class="modal fade" id="avaliacao" tabindex="-1" role="dialog" aria-labelledby="avaliacaoCenterTitle" aria-hidden="false" style="margin-top: 100px;margin-left: 40px;margin-right: 40px">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header"  style="  background-color: #f9f9f9;" id="header_avaliacao">
          <h5 class="modal-title"><center><b id="name_parada"> </b><br>Avaliação parada</center></h5>
        </div>
        <div  class="modal-body"  id="content_add_avaliacao">

        </div>

      </div>
    </div>
  </div>




    <div id="map" ></div>
  <script src="menus.js"></script>
  <script src="funcoes.js"></script>

	<script>

  var map, infoWindow;
  var trafficLayer;
  var trafficHabilitado = false;
  var directionsService;
  var directionsDisplay;
  var marker_paradas = [];



function info_paradas(props){
  var marker = new google.maps.Marker({position:props.coords,map:map});


  marker.setIcon('icones/marker_location.png');
  if(props.content){
    var infoWindow = new google.maps.InfoWindow({
    content:props.content
    });
    marker.addListener('click', function(){
    infoWindow.open(map, marker);
    });
  }

  marker_paradas.push(marker);
}





  function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    infoWindow.setPosition(pos);
    infoWindow.setContent(browserHasGeolocation ?
                          'Error: The Geolocation service failed.' :
                          'Error: Your browser doesn\'t support geolocation.');
    infoWindow.open(map);
  }

  var avaliacao_array = [];
  function inserirAvaliacao(){
    var dadosajax = {
        "avaliacoes" : avaliacao_array
    };
      $.ajax({
        url: 'insertAvaliacao.php',
        data: dadosajax,
        type: 'POST',
        cache: false,
        error: function(result){
          console.log('Erro ao selecionar registros..' + result);
        },
        success: function(result)
        { 
      document.getElementById("header_avaliacao").hidden = true;
          document.getElementById("content_add_avaliacao").innerHTML = '<div class="avaliao-salva-successo">'+
            '<center>'+
             ' <br><br>Avaliação salva com sucesso.<br><br>'+
            '  Seus companheiros agradecem!<br><br><img src="icones/path.png" style="margin-top: 5px;margin-bottom: 5px;"><br><br>'+
           '</center>'+
           ' <button type="button" class="btn btn-primary btn-block button_class"  data-dismiss="modal" >okay</button>'+
          '</div>';
          ;
        } 
    });
  }

  function iniciarAvaliacao(id_local,nome){
      document.getElementById("content_add_avaliacao").innerHTML = '';
      document.getElementById("header_avaliacao").hidden = false;
      document.getElementById("name_parada").innerHTML = nome;
      avaliacao_array = [];
    var opcoes_avaliacao = '';

      var dadosajax = {
        "local" : id_local
      };
      $.ajax({
        url: 'getServicoLocal.php',
        data: dadosajax,
        type: 'GET',
        cache: false,
        error: function(result){
          console.log('Erro ao selecionar registros..' + result);
        },
        success: function(result)
        { 
          for(var i = 0; i < result.length;i++){
            avaliacao_array[i] = {"categoria" : result[i].id , "estrelas" : 0, "id_local" : id_local};
              opcoes_avaliacao += '<div style="text-align: center;"><img src="icones/'+result[i].icon +'">'+
                '<a href="javascript:void(0)"  onclick="Avaliar(1,' +result[i].id+ ',' + i + ')">'+
                    '<img src="icones/star0.png" style="padding-right: 5px;" width="35px" height="30px" id="s1_'+result[i].id+'"></a>'+
               '<a href="javascript:void(0)" onclick="Avaliar(2,' +result[i].id+ ',' + i + ')">'+
                    '<img src="icones/star0.png"  style="padding-right: 5px;"width="35px" height="30px"  id="s2_'+result[i].id+'"></a>'+
                '<a href="javascript:void(0)" onclick="Avaliar(3,' +result[i].id+ ',' + i + ')">'+
                    '<img src="icones/star0.png"  style="padding-right: 5px;" width="35px" height="30px" id="s3_'+result[i].id+'"></a>'+
                '<a href="javascript:void(0)" onclick="Avaliar(4,' +result[i].id+ ',' + i + ')">'+
               '     <img src="icones/star0.png"  style="padding-right: 5px;" width="35px" height="30px" id="s4_'+result[i].id+'"></a>'+
              '  <a href="javascript:void(0)" onclick="Avaliar(5,' +result[i].id+ ',' + i + ')">'+
             '       <img src="icones/star0.png"  style="padding-right: 5px;" width="35px" height="30px" id="s5_'+result[i].id+'"></a>'+
            '</div><br>';
          }
          document.getElementById("content_add_avaliacao").innerHTML = opcoes_avaliacao + '<button type="button" class="btn btn-primary btn-block button_class" onclick="inserirAvaliacao()" >enviar</button>';
        } 
    });

  }

  function clearParadas(){
    for(var i =0; i < marker_paradas.length;i++)
      marker_paradas[i].setMap(null);
    
    marker_paradas = [];
  }

  function getLocais(){
    clearParadas();
    var dadosajax = {
      'latitude' : lat_user,
      'longitude' : long_user,
      'limite_km' : 80
    };
    $.ajax({
      url: 'getLocais.php',
      data: dadosajax,
      type: 'GET',
      cache: false,
      error: function(result){
        console.log('Erro ao selecionar registros..' + result);
      },
      success: function(result)
      { 
        for(var i = 0; i < result.length;i++){
          var avaliacao = 'Média avaliação<br><br>';
          var id_local = result[i].id;
          for(var y = 0;y < (result[i].avaliacao).length;y++){
              var objeto = result[i].avaliacao[y];
              avaliacao += ''+
              '<img src="icones/'+ objeto.icon +'"><a style="margin-left: 6px;font-size: 14px;background-color: #343e78" class="badge"  ><span  class="glyphicon glyphicon-star"> </span> '  + objeto.nota + '</a><hr style="margin-top: 9px;margin-bottom: 9px;">';
          }
          var content_string = '<div class="infos" ><font size="3"color="#660000"><b>' + result[i].nome +   '</b></font><hr></font><b> ' + avaliacao + ' <br><button class="btn btn-block button_class"  data-toggle="modal" data-target="#avaliacao" onClick="iniciarAvaliacao(' + id_local +',' + "'" + result[i].nome+ "'" + ')">avaliar</button>'+ '</div>';

         var infowindow = new google.maps.InfoWindow({
          content: content_string
        });
          var myLatlng = new google.maps.LatLng(result[i].lat,result[i].long);
          var marker = new google.maps.Marker({
              position: myLatlng,
              title: result[i].nome
          });
          marker.setMap(map);
          marker.addListener('click', function() {
           infowindow.open(map, marker);
          });
          marker_paradas.push(marker);
        }
        console.log(result);
      }
    });
  }


  function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
      zoom: 6,
      streetViewControl: false,
             mapTypeControl: false,
      center: {lat:-16.964213, lng: -52.415342}
    });

    trafficLayer = new google.maps.TrafficLayer();
    var configuracoesLinha = new google.maps.Polyline({
      strokeColor: '#604acb'
    });
    directionsService = new google.maps.DirectionsService;
    directionsDisplay = new google.maps.DirectionsRenderer({
      draggable: true,
      map: map,
      polylineOptions: configuracoesLinha
    });
    var pos = {
      lat: lat_user,
      lng: long_user
    };
    var myLatlng = new google.maps.LatLng(lat_user,long_user);
    var marker = new google.maps.Marker({
        position: myLatlng,
        title:"Você está aqui!",
        icon:"truck.png"
    });
    marker.setMap(map);
    map.setCenter(pos);
    map.setZoom(5);

    // if (navigator.geolocation) {
    //   navigator.geolocation.getCurrentPosition(function(position) {
    //     var pos = {
    //       lat: position.coords.latitude,
    //       lng: position.coords.longitude
    //     };
    //     lat_user = pos.lat;
    //     long_user = pos.lng;
    //     var myLatlng = new google.maps.LatLng(pos.lat,pos.lng);
    //     var marker = new google.maps.Marker({
    //         position: myLatlng,
    //         title:"Você está aqui!",
    //         icon:"truck.png"
    //     });
    //     marker.setMap(map);
    //     map.setCenter(pos);
    //     map.setZoom(5);
    //   }, function() {
    //     handleLocationError(true, infoWindow, map.getCenter());
    //   });
    // } else {
    //   // Browser doesn't support Geolocation
    //   handleLocationError(false, infoWindow, map.getCenter());
    // }

    /* Menu com o formulário para criar o roteiro da rota */
    var centerControlDiv = document.createElement('div');
    var centerControl = new CenterControl(centerControlDiv, map);
    centerControlDiv.index = 1;
    map.controls[google.maps.ControlPosition.TOP_CENTER].push(centerControlDiv);
   //  /* Menu com o formulário para criar o roteiro da rota */
  	// var centerRouteControlDiv = document.createElement('div');
  	// var centerRouteControl = new RouteControl(centerRouteControlDiv, map);
  	// centerRouteControlDiv.index = 1;
  	// map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(centerRouteControlDiv);
  	// /* Display da Rota */
   //  directionsDisplay.setMap(map);
   if(criar_rota){
      document.getElementById("origem").value = lat_user + "," + long_user;
      document.getElementById("destino").value = destino_voz;
      calcularRota();
   }
  }

  // Iniciar a rota
  function calcularRota(){
    iniciarRoteiro(directionsService, directionsDisplay);
  }


  //Função para habilitar o trânsito do país.
  function habilitarTraffic(){
  	if(!trafficHabilitado){
  		trafficHabilitado = true;
  		trafficLayer.setMap(map);
  	}else{
  		trafficHabilitado = false;
  		trafficLayer.setMap(null);
  	}
  }

	
function iniciarRoteiro(directionsService, directionsDisplay) {
  clearParadas();
  var waypts = [];
  var checkboxArray = document.getElementById('waypoints');
  for (var i = 0; i < checkboxArray.length; i++) {
    if (checkboxArray.options[i].selected) {
      waypts.push({
      location: checkboxArray[i].value,
      stopover: true
      });
    }
  }
  var origem = document.getElementById('origem').value; 
  var destino = document.getElementById('destino').value; 

  directionsService.route({
      origin: origem,
      destination: destino,
      waypoints: waypts,
      travelMode: 'DRIVING'
    },
  function(response, status) {
    if (status === 'OK') {
      directionsDisplay.setDirections(response);
      // var preco_combustivel = parseFloat(document.getElementById('preco_combustivel').value);
      // var km_l = parseFloat(document.getElementById('media_veiculo').value);
      // var tempo_viagem = response.routes[0].legs[0].duration.text;
      // var distancia_viagem_km = response.routes[0].legs[0].distance.value / 1000;
      // var consumoViagem = ( distancia_viagem_km / km_l) * preco_combustivel;
      var overviewPath = response.routes[0].overview_path,overviewPathGeo = [];
      var vetor_latitude = [];
      var vetor_longitude = [];
      for(var i = 0; i < overviewPath.length; i++) {
        vetor_latitude.push(overviewPath[i].lat());
        vetor_longitude.push(overviewPath[i].lng());
      }
      pageurl = 'verificarRota.php';
      var dadosajax = {
        "latitude" : vetor_latitude,
        "longitude" : vetor_longitude,
        "limite_km" : 1
      };
      $.ajax({
        url: pageurl,
        data: dadosajax,
        type: 'POST',
        cache: false,
        error: function(reuslt){
          alert('Erro: Inserir Registo!!')
          console.log(result);
        },
        success: function(result)
        { 
          console.log(result);
          for(var i = 0; i < result.length;i++){
            var avaliacao = 'Média avaliação<br><br>';
            var id_local = result[i].id;
            var servicos = [];
            for(var y = 0;y < (result[i].avaliacao).length;y++){
              var objeto = result[i].avaliacao[y];
              avaliacao += ''+
              '<img src="icones/'+ objeto.icon +'"><a style="margin-left: 6px;font-size: 14px;background-color: #343e78" class="badge"  ><span  class="glyphicon glyphicon-star"> </span> '  + objeto.nota + '</a><hr style="margin-top: 9px;margin-bottom: 9px;">';
            }
            var contentString = '<div class="infos" ><font size="3"color="#660000"><b>' + result[i].nome +   '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></font><hr></font><b> ' + avaliacao + ' <br><button class="btn btn-block button_class"  data-toggle="modal" data-target="#avaliacao" onClick="iniciarAvaliacao(' + id_local +',' + "'" + result[i].nome+ "'" + ')">avaliar</button>'+ '</div>';
            var coordinate = {lat: parseFloat(result[i].lat),lng: parseFloat(result[i].long)};
            var marcador_parada = ({
              coords: coordinate,
              content: contentString,
              map: map
            });
            info_paradas(marcador_parada);
          }
        }
      });

    }else {
      window.alert('Falha ao gerar rota: ' + status);
    }
  });
}
google.maps.event.addDomListener(window, 'load', initMap);

</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBy9FBPgLtjW9GWaxKUFk2naTE4ce_FkCQ&callback=initMap"></script>
</script> 
</body>
</html>
