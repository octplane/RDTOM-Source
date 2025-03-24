<?php

// editing a post
$tmp_question = get_question_from_ID($_POST['question_id']);
$qid = $tmp_question->get_ID();

$old_question_string = (string)$tmp_question;

// save all the answers submitted into an array
foreach ($_POST['answer'] as $id => $answer) {
    if (trim($answer)) {
        $is_correct = $_POST['correct'][$id] == 1;
        $temp_answer_array[] = new answer(-1, $_POST['question_id'], trim($answer), $is_correct);
    }
}


// have the answers changed? There may not be any answers.
if ($temp_answer_array && ($tmp_question->is_answers_different($temp_answer_array))) {

    // delete existing questions
    $mydb->remove_answers_given_questionID($tmp_question->get_ID());
    $message .= "Answers deleted! ";

    // save all the answers
    foreach ($_POST['answer'] as $id => $answer) {
        if (trim($answer)) {
            $is_correct = $_POST['correct'][$id] == 1;
            add_answer($qid, trim($answer), $is_correct);
        }
    }
    $message.= "Answers saved! ";
} else {
    $message.= "Answers unchanged! ";
}

// edit the question
edit_question($qid, $_POST['question_text'], $_POST['question_section'], trim($_POST['question_notes']));
$message.= "Question edited! ";

// check the applicable rule set
// remove all relationships
$mydb->remove_relationship_given_Question_ID($qid);
$message.= "Relationships Removed! ";

// build new ones
if ($_POST['term_checkbox']) {
    foreach ($_POST['term_checkbox'] as $term_ID => $data) {
        $mydb->add_relationship($qid, $term_ID);
    }
    $message.= "Relationships Rebuilt! ";
}
// save a comment
$comment_text = "Question Edited \n\nFrom: \n " . $old_question_string . " \nTo: \n" . get_question_from_ID($qid);

// make a new comment
$comment = new comment(-1, $user->get_ID(), $qid, time(), $comment_text, QUESTION_CHANGED);

// save the comment
set_comment($comment);
?>
