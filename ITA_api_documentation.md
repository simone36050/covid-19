# Cos'è questo?

Abbiamo sviluppato un sito web per il monitoraggio dei casi di COVID-19 in Italia. Il sito web è composto da un database,
una API e una webapp. Questo articolo è una documentazione sulle API.

# Le API (non quelle degli alveari)
L'API è suddivisa in tre parti: _area_, _provinces_ e _regions_. Le API sono raggiungibili dal sito https://example.com/api/.
## Aree
Link: https://example.com/api/area
Parametri:
| Nome | Tipo | Valori | Note |
|------|------|--------|------|
| `only_region` | flag | | |
| `regions` | array | int | |
Esempio: https://example.com/api/area?only_region&regions[]=4&regions[]=5

## Province
Link: https://example.com/api/provinces
| Nome | Tipo | Valori | Note |
|------|------|--------|------|
| `mode` | choice | _absolute_, _relative_ | |
| `group` | choice | _province_, _date_ | |
| `from` | date | | Formato `YYYY-MM-DD` |
| `to` | date | | Formato `YYYY-MM-DD` |
| `provinces` | array | int | |
Esempio: https://example.com/api/provinces?provinces[]=4&mode=absolute&from=2020-04-01

## Regioni
Link: https://example.com/api/regions
| Nome | Tipo | Valori | Note |
|------|------|--------|------|
| `mode` | choice | _absolute_, _relative_ | |
| `group` | choice | _province_, _date_ | |
| `from` | date | | Formato `YYYY-MM-DD` |
| `to` | date | | Formato `YYYY-MM-DD` |
| `regions` | array | int | |
| `data` | array | _icu_, _hospitalized_, _isolation_, _positives_, _cures_, _dead_, _swabs_ | Ne puoi selezionare più di uno |
Esempio: https://example.com/api/regions?to=2020-04-01&data[]=icu&data[]=dead

# Crediti
**Backend:** [Simone Brunello](https://github.com/simone36050)
**Frontend:** Matteo Zigante
