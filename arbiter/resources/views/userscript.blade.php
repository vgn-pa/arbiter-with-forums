@extends('layouts.blank')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Install userscript</div>

                <div class="card-body">
                  <p>Copy/paste the below code into a new userscript in your greasemonkey/tampermonkey plugin/extension on your browser and then enable it.</p>
                  <pre>
// ==UserScript==
// @name          VenoX PA Script
// @namespace     na
// @version       0.1
// @description   Loads VenoX PA Script into PA
// @run-at        document-start
// @exclude       https://*/login*
// @exclude       https://*/signup*
// @exclude       https://*/register*
// @exclude       https://*/support*
// @exclude       https://*/manual*
// @exclude       https://*/chat*
// @exclude       https://*/forum*
// @exclude       https://*/botfiles*
// @exclude       https://pirate.planetarion.com/*
// @exclude       https://ninja.planetarion.com/*
// @exclude       https://forum.planetarion.com/*
// @exclude       https://www.planetarion.com/*
// {{ '@'.'include' }}        https://*.planetarion.com/*
// {{ '@'.'include' }}        http://*.planetarion.com/*
// {{ '@'.'include' }}        https://*.planetarion.com/*.p*
// {{ '@'.'include' }}        http://*.planetarion.com/*.p*
// {{ '@'.'include' }}        https://pa.ranultech.co.uk/*.p*
// @match       https://*.planetarion.com/*.p*
// @match       http://*.planetarion.com/*.p*
// @match       https://*.planetarion.com/*
// @match       http://*.planetarion.com/*
// @match       https://pa.ranultech.co.uk/*.p*
// @copyright   Original Copyright: 2008-2013, William Elwood (Will, will@howlingrain.co.uk). Adapted for CT by Rene Lodder (remyafk@gmail.com) 2011-2013. Adapted for p3nguins by munkee 2017. Stolen by moldypenguins 2018. Adapted by VenoX 2019.
// @license     GNU General Public License version 3; https://www.gnu.org/licenses/gpl.html
// ==/UserScript==

(function(w) {
    var d = w.document, l = w.location;
    if(!d || !l) return;
    var h;
    var s = d.createElement('script');
    s.async = true;
    s.setAttribute('type', 'text/javascript');
    s.setAttribute('src', '{{ url('/') }}/pascript');
    inject();
    function inject() {
        if(h = (d.head || d.getElementsByTagName('head')[0] || d.body || d.documentElement)) {
            h.insertBefore(s, h.firstChild);
        } else {
            setTimeout(inject, 10);
        }
    }
})(window);
                </pre>
              </div>
            </div>
          </div>
        </div>
      </div>
@endsection
