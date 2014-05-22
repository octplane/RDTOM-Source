<?php

// presentation functions

// True or False
function is_question()
{
	global $is_question, $question;
	return $is_question && $question;
}

function is_random_question()
{
	global $is_question, $is_random_question, $question;
	return $is_question && $question && $is_random_question;
}

function is_admin_page()
{
	global $url_array;
	return ($url_array[0] == "admin");
}

function is_admin()
{
	global $user;
	if ($user)
	{
		if (($user->get_Name() == "Sausage Roller") || ($user->get_Name() == "Laddie"))
		{
			return true;
		}
	}
	return false;
}

// set functions
function set_page_subtitle($req_subtitle)
{
	global $page_subtitle;
	$page_subtitle = $req_subtitle;
}

// get functions
function get_page_subtitle()
{
	global $page_subtitle;
	if ($page_subtitle)
	{
		return $page_subtitle;
	}
	else
	{
		return "Turn left and learn the rules.";
	}
}

// set functions
function set_page_title($req_title)
{
	global $page_title;
	$page_title = $req_title;
}

// get functions
function get_page_title()
{
	global $page_title;
	if ($page_title)
	{
		return $page_title;
	}
	else
	{
		return "Roller Derby Test O'Matic";
	}
}

function is_remebering_results()
{

	if (count(get_session('random_questions_asked')) > 0)
	{
		return true;
		
	}
	else
	{
		return false;
	}
	
}

function get_remebered_string()
{
	global $random_questions_to_remeber;
	// generate a string recapping the remebered data

	$correct_count = 0;
	
	$random_questions_asked = get_session('random_questions_asked');
	$random_questions_results = get_session('random_questions_results');
	
	if (count($random_questions_asked) > 0)
	{
		/*
		$result .= "The site is remembering the last <strong>" . count($random_questions_asked) . "</strong> question";
		if (count($random_questions_asked)!=1) 
		{ 
			$result .= "s";
		}
		$result .= " you've answered. ";
		*/
		
		// add the success percentage
		if (count($random_questions_results) > 0)
		{
			foreach ($random_questions_results as $tmp_result)
			{
				if ($tmp_result)
				{
					$correct_count++;
				}
			}
			
			$perc_value = round ((($correct_count / count($random_questions_results)) * 100), 2);
			$perc_colour = get_colour_from_percentage($perc_value);
			
			$result .= "You have a current success rate of <span style=\"font-weight:bold; color:" . $perc_colour . "\">" . $perc_value . "%</span> (" . $correct_count . " correct out of " . count($random_questions_results) . ").";
		}
		
		// add the winning streak
		
		// if the most recent response was correct
		if ($random_questions_results[count($random_questions_results)-1])
		{
			$current_streak = 1;
			$lost_streak = false;
		}
		else
		{
			$current_streak = 0;
			$lost_streak = true;
		}
		
		for ($i = count($random_questions_results)-2; $i>=0; $i--)
		{
			if ($random_questions_results[$i])
			{
				$current_streak ++;
			}
			else
			{
				break;
			}
		}
		
		
		if ($current_streak > 4)
		{
			// currently on a winning streak
			if ($lost_streak == false)
			{
				$result .= " You are on a winning streak of <strong>" . $current_streak . "</strong>. ";
			}
			if ($lost_streak == true)
			{
				$result .= " <span style=\"color:#FF0000\">You just ended your streak of <strong>" . $current_streak . "</strong></span>. ";
			}	
		}
		
		if (count($random_questions_asked) > ($random_questions_to_remeber * 0.9))
		{
			$result .= " The site only remembers not to ask you the last " . $random_questions_to_remeber . " questions you've answered."; 
		}
		
		// add the forgert link
		$result .= " <a href=\"" . get_site_URL() . "forget\">Forget</a>.";
		
	}
	else
	{
		$result .= "You've not answered any questions recently.";
	}
	
	return $result;
}

function get_site_URL($check_if_ssl = false)
{
	global $site_URL;
	if ($check_if_ssl && is_secure_https())
	{
		// we want the https URL
		return get_secure_site_URL();
	}
	else
	{
		return $site_URL;
	}
}

function get_secure_site_URL()
{
	global $site_URL;
	return str_replace("http://", "https://" , $site_URL);
}

function get_http_or_https()
{
	if ($_SERVER["HTTPS"] == "on")
	{
		return "https";
	} 
	else 
	{
		return "http";
	}
}


function force_secure()
{
	// if we want to force HTTPS
	// if HTTPS is already on, everything is fine
	if ($_SERVER["HTTPS"] == "on")
	{
		return true;
	}
	
	// redirect to HTTPS & end script execution
	header('Location: ' . preg_replace("/http/", "https", strtolower(curPageURL()), 1));
	exit;
}

