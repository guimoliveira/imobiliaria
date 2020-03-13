var placeholders = ["Código do imóvel", "CPF do locador", "CPF do locatário", "CPF do cliente", "Nome do cliente"];

function searchMethodChanged() {
    var smEl = document.getElementById('search_method');
    var siEl = document.getElementById('search_input');

    var sm = parseInt(smEl.value);
    siEl.placeholder = placeholders[sm];
}

function confirmBox(title, msg, link) {
    document.getElementById('background_box').style.display = "block";
    document.getElementById('title').innerHTML = title;
    document.getElementById('msg').innerHTML = msg;
    document.getElementById('button_yes').href = link;
    
    document.getElementById('button_yes').innerHTML = "Sim";
    document.getElementById('button_no').innerHTML = "Não";
}

function promptBox(title, placeholder, link) {
    document.getElementById('background_box').style.display = "block";
    document.getElementById('title').innerHTML = title;
    document.getElementById('msg').innerHTML = "<input id='value' placeholder='"+placeholder+"' style='padding: 10px; width: 250px;'>";
    document.getElementById('button_yes').href = "#";
    document.getElementById('button_yes').onclick = function() {
        location.href = link + "&value=" + document.getElementById('value').value;
    };
    
    document.getElementById('button_yes').innerHTML = "OK";
    document.getElementById('button_no').innerHTML = "Cancelar";
}

function closeBox() {
    document.getElementById('background_box').style.display = "none";
    document.getElementById('confirmation_box').style.display = "none";
}