<?php

$action = (!empty($_POST['btn_submit']) && ($_POST['btn_submit'] === 'Save')) ? 'save_movie' 
                                                                              : 'show_form';

switch($action){
    
    case 'save_movie':
    
        try {

            $connection = new MongoClient($_ENV['OPENSHIFT_MONGODB_DB_URL']);
            $database   = $connection->selectDB('tmdb');
            $collection = $database->selectCollection('movies');
            
            $movies               = array();
            $movies['name']      = $_POST['name'];
			$movies['release_date']      = new MongoDate(strtotime($_POST['release_date']));
            $movies['storyline']    = $_POST['storyline'];
			$movies['duration']    = (int) $_POST['duration'];
			$movies['gerne']    = $_POST['gerne'];
            $movies['actors'] = array()
			$movies['actors']['hero'] = new MongoId($_POST['hero_id']);
            $collection->insert($movies);
        
        } catch(MongoConnectionException $e) {

            die("Failed to connect to database ".$e->getMessage());
        }

        catch(MongoException $e) {

            $die('Failed to insert data '.$e->getMessage());
        }
        break;
        
    case 'show_form':
		try {

            $connection = new MongoClient($_ENV['OPENSHIFT_MONGODB_DB_URL']);
            $database   = $connection->selectDB('tmdb');
            $collection = $database->selectCollection('actors');
            
            $cursor = $collection->find(array(), $fields=array('name', '_id')); 
        
        } catch(MongoConnectionException $e) {

            die("Failed to connect to database ".$e->getMessage());
        }

        catch(MongoException $e) {

            $die('Failed to insert data '.$e->getMessage());
        }
    default:
}
?>

<!DOCTYPE html >

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
    	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" href="style.css"/>
        <title>Data entry movies</title>

    </head>

    <body>
        <div id="contentarea">
            <div id="innercontentarea">
                <h1>Blog Post Creator</h1>
                
                <?php if ($action === 'show_form'): ?>
                
                <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                    <h3>Name</h3>
                    <p><input type="text" name="name" id="title/"></p>
					<h3>Release date</h3>
                    <p><input type="text" name="release_date" id="title/"></p>
                    <h3>Storyline</h3>
                    <textarea name="storyline" rows="5"></textarea>
					<h3>Duration in mins</h3>
                    <p><input type="text" name="duration" id="title/"></p>
					<h3>Gerne</h3>
                    <p><input type="text" name="gerne" id="title/"></p>
					<h3>Hero</h3>
                    <select name="hero_id">
						 <?php while ($cursor->hasNext()):
							$actor = $cursor->getNext(); ?>
							<option value="<?php echo $actor['_id']; ?>"><?php echo $actor['name']; ?></option>
						<?php endwhile; ?>
					</select>
                    <p><input type="submit" name="btn_submit" value="Save"/></p>
                </form>
                <?php else: ?>
                <p>
                    Movie saved. _id: <?php echo $movies['_id'];?>.
                    <a href="<?php echo $_SERVER['PHP_SELF'];?>">Do you want to insert another one?</a>
                </p>
                <?php endif;?>
                
            </div>
        </div>
    </body>
</html>