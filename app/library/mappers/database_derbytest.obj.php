<?php 
/*
 * A database object for dealing specifically with the Roller Derby Test O'Matic database
 */

class database_derbytest extends database
{
	public function get_answer_count()
	{
		$query = "SELECT COUNT(*) FROM rdtom_answers";
		$result = $this->get_var($query);
		return $result;
	}
	
	public function get_response_from_array($req_array)
	{
		return new response(
			$req_array["ID"], 
			$req_array["Question_ID"], 
			$req_array["Answer_ID"], 
			$req_array["Timestamp"], 
			$req_array["Correct"], 
			$req_array["IP"], 
			$req_array["User_ID"]);
	}
	
	public function get_response_count($optional_user_ID = false)
	{
		if ($optional_user_ID)
		{
			settype($optional_user_ID, "integer");
			$clause = " WHERE User_ID = '" . $optional_user_ID . "'";
		}
		$query = "SELECT(SELECT COUNT(*) FROM rdtom_responses" . $clause . ") + (SELECT COUNT(*) FROM rdtom_responses_archive" . $clause . ") as count";
		$result = $this->get_var($query);
		return $result;
	}
	
	public function get_response_count_from_Question_ID($req_ID)
	{
		settype($req_ID, "integer");
		$query = "SELECT(SELECT COUNT(*) FROM rdtom_responses WHERE Question_ID = " . $req_ID . ") + (SELECT COUNT(*) FROM rdtom_responses_archive WHERE Question_ID = " . $req_ID . ") as count";
		$result = $this->get_var($query);
		return $result;
	}
	
	public function get_responses_from_User_ID($User_ID, $include_archive = false)
	{
		settype($User_ID, "integer");
	
		if (!$User_ID)
		{
			throw new exception ("No User ID given to get_responses_from_User_ID");
		}
		
		if ($include_archive)
		{
			$query = "SELECT Tbl1.* FROM ((SELECT * FROM rdtom_responses WHERE User_ID = '" . $User_ID . "') UNION ALL (SELECT * FROM rdtom_responses_archive WHERE User_ID = '" . $User_ID . "')) Tbl1   ORDER BY Tbl1.Timestamp Asc";
		}
		else
		{
			$query = "SELECT * FROM rdtom_responses WHERE User_ID = '" . $User_ID . "' ORDER BY Timestamp Asc";
		}
		
		$results = $this->get_results($query);
		
		if ($results)
		{
			foreach ($results as $result)
			{
				$out[] = $this->get_response_from_array($result);
			}
		}
		else
		{
			return false;
		}
		
		return $out;
	}

	public function responses_disassociate($User_ID)
	{
		settype($User_ID, "integer");
		
		$query = "
		UPDATE 
			rdtom_responses 
		SET 
			User_ID = '0'
		WHERE 
			User_ID = '" . $User_ID . "'
			";
		
		$this->run_query($query);
		
		$query = "
		UPDATE 
			rdtom_responses_archive 
		SET 
			User_ID = '0'
		WHERE 
			User_ID = '" . $User_ID . "'
			";
		
		$this->run_query($query);
		
	}
	
	public function get_responses($limit = 100)
	{
		settype($limit, "integer");
	
		$query = "SELECT * FROM rdtom_responses ORDER BY ID DESC LIMIT 0, " . $limit;
		$results = $this->get_results($query);
		
		if ($results)
		{
			foreach ($results as $result)
			{
				$out[] = $this->get_response_from_array($result);
			}
		}
		else
		{
			return false;
		}
		
		return $out;
	}
	
	public function get_responses_since($req_timestamp)
	{
		settype($req_timestamp, "integer");
	
		$query = "SELECT * FROM rdtom_responses WHERE Timestamp > '$req_timestamp'";
		$results = $this->get_results($query);
		
		if ($results)
		{
			foreach ($results as $result)
			{
				$out[] = $this->get_response_from_array($result);
			}
		}
		else
		{
			return false;
		}
		
		return $out;
	}
	
