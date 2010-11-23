<?php
/**
 * 
 */
class App_Timezone {

    private static $_tzs = null;
	
	/**
	* Returns the list of supported timezones mapped to their display names
	*/
	public static function getTimezones()
	{
		if (null === self::$_tzs) {
            self::$_tzs = self::buildMap();
        }
				
		return self::$_tzs;		
	}
	
	/**
	* Returns a user-friendly display name for the given timezone
	*/
	public static function getDisplayName($identifier)
	{
		$tzs = self::getTimezones();
		if (isset($tzs[$identifier])){
			return $tzs[$identifier];
		}
		return '';	
	}
	
	/**
	* Builds the internal mapping of display names to timezone identifiers
	*/
	protected static function buildMap()
	{
		$tz = array();
		
		$tz['Kwajalein'] = '(GMT-12:00) International Date Line West';
		$tz['Pacific/Midway'] = '(GMT-11:00) Midway Island';
		$tz['Pacific/Samoa'] = '(GMT-11:00) Samoa';
		$tz['Pacific/Honolulu'] = '(GMT-10:00) Hawaii';
		$tz['America/Anchorage'] = '(GMT-09:00) Alaska';
		$tz['America/Los_Angeles'] = '(GMT-08:00) Pacific Time (US & Canada)';
		$tz['America/Tijuana'] = '(GMT-08:00) Tijuana, Baja California';
		$tz['America/Denver'] = '(GMT-07:00) Mountain Time (US & Canada)';
		$tz['America/Chihuahua'] = '(GMT-07:00) Chihuahua';
		$tz['America/Mazatlan'] = '(GMT-07:00) Mazatlan';
		$tz['America/Phoenix'] = '(GMT-07:00) Arizona';
		$tz['Canada/East-Saskatchewan'] = '(GMT-06:00) Saskatchewan';
		$tz['America/Tegucigalpa'] = '(GMT-06:00) Central America';
		$tz['America/Chicago'] = '(GMT-06:00) Central Time (US & Canada)';
		$tz['America/Mexico_City'] = '(GMT-06:00) Mexico City';
		$tz['America/Monterrey'] = '(GMT-06:00) Monterrey';
		$tz['America/New_York'] = '(GMT-05:00) Eastern Time (US & Canada)';
		$tz['America/Bogota'] = '(GMT-05:00) Bogota';
		$tz['America/Lima'] = '(GMT-05:00) Lima';
		$tz['America/Rio_Branco'] = '(GMT-05:00) Rio Branco';
		$tz['America/Indiana/Indianapolis'] = '(GMT-05:00) Indiana (East)';
		$tz['America/Caracas'] = '(GMT-04:30) Caracas';
		$tz['Canada/Atlantic'] = '(GMT-04:00) Atlantic Time (Canada)';
		$tz['America/Manaus'] = '(GMT-04:00) Manaus';
		$tz['America/Santiago'] = '(GMT-04:00) Santiago';
		$tz['America/La_Paz'] = '(GMT-04:00) La Paz';
		$tz['Canada/Newfoundland'] = '(GMT-03:30) Newfoundland';
		$tz['America/Argentina/Buenos_Aires'] = '(GMT-03:00) Buenos Aires';
		$tz['America/Sao_Paulo'] = '(GMT-03:00) Brasilia';
		$tz['America/Godthab'] = '(GMT-03:00) Greenland';
		$tz['America/Montevideo'] = '(GMT-03:00) Montevideo';
		$tz['Atlantic/South_Georgia'] = '(GMT-02:00) Mid-Atlantic';
		$tz['Atlantic/Azores'] = '(GMT-01:00) Azores';
		$tz['Atlantic/Cape_Verde'] = '(GMT-01:00) Cape Verde Is.';
		$tz['Europe/Dublin'] = '(GMT) Dublin';
		$tz['Europe/Lisbon'] = '(GMT) Lisbon';
		$tz['Europe/London'] = '(GMT) London';
		$tz['Africa/Monrovia'] = '(GMT) Monrovia';
		$tz['Atlantic/Reykjavik'] = '(GMT) Reykjavik';
		$tz['Africa/Casablanca'] = '(GMT) Casablanca';
		$tz['Europe/Belgrade'] = '(GMT+01:00) Belgrade';
		$tz['Europe/Bratislava'] = '(GMT+01:00) Bratislava';
		$tz['Europe/Budapest'] = '(GMT+01:00) Budapest';
		$tz['Europe/Ljubljana'] = '(GMT+01:00) Ljubljana';
		$tz['Europe/Prague'] = '(GMT+01:00) Prague';
		$tz['Europe/Sarajevo'] = '(GMT+01:00) Sarajevo';
		$tz['Europe/Skopje'] = '(GMT+01:00) Skopje';
		$tz['Europe/Warsaw'] = '(GMT+01:00) Warsaw';
		$tz['Europe/Zagreb'] = '(GMT+01:00) Zagreb';
		$tz['Europe/Brussels'] = '(GMT+01:00) Brussels';
		$tz['Europe/Copenhagen'] = '(GMT+01:00) Copenhagen';
		$tz['Europe/Madrid'] = '(GMT+01:00) Madrid';
		$tz['Europe/Paris'] = '(GMT+01:00) Paris';
		$tz['Africa/Algiers'] = '(GMT+01:00) West Central Africa';
		$tz['Europe/Amsterdam'] = '(GMT+01:00) Amsterdam';
		$tz['Europe/Berlin'] = '(GMT+01:00) Berlin';
		$tz['Europe/Rome'] = '(GMT+01:00) Rome';
		$tz['Europe/Stockholm'] = '(GMT+01:00) Stockholm';
		$tz['Europe/Vienna'] = '(GMT+01:00) Vienna';
		$tz['Europe/Minsk'] = '(GMT+02:00) Minsk';
		$tz['Africa/Cairo'] = '(GMT+02:00) Cairo';
		$tz['Europe/Helsinki'] = '(GMT+02:00) Helsinki';
		$tz['Europe/Riga'] = '(GMT+02:00) Riga';
		$tz['Europe/Sofia'] = '(GMT+02:00) Sofia';
		$tz['Europe/Tallinn'] = '(GMT+02:00) Tallinn';
		$tz['Europe/Vilnius'] = '(GMT+02:00) Vilnius';
		$tz['Europe/Athens'] = '(GMT+02:00) Athens';
		$tz['Europe/Bucharest'] = '(GMT+02:00) Bucharest';
		$tz['Europe/Istanbul'] = '(GMT+02:00) Istanbul';
		$tz['Asia/Jerusalem'] = '(GMT+02:00) Jerusalem';
		$tz['Asia/Amman'] = '(GMT+02:00) Amman';
		$tz['Asia/Beirut'] = '(GMT+02:00) Beirut';
		$tz['Africa/Windhoek'] = '(GMT+02:00) Windhoek';
		$tz['Africa/Harare'] = '(GMT+02:00) Harare';
		$tz['Asia/Kuwait'] = '(GMT+03:00) Kuwait';
		$tz['Asia/Riyadh'] = '(GMT+03:00) Riyadh';
		$tz['Asia/Baghdad'] = '(GMT+03:00) Baghdad';
		$tz['Africa/Nairobi'] = '(GMT+03:00) Nairobi';
		$tz['Asia/Tbilisi'] = '(GMT+03:00) Tbilisi';
		$tz['Europe/Moscow'] = '(GMT+03:00) Moscow';
		$tz['Europe/Volgograd'] = '(GMT+03:00) Volgograd';
		$tz['Asia/Tehran'] = '(GMT+03:30) Tehran';
		$tz['Asia/Muscat'] = '(GMT+04:00) Muscat';
		$tz['Asia/Baku'] = '(GMT+04:00) Baku';
		$tz['Asia/Yerevan'] = '(GMT+04:00) Yerevan';
		$tz['Asia/Yekaterinburg'] = '(GMT+05:00) Ekaterinburg';
		$tz['Asia/Karachi'] = '(GMT+05:00) Karachi';
		$tz['Asia/Tashkent'] = '(GMT+05:00) Tashkent';
		$tz['Asia/Kolkata'] = '(GMT+05:30) Calcutta';
		$tz['Asia/Colombo'] = '(GMT+05:30) Sri Jayawardenepura';
		$tz['Asia/Katmandu'] = '(GMT+05:45) Kathmandu';
		$tz['Asia/Dhaka'] = '(GMT+06:00) Dhaka';
		$tz['Asia/Almaty'] = '(GMT+06:00) Almaty';
		$tz['Asia/Novosibirsk'] = '(GMT+06:00) Novosibirsk';
		$tz['Asia/Rangoon'] = '(GMT+06:30) Yangon (Rangoon)';
		$tz['Asia/Krasnoyarsk'] = '(GMT+07:00) Krasnoyarsk';
		$tz['Asia/Bangkok'] = '(GMT+07:00) Bangkok';
		$tz['Asia/Jakarta'] = '(GMT+07:00) Jakarta';
		$tz['Asia/Beijing'] = '(GMT+08:00) Beijing';
		$tz['Asia/Chongqing'] = '(GMT+08:00) Chongqing';
		$tz['Asia/Hong_Kong'] = '(GMT+08:00) Hong Kong';
		$tz['Asia/Urumqi'] = '(GMT+08:00) Urumqi';
		$tz['Asia/Irkutsk'] = '(GMT+08:00) Irkutsk';
		$tz['Asia/Ulaanbaatar'] = '(GMT+08:00) Ulaan Bataar';
		$tz['Asia/Kuala_Lumpur'] = '(GMT+08:00) Kuala Lumpur';
		$tz['Asia/Singapore'] = '(GMT+08:00) Singapore';
		$tz['Asia/Taipei'] = '(GMT+08:00) Taipei';
		$tz['Australia/Perth'] = '(GMT+08:00) Perth';
		$tz['Asia/Seoul'] = '(GMT+09:00) Seoul';
		$tz['Asia/Tokyo'] = '(GMT+09:00) Tokyo';
		$tz['Asia/Yakutsk'] = '(GMT+09:00) Yakutsk';
		$tz['Australia/Darwin'] = '(GMT+09:30) Darwin';
		$tz['Australia/Adelaide'] = '(GMT+09:30) Adelaide';
		$tz['Australia/Canberra'] = '(GMT+10:00) Canberra';
		$tz['Australia/Melbourne'] = '(GMT+10:00) Melbourne';
		$tz['Australia/Sydney'] = '(GMT+10:00) Sydney';
		$tz['Australia/Brisbane'] = '(GMT+10:00) Brisbane';
		$tz['Australia/Hobart'] = '(GMT+10:00) Hobart';
		$tz['Asia/Vladivostok'] = '(GMT+10:00) Vladivostok';
		$tz['Pacific/Guam'] = '(GMT+10:00) Guam';
		$tz['Pacific/Port_Moresby'] = '(GMT+10:00) Port Moresby';
		$tz['Asia/Magadan'] = '(GMT+11:00) Magadan';
		$tz['Pacific/Fiji'] = '(GMT+12:00) Fiji';
		$tz['Asia/Kamchatka'] = '(GMT+12:00) Kamchatka';
		$tz['Pacific/Auckland'] = '(GMT+12:00) Auckland';
		$tz['Pacific/Tongatapu'] = '(GMT+13:00) Nuku’alofa';
			
		return $tz;		
		
	}


}