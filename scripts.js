$(document).ready(function(){
    $('#btnExport').click(function(){
        $.ajax({
            url: 'csv.php',
            success: function(data) {
				var blob = new Blob([data]);
				var link = document.createElement('a');
				link.href = window.URL.createObjectURL(blob);
				var today = new Date();
				var dd = String(today.getDate()).padStart(2, '0');
				var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
				var yyyy = today.getFullYear();
				today = mm + '-' + dd + '-' + yyyy;
				link.download = "BallparkPicks_" + today + ".csv";
				link.click();
			  }
        });
    });

    $('form').bind('submit', function () {
        $.ajax({
                type: 'post',
                url: 'contact.php',
                data: $('form').serialize(),
                success: function () {
                    alert("Thanks for the inquiry. We'll get back to you as soon as we can.");
                }
            });
        });

    $('#btnAbout').click(function(){
        $("#divAbout").show();
        $("#divContact").hide();
    });
    $('#btnContact').click(function(){
        $("#divContact").show();
        $("#divAbout").hide();
    });
    $('#CloseAbout').click(function(){
        $("#divAbout").hide();
    });
    $('#CloseContact').click(function(){
        $("#divContact").hide();
    });
});
