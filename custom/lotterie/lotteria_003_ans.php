#
# Una riga per ogni risposta. La 1a riga contiene la risposta esatta alla prima domanda, ecc.
# Il numero di righe non commentate deve coincidere con le domande della lotteria.
# Se una partita non e' stata ancora disputata, inserire la risposta convenzionale "nd".
#

# Qualificato quarti 1:
Svizzera
# Qualificato quarti 2:
Portogallo
# Qualificato quarti 3:
Austria
# Qualificato quarti 4:
Germania
# Qualificato quarti 5:
Olanda
# Qualificato quarti 6:
Italia
# Qualificato quarti 7:
Russia
# Qualificato quarti 8:
Svezia
# Qualificato semifinale 1:
Svizzera
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
4::qualif. quarti	::0,1,2,3,4,5,6,7
3::qualif. semifinali	::8,9,10,11
2::qualif. finali	::12,13
1::vincitore    	::14

#
# Qui viene indicato il punteggio da associare ad ogni risposta a seconda del gruppo in cui essa viene fornita
#
# es: Croazia::4,0;3,4;2,0
[punteggio_risposte]
Svizzera::4,4;3,4
Repubblica Ceca::4,0
Portogallo::4,4
Turchia::4,0;3,0
Austria::4,4
Croazia::4,0
Germania::4,4
Polonia::4,0
Romania::4,0
Francia::4,0
Olanda::4,4
Italia::4,4
Spagna::4,0
Russia::4,4
Grecia::4,0
Svezia::4,4
