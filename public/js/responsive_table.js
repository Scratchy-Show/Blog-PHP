var tds = document.getElementsByTagName("td");

for(var i = 0; i < tds.length; i++){
    var td = tds[i];
    if(td.hasAttribute("headers")){
        var th = document.getElementById(td.getAttribute("headers"));
        if(th != null){
            td.setAttribute("data-headers", th.textContent);
        }
    }
}