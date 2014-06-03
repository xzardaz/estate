<h2>Frequently asked questions:</h2>
<br/><br />
<div id="faqs">
	<!--<h5>Contents:</h5>-->
	<div id="faqContents">
<?php
	foreach($data as $qna)
	{
?>


		<a href="#<?=$qna['id'];?>" class="faqQuestion"><?=$qna['question'];?></a>
		<br />

<?php
	};
?>

	</div>

<?php
	foreach($data as $qna)
	{
?>




	<div class="faqAnswer" id="<?=$qna['id']; ?>">
		<div class="faqAnswerHead">
			<?=$qna['question'];?>
		</div>
		<div class="faqAnswerContent">
			<?=$qna['answer'];?>
		</div>
	</div>
<?php
	};
?>

</div>
