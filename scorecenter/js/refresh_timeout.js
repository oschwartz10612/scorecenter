/**
 * Tournament Score Center (TSC) - Tournament scoring web application.
 * Copyright (C) 2019  Preston Frazier
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see http://www.gnu.org/licenses/.
 *    
 * @package: Tournament Score Center (TSC) - Tournament scoring web application.
 * @version: 1.19.1, 01.13.2019 
 * @author: Preston Frazier http://scorecenter.prestonsproductions.com/index.php 
 * @license: http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */
 
 // Refresh PHP Session Timeout. Current Timeout Set to 24 minutes. 
 // Application will timeout if user closes browser for more then 24 minutes or application is open for over 8 hours
 
 var time = 600000;
 window.setInterval(function() {
	 $.ajax({
		cache: false,
		type: "GET" ,
		url: "functions/session_refresh.php",
		success: function(data) {

		}
	 });
 }, time);