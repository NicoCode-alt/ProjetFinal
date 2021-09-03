function cliquePourLiker(e) {
    e.preventDefault();
    const like = this.dataset.action;

    let url = this.href;
    let requeteAjax = new XMLHttpRequest();
    const data = new FormData();
    data.append("like", like);
    requeteAjax.open("POST", url);

    requeteAjax.send(data);

    requeteAjax.onload = () => {
        if (requeteAjax.status === 200 && requeteAjax.readyState === 4) {
            let response = requeteAjax.responseText;

            // Récupération de la div
            const parent = this.parentNode;

            // Intègre le html dans la div
            parent.innerHTML = response;

            // Réinstalle les événements sur les liens
            parent.childNodes.forEach((a) => {
                a.addEventListener("click", cliquePourLiker);
            });
        }
    };
}

document.addEventListener("DOMContentLoaded", () => {
    const tousMesBoutons = document.querySelectorAll(".boutonLike");
    tousMesBoutons.forEach((bouton) => {
        bouton.addEventListener("click", cliquePourLiker);
    });
});
