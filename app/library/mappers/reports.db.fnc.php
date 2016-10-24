<?php
function get_report_from_array($req_array)
{
	return new report(
		$req_array['ID'],
		$req_array['IP'],
		$req_array['Timestamp'],
		$req_array['Question_ID'],
		$req_array['User_ID'],
		$req_array['Text'],
		$req_array['Status']);
}

function set_report($req_report)
{
	global $myPDO;

	// basic validation
	if (!$req_report->get_ID())
	{
		throw new exception ("Report not found");
	}

	if (!$req_report->get_Text())
	{
		throw new exception ("No text given for answer;");
	}

	if ($req_report->get_ID() <= 0)
	{
		$statement = $myPDO->prepare("
		INSERT
		INTO rdtom_reports
		(
			IP ,
			Timestamp ,
			Question_ID ,
			User_ID ,
			Text ,
			Status
		)
		VALUES
		(
			:IP ,
			:Timestamp ,
			:Question_ID ,
			:User_ID ,
			:Text ,
			:Status
		);");
	}
	else
	{
		$statement = $myPDO->prepare("
		UPDATE  rdtom_reports
		SET
			IP = :IP,
			Timestamp = :Timestamp,
			Question_ID = :Question_ID,
			User_ID = :User_ID,
			Text = :Text,
			Status =  :Status
		WHERE
			ID = :ID
		;");
		$statement->bindValue(':ID', $req_report->get_ID());

	}

	$statement->bindValue(':IP', $req_report->get_IP());
	$statement->bindValue(':Timestamp', $req_report->get_Timestamp());
	$statement->bindValue(':Question_ID', $req_report->get_Question_ID());
	$statement->bindValue(':User_ID', $req_report->get_User_ID());
	$statement->bindValue(':Text', $req_report->get_Text());
	$statement->bindValue(':Status', $req_report->get_Status());

	if(!$statement->execute()) {
		print_r($statement->errorInfo());
		exit();
	}
}

function report_execute_and_return($query, $parms) {
	global $myPDO;
	$statement = $myPDO->prepare($query);
	$statement->execute($parms);

	$results = $statement->fetchAll();

	if ($results)
	{
		foreach ($results as $result)
		{
			$out[] = get_report_from_array($result);
		}
	}
	return $out;
}

function get_report_from_ID($report_ID) {
	$query = "SELECT * FROM rdtom_reports WHERE ID = :ID LIMIT 1";
	$parms = array(
		':ID' => $report_ID
	);

	$out = report_execute_and_return($query, $parms);
	return $out[0];
}

function get_reports($status = false) {
	global $myPDO;

	$query = "SELECT * FROM rdtom_reports ";
	$parms = array(
	);

	if ($status !== false) {
		$query .= " WHERE Status = :STATUS";
		$parms[':STATUS'] = $status;
	}

	$query .= " ORDER BY Timestamp ASC";

	return report_execute_and_return($query, $parms);
}

function get_reports_from_question_ID($question_ID, $status = false)
{
	global $myPDO;

	$query = "SELECT * FROM rdtom_reports WHERE Question_ID = :ID";
	$parms = array(
		':ID' => $question_ID
	);

	if ($status !== false) {
		$query .= " AND Status = :STATUS";
		$parms[':STATUS'] = $status;
	}

	$query .= " ORDER BY Timestamp ASC";

	return report_execute_and_return($query, $parms);
}
?>