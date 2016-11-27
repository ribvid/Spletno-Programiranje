# Projekt pri predmetu Spletno programiranje

## Opis projekta
Za temo sem izbral Microblog, ki je bil na seznamu predlaganih. Gre za spletno aplikacijo, ki omogoča deljenje kratkih zapisov (v mojem primeru do 250 znakov) ali fotografij, ki se jim lahko doda opis, dolg največ 100 znakov. Spletna aplikacija omogoča tudi sledenje uporabnikom in posebno obravnavo ključnih besed, ki se začnejo z znakom @ oz. #.

## Ciljna publika in naprave
Ciljna publika so zlasti mladi - moji vrstniki in tudi nekoliko starejši. Skratka uporabniki, ki so se navajeni izražati v kratkih sporočilih (kot pri SMS-jih). Aplikacija je namenjena za uporabo tako preko računalnikov kot tudi na mobilnih napravah (telefonih in tablicah), saj je prikaz za manjše zaslone prilagojen.

## Poročilo o težavah v različnih brskalnikih
Testiral sem uporabo v brskalnikih Safari, Chrome in Firefox, vendar nikjer visem zapazil nobene napake.

## 2 gradnika, na katera smo posebej ponosni
### Objave, v katerih si označen
Ker spletna aplikacija omogoča označevanje drugih uporabnikov z značko @, sem v spletni vmesnik za prijavljene uporabnike implementiral možnost, da vidi, v katerih objavah je bil nedavno označen. Opozorilo o tem je lahko poljubno široko, saj se prilagaja številu objav, v katerih je bil uporabnik označen. Če je bil tako označen v samo eni objavi, se obvestilo razteza po celi širini, v primeru dveh, se razdeli na pol, v primeru treh pa na tretjine. Uporabnik lahko opozorilo tudi ugasne. Če ima tri opozorila in enega ugasne, se ostali dve dinamično znova raztegneta na polovici, če ugasne še eno, pa se preostalo opozorilo razširi čez celotno širino. Dinamično prileganje širine sem dosegel s pomočjo Javascripta, za uporabnike, ki ga nimajo omogočenega, pa sem uredil posebno stran. Kljub temu, da gre za majhen detajl, sem na dinamično prileganje širine posebej ponosen.

## Slika v modalnem oknu
Še ena malenkost, na katero sem ponosen, pa je priiez povečane slike v modalnem oknu. Ker imajo uporabniki danes čedalje zmogljivejše foto naprave (navadno telefone), se mi je zdelo smiselno omogočiti ogled slike v večji velikosti. To sem dosegel s pomočjo prikaza slike v modalnem oknu. Gre za kombinacijo CSS in Javascripta. Jasno funkcionalnost ni dostopna za uporabnike, ki Javascripta ne uporabljajo, vendar gre za dodatno funkcijo, tako da lahko tudi ti nemoteno uporabljajo spletno stran naprej. 

## Dodatni komentarji
Nekaj stvari je še potrebno implementirati, vendar bom za to potreboval strežniške tehnologije. Gre za "iskanje v živo" (live search), ki ga trenutno izvajam z vnaprej pripravljeno JSON datoteko, v drugi fazi pa bo ta morala biti generirana na podlagi rezultatov iz baze. Druga taka stvar pa so urejanje in brisanje objav ter njihovo všečkanje in deljenje.