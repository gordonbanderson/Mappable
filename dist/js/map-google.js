function FullScreenControl(e,t,r){void 0===t&&(t=null),void 0===r&&(r=null),null==t&&(t="Full screen"),null==r&&(r="Exit full screen");var s=document.createElement("div");s.className="fullScreen",s.index=1,s.style.padding="5px";var o=document.createElement("div");o.style.backgroundColor="white",o.style.borderStyle="solid",o.style.borderWidth="1px",o.style.borderColor="#717b87",o.style.cursor="pointer",o.style.textAlign="center",o.style.boxShadow="rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px",s.appendChild(o);var a=document.createElement("div");a.style.fontFamily="Roboto,Arial,sans-serif",a.style.fontSize="11px",a.style.fontWeight="400",a.style.paddingTop="1px",a.style.paddingBottom="1px",a.style.paddingLeft="6px",a.style.paddingRight="6px",a.innerHTML="<strong>"+t+"</strong>",o.appendChild(a);var i=document.getElementsByTagName("head")[0],n=document.createElement("style");n.setAttribute("type","text/css"),n.setAttribute("media","print");var l=".fullScreen { display: none;}",p=document.createTextNode(l);try{n.appendChild(p)}catch(e){n.styleSheet.cssText=l}i.appendChild(n);var u,h=!1,d=e.getDiv(),g=d.style;d.runtimeStyle&&(g=d.runtimeStyle);var c=g.position,m=g.width,y=g.height;""===m&&(m=d.style.width),""===y&&(y=d.style.height);var _=g.top,C=g.left,M=g.zIndex,k=document.body.style;document.body.runtimeStyle&&(k=document.body.runtimeStyle);var f=k.overflow;return s.goFullScreen=function(){var t=e.getCenter();d.style.position="fixed",d.style.width="100%",d.style.height="100%",d.style.top="0",d.style.left="0",d.style.zIndex="100",document.body.style.overflow="hidden",a.innerHTML="<strong>"+r+"</strong>",h=!0,google.maps.event.trigger(e,"resize"),e.setCenter(t),u=setInterval(function(){"fixed"!==d.style.position&&(d.style.position="fixed",google.maps.event.trigger(e,"resize"))},100)},s.exitFullScreen=function(){var r=e.getCenter();d.style.position=""===c?"relative":c,d.style.width=m,d.style.height=y,d.style.top=_,d.style.left=C,d.style.zIndex=M,document.body.style.overflow=f,a.innerHTML="<strong>"+t+"</strong>",h=!1,google.maps.event.trigger(e,"resize"),e.setCenter(r),clearInterval(u)},google.maps.event.addDomListener(o,"click",function(){h?s.exitFullScreen():s.goFullScreen()}),document.onkeydown=function(e){27==(e=e||window.event).keyCode&&h&&s.exitFullScreen()},s}function MarkerClusterer(e,t,r){this.extend(MarkerClusterer,google.maps.OverlayView),this.map_=e,this.markers_=[],this.clusters_=[],this.sizes=[53,56,66,78,90],this.styles_=[],this.ready_=!1;var s=r||{};this.gridSize_=s.gridSize||60,this.minClusterSize_=s.minimumClusterSize||2,this.maxZoom_=s.maxZoom||null,this.styles_=s.styles||[],this.imagePath_=s.imagePath||this.MARKER_CLUSTER_IMAGE_PATH_,this.imageExtension_=s.imageExtension||this.MARKER_CLUSTER_IMAGE_EXTENSION_,this.zoomOnClick_=!0,void 0!=s.zoomOnClick&&(this.zoomOnClick_=s.zoomOnClick),this.averageCenter_=!1,void 0!=s.averageCenter&&(this.averageCenter_=s.averageCenter),this.setupStyles_(),this.setMap(e),this.prevZoom_=this.map_.getZoom();var o=this;google.maps.event.addListener(this.map_,"zoom_changed",function(){var e=o.map_.getZoom();o.prevZoom_!=e&&(o.prevZoom_=e,o.resetViewport())}),google.maps.event.addListener(this.map_,"idle",function(){o.redraw()}),t&&t.length&&this.addMarkers(t,!1)}function Cluster(e){this.markerClusterer_=e,this.map_=e.getMap(),this.gridSize_=e.getGridSize(),this.minClusterSize_=e.getMinClusterSize(),this.averageCenter_=e.isAverageCenter(),this.center_=null,this.markers_=[],this.bounds_=null,this.clusterIcon_=new ClusterIcon(this,e.getStyles(),e.getGridSize())}function ClusterIcon(e,t,r){e.getMarkerClusterer().extend(ClusterIcon,google.maps.OverlayView),this.styles_=t,this.padding_=r||0,this.cluster_=e,this.center_=null,this.map_=e.getMap(),this.div_=null,this.sums_=null,this.visible_=!1,this.setMap(this.map_)}var primeMap;function loadedGoogleMapsAPI(){primeMap()}MarkerClusterer.prototype.MARKER_CLUSTER_IMAGE_PATH_="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/images/m",MarkerClusterer.prototype.MARKER_CLUSTER_IMAGE_EXTENSION_="png",MarkerClusterer.prototype.extend=function(e,t){return function(e){for(var t in e.prototype)this.prototype[t]=e.prototype[t];return this}.apply(e,[t])},MarkerClusterer.prototype.onAdd=function(){this.setReady_(!0)},MarkerClusterer.prototype.draw=function(){},MarkerClusterer.prototype.setupStyles_=function(){if(!this.styles_.length)for(var e,t=0;e=this.sizes[t];t++)this.styles_.push({url:this.imagePath_+(t+1)+"."+this.imageExtension_,height:e,width:e})},MarkerClusterer.prototype.fitMapToMarkers=function(){for(var e,t=this.getMarkers(),r=new google.maps.LatLngBounds,s=0;e=t[s];s++)r.extend(e.getPosition());this.map_.fitBounds(r)},MarkerClusterer.prototype.setStyles=function(e){this.styles_=e},MarkerClusterer.prototype.getStyles=function(){return this.styles_},MarkerClusterer.prototype.isZoomOnClick=function(){return this.zoomOnClick_},MarkerClusterer.prototype.isAverageCenter=function(){return this.averageCenter_},MarkerClusterer.prototype.getMarkers=function(){return this.markers_},MarkerClusterer.prototype.getTotalMarkers=function(){return this.markers_.length},MarkerClusterer.prototype.setMaxZoom=function(e){this.maxZoom_=e},MarkerClusterer.prototype.getMaxZoom=function(){return this.maxZoom_},MarkerClusterer.prototype.calculator_=function(e,t){for(var r=0,s=e.length,o=s;0!==o;)o=parseInt(o/10,10),r++;return{text:s,index:r=Math.min(r,t)}},MarkerClusterer.prototype.setCalculator=function(e){this.calculator_=e},MarkerClusterer.prototype.getCalculator=function(){return this.calculator_},MarkerClusterer.prototype.addMarkers=function(e,t){for(var r,s=0;r=e[s];s++)this.pushMarkerTo_(r);t||this.redraw()},MarkerClusterer.prototype.pushMarkerTo_=function(e){if(e.isAdded=!1,e.draggable){var t=this;google.maps.event.addListener(e,"dragend",function(){e.isAdded=!1,t.repaint()})}this.markers_.push(e)},MarkerClusterer.prototype.addMarker=function(e,t){this.pushMarkerTo_(e),t||this.redraw()},MarkerClusterer.prototype.removeMarker_=function(e){var t=-1;if(this.markers_.indexOf)t=this.markers_.indexOf(e);else for(var r,s=0;r=this.markers_[s];s++)if(r==e){t=s;break}return-1!=t&&(e.setMap(null),this.markers_.splice(t,1),!0)},MarkerClusterer.prototype.removeMarker=function(e,t){var r=this.removeMarker_(e);return!(t||!r)&&(this.resetViewport(),this.redraw(),!0)},MarkerClusterer.prototype.removeMarkers=function(e,t){for(var r,s=!1,o=0;r=e[o];o++){var a=this.removeMarker_(r);s=s||a}if(!t&&s)return this.resetViewport(),this.redraw(),!0},MarkerClusterer.prototype.setReady_=function(e){this.ready_||(this.ready_=e,this.createClusters_())},MarkerClusterer.prototype.getTotalClusters=function(){return this.clusters_.length},MarkerClusterer.prototype.getMap=function(){return this.map_},MarkerClusterer.prototype.setMap=function(e){this.map_=e},MarkerClusterer.prototype.getGridSize=function(){return this.gridSize_},MarkerClusterer.prototype.setGridSize=function(e){this.gridSize_=e},MarkerClusterer.prototype.getMinClusterSize=function(){return this.minClusterSize_},MarkerClusterer.prototype.setMinClusterSize=function(e){this.minClusterSize_=e},MarkerClusterer.prototype.getExtendedBounds=function(e){var t=this.getProjection(),r=new google.maps.LatLng(e.getNorthEast().lat(),e.getNorthEast().lng()),s=new google.maps.LatLng(e.getSouthWest().lat(),e.getSouthWest().lng()),o=t.fromLatLngToDivPixel(r);o.x+=this.gridSize_,o.y-=this.gridSize_;var a=t.fromLatLngToDivPixel(s);a.x-=this.gridSize_,a.y+=this.gridSize_;var i=t.fromDivPixelToLatLng(o),n=t.fromDivPixelToLatLng(a);return e.extend(i),e.extend(n),e},MarkerClusterer.prototype.isMarkerInBounds_=function(e,t){return t.contains(e.getPosition())},MarkerClusterer.prototype.clearMarkers=function(){this.resetViewport(!0),this.markers_=[]},MarkerClusterer.prototype.resetViewport=function(e){for(var t,r=0;t=this.clusters_[r];r++)t.remove();var s;for(r=0;s=this.markers_[r];r++)s.isAdded=!1,e&&s.setMap(null);this.clusters_=[]},MarkerClusterer.prototype.repaint=function(){var e=this.clusters_.slice();this.clusters_.length=0,this.resetViewport(),this.redraw(),window.setTimeout(function(){for(var t,r=0;t=e[r];r++)t.remove()},0)},MarkerClusterer.prototype.redraw=function(){this.createClusters_()},MarkerClusterer.prototype.distanceBetweenPoints_=function(e,t){if(!e||!t)return 0;var r=(t.lat()-e.lat())*Math.PI/180,s=(t.lng()-e.lng())*Math.PI/180,o=Math.sin(r/2)*Math.sin(r/2)+Math.cos(e.lat()*Math.PI/180)*Math.cos(t.lat()*Math.PI/180)*Math.sin(s/2)*Math.sin(s/2);return 6371*(2*Math.atan2(Math.sqrt(o),Math.sqrt(1-o)))},MarkerClusterer.prototype.addToClosestCluster_=function(e){for(var t,r=4e4,s=null,o=(e.getPosition(),0);t=this.clusters_[o];o++){var a=t.getCenter();if(a){var i=this.distanceBetweenPoints_(a,e.getPosition());i<r&&(r=i,s=t)}}s&&s.isMarkerInClusterBounds(e)?s.addMarker(e):((t=new Cluster(this)).addMarker(e),this.clusters_.push(t))},MarkerClusterer.prototype.createClusters_=function(){if(this.ready_)for(var e,t=new google.maps.LatLngBounds(this.map_.getBounds().getSouthWest(),this.map_.getBounds().getNorthEast()),r=this.getExtendedBounds(t),s=0;e=this.markers_[s];s++)!e.isAdded&&this.isMarkerInBounds_(e,r)&&this.addToClosestCluster_(e)},Cluster.prototype.isMarkerAlreadyAdded=function(e){if(this.markers_.indexOf)return-1!=this.markers_.indexOf(e);for(var t,r=0;t=this.markers_[r];r++)if(t==e)return!0;return!1},Cluster.prototype.addMarker=function(e){if(this.isMarkerAlreadyAdded(e))return!1;if(this.center_){if(this.averageCenter_){var t=this.markers_.length+1,r=(this.center_.lat()*(t-1)+e.getPosition().lat())/t,s=(this.center_.lng()*(t-1)+e.getPosition().lng())/t;this.center_=new google.maps.LatLng(r,s),this.calculateBounds_()}}else this.center_=e.getPosition(),this.calculateBounds_();e.isAdded=!0,this.markers_.push(e);var o=this.markers_.length;if(o<this.minClusterSize_&&e.getMap()!=this.map_&&e.setMap(this.map_),o==this.minClusterSize_)for(var a=0;a<o;a++)this.markers_[a].setMap(null);return o>=this.minClusterSize_&&e.setMap(null),this.updateIcon(),!0},Cluster.prototype.getMarkerClusterer=function(){return this.markerClusterer_},Cluster.prototype.getBounds=function(){for(var e,t=new google.maps.LatLngBounds(this.center_,this.center_),r=this.getMarkers(),s=0;e=r[s];s++)t.extend(e.getPosition());return t},Cluster.prototype.remove=function(){this.clusterIcon_.remove(),this.markers_.length=0,delete this.markers_},Cluster.prototype.getSize=function(){return this.markers_.length},Cluster.prototype.getMarkers=function(){return this.markers_},Cluster.prototype.getCenter=function(){return this.center_},Cluster.prototype.calculateBounds_=function(){var e=new google.maps.LatLngBounds(this.center_,this.center_);this.bounds_=this.markerClusterer_.getExtendedBounds(e)},Cluster.prototype.isMarkerInClusterBounds=function(e){return this.bounds_.contains(e.getPosition())},Cluster.prototype.getMap=function(){return this.map_},Cluster.prototype.updateIcon=function(){var e=this.map_.getZoom(),t=this.markerClusterer_.getMaxZoom();if(t&&e>t)for(var r,s=0;r=this.markers_[s];s++)r.setMap(this.map_);else if(this.markers_.length<this.minClusterSize_)this.clusterIcon_.hide();else{var o=this.markerClusterer_.getStyles().length,a=this.markerClusterer_.getCalculator()(this.markers_,o);this.clusterIcon_.setCenter(this.center_),this.clusterIcon_.setSums(a),this.clusterIcon_.show()}},ClusterIcon.prototype.triggerClusterClick=function(){var e=this.cluster_.getMarkerClusterer();google.maps.event.trigger(e,"clusterclick",this.cluster_),e.isZoomOnClick()&&this.map_.fitBounds(this.cluster_.getBounds())},ClusterIcon.prototype.onAdd=function(){if(this.div_=document.createElement("DIV"),this.visible_){var e=this.getPosFromLatLng_(this.center_);this.div_.style.cssText=this.createCss(e),this.div_.innerHTML=this.sums_.text}this.getPanes().overlayMouseTarget.appendChild(this.div_);var t=this;google.maps.event.addDomListener(this.div_,"click",function(){t.triggerClusterClick()})},ClusterIcon.prototype.getPosFromLatLng_=function(e){var t=this.getProjection().fromLatLngToDivPixel(e);return t.x-=parseInt(this.width_/2,10),t.y-=parseInt(this.height_/2,10),t},ClusterIcon.prototype.draw=function(){if(this.visible_){var e=this.getPosFromLatLng_(this.center_);this.div_.style.top=e.y+"px",this.div_.style.left=e.x+"px"}},ClusterIcon.prototype.hide=function(){this.div_&&(this.div_.style.display="none"),this.visible_=!1},ClusterIcon.prototype.show=function(){if(this.div_){var e=this.getPosFromLatLng_(this.center_);this.div_.style.cssText=this.createCss(e),this.div_.style.display=""}this.visible_=!0},ClusterIcon.prototype.remove=function(){this.setMap(null)},ClusterIcon.prototype.onRemove=function(){this.div_&&this.div_.parentNode&&(this.hide(),this.div_.parentNode.removeChild(this.div_),this.div_=null)},ClusterIcon.prototype.setSums=function(e){this.sums_=e,this.text_=e.text,this.index_=e.index,this.div_&&(this.div_.innerHTML=e.text),this.useStyle()},ClusterIcon.prototype.useStyle=function(){var e=Math.max(0,this.sums_.index-1);e=Math.min(this.styles_.length-1,e);var t=this.styles_[e];this.url_=t.url,this.height_=t.height,this.width_=t.width,this.textColor_=t.textColor,this.anchor_=t.anchor,this.textSize_=t.textSize,this.backgroundPosition_=t.backgroundPosition},ClusterIcon.prototype.setCenter=function(e){this.center_=e},ClusterIcon.prototype.createCss=function(e){var t=[];t.push("background-image:url("+this.url_+");");var r=this.backgroundPosition_?this.backgroundPosition_:"0 0";t.push("background-position:"+r+";"),"object"==typeof this.anchor_?("number"==typeof this.anchor_[0]&&this.anchor_[0]>0&&this.anchor_[0]<this.height_?t.push("height:"+(this.height_-this.anchor_[0])+"px; padding-top:"+this.anchor_[0]+"px;"):t.push("height:"+this.height_+"px; line-height:"+this.height_+"px;"),"number"==typeof this.anchor_[1]&&this.anchor_[1]>0&&this.anchor_[1]<this.width_?t.push("width:"+(this.width_-this.anchor_[1])+"px; padding-left:"+this.anchor_[1]+"px;"):t.push("width:"+this.width_+"px; text-align:center;")):t.push("height:"+this.height_+"px; line-height:"+this.height_+"px; width:"+this.width_+"px; text-align:center;");var s=this.textColor_?this.textColor_:"black",o=this.textSize_?this.textSize_:11;return t.push("cursor:pointer; top:"+e.y+"px; left:"+e.x+"px; color:"+s+"; position:absolute; font-size:"+o+"px; font-family:Arial,sans-serif; font-weight:bold"),t.join("")},window.MarkerClusterer=MarkerClusterer,MarkerClusterer.prototype.addMarker=MarkerClusterer.prototype.addMarker,MarkerClusterer.prototype.addMarkers=MarkerClusterer.prototype.addMarkers,MarkerClusterer.prototype.clearMarkers=MarkerClusterer.prototype.clearMarkers,MarkerClusterer.prototype.fitMapToMarkers=MarkerClusterer.prototype.fitMapToMarkers,MarkerClusterer.prototype.getCalculator=MarkerClusterer.prototype.getCalculator,MarkerClusterer.prototype.getGridSize=MarkerClusterer.prototype.getGridSize,MarkerClusterer.prototype.getExtendedBounds=MarkerClusterer.prototype.getExtendedBounds,MarkerClusterer.prototype.getMap=MarkerClusterer.prototype.getMap,MarkerClusterer.prototype.getMarkers=MarkerClusterer.prototype.getMarkers,MarkerClusterer.prototype.getMaxZoom=MarkerClusterer.prototype.getMaxZoom,MarkerClusterer.prototype.getStyles=MarkerClusterer.prototype.getStyles,MarkerClusterer.prototype.getTotalClusters=MarkerClusterer.prototype.getTotalClusters,MarkerClusterer.prototype.getTotalMarkers=MarkerClusterer.prototype.getTotalMarkers,MarkerClusterer.prototype.redraw=MarkerClusterer.prototype.redraw,MarkerClusterer.prototype.removeMarker=MarkerClusterer.prototype.removeMarker,MarkerClusterer.prototype.removeMarkers=MarkerClusterer.prototype.removeMarkers,MarkerClusterer.prototype.resetViewport=MarkerClusterer.prototype.resetViewport,MarkerClusterer.prototype.repaint=MarkerClusterer.prototype.repaint,MarkerClusterer.prototype.setCalculator=MarkerClusterer.prototype.setCalculator,MarkerClusterer.prototype.setGridSize=MarkerClusterer.prototype.setGridSize,MarkerClusterer.prototype.setMaxZoom=MarkerClusterer.prototype.setMaxZoom,MarkerClusterer.prototype.onAdd=MarkerClusterer.prototype.onAdd,MarkerClusterer.prototype.draw=MarkerClusterer.prototype.draw,Cluster.prototype.getCenter=Cluster.prototype.getCenter,Cluster.prototype.getSize=Cluster.prototype.getSize,Cluster.prototype.getMarkers=Cluster.prototype.getMarkers,ClusterIcon.prototype.onAdd=ClusterIcon.prototype.onAdd,ClusterIcon.prototype.draw=ClusterIcon.prototype.draw,ClusterIcon.prototype.onRemove=ClusterIcon.prototype.onRemove,function(e){var t=[];function r(e,r,s,o,a,i,n,l,p,u){mapId=e.getDiv().getAttribute("id");var h=new google.maps.Marker;if(h.setPosition(new google.maps.LatLng(r,s)),h.mycategory=a,i&&""!==i){var d=new google.maps.MarkerImage(i);h.setIcon(d)}return"1"!=n&&h.setMap(e),google.maps.event.addListener(h,"click",function(){l&&e.setCenter(new google.maps.LatLng(r,s),p);var a=t[mapId];a.setContent(o),a.open(e,this)}),console.log(!1===u),"1"==u?h.setVisible(!1):h.setVisible(!0),h}function s(e){var t=google.maps.MapTypeId.ROADMAP;switch(e){case"aerial":t=google.maps.MapTypeId.SATELLITE;break;case"hybrid":t=google.maps.MapTypeId.HYBRID;break;case"terrain":t=google.maps.MapTypeId.TERRAIN}return t}primeMap=function(){e("div[data-shortcode-map]").each(function(t){var r=e(this),o=r.attr("id"),a=s(r.attr("data-maptype")),i=parseFloat(r.attr("data-latitude")),n=parseFloat(r.attr("data-longitude")),l=parseInt(r.attr("data-zoom")),p=parseInt(r.attr("data-allowfullscreen")),u={center:{lat:i,lng:n},zoom:l,mapTypeId:a},h=new google.maps.Map(document.getElementById(o),u);1==p&&h.controls[google.maps.ControlPosition.TOP_RIGHT].push(FullScreenControl(h,"Full Screen","Original Size"))}),e("div[data-streetview]").each(function(t){var r=e(this),s=r.attr("id"),o=parseFloat(r.attr("data-latitude")),a=parseFloat(r.attr("data-longitude")),i=parseInt(r.attr("data-zoom")),n=parseFloat(r.attr("data-heading")),l=parseFloat(r.attr("data-pitch")),p={position:new google.maps.LatLng(o,a),pov:{heading:n,pitch:l},zoom:i},u=document.getElementById(s);new google.maps.StreetViewPanorama(u,p).setVisible(!0)}),e("div[data-map]").each(function(o){var a=e(this);mapdomid=a.attr("id");var n=new google.maps.Map(document.getElementById(mapdomid));if(geocoder=new google.maps.Geocoder,a.attr("data-mapstyles")){var l=e.parseJSON(a.attr("data-mapstyles"));n.setOptions({styles:l})}a.data["data-allowfullscreen"]&&n.controls[google.maps.ControlPosition.TOP_RIGHT].push(FullScreenControl(n,"Full Screen","Original Size"));var p=e.parseJSON(a.attr("data-mapmarkers")),u=a.attr("data-useclusterer"),h=a.attr("data-enableautocentrezoom"),d=function(e,t,s,o,a,i){e.minLat=1e6,e.minLng=1e6,e.maxLat=-1e6,e.maxLng=-1e6;for(var n=[],l=0;l<t.length;l++){var p=t[l],u=r(e,p.latitude,p.longitude,p.html,p.category,p.icon,s,o,a,i),h=parseFloat(p.latitude),d=parseFloat(p.longitude);h<e.minLat&&(e.minLat=h),h>e.maxLat&&(e.maxLat=h),d>e.maxLng&&(e.maxLng=d),d<e.minLng&&(e.minLng=d),n.push(u)}var g=[];return g.lat=(parseFloat(e.minLat)+parseFloat(e.maxLat))/2,g.lng=(parseFloat(e.minLng)+parseFloat(e.maxLng))/2,e.centreCoordinates=g,n}(n,p,u,h,a.attr("data-infowindowzoom"),a.attr("data-defaulthidemarker")),g=parseInt(a.attr("data-allowfullscreen"));if(1==h){centre=e.parseJSON(a.attr("data-centre")),n.setCenter(new google.maps.LatLng(centre.lat,centre.lng));var c=new google.maps.LatLngBounds(new google.maps.LatLng(n.minLat,n.minLng),new google.maps.LatLng(n.maxLat,n.maxLng));n.fitBounds(c)}else{centre=e.parseJSON(a.attr("data-centre")),n.setCenter(new google.maps.LatLng(centre.lat,centre.lng));var m=parseInt(a.attr("data-zoom"));n.setZoom(m)}1==g&&n.controls[google.maps.ControlPosition.TOP_RIGHT].push(FullScreenControl(n,"Full Screen","Original Size"));var y=s(a.attr("data-maptype"));if(n.setMapTypeId(y),1==u)new MarkerClusterer(n,d,{gridSize:parseInt(a.attr("data-clusterergridsize")),maxZoom:parseInt(a.attr("data-clusterermaxzoom"))});!function(e,t){for(i=0;i<t.length;i++){var r=t[i],s=[new google.maps.LatLng(r.lat1,r.lon1),new google.maps.LatLng(r.lat2,r.lon2)];new google.maps.Polyline({path:s,strokeColor:r.color,strokeWeight:4,strokeOpacity:.8}).setMap(e)}}(n,e.parseJSON(a.attr("data-lines"))),function(e,t){for(var r=0;r<t.length;r++){var s=t[r];new google.maps.KmlLayer(s,{suppressInfoWindows:!0,map:e})}}(n,e.parseJSON(a.attr("data-kmlfiles")));var _=new google.maps.InfoWindow({content:""});t[mapdomid]=_,a.trigger("mapInitialised",[n])})},window.loadGoogleMapsScript=function(){var e=document.createElement("script");e.type="text/javascript",e.src="https://maps.googleapis.com/maps/api/js?&sensor=false&callback=loadedGoogleMapsAPI&hl=en",document.body.appendChild(e)},window.addEventListener?window.addEventListener("load",loadGoogleMapsScript,!1):window.attachEvent&&window.attachEvent("onload",loadGoogleMapsScript)}(jQuery);