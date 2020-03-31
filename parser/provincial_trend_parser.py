#!/usr/bin/env python3
# python3 is recommended

import requests
import json

def download_json():
    res = requests.get("https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-json/dpc-covid19-ita-province.json")
    return json.loads(res.text)

def main():
    for row in download_json():
        if row['denominazione_provincia'] != 'In fase di definizione/aggiornamento':
            date = row['data'].split('T')[0]
            province = row['codice_provincia']
            cases = row['totale_casi']
            print("INSERT INTO covid_provincial_trend VALUES ({}, '{}', {});".format(province, date, cases))

if __name__ == "__main__":
    main()