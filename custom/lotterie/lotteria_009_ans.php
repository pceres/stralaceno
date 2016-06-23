#
# Una riga per ogni risposta. La 1a riga contiene la risposta esatta alla prima domanda, ecc.
# Il numero di righe non commentate deve coincidere con le domande della lotteria.
# Se una partita non e' stata ancora disputata, inserire la risposta convenzionale "nd".
#

# Qualificato ottavi 1:
Francia
# Qualificato ottavi 2:
Svizzera
# Qualificato ottavi 3:
Galles
# Qualificato ottavi 4:
Inghilterra
# Qualificato ottavi 5:
Italia
# Qualificato ottavi 6:
Germania
# Qualificato ottavi 7:
Spagna
# Qualificato ottavi 8:
Polonia
# Qualificato ottavi 9:
Slovacchia
# Qualificato ottavi 10:
Croazia
# Qualificato ottavi 11:
Irlanda del Nord
# Qualificato ottavi 12:
Portogallo
# Qualificato ottavi 13:
Islanda
# Qualificato ottavi 14:
Ungheria
# Qualificato ottavi 15:
Belgio
# Qualificato ottavi 16:
Repubblica d'Irlanda
# Qualificato quarti 1:
nd
# Qualificato quarti 2:
nd
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
Francia::5,20;
Romania::5,0;4,0;3,0;2,0;1,0
Albania::5,0;4,0;3,0;2,0;1,0
Svizzera::5,20;
Inghilterra::5,20;
Russia::5,0;4,0;3,0;2,0;1,0
Galles::5,20;
Slovacchia::5,0
Germania::5,20;
Ucraina::5,0;4,0;3,0;2,0;1,0
Polonia::5,20;
Irlanda del Nord::5,0;
Spagna::5,20;
Repubblica Ceca::5,0;4,0;3,0;2,0;1,0
Turchia::5,0;4,0;3,0;2,0;1,0
Croazia::5,20;
Belgio::5,20;
Italia::5,20;
Repubblica d'Irlanda::5,0
Svezia::5,0;4,0;3,0;2,0;1,0
Portogallo::5,0;
Islanda::5,20;
Austria::5,0;4,0;3,0;2,0;1,0
Ungheria::5,20;
