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
<!--
<div id="coiils_smykker">
</div>
-->

</div><!-- #primary -->

<?php if ( astra_page_layout() == 'right-sidebar' ) : ?>

<?php get_sidebar(); ?>

<?php endif ?>

<?php get_footer(); ?>

<!-- Opretter skabelon til indholdet-->
<template>
    <article class="article_coiils">
        <div class="kollektion_div">
            <img src="" alt="" class="img_front">
            <div class="produktinfo">
                <p class="produktnavn"></p>
                <p class="pris"></p>
            </div>
        </div>
    </article>
</template>

<!-- Javascript begynder-->
<script>
    let smykker //opretter variablen smykker

    //Laver variabler til json indhold
    const dbUrl = "http://sophiespang.dk/kea/10_eksamensprojekt/LIIRO/wp-json/wp/v2/smykke?per_page=100";

    async function getJson() {
        const dataDb = await fetch(dbUrl); // Opretter variablen data. Fetch = et API til hentning af vores json
        smykker = await dataDb.json(); // Sætter json ind i vairbalen "smykker" (som er oprettet tidligere)
        console.log(smykker); // Ser i konsollen om Array bliver hentet

        visSmykker(); // Kalder funktionen "visSmykker"
    }

    function visSmykker() {
        let skabelon = document.querySelector("template"); // Opretter variablen til templaten som indholdet skal ned i
        let container = document.querySelector("#coiils_smykker"); // Opretter variablen til hvor templatesene skal klones i
        smykker.forEach(smykke => { // Looper igennem "smykker-arrayet". Får hvert smykke skal det efterfølgende ske:
            if (smykke.kollektion == 17) { // hvis smykke-arrayet indeholder kollektions id 17 - coiils kollektionens id, så ske følgende:
                let klon = skabelon.cloneNode(true).content; // Opretter variablen "klon" som gør det muligt at klone ned i vores template
                klon.querySelector(".img_front").src = smykke.billede_front.guid; // Billede hentes
                klon.querySelector(".produktnavn").textContent = smykke.title.rendered; // Produktnavn hentes
                klon.querySelector(".pris").textContent = smykke.pris + " DKK"; // Pris hentes
                klon.querySelector(".article_coiils").addEventListener("click", () => {
                    location.href = smykke.link;
                }) // sætter eventlistener på, så man kan klikke på produktet og komme til singleview
                container.appendChild(klon); // Indholdet i templaten bliver sat ind i vores sektion
            }
        })
    }

    getJson();

</script>
