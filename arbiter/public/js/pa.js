(function(w) {
  var doc = w.document;
  if (!doc) return;

  var $j;
  var skin = '';
  var page = '';
  var view = '';
  var tick = 0;
  var globalDependencies = ['jQuery', 'get_cookie', 'get_ships_cookie', 'PA'];


  if (doc.readyState == 'complete') {
    checkDeps(true);
  } else {
    var _ev = w.addEventListener ? {add: 'addEventListener', rem: 'removeEventListener', pfx: ''} : w.attachEvent ? {add: 'attachEvent', rem: 'detachEvent', pfx: 'on'} : null;
    if(_ev) {
      doc[_ev.add](_ev.pfx + 'DOMContentLoaded', waitLoad, false);
      doc[_ev.add](_ev.pfx + 'readystatechange', waitLoad, false);
      w[_ev.add](_ev.pfx + 'load', waitLoad, false);
    } else {
      checkDeps();
    }
  }

  function waitLoad(ev) {
    ev = ev || w.event;
    if(ev.type === 'readystatechange' && doc.readyState && doc.readyState !== 'complete' && doc.readyState !== 'loaded') return;
    if(_ev) {
      doc[_ev.rem](_ev.pfx + 'DOMContentLoaded', waitLoad);
      doc[_ev.rem](_ev.pfx + 'readystatechange', waitLoad);
      w[_ev.rem](_ev.pfx + 'load', waitLoad);
      _ev = null;
      checkDeps(true);
    }
  }

  function checkDeps(loaded) {
    var remainingDeps = globalDependencies.filter(function(dep) {
      return !w[dep];
    });
    if(!remainingDeps.length) init();
    else if (loaded) console.error(remainingDeps.length+' missing userscript dependenc'+(remainingDeps.length==1?'y':'ies')+': '+remainingDeps.join(', '));
  }

  function init() {
    if($j) return;
    $j = w.jQuery;
    $pa = w.PA;

    $j('ul#menu').append('<div style="background:pink; color: black; text-align: center;padding: 5px; margin-top: 10px">VenoX script running</div>');

    var pageHtml = $j('html').html();

    parseScanLinks(pageHtml);

    if(typeof w.PA != 'undefined' && 'page' in w.PA) { page = w.PA.page; }
    if(typeof w.PA != 'undefined' && 'last_tick' in w.PA) { tick = w.PA.last_tick; }

    if(page == 'galaxy_status') {
        parseGalStatus(pageHtml);
    }
  }

  function parseScanLinks(page)
  {
      var regex = new RegExp(/showscan.pl[?]scan_id=([a-zA-Z0-9]*)/g);
      var mtc = [];
      while( (match = regex.exec( page )) != null ) {
          mtc.push(match[1]);
      }

      var xhr = new XMLHttpRequest();
      xhr.open("POST", toolsUrl + 'api/v1/collector/scans', true);
      xhr.setRequestHeader('Content-Type', 'application/json');
      xhr.send(JSON.stringify({
          ids: mtc
      }));
  }

  function parseGalStatus(page)
  {
      var fleets = [];

      $j('#galaxy_status_incoming .mission_attack').each(function() {
          var fleet = $(this).html().replace(/(\r\n|\n|\r|\t)/gm, "");
          var regex = new RegExp(/<td[^>]*><b>(\d+)\:(\d+)\:(\d+).*<\/b>[*]?<\/td><td[^>]*><a[^>]*>(\d+)\:(\d+)\:(\d+)<\/a><\/td><td[^>]*>A<\/td><td[^>]*>([\w ]+)<\/td><td[^>]*>[a-zA-Z]*<\/td><td[^>]*>(\d+)<\/td><td[^>]*>(\d+)<\/td>/g);
          var match = regex.exec( fleet );
          if(match) {
              fleets.push({
                to_x: match[1],
                to_y: match[2],
                to_z: match[3],
                from_x: match[4],
                from_y: match[5],
                from_z: match[6],
                fleet: match[7],
                ships: match[8],
                eta: match[9],
                tick: tick,
                type: 'Attack'
              });
          }
      })

      $j('#galaxy_status_incoming .mission_defend').each(function() {
          var fleet = $(this).html().replace(/(\r\n|\n|\r|\t)/gm, "");
          var regex = new RegExp(/<td[^>]*><b>(\d+)\:(\d+)\:(\d+).*<\/b>[*]?<\/td><td[^>]*><a[^>]*>(\d+)\:(\d+)\:(\d+)<\/a><\/td><td[^>]*>D<\/td><td[^>]*>([\w ]+)<\/td><td[^>]*>[a-zA-Z]*<\/td><td[^>]*>(\d+)<\/td><td[^>]*>(\d+)<\/td>/g);
          var match = regex.exec( fleet );
          if(match) {
              fleets.push({
                to_x: match[1],
                to_y: match[2],
                to_z: match[3],
                from_x: match[4],
                from_y: match[5],
                from_z: match[6],
                fleet: match[7],
                ships: match[8],
                eta: match[9],
                tick: tick,
                type: 'Defend'
              });
          }
      })

      $j('#galaxy_status_outgoing .mission_attack').each(function() {
          var fleet = $(this).html().replace(/(\r\n|\n|\r|\t)/gm, "");
          var regex = new RegExp(/<td[^>]*><a[^>]*>(\d+)\:(\d+)\:(\d+)<\/a><\/td><td[^>]*>(\d+)\:(\d+)\:(\d+).*<\/td><td[^>]*>A<\/td><td[^>]*>([\w ]+)<\/td><td[^>]*>(\d+)<\/td><td[^>]*>(\d+)<\/td>/g);
          var match = regex.exec( fleet );
          if(match) {
              fleets.push({
                to_x: match[1],
                to_y: match[2],
                to_z: match[3],
                from_x: match[4],
                from_y: match[5],
                from_z: match[6],
                fleet: match[7],
                ships: match[8],
                eta: match[9],
                tick: tick,
                type: 'Attack'
              });
          }
      })

      $j('#galaxy_status_outgoing .mission_defend').each(function() {
          var fleet = $(this).html().replace(/(\r\n|\n|\r|\t)/gm, "");
          var regex = new RegExp(/<td[^>]*><a[^>]*>(\d+)\:(\d+)\:(\d+)<\/a><\/td><td[^>]*>(\d+)\:(\d+)\:(\d+).*<\/td><td[^>]*>D<\/td><td[^>]*>([\w ]+)<\/td><td[^>]*>(\d+)<\/td><td[^>]*>(\d+)<\/td>/g);
          var match = regex.exec( fleet );
          if(match) {
              fleets.push({
                to_x: match[1],
                to_y: match[2],
                to_z: match[3],
                from_x: match[4],
                from_y: match[5],
                from_z: match[6],
                fleet: match[7],
                ships: match[8],
                eta: match[9],
                tick: tick,
                type: 'Defend'
              });
          }
      })

      if(fleets.length) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", toolsUrl + 'api/v1/collector/fleets', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.send(JSON.stringify(fleets));
      }
      
  }

})(window);





function getUrlParameter(name) {
  name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
  var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
  var results = regex.exec(location.search);
  return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
};
