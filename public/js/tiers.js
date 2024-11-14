const clients = [
    { id: 1, code: "CLT001", nom: "LUMIER", adresse: 'Paris', tel: '06 03 40 85 76' },
    { id: 2, code: "CLT002", nom: "TASSE", adresse: 'Lyon', tel: '06 59 60 35 90' },
    { id: 3, code: "CLT003", nom: "CLAVIER", adresse: 'Rennes', tel: '07 93 04 03 28' },
    { id: 4, code: "CLT004", nom: "SOURIS", adresse: 'Marseille', tel: '06 38 72 93 00' },
    { id: 5, code: "CLT005", nom: "TOUCHE", adresse: 'Bordeaux', tel: '06 28 63 09 35' },
  ];

function showListClient() {
    let html = '';
    clients.forEach(function(client) {
        html += `
            <tr>
                <td class="w5 center">
                    <input type="checkbox" id=${client.id} name="choix" onclick="onlyOne(this)">
                </td>
                <td class="w15 center"><label for="${client.id}">${client.id}</label></td>
                <td class="w15 center"><label for="${client.id}">${client.code}</label></td>
                <td class="center"><label for="${client.id}">${client.nom}</label></td>
                <td class="w15 center">${client.adresse}</td>
                <td class="w20 center">${client.tel}</td>
            </tr>
        `
    })
    tbody_client.innerHTML = html;
    tfoot_th_client.innerHTML = `Vous avez ${clients.length} clients`
};
showListClient();

function creer() {
    let client = {id: 0, code: '', nom: '', adresse: '', tel: ''};
    remplirModal(client);
    protection(false);
    showModal.click();
};

function afficher() {
    let client_id = getIdChecked('choix');
    // console.log(client_id);
    let client = find(client_id);
    // console.log(client);
    if (client_id == 0) {
        alert('Veuillez selectionner une ligne');
        return;
    } else {
        remplirModal(client);
        protection(true);
        showModal.click();
    }
};

function modifier() {
    let client_id = getIdChecked('choix');
    let client = find(client_id);
    if (client_id == 0) {
        alert('Veuillez selectionner une ligne');
        return;
    } else {
        protection(false);
        remplirModal(client);
        showModal.click();
    }
};

function supprimer() {
    let client_id = getIdChecked('choix');
    let client = find(client_id);
    if (client_id == 0) {
        alert('Veuillez selectionner une ligne');
        return;
    } else {
        if (confirm(`Voulez-vous vraiment supprimer l'client ${client.code} - ${client.nom} ?`)) {
        remplirModal(client);
        protection(true);
        showModal.click();
        }
    }
};

function imprimer() {
    window.print();   
};


function quitter() {
    document.location.href="index.html";
};

function find(client_id) {
    let object = {};
    for (i = 0; i < clients.length; i++) {
        if (clients[i].id == client_id) {
            object = clients[i];
        }
    };
    return object;
};

function protection(etat) {
    id.disabled = true;
    numClient.disabled = etat;
    nomClient.disabled = etat;
    adresse.disabled = etat;
    tel.disabled = etat;
}

function remplirModal(object) {
    id.value = object.id;
    numClient.value = object.code;
    nomClient.value = object.nom;
    adresse.value = object.adresse;
    tel.value = object.tel;
};