function is_secure_https()
{
	global $site_url;
	
	if ($_SERVER["HTTPS"] == "on")
	{
		return true;
	} 
	else 
	{
		return false;
	}
}

function get_CSS_URL($type = false)
{
	if ($type)
	{
		if ($type == "print")
		{
			return get_site_URL(true) . "css/print.css?v=" . filemtime("css/print.css");
		}
		if ($type == "minify")
		{
			return get_site_URL(true) . "css/style-min.css?v=" . filemtime("css/style-min.css");
		}
	}
	return get_site_URL() . "css/style.css?v=" . filemtime("css/style.css");
}

function get_CSS_embed($type = false)
{
	if ($type)
	{
		if ($type == "print")
		{
			return "
				    <style type=\"text/css\">
				        @media print {
							body	
							{
								font-size:12px;
								max-width: 100%;	
							}
							
							.footer
							{
								display:none;
							}
							
							
							.print_footer 
							{
								display: block;
							}
				        }
				    </style>
			";
		}
		if ($type == "minify")
		{
			return "<link rel=\"stylesheet\" href=\"" . get_site_URL(true) . "css/style-min.css?v=" . filemtime("css/style-min.css") . "\" type=\"text/css\" >";
	
		}
	}
	return "<link rel=\"stylesheet\" href=\"" . get_site_URL(true) . "css/style.css?v=" . filemtime("css/style.css") . "\" type=\"text/css\" >";
	
	
	return ;	
	

}

