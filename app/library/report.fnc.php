<?php
function report_question() {
    if (!$_POST) {
        header('Location: ' . get_site_URL());
        exit;
    }

    global $reportHasBeenFiled, $error_string;

    $questionID = $_POST['report_question_ID'];
    $report_string = "Question #" . $questionID . ": " . $_POST['report_text'];

    save_log("report", $report_string, $questionID);

    $report = new report(-1, get_ip() , time() , $questionID, 0, $report_string, REPORT_OPEN);
    set_report($report);

    // clear the input
    $_POST['report_text'] = false;
    $questionID = false;
    $reportHasBeenFiled = true;
}
