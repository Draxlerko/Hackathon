<?php
include 'header.php';
include 'navbar.php';
?>
<style>
    form {
        max-width: 500px;
        margin: 0 auto;
        /* Zarovnanie formulára na stred */
        padding: 20px;
        background-color: #fff;
        /* Biele pozadie */
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        /* Jemný tieň */
    }

    form h3 {
        color: #3A59D1;
        /* Modrá farba nadpisu */
        margin-bottom: 20px;
        text-align: center;
    }

    form label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
        color: #333;
    }

    form select,
    form input[type="text"],
    form button {
        width: 100%;
        /* Všetky inputy budú rovnako široké */
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
        text-align: center;
        /* Text v strede inputu */
        box-sizing: border-box;
    }

    form select:focus,
    form input[type="text"]:focus {
        border-color: #3A59D1;
        /* Modrý okraj pri focus */
        outline: none;
        box-shadow: 0 0 5px rgba(58, 89, 209, 0.5);
        /* Jemný modrý tieň */
    }

    form button {
        background-color: #3A59D1;
        color: white;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    form button:hover {
        background-color: #2f47aa;
        /* Tmavšia modrá pri hover */
    }

    .payment-dropdown {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 10px;
    }

    .payment-dropdown option {
        padding: 10px;
        font-size: 16px;
        display: flex;
        align-items: center;
    }

    .payment-dropdown option[data-icon]::before {
        content: '';
        display: inline-block;
        width: 20px;
        height: 20px;
        margin-right: 10px;
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        vertical-align: middle;
    }

    .payment-dropdown option[value="apple_pay"]::before {
        background-image: url('assets/icon/apple-pay.png');
    }

    .payment-dropdown option[value="google_pay"]::before {
        background-image: url('assets/icon/google-pay.png');
    }

    .payment-dropdown option[value="paypal"]::before {
        background-image: url('assets/icon/paypall.png');
    }

    .payment-dropdown option[value="kreditna_karta"]::before {
        background-image: url('assets/icon/card.png');
    }

    .payment-dropdown option[value="bankovy_prevod"]::before {
        background-image: url('assets/icon/bank-transfer.png');
    }
</style><br>
<div class="container">
    <div class="row">
        <h2></h2>
        <div class="col-lg-2">
        </div>
        <div class="col-lg-8">
            <h2 class="text-center">Platobná brána</h2>
            <div class="text-center">
                <img src="assets/icon/erb.png" alt="erb" class="navbar-logo">
            </div>
            <div class="text-center">
                <p>Na tejto stránke môžete uskutočniť platbu za služby.
                    Platba je spracovaná cez zabezpečenú platobnú bránu.</p>
                Vaše údaje sú chránené a nebudú zdieľané s tretími stranami.</p>
            </div>
            <div class="text-center">
                <div class="x">
                    <div class="y">
                        <form method="post" action="">
                            <h3>Platba kartou</h3>
                            <label for="dovod_platby">Dôvod platby:</label>
                            <select id="dovod_platby" name="dovod_platby" required class="text-start">
                                <option value="" disabled selected>Vyberte dôvod platby</option>
                                <option value="dan_za_domace_zvieratko">Daň za domáce zvieratko</option>
                                <option value="dan_za_odpad">Daň za odpad</option>
                                <option value="poplatok_za_stavebne_povolenie">Poplatok za stavebné povolenie</option>
                                <option value="poplatok_za_prenajom_mestskeho_priestoru">Poplatok za prenájom mestského priestoru</option>
                                <option value="poplatok_za_vydanie_dokladov">Poplatok za vydanie dokladov</option>
                                <option value="poplatok_za_pouzitie_mestskej_infrastruktúry">Poplatok za použitie mestskej infraštruktúry</option>
                                <option value="dan_za_nehnutelnost">Daň za nehnuteľnosť</option>
                            </select>

                            <label for="sposob_platby">Spôsob platby:</label>
                            <select id="sposob_platby" name="sposob_platby" class="payment-dropdown text-start" required>
                                <option value="" disabled selected>Vyberte spôsob platby</option>
                                <option value="apple_pay" >Apple Pay</option>
                                <option value="google_pay" >Google Pay</option>
                                <option value="paypal" >PayPal</option>
                                <option value="kreditna_karta" >Kreditná karta</option>
                                <option value="bankovy_prevod" >Bankový prevod</option>
                            </select>
                            <button type="">Uskutočniť platbu</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
        </div>
    </div>
</div><br><br>
<?php include 'footer.php'; ?>