	public function get_responses_raw_since($req_timestamp)
	{
		settype($req_timestamp, "integer");
	
		$query = "SELECT Correct, User_ID FROM rdtom_responses WHERE Timestamp > '$req_timestamp'";
		$results = $this->get_results($query);
		
		if ($results)
		{
			foreach ($results as $result)
			{
				$out[] = $result;
			}
		}
		else
		{
			return false;
		}
		
		return $out;
	}
	
	public function get_responses_raw_between($req_timestamp, $req_untiltimestamp)
	{
		settype($req_timestamp, "integer");
		settype($req_untiltimestamp, "integer");
	
		$query = "SELECT Correct, User_ID FROM rdtom_responses WHERE Timestamp > '$req_timestamp' AND Timestamp <= '$req_untiltimestamp'";
		$results = $this->get_results($query);
		
		if ($results)
		{
			foreach ($results as $result)
			{
				$out[] = $result;
			}
		}
		else
		{
			return false;
		}
		
		return $out;
	}
	
	public function get_response_count_since($req_timestamp)
	{
		settype($req_timestamp, "integer");
		$query = "SELECT COUNT(*) FROM rdtom_responses WHERE Timestamp > '$req_timestamp'";
		$result = $this->get_var($query);
		return $result;
	}
	
	public function get_response_distinct_ip_count()
	{
		$query = "SELECT(SELECT COUNT(DISTINCT IP) FROM rdtom_responses) + (SELECT COUNT(DISTINCT IP) FROM rdtom_responses) as count";
		$result = $this->get_var($query);
		return $result;
	}
	
	public function remove_question_and_answers($req_question_ID)
	{
		settype($req_question_ID, "integer");
		// delete question
		$query = "DELETE FROM rdtom_questions WHERE ID = '" .$req_question_ID . "' LIMIT 1;";
		$this->run_query($query);
		
		// delete answers
		$query = "DELETE FROM rdtom_answers WHERE Question_ID = '" .$req_question_ID . "'";
		$this->run_query($query);
		
		// delete relationships
		$query = "DELETE FROM rdtom_relationships WHERE Question_ID = '" .$req_question_ID . "'";
		$this->run_query($query);
	}
	
	public function remove_answers_given_questionID($req_question_ID)
	{
		settype($req_question_ID, "integer");
		
		// delete answers
		$query = "DELETE FROM rdtom_answers WHERE Question_ID = '" .$req_question_ID . "'";
		$this->run_query($query);
	}
	
	public function set_response($req_response)
	{
		$req_Question_ID = $req_response->get_Question_ID();
		$req_Answer_ID = $req_response->get_Answer_ID();
		$req_Timestamp_ID = $req_response->get_Timestamp();
		$req_Correct = $req_response->get_Correct();
		$req_IP = $req_response->get_IP();
		$req_User_ID = $req_response->get_User_ID();
		
		settype($req_Question_ID, "integer");
		settype($req_Answer_ID, "integer");
		settype($req_Timestamp_ID, "integer");
		settype($req_Correct, "integer");
		$req_IP = $this->mysql_res($req_IP);
		settype($req_User_ID, "integer");
		
		$query = "
		INSERT INTO rdtom_responses 
		(
			Question_ID ,
			Answer_ID ,
			Timestamp ,
			Correct ,
			IP,
			User_ID
		) VALUES (
			'$req_Question_ID',  
			'$req_Answer_ID',  
			'$req_Timestamp_ID',  
			$req_Correct,  
			'$req_IP',
			'$req_User_ID'
		);
		";
		
		$this->run_query($query);
	}
	
	
	
