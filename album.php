<?php 

    include("includes/includedFiles.php"); 

    (isset($_GET['id'])) ? $albumId = $_GET['id'] : header("Location: index.php");

    $album = new Album($con, $albumId);
    $artist = $album->getArtist();
    
?>

<div class="entityInfo">
    <div class="leftSection">
        <img src="<?php echo $album->getArtworkPath(); ?>" alt="<?php echo $album->getTitle(); ?>">
    </div>

    <div class="rightSection">
        <h2><?php echo $album->getTitle(); ?></h2>
        <p>By <?php echo $artist->getName(); ?></p>
        <p><?php echo $album->getNumberOfSongs(); ?> songs</p>
    </div>
</div>

<div class="tracklistContainer">
    <ul class="tracklist">
        <?php
            $songIdArray = $album->getSongIds();

            $i = 1;
            foreach($songIdArray as $songId) {  
                $albumSong = new Song($con, $songId);
                $albumArtist = $albumSong->getArtist();

                echo "
                    <li class='tracklistRow'>
                        <div class='trackCount'>
                            <img class='play' src='assets/images/icons/play-white.png' onclick='setTrack(\"" . $albumSong->getId() . "\", pagePlaylist, true)'>
                            <span class='trackNumber'>$i</span>
                        </div>

                        <div class='trackInfo'>
                            <span class='trackName'>" . $albumSong->getTitle() . "</span>
                            <span class='artistName'>" . $albumArtist->getName() . "</span>
                        </div>

                        <div class='trackOptions'>
                            <input type='hidden' class='songId' value='" . $albumSong->getId() . "'>
                            <img class='optionsButton' src='assets/images/icons/more.png' onclick='showOptionsMenu(this)'>
                        </div>

                        <div class='trackDuration'>
                            <span class='duration'>" . $albumSong->getDuration() . "</span>
                        </div>
                    </li>
                ";
                $i++;
            }
        ?>

        <script>
            var pageSongIds = '<?php echo json_encode($songIdArray); ?>';
            pagePlaylist = JSON.parse(pageSongIds);
        </script>
    </ul>
</div>

<nav class="optionsMenu">
    <input type="hidden" class="songId">
    <?php 
        echo Playlist::getPlaylistDropdown($con, $userLoggedIn->getUsername());
    ?>
</nav>