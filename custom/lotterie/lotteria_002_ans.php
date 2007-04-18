#
# Una riga per ogni risposta. La 1a riga contiene la risposta esatta alla prima domanda, ecc.
# Il numero di righe non commentate deve coincidere con le domande della lotteria.
# Se una partita non e' stata ancora disputata, inserire la risposta convenzionale "nd".
#

# Qualificato quarti 1:
Roma
# Qualificato quarti 2:
Liverpool
# Qualificato quarti 3:
Chelsea
# Qualificato quarti 4:
Valencia
# Qualificato quarti 5:
Psv Eindhoven
# Qualificato quarti 6:
Bayern Monaco
# Qualificato quarti 7:
Milan
# Qualificato quarti 8:
Manchester United
# Qualificato semifinale 1:
Manchester United
# Qualificato semifinale 2:
Chelsea
# Qualificato semifinale 3:
Milan
# Qualificato semifinale 4:
Liverpool
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
4::qualif. quarti	::0,1,2,3,4,5,6,7
3::qualif. semifinali	::8,9,10,11
2::qualif. finali	::12,13
1::vincitore    	::14

#
# Qui viene indicato il punteggio da associare ad ogni risposta a seconda del gruppo in cui essa viene fornita
#
[punteggio_risposte]
#
Porto::4,0
Chelsea::4,4;3,4
Psv Eindhoven::4,4;3,0
Arsenal::4,0
Roma::4,4;3,0
Lione::4,0
Real Madrid::4,1
Bayern Monaco::4,3;3,0
Celtic Glasgow::4,0
Milan::4,4;3,4
Lilla::4,0
Manchester United::4,4;3,4
Barcellona::4,1
Liverpool::4,3;3,4
Inter::4,1
Valencia::4,3;3,0
