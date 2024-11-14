const nombre = 12;
var p = 0;
carrosel_content.style.width = (1100 * nombre) + 'px';

for (i = 1; i <= nombre; i++) {
    let div = document.createElement('div');
    div.style.backgroundImage = `url("./public/img/worker${i}.jpg")`;
    div.className = 'photo';
    carrosel_content.appendChild(div);
};

function afficherMasquerFleche() {
    if (p == 0) {
        g.style.display = 'none';
    } else {
        g.style.display = 'block';
    };

    if (p == -nombre + 1) {
        d.style.display = 'none';
    } else {
        d.style.display = 'block';
    }
};
afficherMasquerFleche();

g.onclick = function() {
    p++;
    carrosel_content.style.translate = (p*1100) + 'px';
    afficherMasquerFleche();
};

d.onclick = function() {
    p--;
    carrosel_content.style.translate = (p*1100) + 'px';
    afficherMasquerFleche();
};