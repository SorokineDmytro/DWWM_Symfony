const articles = [
    { id: 1, code: "BB001", designation: "Biere Castel", pu: 4.50, pr: 2.50 },
    { id: 2, code: "BB002", designation: "Biere Phoenix", pu: 4.50, pr: 2.50 },
    { id: 3, code: "BJ001", designation: "Jus Ananas", pu: 2.50, pr: 1.50 },
    { id: 4, code: "BJ002", designation: "Jus Orange", pu: 3.50, pr: 1.75 },
    { id: 5, code: "BA001", designation: "Rhume charete", pu: 14.50, pr: 7.50 },
    { id: 6, code: "BA002", designation: "Whiskey  Long John", pu: 24.50, pr: 10.50 },
  ];

function showListArticle() {
    let html = '';
    articles.forEach(function(article) {
        html += `
            <tr>
                <td class="w5 center">
                    <input type="checkbox" id=${article.id} name="choix" onclick="onlyOne(this)">
                </td>
                <td class="w10 center"><label for="${article.id}">${article.id}</label></td>
                <td class="w10 center"><label for="${article.id}">${article.code}</label></td>
                <td class=" left"><label for="${article.id}">${article.designation}</label></td>
                <td class="w10 right">${article.pu}</td>
                <td class="w10 right">${article.pr}</td>
            </tr>
        `
    })
    tbody_article.innerHTML = html;
    tfoot_th_article.innerHTML = `Vous avez ${articles.length} articles`
};
showListArticle();

function creer() {
    let article = {id: 0, code: '', designation: '', pu: '', pr: ''};
    remplirModal(article);
    protection(false);
    showModal.click();
};

function afficher() {
    let article_id = getIdChecked('choix');
    // console.log(article_id);
    let article = find(article_id);
    // console.log(article);
    if (article_id == 0) {
        alert('Veuillez selectionner une ligne');
        return;
    } else {
        remplirModal(article);
        protection(true);
        showModal.click();
    }
};

function modifier() {
    let article_id = getIdChecked('choix');
    let article = find(article_id);
    if (article_id == 0) {
        alert('Veuillez selectionner une ligne');
        return;
    } else {
        protection(true);
        remplirModal(article);
        showModal.click();
    }
};

function supprimer() {
    let article_id = getIdChecked('choix');
    let article = find(article_id);
    if (article_id == 0) {
        alert('Veuillez selectionner une ligne');
        return;
    } else {
        if (confirm(`Voulez-vous vraiment supprimer l'article ${article.code} - ${article.designation} ?`)) {
        remplirModal(article);
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

function find(article_id) {
    let object = {};
    for (i = 0; i < articles.length; i++) {
        if (articles[i].id == article_id) {
            object = articles[i];
        }
    };
    return object;
};

function protection(etat) {
    id.disabled = true;
    numArticle.disabled = etat;
    designation.disabled = etat;
    prixUnitaire.disabled = etat;
    prixRevient.disabled = etat;
}

function remplirModal(object) {
    id.value = object.id;
    numArticle.value = object.code;
    designation.value = object.designation;
    prixUnitaire.value = object.pu;
    prixRevient.value = object.pr;
};