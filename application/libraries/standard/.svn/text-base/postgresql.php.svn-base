<?php

class SM_Postgresql {

	// Database server information
    public $host;                 // (string)  Host server of database
    public $user;                 // (string)  User
    public $password;             // (string)  Password
    public $db_name;              // (string)  Database that will be selected
    public $port;                 // (int)     Server port
    public $connection = false;   // (link identifier)   Connection link identifier
    public $result;               // (link identifier)   Result link identifier

    // Class operation setup


    // 0 LOG_NONE
    // 1 ECHO
    // 2 HIDDEN ECHO
    // 3 LOG FILE
    public $debugType  = 3 ;
    // 0 LogAll
    // 1 LogOnly Bad & Noresults Querrys
    // 2 LogOnly Bad Querrys
    public $debugLevel  = 0 ;
    public $error_level  = 0 ;
    public $error_desc   = "No errors" ;
    public $logfile      = "/tmp/datalog" ;
    public $filehdl      = 0 ;
    public $messsages    = array() ;

    public $affected_rows = 0 ;
    public $num_rows      = 0 ;
    public $recordcount   = 0 ;
    public $lastid        = 0 ;
    public $sqlString;

    public $query_no     = 0 ;



	public function postgresql($host = "", $user = "", $password = "", $db_name = "", $port = "")
	{
        $this->host     = ( !empty( $host ) )      ?  (string)$host      :  "localhost";
        $this->user     = ( !empty( $user ) )      ?  (string)$user      :  "postgres";
        $this->password = ( !empty( $password ) )  ?  (string)$password  :  "";
        $this->db_name  = ( !empty( $db_name ) )   ?  (string)$db_name   :  "";
        $this->port     = ( !empty( $port ) )      ?  (int)$port         :  5432;
	}


    public function dbconnect($is_persistent = false)
    {
    	$this->logfile_init() ;

        if (!$is_persistent) {
            $this->connection = pg_connect(
            	"host='".$this->host.
            	"' port='".$this->port.
            	"' user='".$this->user.
            	"' password='".$this->password.
            	"' dbname='".$this->db_name."'");
        } else {
            $this->connection = pg_pconnect(
            	"host='".$this->host.
            	"' port='".$this->port.
            	"' user='".$this->user.
            	"' password='".$this->password.
            	"' dbname='".$this->db_name."'");
        }

        $this->error_report();

        if (!$this->connection) {
            // Conection failed
            $this->add_debug_message ( date("d/m/Y - H:i:s") . " - ERROR " . $this->error_level . ": " . $this->error_desc . "\r\n" ) ;
            $this->release_db() ;
        }
    }

    // Releasing database connection
    public function release_db()
    {
        // Checking if a conection is open?
        if ($this->connection) {
            // Trying to close the connection ...
            if (pg_close($this->connection)) {
                $this->add_debug_message ( date("d/m/Y - H:i:s") . " - OPERATION O.K.: Database " . $this->db_name . " released" . "\r\n" );
            } else {
                // Failed to liberate the database...
                $this->error_report() ;
                $this->add_debug_message ( date("d/m/Y - H:i:s") . " - ERROR " . $this->error_level . ": " . $this->error_desc . "\r\n" );
            }
        } else {
            // No database open
            $this->add_debug_message ( date("d/m/Y - H:i:s") . " - OPERATION CANCELLED: No database open" . "\r\n" );
        }
        // LOG the operation and close logging operations
        $this->debug() ;
        $this->logfile_close() ;
    }

    // Error reporting auxiliary method
    public function error_report()
    {
        $this->error_desc = pg_last_error() ;
    }

    // Log operations initialization
    public function logfile_init()
    {
        if ($this->debugType==3) {
            $this->add_debug_message ( date("d/m/Y - H:i:s") . " ===== SESSION STARTED BY " . $GLOBALS["PHP_SELF"] . " =====" .  "\r\n" );
            $this->logfile = $this->logfile . "-" . date("m") . "-" . date("Y") ;
            $this->filehdl = fopen($this->logfile,'a') ;

            if (!$this->filehdl) {
                echo "<!-- UNABLE TO OPEN SPECIFIED LOG FILE " . $this->logfile . " -->" ;
                $this->debugType-- ;
                $this->logfile_init() ;
            }
        }
        $this->debug() ;
    }

    // Closing log operations
    public function logfile_close()
    {
        if ($this->filehdl) {
            // If we opened a file to log operations need to close it
            fclose($this->filehdl) ;
        }
    }

    public function add_debug_message($message)
    {
        $this->messsages[]=$message;
    }

