<?php 
session_start();

$hostName = 'http://localhost/php/crud_ajax';
$basePath = $_SERVER['DOCUMENT_ROOT'].'/php/crud_ajax';


/**
 * Database Class
 */
class database {

// Declare Properties
  private $dbHost = 'localhost';
  private $dbUser = 'root';
  private $dbPass = 'B@$!tmy$qll!te3';
  private $dbName = 'php';

  private $result = array();
  private $connection = false;
  private $conn = "";

    /**
     *  Create Connection
     */
    public function __construct()
    {
     if (!$this->connection) {
      $this->conn = new mysqli($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName);
      $this->connection = true;
      if ($this->conn->connect_error) {
        array_push($this->result, $this->conn->connect_error);
        return false;
      }
    } else {
      return true;
    } 
  }


    /**
     *  Insert Function into Database
     */
    public function insert($table,$param = array()) {

      if ($this->tableExist($table)) {
        $columns = implode(', ',array_keys($param));
        $values = implode("', '",$param);
        $sql = "INSERT INTO $table ($columns) VALUES ('$values')";
        if ($this->conn->query($sql)) {
          array_push($this->result, $this->conn->insert_id);
          return true;
        } else {
          array_push($this->result, $this->conn->error);
        }
      }else {
        return false;
      }
    }


    /**
     *  Update Function into Database
     */
    public function update($table,$param = array(),$where = null) {

      if ($this->tableExist($table)) {
        $args = array();
        foreach ($param as $key => $value) {
          $args[] = "$key = '$value'";
        }
        $sql = "UPDATE $table SET ". implode(',', $args);
        if ($where != null) {
         $sql .= " WHERE $where";
       }
       if ($this->conn->query($sql)) {
        array_push($this->result, $this->conn->affected_rows);
        return true;
      } else {
        array_push($this->result, $this->conn->error);
      }
    }else {
      return false;
    }
  }


    /**
     *  Delete Function into Database
     */
    public function delete($table,$where = null) {
      if ($this->tableExist($table)) {
        $sql = "DELETE FROM $table";
        if ($where != null) {
          $sql .= " WHERE $where";
        }else {
         $sql .= " WHERE id = '0'";
       }
       if ($this->conn->query($sql)) {
        array_push($this->result, $this->conn->affected_rows);
        return true;
      } else {
        array_push($this->result, $this->conn->error);
      }
    }else {
      return false;
    }
  }


    /**
     *  Select Raw data Function into Database
     */
    public function sql($sql) {
      $query = $this->conn->query($sql);
      if ($query) {
        $this->result = $query->fetch_all(MYSQLI_ASSOC);
        return true;
      } else {
        array_push($this->result , $this->conn->error);
        return false;
      }
    }


    /**
     *  SELECT Conditional Data Function From Database
     */
    public function select($table, $rows = "*", $join = null, $where = null, $order = null,$limit = null) {
      if ($this->tableExist($table)) {
        $sql = "SELECT $rows FROM $table";
        if ($join != null) {
          $sql .= " $join";
        }
        if ($where != null) {
          $sql .= " WHERE $where";
        }
        if ($order != null) {
          $sql .= " ORDER BY $order";
        }
        if ($limit != null) {
          if (isset($_GET['page'])) {
            $page = $_GET['page'];
          }else {
            $page = 1;
          }
          $offset = ( $page - 1)*$limit;

          $sql .= " Limit $offset, $limit";
        }
        $query = $this->conn->query($sql);
      if ($query) {
        $this->result = $query->fetch_all(MYSQLI_ASSOC);
        return true;
      } else {
        array_push($this->result , $this->conn->error);
        return false;
      }
      } else {
        return false;
      }
    }

    /**
     *  SELECT Conditional Data Function From Database
     */
      public function pagination($table, $join = null, $where = null, $limit = null) {
        if ($this->tableExist($table)) {
          if ($limit != null) {
            $sql = "SELECT COUNT(*) FROM $table";
            if ($where != null) {
              $sql .= " WHERE $where";
            }
            if ($join != null) {
              $sql .= " $join";
            }

            $query = $this->conn->query($sql);

            $totalRecord = $query->fetch_array();
            $totalRecord = $totalRecord[0];

            $totalPages = ceil($totalRecord / $limit);

            $url = basename($_SERVER['PHP_SELF']);

            if (isset($_GET['page'])) {
            $page = $_GET['page'];
          }else {
            $page = 1;
          }

          $output = "<ul class='pagination'>";
          if ($page > 1) {
            $output .= "<li><a href='$url?page=".($page-1)."'>Prev</a></li>";
          }

if ($totalRecord > $limit) {
  for($i = 1; $i <= $totalPages; $i++) {
    if ($i == $page) {
      $active = "class='active'";
    }else {
      $active = "";
    }
    $output .= "<li><a href='$url?page=$i'>$i</a></li>";
  }
}

if ($totalPages > $page) {
            $output .= "<li><a href='$url?page=".($page+1)."'>Next</a></li>";
          }

          $output .= "</ul>";
            echo $output;
          }else {
            return false;
          }
        } else {
          return false;
        }
      }


    /**
     *  Check Table Exits in Database or not
     */
    private function tableExist($table) {
      $sql = "show tables from $this->dbName like '$table'";
      $tableInDb = $this->conn->query($sql);
      if ($tableInDb) {
        if ($tableInDb->num_rows == 1) {
          return true;
        }else {
          array_push($this->result,$table." does not exist in this database");
          return false;
        }
      }
    }

    /**
     * Get Result
     */
    public function getResult() {
     $val = $this->result;
     $this->result = array();
     return $val;
   }


    /**
     *  Close Connection
     */
    public function __destruct(){
      if ($this->connection) {
        if ($this->conn->close()) {
          $this->connection = false;
          return true;
        }
      } else {
        return false;
      }
    }

  }// Close Class

?>