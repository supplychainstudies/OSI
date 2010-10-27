<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/***
* Name Conversion for CodeIgniter
*
*    author: Bianca Sayan
* copyright: (c) 2010
*   license: http://creativecommons.org/licenses/by-sa/2.5/
*      file: libraries/name_conversion.php
*/

class Unit_conversion {

    public function Unit_conversion () {
		
	} /*** END ***/

	public $property = Array();
	public $unit = Array();
	public $factor = Array();

	$unit["Acceleration"] = Array("Meter/sq.sec (m/sec^2)", "Foot/sq.sec (ft/sec^2)", "G (g)", "Galileo (gal)", "Inch/sq.sec (in/sec^2)");
	$factor["Acceleration"] = Array(1, .3048, 9.806650, .01, 2.54E-02);

	$unit["Area"] = Array("Square meter (m^2)", "Acre (acre)", "Are", "Barn (barn)", "Hectare", "Rood", "Square centimeter", "Square kilometer", "Circular mil", "Square foot (ft^2)", "Square inch (in^2)", "Square mile (mi^2)", "Square yard (yd^2)");
	$factor["Area"] = Array(1, 4046.856, 100, 1E-28, 10000, 1011.71413184285, .0001, 1000000, 5.067075E-10, 9.290304E-02, 6.4516E-04, 2589988, .8361274);

	$unit["Torque"] = Array("Newton-meter (N m)", "Dyne-centimeter(dy cm)", "Kgrf-meter (kgf m)", "lbf-inch (lbf in)", "lbf-foot (lbf ft)");
	$factor["Torque"] = Array(1, .0000001, 9.806650, .1129848, 1.355818);

	$unit["Electricity"] = Array("Coulomb (Cb)", "Abcoulomb", "Ampere hour (A hr)", "Faraday (F)", "Statcoulomb", "Millifaraday (mF)", "Microfaraday (mu-F)", "Picofaraday (pF)");
	$factor["Electricity"] = Array(1, 10, 3600, 96521.8999999997, .000000000333564, 96.5219, 9.65219E-02, 9.65219E-05);

	$unit["Energy"] = Array("Joule (J)", "BTU (mean)", "BTU (thermochemical)", "Calorie (SI) (cal)", "Calorie (mean)(cal)", "Calorie (thermo)", "Electron volt (eV)", "Erg (erg)", "Foot-pound force", "Foot-poundal", "Horsepower-hour", "Kilocalorie (SI)(kcal)", "Kilocalorie (mean)(kcal)", "Kilowatt-hour (kW hr)", "Ton of TNT", "Volt-coulomb (V Cb)", "Watt-hour (W hr)", "Watt-second (W sec)");
	$uri_unit["Energy"] = Array("http://dbpedia.org/class/yago/Joule113726074", "BTU (mean)", "http://dbpedia.org/class/yago/BritishThermalUnit113726947", "Calorie (SI) (cal)", "Calorie (mean)(cal)", "Calorie (thermo)", "Electron volt (eV)", "Erg (erg)", "Foot-pound force", "Foot-poundal", "Horsepower-hour", "Kilocalorie (SI)(kcal)", "Kilocalorie (mean)(kcal)", "Kilowatt-hour (kW hr)", "Ton of TNT", "Volt-coulomb (V Cb)", "Watt-hour (W hr)", "Watt-second (W sec)");
	$factor["Energy"] = Array(1, 1055.87, 1054.35, 4.1868, 4.19002, 4.184, 1.6021E-19, .0000001, 1.355818, 4.214011E-02, 2684077.3, 4186.8, 4190.02, 3600000, 4.2E9, 1, 3600, 1);

	$unit["Force"] = Array("Newton (N)", "Dyne (dy)", "Kilogram force (kgf)", "Kilopond force (kpf)", "Kip (k)", "Ounce force (ozf)", "Pound force (lbf)", "Poundal");
	$factor["Force"] = Array(1, .00001, 9.806650, 9.806650, 4448.222, .2780139, .4535924, .138255);

	$unit["Force / Length"] = Array("Newton/meter (N/m)", "Pound force/inch (lbf/in)", "Pound force/foot (lbf/ft)");
	$factor["Force / Length"] = Array(1, 175.1268, 14.5939);