    // Debugging operations
    public function debug()
    {
        switch ($this->debugType) {
            case 0: // NO LOG OPERATIONS
                break ;
            case 1: // SCREEN OUTPUT
                foreach ($this->messsages as $m) {
                    echo '<BR>DEBUG: ' . $m . '<BR>' ;
                }
                break ;
            case 2: // SILENT OUTPUT (<!-- -->)
                foreach ($this->messsages as $m) {
                    echo "\n<!-- DEBUG: " . $m . "-->\n" ;
                }
                break ;
            case 3: // FILE OUTPUT
                foreach ($this->messsages as $m) {
                    fwrite($this->filehdl,$m) ;
                }
                break ;
        }
    }


    // Destructor
    public function destroy()
    {
        $this->release_db() ;
    }

    // performes an sqlQuery
    public function query($sqlString)
    {
        $this->sqlString=$sqlString;
        $this->query_no++;
        if ($this->connection !== false) {
            $this->result = pg_query($this->connection,$sqlString);
            $this->error_report() ;
            // Affectected rows...
            if ($this->result) {
                // Execution was o.k.
                $this->affected_rows = pg_affected_rows( $this->result );
                if (is_resource($this->result)) {
                    $this->num_rows = pg_num_rows( $this->result );
                } else  {
                    $this->num_rows = 0;
                }

				// craft inserts to return last id, and get that ID from the resource
				$result = pg_fetch_assoc($this->result);
				if ( $result && in_array('last_id', $result) ) {
	                $this->lastid = $result['last_id'];
                }
                
                if ( ($this->debugLevel==1 && ($this->affected_rows+$this->num_rows)<1 ) OR $this->debugLevel == 0 ) {
                    $this->add_debug_message( date("d/m/Y - H:i:s") . " - OPERATION O.K.: Executed [" . $this->sqlString ."] [affected " . $this->affected_rows . " rows] [rows in result " . $this->num_rows . " ]" . "\r\n" );
                }
                return true;
            } else {
                // Execution Failed
                $this->affected_rows = 0 ;
                $this->num_rows = 0 ;
                $this->add_debug_message( date("d/m/Y - H:i:s") . " - OPERATION FAILED: Executed [" . $this->sqlString . "] got " . $this->error_level . " " . $this->error_desc . "\r\n" );
                return false;
            }
        } else {
            // No database ready to query
            $this->affected_rows = 0 ;
            $this->num_rows = 0 ;
            $this->add_debug_message( date("d/m/Y - H:i:s") . " - OPERATION FAILED: No database open OR no SQL command provided" . "\r\n"  );
            return false;
        }
    }

    public function fetch_assoc()
    {
        return pg_fetch_assoc( $this->result );
    }

    public function clean_data($data)
    {
        return pg_escape_string( $this->connection, $data );
    }

    public function fetch_data_array ($key = false)
    {
        $data=array();
        while( $row = $this->fetch_assoc() )
        {
            if ($key && isset($row[$key]))
            {
                $data[$row[$key]]=$row;
            } else {
                $data[]=$row;
            }
        }
        return $data;
    }

    // grabs a list of rows from a table ... returns an array of data
    public function list_table( $table_name, $where = false, $parameters = array () )
    {
        $range       = ( isset($parameters['range'])       && !empty($parameters['range']) )       ? $parameters['range']       : " * " ;
        $key         = ( isset($parameters['key'])         && !empty($parameters['key']) )         ? $parameters['key']         : false ;
        $sortColumn  = ( isset($parameters['sortColumn'])  && !empty($parameters['sortColumn']) )  ? $parameters['sortColumn']  : false ;
        $sortType    = ( isset($parameters['sortType'])    && !empty($parameters['sortType']) )    ? $parameters['sortType']    : "ASC" ;
        $limitOffset = ( isset($parameters['limitOffset']) && !empty($parameters['limitOffset']) ) ? $parameters['limitOffset'] : false ;
        $rowCount    = ( isset($parameters['rowCount'])    && !empty($parameters['rowCount']) )    ? $parameters['rowCount']    : false ;

        $queryString= "SELECT $range FROM $table_name ";

        if ( $where !== false ) $queryString .= " WHERE ".$where;
        if ( $sortColumn !== false ) $queryString .= " ORDER BY $sortColumn $sortType ";
        if ( $rowCount !== false ) {
            $queryString .= " LIMIT $rowCount ";
            
            if ( $limitOffset !== false ) {
                $queryString .= " OFFSET $limitOffset ";
            }
				}

        $this->query($queryString);
        if( $this->num_rows < 1 ) {
            return false;
        } else {
            return $this->fetch_data_array($key);
        }
    }


