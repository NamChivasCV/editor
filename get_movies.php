<?php
echo "Running.....";
date_default_timezone_set('Asia/Ho_Chi_Minh');

for ($i = 1; $i <= 421; $i++) {

  $listOfPage = json_decode(file_get_contents("https://ophim1.com/danh-sach/phim-moi-cap-nhat?page={$i}"));
  foreach ($listOfPage->items as $onPageMovie) {
    $mMovie = getMovieDetail($onPageMovie->slug);
    $mMovie->actor = json_encode($mMovie->actor, JSON_UNESCAPED_UNICODE);
    $mMovie->director = json_encode($mMovie->director, JSON_UNESCAPED_UNICODE);
    $mMovie->category = json_encode($mMovie->category, JSON_UNESCAPED_UNICODE);
    $mMovie->country = json_encode($mMovie->country, JSON_UNESCAPED_UNICODE);
    $mMovie->episodes = json_encode($mMovie->episodes, JSON_UNESCAPED_UNICODE);

    $sql = "INSERT INTO `mmovi`
(`id`,`_id`,`name`,`origin_name`,`content`,`type`,`status`,`thumb_url`,`trailer_url`,`time`,`episode_current`,`episode_total`,`quality`,`lang`,`slug`,`year`,`actor`,`director`,`category`,`country`,`episodes`)
VALUES
(null,'$mMovie->_id','$mMovie->name','$mMovie->origin_name','$mMovie->content','$mMovie->type','$mMovie->status','$mMovie->thumb_url','$mMovie->trailer_url','$mMovie->time','$mMovie->episode_current','$mMovie->episode_total','$mMovie->quality','$mMovie->lang','$mMovie->slug','$mMovie->year','$mMovie->actor','$mMovie->director','$mMovie->category','$mMovie->country','$mMovie->episodes')";


    /** */
    $statusRunning = DB::getInstance()->exec($sql) ? "OK" : "ERROR";
    file_put_contents('count.txt', PHP_EOL . $statusRunning . ":   " . $mMovie->name . ' --|-- ' . date('d-m-Y H:i:s', time()) . ' ', FILE_APPEND);
  }
}





function getMovieDetail($slug = '')
{
  $movie = json_decode(file_get_contents("https://ophim1.com/phim/{$slug}"));


  $category = [];
  foreach ($movie->movie->category as $val) {
    array_push($category, $val->name);
  }


  $director = [];
  foreach ($movie->movie->director as $val) {
    array_push($director, addslashes($val));
  }


  $actor = [];
  foreach ($movie->movie->actor as $val) {
    array_push($actor, addslashes($val));
  }

  $country = [];
  foreach ($movie->movie->country as $val) {
    array_push($country, $val->name);
  }


  foreach ($movie->episodes as $epi) {
    foreach ($epi->server_data as $data) {
      if ($data->filename) {
        unset($data->filename);
      }
    }
  }

  $convertMovie = (object)array(
    '_id' => $movie->movie->_id,
    'name' => addslashes($movie->movie->name),
    'origin_name' => addslashes($movie->movie->origin_name),
    'content' => htmlspecialchars($movie->movie->content),
    'type' => $movie->movie->type,
    'status' => $movie->movie->status,
    'thumb_url' => $movie->movie->thumb_url,
    'trailer_url' => $movie->movie->trailer_url,
    'time' => addslashes($movie->movie->time),
    'episode_current' => $movie->movie->episode_current,
    'episode_total' => $movie->movie->episode_total,
    'quality' => $movie->movie->quality,
    'lang' => $movie->movie->lang,
    'slug' => $movie->movie->slug . '-' . rand(1000, 9999),
    'year' => $movie->movie->year,
    'actor' => $actor,
    'director' => $director,
    'category' => $category,
    'country' => $country,
    'episodes' => $movie->episodes
  );
  return $convertMovie;
}

//////////


















///// Classs Database /////
class DB
{
  private static $instances = [];
  protected function __construct()
  {
    $conn = mysqli_connect(
      'localhost',
      'root',
      '',
      'viesub2'
    ) or die("Can't Connect To Database!");
    $conn->set_charset("utf8");
    $this->conn = $conn;
    return $this;
  }
  public function __wakeup()
  {
    throw new \Exception("Cannot unserialize a singleton.");
  }
  public static function getInstance(): DB
  {
    $cls = static::class;
    if (!isset(self::$instances[$cls])) {
      self::$instances[$cls] = new static();
    }
    return self::$instances[$cls];
  }
  /**
   * 
   */

  public function num_rows($result)
  {
    if ($result) return mysqli_num_rows($result);
    return 0;
  }


  public function exec($sql)
  {
    $this->conn->set_charset("utf8");
    $ex = $this->conn->query($sql);
    mysqli_error($this->conn);
    return $ex;
  }
}
