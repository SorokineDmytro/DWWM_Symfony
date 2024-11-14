const commandes = [
    { id: 1, code: "VTE001", dateCommande: "01/09/2024", nomClient: "LUMIER", telClient: '06 03 40 85 76', montant: 200.00 },
    { id: 2, code: "VTE002", dateCommande: "02/09/2024", nomClient: "TASSE", telClient: '06 59 60 35 90', montant: 300.00 },
    { id: 3, code: "VTE003", dateCommande: "03/09/2024", nomClient: "CLAVIER", telClient: '07 93 04 03 28', montant: 220.00 },
    { id: 4, code: "VTE004", dateCommande: "04/09/2024", nomClient: "SOURIS", telClient: '06 38 72 93 00', montant: 400.00 },
    { id: 5, code: "VTE005", dateCommande: "05/09/2024", nomClient: "TOUCHE", telClient: '06 28 63 09 35', montant: 450.00 },
  ];

function showListCommandes() {
    let html = '';
    commandes.forEach(function(commande) {
        html += `
            <tr>
                <td class="w5 center">
                    <input type="checkbox" id=${commande.id} name="choix" onclick="onlyOne(this)">
                </td>
                <td class="w10 center"><label for="${commande.id}">${commande.id}</label></td>
                <td class="w10 center"><label for="${commande.id}">${commande.code}</label></td>
                <td class="w15 center"><label for="${commande.id}">${commande.dateCommande}</label></td>
                <td class="center"><label for="${commande.id}">${commande.nomClient}</label></td>
                <td class="w15 center">${commande.telClient}</td>
                <td class="w10 right">${commande.montant}</td>
            </tr>
        `
    })
    tbody_commandes.innerHTML = html;
    tfoot_th_commandes.innerHTML = `Vous avez ${commandes.length} commandes`
};
showListCommandes();

function creer() {
    let commande = {id: 0, code: '', dateCommande: '', nomClient: '', telClient: '', montant: ''};
    remplirModal(commande);
    protection(false);
    showModal.click();
};

function afficher() {
    let commande_id = getIdChecked('choix');
    // console.log(commande_id);
    let commande = find(commande_id);
    // console.log(commande);
    if (commande_id == 0) {
        alert('Veuillez selectionner une ligne');
        return;
    } else {
        remplirModal(commande);
        protection(true);
        showModal.click();
    }
};

function modifier() {
    let commande_id = getIdChecked('choix');
    let commande = find(commande_id);
    if (commande_id == 0) {
        alert('Veuillez selectionner une ligne');
        return;
    } else {
        protection(false);
        remplirModal(commande);
        showModal.click();
    }
};

function supprimer() {
    let commande_id = getIdChecked('choix');
    let commande = find(commande_id);
    if (commande_id == 0) {
        alert('Veuillez selectionner une ligne');
        return;
    } else {
        if (confirm(`Voulez-vous vraiment supprimer la commande ${commande.code} - ${commande.nom} ?`)) {
        remplirModal(commande);
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

function find(commande_id) {
    let object = {};
    for (i = 0; i < commandes.length; i++) {
        if (commandes[i].id == commande_id) {
            object = commandes[i];
        }
    };
    return object;
};

function protection(etat) {
    idCommande.disabled = true;
    numeroCommande.disabled = etat;
    dateCommande.disabled = etat;
    nomClientComm.disabled = etat;
    telClientComm.disabled = etat;
    montantComm.disabled = etat;
}

function remplirModal(object) {
    idCommande.value = object.id;
    numeroCommande.value = object.code;
    dateCommande.value = object.dateCommande;
    nomClientComm.value = object.nomClient;
    telClientComm.value = object.telClient;
    montantComm.value = object.montant;
};