	public function get_stats_hourly_posts($hour_count)
	{
		// get the time x hours ago & round down
		$time_now = time();
		$time_ago = $time_now - (60*60*$hour_count);
		$time_ago = floor($time_ago/3600) * 3600;
		
		// get the data from the database
		$query = "
			SELECT 
			Timestamp
			FROM rdtom_responses 
			WHERE Timestamp > '$time_ago' AND Timestamp < '$time_now'";
		
		$raw_timestamps = $this->get_col($query);
		
		// build an empty array for the last 24 hours, this makes sure if any values are 0, they have a record
		for ($i = 0; $i < 24; $i ++)
		{
			$results[$i] = 0;
			
		}
		
		// fill it with data
		foreach ($raw_timestamps as $timestamp)
		{
			//$results[date('Y z H', $timestamp)]++;
			// seconds since 0 hour, rounded to the hour
			$results[floor(($timestamp - $time_ago)/3600)]++;
		}
		
		return $results;
	}
	

	public function get_user_from_array($req_array)
	{
		return new user(
			$req_array['ID'], 
			$req_array['name'], 
			$req_array['password_hash'], 
			$req_array['password_salt'], 
			$req_array['email'], 
			$req_array['registered_time']);
	}
	
	public function get_user_password_hash_from_password($req_password, $req_user_salt)
	{
		global $database_salt;
		$hash = hash("sha384", $req_password . DATABASE_SALT);
		$real_hash = hash("sha512", $hash . $req_user_salt);
		
		return $real_hash;
	}

	public function get_user_from_name_and_password($req_user_name, $req_user_password)
	{
		// get the hash if the username is valid
		$user_salt = $this->get_user_password_salt_from_user_name($req_user_name);
		
		
		if (!$user_salt)
		{
			return false;
		}
		
		$user_password_hash = $this->get_user_password_hash_from_password($req_user_password, $user_salt);
		
		
		$user_name = $this->mysql_res($req_user_name);
		$user_password_hash = $this->mysql_res($user_password_hash);
		
    $query="SELECT * FROM rdtom_users WHERE name=\"" . $user_name . "\" AND  password_hash=\"" . $user_password_hash . "\"";
		$result = $this->get_row($query);
		
		if ($result)
		{
			return $this->get_user_from_array($result);
		}
		else
		{
			return false;
		}
		// an error has occured
	}
	
	public function get_user_from_ID($req_ID)
	{
		settype($req_ID, "integer");
		
		$query="SELECT * FROM rdtom_users WHERE ID=\"" . $req_ID . "\"";
		$result = $this->get_row($query);
		if ($result)
		{
			return $this->get_user_from_array($result);
		}
		else
		{
			throw new exception("User not found with ID " . $req_ID);
		} 
	}
	
	public function get_user_from_email($req_email)
	{
		
		if (!trim($req_email))
		{
			throw new exception("No email address give.");
		}
		
		$req_email = $this->mysql_res($req_email);
		
		$query="SELECT * FROM rdtom_users WHERE email=\"" . $req_email . "\"";
		$result = $this->get_row($query);
		if ($result)
		{
			return $this->get_user_from_array($result);
		}
		else
		{
			throw new exception("User not found with email " . $req_email);
		} 
	}
	
	public function get_user_from_name($req_name)
	{
		
		if (!trim($req_name))
		{
			throw new exception("No name give.");
		}
		
		$req_name = $this->mysql_res($req_name);
		
		$query="SELECT * FROM rdtom_users WHERE UPPER(name)=\"" . strtoupper($req_name) . "\"";
		$result = $this->get_row($query);
		if ($result)
		{
			return $this->get_user_from_array($result);
		}
		else
		{
			throw new exception("User not found with name " . $req_name);
		} 
	}

	public function get_user_password_salt_from_user_name($req_user_name)
	{
		$user_name = $this->mysql_res($req_user_name);
		
    $query="SELECT password_salt FROM rdtom_users WHERE name=\"" . $user_name . "\"";
		$result = $this->get_var($query);
		
		if ($result)
		{
			return $result;
		}
		else
		{
			return false;
		}
	}

	public function get_user_count()
	{
		$query = "SELECT COUNT(*) FROM rdtom_users";
		$result = $this->get_var($query);
		return $result;
	}
	

