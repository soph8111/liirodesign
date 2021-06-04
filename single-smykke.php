<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
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


    <?php astra_primary_content_bottom(); ?>

    <!--   EGET KODE -->
    <section id="sektion_singeview">
        <button class="tilbageknap">❮ Tilbage</button>
        <article>
            <div class="singleview_row">
                <div class="singleview_column" id="slideshow">
                    <div class="billede_slideshow">
                        <img class="img1" src="#" alt="">
                    </div>
                    <div class="billede_slideshow">
                        <img class="img2" src="#" alt="">
                    </div>
                    <div class="billede_slideshow">
                        <img class="img3" src="#" alt="">
                    </div>
                    <div class="skift_billede">
                        <button class="tilbage" onclick="plusSlides(-1)">❮</button>
                        <div>
                            <span class="dot"></span>
                            <span class="dot"></span>
                            <span class="dot"></span>
                        </div>
                        <button class="frem" onclick="plusSlides(1)">❯</button>
                    </div>
                </div>

                <div class="singleview_column">
                    <h2 class="navn"></h2>
                    <p class="prisen"></p>
                    <hr>
                    <p class="beskrivelse"></p>
                    <p class="storrelse"></p>
                    <button class="tilfoej">Tilføj til kurv</button>
                </div>
            </div>

        </article>
    </section>
</div><!-- #primary -->

<?php if ( astra_page_layout() == 'right-sidebar' ) : ?>

<?php get_sidebar(); ?>

<?php endif ?>

<?php get_footer(); ?>

<script>
    window.addEventListener("load", sidenVises);

    function sidenVises() {
        console.log("sidenVises");

        getJson();

        // Tilbageknap får eventlistener
        document.querySelector(".tilbageknap").addEventListener("click", tilbageTilShop);
        // Tilføj til kurv-knap får eventlistener
        document.querySelector(".tilfoej").addEventListener("click", tilføjTilKurv);

    }

    let tal = 1; // Bruges til at tælle i kurven
    let smykke;
    const dbUrl = "http://sophiespang.dk/kea/10_eksamensprojekt/LIIRO/wp-json/wp/v2/smykke/" + <?php echo get_the_ID() ?>; // Laver variable til json indhold

    console.log(dbUrl);
    async function getJson() {
        const data = await fetch(dbUrl); // Opretter variablen data. Fetch = et API til hentning af vores json
        smykke = await data.json(); // Sætter json ind i vairbalen "smykker" (som er oprettet tidligere)
        console.log(smykke); // Ser i konsollen om Array bliver hentet
        visSmykke(); // Kalder funktionen "visSmykke"
    }

    function visSmykke() {
        document.querySelector(".navn").textContent = smykke.title.rendered; // Produktnavn hentes
        document.querySelector(".prisen").textContent = smykke.pris + " DKK"; // Pris hentes
        document.querySelector(".beskrivelse").textContent = smykke.beskrivelse; // Beskrivelsen hentes
        document.querySelector(".storrelse").textContent = "Størrelse: " + smykke.stoerrelse; // Beskrivelsen hentes

        // Indsætter billeder fra databasen til de tre img-tags
        document.querySelector(".img1").src = smykke.billede_front.guid; // Billede 1 hentes
        document.querySelector(".img2").src = smykke.billede_vinkel.guid; // Billede 2 hentes
        document.querySelector(".img3").src = smykke.billede_model.guid; // Billede 3 hentess
    }

    function tilbageTilShop() {
        console.log("tilbageTilShop")
        history.back();
    }

    function tilføjTilKurv() {
        document.querySelector("#antal_kurv").textContent = "  (" + tal++ + ")"; // Plus en til hver gang der trykkers på "tilføj til kurv"
    }

    // SLIDESHOW HERUNDER - kig single-smykke imens

    // sætter variablen "slideNummer" = 1
    let slideNummer = 1;

    visSlides(slideNummer); // Kalder funktionen "visSlides" og sender slideNummer værdien med (dvs. 1). funktionen visSlide får værdien fra variablen "slideNummer" med sig

    function plusSlides(n) {
        console.log("N:" + n); // n = 1 ved pil frem og -1 ved pil tilbage. Det er angivet i HTML filen.
        visSlides(slideNummer += n); // Kalder funktionen "visSlides" og sender slideNummer værdien med, samt lægger n (1/-1) til.
    }

    // Kør funktion visSlides - har værdien fra "n" med sig (1/-1)
    function visSlides(n) {

        let i; // Opretter variablen i så den kan tælles på
        let slides = document.getElementsByClassName("billede_slideshow"); // Opretter variablen slides = classerne med "billede_slideshow"
        let dots = document.getElementsByClassName("dot"); // Opretter varibalen dots = classerne med "dot"

        // To if-sætninger til at styre hvilket nummer af ".billede_slideshow" der skal vises.
        // Hvis n (her 4) > 3 så sæt slideNummer = 1, således at billede 1 bliver vist (se consollen)
        if (n > slides.length) {
            console.log("N = " + n); // N = 4 da n bliver 3(slideNummer) + 1(n) = 4
            console.log("Hvor mange img'er der = " + slides.length); // Der er 2 diver med classen billede_slideshow

            slideNummer = 1 // Sæt slideNummer = 1 dvs vis billede 1
        }

        // Hvis n (her 0) < 1 så sæt slideNummer = 3 således at billede 3 bliver vist
        if (n < 1) {
            console.log("N = " + n); // N = 0 da n bliver 1 - 1 = 0
            console.log("Hvor mange img'er der = " + slides.length); // Der er 3 diver med classen billede_slideshow


            slideNummer = slides.length // Sæt slideNummer = 3 dvs vis billede 3
        }
        // i står for index - loop variablen
        // For = loop igennem kode et antal gange
        for (i = 0; i < slides.length; i++) { // Sætter først i = 0; derefter kører koden 3 gange(antal slides); +1 til i hver gang den har kørt
            slides[i].style.display = "none"; // Slides 0, 1, 2 i arrayet får display: none således at billedet ikke bliver vist
        }
        for (i = 0; i < dots.length; i++) { // Sætter først i = 0; derefter kører koden 3 gange(antal slides); +1 til i hver gang
            dots[i].className = dots[i].className.replace(" valgt_dot", ""); // doot 0, 1, 2 i arrayet får erstattet klassen " valgt_dot" med ingenting, således at den ikke har styling på
        }

        slides[slideNummer - 1].style.display = "block"; // aktuel slide får display: block så billedet bliver vist
        dots[slideNummer - 1].className += " valgt_dot"; // aktuel dot får tildelt klassen " valgt_dot" så den er stylet anderledes end de andre
    }

</script>