	$unit["Length"] = Array("Meter (m)", "Angstrom (A')", "Astronomical unit (AU)", "Caliber (cal)", "Centimeter (cm)", "Kilometer (km)", "Ell", "Em", "Fathom", "Furlong", "Fermi (fm)", "Foot (ft)", "Inch (in)", "League (int'l)", "League (UK)", "Light year (LY)", "Micrometer (mu-m)", "Mil", "Millimeter (mm)", "Nanometer (nm)", "Mile (int'l nautical)", "Mile (UK nautical)", "Mile (US nautical)", "Mile (US statute)", "Parsec", "Pica (printer)", "Picometer (pm)", "Point (pt)", "Rod", "Yard (yd)");
	$factor["Length"] = Array(1, 1E-10, 1.49598E11, .000254, .01, 1000, 1.143, 4.2323E-03, 1.8288, 201.168, 1E-15, .3048, .0254, 5556, 5556, 9.46055E+15, .000001, .0000254, .001, 1E-9, 1852, 1853.184, 1852, 1609.344, 3.08374E+16, 4.217518E-03, 1E-12, .0003514598, 5.0292, .9144);

	$unit["Light"] = Array("Lumen/sq.meter (Lu/m^2)", "Lumen/sq.centimeter", "Lumen/sq.foot", "Foot-candle (ft-cdl)", "Foot-lambert", "Candela/sq.meter", "Candela/sq.centimeter", "Lux (lux)", "Phot");
	$factor["Light"] = Array(1, 10000, 10.76391, 10.76391, 10.76391, 3.14159250538575, 31415.9250538576, 1, 10000);

	$unit["Mass"] = Array("Kilogram (kgr)", "Gram (gr)", "Milligram (mgr)", "Microgram (mu-gr)", "Carat (metric)(ct)", "Hundredweight (long)", "Hundredweight (short)", "Pound mass (lbm)", "Pound mass (troy)", "Ounce mass (ozm)", "Ounce mass (troy)", "Slug", "Ton (assay)", "Ton (long)", "Ton (short)", "Ton (metric)", "Tonne");
	$uri_unit["Mass"] = Array(
						"http://dbpedia.org/class/yago/Kilogram113724582" => 1, 
						"http://dbpedia.org/class/yago/Gram113723712" => .001, 
						"http://dbpedia.org/class/yago/Milligram113723061" => 1e-6, 
						"http://dbpedia.org/class/yago/Microgram113722929" => .000000001, 
						"http://dbpedia.org/class/yago/Carat113723577" => .0002, 
						"http://dbpedia.org/class/yago/Hundredweight113721003" => 50.80235, 
						"http://dbpedia.org/class/yago/Hundredweight113721177" => 45.35924, 
						"http://dbpedia.org/class/yago/Pound113720096" => 45.35924, 
						"Pound mass (troy)" => .3732417, 
						"http://dbpedia.org/class/yago/Ounce113719922" => .02834952, 
						"http://dbpedia.org/class/yago/Ounce113722522" => .03110348, 
						"Slug" => 14.5939, 
						"Ton (assay)" => .02916667, 
						"http://dbpedia.org/class/yago/LongTon113721387" => 1016.047, 
						"http://dbpedia.org/class/yago/ShortTon113721529" => 907.1847, 
						"http://dbpedia.org/class/yago/MetricTon113725588" => 1000, 
						"Tonne" => 1000
					);
	
	$factor["Mass"] = Array(1, .001, 1e-6, .000000001, .0002, 50.80235, 45.35924, .4535924, .3732417, .02834952, .03110348, 14.5939, .02916667, 1016.047, 907.1847, 1000, 1000);

	$unit["Mass Flow"] = Array("Kilogram/second (kgr/sec)", "Pound mass/sec (lbm/sec)", "Pound mass/min (lbm/min)");
	$factor["Mass Flow"] = Array(1, .4535924, .007559873);

	$unit["Density & Mass capacity"] = Array("Kilogram/cub.meter", "Grain/galon", "Grams/cm^3 (gr/cc)", "Pound mass/cubic foot", "Pound mass/cubic-inch", "Ounces/gallon (UK,liq)", "Ounces/gallon (US,liq)", "Ounces (mass)/inch", "Pound mass/gal (UK,liq)", "Pound mass/gal (US,liq)", "Slug/cubic foot", "Tons (long,mass)/cub.yard");
	$factor["Density & Mass capacity"] = Array(1, .01711806, 1000, 16.01846, 27679.91, 6.236027, 7.489152, 1729.994, 99.77644, 119.8264, 515.379, 1328.939);

	$unit["Power"] = Array("Watt (W)", "Kilowatt (kW)", "Megawatt (MW)", "Milliwatt (mW)", "BTU (SI)/hour", "BTU (thermo)/second", "BTU (thermo)/minute", "BTU (thermo)/hour", "Calorie (thermo)/second", "Calorie (thermo)/minute", "Erg/second", "Foot-pound force/hour", "Foot-pound force/minute", "Foot-pound force/second", "Horsepower(550 ft lbf/s)", "Horsepower (electric)", "Horsepower (boiler)", "Horsepower (metric)", "Horsepower (UK)", "Kilocalorie (thermo)/min", "Kilocalorie (thermo)/sec");
	$factor["Power"] = Array(1, 1000, 1000000, .001, .2930667, 1054.35, 17.5725, .2928751, 4.184, 6.973333E-02, .0000001, .0003766161, .02259697, 1.355818, 745.7, 746, 9809.5, 735.499, 745.7, 69.7333, 4184);