	public function get_users()
	{
		settype($req_ID, "integer");
		
		$query="SELECT * FROM rdtom_users";
		$results = $this->get_results($query);
		
		if ($results)
		{
			foreach ($results as $result)
			{
				$tmp_user = $this->get_user_from_array($result);
				$out[$tmp_user->get_ID()] = $tmp_user;
			}
		}
		else
		{
			throw new exception("No users found. Oh dear");
		}
		
		return $out;
		
	}
	
	public function add_user($req_name, $req_password, $req_email)
	{
		$randomStringGenerator = new RandomStringGenerator();
		$user_password_salt = $randomStringGenerator->generate(50);
		$user_password_hash = $this->get_user_password_hash_from_password($req_password, $user_password_salt);
		$user_name = $this->mysql_res($req_name);
		$user_email = $this->mysql_res($req_email);
		
		// add the user
		$query="
			INSERT INTO rdtom_users 
			(
				name ,
				password_hash ,
				password_salt ,
				email ,
				registered_time
			)
			VALUES 
			(
				'" . $user_name . "',  
				'" . $user_password_hash . "',  
				'" . $user_password_salt . "',  
				'" . $user_email . "',  
				'" . time() . "'
			);";
		
		$this->run_query($query);
	}
	
	public function set_user_password($req_user_ID, $req_password)
	{
		$randomStringGenerator = new RandomStringGenerator();
		$user_password_salt = $randomStringGenerator->generate(50);
		$user_password_hash = $this->get_user_password_hash_from_password($req_password, $user_password_salt);
		settype($req_user_ID, "integer");
		
		if (!$req_user_ID)
		{
			throw new Exception("User ID not given");
		}
		
		// update the user
		$query= "
			UPDATE 
				rdtom_users 
			SET 
				password_hash = '$user_password_hash',
				password_salt = '$user_password_salt' 
			WHERE  
				ID = '$req_user_ID'";
		
		$this->run_query($query);
		
		save_log("password_reset", "RESET OR CHANGED for user ID: " . $req_user_ID);
	}
	
	public function set_user_name($req_User_ID, $req_username)
	{
		$selected_user = $this->get_user_from_ID($req_User_ID);
		
		$req_username = $this->mysql_res($req_username);
		settype($req_User_ID, "integer");
		
		$query = "
			UPDATE 
				rdtom_users
			SET
				name = '" . $req_username . "'
			WHERE
				ID = '" . $req_User_ID . "'";
		
		$this->run_query($query);
		
		save_log("username_change", "Changed from '". $selected_user->get_Name() . "' to '" . $req_username . "' for user ID: " . $req_User_ID);
	}
	
	public function set_user_email($req_User_ID, $req_email)
	{
		$selected_user = $this->get_user_from_ID($req_User_ID);
		
		$req_email = $this->mysql_res($req_email);
		settype($req_User_ID, "integer");
		
		$query = "
			UPDATE 
				rdtom_users
			SET
				email = '" . $req_email . "'
			WHERE
				ID = '" . $req_User_ID . "'";
		
		$this->run_query($query);
		
		save_log("email_change", "Changed from '". $selected_user->get_Email() . "' to '" . $req_email . "' for user ID: " . $req_User_ID);
	}
	
	
	public function add_token($req_token, $req_User_ID, $req_IP)
	{
		$req_token = $this->mysql_res($req_token);
		$req_IP = $this->mysql_res($req_IP);
		settype($req_User_ID, "integer");
		
		// add the user
		$query="
			INSERT INTO rdtom_usertokens 
			(
				User_ID ,
				Token ,
				IP ,
				Timestamp
			)
			VALUES 
			(
				'" . $req_User_ID . "',  
				'" . $req_token . "',  
				'" . $req_IP . "',  
				'" . time() . "'
			);
		";
		
		$this->run_query($query);		
	}
	
