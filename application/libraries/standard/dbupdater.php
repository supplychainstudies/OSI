<?php

class DBUpdater {
	public $dbtype;
    protected $_db = false;
    protected $_versionTable = 'meta';
    protected $_versionField = 'versionDb';
    protected $_versionValue = false;
    protected $_versionValueDefault = '1';
    protected $_tables = array('db' =>false, 'files' => false, 'data' => false);

	public function __construct( $db ) {
			$ci =& get_instance();
	        $this->dbtype = $ci->config->item('dbtype');
			define('DB_PATH'            , realpath(BASEPATH.'../db/'));
			define('DB_USESAMPLEDATA'            , 'true');

			define('DB_CHANGESETS_PATH' , DB_PATH . '/deltas/'.$this->dbtype.'/');
			define('DB_DATA_PATH'       , DB_PATH . '/data/'.$this->dbtype.'/');
			define('DB_TABLES_PATH'     , DB_PATH . '/schema/'.$this->dbtype.'/');

			define('MAIN_APP_PATH' , dirname(DB_PATH));

			error_reporting('E_NONE');

			define('DB_HOST',		"localhost");
			define('DB_USER',		$db[0]->username);
			define('DB_PASSWORD',	$db[0]->password);
			define('DB_NAME',		$db[0]->database); 
			require 'dbupdate/'.$this->dbtype.'.php';
			if ( $this->dbtype == 'mysql' ) {
	      		$this->_db = new GS_Mysql();
			}
	    	elseif ( $this->dbtype == 'postgresql' ) {
	    		$this->_db = new SM_Postgresql();
			}
		}

    public function runUpdate() {
        $this->updateSchema();
        $this->_tables['db'] = $this->getDbTables(true);
        $this->updateData();
        $this->runChangesets();
        $this->getDb()->debug();
        $this->verbose('<h2>Database up-to-date.</h2>');
    }


    protected function updateSchema() {
        $tables['files'] = $this->getSchemaTables();
        foreach ($tables['files'] as $table) {
            if (!$this->tableDbExists($table)) {
				$this->verbose('<ul>');	
                $this->installTable($table);
				$this->verbose('</ul>');
            }
        }
    }


    protected function updateData() {
        $tables['data'] = $this->getDataTables();
        
        foreach ($tables['data'] as $table) {
            $matches = array();
            $match = preg_match("/(.*)_sample/",$table,&$matches);
            if ($match == 1 && DB_USESAMPLEDATA) {
                if ($this->tableDbExists($matches[1])) {
                    $this->verbose('<ul>');
                    $this->installData($table);
                    $this->verbose('</ul>');
                }
            }
            elseif ($this->tableDbExists($table)) {
				$this->verbose('<ul>');	
                $this->installData($table);
				$this->verbose('</ul>');

            }
        }
    }


    protected function installTable($table) {
        $this->verbose('<li><strong>Installed table [' . $table . '] </strong></li>');
        $this->runFile($this->tablePath($table)); 

    }

    protected function installData($table) {
        $this->verbose('<li><strong>Installed data [' . $table . '] </strong></li>');
        $match = preg_match("/_sample/",$table);
        if($match == 0) {
            $this->runFile($this->dataPath($table)); 
        }
		elseif(DB_USESAMPLEDATA) {
			$this->runFile($this->dataPath($table));
	        
		}
    }

    protected function runChangesets() {
        $changesets = $this->getChangesets();
        if (is_array($changesets) && count($changesets) > 0) {
	        $this->verbose('');
	        $this->verbose('<h2>Running Changes </h2>');
			$this->verbose('<ul>');
			
            foreach ($changesets as $changeset) {
                $this->runChangeset($changeset);
            }

			$this->verbose('</ul>');
			
            $this->setVersion($changeset);
        }
    }

