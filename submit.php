<?php

/**
* Author : Ashish Gaikwad & Suraj Dubey
* 2014
* basic lass for form submission handling
*/
class FormSubmit
{
	
	function __construct( )
	{
		$f = array_map(htmlspecialchars, (array) json_decode(file_get_contents("php://input")) );
		if(!empty($f['submit']) and $f['submit']=='submit'){
		if( empty($f['name']) or empty($f['album']) or empty($f['artists']) or empty($f['year']) or empty($f['audio']) or empty($f['video']) )
		{
			// all fields are required
		}

		else {
			// fill data into base
			$db = null;
			try{
				$db = new PDO('mysql:host=localhost;dbname=song;charset=utf8', 'deo', 'ng');
			}catch(Exception $e){
				die( $e->getMessage() );
			}

			$db->prepare("INSERT INTO song_info (name, album, artists, year, audio, video, live) VALUES (:name, :album, :artists, :year, :audio, :video, 0)")
			->execute( array( ':name'=>$f['name'], ':album'=>$f['album'], ':artists'=>$f['artists'], ':year'=>$f['year'], ':audio'=>$f['audio'], ':video'=>$f['video'] ) );

			die( json_encode(array('status'=>'success')) );
			}
		}
	}
}

$lol = new FormSubmit();

?><!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Submit your song info to SongApp : Music App by Ashish and Suraj </title>

	<style type="text/css">
	* {padding: 0; margin: 0; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}
	body {color: #222}
	.ag-container {max-width: 800px; margin: 0 auto; font-family: "Roboto", sans-serif}
	input {display: block; width: 100%; margin: 0.8em 0; padding: 10px; font-size: 1.3em}
	fieldset {border: 0px solid white}
	button {padding: 7px 15px; background-color: #eee; font-size: 1.2em; border: 1px solid silver; border-radius: 6px}
	button:hover {background-color: #ddd}
	header {margin: 1em 0}
	a {text-decoration: none; color: inherit;}
	.ag-brand {display: inline-block; font-size: 3rem; color: #5856d6}
	.ag-sub {color: black; color: violet; display: inline-block;}

	footer {margin: 3em 0; }
	</style>
</head>
<body ng-app="songapp">
<div class="ag-container" ng-controller="submitCtrl">
	<header>
		<h1 class="ag-brand"><a href="/"> SongApp </a></h1>
		<sup class="ag-sub">Smarter way to enjoy free music</sup>
	</header>

	<form action="http://songapp.atwebpages.com/submit.php" method="get">
		<fieldset>
			<legend>Submit Your Song's Info </legend>
			<input type="text" placeholder="Song name" ng-model="name" name="name" required>
			<input type="text" placeholder="Album" ng-model="album" name="album" required>
			<input type="text" placeholder="Artists" ng-model="artists" name="artists" required>
			<input type="numbers" placeholder="Year of release" ng-model="year" name="year" required>
			<input type="url" placeholder="Audio url" ng-model="audio" name="audio" required>
			<input type="text" placeholder="YouTube video ID" ng-model="video" name="video" required>

			<button ng-click="upload()" type="button"> Submit </button>
			<!--button style="display:none" type="submit" onclick="return false">.</button-->
		</fieldset>
	</form>

	<footer>
		<small>{{info}}</small>
		<p>Made in Mumbai with <abbr title="love">♥</abbr> at <b> GDG Hackathon </b> by <a href="//twitter.com/iHackLean"> Ashish </a> and <a href="//twitter.com/thesurajdubey"> Suraj </a></p>
	</footer>
</div>

<script src="angular.min.js"></script>
<script>
	angular.module('songapp', []).controller('submitCtrl', function($scope, $http){
		$scope.upload = function(){
			if(!$scope.name.length) return
			songData = {'submit':'submit' , 'name':$scope.name, 'album':$scope.album, 'artists':$scope.artists, 'year':$scope.year, 'audio':$scope.audio, 'video':$scope.video }
		//console.log(songData)
		$http({
			url: '/submit.php',
       		method: "POST",
        	data: songData,
        	headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).then(function(result){
			//console.log(result.data)
			if(result.data.status == 'success') $scope.info = 'Saved'
			else $scope.info = 'Unable to save'
		})
		return false
	}
	})
</script>
</body>
</html>
