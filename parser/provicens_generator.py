#!/usr/bin/env python3
# python3 is recommended

import requests
import json

def download_json():
    res = requests.get("https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-json/dpc-covid19-ita-province.json")
    return json.loads(res.text)

def main():
    provinces = []

    for row in download_json():
        if row['codice_provincia'] not in provinces and row['denominazione_provincia'] != 'In fase di definizione/aggiornamento':
            region = row['codice_regione']
            prov_id = row['codice_provincia']
            name = row['denominazione_provincia'].replace('\'', '\\\'')
            code = row['sigla_provincia']
            
            print("INSERT INTO covid_provinces VALUES ({}, '{}', '{}', {});".format(prov_id, name, code, region))
            provinces.append(prov_id)

if __name__ == "__main__":
    main()