    protected function runChangeset($changeset) {
        $this->verbose('<li><h3>Running changeset  [' . $changeset . ']</h3>');
        $dirPath = $this->changesetDirPath($changeset);
        $files = $this->getFiles($dirPath);
        if (is_array($files)) {
			$this->verbose('<ul>');
            foreach ($files as $file) {
                $this->verbose('<li>File [' . $file . ']</li>');
                $this->runFile($this->changesetPath($file, $changeset));
            }
			$this->verbose('</ul>');

        } else {

        }
		$this->verbose('</li>');

        return false;
    }

    protected function getSchemaTables($reset = false) {
        if ( $this->_tables['files'] == false or $reset ) {
            $this->verbose('<h2>Getting Table Schemas</h2><ul>');
            $tables = $this->getFiles($this->tablePath());
            if (is_array($tables)) {
                foreach ($tables as $table) {
                    $this->verbose('<li>' . $table . '</li>');
                }
            }
			$this->verbose('</ul>');
            $this->_tables['files'] = $tables;
        }

        return $this->_tables['files'];
    }
    
    protected function getDataTables($reset = false) {
        if ( $this->_tables['data'] == false or $reset ) {
            $this->verbose('<h2>Getting Table Data</h2><ul>');
            $tables = $this->getFiles($this->dataPath());
            if (is_array($tables)) {
                foreach ($tables as $table) {
                    $this->verbose('<li>' . $table . '</li>');
                }
            }
			$this->verbose('</ul>');
            $this->_tables['data'] = $tables;
        }

        return $this->_tables['data'];
    }

    protected function getChangesets() {
        $dirsTemp = $this->getDirs($this->changesetDirPath());
        $version = $this->getVersion();
        $dirs = array();

        $this->verbose('<h2>Getting Changesets</h2><ul>');
        if (is_array($dirsTemp)) {
            foreach ($dirsTemp as $dir) {
                if ((int)$dir > (int)$version) {
                    $this->verbose('<li>' . $dir . '</li>');
                    $dirs[] = $dir;
                }
            }
        }
		$this->verbose('</ul>');
        return $dirs;
    }

    protected function getFiles($dir) {
        if (is_dir($dir)) {
           if ($dh = opendir($dir)) {
               while (($file = readdir($dh)) !== false) {
                  if ($file!='.' && $file!='..') {
                      $type = substr($file,(strrpos($file, ".")+1));
                      $name = substr($file,0,strrpos($file, "."));
                      if ($type == 'sql') {
                          $files[] = $name;
                      } // de la if ...
                  }
               }
               closedir($dh);
               sort($files, SORT_NUMERIC);
               return $files;
           }
        }

        return false;
    }

    protected function getDirs($dir) {
        if (is_dir($dir)) {
           if ($dh = opendir($dir)) {
               while (($file = readdir($dh)) !== false) {
                  if ($file!='.' && $file!='..'  && $file!='.svn' && is_dir($dir . $file)) {
                      $dirs[] = $file;
                  }
               }
               closedir($dh);
			   if(is_array($dirs)) {
               		sort($dirs, SORT_NUMERIC);
		   	   }
               return $dirs;
           }
        }

        return false;
    }


    protected function runFile($filePath) {
        $this->verbose('<li>Running file [' . $filePath . ']</li>');
        if (is_file($filePath)) {
            $content = file_get_contents($filePath);
            if (!empty ($content)) {
                $queries = preg_split('/[.+;][\s]*\n/', $content, -1, PREG_SPLIT_NO_EMPTY);
                if (is_array($queries)) {
                    foreach ($queries as $query) {
                        $query = trim($query);
                        if (!empty ($query)) {
                            $this->runQuery($query);
                        }
                    }
                }
            }
        } else {

        }

        return false;
    }