	public function get_user_from_token($req_token, $req_IP)
	{
		$req_token = $this->mysql_res($req_token);
		$req_IP = $this->mysql_res($req_IP);

		// No longer requires same IP to be remembered - account for people with changing IPs
		// however, the Ip is still recorded when Tokens generated
		
		//$query="SELECT User_ID FROM rdtom_usertokens WHERE Token = '" . $req_token . "' AND IP = '" . $req_IP . "'";
		$query="SELECT User_ID FROM rdtom_usertokens WHERE Token = '" . $req_token . "'";
		
		$result = $this->get_var($query);
		
		if ($result)
		{
			return $this->get_user_from_ID($result);
		}
		
		return false;
	}
	
	public function remove_token($req_User_ID, $req_IP)
	{
		$req_IP = $this->mysql_res($req_IP);
		settype($req_User_ID, "integer");
		
		//$query = "DELETE FROM rdtom_usertokens WHERE User_ID = '" . $req_User_ID . "' AND IP = '" . $req_IP . "'";
		$query = "DELETE FROM rdtom_usertokens WHERE User_ID = '" . $req_User_ID . "'";
		$this->run_query($query);	
	}
	
	public function remove_old_token($req_timestamp)
	{
		settype($req_timestamp, "integer");
		
		$query = "DELETE FROM rdtom_usertokens WHERE Timestamp < '" . $req_timestamp . "'";
		$this->run_query($query);	
	}
	