    public function listDistinct( $table_name, $where = false,$group = false, $parameters = array () )
    {
        $range       = ' COUNT( * ) AS Rows , ';
        $range      .= ( isset($parameters['range'])       && !empty($parameters['range']) )       ? $parameters['range']       : " * " ;
        $sortColumn  = ( isset($parameters['sortColumn'])  && !empty($parameters['sortColumn']) )  ? $parameters['sortColumn']  : false ;
        $sortType    = ( isset($parameters['sortType'])    && !empty($parameters['sortType']) )    ? $parameters['sortType']    : "ASC" ;
        $limitOffset = ( isset($parameters['limitOffset']) && !empty($parameters['limitOffset']) ) ? $parameters['limitOffset'] : false ;
        $rowCount    = ( isset($parameters['rowCount'])    && !empty($parameters['rowCount']) )    ? $parameters['rowCount']    : false ;

        $queryString= "SELECT $range FROM $table_name ";
        if ( $where !== false ) $queryString .= " WHERE ".$where;
        if ( $group !== false ) $queryString .= " GROUP BY $group ";
        if ( $sortColumn !== false ) $queryString .= " ORDER BY $sortColumn $sortType ";
        if ( $rowCount !== false ) {
            $queryString .= " LIMIT $rowCount ";

            if ( $limitOffset !== false ) {
                $queryString .= " OFFSET $limitOffset ";
            }
        }

        $this->query($queryString);
        if( $this->num_rows < 1 ) {
            return false;
        } else {
            return $this->fetch_data_array();
        }
    }

    // fetch a row from a table
    public function fetch_row( $table_name, $where = false , $parameters = array () )
    {
        $range       = ( isset($parameters['range'])       && !empty($parameters['range']) )       ? $parameters['range']       : " * " ;
        $order       = ( isset($parameters['order'])       && !empty($parameters['order']) )       ? $parameters['order']       : false ;


        $queryString= "SELECT $range FROM $table_name ";
        if ( $where != false ) $queryString .= " WHERE $where";

        if ( $order != false ) {
            $orderBy = array();
            foreach ($order as $field => $type) {
                $orderBy[] = $field . " " . strtoupper($type);
            }
            $queryString .= " ORDER BY " . implode(",", $orderBy);
        }

        $queryString .= " LIMIT 1";
        $this->query($queryString);

        if( $this->num_rows < 1 ) { return false; }
        else { return $this->fetch_assoc(); }
    }

    public function count_records( $table_name, $where =false , $parameters = array() )
    {
        $queryString= "SELECT COUNT(*) as rNumber FROM $table_name ";
        if ( $where != false ) $queryString .= " WHERE $where ";

        if ($this->query($queryString) == true ) {
            $row=$this->fetch_assoc();
            return $row["rNumber"];
        } else return false;
    }

    public function increment_field( $table_name, $field, $where, $parameters = array() )
    {
        $queryString= "UPDATE $table_name SET $field=$field+1  WHERE $where ";
        $this->query($queryString);
    }

    public function record_update( $table_name, $data, $where, $parameters = array() )
    {
        $queryString="UPDATE ".$table_name." SET ";
        $fields=array();

        foreach ($data as $key=>$value)	{
            $fields[] = " $key='".$this->clean_data( $value )."' ";
        }

        $queryString .= implode(',',$fields)." WHERE ".$where;

        return $this->query($queryString);
    }

    public function record_insert( $table_name, $data, $parameters = array(), $id_col = 'id' )
    {
        $queryString="INSERT INTO ".$table_name." (";
        $columns=array();
        $values=array();

        foreach ($data as $key=>$value)
        {
            $columns []= $key;
            $values  []= "'".$this->clean_data( $value )."'";
        }

        $queryString .= implode(',',$columns) .") VALUES (". implode(',',$values) .") ";
        
        $queryString .= "RETURNING $id_col as last_id";

        return $this->query($queryString);
    }

    public function record_delete( $table_name, $where, $parameters = array() )
    {
        $queryString = "DELETE FROM ". $table_name ." WHERE ". $where;
        return $this->query($queryString);
    }

    public function table_info($table_name)
    {
        $this->query(" SELECT * FROM $table_name LIMIT 1");
        $fields = pg_num_fields($this->result);

        for ($i=0; $i <= $fields; $i++) {
            $fields[$i]['type'] = pg_field_type($result, $i);
            $fields[$i]['name'] = pg_field_name($result, $i);
            $fields[$i]['len']  = pg_field_size($result, $i);
        }

        return $fields;
    }

    public function table_max_value( $table, $field)
    {
        $this->query(" SELECT max($field) as max_value FROM $table ");
        $data=$this->fetch_assoc();

        return $data["max_value"];
    }

    public function listTables() {
        $this->query("SELECT relname FROM pg_stat_user_tables;");
        $data = $this->fetch_data_array();

        return $data;
    }

}
?>
