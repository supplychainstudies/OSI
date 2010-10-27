<?php if(!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * Helper for generating kml.
 *
 * @version 0.7.5
 * @author sourcemap@media.mit.edu
 * @package sourcemap
 * @subpackage helpers
 */

function kml_encode($object) {
	$ocoords = explode("|", $object->latlon);
	$kml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$kml .= "<kml xmlns=\"http://www.opengis.net/kml/2.2\" xmlns:gx=\"http://www.google.com/kml/ext/2.2\">\n\n";
	$kml .= " <Document>\n";
	$kml .= "  <name>Sourcemap of " . $object->name . "</name>\n";
	$kml .= "  <open>1</open>\n\n";
	$kml .= "  <Style id=\"smline\">\n";
	$kml .= "   <LineStyle>\n";
	$kml .= "    <color>7f00ff00</color>\n";
	$kml .= "    <width>5</width>\n";
	$kml .= "   </LineStyle>\n";
	$kml .= "  </Style>\n\n";
	$kml .= "  <Style id=\"smorigin\">\n";
	$kml .= "   <IconStyle>\n";
	$kml .= "    <Icon>\n";
	$kml .= "     <href>http://maps.google.com/mapfiles/kml/pal4/icon28.png</href>\n";
	$kml .= "    </Icon>\n";
	$kml .= "   </IconStyle>\n";
	$kml .= "  </Style>\n\n";
	$kml .= "  <Style id=\"smnormal\">\n";
	$kml .= "   <IconStyle>\n";
	$kml .= "    <Icon>\n";
	$kml .= "     <href>http://maps.google.com/mapfiles/kml/pal4/icon27.png</href>\n";
	$kml .= "    </Icon>\n";
	$kml .= "   </IconStyle>\n";
	$kml .= "  </Style>\n\n";
	$kml .= "  <gx:Tour>\n";
	$kml .= "   <name>Travel through the Sourcemap for ".$object->name."</name>\n";
	$kml .= "   <gx:Playlist>\n";
	$kml .= "    <gx:TourControl>\n";
	$kml .= "     <gx:playMode>pause</gx:playMode>   \n";
	$kml .= "    </gx:TourControl>  \n";
	$kml .= "       \n";
	// Create tour
	foreach($object->parts as $part) {
		$coords = explode("|", $part->latlon);
		$kml .= "    <gx:FlyTo>\n";
		$kml .= "     <gx:duration>5.0</gx:duration>\n";
		$kml .= "     <Camera>\n";
		$kml .= "      <longitude>" . $ocoords[1] . "</longitude>\n";
		$kml .= "      <latitude>" . $ocoords[0] . "</latitude>\n";
		$kml .= "      <altitude>9700</altitude>\n";
		$kml .= "      <heading>-6.333</heading>\n";
		$kml .= "      <tilt>0</tilt> \n";
		$kml .= "     </Camera>\n";
		$kml .= "    </gx:FlyTo>\n\n";
		$kml .= "    <gx:FlyTo>\n";
		$kml .= "     <gx:duration>10.0</gx:duration>\n";
		$kml .= "     <Camera>\n";
		$kml .= "      <longitude>" . $coords[1] . "</longitude>\n";
		$kml .= "      <latitude>" . $coords[0] . "</latitude>\n";
		$kml .= "      <altitude>9700</altitude>\n";
		$kml .= "      <heading>-6.333</heading>\n";
		$kml .= "      <tilt>0</tilt> \n";
		$kml .= "     </Camera>\n";
		$kml .= "    </gx:FlyTo>\n\n";
	}
	$kml .= "   </gx:Playlist>\n";
	$kml .= "  </gx:Tour>\n\n";
	$kml .= "  <Placemark>\n";
	$kml .= "   <name>" . $object->name . " made in " . $object->origin . "</name>\n";
	$kml .= "   <description><![CDATA[" . $object->description . "]]></description>\n";
	$kml .= "   <styleUrl>#smorigin</styleUrl>\n";
	$kml .= "   <Point>\n";
	$kml .= "    <coordinates>". $ocoords[1] . "," . $ocoords[0] . "</coordinates>\n";
	$kml .= "   </Point>\n";
	$kml .= "  </Placemark>\n\n";
	// Render markers and paths
	foreach($object->parts as $part) {
		$coords = explode("|", $part->latlon);
		$kml .= "  <Placemark>\n";
		$kml .= "   <styleUrl>#smline</styleUrl>\n";
		$kml .= "   <LineString>\n";
		$kml .= "    <name>Path of " . $part->name . " to assembly.</name>\n";
		$kml .= "    <extrude>1</extrude>\n";
		$kml .= "    <tessellate>1</tessellate>\n";
		$kml .= "    <altitudeMode>clampToGround</altitudeMode>\n";
		$kml .= "    <coordinates>". $ocoords[1] . "," . $ocoords[0] . " " . $coords[1] . "," . $coords[0] . "</coordinates>\n";
		$kml .= "   </LineString>\n";
		$kml .= "  </Placemark>\n\n";
		$kml .= "  <Placemark>\n";
		$kml .= "   <styleUrl>#smnormal</styleUrl>\n";
		$kml .= "   <name>" . $part->name . " from " . $part->origin ."</name>\n";
		$kml .= "   <description><![CDATA[" . $part->description . "]]></description>\n";
		$kml .= "   <Point>\n";
		$kml .= "    <coordinates>". $coords[1] . "," . $coords[0] . "</coordinates>\n";
		$kml .= "   </Point>\n";
		$kml .= "   </Placemark>\n\n";
	}
	$kml .=	" </Document>";
	$kml .= "</kml>";
	return $kml;
}