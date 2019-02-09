<?php

include('../utilities/database.php');
ini_set('display_errors', 1);
$db = new Database();
$db->connect();

//$id = isset($_GET['id']) ? " where id=".$_GET['id'] : '';
//$sql = "SELECT * FROM `customer`".$id; 

//$query = "insert into GENRES (5,'Rock')";
//$insertResult = $db->insertData($query);
//echo "$insertResult";
$sql = "SELECT DISTINCT ALBUM.ID,ALBUM.ALBUM_NAME,GENRES.GENRES_NAME,ALBUM.ALBUM_POSTER,ALBUM.ALBUM_RELEASED_DATE "
        . "FROM ALBUM,ARTIST,SONG,GENRES "
        . "WHERE ALBUM.ID=SONG.ALBUM_ID AND SONG.ARTIST_ID=ARTIST.ID AND GENRES.ID=ALBUM.GENRES_ID";
$result = $db->getData($sql);
$response["albums"] = array();

$listOfArtist = array();
if ($result->num_rows > 0) 
{   
    while ($row = $result->fetch_assoc()) 
    {     
        $album = array();
//        $album["album_id"] = $row["ID"];
        $album["album_name"] = $row["ALBUM_NAME"];
//        $album["artist_name"] = $row["ARTIST_NAME"];
        $album["genres_name"] = $row["GENRES_NAME"];
        $album["released_date"] = $row["ALBUM_RELEASED_DATE"];
        $album["album_poster"] = "http://www.".$row["ALBUM_POSTER"];
        $album['artists'] = array();
        $album['song_details'] = array();
        
        $sql2 = "SELECT * FROM SONG where SONG.ALBUM_ID =".$row["ID"];
        $result2 = $db->getData($sql2);
        while ($row2 = $result2->fetch_assoc())
        {   
            $album['song_details'][] = array(
            'song_name' => $row2["SONG_NAME"],
            'song_duration'=> $row2["SONG_DURATION"],
            'song_location' => "http://www.".$row2["SONG_LOCATION"]);

            $sql3 = "SELECT DISTINCT ARTIST_NAME FROM ARTIST WHERE ARTIST.ID =".$row2["ARTIST_ID"];
            $result3 = $db ->getData($sql3);
            while ($row3 = $result3->fetch_assoc())
            {       
                if(!in_array($row3["ARTIST_NAME"],$listOfArtist))
                {   $album['artists'][] = array(
                    'r_name' => $row3["ARTIST_NAME"]);
                    array_push($listOfArtist, $row3["ARTIST_NAME"]);
                }
            }
        }
        array_push($response["albums"], $album);
    }
    $response["success"] = 1;
} 
else 
{
    $response["success"] = 0;
    $response["message"] = 'No records found.';
}
header("HTTP/1.1 200 OK");
header('Content-Type: application/json');



function querySort ($x, $y) {
    return strcmp($x['genres_name'], $y['genres_name']);
}
usort($response['albums'],'querySort');
//print_r($response);
echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

