<?php

/*
 * A generic object for dealing with a mySQL databse
*/
class database
{
	protected $dbUser = "";
	protected $dbUserPw = "";
	protected $dbName = "";
	protected $dbHost = "";

	private $saved_link;
	
	function __construct() {
		$this->dbUser = DATABASE_USERNAME;
		$this->dbUserPw = DATABASE_PASSWORD;
		$this->dbName = DATABASE_NAME;
		$this->dbHost = DATABASE_HOST;
	}
	
	// run a query and return a single variable
	public function get_var($req_query) {
		$results = $this->run_query($req_query);
		if (!$results) return null;
		
		$row = $results->fetch_row();
		
		return $row[0];
	}
	
	// run a query and return a single result/row as a one dimensional assoc aray
	public function get_row($req_query) {
		$results = $this->run_query($req_query);
		if (!$results) return null;
		
		// return the first row/column
		return mysqli_fetch_assoc($results);
	}
	
	// run a query and return a single column as a one dimensional array
	public function get_col($req_query) {
		$results = $this->run_query($req_query);
		if (!$results) return null;
		
		while ($row = $result->fetch_row()) $result_array[] = $row[0];
		
		return $result_array;
	}
	
	// run a query and return an array containing an assoc array.
  public function get_results($req_query) {
		$results = $this->run_query($req_query);
		
		if (!$results) return null;
		
		// pass results into a multidimensional array
		while ($row = $results->fetch_assoc()) $result_array[] = $row;
		return $result_array;
	}
	
	// run a query and return the results
	public function run_query($req_query) {

		if (!$this->saved_link) {
      $this->saved_link = mysqli_connect($this->dbHost, $this->dbUser,
        $this->dbUserPw, 
        $this->dbName) or die("Could not connect : " . mysql_error());
		}
		
		$results = $this->saved_link->query($req_query) or die("Query error:<br />" . $req_query . "<br />" . mysql_error());
		
		return $results;
	}
	
	public function mysql_res($req_text) {
		global $saved_link;
		
		if (!$saved_link) {
      $saved_link = mysqli_connect($this->dbHost, $this->dbUser,
        $this->dbUserPw, 
        $this->dbName) or die("Could not connect : " . mysql_error());
		}
		
		$req_text = $saved_link->real_escape_string($req_text);
		
		return $req_text;
	}
	
	// connect to the database
	public function mysql_dbconnect() {
		$link = mysql_connect($this->dbHost, $this->dbUser, $this->dbUserPw) or die("Could not connect : " . mysql_error());
		
		mysql_select_db($this->dbName) or die("Could not select database");
	}
}