function is_error()
{
	global $error_string;
	if ($error_string)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function get_error_string($nice_formatting = true)
{
	global $error_string;
	if ($nice_formatting)
	{
		return htmlentities($error_string);
	}
	else
	{
		return $error_string;
	}
}

function add_google_chart_drawChart($req_script)
{
	global $drawChart_script_array;
	$drawChart_script_array[] = $req_script;
}

function get_google_chart_script()
{
	global $drawChart_script_array;
	if ($drawChart_script_array)
	{
		$script_string = implode("\n", $drawChart_script_array);
		
		$script .= '<script type="text/javascript" src="https://www.google.com/jsapi"></script>';
	   	$script .= '<script type="text/javascript">
	   	
	   		var options_user_section_totals;
	   		var data_user_section_totals;
	   		var data_stats_user_progress;
	   		var options_stats_user_progress;
	   		
	      google.load("visualization", "1", {packages:["corechart"]});
	      google.setOnLoadCallback(drawChart);
	      function drawChart() {
	        ' . $script_string . '
	      }
	    </script>';
	}
	return $script;
}

function get_recent_wrong_questions()
{
	global $user, $mydb;
	// get all the incorrect responses from the past 2 weeks
	
	$questions = get_questions_from_User_ID($user->get_ID(), 20, 1209600, true);
	
	if ($questions)
	{
		$out .= "<h3>Recent questions you got wrong</h3>";
		$out .= "<p class=\"small_p\">";
		foreach ($questions as $question)
		{
			$question_string = $question->get_Text();
			if (strlen($question_string) > 100)
			{
				$question_string = substr($question_string, 0, 97) . "...";
			}
			
			$out .= $question->get_Section() . " <a href=\"" . $question->get_URL() . "\">" . htmlentities(stripslashes($question_string)) . "</a><br />";
		}
		$out .= "</p>";
	}
	
	return $out;
}

function get_recent_questions()
{
	global $user, $mydb;
	// get all the  responses from the past 24 hours
	
	$questions = get_questions_from_User_ID($user->get_ID(), 20, 86400, false);
	
	$out .= "<h3>All the questions you've answered in the past 24 hours</h3>";
	
	if ($questions)
	{
		$out .= "<p class=\"small_p\">";
		foreach ($questions as $question)
		{
			$question_string = $question->get_Text();
			if (strlen($question_string) > 100)
			{
				$question_string = substr($question_string, 0, 97) . "...";
			}
			
			$out .= $question->get_Section() . " <a href=\"" . $question->get_URL() . "\">" . htmlentities(stripslashes($question_string)) . "</a><br />";
		}
		$out .= "</p>";
	}
	else 
	{
		$out .= "<p class=\"small_p\">You've not answered any questions, <a href=\"" . get_site_URL() . "\">best fix that</a>.</p>";
	}
	
	return $out;
}


function get_formatted_admin_report($report)
{
	$out .= '<br />
		 <a href="' . get_site_URL() . 'admin/edit/' . $report->get_Question_ID() . '">' . $report->get_Question_ID() . '</a>
		(' . get_formatted_admin_report_links($report) . '):' . nl2br(htmlentities(stripslashes($report->get_Text()))) . '<br />';
	
	return $out;
}

function get_formatted_admin_report_links($report)
{
	$out .= '<a href="' . get_site_URL() . 'admin/?update_report=' . $report->get_ID() . '&new_status=fixed">fixed</a>, 
		 <a href="' . get_site_URL() . 'admin/?update_report=' . $report->get_ID() . '&new_status=incorrect">incorrect</a>, 
		 <a href="' . get_site_URL() . 'admin/?update_report=' . $report->get_ID() . '&new_status=clarified">clarified</a>, 
		 <a href="' . get_site_URL() . 'admin/?update_report=' . $report->get_ID() . '&new_status=noaction">no action taken</a>';
	
	return $out;
}

function get_page_description()
{
	global $question;
	
	if (!is_random_question() && is_question()) 
	{ 
		$out = htmlspecialchars(stripslashes("Queston #" . $question->get_ID() . " - " . $question->get_Text())); 
	} 
	else
	{ 
		$out = "A free online WFTDA roller derby rules test with hundreds of questions. Turn left and learn the rules.";
	}
	return $out;
}

function get_open_report_count($reports_open = false)
{
	global $mydb, $global_reports_open_count;
	
	// have we been given the open reports array?
	if ($reports_open)
	{
		$global_reports_open_count = count($reports_open);
		return $global_reports_open_count;
	}
	
	//need to see if we've already worked it out
	if (isset($global_reports_open_count))
	{
		return $global_reports_open_count;
	}
	
	// need to work it out and remeber it
	$reports_open = $mydb->get_reports(REPORT_OPEN);
		
	if ($reports_open && count($reports_open) > 0)
	{
		$global_reports_open_count = count($reports_open);
	}
	else
	{
		$global_reports_open_count = 0;
	}
	
	return $global_reports_open_count;	
}

function get_open_report_count_string()
{
	if (get_open_report_count() > 0)
	{
		return " (" . get_open_report_count() . ")";
	}
	else
	{
		return "";
	}
}

function get_admin_terms_checkboxes($term, $question = false)
{
	global $mydb;
	
	$terms = $mydb->get_terms($term);
	
	if ($terms)
	{
		if ($question)
		{
			$question_terms = $question->get_terms($term);
		}
		
		foreach($terms as $term)
		{
			$selected_string = "";
			if ($question_terms)
			{
				// is this rule set already chosen for this question?
				
				foreach ($question_terms as $question_term)
				{
					if ($question_term->get_ID() == $term->get_ID())
					{
						$selected_string = "checked";
					}
				}
			}
			
			// special case where we want the Author
			if ($term->get_taxonomy() == "author-id")
			{
				$tmp_user = $mydb->get_user_from_ID($term->get_Name());
				$display_name = $tmp_user->get_Name();
			}
			else
			{
				$display_name = $term->get_Name();
			}
			
			$out .= "<input $selected_string type=\"checkbox\" id=\"term_checkbox[" . $term->get_ID() . "]\" name=\"term_checkbox[" . $term->get_ID() . "]\">" . htmlentities(stripslashes($display_name)) . "<br />";
		}
		
	}
	else
	{
		$out .= "No terms found";
	}	
	
	return $out;
}

function get_admin_terms_checkboxes_ajax($term, $question)
{
	global $mydb;
	
	$terms = $mydb->get_terms($term);
	
	if ($terms)
	{
		if ($question)
		{
			$question_terms = $question->get_terms($term);
		}
		
		foreach($terms as $term)
		{
			$style = "";
			if ($question_terms)
			{
				// is this rule set already chosen for this question?
				
				foreach ($question_terms as $question_term)
				{
					if ($question_term->get_ID() == $term->get_ID())
					{
						$style = "style=\"font-weight: bold;\"";
						
					}
					else
					{
						
					}
				}
			}
			
			// special case where we want the Author
			if ($term->get_taxonomy() == "author-id")
			{
				$tmp_user = $mydb->get_user_from_ID($term->get_Name());
				$display_name = $tmp_user->get_Name();
			}
			else
			{
				$display_name = $term->get_Name();
			}
			
			//$bonus = "";
			//if (!$style && ($term->get_ID() == 3))
			//	$bonus = "xx";
				
			$out .= "<a $style id=\"term_" . $term->get_ID() . "_" . $question->get_ID() . "\" onclick=\"toggle_term_relationship(" . $term->get_ID() . ", " . $question->get_ID() . ")\">" . htmlentities(stripslashes($display_name)) . "</a> ";
		}
		
	}
	else
	{
		$out .= "No terms found";
	}	
	
	return $out;
}

?>