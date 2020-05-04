<?php
// display the page
include("header.php");
include("../objects/rule.class.php");


$page_title = "<i class=\"fa fa-question-circle\" aria-hidden=\"true\"></i> Question n&deg;" . $question->get_ID() . "&nbsp;:";

$question_text = htmlspecialchars(stripslashes($question->get_Text()));

?>


<div class="question-content">
<div class="question-box">
	<span class="question-block"><?= $page_title ?></span> <a class="question-block" href="<?php echo get_site_URL(); ?>">Nouvelle question
		<i class="fa fa-random" aria-hidden="true"></i>
	</a>
</div>
<div class="question-body">
	<p class="question-text"><?= $question_text ?></p>
<ol type="A">
	<?php
	foreach ($answers as $answer) {
		$quick_answer[] = $answer->get_ID();

		$correct_class = "correct_answer_link";

		if (!$answer->is_correct()) {
			$correct_class = "wrong_answer_link";
		}

		echo "<li>
			<a class=\"mobilebutton $correct_class\"  onclick=\"select_answer(" . $answer->get_ID() . ");\">" . htmlspecialchars(stripslashes($answer->get_Text())) . "</a>";
		if ($answer->is_correct()) {
			$section_string = "";


			echo " <span style=\"display:none;\" class=\"correct_answer_win\"><strong>Gagn&eacute;&nbsp;!</strong> " . $section_string . "</span><span style=\"display:none;\" class=\"correct_answer\"><strong> La bonne r&eacute;ponse.</strong> " . $section_string . "</span>";
		}
		else
		{
			echo " <span style=\"display:none;\" class=\"wrong_answer\" id=\"wrong_answer" . $answer->get_ID() . "\"><strong>Perdu&nbsp;!</strong></span>";
		}
		echo "</li>";
	}

	?>
</ol>
</div>
	<!--<?php if (is_admin() && $question->get_WFTDA_Link()) { ?>
	<div class="question-body rule-hint">
	<a href="#<?= $question->get_Section() ?>"><?php {
		echo "R&egrave;gle " . htmlspecialchars(stripslashes($question->get_Section())) . "<i class='fa fa-arrow-down' aria-hidden='true'></i></a>";
		echo " (<a target=\"_blank\" href=\"" . $question->get_WFTDA_Link() . "\" title=\"Section officielle des r&egrave;gles\" >voir sur WFTDA.org</a>)<br><br>";
	}

	$rule = new rule($question->get_WFTDA_Link());
	echo $rule->get_content_with_selection($question->get_Section());
	?>
	</div>
	<?php } ?>-->
</div>


<?php if ($question->get_Notes()) {?>
	<p  style="display:none;" class="question_notes">Note : <?php echo htmlspecialchars(stripslashes($question->get_Notes())); ?></p>
<?php } ?>

<!--<p>
	<a class="button mobilebutton" href="<?php echo get_site_URL(); ?>">
		Nouvelle question
		<i class="fa fa-random" aria-hidden="true"></i>
	</a>
</p>-->

<?php if ($question->get_Source()) {?>
	<p class="small_p" >Source : <?php echo htmlspecialchars(stripslashes($question->get_Source())); ?></p>
<?php } ?>


<p class="small_p">
	<span class="light-bulb"><i class="fa fa-lightbulb-o " aria-hidden="true"></i> raccourcis clavier</span>
</p>
<div class="shortcuts">
	<div><span class="keycap">a</span>
		<span class="keycap">b</span>
		<span class="keycap">c</span>
		<span class="keycap">d</span> choisir la réponse,<br>
		<span class="keycap">s</span> suivant

		</div>
</div>
<div 
	class="fb-comments" 
	data-href="http://www.rollerderbytestomatic.fr/question/<?php echo $question->get_ID() ?>"
	data-numposts="5"
	data-width=""></div>


<script type="text/javascript">
	var answered = false;

	function select_answer(selected)
	{
		if (!answered)
		{
			// make sure we only answer once
			answered = true;
			$(".rule-hint").show();
			// show what was right and what was wrong
			if (selected == <?php echo $correct_answer->get_ID()?>)
			{
				// correct!
				$(".correct_answer_win").show();
			}
			else
			{
				// wrong!
				$(".correct_answer").show();
				$("#wrong_answer" + selected).show();
			}

			<?php if ($question->get_Notes()) {?>
			// show the notes
			$(".question_notes").show();
			<?php } ?>

			// ajax save the response for stats tracking
			$.post("/ajax.php", {
				call: "save_response",
				question_ID: "<?php echo $question->get_ID(); ?>",
				response_ID: selected,
				return_remembered_questions_string: true},
				function(data) {
					$("#remembered_string").hide();
					$("#remembered_string").html(data);
					$("#remembered_string").fadeIn();
					$("#remembered_string_p").show();
				}
			);
		}
	}

	var allow_keypress = true;

	$(function() {
		 $(".light-bulb").hover(function () {
         	$(".shortcuts").toggle();
		 });
	});
	$(document).keypress(function(e) {
		if (allow_keypress)
		{
		    if((e.which == 78) || (e.which == 110) ||
				(e.which == 83) || (e.which == 115))
			{
		    	window.location.href = "<?php echo get_site_URL(); ?>";
		    }
		    <?php
		    for ($i = 0; $i < count($answers); $i++)
		    {
			    ?>
			    if((e.which == <?php echo $i + 49 ?>) || (e.which == <?php echo $i + 65 ?>) || (e.which == <?php echo $i + 97 ?>))
				{
			    	select_answer(<?php echo $quick_answer[$i]; ?>);
			    }
			    <?php
			}?>
		}
	});

</script>

<?php include("footer.php"); ?>
