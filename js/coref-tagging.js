// global variables
var tag_counter = 0;
var tags = new Object();
tags.length = 0;
var history = new Array();
var accuracy_shown = 0;
var charsToTrim = [",","."," "];

// initialize shortcut keys
function initShortcuts() {
	// coref hotkeys
	createHotkey('1');
	createHotkey('2');
	createHotkey('3');
	createHotkey('4');
	createHotkey('5');
	createHotkey('6');
	createHotkey('7');
	createHotkey('8');
	createHotkey('9');
	createHotkey('q');
	createHotkey('w');
	createHotkey('e');
	createHotkey('r');
	createHotkey('t');
	createHotkey('y');
	createHotkey('u');
	createHotkey('i');
	createHotkey('o');
	
	
	// prev hotkey and button
	if (prevqid != 2147483647) {
		createButtonAndHotkey('#prev', 'p', function(e) {
			document.location.href = 'index.php?qid=' + prevqid;
		});
	}
	
	// next hotkey and button
	if (nextqid != 2147483647) {
		createButtonAndHotkey('#next', 'n', function(e) {
			document.location.href = 'index.php?qid=' + nextqid;
		});
	}

	// undo hotkey and button
	createButtonAndHotkey('#undo', 'ctrl+z', function(e) {
		undoLastTag();
	});
	
	// check accuracy hotkey and button
	if (show_accuracy == 1) {
		createButtonAndHotkey('#check_accuracy', 'c', function(e) {
			toggleAccuracy();
		});
	}
	
	// show answer hotkey and button
	createButtonAndHotkey('#answer', 'a', function(e) {
		$("#answer_container").toggle('fade');
	});
}

function qbAutoSave() {
	$.post( "ajax.php?action=save", tags, function( data ) {
		$('#saved-state').fadeIn().delay(3000).fadeOut();
		$('#points').text(data);
	});
}

function changeLastTag(type) {
	if (tags.length == 0)
		return;
	
	// Remove previous tag
	var tag = history.pop(); // removes from history
	var annotation = tags[tag]['description'];
	var pos = tags[tag]['pos'];
	var old_type = tags[tag]['type'];
	$('#anno' + tag + ',#tag' + tag).remove();
	if ($('#group'+old_type+'-results').is(':empty')) {
		$('#group'+old_type+'-results').hide();
		$('#group'+old_type+'-header').hide();
	}
	delete tags[tag]; // remove from tags array
	tags.length--;
	
	// Add new tag groupping
	var tag_id = tag_counter;
	tag_counter++;
	
	appendAnnotation (type, tag_id, annotation);
	highlightText(type, tag_id, pos);
	
	// add to tags array
	tags[tag_id] = new Object();
	tags[tag_id]['id'] = tag_id;
	tags[tag_id]['description'] = annotation;
	tags[tag_id]['pos'] = pos;
	tags[tag_id]['type'] = type;
	tags.length++;
	
	// add to history
	history.push(tag_id);
	
	toggleClearButton();
	
	// Auto save
	qbAutoSave();
}

// create a new coreference tag
function createNewTag(type) {
	var annotation = $(question_id).selection('get');
	// If selection is empty, change last selection to new tag
	if (!annotation) {
		changeLastTag(type);
		return;
	}
	var tag_id = tag_counter;
	tag_counter++;
	var pos = $(question_id).selection('getPos');
	// Check for whitespace
	for (var i=0; i< charsToTrim.length; i++) {
		if (annotation.substr(0,1) == charsToTrim[i]) {
			pos['start'] += 1;
			annotation = annotation.substr(1);
		}
	}
	for (var i=0; i< charsToTrim.length; i++) {
		if (annotation.substr((annotation.length-1),1) == charsToTrim[i]) {
			pos['end'] -= 1;
			annotation = annotation.substr(0,(annotation.length-1));
		}
	}
	appendAnnotation (type, tag_id, annotation);
	highlightText(type, tag_id, pos);

	// add to tags array
	tags[tag_id] = new Object();
	tags[tag_id]['id'] = tag_id;
	tags[tag_id]['description'] = annotation;
	tags[tag_id]['pos'] = pos;
	tags[tag_id]['type'] = type;
	tags.length++;

	// add to history
	history.push(tag_id);

	// deselect text
	$(question_id).blur();
	
	toggleClearButton();
	
	// Auto save
	qbAutoSave();
	
	// Lookup accuracy
	getAccuracy(tag_id, annotation, pos);
}