	$unit["Pressure & Stress"] = Array("Newton/sq.meter", "Atmosphere (normal)", "Atmosphere (techinical)", "Bar", "Centimeter mercury(cmHg)", "Centimeter water (4'C)", "Decibar", "Kgr force/sq.centimeter", "Kgr force/sq.meter", "Kip/square inch", "Millibar", "Millimeter mercury(mmHg)", "Pascal (Pa)", "Kilopascal (kPa)", "Megapascal (Mpa)", "Poundal/sq.foot", "Pound-force/sq.foot", "Pound-force/sq.inch (psi)", "Torr (mmHg,0'C)");
	$factor["Pressure & Stress"] = Array(1, 101325, 98066.5, 100000, 1333.22, 98.0638, 10000, 98066.5, 9.80665, 6894757, 100, 133.3224, 1, 1000, 1000000, 47.88026, 47.88026, 6894.757, 133.322);

	// !!! Caution: Temperature requires an increment as well as a multiplying factor
	// !!! and that's why it's handled differently
	// !!! Be VERY careful in how you change this behavior
	$property[14] = "Temperature";
	$unit[14] = Array("Degrees Celsius ('C)", "Degrees Fahrenheit ('F)", "Degrees Kelvin ('K)", "Degrees Rankine ('R)");
	$factor[14] = Array(1,  0.555555555555, 1, 0.555555555555);
	$tempIncrement = Array(0, -32, -273.15, -491.67);

	$unit["Time"] = Array("Second (sec)", "Day (mean solar)", "Day (sidereal)", "Hour (mean solar)", "Hour (sidereal)", "Minute (mean solar)", "Minute (sidereal)", "Month (mean calendar)", "Second (sidereal)", "Year (calendar)", "Year (tropical)", "Year (sidereal)");
	$factor["Time"] = Array(1, 8.640E4, 86164.09, 3600, 3590.17, 60, 60, 2628000, .9972696, 31536000, 31556930, 31558150);

	$unit["Velocity & Speed"] = Array("Meter/second (m/sec)", "Foot/minute (ft/min)", "Foot/second (ft/sec)", "Kilometer/hour (kph)", "Knot (int'l)", "Mile (US)/hour (mph)", "Mile (nautical)/hour", "Mile (US)/minute", "Mile (US)/second", "Speed of light (c)", "Mach (STP)(a)");
	$factor["Velocity & Speed"] = Array(1, 5.08E-03, .3048, .2777778, .5144444, .44707, .514444, 26.8224, 1609.344, 299792458, 340.0068750);

	$unit["Viscosity"] = Array("Newton-second/meter", "Centipoise", "Centistoke", "Sq.foot/second", "Poise", "Poundal-second/sq.foot", "Pound mass/foot-second", "Pound force-second/sq.foot", "Rhe", "Slug/foot-second", "Stoke");
	$factor["Viscosity"] = Array(1, .001, .000001, 9.290304E-02, .1, 1.488164, 1.488164, 47.88026, 10, 47.88026, .0001);

	$unit["Volume & Capacity"] = Array("Cubic Meter (m^3)", "Cubic centimeter", "Cubic millimeter", "Acre-foot", "Barrel (oil)", "Board foot", "Bushel (US)", "Cup", "Fluid ounce (US)", "Cubic foot", "Gallon (UK)", "Gallon (US,dry)", "Gallon (US,liq)", "Gill (UK)", "Gill (US)", "Cubic inch (in^3)", "Liter (new)", "Liter (old)", "Ounce (UK,fluid)", "Ounce (US,fluid)", "Peck (US)", "Pint (US,dry)", "Pint (US,liq)", "Quart (US,dry)", "Quart (US,liq)", "Stere", "Tablespoon", "Teaspoon", "Ton (register)", "Cubic yard");
	$factor["Volume & Capacity"] = Array(1, .000001, .000000001, 1233.482, .1589873, .002359737, .03523907, .0002365882, .00002957353, .02831685, .004546087, .004404884, .003785412, .0001420652, .0001182941, .00001638706, .001, .001000028, .00002841305, .00002957353, 8.8097680E-03, .0005506105, 4.7317650E-04, .001101221, 9.46353E-04, 1, .00001478676, .000004928922, 2.831685, .7645549);
	
	
	public function convert ($type, $value, $convert_from, $convert_to) {
		return $value * ($this->$uri_unit[$type][$convert_to]/$this->$uri_unit[$type][$convert_from]);
	}	
}
?>