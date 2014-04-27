
<html >
    <head>
    	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" href="style.css"/>
        <title>Data entry movies</title>

    </head>

    <body>
        <div id="contentarea">
            <div id="innercontentarea">
                <h1>Blog Post Creator</h1>
                
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
						 <?php 
						 try {

            $connection = new MongoClient($_ENV['OPENSHIFT_MONGODB_DB_URL']);
            $database   = $connection->selectDB('tmdb');
            $collection = $database->selectCollection('actors');
            
            $cursor = $collection->find(array(), array('name', '_id')); 
        
        } catch(MongoConnectionException $e) {

            die("Failed to connect to database ".$e->getMessage());
			echo $e->getMessage();
        }

        catch(MongoException $e) {

            $die('Failed to insert data '.$e->getMessage());
			echo $e->getMessage();
        }
		catch (Exception $e)
		{
			echo $e->getMessage();
		}
						 while ($cursor->hasNext()):
							$actor = $cursor->getNext(); ?>
							<option value="<?php echo $actor['_id']; ?>"><?php echo $actor['name']; ?></option>
						<?php endwhile; ?>
					</select>
                    <p><input type="submit" name="btn_submit" value="Save"/></p>
                </form>
                
            </div>
        </div>
    </body>
</html>