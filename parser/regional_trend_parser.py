#!/usr/bin/env python3
# python3 is recommended

import requests
import json

def download_json():
    res = requests.get("https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-json/dpc-covid19-ita-regioni.json")
    return json.loads(res.text)

def main():
    js = download_json()
    regs = [
        {"id":"1","name":"Piemonte"},
        {"id":"2","name":"Valle d'Aosta"},
        {"id":"3","name":"Lombardia"},
        {"id":"5","name":"Veneto"},
        {"id":"6","name":"Friuli Venezia Giulia"},
        {"id":"7","name":"Liguria"},
        {"id":"8","name":"Emilia Romagna"},
        {"id":"9","name":"Toscana"},
        {"id":"10","name":"Umbria"},
        {"id":"11","name":"Marche"},
        {"id":"12","name":"Lazio"},
        {"id":"13","name":"Abruzzo"},
        {"id":"14","name":"Molise"},
        {"id":"15","name":"Campania"},
        {"id":"16","name":"Puglia"},
        {"id":"17","name":"Basilicata"},
        {"id":"18","name":"Calabria"},
        {"id":"19","name":"Sicilia"},
        {"id":"20","name":"Sardegna"},
        {"id":"40","name":"P.A. Bolzano"},
        {"id":"41","name":"P.A. Trento"}
    ]

    for reg in regs:
        prev_icu = 0    
        prev_hosp = 0
        prev_isolation = 0
        prev_positives = 0
        prev_cures = 0
        prev_dead = 0
        prev_swabs = 0

        for row in js:
            if row['denominazione_regione'] == reg['name']:
                date = row['data'].split('T')[0]

                icu = row['terapia_intensiva']
                hosp = row['ricoverati_con_sintomi']
                isolation = row['isolamento_domiciliare']
                positives = row['totale_attualmente_positivi']
                cures = row['dimessi_guariti']
                dead = row['deceduti']
                swabs = row['tamponi']

                print("INSERT INTO covid_regional_trend VALUES ({}, '{}', {}, {}, {}, {}, {}, {}, {});".format(
                    reg['id'], date, icu - prev_icu, hosp - prev_hosp, isolation - prev_isolation, positives - prev_positives,
                    cures - prev_cures, dead - prev_dead, swabs - prev_swabs))

                # prev_icu = icu
                # prev_hosp = hosp
                # prev_isolation = isolation
                # prev_positives = positives
                # prev_cures = cures
                # prev_dead = dead
                # prev_swabs = swabs



if __name__ == "__main__":
    main()