$(document).ready(function() {
	var i = 1;
	$('#hair').attr('src', 'images/hair1.jpg');
	
	setInterval(function() {
		$('#hair').attr('src', 'images/hair' + i++ + '.jpg');
		if (i > 4) i = 1;
	}, 3000);

	$('#services').hide();
	$('a.svc').click(function() {
		$('#services').show('slow');
	});

	$('#closesvc').click(function() {
		$('#services').hide();
	});

});