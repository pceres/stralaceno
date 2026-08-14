#
# Una riga per ogni risposta. La 1a riga contiene la risposta esatta alla prima domanda, ecc.
# Il numero di righe non commentate deve coincidere con le domande della lotteria.
# Se una partita non e' stata ancora disputata, inserire la risposta convenzionale "nd".
#

# Qualificato sedicesimi 1:
Messico
# Qualificato sedicesimi 2:
USA
# Qualificato sedicesimi 3:
Germania
# Qualificato sedicesimi 4:
Francia
# Qualificato sedicesimi 5:
Norvegia
# Qualificato sedicesimi 6:
Argentina
# Qualificato sedicesimi 7:
Svizzera
# Qualificato sedicesimi 8:
Canada
# Qualificato sedicesimi 9:
Colombia
# Qualificato sedicesimi 10:
Bosnia ed Erzegovina
# Qualificato sedicesimi 11:
Brasile
# Qualificato sedicesimi 12:
Marocco
# Qualificato sedicesimi 13:
Australia
# Qualificato sedicesimi 14:
Sudafrica
# Qualificato sedicesimi 15:
Costa d'Avorio
# Qualificato sedicesimi 16:
Ecuador
# Qualificato sedicesimi 17:
Olanda
# Qualificato sedicesimi 18:
Giappone
# Qualificato sedicesimi 19:
Svezia
# Qualificato sedicesimi 20:
Paraguay
# Qualificato sedicesimi 21:
Belgio
# Qualificato sedicesimi 22:
Egitto
# Qualificato sedicesimi 23:
Capo Verde
# Qualificato sedicesimi 24:
Senegal
# Qualificato sedicesimi 25:
Spagna
# Qualificato sedicesimi 26:
Austria
# Qualificato sedicesimi 27:
Algeria
# Qualificato sedicesimi 28:
Portogallo
# Qualificato sedicesimi 29:
Repubblica Democratica del Congo
# Qualificato sedicesimi 30:
Inghilterra
# Qualificato sedicesimi 31:
Croazia
# Qualificato sedicesimi 32:
Ghana
# Qualificato ottavi 1:
Canada
# Qualificato ottavi 2:
Brasile
# Qualificato ottavi 3:
Paraguay
# Qualificato ottavi 4:
Marocco
# Qualificato ottavi 5:
Norvegia
# Qualificato ottavi 6:
Francia
# Qualificato ottavi 7:
Messico
# Qualificato ottavi 8:
Inghilterra
# Qualificato ottavi 9:
Belgio
# Qualificato ottavi 10:
USA
# Qualificato ottavi 11:
Spagna
# Qualificato ottavi 12:
Portogallo
# Qualificato ottavi 13:
Svizzera
# Qualificato ottavi 14:
Egitto
# Qualificato ottavi 15:
Argentina
# Qualificato ottavi 16:
Colombia
# Qualificato quarti 1:
Marocco
# Qualificato quarti 2:
Francia
# Qualificato quarti 3:
Norvegia
# Qualificato quarti 4:
Inghilterra
# Qualificato quarti 5:
Spagna
# Qualificato quarti 6:
Belgio
# Qualificato quarti 7:
Argentina
# Qualificato quarti 8:
Svizzera
# Qualificato semifinale 1:
Francia
# Qualificato semifinale 2:
Spagna
# Qualificato semifinale 3:
Inghilterra
# Qualificato semifinale 4:
Argentina
# Qualificato finale 1:
Spagna
# Qualificato finale 2:
Argentina
# Vincitore:
Spagna

#
# Le risposte sono suddivise in gruppi. Per ciascun gruppo esiste un set di risposte piu' o meno corrette
#
[equivalenza_risposte]
6::qualif. sedicesimi::0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31
5::qualif. ottavi::32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47
4::qualif. quarti::48,49,50,51,52,53,54,55
3::qualif. semifinali::56,57,58,59
2::qualif. finali::60,61
1::vincitore::62

