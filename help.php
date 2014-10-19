<?php 
require_once 'includes/QBCoref.class.php';
$qbc = new QBCoref();
?>
<!doctype html>
<html lang="us">
<head>
	<meta charset="utf-8">
	<title>Coreference Tool - FAQs</title>
	<link href="css/smoothness/jquery-ui-1.10.4.custom.css" rel="stylesheet">
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
	<link href="css/coref.css" rel="stylesheet">
	<script src="js/jquery-1.10.2.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery-ui-1.10.4.custom.js"></script>
	<style type="text/css">
	.lst-kix_hh2brkz0khcb-3>li:before {
		content: "" counter(lst-ctn-kix_hh2brkz0khcb-3, decimal) ". "
	}
	
	ol.lst-kix_hh2brkz0khcb-8 {
		list-style-type: none
	}
	
	ol.lst-kix_hh2brkz0khcb-7 {
		list-style-type: none
	}
	
	ol.lst-kix_hh2brkz0khcb-6 {
		list-style-type: none
	}
	
	ol.lst-kix_hh2brkz0khcb-5 {
		list-style-type: none
	}
	
	ol.lst-kix_hh2brkz0khcb-1.start {
		counter-reset: lst-ctn-kix_hh2brkz0khcb-1 0
	}
	
	ol.lst-kix_hh2brkz0khcb-4 {
		list-style-type: none
	}
	
	ol.lst-kix_hh2brkz0khcb-3 {
		list-style-type: none
	}
	
	ol.lst-kix_hh2brkz0khcb-2 {
		list-style-type: none
	}
	
	ol.lst-kix_hh2brkz0khcb-4.start {
		counter-reset: lst-ctn-kix_hh2brkz0khcb-4 0
	}
	
	.lst-kix_hh2brkz0khcb-1>li {
		counter-increment: lst-ctn-kix_hh2brkz0khcb-1
	}
	
	ol.lst-kix_hh2brkz0khcb-1 {
		list-style-type: none
	}
	
	ol.lst-kix_hh2brkz0khcb-0 {
		list-style-type: none
	}
	
	ol.lst-kix_hh2brkz0khcb-7.start {
		counter-reset: lst-ctn-kix_hh2brkz0khcb-7 0
	}
	
	ol.lst-kix_hh2brkz0khcb-2.start {
		counter-reset: lst-ctn-kix_hh2brkz0khcb-2 0
	}
	
	.lst-kix_hh2brkz0khcb-4>li:before {
		content: "" counter(lst-ctn-kix_hh2brkz0khcb-4, lower-latin) ". "
	}
	
	.lst-kix_hh2brkz0khcb-7>li:before {
		content: "" counter(lst-ctn-kix_hh2brkz0khcb-7, lower-latin) ". "
	}
	
	.lst-kix_hh2brkz0khcb-2>li:before {
		content: "" counter(lst-ctn-kix_hh2brkz0khcb-2, lower-roman) ". "
	}
	
	ol.lst-kix_hh2brkz0khcb-5.start {
		counter-reset: lst-ctn-kix_hh2brkz0khcb-5 0
	}
	
	.lst-kix_hh2brkz0khcb-6>li:before {
		content: "" counter(lst-ctn-kix_hh2brkz0khcb-6, decimal) ". "
	}
	
	.lst-kix_hh2brkz0khcb-0>li:before {
		content: "" counter(lst-ctn-kix_hh2brkz0khcb-0, decimal) ". "
	}
	
	.lst-kix_hh2brkz0khcb-3>li {
		counter-increment: lst-ctn-kix_hh2brkz0khcb-3
	}
	
	.lst-kix_hh2brkz0khcb-6>li {
		counter-increment: lst-ctn-kix_hh2brkz0khcb-6
	}
	
	.lst-kix_hh2brkz0khcb-2>li {
		counter-increment: lst-ctn-kix_hh2brkz0khcb-2
	}
	
	ol.lst-kix_hh2brkz0khcb-3.start {
		counter-reset: lst-ctn-kix_hh2brkz0khcb-3 0
	}
	
	.lst-kix_hh2brkz0khcb-8>li {
		counter-increment: lst-ctn-kix_hh2brkz0khcb-8
	}
	
	.lst-kix_hh2brkz0khcb-4>li {
		counter-increment: lst-ctn-kix_hh2brkz0khcb-4
	}
	
	.lst-kix_hh2brkz0khcb-5>li:before {
		content: "" counter(lst-ctn-kix_hh2brkz0khcb-5, lower-roman) ". "
	}
	
	.lst-kix_hh2brkz0khcb-8>li:before {
		content: "" counter(lst-ctn-kix_hh2brkz0khcb-8, lower-roman) ". "
	}
	
	ol.lst-kix_hh2brkz0khcb-8.start {
		counter-reset: lst-ctn-kix_hh2brkz0khcb-8 0
	}
	
	.lst-kix_hh2brkz0khcb-0>li {
		counter-increment: lst-ctn-kix_hh2brkz0khcb-0
	}
	
	.lst-kix_hh2brkz0khcb-1>li:before {
		content: "" counter(lst-ctn-kix_hh2brkz0khcb-1, lower-latin) ". "
	}
	
	ol.lst-kix_hh2brkz0khcb-0.start {
		counter-reset: lst-ctn-kix_hh2brkz0khcb-0 0
	}
	
	.lst-kix_hh2brkz0khcb-7>li {
		counter-increment: lst-ctn-kix_hh2brkz0khcb-7
	}
	
	.lst-kix_hh2brkz0khcb-5>li {
		counter-increment: lst-ctn-kix_hh2brkz0khcb-5
	}
	
	ol.lst-kix_hh2brkz0khcb-6.start {
		counter-reset: lst-ctn-kix_hh2brkz0khcb-6 0
	}
	
	ol {
		margin: 0;
		padding: 0
	}
	
	.c13 {
		vertical-align: baseline;
		color: #000000;
		font-size: 11pt;
		font-style: normal;
		font-family: "Arial";
		text-decoration: none;
		font-weight: normal
	}
	
	.c9 {
		line-height: 1.0;
		padding-top: 0pt;
		text-align: left;
		padding-bottom: 0pt

	}
	
	.c16 {
		max-width: 468pt;
		background-color: #ffffff;
		padding: 72pt 72pt 72pt 72pt
	}
	
	.c6 {
		margin: 5px;
		border: 1px solid black
	}
	
	.c7 {
		color: #333333;
		background-color: #00ff00
	}
	
	.c5 {
		color: #333333;
		background-color: #c9daf8
	}
	
	.c4 {
		color: #333333;
		background-color: #ffff00
	}
	
	.c3 {
		color: #333333;
		background-color: #ff9900
	}
	
	.c14 {
		background-color: #ff9900
	}
	
	.c11 {
		text-decoration: underline
	}
	
	.c15 {
		background-color: #00ff00
	}
	
	.c0 {
		font-weight: bold
	}
	
	.c2 {
		direction: ltr
	}
	
	.c1 {
		font-style: italic
	}
	
	.c10 {
		background-color: #ff00ff
	}
	
	.c17 {
		background-color: #f5f5f5
	}
	
	.c12 {
		height: 11pt
	}
	
	.c8 {
		color: #333333
	}
	
	</style>