// create coreference from existing tag
function createExistingTag(tag_id, annotation, pos, type) {
	appendAnnotation (type, tag_id, annotation);
	highlightText(type, tag_id, pos);
	
	tags[tag_id] = new Object();
	tags[tag_id]['id'] = tag_id;
	tags[tag_id]['description'] = annotation;
	tags[tag_id]['pos'] = pos;
	tags[tag_id]['type'] = type;
	tags.length++;

	history.push(tag_id);
	tag_counter++;
	
	toggleClearButton()
	
	// Lookup accuracy
	getAccuracy(tag_id, annotation, pos);
}

function appendAnnotation (type, tag_id, annotation) {
	$('#group'+type+'-results').append(
			'<div id="anno' + tag_id + '" class="coref' + type
					+ '"><a href="#" onclick="deleteTag(' + tag_id
					+ ',false)">[X]</a> ' + annotation + '<div id="accuracy' + tag_id + '" class="accuracy small"></div></div>').show();
	$('#group'+type+'-header').show();
}

function getAccuracy(tag_id, annotation, pos) {
	if (show_accuracy == 1) {
		$.post( "ajax.php?action=accuracy", {'qid': qid, 'description': annotation, 'pos': pos}, function( data ) {
			$('#accuracy'+tag_id).html(data);
		});
		
		if (accuracy_shown == 1) {
			$('#accuracy'+tag_id).show();
		}
	}
}

function toggleAccuracy() {
	if (show_accuracy == 1) {
		if (accuracy_shown == 1) {
			$(".accuracy").hide();
			accuracy_shown = 0;
		}
		else {
			$(".accuracy").show();
			accuracy_shown = 1;
		}
	}
}

// helper function to create color overlay
function highlightText(type, tag_id, pos) {
	var question_text = $(question_id).text();
	var start = pos.start;
	var end = pos.end;
	var highlighted_text = question_text.substr(0, start) + '<span class="coref'
			+ type + '">' + question_text.substr(start, (end - start))
			+ '</span>' + question_text.substr(end);
	var tid = 'tag' + tag_id;
	var new_div = $('<div id="' + tid + '" class="question-overlay"></div>');
	if (tags.length == 0 || tag_id == 0)
		new_div.insertAfter(question_id);
	else {
		new_div.insertAfter("#tag" + getLastTagNum());
	}
	$('#' + tid).html(highlighted_text);
}

// get id for last created tag
function getLastTagNum() {
	if (tags.length == 0)
		return;

	var last_tag = history[history.length - 1];
	return last_tag;
}

// undo the last created tag
function undoLastTag() {
	if (tags.length == 0)
		return;

	var tag = history.pop(); // removes from history
	$('#anno' + tag + ',#tag' + tag).remove();
	var type = tags[tag]['type'];
	if ($('#group'+type+'-results').is(':empty')) {
		$('#group'+type+'-results').hide();
		$('#group'+type+'-header').hide();
	}
	delete tags[tag]; // remove from tags array
	tags.length--;
	
	toggleClearButton();
	
	// Auto save
	qbAutoSave();
}

// delete a specific tag
function deleteTag(tag, ignore_save) {
	$('#anno' + tag + ',#tag' + tag).remove();
	var type = tags[tag]['type'];
	if ($('#group'+type+'-results').is(':empty')) {
		$('#group'+type+'-results').hide();
		$('#group'+type+'-header').hide();
	}
	delete tags[tag]; // remove from tags array
	tags.length--;
	history.splice(history.indexOf(tag), 1); // removes from history
	
	toggleClearButton();
	
	// Auto save
	if (!ignore_save) {
		qbAutoSave();
	}
}

function clearAll() {
	var r = confirm("Are you sure you want to remove all coreferences? You will NOT be able to undo this action.");
	if (r == true) {
		var i = history.length;
		while(history.length > 0) {
		    tag = history[i-1];
		    deleteTag(tag,true);
		    i--;
		}
		qbAutoSave();
	}
}

// Hide/Show clear button if needed
function toggleClearButton() {
	var element = $('#clear_all');
	if (tags.length == 0) {
		element.hide();
	}
	else if (!element.is(':visible')) {
		element.show();
	}
}

function createHotkey(key) {
	$(document).bind('keydown', key, function(e) {
		if (e.which < 90) { // Special exception to ignore F keys that have same ascii values
			changeLastTag(key);
		}
	});
	$('#question').bind('keydown', key, function(e) {
		createNewTag(key);
	});
}

function createButtonAndHotkey(element, key, action) {
	$(document).bind('keydown', key, function(e) {
		action();
	});
	$('#question').bind('keydown', key, function(e) {
		action();
	});
	$(element).click(function() {
		action();
	});
}