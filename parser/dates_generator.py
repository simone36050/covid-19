#!/usr/bin/env python3
# python3 is recommended

import requests
import json

def download_json():
    res = requests.get("https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-json/dpc-covid19-ita-regioni.json")
    return json.loads(res.text)

def main():
    dates = []

    for row in download_json():
        date = row['data'].split('T')[0]
        if date not in dates:
            print("INSERT INTO covid_dates VALUES (NULL, '{}');".format(date))
            dates.append(date)

if __name__ == "__main__":
    main()