</head>
<body>
	<?php 
		$active_page = 'help';
		require_once 'views/topnav.php';
	?>

    <div class="container">
    	<div class="row">
    		<div class="page-header">
				<h2>Frequently Asked Questions</h2>
			</div>
    		<p class="c2">
				<span class="c0">What is a coreference?</span>
			</p>
			<p class="c2">
				<span>A </span><span class="c1">coreference</span><span>&nbsp;occurs
					when two or more words or phrases refer to the same person or thing.
					Take for instance the following quizbowl question:</span>
			</p>
			<p class="c2">
				<span class="c3 c1">One character</span><span class="c1 c8">&nbsp;in </span><span
					class="c7 c1">this work</span><span class="c1 c8">&nbsp;kneels to pay
					homage to a one hundred year old cupboard. </span><span class="c1 c3">That
					character</span><span class="c1 c8">&nbsp;frequently references
					billiards and is named </span><span class="c3 c1">Gayef</span><span
					class="c1 c8">. </span><span class="c1 c8 c10">One character&#39;s</span><span
					class="c1 c8">&nbsp;son Grisha drowned, leading to</span><span
					class="c1 c8">&nbsp;</span><span class="c1 c10 c8">that
					character&#39;s</span><span class="c1 c8">&nbsp;departure for France.
				</span><span class="c1 c10 c8">That character</span><span class="c1 c8">&nbsp;rejects
					a suggestion to build and rent out villas to pay a mortgage. At one
					point, </span><span class="c4 c1">the servant Firs</span><span
					class="c1 c8">&nbsp;claims that </span><span class="c1 c4">he</span><span
					class="c1 c8">&nbsp;is ready to die now that Madame Ranevsky has
					returned home. At a party late in this work, </span><span
					class="c1 c5">Lopakhin</span><span class="c1 c8">&nbsp;announces that
				</span><span class="c5 c1">he</span><span class="c1 c8">&nbsp;has
					purchased the </span><span class="c1 c7">title estate</span><span
					class="c1 c8">. For 10 points, name </span><span class="c7 c1">this
					final play by Anton Chekhov</span><span class="c1 c8">.</span>
			</p>
			<p class="c2">
				<span class="c1">A: </span><span class="c1 c15">The Cherry Orchard</span>
			</p>
			<p class="c2">
				<span>Each highlighted color indicates a different coreference group.
					For example, all text in </span><span class="c15">green</span><span>&nbsp;refers
					to the answer of the question, while all text in </span><span
					class="c14">orange</span><span>&nbsp;refers to the character </span><span
					class="c1">Gayef</span><span>. Since references can be multiple words
					(e.g. </span><span class="c1">&ldquo;that character&rdquo;,
					&ldquo;this work&rdquo;</span><span>), </span>
			</p>
			<p class="c2">
				<span class="c0">How do I get started?</span>
			</p>
			<p class="c2">
				<span>To get started, select a word or phrase and then press a number
					on your keyboard corresponding to the appropriate coreference group.
					If you press &ldquo;1&rdquo;, your selected text will be marked as a
					coreference with the answer of the question. If you press numbers 2-9
					you will be able to mark text as a coreference to other words or
					phrases in the question marked with the same number. </span>
			</p>
			<p class="c2">
				<span>In the example question above, we would highlight </span><span
					class="c1">&ldquo;this work&rdquo;</span><span>&nbsp;in the first
					sentence and press number 1 to indicate a coreference with the answer.
					For non-answer coreferences, we do exactly the same thing except with
					numbers 2-9. </span>
			</p>
			<p class="c2">
				<span>Since coreferences can include multi-word expressions as well as
					single words, you may encounter ambiguous cases. Take as an example
					the phrase &ldquo;</span><span class="c1">this final play by Anton
					Chekhov</span><span>&rdquo;. This phrase refers to the answer of the
					question, but so does the word &ldquo;</span><span class="c1">play</span><span>&rdquo;
					contained within this phrase, as well as the smaller phrases &ldquo;</span><span
					class="c1">final play</span><span>&rdquo; and &ldquo;</span><span
					class="c1">this final play</span><span>&rdquo;. In such cases, we
					encourage you to select the largest possible phrase that is still a
					coreference.</span>
			</p>
			<p class="c2">
				<span class="c0">How much of a coreference should I highlight?</span>
			</p>
			<p class="c2">
				<span>A good rule of thumb is to highlight everything that could be
					replaced by a pronoun and still make sense.</span>
			</p>
			<p class="c2">
				<span>Consider the sentence &ldquo;This author of </span><span
					class="c1">Down and out in London and Paris</span><span>&nbsp;fought
					in the Spanish Civil War.&rdquo; &nbsp;If you only highlight </span><span
					class="c11">author</span><span>, you get &ldquo;This he of </span><span
					class="c1">Down and out in London and Paris</span><span>&nbsp;fought
					in the Spanish Civil War,&rdquo; which doesn&rsquo;t make sense.
					&nbsp;If you only highlight </span><span class="c11">this author</span><span>,
					you get &ldquo;he of </span><span class="c1">Down and out in London
					and Paris</span><span>&nbsp;fought in the Spanish Civil War,&rdquo;
					which makes more sense but still isn&rsquo;t comprehensible. &nbsp;The
					annotation that makes the most sense is </span><span class="c11">This
					author of </span><span class="c1 c11">Down and out in London and Paris</span><span>,
					which produces the sentence, &ldquo;he fought in the Spanish Civil
					War,&rdquo; which makes complete sense.</span>
			</p>
			<p class="c2">
				<span class="c0">What if I run out of group numbers?</span>
			</p>
			<p class="c2">
				<span>It is possible that for several questions you may need more than the 18 group numbers currently available. If this happens please use your best judgement to highlight the most important coreferences and don't worry about the remaining coreferences that you do not have group numbers for.</span>
			</p>
			<p class="c2">
				<span class="c0">What if I&rsquo;m uncertain?</span>
			</p>
			<p class="c2">
				<span>If you don&rsquo;t know whether a particular word or phrase is a
					coreference, then don&rsquo;t mark it. &nbsp;Only mark coreferences
					that you&rsquo;re relatively sure of.</span>
			</p>
			<p class="c2">
				<span class="c0">What about appositives? &nbsp;Does it matter when they are set off by commas?</span>
			</p>
			<p class="c2">
				<span>They both refer to the same person, but they are different ways of referring to the person.  
					So you should mark both of them. For example, if you see The Thought-Criminal Winston, then you would highlight 
					&ldquo;The Thought-Criminal&rdquo; and &ldquo;Winston&rdquo; individually referring to the same entity.</span>
			</p>
			<p class="c2">
				<span class="c0">Do you really want us to highlight prepositions? &nbsp;What about the case &ldquo;name this novel about Emma&rdquo;?  
				Wouldn&rsquo;t it make sense as &ldquo;name it about her&rdquo;?</span>
			</p>
			<p class="c2">
				<span>Because of technical syntax reasons, we want all of the prepositional phrase to be marked. 
					So you should mark all of the reference, starting with &ldquo;this novel&rdquo; until the end of the prepositional phrase.
					So you should mark all of &ldquo;this novel about Emma&rdquo;. This will generate some long phrases, so you will often have
					to use nested annotations to mark things within the prepositional phrase.<br>
					If you&rsquo;re interested in the technical explanation, the reason that we want this is that automatic parsers 
					consider prepositional phrases to be &ldquo;under&rdquo; the nouns that they modify, so we want to capture the entire phrase.</span>
			</p>
			<p class="c2">
				<span class="c0">How should we handle &ldquo;titular&rdquo; references?</span>
			</p>
			<p class="c2">
				<span>Use your best judgement. This is really tricky. <br>
                                  You should definitely mark titular references when there&rsquo;s no ambiguity.
                                  For example, &ldquo;Delta Wedding&rdquo; and &ldquo;titular event&rdquo; should definitely be marked as being a coreference.<br>
				      However, please don&rsquo;t mark coreference when it&rsquo;s only a &ldquo;titular&rdquo; part of the title.  
				      For instance, if the answer were &ldquo;Tinker, Tailor, Solider, Spy&rdquo;, and there was a reference to &ldquo;one titular character&rdquo;, 
                                  you should <b>NOT</b> mark that as a reference to the title. This is something we would like to support in the future, but we do not support it yet.</span>
			</p>
			<p class="c2">
				<span class="c0">This question is terrible! Why is it even here?</span>
			</p>
			<p class="c2">
				<span>First, if you don&rsquo;t like a question for any reason, please just skip it.
					We&rsquo;d rather you stay happy than for you to hold your nose and annotate a crappy question.<br> Second, there is a method to our madness.
					When we collected data about player ability, it was on this set of questions. We will want to compare our new algorithms on these same data.<br>
					That said, we&rsquo;re working on adding more questions to the database from QBDB.  </span>
			</p>

			<p class="c2">
				<span class="c0">What&rsquo;s the leaderboard? &nbsp;How can I be
					eligible for a prize?</span>
			</p>
			<p class="c2">
				<span>The leaderboard tracks how many questions you have annotated.
					&nbsp;We will award prizes as follows: the top three annotators will
					receive gift cards; three randomly chosen annotators will receive gift
					cards based on the number of questions they have annotated. &nbsp;In
					each case, we will verify that the annotations are correct before
					awarding prizes.</span>
			</p>
			<p>&nbsp;</p>
			<p class="c2">
				<span>If you encounter any mistakes or would like to report any bugs,
					please create a <a href="http://github.com/dbouman/qb-coref/issues" target="_blank">new issue</a>.</span>
			</p>
		</div>
	</div>
	<?php 
		require_once 'views/footer.php';
	?>
</body>
</html>