#
# Qui viene indicato il punteggio da associare ad ogni risposta a seconda del gruppo in cui essa viene fornita
# Il formato aggiornato per il 2026 è: 
# Squadra::6,punti_sedicesimi;5,punti_ottavi;4,punti_quarti;3,punti_semi;2,punti_finale;1,punti_vincitore
#
# es:
# Brasile::                         # prima dell'inizio del torneo
# Brasile::6,0;5,0;4,0;3,0;2,0;1,0  # se il Brasile viene escluso già al primo turno
# Brasile::6,20;5,0                 # se il Brasile passa il primo turno ma esce al secondo, e non sono state giocate altre partite
# Brasile::6,20;5,0;4,0;3,0;2,0;1,0 # se il Brasile passa il primo turno ma esce al secondo, e sono state giocate tutte le partite
[punteggio_risposte]
#
# Girone A
#
Messico::6,20;5,20;4,0;3,0;2,0;1,0
Corea del Sud::6,0;5,0;4,0;3,0;2,0;1,0
Sudafrica::6,20;5,0;4,0;3,0;2,0;1,0
Repubblica Ceca::6,0;5,0;4,0;3,0;2,0;1,0
#
# Girone B
#
Canada::6,14;5,20;4,0;3,0;2,0;1,0
Svizzera::6,20;5,20;4,22;3,16;2,0;1,0
Qatar::6,0;5,0;4,0;3,0;2,0;1,0
Bosnia ed Erzegovina::6,6;5,0;4,0;3,0;2,0;1,0
#
# Girone C
#
Brasile::6,20;5,20;4,0;3,0;2,0;1,0
Marocco::6,20;5,11;4,40;3,0;2,0;1,0
Scozia::6,0;5,0;4,0;3,0;2,0;1,0
Haiti::6,0;5,0;4,0;3,0;2,0;1,0
#
# Girone D
#
USA::6,20;5,20;4,0;3,0;2,0;1,0
Australia::6,14;5,9;4,0;3,0;2,0;1,0
Paraguay::6,6;5,11;4,0;3,0;2,0;1,0
Turchia::6,0;5,0;4,0;3,0;2,0;1,0
#
# Girone E
#
Germania::6,20;5,9;4,0;3,0;2,0;1,0
Ecuador::6,0;5,0;4,0;3,0;2,0;1,0
Costa d'Avorio::6,20;5,0;4,0;3,0;2,0;1,0
Curaçao::6,0;5,0;4,0;3,0;2,0;1,0
#
# Girone F
#
Olanda::6,20;5,9;4,0;3,0;2,0;1,0
Giappone::6,20;5,0;4,0;3,0;2,0;1,0
Tunisia::6,0;5,0;4,0;3,0;2,0;1,0
Svezia::6,0;5,0;4,0;3,0;2,0;1,0
#
# Girone G
#
Belgio::6,20;5,16;4,40;3,0;2,0;1,0
Iran::6,0;5,0;4,0;3,0;2,0;1,0
Egitto::6,20;5,11;4,0;3,0;2,0;1,0
Nuova Zelanda::6,0;5,0;4,0;3,0;2,0;1,0
#
# Girone H
#
Spagna::6,20;5,20;4,40;3,80;2,160;1,256
Uruguay::6,0;5,0;4,0;3,0;2,0;1,0
Arabia Saudita::6,0;5,0;4,0;3,0;2,0;1,0
Capo Verde::6,20;5,4;4,0;3,0;2,0;1,0
#
# Girone I
#
Francia::6,20;5,20;4,40;3,80;2,0;1,0
Senegal::6,0;5,4;4,0;3,0;2,0;1,0
Norvegia::6,20;5,20;4,40;3,16;2,0;1,0
Iraq::6,0;5,0;4,0;3,0;2,0;1,0
#
# Girone J
#
Argentina::6,20;5,16;4,40;3,64;2,160;1,64
Austria::6,14;5,0;4,0;3,0;2,0;1,0
Algeria::6,6;5,0;4,0;3,0;2,0;1,0
Giordania::6,0;5,0;4,0;3,0;2,0;1,0
#
# Girone K
#
Portogallo::6,20;5,20;4,0;3,0;2,0;1,0
Colombia::6,20;5,20;4,18;3,0;2,0;1,0
Uzbekistan::6,0;5,0;4,0;3,0;2,0;1,0
Repubblica Democratica del Congo::6,0;5,0;4,0;3,0;2,0;1,0
#
# Girone L
#
Inghilterra::6,20;5,20;4,40;3,64;2,0;1,0
Croazia::6,20;5,0;4,0;3,0;2,0;1,0
Panama::6,0;5,0;4,0;3,0;2,0;1,0
Ghana::6,0;5,0;4,0;3,0;2,0;1,0
