<?php
  
  require 'start.php';
  
  $q = json_decode(file_get_contents("php://input"))->q;
  if(!empty($q))
  { 
    $conn = $db->prepare( "SELECT name, album, artists, year, audio, video FROM song_info WHERE name LIKE :name OR album LIKE :name OR artists LIKE :name" );
    $conn->execute( array(':name'=>'%' . $q . "%") );
    echo json_encode( $conn->fetchAll( PDO::FETCH_ASSOC) );
  }
