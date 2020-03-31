#!/usr/bin/env python3
# python3 is recommended

import requests
import json

def download_json():
    res = requests.get("https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-json/dpc-covid19-ita-regioni.json")
    return json.loads(res.text)

def main():
    regions = []

    for row in download_json():
        if row['denominazione_regione'] not in regions:
            code = row['codice_regione']
            name = row['denominazione_regione']

            # Yes, in Italy there are two regions (exactly "autonomous provinces") that have the same code
            if name == "P.A. Bolzano":
                code = 40
            if name == "P.A. Trento":
                code = 41

            print("INSERT INTO covid_regions VALUES ({}, '{}');".format(code, name.replace('\'', '\\\'')))
            regions.append(name)

if __name__ == "__main__":
    main()