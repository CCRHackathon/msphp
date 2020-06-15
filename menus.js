      var map;
      function CenterControl(controlDiv, map) {

        var controlUI = document.createElement('div');
        controlUI.style.backgroundColor = '#fff';
        controlUI.style.border = '2px solid #fff';
        controlUI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
        controlUI.style.textAlign = 'center';
        controlUI.title = 'hackathon ccr';
        controlDiv.appendChild(controlUI);

        //wL
        var controlText = document.createElement('div');
        controlText.style.color = 'rgb(25,25,25)';
        controlText.style.fontFamily = 'Roboto,Arial,sans-serif';
        controlText.style.fontSize = '12px';
        controlText.style.paddingLeft = '3px';
        controlText.style.paddingRight = '5px';
        controlText.style.lineHeight = '28px';
        controlText.style.textAlign = 'left';
        controlText.innerHTML = '        <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#exampleModalCenter"><span class="glyphicon glyphicon-road" style="height:10px;" ></span></button> <button class="btn btn-sm btn-info"  onclick="getLocais()"><span class="glyphicon glyphicon-exclamation-sign" style="height:10px;" ></span></button> <button class="btn btn-sm btn-warning"  onclick="habilitarTraffic()"><span class="glyphicon glyphicon-stats" style="height:10px;" ></span></button> ';
        controlUI.appendChild(controlText);
      }

function habilitarTraffic(){
  if(!trafficHabilitado){
    trafficHabilitado = true;
    trafficLayer.setMap(map);
  }else{
    trafficHabilitado = false;
    trafficLayer.setMap(null);
  }
}