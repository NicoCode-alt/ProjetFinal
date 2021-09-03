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
            let response = JSON.parse(requeteAjax.responseText);

            let nombreDeLikes = response.nombreLikes;

            this.querySelector(".nombreLikes").innerHTML = nombreDeLikes;

            if (response.liked) {
                if (response.like === "like") {
                    this.classList.remove("btn-danger");
                    this.classList.add("btn-success");
                } else if (response.like === "dislike") {
                    this.classList.remove("btn-success");
                    this.classList.add("btn-danger");
                }
            } else {
                this.classList.remove("btn-success");
                this.classList.remove("btn-danger");
            }
        }
    };
}

document.addEventListener("DOMContentLoaded", () => {
    const tousMesBoutons = document.querySelectorAll(".boutonLike");
    tousMesBoutons.forEach((bouton) => {
        bouton.addEventListener("click", cliquePourLiker);
    });
});
