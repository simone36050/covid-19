<html>
    <header>
         <link rel="stylesheet" href="<?php echo site_url("static/css/base.css"); ?>">
         <link rel="stylesheet" href="<?php echo site_url("static/css/regioni.css"); ?>">
         <script src="<?php echo site_url("static/js/jquery-3.4.1.min.js"); ?>"></script>
         <script src="<?php echo site_url("static/js/button_Regioni.js"); ?>"></script>
        <script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>        
    </header>
    <body >
        <div class="container">
            <div class="relative">
                <div class="grafico" id = "grafico">
                </div>                
                <div class="lato">
                    <a href="<?php echo site_url("webapp/home"); ?>"><button class="back">Back</button></a>
                    <br><br>
                    <h6>Tipi di dati</h6>
                    <button class="b-1 v" value="isolation"> Isolati</button>
                    <button class="b-1 v" value="icu"> Terapia Intensiva</button>
                    <br>
                    <button class="b-1 v"  value="hospitalized"> Ospedalizzati </button>
                    <button class="b-1 v" value="cures"> Guariti </button>
                    <br>
                    <button class="b-1 a" value="dead"> Morti </button>
                    <button class="b-1 v" value="swabs"> Tamponi </button>
                    <br>
                    <h6>Regioni</h6>
                    <button class="reg v" value="13">Abruzzo</button>
                    <button class="reg v" value="17">Basilicata</button>
                    <button class="reg v" value="18">Calbria</button>
                    <br>
                    <button class="reg v" value="15">Campania</button>
                    <button class="reg v" value="8">Emilia Romagna</button>
                    <button class="reg v" value="6">Friuli-Venezia Giulia</button>
                    <br>
                    <button class="reg v" value="12">Lazio</button>
                    <button class="reg v" value="7">Liguria</button>
                    <button class="reg v" value="3">Lombardia</button>
                    <br>
                    <button class="reg v" value="11">Marche</button>
                    <button class="reg v" value="14">Molise</button>
                    <button class="reg v" value="1">Piemonte</button>

                    <br>
                    <button class="reg v" value="16">Puglia</button>
                    <button class="reg v" value="20">Sardegna</button>
                    <button class="reg v" value="19">Sicilia</button>
                    <br>
                    <button class="reg v" value="9">Toscana</button>
                    <button class="reg v" value="40">Bolzano PA</button>
                    <button class="reg v" value="41">Trento PA</button>

                    <br>
                    <button class="reg v" value="10">Umbria</button>
                    <button class="reg v" value="2">Val d'Aosta</button>
                    <button class="reg a" value="5">Veneto</button>
                    <br>
                    
                </div>
            </div>

            <img src="<?php echo site_url("/static/img/Chart-1.jpg"); ?>">
        </div>
    </body>
</html>
<!-- HTML srcitto da Matteo Zigante>