	public function is_user_name_taken($req_user_name)
	{
		$user_name = $this->mysql_res($req_user_name);
		
		$query="SELECT count(*) FROM rdtom_users WHERE name=\"" . $user_name . "\"";
		$result = $this->get_var($query);
		
		if ($result == 0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	public function is_email_taken($req_email)
	{
		$user_email = $this->mysql_res($req_email);
		
		$query="SELECT count(*) FROM rdtom_users WHERE email=\"" . $user_email . "\"";
		$result = $this->get_var($query);
		
		if ($result == 0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	/*
	 * functions to handle password reset tokens
	 */
	
	// save a password reset token
	public function set_password_reset_token($req_Token, $req_User_ID, $req_Email)
	{
		// clean the input
		$req_Token = $this->mysql_res($req_Token);
		settype($req_User_ID, "integer");
		$req_Email = $this->mysql_res($req_Email);
		
		$query = "
			INSERT INTO 
				rdtom_passwordresettokens (
				User_ID, 
				Email, 
				Token, 
				Timestamp, 
				Used
				) 
			VALUES 
				(
				'$req_User_ID', 
				'$req_Email', 
				'$req_Token', 
				'" . time() . "', 
				'0');";
		
		$this->run_query($query);
	}
	
	public function is_valid_password_reset_token($req_Token)
	{
		
		$req_Token = $this->mysql_res($req_Token);
		$time_ago = time() - PASSWORD_RESET_TOKEN_TTL;
		
		$query = "SELECT count(*) FROM rdtom_passwordresettokens WHERE Token = '$req_Token' AND Timestamp >= '$time_ago' AND Used = '0'";
	
		$result = $this->get_var($query);
		
		if ($result)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	
	public function get_user_from_password_reset_token($req_Token)
	{
		
		$req_Token = $this->mysql_res($req_Token);
		$time_ago = time() - PASSWORD_RESET_TOKEN_TTL;
		
		$query = "SELECT User_ID FROM rdtom_passwordresettokens WHERE Token = '$req_Token' AND Timestamp >= '$time_ago' AND Used = '0'";
	
		$User_ID = $this->get_var($query);
		
		$forgetful_user = $this->get_user_from_ID($User_ID);
		
		if ($forgetful_user)
		{
			return $forgetful_user;
		}
		else
		{
			throw new exception("Valid token, invalid user ID");
		}
	}
	
	
	public function use_password_reset_token($req_Token)
	{
		// mark all reset tokens for this account as used
		
		$forgetful_user = $this->get_user_from_password_reset_token($req_Token);
		
		$User_ID = $forgetful_user->get_ID();
		settype($User_ID, "integer");
		
		$query = "
			UPDATE 
				rdtom_passwordresettokens
			SET  
				Used =  '1' 
			WHERE 
				User_ID = '$User_ID'";
		
		$this->run_query($query);
	}
	
	public function get_terms($req_taxonomy = false, $req_Question_ID = false)
	{
		
		if ($req_Question_ID)
		{
			settype($req_Question_ID, "integer");
			if ($req_taxonomy)
			{
				$req_taxonomy = $this->mysql_res($req_taxonomy);
				$query = "SELECT rdtom_terms.* 
					FROM rdtom_terms
					JOIN rdtom_relationships ON rdtom_relationships.Term_ID = rdtom_terms.ID
					WHERE taxonomy =  '$req_taxonomy'
					AND rdtom_relationships.Question_ID =  '$req_Question_ID'";
			}
			else
			{
				$query = "SELECT rdtom_terms.* 
					FROM rdtom_terms
					JOIN rdtom_relationships ON rdtom_relationships.Term_ID = rdtom_terms.ID
					WHERE rdtom_relationships.Question_ID =  '$req_Question_ID'";
			}
		}
		elseif ($req_taxonomy)
		{
			$req_taxonomy = $this->mysql_res($req_taxonomy);
			$query = "SELECT * FROM rdtom_terms WHERE taxonomy = '$req_taxonomy' ";
		}
		else
		{
			$query = "SELECT * FROM rdtom_terms ORDER BY taxonomy";
		}
		
		$results = $this->get_results($query);
		
		if ($results)
		{
			foreach($results as $result)
			{
				$out[$result['ID']] = $this->get_term_from_array($result);
			}
			
			return $out;
		}
		else
		{
			return false;
		}
	}
	
	public function get_term_from_ID($req_ID)
	{
		settype($req_ID, "integer");
		
		$query = "SELECT * FROM rdtom_terms WHERE ID = '$req_ID'";

		$result = $this->get_row($query);
		
		if ($result)
		{
			return $this->get_term_from_array($result);
		}
		else
		{
			return false;
		}
	}
	
	public function get_term_from_taxonomy_and_name($req_taxonomy, $req_name)
	{
		$req_taxonomy = $this->mysql_res($req_taxonomy);
		$req_name = $this->mysql_res($req_name);
		
		$query = "SELECT * FROM rdtom_terms WHERE taxonomy = '$req_taxonomy' AND name = '$req_name'";

		$result = $this->get_row($query);
		
		if ($result)
		{
			return $this->get_term_from_array($result);
		}
		else
		{
			return false;
		}
	}
	
	public function get_term_from_array($req_array)
	{
		
		return new term(
			$req_array['ID'],
			$req_array['name'],
			$req_array['description'],
			$req_array['taxonomy']);
	}
	
	public function remove_relationship_given_Question_ID($req_question_ID)
	{
		settype($req_question_ID, "integer");
		
		$query = "DELETE FROM rdtom_relationships WHERE Question_ID = '" .$req_question_ID . "'";
		$this->run_query($query);
	}
	
	public function remove_relationship_given_Term_ID($req_term_ID)
	{
		settype($req_term_ID, "integer");
		
		$query = "DELETE FROM rdtom_relationships WHERE Term_ID = '" .$req_term_ID . "'";
		$this->run_query($query);
	}
	
	public function add_relationship($req_question_ID, $req_term_ID)
	{
		settype($req_question_ID, "integer");
		settype($req_term_ID, "integer");
		
		if ($this->get_term_from_ID($req_term_ID))
		{
			$query = "INSERT INTO rdtom_relationships (
				Question_ID ,
				Term_ID
				)
				VALUES (
				'$req_question_ID',  '$req_term_ID')";
			
			$this->run_query($query);
		}
		else
		{
			throw new exception ("Term not found with ID " . $req_term_ID);
		}
	}
} // class database
?>
