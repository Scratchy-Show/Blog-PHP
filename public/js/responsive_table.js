let tds = document.getElementsByTagName("td");

for (let i = 0; i < tds.length; i++) {
    let td = tds[i];
    if (td.hasAttribute("headers")) {
        let th = document.getElementById(td.getAttribute("headers"));
        if (th != null) {
            td.setAttribute("data-headers", th.textContent);
        }
    }
}