#
# Una riga per ogni risposta. La 1a riga contiene la risposta esatta alla prima domanda, ecc.
# Il numero di righe non commentate deve coincidere con le domande della lotteria.
# Se una partita non e' stata ancora disputata, inserire la risposta convenzionale "nd".
#

# Qualificato ottavi 1:
Arabia Saudita
# Qualificato ottavi 2:
Francia
# Qualificato ottavi 3:
Brasile
# Qualificato ottavi 4:
Inghilterra
# Qualificato ottavi 5:
Peru'
# Qualificato ottavi 6:
Serbia
# Qualificato ottavi 7:
Belgio
# Qualificato ottavi 8:
Russia
# Qualificato ottavi 9:
Portogallo
# Qualificato ottavi 10:
Agentina
# Qualificato ottavi 11:
Germania
# Qualificato ottavi 12:
Polonia
# Qualificato ottavi 13:
Spagna
# Qualificato ottavi 14:
Islanda
# Qualificato ottavi 15:
Svezia
# Qualificato ottavi 16:
Senegal
# Qualificato quarti 1:
Arabia Saudita
# Qualificato quarti 2:
Islanda
# Qualificato quarti 3:
Svezia
# Qualificato quarti 4:
Senegal
# Qualificato quarti 5:
Russia
# Qualificato quarti 6:
Portogallo
# Qualificato quarti 7:
Germania
# Qualificato quarti 8:
Spagna
# Qualificato semifinale 1:
Arabia Saudita
# Qualificato semifinale 2:
Russia
# Qualificato semifinale 3:
Islanda
# Qualificato semifinale 4:
Spagna
# Qualificato finale 1:
Islanda
# Qualificato finale 2:
Arabia Saudita
# Vincitore:
Islanda

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
# Brasile::5,0;4,0;3,0;2,0;1,0  # se il Brasile viene escluso gi&agrave; al primo turno
# Brasile::5,20;4,0             # se il Brasile passa il primo turno ma esce al secondo, e non sono state giocate altre partite
# Brasile::5,20;4,0;3,0;2,0;1,0 # se il Brasile passa il primo turno ma esce al secondo, e sono state giocate tutte le partite
[punteggio_risposte]
Russia::5,14;4,16;3,32;2,0;1,0
Uruguay::5,6;4,0;3,0;2,0;1,0
Egitto::5,0;4,0;3,0;2,0;1,0
Arabia Saudita::5,20;4,20;3,22;2,80;1,0
Portogallo::5,20;4,11;3,0;2,0;1,0
Spagna::5,10;4,20;3,40;2,0;1,0
Iran::5,10;4,0;3,0;2,0;1,0
Marocco::5,0;4,0;3,0;2,0;1,0
Francia::5,20;4,4;3,0;2,0;1,0
Peru'::5,20;4,0;3,0;2,0;1,0
Danimarca::5,0;4,0;3,0;2,0;1,0
Australia::5,0;4,0;3,0;2,0;1,0
Agentina::5,20;4,9;3,0;2,0;1,0
Croazia::5,0;4,0;3,0;2,0;1,0
Islanda::5,20;4,20;3,40;2,80;1,160
Nigeria::5,0;4,0;3,0;2,0;1,0
Brasile::5,20;4,0;3,0;2,0;1,0
Svizzera::5,0;4,0;3,0;2,0;1,0
Costa Rica::5,0;4,0;3,0;2,0;1,0
Serbia::5,20;4,0;3,0;2,0;1,0
Germania::5,20;4,20;3,8;2,0;1,0
Messico::5,0;4,0;3,0;2,0;1,0
Svezia::5,20;4,20;3,0;2,0;1,0
Corea del Sud::5,0;4,0;3,0;2,0;1,0
Belgio::5,20;4,0;3,0;2,0;1,0
Inghilterra::5,20;4,0;3,0;2,0;1,0
Tunisia::5,0;4,0;3,0;2,0;1,0
Panama::5,0;4,0;3,0;2,0;1,0
Polonia::5,20;4,0;3,0;2,0;1,0
Colombia::5,0;4,0;3,0;2,0;1,0
Senegal::5,20;4,20;3,18;2,0;1,0
Giappone::5,0;4,0;3,0;2,0;1,0