    protected function getVersion($reset = false) {
        if (!$this->_versionValue OR $reset) {
            $this->verbose('<h2>Getting version from DB</h2>');
            if (!$this->tableDbExists($this->_versionTable)) {
				$this->verbose('<ul>');
                $this->insertVersionData();
                $this->setVersion($this->_versionValueDefault);
				$this->verbose('</ul>');

            } else {
                $row = $this->getDb()->fetch_row($this->_versionTable, "name = '$this->_versionField'");
                if (is_numeric($row['value'])) {
                    $this->_versionValue = $row['value'];
                } else {
					$this->verbose('<ul>');
	
                    $this->insertVersionData();
                    $this->setVersion($this->_versionValueDefault);
					$this->verbose('</ul>');

                }
            }

            $this->verbose('<ul><li><strong>Current Version [' . $this->_versionValue . ']</strong></li></ul>');
        }
        return intval($this->_versionValue);
    }

    protected function setVersion($version = false) {
        if (is_numeric($version)) {
            $this->_versionValue = $version;
            $this->updateVersion();
        } else {
            die('VERSION IS NOT NUMERIC');
        }
    }

    protected function updateVersion() {

        $this->verbose(' * Updating version table');
		$sql = "SELECT * FROM $this->_versionTable WHERE name = '$this->_versionField' FOR UPDATE LIMIT 1; ";
        $sql = "UPDATE $this->_versionTable SET value = $this->_versionValue WHERE name = '$this->_versionField';";
        $this->runQuery($sql);

        $this->verbose(' ** Updated version to [' . $this->_versionValue . ']');
    }

    protected function insertVersionData() {
        $this->verbose(' -> Inserting version table');
		if(!$this->_versionValue) {
			$this->_versionValue = $this->_versionValueDefault;
		}
        $sqlCreate = "INSERT INTO $this->_versionTable (name, value) VALUES ('$this->_versionField', $this->_versionValue);";
        $this->runQuery($sqlCreate);
    }

    protected function connectInitDb() {
        $this->_db->host     = DB_HOST;
        $this->_db->user     = DB_USER;
        $this->_db->password = DB_PASSWORD;
        $this->_db->db_name  = DB_NAME;
        $this->_db->dbconnect();
    }

    /**
     * Returns the mysql object
     * @return GS_Mysql or SM_Postgresql, dependend upon config setting.
     */
    protected function getDb() {
        if (!$this->_db->connection) {
            $this->connectInitDb();
        }
        return $this->_db;
    }

    protected function runQuery($query) {
        $this->getDb()->query($query);
    }

    protected function getDbTables($reset = false) {
        if ( $this->_tables['db'] == false or $reset ) {
            $this->verbose('<h2>Checking Database Tables</h2><ul>');
            $temp = $this->getDb()->listTables();
            $this->_tables['db'] = array();

            if (is_array($temp)) {
                foreach ($temp as $row) {
                    $table = array_shift($row);
                    $this->verbose('<li>' . $table . '</li>');
                    $this->_tables['db'][] = $table;
                }
            }
			$this->verbose('</ul>');
        }

        return $this->_tables['db'];
    }

    protected function tableDbExists($table) {
        return in_array($table, $this->getDbTables());
    }

    protected function tablePath($table = false) {
        $path = DB_TABLES_PATH;
        if (is_string($table)) {
            $path .= $table . '.sql';
        }

        return $path;
    }
    
    protected function dataPath($table = false) {
        $path = DB_DATA_PATH;
        if (is_string($table)) {
            $path .= $table . '.sql';
        }

        return $path;
    }

    protected function changesetDirPath($changeset = false) {
        $path = DB_CHANGESETS_PATH;
        if (!empty ($changeset)) {
            $path .= $changeset . '/';
        }

        return $path;
    }

    protected function changesetPath($file = false, $changeset = false) {
        $path = $this->changesetDirPath($changeset);
        $path .= $file . '.sql';
        return $path;
    }

    protected function verbose($message) {
        $verbose = 'echo';
        switch ($verbose) {
            case 'echo':
                echo $message . '';
                break;

            default:
                break;
        }
    }
}