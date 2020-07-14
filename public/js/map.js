class Map {
    constructor(urlSource, marker) {
        this.adds = [];
        this.lat = 48.577;
        this.lon = -3.841;
        this.zoom = 14;
        this.mymap = L.map('map', { scrollWheelZoom: false }).setView([this.lat, this.lon], this.zoom);
        this.tileLayer = L.tileLayer('http://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png').addTo(this.mymap);
        this.markerCluster = new L.markerClusterGroup();
        this.iconMarker = marker;
        this.req = urlSource;
        this.ajaxGet(this.req, (response) => {
            JSON.parse(response).forEach((adds) => {
                let lat = adds[0].lat;
                let lng = adds[0].lng;
                let title = "Annonce(s) de " + adds[0].name;
                let popup = `<h3>${title}</h3>`;
                this.markerStyle = new L.icon({
                    iconUrl: this.iconMarker,
                    iconSize: [70, 70],
                    iconAnchor: [25, 50],
                    popupAnchor: [-3, -76],
                });

                this.marker = new L.marker([lat, lng], { icon: this.markerStyle });

                this.markerCluster.addLayer(this.marker);

                this.mymap.addLayer(this.markerCluster);

                adds.forEach(add => {
                    popup += `
                       <p>${add.title}</p>
                       <a href="#" id="${add.id}" class="add_link">Voir</a><hr>
                   `;
                    //Pour garder en mémoire les infos de l'add
                    this.adds[add.id] = add;
                });
                this.marker.bindPopup(popup);
            });

        });
        document.addEventListener("click", e => {
            if (e.target.classList.contains("add_link")) {
                e.preventDefault();
                let id = e.target.id;
                document.getElementById("current_add_name").innerText = this.adds[id].name;

                let isBasket = (this.adds[id].company_id !== undefined);
                document.getElementById("current_add_title").innerText = this.adds[id].title;
                if (!isBasket) {
                    document.getElementById("current_add_description").innerText = "Votre mission : " + this.adds[id].description;
                    document.getElementById("current_add_nb_basket").innerText = "Nombre de paniers : " + this.adds[id].basket_quantity;
                    document.getElementById("current_add_basket_size").innerText = " Taille de panier : " + this.adds[id].basket_size;
                    document.getElementById("add_id").value = this.adds[id].id;
                    document.getElementById("current_add_category").innerText = this.adds[id].select_value;
                    document.getElementById("creator_id").value = this.adds[id].users_id;
                    if (document.getElementById("user_id").value == this.adds[id].users_id) {
                        document.getElementById("error_form").innerText = "Impossible de répondre à ta propre annonce chouchou !";
                        document.getElementById("validation_form").classList.add("hidden_form");
                    } else {
                        document.getElementById("validation_form").classList.remove("hidden_form");
                        document.getElementById("error_form").innerText = "";
                    }
                } else {
                    document.getElementById("current_add_zip_city").innerText = this.adds[id].address + " - " + this.adds[id].zip_code + " " + this.adds[id].city;
                    document.getElementById("current_add_description").innerText = "Nous proposons : " + this.adds[id].description;
                }
            }
        })
    }
    ajaxGet(url, callback) {
        let req = new XMLHttpRequest();
        req.open("GET", url);
        req.addEventListener("load", function () {
            if (req.status >= 200 && req.status < 400) {
                /* Appelle la fonction callback en lui passant la réponse de la requête*/
                callback(req.responseText);
            } else {
                console.error(req.status + " " + req.statusText + " " + url);
            }
        });
        req.addEventListener("error", function () {
            console.error("Erreur réseau avec l'URL " + url);
        });
        req.send(null);
    }
}


