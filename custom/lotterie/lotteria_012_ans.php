#
# Una riga per ogni risposta. La 1a riga contiene la risposta esatta alla prima domanda, ecc.
# Il numero di righe non commentate deve coincidere con le domande della lotteria.
# Se una partita non e' stata ancora disputata, inserire la risposta convenzionale "nd".
#

# Qualificato ottavi 1:
Francia
# Qualificato ottavi 2:
Australia
# Qualificato ottavi 3:
Paesi Bassi
# Qualificato ottavi 4:
Senegal
# Qualificato ottavi 5:
Inghilterra
# Qualificato ottavi 6:
Stati Uniti
# Qualificato ottavi 7:
Brasile
# Qualificato ottavi 8:
Portogallo
# Qualificato ottavi 9:
Argentina
# Qualificato ottavi 10:
Polonia
# Qualificato ottavi 11:
Giappone
# Qualificato ottavi 12:
Spagna
# Qualificato ottavi 13:
Marocco
# Qualificato ottavi 14:
Croazia
# Qualificato ottavi 15:
Svizzera
# Qualificato ottavi 16:
Corea del Sud
# Qualificato quarti 1:
Paesi Bassi
# Qualificato quarti 2:
Argentina
# Qualificato quarti 3:
nd
# Qualificato quarti 4:
nd
# Qualificato quarti 5:
nd
# Qualificato quarti 6:
nd
# Qualificato quarti 7:
nd
# Qualificato quarti 8:
nd
# Qualificato semifinale 1:
nd
# Qualificato semifinale 2:
nd
# Qualificato semifinale 3:
nd
# Qualificato semifinale 4:
nd
# Qualificato finale 1:
nd
# Qualificato finale 2:
nd
# Vincitore:
nd

#
# Le risposte sono suddivise in gruppi. Per ciascun gruppo esiste un set di risposte piu' o meno corrette
#
[equivalenza_risposte]
5::qualif. ottavi	::0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15
4::qualif. quarti	::16,17,18,19,20,21,22,23
3::qualif. semifinali	::24,25,26,27
2::qualif. finali	::28,29
1::vincitore    	::30

#
# Qui viene indicato il punteggio da associare ad ogni risposta a seconda del gruppo in cui essa viene fornita
#
# es:
# Brasile::                     # prima dell'inizio del torneo
# Brasile::5,0;4,0;3,0;2,0;1,0  # se il Brasile viene escluso già al primo turno
# Brasile::5,20;4,0             # se il Brasile passa il primo turno ma esce al secondo, e non sono state giocate altre partite
# Brasile::5,20;4,0;3,0;2,0;1,0 # se il Brasile passa il primo turno ma esce al secondo, e sono state giocate tutte le partite
[punteggio_risposte]
Qatar::5,0;4,0;3,0;2,0;1,0
Ecuador::5,0;4,0;3,0;2,0;1,0
Senegal::5,20
Paesi Bassi::5,20;4,20
Inghilterra::5,20
Iran::5,0;4,0;3,0;2,0;1,0
Stati Uniti::5,20;4,0;3,0;2,0;1,0
Galles::5,0;4,0;3,0;2,0;1,0
Argentina::5,20;4,20
Arabia Saudita::5,0;4,0;3,0;2,0;1,0
Messico::5,6;4,0;3,0;2,0;1,0
Polonia::5,14;
Francia::5,20
Australia::5,20;4,0;3,0;2,0;1,0
Danimarca::5,0;4,0;3,0;2,0;1,0
Tunisia::5,0;4,0;3,0;2,0;1,0
Spagna::5,14
Costa Rica::5,0;4,0;3,0;2,0;1,0
Germania::5,6;4,0;3,0;2,0;1,0
Giappone::5,20;
Belgio::5,0;4,0;3,0;2,0;1,0
Canada::5,0;4,0;3,0;2,0;1,0
Marocco::5,20
Croazia::5,20
Brasile::5,20
Serbia::5,0;4,0;3,0;2,0;1,0
Svizzera::5,20
Camerun::5,0;4,0;3,0;2,0;1,0
Portogallo::5,20
Ghana::5,0;4,0;3,0;2,0;1,0
Uruguay::5,10
Corea del Sud::5,10
