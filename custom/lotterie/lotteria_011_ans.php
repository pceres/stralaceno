#
# Una riga per ogni risposta. La 1a riga contiene la risposta esatta alla prima domanda, ecc.
# Il numero di righe non commentate deve coincidere con le domande della lotteria.
# Se una partita non e' stata ancora disputata, inserire la risposta convenzionale "nd".
#

# Qualificato ottavi 1:
Italia
# Qualificato ottavi 2:
Galles
# Qualificato ottavi 3:
Belgio
# Qualificato ottavi 4:
Danimarca
# Qualificato ottavi 5:
Olanda
# Qualificato ottavi 6:
Austria
# Qualificato ottavi 7:
Inghilterra
# Qualificato ottavi 8:
Croazia
# Qualificato ottavi 9:
Svezia
# Qualificato ottavi 10:
Spagna
# Qualificato ottavi 11:
Francia
# Qualificato ottavi 12:
Germania
# Qualificato ottavi 13:
Portogallo
# Qualificato ottavi 14:
Repubblica Ceca
# Qualificato ottavi 15:
Svizzera
# Qualificato ottavi 16:
Ucraina
# Qualificato quarti 1:
Danimarca
# Qualificato quarti 2:
Italia
# Qualificato quarti 3:
Belgio
# Qualificato quarti 4:
Repubblica Ceca
# Qualificato quarti 5:
Spagna
# Qualificato quarti 6:
Svizzera
# Qualificato quarti 7:
Inghilterra
# Qualificato quarti 8:
Ucraina
# Qualificato semifinale 1:
Spagna
# Qualificato semifinale 2:
Italia
# Qualificato semifinale 3:
Danimarca
# Qualificato semifinale 4:
Inghilterra
# Qualificato finale 1:
Italia
# Qualificato finale 2:
Inghilterra
# Vincitore:
Italia

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
Turchia::5,0;4,0;3,0;2,0;1,0
Italia::5,20;4,16;3,40;2,44;1,88
Galles::5,14;4,0;3,0;2,0;1,0
Svizzera::5,6;4,11;3,18;2,0;1,0
Danimarca::5,14;4,20;3,40;2,16;1,0
Finlandia::5,3;4,0;3,0;2,0;1,0
Belgio::5,20;4,20;3,0;2,0;1,0
Russia::5,3;4,0;3,0;2,0;1,0
Olanda::5,20;4,0;3,0;2,0;1,0
Ucraina::5,0;4,16;3,0;2,0;1,0
Austria::5,20;4,4;3,0;2,0;1,0
Macedonia del Nord::5,0;4,0;3,0;2,0;1,0
Inghilterra::5,20;4,20;3,40;2,64;1,72
Croazia::5,10;4,4;3,0;2,0;1,0
Scozia::5,0;4,0;3,0;2,0;1,0
Repubblica Ceca::5,10;4,20;3,0;2,0;1,0
Spagna::5,20;4,16;3,22;2,36;1,0
Svezia::5,20;4,4;3,0;2,0;1,0
Polonia::5,0;4,0;3,0;2,0;1,0
Slovacchia::5,0;4,0;3,0;2,0;1,0
Ungheria::5,0;4,0;3,0;2,0;1,0
Portogallo::5,6;4,0;3,0;2,0;1,0
Francia::5,20;4,9;3,0;2,0;1,0
Germania::5,14;4,0;3,0;2,0;1,0
