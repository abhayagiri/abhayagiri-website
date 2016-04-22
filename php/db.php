<?

class DB {

    private $_db;

    public function __construct() {
        global $config;

        try {
            $this->_db = new PDO($config['db']['dsn'], $config['db']['username'], $config['db']['password'], $config['db']['options']);
        } catch (PDOException $e) {
            error_log("Failed to connect to database: " . $e->getMessage());
        }
    }

    /* -------------------------------------------------------------------------
      SELECT
      ------------------------------------------------------------------------- */

    public function _query($query) {
        $stmt = $this->_db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function _select($table, $columns = "*", $where = "", $order = "ORDER BY date DESC", $limit = "", $op = "=") {
        try {

            if (is_array($where)) {
                $filter = array();
                foreach ($where as $key => $row) {
                    $filter[] = "{$key} $op (:{$key})";
                }
                $filter = implode(' AND ', $filter);
                $filter = 'WHERE ' . $filter;
            }

            $stmt = $this->_db->prepare("SELECT $columns FROM $table $filter ORDER BY DATE DESC $limit");

            if (is_array($where)) {
                foreach ($where as $key => $row) {
                    $stmt->bindValue(":{$key}", $row);
                }
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo("Failed to connect to database: " . $e->getMessage());
        }
    }

    /* -------------------------------------------------------------------------
      SELECT
      ------------------------------------------------------------------------- */

    public function _join($tableA, $tableB, $columnsA, $columnsB, $on = array("1" => "1"), $where = array("1" => "1"), $order = "ORDER BY date DESC", $limit = '') {
        try {
            $filter1 = array();
            $filter2 = array();
            foreach ($on as $key => $row) {
                $filter1[] = "{$key}={$row}";
            }
            foreach ($where as $key => $row) {
                $filter2[] = "{$key}=:{$key}";
            }
            $filter1 = implode(' AND ', $filter1);
            $filter2 = implode(' AND ', $filter2);

            $_columnsA = str_replace("$tableA.", '', $columnsA);
            $_columnsB = str_replace("$tableB.", '', $columnsB);

            $stmt = $this->_db->prepare("
            SELECT $_columnsA,$_columnsB FROM
            (SELECT $columnsA,$columnsB
            FROM $tableA LEFT JOIN $tableB ON $filter1
            ) sel WHERE $filter2 $order $limit");

            foreach ($where as $key => $row) {
                $stmt->bindValue(":{$key}", $row);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo("Failed to connect to database: " . $e->getMessage());
        }
    }

    /* -------------------------------------------------------------------------
      Insert
      ------------------------------------------------------------------------- */

    public function _insert($table, $params) {
        try {
            //Prepare Statement
            $cols = $vals = array();
            foreach ($params as $key => $row) {
                $cols[] = $key;
                $vals[] = ":{$key}";
            }
            $cols = implode(',', $cols);
            $vals = implode(',', $vals);
            $stmt = $this->_db->prepare("INSERT INTO $table ($cols) VALUES ($vals)");
            //echo "<br>INSERT INTO $table ($cols) VALUES ($vals)<br>";
            //var_dump($params);
            //Bind Params
            foreach ($params as $key => $row) {
                $stmt->bindValue(":{$key}", $row);
            }
            //Execute
            $stmt->execute();
        } catch (PDOException $e) {
            echo("Failed to connect to database: " . $e->getMessage());
        }
    }

    /* -------------------------------------------------------------------------
      Delete
      ------------------------------------------------------------------------- */

    public function _delete($table, $where = array("1" => "1")) {
        try {
            $filter = array();
            foreach ($where as $key => $row) {
                $filter[] = "{$key}=:{$key}";
            }
            $filter = implode(' AND ', $filter);
            $stmt = $this->_db->prepare("DELETE FROM $table WHERE $filter");
            foreach ($where as $key => $row) {
                $stmt->bindValue(":{$key}", $row);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo("Failed to connect to database: " . $e->getMessage());
        }
    }

    /* -------------------------------------------------------------------------
      UPDATE
      ------------------------------------------------------------------------- */

    public function _update($table, $params, $where = array("1" => "1")) {
        try {
            //Build filter
            $filter = array();
            foreach ($where as $key => $row) {
                $filter[] = "{$key}=:{$key}";
            }
            //Prepare Statement
            $cols = $vals = array();
            foreach ($params as $key => $row) {
                $cols[] = "{$key}=:{$key}";
            }
            $cols = implode(',', $cols);
            $filter = implode(' AND ', $filter);
            $stmt = $this->_db->prepare("UPDATE $table SET $cols WHERE $filter");
            //Bind Params
            foreach ($params as $key => $row) {
                $stmt->bindValue(":{$key}", $row);
            }
            foreach ($where as $key => $row) {
                $stmt->bindValue(":{$key}", $row);
            }
            //Execute
            $stmt->execute();
        } catch (PDOException $e) {
            echo("Failed to connect to database: " . $e->getMessage());
        }
    }

    /* -------------------------------------------------------------------------
      LOG
      ------------------------------------------------------------------------- */

    public function _log($activity, $page, $title, $user) {
        $verb;
        $preposition;
        switch ($activity) {
            case "insert":
                $verb = "Added";
                $preposition = "to";
                break;
            case "update":
                $verb = "Updated";
                $preposition = "in";
                break;
            case "delete":
                $verb = "Deleted";
                $preposition = "from";
        }
        $title = $verb . ' "' . ucfirst($title) . '" ' . $preposition . ' ' . $page;
        $this->_insert('log', array('title' => $title, 'date' => date("Y-m-d H:i:s"), 'user' => $user));
    }

}

$db = new DB();
?>