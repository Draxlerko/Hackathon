<style>
    .footer-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* Responzívne stĺpce */
    gap: 20px; /* Medzera medzi stĺpcami */
    background-color: #003366; /* Farba pozadia */
    color: white; /* Farba textu */
    padding: 40px 20px; /* Vnútorné odsadenie */
}

.footer-item h3 {
    color: #FFD700; /* Zlatá farba nadpisov */
    margin-bottom: 15px;
}

.footer-item ul {
    list-style: none; /* Odstránenie odrážok */
    padding: 0;
}

.footer-item ul li {
    margin-bottom: 10px;
}

.footer-item ul li a {
    color: white;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-item ul li a:hover {
    color: #FFD700; /* Zlatá farba pri hover */
}

.footer-item p a {
    color: white;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-item p a:hover {
    color: #FFD700; /* Zlatá farba pri hover */
}
</style>

<footer>
    <div class="footer-container">
        <div class="footer-item">
            <h3>Kontakt</h3>
            <p>
                Obecný úrad Čierne<br>
                Čierne 189, 023 13<br>
                IČO: 00 313 980<br>
                DIČ: 2020552985<br><br>
                Telefón: <a href="tel:+421414373222">041/43 73 222</a><br>
                E-mail: <a href="mailto:sekretariat@obeccierne.sk">sekretariat@obeccierne.sk</a>
            </p>
        </div>
        <div class="footer-item">
            <h3>Samospráva</h3>
            <ul>
                <li><a href="#">Obecný úrad</a></li>
                <li><a href="#">Úradná tabuľa</a></li>
                <li><a href="#">Komisie OZ</a></li>
                <li><a href="#">Obecné zastupiteľstvo</a></li>
                <li><a href="#">Rozpočet obce</a></li>
                <li><a href="#">Zmluvy, faktúry, objednávky</a></li>
            </ul>
        </div>
        <div class="footer-item">
            <h3>Život v obci</h3>
            <ul>
                <li><a href="#">Virtuálny cintorín</a></li>
                <li><a href="#">TES Čierne</a></li>
                <li><a href="#">Organizácie v obci</a></li>
                <li><a href="#">Obecná knižnica</a></li>
                <li><a href="#">Separovaný zber</a></li>
                <li><a href="#">Kalendár akcií obce</a></li>
            </ul>
        </div>
        <div class="footer-item">
            <h3>Pre návštevníkov</h3>
            <ul>
                <li><a href="#">Región Kysuce</a></li>
                <li><a href="#">Kysucký triangl</a></li>
                <li><a href="#">Organizácie CR</a></li>
                <li><a href="#">Turistické mapy</a></li>
                <li><a href="#">Trojmedzie</a></li>
                <li><a href="#">Šance – Valy</a></li>
            </ul>
        </div>
    </div>
</footer>
</body>

</html>