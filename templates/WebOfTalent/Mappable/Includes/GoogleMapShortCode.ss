<% include WebOfTalent/Mappable/GoogleJavaScript %>
SHORT CODE! KEY=$GoogleMapKey LL=$Latitude $Longitude
<div class="googlemapcontainer">
<div id="$DomID" class="mappable-map googlemap" <% if $GoogleMapKey %> data-google-map-key="$GoogleMapKey"<% end_if %><% if $GoogleMapLang %> data-google-map-lang="$GoogleMapLang" <% end_if %>
	 data-shortcode-map
	 data-latitude=$Latitude
	 data-longitude=$Longitude
	 data-zoom=$Zoom
	 data-maptype=$MapType
	 data-allowfullscreen=$AllowFullScreen
></div>
<% if $Caption %><p class="caption">$Caption</p><% end_if %>
</div>
