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




	<div class="faqAnswer" id="<?=$qna["mdId"];?>">
		<div class="faqAnswerHead" >
			<div><?=$qna['question'];?></div>
			<b><a href="#<?=$qna["id"];?>" class="editFAQ">edit</a></b>
			<b><a href="#<?=$qna["id"];?>" class="moveUpFAQ">move up</a></b>
			<b><a href="#<?=$qna["id"];?>" class="moveDownFAQ">move down</a></b>
			<b><a href="#<?=$qna["id"];?>" class="removeFAQ">remove</a></b>
		</div>
		<div class="faqAnswerContent">
			<?=$qna['answer'];?>
		</div>
	</div>
<?php
	};
?>

</div>
