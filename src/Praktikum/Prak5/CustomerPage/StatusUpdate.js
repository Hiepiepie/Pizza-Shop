const json = '{"pizzas":[["61","Vegetaria","0"],["61","Spinat-H\u00fchnchen","0"]]}'
const request = new XMLHttpRequest();

function requestData() { // fordert die Daten asynchron an
    request.open("GET", "CustomerStatus.php"); // URL für HTTP-GET
    request.onreadystatechange = processData; //Callback-Handler zuordnen
    request.send(null); // Request abschicken
}
function processData() {
    if(request.readyState == 4) { // Uebertragung = DONE
        if (request.status == 200) {   // HTTP-Status = OK
            if(request.responseText != null)
                process(request.responseText);// Daten verarbeiten
            else console.error ("Dokument ist leer");
        }
        else console.error ("Uebertragung fehlgeschlagen");
    } else ;          // Uebertragung laeuft noch
}
function printStatus(pizzas) {
    let element = document.getElementById("status");
    let node = document.createTextNode("Bestellung Nummer " + pizzas.pizzas[0][0]);
    for (let i = 0; i < pizzas.pizzas.length; i++) {
        let ordernr = pizzas.pizzas[i][0]
        let pizName = pizzas.pizzas[i][1]
        let status = pizzas.pizzas[i][2]
        switch (status) {
            case "0": {
                status = "Bestellt";
                break;
            }
            case "1": {
                status = "Im Ofen";
                break;
            }
            case "2": {
                status = "Fertig";
                break;
            }
            case "3": {
                status = "In Lieferung";
                break;
            }
            case "4": {
                status = "Geliefert";
                break;
            }
        }
        let para1 = document.createElement("p");
        para1.appendChild(node);
        let para2 = document.createElement("p");
        node = document.createTextNode(pizName + " : " + status);
        para2.appendChild(node);
        element.appendChild(para1);
        element.appendChild(para2);
    }
}

function process(JSONstring) {
    let element = document.getElementById("status");
    while (element.hasChildNodes()){
        element.removeChild(element.firstChild)
    }
    let pizzas = JSON.parse(JSONstring);

    printStatus(pizzas);
}