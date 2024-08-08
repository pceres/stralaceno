#
# Una riga per ogni risposta. La 1a riga contiene la risposta esatta alla prima domanda, ecc.
# Il numero di righe non commentate deve coincidere con le domande della lotteria.
# Se una partita non e' stata ancora disputata, inserire la risposta convenzionale "nd".
#

# Qualificato ottavi 1:
Germania
# Qualificato ottavi 2:
Spagna
# Qualificato ottavi 3:
Portogallo
# Qualificato ottavi 4:
Svizzera
# Qualificato ottavi 5:
Italia
# Qualificato ottavi 6:
Inghilterra
# Qualificato ottavi 7:
Austria
# Qualificato ottavi 8:
Francia
# Qualificato ottavi 9:
Danimarca
# Qualificato ottavi 10:
Romania
# Qualificato ottavi 11:
Belgio
# Qualificato ottavi 12:
Turchia
# Qualificato ottavi 13:
Slovacchia
# Qualificato ottavi 14:
Slovenia
# Qualificato ottavi 15:
Olanda
# Qualificato ottavi 16:
Georgia
# Qualificato quarti 1:
Svizzera
# Qualificato quarti 2:
Germania
# Qualificato quarti 3:
Inghilterra
# Qualificato quarti 4:
Spagna
# Qualificato quarti 5:
Francia
# Qualificato quarti 6:
Portogallo
# Qualificato quarti 7:
Olanda
# Qualificato quarti 8:
Turchia
# Qualificato semifinale 1:
Spagna
# Qualificato semifinale 2:
Francia
# Qualificato semifinale 3:
Inghilterra
# Qualificato semifinale 4:
Olanda
# Qualificato finale 1:
Spagna
# Qualificato finale 2:
Inghilterra
# Vincitore:
Spagna

#
# Le risposte sono suddivise in gruppi. Per ciascun gruppo esiste un set di risposte piu' o meno corrette
#
[equivalenza_risposte]
5::qualif. ottavi::0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15
4::qualif. quarti::16,17,18,19,20,21,22,23
3::qualif. semifinali::24,25,26,27
2::qualif. finali::28,29
1::vincitore::30

#
# Qui viene indicato il punteggio da associare ad ogni risposta a seconda del gruppo in cui essa viene fornita
#
# es:
# Brasile::                     # prima dell'inizio del torneo
# Brasile::5,0;4,0;3,0;2,0;1,0  # se il Brasile viene escluso già al primo turno
# Brasile::5,20;4,0             # se il Brasile passa il primo turno ma esce al secondo, e non sono state giocate altre partite
# Brasile::5,20;4,0;3,0;2,0;1,0 # se il Brasile passa il primo turno ma esce al secondo, e sono state giocate tutte le partite
[punteggio_risposte]
Germania::5,20;4,20;3,8;2,0;1,0
Ungheria::5,0;4,0;3,0;2,0;1,0
Scozia::5,0;4,0;3,0;2,0;1,0
Svizzera::5,20;4,20;3,18;2,0;1,0
Spagna::5,20;4,20;3,32;2,80;1,160
Albania::5,0;4,0;3,0;2,0;1,0
Croazia::5,0;4,0;3,0;2,0;1,0
Italia::5,20;4,0;3,0;2,0;1,0
Inghilterra::5,20;4,16;3,22;2,80;1,0
Danimarca::5,10;4,0;3,0;2,0;1,0
Slovenia::5,10;4,9;3,0;2,0;1,0
Serbia::5,0;4,0;3,0;2,0;1,0
Francia::5,20;4,20;3,22;2,0;1,0
Austria::5,20;4,0;3,0;2,0;1,0
Olanda::5,0;4,20;3,40;2,0;1,0
Polonia::5,0;4,0;3,0;2,0;1,0
Belgio::5,14;4,0;3,0;2,0;1,0
Romania::5,14;4,0;3,0;2,0;1,0
Slovacchia::5,6;4,4;3,0;2,0;1,0
Ucraina::5,6;4,0;3,0;2,0;1,0
Portogallo::5,20;4,11;3,18;2,0;1,0
Turchia::5,20;4,20;3,0;2,0;1,0
Repubblica Ceca::5,0;4,0;3,0;2,0;1,0
Georgia::5,0;4,0;3,0;2,0;1,0
