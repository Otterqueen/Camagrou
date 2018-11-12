window.onload = function() {
	var limit = document.getElementById("limit");
    var offset = document.getElementById("offset");
    var bouton_next = document.getElementById("bouton_next");
    
    bouton_next.addEventListener("click", function()
                 { 
                    limit = limit+20;

                 });
	
}