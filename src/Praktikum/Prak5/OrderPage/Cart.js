    var totalPrice;

    function showNotification(name) {
        const noti = new Notification("Pizza Bestellung",{
            body: name+" added to Cart"
        })
    }
    function isValid(){
    "use strict";

        let select = document.getElementById("Cart");
        let text = document.getElementById("Address").value;

        if (!select.options[0]) {
            alert("Bitte mindesten ein Pizza bestellen");
            return false;
        }
        else if (text == "") {
            alert("Geben Sie bitte eine gültige Addresse")
            return false;
        }
        else {
            for (let i= 0; i< select.options.length ; i++){
                if (select.options[i].selected) continue;
                else select.options[i].selected = 'selected';
            }
            return true;
        }
    }
    function add(pizza_id, name, price){
        "use strict";
        let select = document.getElementById("Cart");
        select.style.minWidth="25vw"
        let option = document.createElement('option');
        option.textContent = name;
        option.value = pizza_id;
        option.setAttribute("data-price", price);
        select.add(option)
        let total = document.getElementById("totalPrice");
        totalPrice = parseFloat(price.replace(",", ".")) + parseFloat(total.textContent)
        total.textContent = totalPrice.toFixed(2).toString()
        if (Notification.permission ==="granted"){
            showNotification(name);
        }else if (Notification.permission !== "denied"){
            Notification.requestPermission().then(permission =>{
                if (permission === "granted"){
                    showNotification(name);
                }
            })
        }
        return false;
    }

    function clearCart(){
        "use strict";
        let select = document.getElementById("Cart");
            while (select.hasChildNodes()){
            select.removeChild(select.firstChild);
            let total = document.getElementById("totalPrice");
            total.textContent = "0"

        }
    }
    function deleteOne() {
        "use strict";
        let select = document.getElementById("Cart");
        for (let i in select.options[select.selectedIndex]){
            let option = select.options[select.selectedIndex].getAttribute("data-price")
            let total = document.getElementById("totalPrice");
            totalPrice = parseFloat(total.textContent) - parseFloat(option)
            total.textContent = totalPrice.toFixed(2).toString()
            select.remove(select.selectedIndex);
        }

    }

    function checkSubmit() {
        "use strict";
        let select = document.getElementById("Cart");
        let address = document.getElementById("Address");
        let submit = document.getElementById("btnSubmit");

        submit.disabled = address.value.length === 0 || !select.hasChildNodes();

    }


