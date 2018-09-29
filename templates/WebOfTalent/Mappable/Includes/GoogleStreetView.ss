<% include WebOfTalent/Mappable/Includes/GoogleJavaScript %>
<% require css("weboftalent/mappable: dist/css/clientbundle.css") %>
SHORT CODE SV! KEY=$GoogleMapKey LL=$Latitude $Longitude

<div class="streetviewcontainer">
<div id="$DomID" class="streetview googlestreetview mappable-map" <% if $GoogleMapKey %> data-google-map-key="$GoogleMapKey"<% end_if %><% if $GoogleMapLang %> data-google-map-lang="$GoogleMapLang" <% end_if %> data-streetview
data-latitude=$Latitude
data-longitude=$Longitude
data-zoom=$Zoom
data-pitch=$Pitch
data-heading=$Heading
></div>
<% if $Caption %><p class="caption">$Caption</p><% end_if %>
</div>
