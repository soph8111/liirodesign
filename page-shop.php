<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>

<?php if ( astra_page_layout() == 'left-sidebar' ) : ?>

<?php get_sidebar(); ?>

<?php endif ?>

<div id="primary" <?php astra_primary_class(); ?>>

    <?php astra_primary_content_top(); ?>

    <?php astra_content_page_loop(); ?>

    <?php astra_primary_content_bottom(); ?>

    <section id="oversigt">
        <div id="filter">
            <h3>Kategori</h3>
            <nav id="filterknapper" class="hidden">
                <button data-kat="alle" class="alle">Alle</button>
            </nav>
        </div>

        <div id="sektion_smykker">
        </div>
    </section>
</div><!-- #primary -->

<?php if ( astra_page_layout() == 'right-sidebar' ) : ?>

<?php get_sidebar(); ?>

<?php endif ?>

<?php get_footer(); ?>

<!-- Opretter skabelon til indholdet-->
<template>
    <article class="article_multiview">
        <img src="" alt="" class="img_front">
        <div class="produktinfo">
            <p class="produktnavn"></p>
            <p class="pris"></p>
        </div>
    </article>
</template>

<!-- Javascript begynder-->
<script>
    document.addEventListener("DOMContentLoaded", () => //tjekker inden om DOM er loaded
        {

            let smykker //opretter variablen smykker
            let kategorier //opretter variablen kategorier
            let kollektioner //opretter variablen kollektioner
            let farver //opretter variablen farver
            let filterSmykke = "alle" // opretter en variable som skal holde styr på hvilken kategori der er valgt

            //Laver variabler til json indhold
            const dbUrl = "http://sophiespang.dk/kea/10_eksamensprojekt/LIIRO/wp-json/wp/v2/smykke?per_page=100";
            const catUrl = "http://sophiespang.dk/kea/10_eksamensprojekt/LIIRO/wp-json/wp/v2/categories?per_page=100";
            const kolUrl = "http://sophiespang.dk/kea/10_eksamensprojekt/LIIRO/wp-json/wp/v2/kollektion?per_page=100";
            const farveUrl = "http://sophiespang.dk/kea/10_eksamensprojekt/LIIRO/wp-json/wp/v2/farve?per_page=100";

            async function getJson() {
                console.log("test");
                const dataDb = await fetch(dbUrl); // Opretter variablen data. Fetch = et API til hentning af vores json
                smykker = await dataDb.json(); // Sætter json ind i vairbalen "smykker" (som er oprettet tidligere)
                console.log(smykker); // Ser i konsollen om Array bliver hentet

                // Samme sker for kategorier, kollektioner og farver
                const dataCat = await fetch(catUrl);
                kategorier = await dataCat.json();
                console.log(kategorier);

                const dataFarve = await fetch(farveUrl);
                farver = await dataFarve.json();
                console.log(farver);

                const dataKol = await fetch(kolUrl);
                kollektioner = await dataKol.json();
                console.log(kollektioner);

                visSmykker(); // Kalder funktionen "visSmykker"
                opretKnapper(); // Kalder funktionen "opretKnapper"
            }

            function opretKnapper() {
                kategorier.forEach(kat => { // looper gennem alle kategorier. Får hver ketegori skal følgende ske:
                    document.querySelector("#filterknapper").innerHTML += `<button class="filter" data-kat="${kat.id}">${kat.name}</button>`
                }) // indsætter en knap for hver kategori. Hver knap får data-set = kategoriens id, så man senere kan filtrere på det.

                addEventListenerToButtons(); // Kalder den funktion der skal give eventlistener på alle knapper

                document.querySelector("#filter").addEventListener("click", () => {
                    document.querySelector("#filterknapper").classList.toggle("hidden");
                }) // Knap til mobilversion af drop down. Når der trykkes på "kategori" bliver der togglet mellem klassen hidden - display: none
            }

            function addEventListenerToButtons() {
                document.querySelectorAll("#filterknapper button").forEach(elm => {
                    elm.addEventListener("click", filtrering); //Hver knap får en click-eventlistner. Når der klikkes på knappen skal den kalde funktionen "filtrering"
                })
            }

            function filtrering() {
                filterSmykke = this.dataset.kat; // variablen filterSmykke bliver sat lig med den aktuelle kategori's id
                visSmykker(); // Kalder funktionen visSmykker
            }

            function visSmykker() {
                let skabelon = document.querySelector("template"); // Opretter variablen til templaten som indholdet skal ned i
                let container = document.querySelector("#sektion_smykker"); // Opretter variablen til hvor templatesene skal klones i
                container.innerHTML = ""; // Sørger for at containeren er blank, inden at det nye indhold filtreres ind
                smykker.forEach(smykke => { // Looper igennem "smykker-arrayet". Får hvert smykke skal det efterfølgende ske:
                    if (filterSmykke == "alle" || smykke.categories.includes(parseInt(filterSmykke))) { // hvis smykke-arrayet indeholder samme id som dataset er sat lig med, så skal den vise følgende: - pareseInt laver filterKat stringen om til tal
                        let klon = skabelon.cloneNode(true).content; // Opretter variablen "klon" som gør det muligt at klone ned i vores template
                        klon.querySelector(".img_front").src = smykke.billede_front.guid; // Billede hentes
                        klon.querySelector(".produktnavn").textContent = smykke.title.rendered; // Produktnavn hentes
                        klon.querySelector(".pris").textContent = smykke.pris + " DKK"; // Pris hentes
                        klon.querySelector(".article_multiview").addEventListener("click", () => {
                            location.href = smykke.link;
                        }) // sætter eventlistener på, så man kan klikke på produktet og komme til singleview
                        container.appendChild(klon); // Indholdet i templaten bliver sat ind i vores sektion
                    }
                })
            }

            getJson();

        })

</script>
