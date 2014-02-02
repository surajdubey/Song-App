<?php
/* Author : Ashish Gaikwad & Suraj Dubey
* 2014
*/
?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<title>SongApp : Music App by Ashish and Suraj </title>

	<style type="text/css">
	* {padding: 0; margin: 0; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}
	body {color: #333}
	.ag-container {max-width: 800px; margin: 0 auto; font-family: "Roboto", sans-serif;}
	input {display: block; width: 100%; margin: 0.8em 0; padding: 10px; font-size: 1.4em}
	button {margin-left: 3px ; padding: 7px 15px; background-color: #eee; font-size: 1.2em; border: 1px solid silver; border-radius: 6px}
	button:hover {background-color: #ddd}
	header {margin: 1em 10px}
	h4 {display: inline-block;}
	.ag-brand {display: inline-block; font-size: 3rem; color: #5856d6}
	.ag-sub {color: black; color: violet; display: inline-block;}
	.ag-pull {float: right; clear: left}
	.ag-add {margin-bottom: 10px;}
	a {text-decoration: none; color: #1d62f0; cursor: pointer;}
	.ag-result-list {list-style: none; margin: 3em 0;}
	.ag-item {padding: 15px 10px; border-radius: 2px}
	.ag-item:hover {background-color: #eed}
	.ag-info {margin: 1em 0; text-align: center}

	footer {margin: 2em 3px; }
	</style>
</head>
<body ng-app="songapp">
<div ng-controller="songappCtrl" class="ag-container">
	<header>
		<h1 class="ag-brand">SongApp</h1>
		<sup class="ag-sub">Smarter way to enjoy free music</sup>
		<p> <a href="/submit.php" class="ag-pull ag-add">+Add</a> </p>
	</header>

	<input type="text" ng-model="searchText" ag-enter="search()" placeholder="Search a song...">
	<button ng-click="search()">Search</button>

	<p class="ag-info">{{info}}</p>
	<div class="ag-info">
		<div id="ytplayer"></div>
	</div>

	<ul class="ag-result-list">
		<li ng-repeat="s in songList" class="ag-item">
			<h4>{{s.name}}</h4>
			<small class="ag-pull">{{s.artists}}</small>
			<p>
			<strong>{{s.album}}</strong> <em>{{s.year}}</em>

			<span class="ag-pull"> <a href="{{s.audio}}"> Audio </a> &emsp;
			<a ng-click="showVideo(s.video)"> Video </a> </span>
			</p>
		</li>
	</ul>

	<footer>
		<p>Made in Mumbai with <abbr title="love">♥</abbr> at <b> GDG Hackathon </b> by <a href="//twitter.com/iHackLean"> Ashish </a> and <a href="//twitter.com/thesurajdubey"> Suraj </a></p>
	</footer>
</div>

<script src="angular.min.js"></script>
<script>
	var player = null;
	
	angular.module('songapp', []).controller('songappCtrl', function($scope, $http){
		$scope.search = function(){
			console.log('Searching ' + $scope.searchText)
			if(!$scope.searchText.length) return
			$http({
        		url: '/search.php',
        		method: "POST",
        		data: {'q': $scope.searchText},
        		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
    		}).then(function(result){
    			$scope.songList = result.data
    			console.log(result.data)
    			if($scope.songList.length) $scope.info = ''
    			else $scope.info = 'Looks like "' + $scope.searchText + '" is not here yet! You can help us by adding this :-)'
			})
		}

		$scope.showVideo = function(video) {
			if(player==null) player = new YT.Player('ytplayer', {
      			height: '390',
      			width: '640',
      			videoId: video
    		})
			else player.loadVideoById(video)
		}
	}).directive('agEnter', function(){
    return function(scope, elem, attr){
		elem.bind("keydown keypress", function(event){
			if(event.which === 13){
				scope.$apply(function(){
					scope.$eval(attr.agEnter)
				})
			}
		})
	}
})
</script>

<script>
  // Load the IFrame Player API code asynchronously.
  var tag = document.createElement('script');
  tag.src = "https://www.youtube.com/player_api";
  var firstScriptTag = document.getElementsByTagName('script')[0];
  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

  // Replace the 'ytplayer' element with an <iframe> and
  // YouTube player after the API code downloads.
</script>

</body>
</html>
