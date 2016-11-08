<?php
	if ($question){
		$sectionHeader = "Éditer la question n&deg;" . $question->get_ID() . ":";
		$formPostAction = get_site_URL() . "admin/edit/" . $question->get_ID();

		$questionSuccessColour = ColourFromPercentageCalculator::calculate($question->get_SuccessRate());

	} else {
		$sectionHeader = "Add question:";
		$formPostAction = get_site_URL() . "admin/";
	}
?>
<div class="layout_box" id="layout_box_edit_question">
	<h3><?php echo $sectionHeader; ?></h3>

	<form id="editquestionform" name="editquestionform" method="post" action="<?php echo $formPostAction; ?>">
		<table>

			<tr>
				<td style="width:200px">ID&nbsp;:</td>
				<td><input type="text"  id="question_id" name="question_id" value = "<?php
			if ($question)
			{
				echo $question->get_ID();
			}
			?>"></input> </td>
				</tr>
				<tr>
					<td style="width:200px">Question&nbsp;:</td>
					<td><textarea id="question_text" style="width:500px" name="question_text" cols="40" rows="5"><?php
					if ($question)
					{
						echo htmlspecialchars(stripslashes($question->get_Text()));
					}
					?></textarea></td>
				</tr>
				<tr>
					<td style="width:200px">Section&nbsp;:</td>
					<td><input type="text"  id="question_section" name="question_section" style="width:100px" value="<?php
					if ($question)
					{
						$section_value = htmlspecialchars(stripslashes($question->get_Section_String()));
					}
					else
					{
						$section_value = htmlspecialchars(addslashes($_POST['question_section']));
					}

					echo $section_value;
					?>"></input> <?php
					if ($section_value)
					{
						$section_array = explode(".", $section_value);
						if (count($section_array)>1 && is_numeric($section_array[count($section_array)-1]))
						{
							$section_array[count($section_array)-1]++;
							$incrimneted_section_array = implode(".", $section_array);
						}

						if ($incrimneted_section_array)
						{
							?><a id="incrimentbutton" onclick="$('input[name=\'question_section\']').val('<?php echo $incrimneted_section_array ?>'); $('#incrimentbutton').hide()">+</a><?php
						}

						if ($question && $question->get_WFTDA_Link())
						{
							?> <a target="_blank" href="<?php echo $question->get_WFTDA_Link(); ?>"> Link</a> <?php
						}

					}
					?>
					</td>
				</tr>
				<tr>
					<td>Réponses&nbsp;:<br />
					(cochez les réponses correctes)<br />
					(<a onclick="setdefaultanswers('tf');">Vr-Fx</a> <a onclick="setdefaultanswers('pen');">Pénalités</a>)</td>
					<td><?php
					for ($i=0; $i<NUMBER_OF_ANSWERS; $i++)
					{
						$checked = "";
						$value = "";

						// if we're editing
						if ($answers && $answers[$i])
						{
							if ($answers[$i]->is_correct())
							{
								$checked = " checked";
							}
							$value = htmlspecialchars(stripslashes($answers[$i]->get_Text()),ENT_QUOTES, "utf-8" );
						}

						// if we're remembering
						if ($_POST['rememberanswers'])
						{
							if ($_POST['correct'][$i] == 1)
							{
								$checked = " checked";
							}
							$value = $_POST['answer'][$i];

						}

						echo "<input type=\"checkbox\" id=\"correct[" . $i . "]\" name=\"correct[" . $i . "]\" value=\"1\" $checked /> <input style=\"width:500px\"  type=\"text\" id=\"answer[" . $i . "]\" name=\"answer[" . $i . "]\" value=\"$value\" /><br />";


					}?></td>
				<tr>
					<td style="width:200px">Notes :</td>
					<td>
					<?php
					if ($question && $question->get_Notes())
					{
					?>
						<textarea id="question_notes" style="width:500px" name="question_notes" cols="40" rows="5"><?php
						if ($question)
						{
							echo htmlspecialchars(stripslashes($question->get_Notes()));
						}
						?></textarea>
					<?php
					}
					else
					{
					?>
						<span id="question_notes_link"><a onclick="$('#question_notes_link').hide();$('#question_notes').slideDown();">Click pour ajouter une note</a></span>
						<textarea id="question_notes" style="width:500px; display:none;" name="question_notes" cols="40" rows="5"></textarea>
					<?php
					}?>

					</td>
				</tr>
				<tr>
					<td style="width:200px">Source :</td>
					<td>
						<?php
							echo get_admin_terms_checkboxes("source", $question);
						?>


					</td>
				</tr>
				<tr>
					<td style="width:200px">Rule Set :</td>
					<td>
						<?php
							echo get_admin_terms_checkboxes("rule-set", $question);
						?>
					</td>
				</tr>
				<tr>
					<td style="width:200px">Tag s:</td>
					<td>
						<?php
							echo get_admin_terms_checkboxes("tag", $question);
						?>
					</td>
				</tr>
				<tr>
					<td style="width:200px">Difficulté :</td>
					<td>
						<?php
							echo get_admin_terms_checkboxes("difficulty", $question);
						?>
					</td>
				</tr>
				<tr>
					<td style="width:200px">Auteur :</td>
					<td>
						<?php
							echo get_admin_terms_checkboxes("author-id", $question);
						?>
					</td>
				</tr>
				<tr>
					<td style="width:200px">Langue :</td>
					<td>
						<?php
							echo get_admin_terms_checkboxes("language", $question);
						?>
					</td>
				</tr>
				<tr>
					<td></td>
					<td><a class="question-block" id="edit_question_button" onclick="newquestionvalidation('editquestionform');return false;"/><?php
						if ($question)
						{
							echo "Editer la";
						}
						else
						{
							echo "Ajouter une";
						}
						?> question</a>
					</td>
				</tr>
				<tr>
					<td>Se souvenir des réponses :</td>
					<td><input <?php if ($_POST['rememberanswers']) { echo " checked"; }?> type="checkbox" value="yes" name="rememberanswers"/></td>
				</tr>
				<?php  if($question)
				{?>
				<tr>
					<td>Taux de bonnes réponses : </td>
					<td><?php echo "<span style=\"color: " . $questionSuccessColour . "\">" . $question->get_SuccessRate() . "%</span> (" . number_format($question->get_ResponseCount()) . " responses)"; ?></td>
				</tr>
				<?php  } ?>
			</table>
		</form>

		<?php
		if ($question)
		{
			?>
			<h3>Commentaires et signalements :</h3>
			<?php
			if ($question)
			{
				if ($question->get_comments())
				{
					foreach ($question->get_comments() as $comment)
					{
						$comment_and_reports[$comment->get_Timestamp()] = $comment;
					}
				}

				if ($question->get_reports(false))
				{
					foreach ($question->get_reports(false) as $report)
					{
						$comment_and_reports[$report->get_Timestamp()] = $report;
					}
				}

				if ($comment_and_reports)
				{
					ksort($comment_and_reports);

					foreach ($comment_and_reports as $comment_or_report)
					{
						if (get_class($comment_or_report) == "comment")
						{
							if ($comment_or_report->get_Type() == QUESTION_COMMENT)
							{
								echo "<hr>
								<p>
									<strong>" . htmlspecialchars($comment_or_report->get_author_name()) . "</strong> <i>" . date("D, jS M Y H:i", $comment_or_report->get_Timestamp()) . "</i>
								</p>
								<p>
									" . nl2br(htmlspecialchars(stripslashes($comment_or_report->get_text()))) . "
								</p>";
							}
							else
							{
								echo "<hr>
								<p class=\"small_p\">
									<span style=\"font-weight:bold; color:orange;\">
										Edit - " . htmlspecialchars($comment_or_report->get_author_name()) . "</span>
									<i>" . date("D, jS M Y H:i", $comment_or_report->get_Timestamp()) . "</i>
									<a id=\"edit_link_" . $comment_or_report->get_ID() . "_show\" onclick=\"$('#edit_link_" . $comment_or_report->get_ID() . "_show').hide(); $('#edit_link_" . $comment_or_report->get_ID() . "_hide').show(); $('#edit_text_" . $comment_or_report->get_ID() . "').show()\"/>Show</a>
									<a id=\"edit_link_" . $comment_or_report->get_ID() . "_hide\" onclick=\"$('#edit_link_" . $comment_or_report->get_ID() . "_show').show(); $('#edit_link_" . $comment_or_report->get_ID() . "_hide').hide(); $('#edit_text_" . $comment_or_report->get_ID() . "').hide()\" style=\"display:none;\"/>Hide</a>

								</p>
								<p class=\"small_p\">
									<span id=\"edit_text_" . $comment_or_report->get_ID() . "\" style=\"display:none;\">
									" . nl2br(htmlspecialchars(stripslashes($comment_or_report->get_text()))) . "
									</span>
								</p>";
							}
						}
						else
						{
							if ($comment_or_report->get_Status() == REPORT_OPEN)
							{
								$text_colour = "red";
							}
							else
							{
								$text_colour = "green";
							}
							echo "<hr>
							<p class=\"small_p\">
								<span style=\"font-weight:bold; color:$text_colour;\">Report #" . $comment_or_report->get_ID() . " - " . $comment_or_report->get_Status_String() . "</span> <i>" . date("D, jS M Y H:i", $comment_or_report->get_Timestamp()) . "</i>
							</p>
							<p class=\"small_p\">" . nl2br(htmlspecialchars(stripslashes($comment_or_report->get_Text()))) . "
							</p>
							<p class=\"small_p\">
								Set: " . get_formatted_admin_report_links($comment_or_report) . "
							</p>";
						}
					}
				}
			}
			?>
			<hr>
			<p><strong>Laisser un commentaire :</strong></p>
			<textarea id="question_comment_text" style="width:500px;" name="question_comment_text" cols="40" rows="5"></textarea>
			<input type="hidden" id="question_comment_question_id" name="question_comment_question_id" value="<?php echo $question->get_ID(); ?>"/>

			<br /><a class="question-block" id="save_comment_button" onclick="save_comment();return false;"/>Enregistrer un commentaire</a> <span id="question_comment_ajax_status"></span>

			<script type="text/javascript">
				function save_comment()
				{
					$('#question_comment_ajax_status').show();
					$('#question_comment_ajax_status').html("Saving...");

					// ajax save the response for stats tracking
					$.post("/ajax.php", {
						call: "save_comment",
						question_id: $('#question_comment_question_id').val(),
						text: $('textarea#question_comment_text').val()},
						function(data) {
							$('#question_comment_ajax_status').html("Saved! Reload the page to view.");
						}
					);

				}
			</script>
		<?php
		}
		?>
	</div>
